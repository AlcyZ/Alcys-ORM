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

namespace Alcys\Core\Db\References\MySql;

use Alcys\Core\Db\References\SqlFunctionInterface;

/**
 * Class SqlFunction
 * @package Alcys\Core\Db\References\MySql
 */
class SqlFunction implements SqlFunctionInterface, ReferencesInterface
{
	/**
	 * Name of the MySql function.
	 *
	 * @var string The property that represent the function name.
	 */
	private $functionName;


	/**
	 * Validated string of the arguments.
	 *
	 * @var string The property that represent the function arguments or is empty.
	 */
	private $arguments;


	/**
	 * @var string The property that represent the expression.
	 */
	private $expression;


	/**
	 * Initialize the object that represent a MySql Function.
	 * The constructor validate the value .
	 *
	 * @param string $functionName
	 * @param array  $args
	 */
	public function __construct($functionName, array $args = array())
	{
		$this->functionName = strtoupper($functionName);
		$this->arguments    = $this->_parseArgs($args);
		$this->expression   = $this->getFunctionName() . '(' . $this->arguments . ')';
	}


	/**
	 * @return string
	 */
	public function getFunctionName()
	{
		return $this->functionName;
	}


	/**
	 * @return string
	 */
	public function getArguments()
	{
		return $this->arguments;
	}


	/**
	 * @return mixed
	 */
	public function getExpression()
	{
		return $this->expression;
	}


	/**
	 * Interface wrapper method.
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->getExpression();
	}


	/**
	 * This method parse the argument that should
	 * passed to the MySql Function.
	 *
	 * @param array $args The argument that should passe to the sql function, in an array.
	 *
	 * @return string
	 */
	private function _parseArgs(array $args)
	{
		$arg = '';
		if(count($args) > 0)
		{
			foreach($args as $number => $argument)
			{
				if($number === 0)
				{
					$arg .= $argument;
				}
				else
				{
					$arg .= ', ' . $argument;
				}
			}
		}

		return $arg;
	}
}
