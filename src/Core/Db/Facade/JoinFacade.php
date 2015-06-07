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
 * @Todo    Implement interfaces.
 * @Todo    Implement functionality to add multiple tables to the join expression.
 * @package Alcys\Core\Db\Facade
 */
class JoinFacade
{
	/**
	 * @var JoinInterface
	 */
	private $join;

	/**
	 * @var DbFactoryInterface
	 */
	private $factory;


	public function __construct(JoinInterface $join, DbFactoryInterface $factory)
	{
		$this->join    = $join;
		$this->factory = $factory;
	}


	public function inner($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->inner($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	public function left($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->left($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	public function right($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->right($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	public function leftOuter($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->leftOuter($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	public function rightOuter($table, array $optionalTables = array())
	{
		$preparedTablesArray = $this->_getPreparedTableObjects($table, $optionalTables);
		$this->join->rightOuter($preparedTablesArray['table'], $preparedTablesArray['optionalTables']);

		return $this;
	}


	public function natural($table, $way = null)
	{
		/** @var TableInterface $tableObj */
		$tableObj = $this->factory->references('Table', $table);
		$this->join->natural($tableObj, $way);

		return $this;
	}


	public function on(array $firstColumn, array $secondColumn)
	{
		/** @var ColumnInterface $firstColumnObj */
		/** @var ColumnInterface $secondColumnObj */

		$firstColumnObj  = $this->factory->references('Column', $firstColumn['column'], $firstColumn['table']);
		$secondColumnObj = $this->factory->references('Column', $secondColumn['column'], $secondColumn['table']);
		$this->join->on($firstColumnObj, $secondColumnObj);
	}


	public function using()
	{
		
	}


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
}