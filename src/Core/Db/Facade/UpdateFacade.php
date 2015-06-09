<?php
/**
 * Copyright (c) 2015 Tobias Schindler
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Alcys\Core\Db\Facade;

use Alcys\Core\Db\Factory\DbFactoryInterface;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\OrderModeEnumInterface;
use Alcys\Core\Db\References\TableInterface;
use Alcys\Core\Db\Statement\ConditionStatementInterface;
use Alcys\Core\Db\Statement\StatementInterface;
use Alcys\Core\Db\Statement\UpdateInterface;
use Alcys\Core\Types\Numeric;

/**
 * Class UpdateFacade
 * @Todo    Type in value method as sec arg, default is value, e.g.: $update->value('column_name', 'column')
 * @package Alcys\Core\Db\Facade
 */
class UpdateFacade implements UpdateFacadeInterface, WhereConditionFacadeInterface
{
	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var UpdateInterface|StatementInterface|ConditionStatementInterface
	 */
	private $update;

	/**
	 * @var DbFactoryInterface
	 */
	private $factory;

	/**
	 * @var array
	 */
	private $columnsArray = array();


	/**
	 * Initialize the update facade.
	 * Set all required properties to update entries in the database.
	 *
	 * @param \PDO               $pdo     PDO connection.
	 * @param UpdateInterface    $update  Select statement.
	 * @param DbFactoryInterface $factory Factory to create several objects.
	 * @param string             $table   Name of the table from which should select (tables can get extended).
	 */
	public function __construct(\PDO $pdo, UpdateInterface $update, DbFactoryInterface $factory, $table)
	{
		$this->pdo     = $pdo;
		$this->update  = $update;
		$this->factory = $factory;

		/** @var TableInterface $tableObj */
		$tableObj = $this->factory->references('Table', $table);
		$this->update->table($tableObj);
	}


	/**
	 * Execute the query and update the expected entries in the database.
	 *
	 * @throws \Exception When an error occur while updating.
	 */
	public function execute()
	{
		$query  = $this->factory->builder($this->update)->process();
		$result = $this->pdo->query($query);
		if(!$result)
		{
			throw new \Exception('An error while updating is occurred');
		}
	}


	/**
	 * Set a single column to the query which should get an update.
	 * Afterwards, you have to call the UpdateFacade::value() method!!!
	 * This process can done multiple times to update more then one column.
	 *
	 * @param string $column Name of the column which should updated.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column($column)
	{
		/** @var ColumnInterface $columnObj */
		$columnObj = $this->factory->references('Column', $column);
		$this->update->column($columnObj);

		return $this;
	}


	/**
	 * Set the value for the column, which is passed as argument in the UpdateFacade::column() method.
	 * You have to call this method immediate after the column method.
	 * Before calling this method again, the column() has to be invoked.
	 *
	 * @param string $value The value which should set.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When UpdateFacade::column() was not called before.
	 */
	public function value($value)
	{
		$valueObj = $this->factory->references('Value', $value);
		$this->update->value($valueObj);

		return $this;
	}


	/**
	 * Set multiple columns to the query which should get an update.
	 * Afterwards, you have to call the UpdateFacade::values() method!!!
	 *
	 * @param array $columns An usual array with the column names as elements.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function columns(array $columns)
	{
		$this->columnsArray = $columns;

		return $this;
	}


	/**
	 * Set values for the columns, which are passed as array argument in the UpdateFacade::columns() method.
	 * You have to call this method immediate after the columns method.
	 * Before calling this method again, the columns() has to be invoked.
	 *
	 * @param array $values An usual array with the values as elements.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When the UpdateFacade::columns() method was not called before.
	 */
	public function values(array $values)
	{
		$columnsArraySize = count($this->columnsArray);
		if($columnsArraySize === 0 || $columnsArraySize !== count($values))
		{
			throw new \Exception('Columns method must called before and both passed array require the same amount of elements');
		}
		foreach($this->columnsArray as $number => $column)
		{
			/** @var ColumnInterface $columnObj */
			$columnObj = $this->factory->references('Column', $column);
			$valueObj  = $this->factory->references('Value', $values[$number]);
			$this->update->column($columnObj)->value($valueObj);
		}

		return $this;
	}


	/**
	 * Add a where expression to the query.
	 *
	 * @param ConditionFacadeInterface $condition The configured condition object, get by conditionBuilder method.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function where(ConditionFacadeInterface $condition)
	{
		$this->update->where($condition->getCondition());

		return $this;
	}


	/**
	 * Add an order by expression to the query.
	 *
	 * @param string      $column    Column Name.
	 * @param string|null $orderMode Order mode, whether desc or asc.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function orderBy($column, $orderMode = null)
	{
		/** @var ColumnInterface $columnObj */
		$columnObj = $this->factory->references('Column', $column);

		/** @var OrderModeEnumInterface $orderModeObj */
		$orderModeObj = ($orderMode) ? $this->factory->references('OrderModeEnum', $orderMode) : null;

		$this->update->orderBy($columnObj, $orderModeObj);

		return $this;
	}


	/**
	 * Add a limit expression to the query.
	 *
	 * @param int      $beginAmount Amount if only one arg isset, if two, the first entry which will used.
	 * @param int|null $amount      (Optional) Amount of entries which.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function limit($beginAmount, $amount = null)
	{
		$beginObj  = new Numeric((int)$beginAmount);
		$amountObj = ($amount) ? new Numeric((int)$amount) : null;

		$this->update->limit($beginObj, $amountObj);

		return $this;
	}


	/**
	 * Return an condition facade to create where conditions for the query.
	 *
	 * @return ConditionFacade Instance of conditionFacade.
	 */
	public function condition()
	{
		$condition = $this->factory->expression('Condition');

		return $this->factory->expressionFacade('Condition', $condition);
	}
}