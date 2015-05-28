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
 * Class Enum
 * @package Alcys\Core\Types
 */
abstract class Enum implements TypesInterface
{
	/**
	 * @var string The property that represent the enum name. Could directly validated from child-class.
	 */
	protected $enum;


	/**
	 * Initialize the object that represent a EnumType.
	 * Because the class is abstract, it could not get instantiated.
	 * Child classes should only override the _validateEnumValue 
	 * method with a call to the parent method at the begin.
	 * The passed value will validated and returned.
	 *
	 * @param $enum
	 */
	public function __construct($enum)
	{
		$this->_validateEnumValue($enum);
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
		return $this->enum;
	}


	/**
	 * Validate the enum value and set the enum property to the validated value.
	 *
	 * Each type of enum should override this method to implement his
	 * own validation logic. This is the onliest method that should have
	 * an enum extended class, the other logic is implemented here in the abstract class.
	 *
	 * @param string $arg
	 */
	protected function _validateEnumValue($arg)
	{
		if(!is_string($arg))
		{
			throw new \InvalidArgumentException('argument has to be a string!');
		}
	}
}
