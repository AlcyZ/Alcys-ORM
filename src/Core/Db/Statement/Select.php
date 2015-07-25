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
	 * @inheritdoc
	 */
	public function table(TableInterface $table)
	{
		$this->table[] = $table;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function column(ColumnInterface $column)
	{
		$this->columns[] = $column;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function orderBy(ColumnInterface $column, OrderModeEnumInterface $orderMode = null)
	{
		$this->orderBy = array();
		$this->orderBy = ($orderMode) ? array((string)$orderMode => $column) : array('ASC' => $column);

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function limit(Numeric $beginAmount, Numeric $amount = null)
	{
		$this->limit = ($amount) ? array((string)$beginAmount => (string)$amount) : array('0' => (string)$beginAmount);

		return $this;
	}


	/**
	 * @inheritdoc
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
	 * @inheritdoc
	 */
	public function distinct()
	{
		$this->distinct = true;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function having(ExpressionInterface $condition)
	{
		$this->having = $condition;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function where(ConditionInterface $condition)
	{
		$this->condition = $condition;

		return $this;
	}


	/**
	 * @inheritdoc
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
