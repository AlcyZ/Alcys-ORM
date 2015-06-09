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

namespace Alcys\Core\Db\Facade;

use Alcys\Core\Db\Expression\JoinInterface;
use Alcys\Core\Db\Factory\DbFactoryInterface;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class JoinFacade
 * @Todo    $join->on('firstColumnName', 'firstTableRef', 'secondColumnName', 'secondTableRef') and:
 *            $join->on(
 *                array('table' => $tableRefOne', 'column' => $columnNameOne),
 *                array('table' => $tableRefTwo', 'column' => $columnNameTwo)
 *            )
 * @Todo    Implement functionality to add multiple tables to the join expression.
 * @package Alcys\Core\Db\Facade
 */
class JoinFacade implements JoinFacadeInterface
{
	/**
	 * @var JoinInterface
	 */
	private $join;

	/**
	 * @var DbFactoryInterface
	 */
	private $factory;


	/**
	 * Initialize the join facade object and set required properties.
	 *
	 * @param JoinInterface      $join    Join object to create the query snippet with a join builder object.
	 * @param DbFactoryInterface $factory Factory to create reference objects.
	 */
	public function __construct(JoinInterface $join, DbFactoryInterface $factory)
	{
		$this->join    = $join;
		$this->factory = $factory;
	}


	/**
	 * Join the expected table with an inner join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function inner($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->inner($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	/**
	 * Join the expected table with a left join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function left($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->left($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	/**
	 * Join the expected table with a right join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function right($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->right($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	/**
	 * Join the expected table with a left outer join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function leftOuter($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->leftOuter($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	/**
	 * Join the expected table with a right outer join.
	 * In the second argument can pass optional tables for the join operation.
	 *
	 * @param string $table          Name of the table for the join.
	 * @param array  $optionalTables (Optional) Array with table names as elements
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function rightOuter($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->rightOuter($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	/**
	 * Join the expected table with a natural join.
	 * In the second argument can pass the way, which have to be whether 'inner', 'left[:outer]' or 'right[:outer]'.
	 *
	 * @param string      $table Name of the table for the join.
	 * @param string|null $way   (Optional) Whether 'inner', 'left[:outer]', 'right[:outer]' or null.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function natural($table, $way = null)
	{
		/** @var TableInterface $tableObj */
		$tableObj = $this->factory->references('Table', $table);
		$this->join->natural($tableObj, $way);

		return $this;
	}


	/**
	 * Set the condition how to join the table[s] with the 'on' keyword.
	 * The passed arguments require to be arrays in the following format: ['column' => $col, 'table' => $table].
	 * An invalid argument exception will thrown when the arrays are invalid.
	 *
	 * @param array $firstColumn  Associative array: ['column' => $col, 'table' => $table].
	 * @param array $secondColumn Associative array: ['column' => $col, 'table' => $table].
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function on(array $firstColumn, array $secondColumn)
	{
		/** @var ColumnInterface $firstColumnObj */
		/** @var ColumnInterface $secondColumnObj */

		$this->_checkOnArguments($firstColumn, $secondColumn);
		$firstColumnObj  = $this->factory->references('Column', $firstColumn['column'], $firstColumn['table']);
		$secondColumnObj = $this->factory->references('Column', $secondColumn['column'], $secondColumn['table']);
		$this->join->on($firstColumnObj, $secondColumnObj);

		return $this;
	}


	/**
	 * Set the condition how to join the table[s] with the 'using' keyword.
	 *
	 * @param string $column Name of the column for the using condition.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function using($column)
	{
		/** @var ColumnInterface $columnObj */
		$columnObj = $this->factory->references('Column', $column);
		$this->join->using($columnObj);

		return $this;
	}


	/**
	 * @return JoinInterface
	 */
	public function getJoin()
	{
		return $this->join;
	}


	/**
	 * Create table objects from the passed arguments.
	 * An array will return with the key 'table' for the main table and 'optionalTables' for optional tables.
	 * When no optional tables are passed, the key 'optionalTables' return an empty array.
	 *
	 * @param string $table          Name of the main table.
	 * @param array  $optionalTables Optional table names, hold in an array.
	 *
	 * @return array Array with prepared table objects, format: ['table' => $tbl, 'optionalTables' => array].
	 */
	private function _getPreparedTableObjects($table, array $optionalTables)
	{
		$tableObj          = $this->factory->references('Table', $table);
		$optionalTablesObj = array();
		foreach($optionalTables as $tbl)
		{
			$optionalTablesObj[] = $this->factory->references('Table', $tbl);
		}

		return array('table' => $tableObj, 'optionalTables' => $optionalTablesObj);
	}


	/**
	 * Check the passed arguments of the on method. If they don't have keys equivalent to column and table,
	 * an invalid argument exception will thrown.
	 *
	 * @param array $firstColumn  First argument of on method.
	 * @param array $secondColumn Second argument of on method.
	 */
	private function _checkOnArguments(array $firstColumn, array $secondColumn)
	{
		if(!array_key_exists('column', $firstColumn) || !array_key_exists('table', $firstColumn) ||
		   !array_key_exists('column', $secondColumn) || !array_key_exists('table', $secondColumn)
		)
		{
			throw new \InvalidArgumentException('Both arguments of on method require "column" and "table" as key');
		}
	}
}