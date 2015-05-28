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

namespace Alcys\Core\Db\Statement;

use Alcys\Core\Db\Expression\ConditionInterface;
use Alcys\Core\Db\Expression\ExpressionInterface;
use Alcys\Core\Db\Expression\JoinInterface;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\OrderModeEnumInterface;
use Alcys\Core\Db\References\TableInterface;
use Alcys\Core\Types\Numeric;

/**
 * Class Select
 * @todo    for where and having method condition interface
 * @package Alcys\Core\Db\Statement
 */
class Select implements StatementInterface, SelectInterface, ConditionStatementInterface
{
	/**
	 * @var TableInterface[] Array of TableInterface elements
	 */
	private $table = array();

	/**
	 * @var ColumnInterface[]
	 */
	private $columns = array();

	/**
	 * @var array
	 */
	private $orderBy = array();

	/**
	 * @var array
	 */
	private $limit = array();

	/**
	 * @var array
	 */
	private $groupBy = array();

	/**
	 * @var bool
	 */
	private $distinct = false;

	/**
	 * @var ExpressionInterface
	 */
	private $having;

	/**
	 * @var ExpressionInterface
	 */
	private $condition;

	/**
	 * @var JoinInterface[]
	 */
	private $join = array();


	/**
	 * Add validated tables to the statement.
	 *
	 * The method fill the table array in the correct format for the query builder.
	 * For each call of the method, the tables will sorted in the same order like the invokes.
	 *
	 * @param TableInterface $table The validated table value.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table(TableInterface $table)
	{
		$this->table[] = $table;

		return $this;
	}


	/**
	 * Add validated columns to the statement.
	 *
	 * The method fill the columns array in the correct format for the query builder.
	 * For each call of the method, the columns will sorted in the same order like the invokes.
	 *
	 * @param ColumnInterface $column The validated columns value.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column(ColumnInterface $column)
	{
		$this->columns[] = $column;

		return $this;
	}


	/**
	 * Add an order by expression to the statement.
	 *
	 * The method will fill the array in the correct way to be valid for the query builder.
	 * To avoid errors, the method should only invoked one time! (working at a solution)
	 *
	 * @param ColumnInterface        $column    The column which should sorted.
	 * @param OrderModeEnumInterface $orderMode The expected mode. Whether 'ASC' or 'DESC'.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function orderBy(ColumnInterface $column, OrderModeEnumInterface $orderMode = null)
	{
		$this->orderBy = array();
		$this->orderBy = ($orderMode) ? array((string)$orderMode => $column) : array('ASC' => $column);

		return $this;
	}


	/**
	 * Add a limit expression to the statement.
	 *
	 * The method will fill the array in the correct way to be valid for the query builder.
	 *
	 * @param Numeric $beginAmount The first entry that get selected or the amount of
	 *                             returned entries, when the second argument is null.
	 * @param Numeric $amount      The amount of entries that should returned.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function limit(Numeric $beginAmount, Numeric $amount = null)
	{
		$this->limit = ($amount) ? array((string)$beginAmount => (string)$amount) : array('0' => (string)$beginAmount);

		return $this;
	}


	/**
	 * Add an group by expression to the statement.
	 *
	 * The method will fill the array in the correct way to be valid for the query builder.
	 * To avoid errors, the method should only invoked one time! (working at a solution)
	 *
	 * @param ColumnInterface        $column    The column which should grouped.
	 * @param OrderModeEnumInterface $orderMode The expected mode. Whether 'ASC', 'DESC' or null(optional).
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function groupBy(ColumnInterface $column, OrderModeEnumInterface $orderMode = null)
	{
		$this->groupBy = array();
		if($orderMode)
		{
			$this->groupBy[(string)$orderMode] = $column;
		}
		else
		{
			$this->groupBy[] = $column;
		}

		return $this;
	}


	/**
	 * Add a distinct expression to the statement.
	 *
	 * The property distinct will set to true and the builder place a distinct expression in the query.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function distinct()
	{
		$this->distinct = true;

		return $this;
	}


	/**
	 * Add a having expression to the statement.
	 *
	 * The query builder have the functionality to build a valid
	 * formatted condition, but at least one condition method must
	 * be invoked. Otherwise, no having condition will added.
	 *
	 * @param ExpressionInterface $condition
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function having(ExpressionInterface $condition)
	{
		$this->having = $condition;

		return $this;
	}


	/**
	 * Add a where expression to the statement.
	 *
	 * The query builder have the functionality to build a valid
	 * formatted condition, but at least one condition method must
	 * be invoked. Otherwise, no condition will added.
	 *
	 * @param ConditionInterface $condition Condition value object in which the condition expressions are buffered.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function where(ConditionInterface $condition)
	{
		$this->condition = $condition;

		return $this;
	}


	/**
	 * Add a join expression to the statement.
	 *
	 * The query builder have the functionality to build a valid
	 * formatted join statement, but at least one join method
	 * be invoked. Otherwise, no expression will added.
	 *
	 * @param JoinInterface $join Join value object in which the condition expressions are buffered.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function join(JoinInterface $join)
	{
		$this->join[] = $join;

		return $this;
	}


	/**
	 * @return TableInterface[]
	 */
	public function getTable()
	{
		return $this->table;
	}


	/**
	 * @return ColumnInterface[]
	 */
	public function getColumns()
	{
		return $this->columns;
	}


	/**
	 * @return array
	 */
	public function getOrderBy()
	{
		return $this->orderBy;
	}


	/**
	 * @return array
	 */
	public function getLimit()
	{
		return $this->limit;
	}


	/**
	 * @return array
	 */
	public function getGroupBy()
	{
		return $this->groupBy;
	}


	/**
	 * @return boolean
	 */
	public function isDistinct()
	{
		return $this->distinct;
	}


	/**
	 * @return ExpressionInterface
	 */
	public function getHaving()
	{
		return $this->having;
	}


	/**
	 * @return ConditionInterface
	 */
	public function getCondition()
	{
		return $this->condition;
	}


	/**
	 * @return JoinInterface[]
	 */
	public function getJoin()
	{
		return $this->join;
	}
}
