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
 * Interface JoinFacadeInterface
 * @Todo    Split interface, separate on and using method!
 * @package Alcys\Core\Db\Facade
 */
interface JoinFacadeInterface
{
	/**
	 * Join the expected table with an inner join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function inner($table, array $optionalTables = array());


	/**
	 * Join the expected table with a left join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function left($table, array $optionalTables = array());


	/**
	 * Join the expected table with a right join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function right($table, array $optionalTables = array());


	/**
	 * Join the expected table with a left outer join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function leftOuter($table, array $optionalTables = array());


	/**
	 * Join the expected table with a right outer join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function rightOuter($table, array $optionalTables = array());


	/**
	 * Join the expected table with a natural join.
	 * In the second argument can pass the way, which have to be whether 'inner', 'left[:outer]' or 'right[:outer]'.
	 *
	 * @param string      $table Name of the table for the join.
	 * @param string|null $way   (Optional) Whether 'inner', 'left[:outer]', 'right[:outer]' or null.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function natural($table, $way = null);


	/**
	 * Set the condition how to join the table[s] with the 'on' keyword.
	 * The passed arguments require to be arrays in the following format: ['column' => $col, 'table' => $table].
	 * An invalid argument exception will thrown when the arrays are invalid.
	 *
	 * @param array $firstColumn  Associative array: ['column' => $col, 'table' => $table].
	 * @param array $secondColumn Associative array: ['column' => $col, 'table' => $table].
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function on(array $firstColumn, array $secondColumn);


	/**
	 * Set the condition how to join the table[s] with the 'using' keyword.
	 *
	 * @param string $column Name of the column for the using condition.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function using($column);


	/**
	 * @return JoinInterface
	 */
	public function getJoin();
}