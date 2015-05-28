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

use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\Expression\ExpressionInterface;
use Alcys\Core\Db\References\OrderModeEnumInterface;
use Alcys\Core\Db\Expression\JoinInterface;

/**
 * Interface SelectInterface
 * @package Alcys\Core\Db\Statement
 */
interface SelectInterface
{
	/**
	 * Add validated columns to the statement.
	 *
	 * @param ColumnInterface $column The validated columns value.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column(ColumnInterface $column);


	/**
	 * Add an group by expression to the statement.
	 *
	 * @param ColumnInterface        $column    The column which should grouped.
	 * @param OrderModeEnumInterface $orderMode The expected mode. Whether 'ASC', 'DESC' or null(optional).
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function groupBy(ColumnInterface $column, OrderModeEnumInterface $orderMode = null);


	/**
	 * Add a distinct expression to the statement.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function distinct();


	/**
	 * Add a having expression to the statement.
	 *
	 * @param ExpressionInterface $condition
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function having(ExpressionInterface $condition);


	/**
	 * Add a join expression to the statement.
	 *
	 * @param JoinInterface $join Join value object in which the condition expressions are buffered.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function join(JoinInterface $join);


	/**
	 * @return ColumnInterface[]
	 */
	public function getColumns();


	/**
	 * @return array
	 */
	public function getGroupBy();


	/**
	 * @return boolean
	 */
	public function isDistinct();


	/**
	 * @return ExpressionInterface
	 */
	public function getHaving();


	/**
	 * @return JoinInterface[]
	 */
	public function getJoin();
}