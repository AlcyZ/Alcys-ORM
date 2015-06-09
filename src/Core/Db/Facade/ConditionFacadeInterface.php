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

use Alcys\Core\Db\Expression\Condition;

/**
 * Interface ConditionFacadeInterface
 * @Todo    Split interface in smaller, more precise interfaces .. maybe.
 * @package Alcys\Core\Db\Facade
 */
interface ConditionFacadeInterface
{
	/**
	 * Compare in the following style in the where condition:
	 * $column = $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function equal($column, $compareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column != $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function notEqual($column, $compareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column > $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function greater($column, $compareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column >= $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function greaterEqual($column, $compareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column < $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function lower($column, $compareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column <= $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function lowerEqual($column, $compareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column BETWEEN $firstCompareValue AND $secondCompareValue
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column             The column name which should compare.
	 * @param string      $firstCompareValue  The first value for the comparison.
	 * @param string      $secondCompareValue The second value for the comparison.
	 * @param string|null $type               (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function between($column, $firstCompareValue, $secondCompareValue, $type = null);


	/**
	 * Compare in the following style in the where condition:
	 * $column NOT BETWEEN $firstCompareValue AND $secondCompareValue
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column             The column name which should compare.
	 * @param string      $firstCompareValue  The first value for the comparison.
	 * @param string      $secondCompareValue The second value for the comparison.
	 * @param string|null $type               (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function notBetween($column, $firstCompareValue, $secondCompareValue, $type = null);


	/**
	 * Use this method to set another comparison in connection with a logical and to the condition.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function logicAnd();


	/**
	 * Use this method to set another comparison in connection with a logical and to the condition.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function logicOr();


	/**
	 * @return Condition
	 */
	public function getCondition();
}