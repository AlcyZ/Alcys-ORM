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

namespace Alcys\Core\Db\Expression;

use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\TableInterface;

/**
 * Class Join
 * @Todo    Multiple tables with natural join.
 * @Todo    Join expression could implement also for delete and update statement
 * @Todo    Implement cross join and logic operators in on condition.
 * @package Alcys\Core\Db\Expression
 */
class Join implements ExpressionInterface, JoinInterface, ReBuildableExpressionInterface
{
	/**
	 * @var array Default join array. The join builder will throw an exception if the array have the same format there.
	 */
	private $joinArray = array('way' => null, 'tables' => null, 'conditionType' => null, 'condition' => null);


	/**
	 * Set the join array value with key way to inner and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function inner(TableInterface $table, array $optionalTables = array())
	{
		return $this->_setWayValue('inner', $table, $optionalTables);
	}


	/**
	 * Set the join array value with key way to left and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function left(TableInterface $table, array $optionalTables = array())
	{
		return $this->_setWayValue('left', $table, $optionalTables);
	}


	/**
	 * Set the join array value with key way to right and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function right(TableInterface $table, array $optionalTables = array())
	{
		return $this->_setWayValue('right', $table, $optionalTables);
	}


	/**
	 * Set the join array value with key way to left:outer and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function leftOuter(TableInterface $table, array $optionalTables = array())
	{
		return $this->_setWayValue('left:outer', $table, $optionalTables);
	}


	/**
	 * Set the join array value with key way to right:outer and create a tables array.
	 * Each argument, which has to be of type table interface, will passed to the created tables array.
	 *
	 * @param TableInterface   $table          Table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @see Join::_setWayValue
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function rightOuter(TableInterface $table, array $optionalTables = array())
	{
		return $this->_setWayValue('right:outer', $table, $optionalTables);
	}


	/**
	 * Use a natural join.
	 *
	 * Set the key 'way' of the join array to the validated $way argument, the key 'tables' to
	 * array($table) and the key 'conditionType' to natural.
	 *
	 * The natural join is like a using condition which automatic selection of all equivalent columns.
	 *
	 * @param TableInterface $table Table in which should joined.
	 * @param string|null    $way   The way how to join.
	 *
	 * @see Join::_validateNaturalWayArgument
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function natural(TableInterface $table, $way = null)
	{
		$this->joinArray['way']           = $this->_validateNaturalWayArgument($way);
		$this->joinArray['tables']        = array($table);
		$this->joinArray['conditionType'] = 'natural';

		return $this;
	}


	/**
	 * Set the join array value with key conditionType to on and with key condition to an validated condition.
	 * An exception will thrown, when the passed column arguments does not have the same column name, does
	 * not have table references or no of the way methods(left(), right(), inner(), ...) was called before.
	 *
	 * @param ColumnInterface $firstColumn  First column to compare.
	 * @param ColumnInterface $secondColumn Second column to compare.
	 *
	 * @see Join::_validateOnArguments
	 *
	 * @return $this The same instance to concatenate methods.
	 * @throws \Exception
	 */
	public function on(ColumnInterface $firstColumn, ColumnInterface $secondColumn)
	{
		$this->_validateOnArguments($firstColumn, $secondColumn);

		$this->joinArray['conditionType'] = 'on';
		$this->joinArray['condition']     = array($firstColumn, $secondColumn);

		return $this;
	}


	/**
	 * Set the join array value with key conditionType to using and with key condition to an validated condition.
	 *
	 * @param ColumnInterface $column The expected column value object.
	 *
	 * @return $this
	 */
	public function using(ColumnInterface $column)
	{
		$this->joinArray['conditionType'] = 'using';
		$this->joinArray['condition']     = array($column);

		return $this;
	}


	/**
	 * @return array
	 */
	public function getJoinArray()
	{
		return $this->joinArray;
	}


	/**
	 * Equivalent to Join::getJoinArray method.
	 * Is required for the ReBuildableExpressionInterface.
	 *
	 * @return array
	 */
	public function getExpressionArray()
	{
		return $this->getJoinArray();
	}


	/**
	 * Set the join array value with key way and tables.
	 * Throw an exception if not each element of args is of type table interface.
	 *
	 * @param string           $way            The way in which the table(s) should joined.
	 * @param TableInterface   $table          The table in which should joined.
	 * @param TableInterface[] $optionalTables (Optional) Array with table objects.
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	private function _setWayValue($way, TableInterface $table, array $optionalTables = array())
	{
		$this->joinArray['way']    = $way;
		$this->joinArray['tables'] = array($table);
		foreach($optionalTables as $arg)
		{
			if(!$arg instanceof TableInterface)
			{
				throw new \InvalidArgumentException('arguments has to be of type table interface');
			}
			$this->joinArray['tables'][] = $arg;
		}

		return $this;
	}


	/**
	 * Check the passed arguments and throw an exception if necessary.
	 *
	 * @param ColumnInterface $firstColumn
	 * @param ColumnInterface $secondColumn
	 *
	 * @throws \Exception
	 */
	private function _validateOnArguments(ColumnInterface $firstColumn, ColumnInterface $secondColumn)
	{
		$initialJoinArray = array('way' => null, 'tables' => null, 'conditionType' => null, 'condition' => null);
		if($this->joinArray === $initialJoinArray)
		{
			throw new \Exception('method to set join way have call before!');
		}
		if(!$firstColumn->getTableName() || !$secondColumn->getTableName())
		{
			throw new \Exception('column arguments require a referenced table!');
		}
//		if($firstColumn->getColumnName() !== $secondColumn->getColumnName())
//		{
//			throw new \Exception('column arguments require a equivalent column names!');
//		}
	}


	/**
	 * Validate the way argument of the natural method.
	 * If the passed value is not equal to 'inner', 'left', 'leftOuter', 'right', 'rightOuter', an
	 * exception will thrown.
	 *
	 * @param string|null $way Value have to be whether inner, left[Outer] or right[Outer].
	 *
	 * @return string|null
	 */
	private function _validateNaturalWayArgument($way)
	{
		if(!$way)
		{
			return null;
		}
		if($way !== 'inner' && $way !== 'left' && $way !== 'leftOuter' && $way !== 'right' && $way !== 'rightOuter')
		{
			throw new \UnexpectedValueException('the way argument has to be type of inner, left[Outer], right[Outer]');
		}

		return $way;
	}
}
