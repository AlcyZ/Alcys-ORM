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

namespace Alcys\Core\Db\QueryBuilder;

use Alcys\Core\Db\Factory\ExpressionBuilderFactoryInterface;
use Alcys\Core\Db\Statement\Update;

/**
 * Class UpdateBuilder
 * @package Alcys\Core\Db\QueryBuilder
 */
abstract class UpdateBuilder
{
	/**
	 * @var Update Statement with filled properties.
	 */
	protected $statement;

	/**
	 * @var ExpressionBuilderFactoryInterface Factory for builder to create strings from value objects.
	 */
	protected $expressionBuilderFactory;


	/**
	 * Initialize the statement property.
	 * The builders process method will check if the statement is in the correct format.
	 *
	 * @param Update                   $update  Statement with invoked table method.
	 * @param ExpressionBuilderFactoryInterface $factory Factory for builder to create strings from value objects.
	 */
	public function __construct(Update $update, ExpressionBuilderFactoryInterface $factory)
	{
		$this->statement                = $update;
		$this->expressionBuilderFactory = $factory;
	}
}