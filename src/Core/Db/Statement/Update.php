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
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\References\OrderModeEnumInterface;
use Alcys\Core\Db\References\TableInterface;
use Alcys\Core\Types\Numeric;

/**
 * Class Update
 * @package Alcys\Core\Db\Statement
 */
class Update implements StatementInterface, UpdateInterface, ConditionStatementInterface
{
	/**
	 * @var TableInterface The name of the table.
	 */
	private $table;

	/**
	 * @var array Expected format: array(array('column' => $column, 'value' => $value), array('column' => $column, 'value' => $value))
	 */
	private $values = array();

	/**
	 * @var ConditionInterface|null The condition to just update the expected columns.
	 */
	private $condition;

	/**
	 * @var array Order by array in a valid format.
	 */
	private $orderBy = array();

	/**
	 * @var array Limit array in a valid format.
	 */
	private $limit = array();

	private $column;


	/**
	 * Add a table object to the statement.
	 *
	 * The method fill the table property and multiple calls override the old value.
	 *
	 * @param TableInterface $table The validated table value.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table(TableInterface $table)
	{
		$this->table = $table;

		return $this;
	}


	/**
	 * Add a column that should updated to the query.
	 * Afterwards, you should call the value method.
	 *
	 * @param ColumnInterface $column Object of type ColumnInterface.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column(ColumnInterface $column)
	{
		$this->column = $column;

		return $this;
	}


	/**
	 * Add a value to the column which was set before.
	 *
	 * @param ReferencesInterface $value The expected value as object of type ReferenceInterface.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When column method was not invoked before.
	 */
	public function value(ReferencesInterface $value)
	{
		if(!$this->column)
		{
			throw new \Exception('The column method must called before using this (value) method!');
		}
		$this->values[] = array('column' => $this->column, 'value' => $value);
		unset($this->column);

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
	 * @return TableInterface
	 */
	public function getTable()
	{
		return $this->table;
	}


	/**
	 * @return array
	 */
	public function getValues()
	{
		return $this->values;
	}


	/**
	 * @return ConditionInterface|null
	 */
	public function getCondition()
	{
		return $this->condition;
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
}