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

namespace Alcys\Core\Db\Statement;

use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class Insert
 * @Todo    on duplicate key update, refactor this class to be compatible with the facade.
 * @package Alcys\Core\Db\Statement
 */
class Insert implements StatementInterface, InsertInterface
{
	/**
	 * @var TableInterface The table name.
	 */
	private $table;

	/**
	 * @var ColumnInterface[]
	 */
	private $columns = array();

	/**
	 * @var ReferencesInterface[][]
	 */
	private $values = array();

	private $onDuplicateKeyUpdate = false;


	/**
	 * Add a table object to the statement.
	 *
	 * @param TableInterface $table The table object.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function table(TableInterface $table)
	{
		$this->table = $table;

		return $this;
	}


	/**
	 * Add columns to the insert statement.
	 *
	 * @param ColumnInterface[] $columnsArray An array with the column names in which should insert.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When not each element is of type ColumnInterface.
	 */
	public function columns(array $columnsArray)
	{
		foreach($columnsArray as $column)
		{
			if(!$column instanceOf ColumnInterface)
			{
				throw new \Exception('Each element of the passed array has to be of type ColumnInterface!');
			}
		}
		$this->columns = $columnsArray;

		return $this;
	}


	/**
	 * Validate the passed array and add their values to the insert statement.
	 * The columns method must called before, otherwise an exception will thrown.
	 *
	 * @param ReferencesInterface[] $valuesArray
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When array does not have the same length like the passed array of the column method,
	 * if column method was not invoke or if element of the passed array is not of type ReferenceInterface
	 */
	public function values(array $valuesArray)
	{
		$this->_checkColumnsArrayIsset()->_checkArraysForSameLength($valuesArray)->_checkValuesArrayType($valuesArray);
		$this->values[] = $valuesArray;

		return $this;
	}


	/**
	 * Add an on duplicate key update expression to the query.
	 * The internal property will set to true, the builder realize it and build the expression.
	 *
	 * @return $this Same instance to concatenate methods.
	 */
	public function onDuplicateKeyUpdate()
	{
		$this->onDuplicateKeyUpdate = true;

		return $this;
	}


	/**
	 * Check of column method was invoked before.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When column method was not invoked.
	 */
	private function _checkColumnsArrayIsset()
	{
		if(count($this->columns) === 0)
		{
			throw new \Exception('Insert statements column method must called before use this method');
		}

		return $this;
	}


	/**
	 * Compare the length of the passed array with the length of the columns array.
	 *
	 * @param array $compareArray Array which should compared.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When arrays does not have the same length.
	 */
	private function _checkArraysForSameLength(array $compareArray)
	{
		if(count($this->columns) !== count($compareArray))
		{
			throw new \Exception('The passed array require the same length like the columns array');
		}

		return $this;
	}


	/**
	 * Check the type of each element of passed array.
	 *
	 * @param array $valuesArray Array to check.
	 *
	 * @throws \Exception When not each element is of type ReferenceInterface.
	 */
	private function _checkValuesArrayType(array $valuesArray)
	{
		foreach($valuesArray as $value)
		{
			if(!$value instanceof ReferencesInterface)
			{
				throw new \Exception();
			}
		}
	}


	/**
	 * @return TableInterface
	 */
	public function getTable()
	{
		return $this->table;
	}


	/**
	 * @return ColumnInterface[]
	 */
	public function getColumns()
	{
		return $this->columns;
	}


	/**
	 * @return ReferencesInterface[][]
	 */
	public function getValues()
	{
		return $this->values;
	}


	/**
	 * @return boolean
	 */
	public function isOnDuplicateKeyUpdate()
	{
		return $this->onDuplicateKeyUpdate;
	}
}
