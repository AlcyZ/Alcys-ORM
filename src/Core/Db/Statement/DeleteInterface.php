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
	 * @param TableInterface $table
	 *
	 * @return $this
	 */
	public function table(TableInterface $table);


	/**
	 * @param ConditionInterface $condition
	 *
	 * @return $this
	 */
	public function where(ConditionInterface $condition);


	/**
	 * @param ColumnInterface        $column
	 * @param OrderModeEnumInterface $orderMode
	 *
	 * @return $this
	 */
	public function orderBy(ColumnInterface $column, OrderModeEnumInterface $orderMode = null);


	/**
	 * @param Numeric $beginAmount
	 * @param Numeric $amount
	 *
	 * @return $this
	 */
	public function limit(Numeric $beginAmount, Numeric $amount = null);
}