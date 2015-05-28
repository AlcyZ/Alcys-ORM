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

namespace Alcys\Core\Db\QueryBuilder\MySql;

use Alcys\Core\Db\Expression\ConditionInterface;
use Alcys\Core\Db\Expression\JoinInterface;
use Alcys\Core\Db\Expression\ReBuildableExpressionInterface;
use Alcys\Core\Db\QueryBuilder\SelectBuilder as AbstractBuilder;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class SelectBuilder
 * @Todo    Implement having logic. The HAVING clause can refer to aggregate functions, which the WHERE clause cannot:
 *        HAVING MAX(salary) > 10;
 * @package Alcys\Core\Db\QueryBuilder\MySql
 */
class SelectBuilder extends AbstractBuilder implements BuilderInterface
{
	/**
	 * @var string The formatted table name.
	 */
	private $tables;

	/**
	 * @var string The formatted column names.
	 */
	private $columns;

	/**
	 * @var string The formatted order by string or null.
	 */
	private $orderBy;

	/**
	 * @var string The formatted group by string or null.
	 */
	private $groupBy;

	/**
	 * @var string The formatted distinct by string or null.
	 */
	private $distinct;

	/**
	 * @var string The formatted having condition string or null.
	 */
	private $having;

	/**
	 * @var string The formatted condition by string or null.
	 */
	private $condition;

	/**
	 * @var string The formatted join expression string or null.
	 */
	private $join;

	/**
	 * @var string The formatted condition by string or null.
	 */
	private $limit;


	/**
	 * This method processing the statement and return a MySql query.
	 *
	 * The method will validate the statements properties and throws
	 * an exception, if they are invalid. Otherwise, the query will
	 * build and returned.
	 *
	 * @return string The well formatted MySql query.
	 * @throws \Exception
	 */
	public function process()
	{
		$this->_prepareTables($this->statement->getTable());
		$this->_prepareColumns($this->statement->getColumns());
		$this->_prepareOrderBy($this->statement->getOrderBy());
		$this->_prepareGroupBy($this->statement->getGroupBy());
		$this->_prepareDistinct($this->statement->isDistinct());
		$this->_prepareHaving($this->statement->getHaving());
		$this->_prepareCondition($this->statement->getCondition());
		$this->_prepareJoin($this->statement->getJoin());
		$this->_prepareLimit($this->statement->getLimit());

		$query = 'SELECT' . $this->distinct . $this->columns . 'FROM' . $this->tables . $this->join . $this->condition
		         . $this->groupBy . $this->having . $this->orderBy . $this->limit;

		return rtrim($query) . ';';
	}


	/**
	 * Fill the property tables in a well format for the process method.
	 *
	 * @param TableInterface[] $tablesArray The validated tables array.
	 *
	 * @throws \Exception Statements table method have to be invoked, otherwise the builder can not build a valid query.
	 */
	private function _prepareTables(array $tablesArray)
	{
		if(count($tablesArray) === 0)
		{
			throw new \Exception('"table"-method of the statement have to invoked before!');
		}

		$tables = ' ';
		foreach($tablesArray as $table)
		{
			$tables .= $table->getExpression() . ', ';
		}
		$tables       = substr($tables, 0, strlen($tables) - 2);
		$this->tables = $tables . ' ';
	}


	/**
	 * Fill the property columns in a well format for the process method.
	 *
	 * @param ColumnInterface[] $columnsArray The validated columns array.
	 *
	 * @throws \Exception
	 */
	private function _prepareColumns(array $columnsArray)
	{
		if(count($columnsArray) === 0)
		{
			throw new \Exception('"column"-method of the statement have to invoked before!');
		}
		$columns = ' ';
		foreach($columnsArray as $column)
		{
			$columns .= $column->getExpression() . ', ';
		}
		$columns       = substr($columns, 0, strlen($columns) - 2);
		$this->columns = $columns . ' ';
	}


	/**
	 * Fill the property orderBy in a well format for the process method.
	 *
	 * @param array $orderByArray Statements orderBy array.
	 */
	private function _prepareOrderBy(array $orderByArray)
	{
		if(count($orderByArray) === 1)
		{
			foreach($orderByArray as $orderMode => $column)
			{
				/** @var ColumnInterface $column */
				if($orderMode)
				{
					$this->orderBy = 'ORDER BY ' . $column->getColumnName() . ' ' . $orderMode . ' ';
				}
				else
				{
					$this->orderBy = 'ORDER BY ' . $column->getColumnName() . ' ';
				}
			}
		}
	}


	/**
	 * Fill the property groupBy in a well format for the process method.
	 *
	 * @param array $groupByArray Statement orderBy array.
	 */
	private function _prepareGroupBy(array $groupByArray)
	{
		if(count($groupByArray) === 1)
		{
			foreach($groupByArray as $orderMode => $column)
			{
				/** @var ColumnInterface $column */
				$this->groupBy = 'GROUP BY ' . $column->getColumnName() . ' ';
				if(is_string($orderMode))
				{
					$this->groupBy .= $orderMode . ' ';
				}
			}
		}
	}


	/**
	 * Fill the property distinct in a well format for the process method.
	 *
	 * @param bool $distinct
	 */
	private function _prepareDistinct($distinct)
	{
		if($distinct)
		{
			$this->distinct = ' DISTINCT';
		}
	}


	private function _prepareHaving()
	{
	}


	/**
	 * Fill the property condition in a well format for the process method.
	 *
	 * @param ConditionInterface $condition Condition value object.
	 */
	private function _prepareCondition(ConditionInterface $condition = null)
	{
		$this->condition = $this->expressionBuilderFactory->create($condition)->build();
	}


	/**
	 * Fill the property join in a well format for the process method.
	 *
	 * @param JoinInterface[]|ReBuildableExpressionInterface[] $joinArray Statements value object join array.
	 */
	private function _prepareJoin(array $joinArray)
	{
		if(count($joinArray) > 0)
		{
			$factory = $this->expressionBuilderFactory->create($joinArray[0]);
			foreach($joinArray as $number => $join)
			{
				if($number === 0)
				{
					$this->join = $factory->build();
				}
				else
				{
					$this->join .= $factory->rebuild($join);
				}
			}
		}
	}


	/**
	 * Fill the property limit in a well format for the process method.
	 *
	 * @param array $limitArray Statements value object limit array.
	 */
	private function _prepareLimit(array $limitArray)
	{
		if(count($limitArray) === 1)
		{
			foreach($limitArray as $begin => $amount)
			{
				$this->limit = 'LIMIT ' . $begin . ', ' . $amount . ' ';
			}
		}
	}
}