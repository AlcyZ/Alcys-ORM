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

namespace Alcys\Core\Db\Factory;

use Alcys\Core\Db\References\MySql\Column;
use Alcys\Core\Db\References\MySql\OrderModeEnum;
use Alcys\Core\Db\References\MySql\SqlFunction;
use Alcys\Core\Db\References\MySql\Table;
use Alcys\Core\Db\References\MySql\Value;
use Alcys\Core\Types\Numeric;

/**
 * Class ReferenceFactory
 * @package Alcys\Core\Db\Factory
 */
class ReferenceFactory
{
	/**
	 * Get an instance of a column reference.
	 *
	 * @param string      $name     Name of the column.
	 * @param string|null $tableRef (Optional) If not null, reference to a specific table.
	 * @param string|null $alias    (Optional) Alias of the column.
	 *
	 * @return Column An instance of a column reference.
	 */
	public function column($name, $tableRef = null, $alias = null)
	{
		return new Column($name, $tableRef, $alias);
	}


	/**
	 * Get an instance of an order mode enum reference.
	 *
	 * @param string $orderMode
	 *
	 * @return OrderModeEnum An instance of a order mode enum reference.
	 */
	public function orderModeEnum($orderMode)
	{
		return new OrderModeEnum($orderMode);
	}


	/**
	 * Get an instance of a sql function reference.
	 *
	 * @param string $functionName The name of the sql function.
	 * @param array  $arguments    (Optional) Arguments that should passed to the function.
	 *
	 * @return SqlFunction An instance of a sql function reference.
	 */
	public function sqlFunction($functionName, array $arguments = array())
	{
		return new SqlFunction($functionName, $arguments);
	}


	/**
	 * Get an instance of a table reference.
	 *
	 * @param string      $tableName Name of the table.
	 * @param string|null $alias     (Optional) Alias of the table.
	 *
	 * @return Table An instance of a table reference.
	 */
	public function table($tableName, $alias = null)
	{
		return new Table($tableName, $alias);
	}


	/**
	 * Get an instance of a value reference.
	 *
	 * @param string $value The value.
	 *
	 * @return Value An instance of a value reference.
	 */
	public function value($value)
	{
		return new Value($value);
	}


	/**
	 * Get an instance of a numeric reference.
	 *
	 * @param number $number
	 *
	 * @return Numeric An instance of a numeric reference.
	 */
	public function numeric($number)
	{
		return new Numeric($number);
	}
}