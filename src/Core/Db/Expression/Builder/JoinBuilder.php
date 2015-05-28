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

namespace Alcys\Core\Db\Expression\Builder;

use Alcys\Core\Db\Expression\JoinInterface;
use Alcys\Core\Db\Expression\ReBuildableExpressionInterface;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class JoinBuilder
 * @package Alcys\Core\Db\Expression
 */
class JoinBuilder implements ExpressionBuilderInterface, MultipleExpressionBuilderInterface
{
	/**
	 * @var array The join array, get from: Alcys\Core\Db\Expression\Join::getJoinArray().
	 */
	private $joinArray;

	/**
	 * @var string Validated way string of the join expression.
	 */
	private $way;

	/**
	 * @var string Validated tables string of the join expression.
	 */
	private $tables;

	/**
	 * @var string Validated condition type of the join expression.
	 */
	private $conditionType;

	/**
	 * @var string Validated condition string of the join expression.
	 */
	private $condition;


	/**
	 * Initialization of the join builder.
	 *
	 * @param JoinInterface $join
	 */
	public function __construct(JoinInterface $join)
	{
		$this->joinArray = $join->getJoinArray();
	}


	/**
	 * Build the join expression string.
	 * Processing the passed join expression and return it in form of a string.
	 *
	 * @see JoinBuilder::_checkJoinArray
	 * @see JoinBuilder::_prepareJoinTable
	 * @see JoinBuilder::_prepareJoinCondition
	 *
	 * @return string The join expression as a string.
	 * @throws \Exception When the join expression has an invalid format.
	 */
	public function build()
	{
		$this->_checkJoinArray();
		$this->way = ($this->joinArray['way']) ?
			strtoupper(str_replace(':', ' ', $this->joinArray['way'])) . ' ' : null;
		$this->_prepareJoinTable($this->joinArray['tables']);
		$this->conditionType = strtoupper($this->joinArray['conditionType']) . ' ';

		if($this->joinArray['conditionType'] !== 'natural')
		{
			$this->_prepareJoinCondition($this->joinArray['condition']);
			$expression = $this->way . 'JOIN' . $this->tables . $this->conditionType . $this->condition;
		}
		else
		{
			$expression = $this->conditionType . $this->way . 'JOIN' . rtrim($this->tables);
		}

		return $expression . ' ';
	}


	/**
	 * Build the join expression string.
	 * Processing the passed join expression and return it in form of a string.
	 *
	 * @see JoinBuilder::_checkJoinArray
	 * @see JoinBuilder::_prepareJoinTable
	 * @see JoinBuilder::_prepareJoinCondition
	 *
	 * @param ReBuildableExpressionInterface $join
	 *
	 * @return string The join expression as a string.
	 * @throws \Exception When the join expression has an invalid format.
	 *
	 * @return string
	 */
	public function rebuild(ReBuildableExpressionInterface $join)
	{
		$this->joinArray = $join->getExpressionArray();

		return $this->build();
	}


	/**
	 * Compare the join array with the expressions default join array and throw an exception if they are the same.
	 *
	 * @throws \Exception
	 */
	private function _checkJoinArray()
	{
		$defaultJoinArray = array('way' => null, 'tables' => null, 'conditionType' => null, 'condition' => null);
		if($this->joinArray === $defaultJoinArray)
		{
			throw new \Exception('one of the methods: inner(), natural(), left[Outer](), right[Outer] must called before!');
		}
	}


	/**
	 * Validate the tables array to a string.
	 *
	 * @param TableInterface[] $tables
	 */
	private function _prepareJoinTable(array $tables)
	{
		$this->tables = ' ';
		foreach($tables as $tableObj)
		{
			$this->tables .= $tableObj->getExpression() . ', ';
		}
		$this->tables = rtrim(rtrim($this->tables), ',') . ' ';
	}


	/**
	 * Validate the columns array to a string.
	 *
	 * @param ColumnInterface[] $condition
	 *
	 * @return string
	 */
	private function _prepareJoinCondition(array $condition)
	{
		if(count($condition) === 1)
		{
			return $this->condition = '(' . $condition[0]->getColumnName() . ')';
		}

		return $this->condition =
			$condition[0]->getTableName() . '.' . $condition[0]->getColumnName() . ' = ' . $condition[1]->getTableName()
			. '.' . $condition[1]->getColumnName();
	}
}