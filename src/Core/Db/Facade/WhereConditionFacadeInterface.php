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

use Alcys\Core\Db\Expression\ConditionInterface;

/**
 * Interface WhereConditionFacadeInterface
 * @package Alcys\Core\Db\Facade
 */
interface WhereConditionFacadeInterface
{
	/**
	 * Add an order by expression to the query.
	 *
	 * @param string      $column    Column Name.
	 * @param string|null $orderMode Order mode, whether desc or asc.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function orderBy($column, $orderMode = null);


	/**
	 * Add a limit expression to the query.
	 *
	 * @param int      $beginAmount Amount if only one arg isset, if two, the first entry which will used.
	 * @param int|null $amount      (Optional) Amount of entries which.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function limit($beginAmount, $amount = null);


	/**
	 * Add a where expression to the query.
	 *
	 * @param ConditionInterface $condition The configured condition object, get by conditionBuilder method.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function where(ConditionInterface $condition);


	/**
	 * Return a condition object.
	 * This will configured and then passed through the where method.
	 *
	 * @return ConditionInterface A condition object.
	 */
	public function conditionBuilder();


	/**
	 * Return an anonymous function which should used to create column arguments for the condition.
	 *
	 * @return callable Closure to create column arguments for the condition.
	 */
	public function getColumns();


	/**
	 * Return an anonymous function which should used to create value arguments for the condition.
	 *
	 * @return callable Closure to create value arguments for the condition.
	 */
	public function getValues();
}