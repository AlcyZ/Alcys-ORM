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
use Alcys\Core\Db\References\OrderModeEnumInterface;
use Alcys\Core\Db\References\TableInterface;
use Alcys\Core\Types\Numeric;

/**
 * Interface DeleteInterface
 * @Todo    Add PHPDocs.
 * @package Alcys\Core\Db\Statement
 */
interface DeleteInterface
{
	/**
	 * Add a validated table to the statement.
	 *
	 * The method fill the table property and multiple calls override the old value.
	 *
	 * @param TableInterface $table The validated table value.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table(TableInterface $table);


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
	public function where(ConditionInterface $condition);


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
	public function orderBy(ColumnInterface $column, OrderModeEnumInterface $orderMode = null);


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
	public function limit(Numeric $beginAmount, Numeric $amount = null);
}