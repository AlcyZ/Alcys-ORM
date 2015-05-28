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

namespace Alcys\Core\Db\Expression\Builder;

use Alcys\Core\Db\Expression\ConditionInterface;

/**
 * Class ConditionBuilder
 * @package Alcys\Core\Db\Expression\Builder
 */
class ConditionBuilder implements ExpressionBuilderInterface
{
	/**
	 * @var array The condition array from the condition expression.
	 */
	private $conditionArray = array();

	/**
	 * @var string The validated condition string which will returned from the build method.
	 */
	private $conditionString = 'WHERE ';


	/**
	 * Initialize the condition builder.
	 *
	 * @param ConditionInterface $condition
	 */
	public function __construct(ConditionInterface $condition)
	{
		$this->conditionArray = $condition->getConditionArray();
	}


	/**
	 * Build the condition expression string.
	 * Processing the passed condition expression and return it in form of a string.
	 *
	 * @see ConditionBuilder::_checkConditionArray
	 * @see ConditionBuilder::_processConditionArray
	 * 
	 * @return string The condition string.
	 * @throws \Exception When no condition method was called before.
	 */
	public function build()
	{
		$this->_checkConditionArray();
		$this->_processConditionArray();
		$this->conditionString = trim($this->conditionString);

		return $this->conditionString . ' ';
	}


	/**
	 * Check if the condition array is in the default state.
	 * When true, an exception will thrown.
	 * 
	 * @throws \Exception
	 */
	private function _checkConditionArray()
	{
		if($this->conditionArray === array())
		{
			throw new \Exception('No condition method was invoked! Condition array is in default state!');
		}
	}


	/**
	 * Processing the condition array and build the condition string.
	 */
	private function _processConditionArray()
	{
		foreach($this->conditionArray as $conditions)
		{
			foreach($conditions as $operator => $condition)
			{
				if(!is_string($operator))
				{
					$this->conditionString .= $condition . ' ';
				}
				else
				{
					$this->conditionString .= strtoupper($operator) . ' ' . $condition . ' ';
				}
			}
		}
	}
}