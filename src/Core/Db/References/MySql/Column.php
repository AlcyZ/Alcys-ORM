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
use Alcys\Core\Db\References\SqlFunctionInterface;
use Alcys\Core\Db\References\ColumnInterface;

/**
 * Class Column
 * @Todo    Method getNumericCompareValue should validates dates. Implementation later.
 * @Todo    Construct should have the format: new Column(String $columnName, TableInterface $table = null, String
 *          $alias)
 * @package Alcys\Core\Db\References\MySql
 */
class Column
	implements ColumnInterface, ReferencesInterface, AliasInterface, WhereCompareInterface, WhereNumericCompareInterface
{
	/**
	 * Name of the column.
	 *
	 * @var string The property that represent the column name.
	 */
	private $columnName;

	/**
	 * Name of the table, when a column of a specific table is mean.
	 * If no table is specified, the value is null.
	 *
	 * @var string The property that represent the table name.
	 */
	private $tableName;

	/**
	 * Name of the alias, if the column should get one.
	 * If no alias is specified, the value is null.
	 *
	 * @var string The property that represent the table alias name.
	 */
	private $alias;

	/**
	 * @var string Name of the expression.
	 */
	private $expression;


	/**
	 * Initialize the object that represent a MySql Column.
	 * The constructor validate the value and throw, if necessary,
	 * an exception.
	 *
	 * @param string      $columnName Name of the column.
	 * @param string|null $tableName  Name of the table, when a column of a specific table is mean.
	 * @param string|null $alias      Name of the alias, if the column should get one.
	 */
	public function __construct($columnName, $tableName = null, $alias = null)
	{
		$this->_prepareColumnArgument($columnName);
		if((!is_string($tableName) && !is_null($tableName)) || (!is_string($alias) && !is_null($alias)))
		{
			throw new \InvalidArgumentException('param have to be a string or null!');
		}
		$this->tableName = ($tableName) ? '`' . htmlentities($tableName, ENT_QUOTES) . '`' : null;
		$this->alias     = ($alias) ? '`' . htmlentities($alias, ENT_QUOTES) . '`' : null;
		$this->_prepareExpression();
	}


	/**
	 * @return string
	 */
	public function getColumnName()
	{
		return $this->columnName;
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
	 * Return the value that should used from the where expression
	 * object to compare with an other column.
	 *
	 * @return string
	 */
	public function getCompareValue()
	{
		$value = ($this->alias) ? : $this->columnName;

		return $value;
	}


	/**
	 * Interface wrapper method.
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->getCompareValue();
	}


	/**
	 * Return the value that should used from the where expression
	 * object to compare with other numeric values.
	 *
	 * @return string
	 */
	public function getNumericCompareValue()
	{
		return $value = $this->getCompareValue();
	}


	/**
	 * This method validate and prepare the column name,
	 * or throw an exception if an invalid argument is passed
	 * as parameter.
	 *
	 * @param string $column
	 */
	private function _prepareColumnArgument($column)
	{
		if(!is_string($column) && !$column instanceof SqlFunctionInterface)
		{
			throw new \InvalidArgumentException('Argument 1 passed is not the expected type!');
		}
		if($column instanceof SqlFunctionInterface || $column === '*')
		{
			$this->columnName = (string)htmlentities($column, ENT_QUOTES);
		}
		else
		{
			$this->columnName = '`' . htmlentities($column, ENT_QUOTES) . '`';
		}
	}


	/**
	 * Prepare the expression property, which is at least
	 * the column name or a combination of the name,
	 * alias and table reference.
	 */
	private function _prepareExpression()
	{
		$this->expression = ($this->tableName && $this->alias) ?
			$this->tableName . '.' . $this->columnName . ' AS ' . $this->alias : (($this->tableName && !$this->alias) ?
				$this->tableName . '.' . $this->columnName : ((!$this->tableName && $this->alias) ?
					$this->columnName . ' AS ' . $this->alias : $this->columnName));
	}
}