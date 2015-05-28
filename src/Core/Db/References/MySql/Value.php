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

use Alcys\Core\Db\References\ValueInterface;
use Alcys\Core\Types\Numeric;

/**
 * Class Value
 * @package Alcys\Core\Db\References
 */
class Value implements ReferencesInterface, ValueInterface, WhereCompareInterface, WhereNumericCompareInterface
{
	/**
	 * Name of the value.
	 *
	 * @var string The property that represent the value name.
	 */
	private $value;


	/**
	 * Initialize the object that represent a MySql Value.
	 * The constructor validate the value and throw, if necessary,
	 * an exception.
	 *
	 * @param string $value
	 */
	public function __construct($value)
	{
		if(is_string($value))
		{
			$this->value = '"' . htmlentities($value, ENT_QUOTES) . '"';
		}
		elseif($value instanceof Numeric || is_numeric($value))
		{
			$this->value = (string)htmlentities($value, ENT_QUOTES);
		}
		elseif($value instanceof Column)
		{
			$this->value = $value->getColumnName();
		}
		else
		{
			throw new \InvalidArgumentException('passed argument has an illegal type');
		}
	}


	/**
	 * Return the value that should used from the where expression
	 * object to compare with an other column.
	 *
	 * @return string
	 */
	public function getCompareValue()
	{
		return $this->getValue();
	}


	/**
	 * Return the value that should used from the where expression
	 * object to compare with other numeric values.
	 *
	 * @return string
	 */
	public function getNumericCompareValue()
	{
		return $this->getValue();
	}


	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}
}
