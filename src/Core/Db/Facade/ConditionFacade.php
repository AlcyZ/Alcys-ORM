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

use Alcys\Core\Db\Expression\Condition;
use Alcys\Core\Db\Expression\ConditionInterface;
use Alcys\Core\Db\Factory\DbFactoryInterface;
use Alcys\Core\Db\References\ColumnInterface;
use Alcys\Core\Db\References\MySql\WhereNumericCompareInterface;

/**
 * Class ConditionFacade
 * Todo Implement interface.
 * Todo Replace PHPDocs at condition property, set interface name instead of class name.
 * @package Alcys\Core\Db\Facade
 */
class ConditionFacade
{
	/**
	 * @var Condition
	 */
	private $condition;

	/**
	 * @var DbFactoryInterface
	 */
	private $factory;


	/**
	 * Initialize the facade object.
	 * The constructor arguments will passed from the method which create the instance.
	 *
	 * @param ConditionInterface $condition A clean instance of a condition interface.
	 * @param DbFactoryInterface $dbFactory The db factory for the creation of reference object.
	 */
	public function __construct(ConditionInterface $condition, DbFactoryInterface $dbFactory)
	{
		$this->condition = $condition;
		$this->factory   = $dbFactory;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column = $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function equal($column, $compareValue, $type = null)
	{
		$preparedObjectsArray = $this->_getPrepareObjectsArray($column, $compareValue, $type);
		$this->condition->equal($preparedObjectsArray['column'], $preparedObjectsArray['compareValue']);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column != $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function notEqual($column, $compareValue, $type = null)
	{
		$preparedObjectsArray = $this->_getPrepareObjectsArray($column, $compareValue, $type);
		$this->condition->notEqual($preparedObjectsArray['column'], $preparedObjectsArray['compareValue']);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column > $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function greater($column, $compareValue, $type = null)
	{
		$preparedObjectsArray = $this->_getPrepareObjectsArray($column, $compareValue, $type);
		$this->condition->greater($preparedObjectsArray['column'], $preparedObjectsArray['compareValue']);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column >= $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function greaterEqual($column, $compareValue, $type = null)
	{
		$preparedObjectsArray = $this->_getPrepareObjectsArray($column, $compareValue, $type);
		$this->condition->greaterEqual($preparedObjectsArray['column'], $preparedObjectsArray['compareValue']);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column < $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function lower($column, $compareValue, $type = null)
	{
		$preparedObjectsArray = $this->_getPrepareObjectsArray($column, $compareValue, $type);
		$this->condition->lower($preparedObjectsArray['column'], $preparedObjectsArray['compareValue']);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column <= $compareValue.
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column       The column name which should compare.
	 * @param string      $compareValue The value for the comparison.
	 * @param string|null $type         (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function lowerEqual($column, $compareValue, $type = null)
	{
		$preparedObjectsArray = $this->_getPrepareObjectsArray($column, $compareValue, $type);
		$this->condition->lowerEqual($preparedObjectsArray['column'], $preparedObjectsArray['compareValue']);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column BETWEEN $firstCompareValue AND $secondCompareValue
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column             The column name which should compare.
	 * @param string      $firstCompareValue  The first value for the comparison.
	 * @param string      $secondCompareValue The second value for the comparison.
	 * @param string|null $type               (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function between($column, $firstCompareValue, $secondCompareValue, $type = null)
	{
		/** @var ColumnInterface $columnObj */
		/** @var WhereNumericCompareInterface $firstCompareObj */
		/** @var WhereNumericCompareInterface $secondCompareObj */

		$columnObj = $this->factory->references('Column', $column);
		if($type === 'column')
		{
			$firstCompareObj  = $this->factory->references('Column', $firstCompareValue);
			$secondCompareObj = $this->factory->references('Column', $secondCompareValue);
		}
		else
		{
			$firstCompareObj  = $this->factory->references('Value', $firstCompareValue);
			$secondCompareObj = $this->factory->references('Value', $secondCompareValue);
		}


		$this->condition->between($columnObj, $firstCompareObj, $secondCompareObj);

		return $this;
	}


	/**
	 * Compare in the following style in the where condition:
	 * $column NOT BETWEEN $firstCompareValue AND $secondCompareValue
	 * When the type argument isset to 'column', the second argument will interpreted as column.
	 * Otherwise it is like a string or value comparison.
	 *
	 * @param string      $column             The column name which should compare.
	 * @param string      $firstCompareValue  The first value for the comparison.
	 * @param string      $secondCompareValue The second value for the comparison.
	 * @param string|null $type               (Optional) Set to 'column' for a comparison with two columns.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function notBetween($column, $firstCompareValue, $secondCompareValue, $type = null)
	{
		/** @var ColumnInterface $columnObj */
		/** @var WhereNumericCompareInterface $firstCompareObj */
		/** @var WhereNumericCompareInterface $secondCompareObj */

		$columnObj = $this->factory->references('Column', $column);
		if($type === 'column')
		{
			$firstCompareObj  = $this->factory->references('Column', $firstCompareValue);
			$secondCompareObj = $this->factory->references('Column', $secondCompareValue);
		}
		else
		{
			$firstCompareObj  = $this->factory->references('Value', $firstCompareValue);
			$secondCompareObj = $this->factory->references('Value', $secondCompareValue);
		}


		$this->condition->notBetween($columnObj, $firstCompareObj, $secondCompareObj);

		return $this;
	}


	/**
	 * Use this method to set another comparison in connection with a logical and to the condition.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function logicAnd()
	{
		$this->condition->_and();

		return $this;
	}


	/**
	 * Use this method to set another comparison in connection with a logical and to the condition.
	 *
	 * @return $this The same instance to concatenate methods.
	 */
	public function logicOr()
	{
		$this->condition->_or();

		return $this;
	}


	/**
	 * @return Condition
	 */
	public function getCondition()
	{
		return $this->condition;
	}


	/**
	 * Build objects from the passed arguments.
	 * The values will returned as assoc array in the following form:
	 * array('column' => $column, 'compareValue' = $value).
	 * $column is of type References\Column. When the type argument is equal to 'column',
	 * $value is also of type References\Column, otherwise of References\Value.
	 * The
	 *
	 * @param string $column       Name of the column for the comparison.
	 * @param string $compareValue Compare value.
	 * @param string $type         Type of the compare value, whether column or usual value.
	 *
	 * @return array Array with prepared objects.
	 */
	private function _getPrepareObjectsArray($column, $compareValue, $type)
	{
		$columnObj = $this->factory->references('Column', $column);
		if($type === 'column')
		{
			$compareObj = $this->factory->references('Column', $compareValue);
		}
		else
		{
			$compareObj = $this->factory->references('Value', $compareValue);
		}

		return array('column' => $columnObj, 'compareValue' => $compareObj);
	}
}