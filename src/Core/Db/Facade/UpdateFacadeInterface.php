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
 * Interface UpdateFacadeInterface
 * @package Alcys\Core\Db\Facade
 */
interface UpdateFacadeInterface
{
	/**
	 * Set a single column to the query which should get an update.
	 * Afterwards, you have to call the UpdateFacade::value() method!!!
	 * This process can done multiple times to update more then one column.
	 *
	 * @param string $column Name of the column which should updated.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column($column);


	/**
	 * Set the value for the column, which is passed as argument in the UpdateFacade::column() method.
	 * You have to call this method immediate after the column method.
	 * Before calling this method again, the column() has to be invoked.
	 *
	 * @param string $value The value which should set.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When UpdateFacade::column() was not called before.
	 */
	public function value($value);


	/**
	 * Set multiple columns to the query which should get an update.
	 * Afterwards, you have to call the UpdateFacade::values() method!!!
	 *
	 * @param array $columns An usual array with the column names as elements.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function columns(array $columns);


	/**
	 * Set values for the columns, which are passed as array argument in the UpdateFacade::columns() method.
	 * You have to call this method immediate after the columns method.
	 * Before calling this method again, the columns() has to be invoked.
	 *
	 * @param array $values An usual array with the values as elements.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When the UpdateFacade::columns() method was not called before.
	 */
	public function values(array $values);
}