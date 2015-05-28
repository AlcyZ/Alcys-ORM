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

namespace Alcys\Core\Types;

/**
 * Class Integer
 * @package Alcys\Core\Types
 */
class Numeric implements TypesInterface
{
	private $int;


	/**
	 * Initialize the object that represent a IntegerType.
	 * The constructor validate the value and throw, if necessary,
	 * an exception.
	 *
	 * @param $integer
	 */
	public function __construct($integer)
	{
		if(is_float($integer) || is_bool($integer) || strstr($integer, '.'))
		{
			throw new \InvalidArgumentException('passed argument has to be an integer');
		}
		elseif($integer === 0 || is_int((int)$integer) && (int)$integer !== 0)
		{
			$this->int = (string)$integer;
		}
		else
		{
			throw new \InvalidArgumentException('passed argument has to be an integer');
		}
	}


	/**
	 * This magic method is used to return the value,
	 * that is passed as argument while the instantiation,
	 * immediate as a string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->int;
	}
}
