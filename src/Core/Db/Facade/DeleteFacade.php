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
use Alcys\Core\Db\Statement\DeleteInterface;
use Alcys\Core\Types\Numeric;

/**
 * Class DeleteFacade
 * @package Alcys\Core\Db\Facade
 */
class DeleteFacade implements DeleteFacadeInterface, WhereConditionFacadeInterface
{
	/**
	 * @var \PDO A php data object to have access and interact with a database.
	 */
	private $pdo;

	/**
	 * @var DeleteInterface The statement object to configure the query.
	 */
	private $statement;

	/**
	 * @var DbFactoryInterface Factory to create reference and other required objects.
	 */
	private $factory;


	/**
	 * Initialize the update facade.
	 * Set all required properties to update entries in the database.
	 *
	 * @param \PDO               $pdo     PDO connection.
	 * @param DeleteInterface    $delete  Select statement.
	 * @param DbFactoryInterface $factory Factory to create several objects.
	 * @param string             $table   Name of the table from which should select (tables can get extended).
	 */
	public function __construct(\PDO $pdo, DeleteInterface $delete, DbFactoryInterface $factory, $table)
	{
		$this->pdo       = $pdo;
		$this->statement = $delete;
		$this->factory   = $factory;

		/** @var TableInterface $tableObj */
		$tableObj = $this->factory->references('Table', $table);
		$this->statement->table($tableObj);
	}


	/**
	 * Execute the query and delete the expected entries from database.
	 *
	 * @throws \Exception When an error occur while the deletion.
	 */
	public function execute()
	{
		$query  = $this->factory->builder($this->statement)->process();
		$result = $this->pdo->query($query);
		if(!$result)
		{
			throw new \Exception('An error is while the deletion occurred!');
		}
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
		$this->statement->where($condition->getCondition());

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
		$this->statement->orderBy($columnObj, $orderModeObj);

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

		$this->statement->limit($beginObj, $amountObj);

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