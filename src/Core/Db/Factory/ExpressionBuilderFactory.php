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

use Alcys\Core\Db\Expression\Builder\ConditionBuilder;
use Alcys\Core\Db\Expression\Builder\JoinBuilder;
use Alcys\Core\Db\Expression\Builder\NullBuilder;
use Alcys\Core\Db\Expression\ConditionInterface;
use Alcys\Core\Db\Expression\ExpressionInterface;
use Alcys\Core\Db\Expression\JoinInterface;

/**
 * Class ExpressionBuilderFactory
 * @Todo    Outsource create code when the become bigger.
 * @package Alcys\Core\Db\Factory
 */
class ExpressionBuilderFactory implements ExpressionBuilderFactoryInterface
{
	/**
	 * Create and return an instance of a specific expression builder.
	 *
	 * @param ExpressionInterface|JoinInterface|ConditionInterface $expression
	 *
	 * @return ConditionBuilder|JoinBuilder|NullBuilder
	 */
	public function create(ExpressionInterface $expression = null)
	{
		if($expression instanceof ConditionInterface)
		{
			return new ConditionBuilder($expression);
		}
		elseif($expression instanceof JoinInterface)
		{
			return new JoinBuilder($expression);
		}
		else
		{
			return new NullBuilder();
		}
	}
}