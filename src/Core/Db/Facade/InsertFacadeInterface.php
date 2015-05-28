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

namespace Alcys\Core\Db\Facade;

/**
 * Interface InsertFacadeInterface
 * @package Alcys\Core\Db\Facade
 */
interface InsertFacadeInterface
{
	/**
	 * Set columns to the query in which should insert new entries.
	 * Afterwards, the value method should call to set the values.
	 *
	 * @param array $columns An usual array with the column names as values.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function columns(array $columns);


	/**
	 * Set values to the query.
	 * The method can execute multiple times. The passed array require the
	 * same amount of elements, otherwise an exception will thrown.
	 *
	 * @param array $values An usual array with the values that should insert.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function values(array $values);
}