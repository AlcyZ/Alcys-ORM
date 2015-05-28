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

use Alcys\Core\Db\Expression\JoinInterface;

/**
 * Interface SelectFacadeInterface
 * @package Alcys\Core\Db\Facade
 */
interface SelectFacadeInterface
{
	/**
	 * Add a table to the query.
	 *
	 * @param string      $tableName The name of the table.
	 * @param string|null $alias     (Optional) An alias name, if exists.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table($tableName, $alias = null);


	/**
	 * Add a column to the query. When this method would not called, a * wildcard will set.
	 *
	 * @param string      $name     Name of the column.
	 * @param string|null $tableRef (Optional) Name of the referred table, if exists.
	 * @param string|null $alias    (Optional) An alias name, if exists.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function column($name, $tableRef = null, $alias = null);


	/**
	 * Add a group by expression to the query.
	 *
	 * @param string      $column
	 * @param string|null $orderMode
	 *
	 * @return $this The same instance to concatenate methods
	 */
	public function groupBy($column, $orderMode = null);


	/**
	 * Add a distinct expression to the query.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function distinct();


	/**
	 * Add a join expression to the query.
	 *
	 * @param JoinInterface $join The configured join object, get by joinBuilder method.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function join(JoinInterface $join);


	/**
	 * Return a join object which could passed through the join method.
	 *
	 * @return JoinInterface
	 */
	public function joinBuilder();
}