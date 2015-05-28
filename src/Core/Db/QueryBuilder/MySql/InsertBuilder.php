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

namespace Alcys\Core\Db\QueryBuilder\MySql;

use Alcys\Core\Db\QueryBuilder\InsertBuilder as AbstractBuilder;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class InsertBuilder
 * @package Alcys\Core\Db\QueryBuilder\MySql
 */
class InsertBuilder extends AbstractBuilder implements BuilderInterface
{
	/**
	 * @var string The formatted table name.
	 */
	private $table;

	/**
	 * @var string The formatted columns string.
	 */
	private $columns;

	/**
	 * @var string The formatted values string.
	 */
	private $values;


	/**
	 * This method processing the statement and return a MySql query.
	 *
	 * The method will validate the statements properties and throws
	 * an exception, if they are invalid. Otherwise, the query will
	 * build and returned.
	 *
	 * @return string The well formatted MySql query.
	 * @throws \Exception
	 */
	public function process()
	{
		$this->_prepareTable($this->statement->getTable())->_prepareColumns($this->statement->getColumns())
			 ->_prepareValues($this->statement->getValues());

		return 'INSERT INTO ' . $this->table . ' (' . $this->columns . ') VALUES ' . $this->values . ';';
	}


	/**
	 * Prepare the table property to set it to the query.
	 *
	 * @param TableInterface $table The table object with filled properties.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	private function _prepareTable(TableInterface $table)
	{
		$this->table = $table->getTableName();

		return $this;
	}


	/**
	 * Validate and prepare the columns property to set it to the query.
	 *
	 * @param ColumnInterface[] $columnArray An array with ColumnInterface objects as elements.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When column method of statement was not invoked.
	 */
	private function _prepareColumns(array $columnArray)
	{
		if(count($columnArray) === 0)
		{
			throw new \Exception('The column method of the statement must called before!');
		}
		foreach($columnArray as $column)
		{
			$this->columns .= $column->getColumnName() . ', ';
		}
		$this->columns = rtrim($this->columns);
		$this->columns = rtrim($this->columns, ',');

		return $this;
	}


	/**
	 * Validate and prepare the values property to set it to the query.
	 *
	 * @param ReferencesInterface[][] $valueArrays An array with arrays that contain ReferenceInterface objects.
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception When statements values method was not called.
	 */
	private function _prepareValues(array $valueArrays)
	{
		if(count($valueArrays) === 0)
		{
			throw new \Exception('The value method of the statement must called before!');
		}
		foreach($valueArrays as $valueArray)
		{
			$valueString = '(';
			foreach($valueArray as $value)
			{
				$valueString .= $value->getValue() . ', ';
			}
			$valueString = rtrim($valueString);
			$valueString = rtrim($valueString, ',') . ')';
			$this->values .= $valueString . ', ';
		}
		$this->values = rtrim($this->values);
		$this->values = rtrim($this->values, ',');

		return $this;
	}
}