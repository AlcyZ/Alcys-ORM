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

namespace Alcys\Core\Db\Expression;

use Alcys\Core\Db\References\TableInterface;
use Alcys\Core\Db\References\ColumnInterface;

/**
 * Interface JoinInterface
 * @package Alcys\Core\Db\Expression
 */
interface JoinInterface
{

	/**
	 * Set the join array value with key way to inner and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function inner(TableInterface $table, array $optionalTables = array());


	/**
	 * Set the join array value with key way to left and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function left(TableInterface $table, array $optionalTables = array());


	/**
	 * Set the join array value with key way to right and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function right(TableInterface $table, array $optionalTables = array());


	/**
	 * Set the join array value with key way to left:outer and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function leftOuter(TableInterface $table, array $optionalTables = array());


	/**
	 * Set the join array value with key way to right:outer and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function rightOuter(TableInterface $table, array $optionalTables = array());


	/**
	 * Set the join array value with key way to natural and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 * The natural join is like a using condition which automatic selection of all equivalent columns.
	 *
	 * @param TableInterface $table Table in which should joined.
	 * @param string|null    $way   The way how to join.
	 *
	 * @see Join::_validateNaturalWayArgument
	 *
	 * @return Join The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function natural(TableInterface $table, $way = null);


	/**
	 * Set the join array value with key conditionType to on and with key condition to an validated condition.
	 * An exception will thrown, when the passed column arguments does not have the same column name, does
	 * not have table references or no of the way methods(left(), right(), inner(), ...) was called before.
	 *
	 * @param ColumnInterface $firstColumn  First column to compare.
	 * @param ColumnInterface $secondColumn Second column to compare.
	 *
	 * @see Join::_validateOnArguments
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function on(ColumnInterface $firstColumn, ColumnInterface $secondColumn);


	/**
	 * Set the join array value with key conditionType to using and with key condition to an validated condition.
	 *
	 * @param ColumnInterface $column The expected column value object.
	 *
	 * @return $this
	 */
	public function using(ColumnInterface $column);


	/**
	 * @return array
	 */
	public function getJoinArray();
}