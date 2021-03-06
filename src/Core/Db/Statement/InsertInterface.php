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
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Interface ColumnInterface
 * @package Alcys\Core\Db\Statement
 */
interface InsertInterface
{
	/**
	 * Add a table to the statement.
	 *
	 * @param TableInterface $table
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table(TableInterface $table);


	/**
	 * Add columns to the statement.
	 *
	 * @param ColumnInterface[] $columnsArray
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function columns(array $columnsArray);


	/**
	 * Add values to the statement.
	 *
	 * @param ReferencesInterface[] $valuesArray
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function values(array $valuesArray);


	/**
	 * @return TableInterface
	 */
	public function getTable();


	/**
	 * @return ColumnInterface[]
	 */
	public function getColumns();


	/**
	 * @return ReferencesInterface[][]
	 */
	public function getValues();
}