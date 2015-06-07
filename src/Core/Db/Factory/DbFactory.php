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

namespace Alcys\Core\Db\Factory;

use Alcys\Core\Db\Expression\Condition;
use Alcys\Core\Db\Expression\ExpressionInterface;
use Alcys\Core\Db\Expression\Join;
use Alcys\Core\Db\Facade\ConditionFacade;
use Alcys\Core\Db\Facade\DeleteFacade;
use Alcys\Core\Db\Facade\DeleteFacadeInterface;
use Alcys\Core\Db\Facade\InsertFacade;
use Alcys\Core\Db\Facade\InsertFacadeInterface;
use Alcys\Core\Db\Facade\SelectFacade;
use Alcys\Core\Db\Facade\SelectFacadeInterface;
use Alcys\Core\Db\Facade\UpdateFacade;
use Alcys\Core\Db\Facade\UpdateFacadeInterface;
use Alcys\Core\Db\QueryBuilder\MySql\DeleteBuilder;
use Alcys\Core\Db\QueryBuilder\MySql\InsertBuilder;
use Alcys\Core\Db\QueryBuilder\MySql\SelectBuilder;
use Alcys\Core\Db\QueryBuilder\MySql\UpdateBuilder;
use Alcys\Core\Db\References\MySql\Column;
use Alcys\Core\Db\References\MySql\OrderModeEnum;
use Alcys\Core\Db\References\MySql\SqlFunction;
use Alcys\Core\Db\References\MySql\Table;
use Alcys\Core\Db\References\MySql\Value;
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\Statement\Delete;
use Alcys\Core\Db\Statement\Insert;
use Alcys\Core\Db\Statement\Select;
use Alcys\Core\Db\Statement\StatementInterface;
use Alcys\Core\Db\Statement\Update;

/**
 * Class DbFactory
 * @Todo    Specify db type (actually only mysql, but should be more).
 * @package Alcys\Core\Db\Factory
 */
class DbFactory implements DbFactoryInterface
{
	/**
	 * Create instances of the type expression.
	 * When the expected condition class is not found, an exception will thrown.
	 *
	 * @param string $name The name of the expression.
	 *
	 * @see Alcys\Core\Db\Factory\DbFactory::_invokeCreateMethod
	 *
	 * @return ExpressionInterface An instance of a condition expression..
	 * @throws \Exception
	 */
	public function expression($name)
	{
		return $this->_invokeCreateMethod(ucfirst(explode('::', __METHOD__)[1]), $name, func_get_args());
	}


	/**
	 * Create instances of the type references.
	 * When the expected reference class is not found, an exception will thrown.
	 *
	 * @param string $name The name of the expression.
	 *
	 * @see Alcys\Core\Db\Factory\DbFactory::_invokeCreateMethod
	 *
	 * @return ReferencesInterface An instance of the expected reference type which implements a ReferencesInterface.
	 * @throws \Exception
	 */
	public function references($name)
	{
		return $this->_invokeCreateMethod(ucfirst(explode('::', __METHOD__)[1]), $name, func_get_args());
	}


	/**
	 * Create an instance of a query builder.
	 * Different between the several statement types and return the correct query builder for the passed statement.
	 *
	 * @param StatementInterface $statement The statement value object, from which should the a query build.
	 *
	 * @return DeleteBuilder|InsertBuilder|SelectBuilder|UpdateBuilder    An instance of QueryBuilder,
	 *                                                                    switched by the passed argument.
	 */
	public function builder(StatementInterface $statement)
	{
		if($statement instanceof Insert)
		{
			return new InsertBuilder($statement);
		}
		/** @var ExpressionBuilderFactoryInterface $factory */
		$factory = $this->expression('BuilderFactory');

		if($statement instanceof Select)
		{
			return new SelectBuilder($statement, $factory);
		}
		elseif($statement instanceof Update)
		{
			return new UpdateBuilder($statement, $factory);
		}
		else
		{
			/** @var Delete $statement */
			return new DeleteBuilder($statement, $factory);
		}
	}


	/**
	 * Create an instance of the expected statement type.
	 * When no type with the passed name is found, an exception will thrown.
	 *
	 * @param string $name The name of the statement type.
	 *
	 * @return StatementInterface A statement entity.
	 * @throws \Exception
	 */
	public function statement($name)
	{
		return $this->_invokeCreateMethod(ucfirst(explode('::', __METHOD__)[1]), $name, func_get_args());
	}


	/**
	 * Create instance of facade objects.
	 * There are four types of facade objects that could created:
	 * 1.SelectFacade, 2. UpdateFacade, 3. InsertFacade and 4. DeleteFacade.
	 * Usage: $factory->facade('select', $pdo, $tableName); or similar variants.
	 *
	 * @param string      $name       Whether 'select', 'update', 'insert' or 'delete'.
	 * @param \PDO        $connection An instance of a PDO connection.
	 * @param string      $tableName  Name of the 'main'-table for the query.
	 * @param string|null $tableAlias (Optional) If exists, an alias name can also set.
	 *
	 * @return DeleteFacadeInterface|InsertFacadeInterface|SelectFacadeInterface|UpdateFacadeInterface
	 * @throws \Exception
	 */
	public function facade($name, \PDO $connection, $tableName, $tableAlias = null)
	{
		if(ucfirst($name) === 'Select')
		{
			return new SelectFacade($connection, $this->statement('Select'), $this, $tableName, $tableAlias);
		}
		elseif(ucfirst($name) === 'Insert')
		{
			return new InsertFacade($connection, $this->statement('Insert'), $this, $tableName);
		}
		elseif(ucfirst($name) === 'Update')
		{
			return new UpdateFacade($connection, $this->statement('Update'), $this, $tableName, $tableAlias);
		}
		elseif(ucfirst($name) === 'Delete')
		{
			return new DeleteFacade($connection, $this->statement('Delete'), $this, $tableName, $tableAlias);
		}
		throw new \Exception('No facade object with the name "' . ucfirst($name) . 'Facade" was found');
	}


	/**
	 * Create instance of expression facade objects.
	 * The difference between this and the facade method
	 * is, that the other one is for statements and this
	 * method for expression facade objects.
	 *
	 * @param string $name Name of the facade object.
	 *
	 * @return ConditionFacade
	 * @throws \Exception
	 */
	public function expressionFacade($name)
	{
		return $this->_invokeCreateMethod(ucfirst(explode('::', __METHOD__)[1]), $name, func_get_args());
	}


	/**
	 * Create an instance of a condition facade.
	 *
	 * @param array $args Get from func_get_arguments
	 *
	 * @return ConditionFacade
	 */
	public function _createExpressionFacadeCondition($args)
	{
		if(!$args[1] instanceof Condition)
		{
			throw new \InvalidArgumentException('The second argument must be of type Expression\Condition');
		}

		return new ConditionFacade($args[1], $this);
	}


	/**
	 * Invoke the expected private create method.
	 *
	 * @param string $type      The type, like 'statement' or 'queryBuilder' of the instance that should create.
	 * @param string $name      The specific name of the class that will instantiating.
	 * @param array  $arguments Arguments that should passed to the constructor of the created object.
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	private function _invokeCreateMethod($type, $name, array $arguments)
	{
		$createMethodName = '_create' . $type . $name;
		if(method_exists($this, $createMethodName))
		{
			return $this->$createMethodName($arguments);
		}
		throw new \Exception('no creation method for the ' . strtolower($type) . ' class ' . $name . ' found!');
	}


	/**
	 * Create an instance of a condition object.
	 *
	 * @return Condition An empty condition value object.
	 */
	private function _createExpressionCondition()
	{
		return new Condition();
	}


	/**
	 * Create an instance of a join object.
	 *
	 * @return Join An empty condition value object.
	 */
	private function _createExpressionJoin()
	{
		return new Join();
	}


	/**
	 * Create an instance of a expression builder factory object.
	 *
	 * @return Join An empty expression builder factory.
	 */
	private function _createExpressionBuilderFactory()
	{
		return new ExpressionBuilderFactory();
	}


	/**
	 * Create an instance of a select statement object.
	 *
	 * @return Select An empty select statement entity.
	 */
	private function _createStatementSelect()
	{
		return new Select();
	}


	/**
	 * Create an instance of an insert statement object.
	 *
	 * @return Insert An empty insert statement entity.
	 */
	private function _createStatementInsert()
	{
		return new Insert();
	}


	/**
	 * Create an instance of an update statement object.
	 *
	 * @return Update An empty update statement entity.
	 */
	private function _createStatementUpdate()
	{
		return new Update();
	}


	/**
	 * Create an instance of a delete statement object.
	 *
	 * @return Delete An empty delete statement entity.
	 */
	private function _createStatementDelete()
	{
		return new Delete();
	}


	/**
	 * Create an instance of a column references.
	 *
	 * @param array $args The arguments that are passed from the clients executed method. Bundled in an array.
	 *
	 * @return Column The validated column object, that can easily parsed to a string with casting (string).
	 * @throws \Exception
	 */
	private function _createReferencesColumn(array $args)
	{
		$this->_checkSecondArgument($args);
		$table = (isset($args[2])) ? $args[2] : null;
		$alias = (isset($args[3])) ? $args[3] : null;

		return new Column($args[1], $table, $alias);
	}


	/**
	 * Create an instance of an orderModeEnum references object.
	 *
	 * @param array $args The arguments that are passed from the clients executed method. Bundled in an array.
	 *
	 * @return OrderModeEnum The validated order mode enum object, that can easily parsed to a string with casting
	 *                       (string).
	 * @throws \Exception
	 */
	private function _createReferencesOrderModeEnum(array $args)
	{
		$this->_checkSecondArgument($args);

		return new OrderModeEnum($args[1]);
	}


	/**
	 * Create an instance of a sql function references object.
	 *
	 * @param array $args The arguments that are passed from the clients executed method. Bundled in an array.
	 *
	 * @return SqlFunction The validated sql function object, that can easily parsed to a string with casting (string).
	 * @throws \Exception
	 */
	private function _createReferencesSqlFunction(array $args)
	{
		$this->_checkSecondArgument($args);
		if(isset($args[2]) && !is_array($args[2]))
		{
			throw new \Exception('sql function references argument have to be an array or null!');
		}
		$argArray = (isset($args[2])) ? $args[2] : array();

		return new SqlFunction($args[1], $argArray);
	}


	/**
	 * Create an instance of a table references object.
	 *
	 * @param array $args The arguments that are passed from the clients executed method. Bundled in an array.
	 *
	 * @return Table The validated table object, that can easily parsed to a string with casting (string).
	 * @throws \Exception
	 */
	private function _createReferencesTable(array $args)
	{
		$this->_checkSecondArgument($args);
		$alias = (isset($args[2])) ? $args[2] : null;

		return new Table($args[1], $alias);
	}


	/**
	 * Create an instance of a value references object.
	 *
	 * @param array $args The arguments that are passed from the clients executed method. Bundled in an array.
	 *
	 * @return Value The validated "value" object, that can easily parsed to a string with casting (string).
	 * @throws \Exception
	 */
	private function _createReferencesValue(array $args)
	{
		$this->_checkSecondArgument($args);

		return new Value($args[1]);
	}


	/**
	 * Check the second array element.
	 * If the element is null or not a string, an exception will thrown.
	 *
	 * @param array $args
	 *
	 * @throws \Exception
	 */
	private function _checkSecondArgument(array $args)
	{
		if(is_null($args[1]) || !is_string($args[1]))
		{
			throw new \Exception('the second argument must be set!');
		}
	}
}