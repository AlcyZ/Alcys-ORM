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

use Alcys\Core\Db\Expression\JoinInterface;
use Alcys\Core\Db\Factory\DbFactoryInterface;
use Alcys\Core\Db\References\MySql\Column;
use Alcys\Core\Db\References\MySql\Table;
use Alcys\Core\Db\Statement\ConditionStatementInterface;
use Alcys\Core\Db\Statement\SelectInterface;
use Alcys\Core\Db\Statement\StatementInterface;
use Alcys\Core\Types\Numeric;

/**
 * Class SelectFacade
 * @Todo    Implement functionality that where and join method can handle arrays.
 * @Todo    Use interface at where method as type hint!
 * @package Alcys\Core\Db\Facade
 */
class SelectFacade implements SelectFacadeInterface, WhereConditionFacadeInterface
{
	/**
	 * Select statement which store information about the query.
	 *
	 * @var StatementInterface|SelectInterface|ConditionStatementInterface
	 */
	private $select;

	/**
	 * @var DbFactoryInterface Factory to create several objects.
	 */
	private $factory;

	/**
	 * @var \PDO
	 */
	private $pdo;


	/**
	 * Initialize the select facade.
	 * Set all required properties to fetch entries from the database.
	 *
	 * @param \PDO               $pdo        PDO connection.
	 * @param SelectInterface    $select     Select statement.
	 * @param DbFactoryInterface $factory    Factory to create several objects.
	 * @param string             $tableName  Name of the table from which should select (tables can get extended).
	 * @param string|null        $tableAlias (Optional) Name of the table alias
	 */
	public function __construct(\PDO $pdo, SelectInterface $select, DbFactoryInterface $factory, $tableName,
								$tableAlias = null)
	{
		$this->pdo     = $pdo;
		$this->select  = $select;
		$this->factory = $factory;

		/** @var Table $table */
		$table = $this->factory->references('Table', $tableName, $tableAlias);
		$this->select->table($table);
	}


	/**
	 * Fetch data from the database.
	 * The facade methods configure the query and use it. If no columns method
	 * is invoked before, a wildcard (*) will set for the columns.
	 *
	 * The entries will returned in an associative array.
	 *
	 * @return array
	 */
	public function fetch()
	{
		if(count($this->select->getColumns()) === 0)
		{
			/** @var Column $column */
			$column = $this->factory->references('Column', '*');
			$this->select->column($column);
		}
		$query    = $this->factory->builder($this->select)->process();
		$queryObj = $this->pdo->query($query);
		if($queryObj)
		{
			return $queryObj->fetchAll(\PDO::FETCH_ASSOC);
		}

		return array();
	}


	/**
	 * Add a table to the query.
	 *
	 * @param string      $tableName The name of the table.
	 * @param string|null $alias     (Optional) An alias name, if exists.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table($tableName, $alias = null)
	{
		$table = $this->factory->references('Table', $tableName, $alias);
		$this->select->table($table);

		return $this;
	}


	/**
	 * Add a column to the query. When this method would not called, a * wildcard will set.
	 *
	 * @param string      $name     Name of the column.
	 * @param string|null $tableRef (Optional) Name of the referred table, if exists.
	 * @param string|null $alias    (Optional) An alias name, if exists.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column($name, $tableRef = null, $alias = null)
	{
		$column = $this->factory->references('Column', $name, $tableRef, $alias);
		$this->select->column($column);

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
		$columnObj    = $this->factory->references('Column', $column);
		$orderModeObj = ($orderMode) ? $this->factory->references('OrderModeEnum', $orderMode) : null;
		$this->select->orderBy($columnObj, $orderModeObj);

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

		$this->select->limit($beginObj, $amountObj);

		return $this;
	}


	/**
	 * Add a group by expression to the query.
	 *
	 * @param string      $column
	 * @param string|null $orderMode
	 *
	 * @return $this The same instance to concatenate methods
	 */
	public function groupBy($column, $orderMode = null)
	{
		$columnObj    = $this->factory->references('Column', $column);
		$orderModeObj = ($orderMode) ? $this->factory->references('OrderModeEnum', $orderMode) : null;
		$this->select->groupBy($columnObj, $orderModeObj);

		return $this;
	}


	/**
	 * Add a distinct expression to the query.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function distinct()
	{
		$this->select->distinct();

		return $this;
	}


	/**
	 * @throws \Exception This function will implemented in a future version.
	 */
	public function having()
	{
		throw new \Exception('having functionality is not implemented yet, please don\'t use it!');
	}


	/**
	 * Add a where expression to the query.
	 *
	 * @param ConditionFacade $condition The configured condition object, get by conditionBuilder method.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function where(ConditionFacade $condition)
	{
		$this->select->where($condition->getCondition());

		return $this;
	}


	/**
	 * Add a join expression to the query.
	 *
	 * @param JoinInterface $join The configured join object, get by joinBuilder method.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function join(JoinInterface $join)
	{
		$this->select->join($join);

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


	/**
	 * Return a join object which could passed through the join method.
	 *
	 * @return JoinInterface
	 */
	public function joinBuilder()
	{
		return $this->factory->expression('Join');
	}

//	private function _getConditionFromArray(array $conditionArray)
//	{
//		if(array_key_exists('type', $conditionArray) && array_key_exists('value', $conditionArray))
//		{
//			$compareType = $conditionArray['type'];
//			
//		}
//	}
}