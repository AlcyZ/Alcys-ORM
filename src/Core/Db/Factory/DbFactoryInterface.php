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

use Alcys\Core\Db\Facade\ConditionFacade;
use Alcys\Core\Db\Facade\DeleteFacadeInterface;
use Alcys\Core\Db\Facade\InsertFacadeInterface;
use Alcys\Core\Db\Facade\SelectFacadeInterface;
use Alcys\Core\Db\Facade\UpdateFacadeInterface;
use Alcys\Core\Db\Statement\StatementInterface;
use Alcys\Core\Db\QueryBuilder\MySql\DeleteBuilder;
use Alcys\Core\Db\QueryBuilder\MySql\InsertBuilder;
use Alcys\Core\Db\QueryBuilder\SelectBuilder;
use Alcys\Core\Db\QueryBuilder\UpdateBuilder;
use Alcys\Core\Db\References\MySql\ReferencesInterface;
use Alcys\Core\Db\Expression\ExpressionInterface;

/**
 * Interface DbFactoryInterface
 * @package Alcys\Core\Db\Factory
 */
interface DbFactoryInterface
{
	/**
	 * Create instances of the type expression.
	 *
	 * @param string $name The name of the expression.
	 *
	 * @return ExpressionInterface An instance of a condition expression..
	 * @throws \Exception
	 */
	public function expression($name);


	/**
	 * Create an instance of the expected statement type.
	 *
	 * @param string $name The name of the statement type.
	 *
	 * @return StatementInterface A statement entity.
	 * @throws \Exception
	 */
	public function statement($name);


	/**
	 * Create instances of the type references.
	 *
	 * @param string $name The name of the expression.
	 *
	 * @return ReferencesInterface An instance of the expected reference type which implements a ReferencesInterface.
	 * @throws \Exception
	 */
	public function references($name);


	/**
	 * Create an instance of a query builder.
	 *
	 * @param StatementInterface $statement The statement value object, from which should the a query build.
	 *
	 * @return DeleteBuilder|InsertBuilder|SelectBuilder|UpdateBuilder    An instance of QueryBuilder,
	 *                                                                    switched by the passed argument.
	 */
	public function builder(StatementInterface $statement);


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
	public function facade($name, \PDO $connection, $tableName, $tableAlias = null);


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
	public function expressionFacade($name);
}