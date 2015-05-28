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
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\References\MySql\WhereCompareInterface;
use Alcys\Core\Db\References\MySql\WhereNumericCompareInterface;

/**
 * Class Condition
 * @Todo    Add comparison "isNull".
 * @package Alcys\Core\Db\Condition
 */
class Condition implements ConditionInterface, ComparisonInterface, LogicConnectorInterface, ExpressionInterface
{
	/**
	 *    array(
	 *        array('`column` = "val"'),
	 *        array('and' => '`other_column` != "value"'),
	 *        array('and' => '`other_column` > "0'),
	 *        array('or' => '`other_column` = "value"')
	 *    );
	 * @var array Below you can see the expected array format after condition method invokes.
	 */
	private $conditionArray = array();

	/**
	 * This value is after initializing false and after the first invoke of a condition method
	 * true. This is required, to get the condition array in the expected format.
	 *
	 * @var bool True when a condition method was invoked
	 */
	private $invoked = false;

	/**
	 * Represent the logic operator, which is whether 'and' or 'or'. After a condition method
	 * was invoked, the value will unset to null. This is required, to get the condition array
	 * in the expected format.
	 *
	 * @var string Enum with the value 'and' or 'or'.
	 */
	private $logicOperator;


	/**
	 * Add an equal condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface     $column Name of the column.
	 * @param WhereCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function equal(ColumnInterface $column, WhereCompareInterface $value)
	{
		$columnName = ($column->getAlias()) ? $column->getAlias() : $column->getColumnName();
		$condition  = $columnName . ' = ' . $value->getCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a not equal condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface     $column Name of the column.
	 * @param WhereCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function notEqual(ColumnInterface $column, WhereCompareInterface $value)
	{
		$condition = $column->getColumnName() . ' != ' . $value->getCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a greater condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function greater(ColumnInterface $column, WhereNumericCompareInterface $value)
	{
		$condition = $column->getColumnName() . ' > ' . $value->getNumericCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a greater or equal condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function greaterEqual(ColumnInterface $column, WhereNumericCompareInterface $value)
	{
		$condition = $column->getColumnName() . ' >= ' . $value->getNumericCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a lower condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function lower(ColumnInterface $column, WhereNumericCompareInterface $value)
	{
		$condition = $column->getColumnName() . ' < ' . $value->getNumericCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a lower or equal condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface              $column Name of the column.
	 * @param WhereNumericCompareInterface $value  Value for the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function lowerEqual(ColumnInterface $column, WhereNumericCompareInterface $value)
	{
		$condition = $column->getColumnName() . ' <= ' . $value->getNumericCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a between condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface     $column       Name of the column.
	 * @param WhereNumericCompareInterface $lowerValue   The lower value of the expression.
	 * @param WhereNumericCompareInterface $greaterValue The higher value of the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function between(ColumnInterface $column,
	                        WhereNumericCompareInterface $lowerValue,
	                        WhereNumericCompareInterface $greaterValue)
	{
		$condition = $column->getColumnName() . ' BETWEEN ' . $lowerValue->getNumericCompareValue() . ' AND '
		             . $greaterValue->getNumericCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a not between condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface              $column       Name of the column.
	 * @param WhereNumericCompareInterface $lowerValue   The lower value of the expression.
	 * @param WhereNumericCompareInterface $greaterValue The higher value of the expression.
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function notBetween(ColumnInterface $column,
	                           WhereNumericCompareInterface $lowerValue,
	                           WhereNumericCompareInterface $greaterValue)
	{
		$condition = $column->getColumnName() . ' NOT BETWEEN ' . $lowerValue->getNumericCompareValue() . ' AND '
		             . $greaterValue->getNumericCompareValue();
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Add a like condition to the condition array.
	 * The order of the method call is important, in which way the condition string will
	 * concatenate.
	 *
	 * @param ColumnInterface     $column Name of the column.
	 * @param ReferencesInterface $value  Value for the expression.
	 * @param int                 $level  The several levels of a like expression, see private likeLevel - methods.
	 *
	 * @see Alcys\Core\Db\Condition\Condition::_likeLevelOne
	 * @see Alcys\Core\Db\Condition\Condition::_likeLevelTwo
	 * @see Alcys\Core\Db\Condition\Condition::_likeLevelThree
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 * @throws \Exception
	 */
	public function like(ColumnInterface $column, ReferencesInterface $value, $level = 0)
	{
		if($level === 0)
		{
			$condition = $column->getColumnName() . ' LIKE ' . $value->getValue();
		}
		elseif($level === 1)
		{
			$condition = $column->getColumnName() . ' LIKE ' . $this->_likeLevelOne($value);
		}
		elseif($level === 2)
		{
			$condition = $column->getColumnName() . ' LIKE ' . $this->_likeLevelTwo($value);
		}
		elseif($level === 3)
		{
			$condition = $column->getColumnName() . ' LIKE ' . $this->_likeLevelThree($value);
		}
		else
		{
			throw new \Exception('invalid level argument');
		}
		$this->_addCondition($condition);

		return $this;
	}


	/**
	 * Set the property value of Alcys\Core\Db\Condition\Condition::logicOperator (the actual instance) to 'and'.
	 *
	 * This logic operator method should called after a condition method, otherwise
	 * a exception will thrown.
	 *
	 * @see Alcys\Core\Db\Condition\Condition::logicOperator
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 */
	public function _and()
	{
		$this->logicOperator = 'and';

		return $this;
	}


	/**
	 * Set the property value of Alcys\Core\Db\Condition\Condition::logicOperator (the actual instance) to 'or'.
	 *
	 * This logic operator method should called after a condition method, otherwise
	 * a exception will thrown.
	 *
	 * @see Alcys\Core\Db\Condition\Condition::logicOperator
	 *
	 * @return $this The current condition instance to concatenate method invokes.
	 */
	public function _or()
	{
		$this->logicOperator = 'or';

		return $this;
	}


	/**
	 * Return a prepared array for the Builder.
	 *
	 * The array is sorted by the order of the invoked methods.
	 * Should only invoked from a QueryBuilder.
	 *
	 * @return array Valid formatted array.
	 */
	public function getConditionArray()
	{
		return $this->conditionArray;
	}


	/**
	 * Return a prepared array for the Builder.
	 *
	 * The array is sorted by the order of the invoked methods.
	 * Should only invoked from a QueryBuilder.
	 * Equivalent of the getConditionArray method, required for
	 * expression interface.
	 *
	 * @return array Valid formatted array.
	 */
	public function getExpressionArray()
	{
		return $this->getConditionArray();
	}


	/**
	 * This method fill the condition array in the expected format.
	 *
	 * If no logic operator method was invoked after a condition method, it will
	 * throw an exception. Otherwise, after the first call, a condition without
	 * an logic operator will add to the condition array and at the others calls,
	 * a condition with an logic operator will add to the condition array.
	 *
	 * @param string $condition
	 *
	 * @throws \Exception
	 */
	private function _addCondition($condition)
	{
		if($this->logicOperator === null && $this->invoked === false)
		{
			$this->conditionArray[] = array($condition);
			$this->invoked          = true;
		}
		elseif($this->logicOperator === null)
		{
			throw new \Exception('a logic operator method have to be invoked to continue add conditions');
		}
		else
		{
			$this->conditionArray[] = array($this->logicOperator => $condition);
			$this->logicOperator    = null;
		}
	}


	/**
	 * Validate the value and the percentage sign to a valid value for an expression.
	 * The sign will passed at the begin of the value.
	 *
	 * Example:    Value: 123 will validated to: %123.
	 *             Value: "a" will validated to: "%a".
	 *
	 * @param ReferencesInterface $value The value for the expression.
	 *
	 * @return string
	 */
	private function _likeLevelOne(ReferencesInterface $value)
	{
		$regex = '/"/';
		if(preg_match($regex, $value->getValue()))
		{
			return preg_replace($regex, '"%', $value->getValue(), 1);
		}

		return '%' . $value->getValue();
	}


	/**
	 * Validate the value and the percentage sign to a valid value for an expression.
	 * The sign will passed at the end of the value.
	 *
	 * Example:    Value: 123 will validated to: 123%.
	 *             Value: "a" will validated to: "a%".
	 *
	 * @param ReferencesInterface $value The value for the expression.
	 *
	 * @return string
	 */
	private function _likeLevelTwo(ReferencesInterface $value)
	{
		$regex   = '/("[a-zA-Z0-9_-]*)"/';
		$matches = array();
		if(preg_match($regex, $value->getValue(), $matches))
		{
			return $matches[1] . '%"';
		}

		return $value->getValue() . '%';
	}


	/**
	 * Validate the value and the percentage sign to a valid value for an expression.
	 * The sign will passed at the begin and at the end of the value.
	 *
	 * Example:    Value: 123 will validated to: 123%.
	 *             Value: "a" will validated to: "a%".
	 *
	 * @param ReferencesInterface $value The value for the expression.
	 *
	 * @return string
	 */
	private function _likeLevelThree(ReferencesInterface $value)
	{
		$regex   = '/"([a-zA-Z0-9_-]*)"/';
		$matches = array();
		if(preg_match($regex, $value->getValue(), $matches))
		{
			return '"%' . $matches[1] . '%"';
		}

		return '%' . $value->getValue() . '%';
	}
}
