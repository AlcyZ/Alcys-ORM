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
use Alcys\Core\Db\QueryBuilder\DeleteBuilder as AbstractBuilder;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class DeleteBuilder
 * @package Alcys\Core\Db\QueryBuilder\MySql
 */
class DeleteBuilder extends AbstractBuilder implements BuilderInterface
{
	/**
	 * @var string The formatted table name.
	 */
	private $table;

	/**
	 * @var string The formatted condition string or null.
	 */
	private $condition;

	/**
	 * @var string The formatted order by string or null.
	 */
	private $orderBy;

	/**
	 * @var string The formatted limit string or null.
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
		$this->_prepareTable($this->statement->getTable());
		$this->_prepareCondition($this->statement->getCondition());
		$this->_prepareOrderBy($this->statement->getOrderBy());
		$this->_prepareLimit($this->statement->getLimit());

		return rtrim('DELETE FROM' . $this->table . $this->condition . $this->orderBy . $this->limit) . ';';
	}


	/**
	 * Fill the property table in a well format for the process method.
	 *
	 * @param TableInterface $table The validated table name.
	 *
	 * @throws \Exception Statements table method have to be invoked, otherwise the builder can not build a valid query.
	 */
	private function _prepareTable(TableInterface $table)
	{
		$this->table = ' ' . $table->getTableName() . ' ';
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
	 * Fill the property orderBy in a well format for the process method.
	 *
	 * @param ColumnInterface[] $orderByArray Statements orderBy array.
	 */
	private function _prepareOrderBy(array $orderByArray)
	{
		if(count($orderByArray) === 1)
		{
			foreach($orderByArray as $orderMode => $column)
			{
				$this->orderBy = 'ORDER BY ' . $column->getColumnName() . ' ' . $orderMode . ' ';
			}
		}
	}


	/**
	 * Fill the property limit in a well format for the process method.
	 *
	 * @param array $limitArray Statement limit array.
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