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

use Alcys\Core\Db\References\OrderModeEnumInterface;
use Alcys\Core\Types\Enum;

/**
 * Class OrderModeEnum
 * @package Alcys\Core\Db\References\MySql
 */
class OrderModeEnum extends Enum implements OrderModeEnumInterface, ReferencesInterface
{
	/**
	 * Validate the enum value and set the enum property to the validated value.
	 *
	 * The validated value will returned from the __toString() method after the value objects initialization.
	 *
	 * @param string $arg
	 */
	protected function _validateEnumValue($arg)
	{
		parent::_validateEnumValue($arg);
		$enum = strtoupper(trim($arg));
		if($enum !== 'ASC' && $enum !== 'DESC')
		{
			throw new \InvalidArgumentException('argument has to be a string of asc or desc!');
		}
		$this->enum = $enum;
	}


	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->enum;
	}
}
