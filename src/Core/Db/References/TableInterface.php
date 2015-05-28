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

namespace Alcys\Core\Db\References;

/**
 * Interface TableInterface
 * @package Alcys\Core\Db\Parameter
 */
interface TableInterface
{
	/**
	 * Initialize the object that represent a Table interface.
	 *
	 * @param string $name
	 */
	public function __construct($name);


	/**
	 * Return the validated name of the represented table.
	 *
	 * @return string
	 */
	public function getTableName();


	/**
	 * Return the validated alias name or null, if none isset.
	 *
	 * @return string|null
	 */
	public function getAlias();


	/**
	 * Return the validated expression of the table reference,
	 * which could be a combination of the table name and alias,
	 * or is just the table name.
	 *
	 * @return string
	 */
	public function getExpression();
}