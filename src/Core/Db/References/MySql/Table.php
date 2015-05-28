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

use Alcys\Core\Db\References\AliasInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class Table
 * @package Alcys\Core\Db\References\MySql
 */
class Table implements TableInterface, ReferencesInterface, AliasInterface
{
	/**
	 * @var string The table name.
	 */
	private $tableName;

	/**
	 * @var string|null If not null, the alias name.
	 */
	private $alias;

	/**
	 * @var string If alias null, only the table name
	 */
	private $expression;


	/**
	 * Initialize the object that represent a MySql Table.
	 * The constructor validate the value and throw, if necessary,
	 * an exception.
	 *
	 * @param string      $tableName
	 * @param string|null $alias
	 */
	public function __construct($tableName, $alias = null)
	{
		if(!is_string($tableName) || (!is_string($alias) && !is_null($alias)))
		{
			throw new \InvalidArgumentException('argument has to be a string or null');
		}
		$this->tableName = '`' . htmlentities($tableName, ENT_QUOTES) . '`';
		$this->alias     = ($alias) ? '`' . htmlentities($alias, ENT_QUOTES) . '`' : null;

		$this->expression = ($alias) ? $this->tableName . ' AS ' . $this->alias : $this->tableName;
	}


	/**
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}


	/**
	 * @return string
	 */
	public function getAlias()
	{
		return $this->alias;
	}


	/**
	 * @return string
	 */
	public function getExpression()
	{
		return $this->expression;
	}


	/**
	 * Interface wrapper method.
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->getTableName();
	}
}