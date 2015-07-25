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

use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\MySql\WhereCompareInterface;
use Alcys\Core\Db\References\MySql\WhereNumericCompareInterface;
use Alcys\Core\Db\References\MySql\ReferencesInterface;

/**
 * Interface ComparisonInterface
 * @package Alcys\Core\Db\Expression
 */
interface ComparisonInterface
{
	/**
	 * Add an equal condition to the condition array.
	 *
	 * @param ColumnInterface       $column Name of the column.
	 * @param WhereCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function equal(ColumnInterface $column, WhereCompareInterface $value);


	/**
	 * Add a not equal condition to the condition array.
	 *
	 * @param ColumnInterface       $column Name of the column.
	 * @param WhereCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function notEqual(ColumnInterface $column, WhereCompareInterface $value);


	/**
	 * Add a greater condition to the condition array.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function greater(ColumnInterface $column, WhereNumericCompareInterface $value);


	/**
	 * Add a greater or equal condition to the condition array.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function greaterEqual(ColumnInterface $column, WhereNumericCompareInterface $value);


	/**
	 * Add a lower condition to the condition array.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function lower(ColumnInterface $column, WhereNumericCompareInterface $value);


	/**
	 * Add a lower or equal condition to the condition array.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function lowerEqual(ColumnInterface $column, WhereNumericCompareInterface $value);


	/**
	 * Add a between condition to the condition array.
	 *
	 * @param ColumnInterface              $column       Name of the column.
	 * @param WhereNumericCompareInterface $lowerValue   The lower value of the expression.
	 * @param WhereNumericCompareInterface $greaterValue The higher value of the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function between(ColumnInterface $column, WhereNumericCompareInterface $lowerValue,
							WhereNumericCompareInterface $greaterValue);


	/**
	 * Add a not between condition to the condition array.
	 *
	 * @param ColumnInterface              $column       Name of the column.
	 * @param WhereNumericCompareInterface $lowerValue   The lower value of the expression.
	 * @param WhereNumericCompareInterface $greaterValue The higher value of the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function notBetween(ColumnInterface $column, WhereNumericCompareInterface $lowerValue,
							   WhereNumericCompareInterface $greaterValue);


	/**
	 * Add a like condition to the condition array.
	 *
	 * @param ColumnInterface     $column Name of the column.
	 * @param ReferencesInterface $value  Value for the expression.
	 * @param int                 $level  The several levels of a like expression, see private likeLevel - methods.
	 *
	 * @see Alcys\Core\Db\Condition\Condition::_likeLevelOne
	 * @see Alcys\Core\Db\Condition\Condition::_likeLevelTwo
	 * @see Alcys\Core\Db\Condition\Condition::_likeLevelThree
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function like(ColumnInterface $column, ReferencesInterface $value, $level = 0);


	/**
	 * Add an is null condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface $column Name of the column to compare.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function isNull(ColumnInterface $column);


	/**
	 * Add a not null condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface $column Name of the column to compare.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function notNull(ColumnInterface $column);
}