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

namespace Alcys\Core\Db\Service;

use Alcys\Core\Db\Facade\DeleteFacade;
use Alcys\Core\Db\Facade\InsertFacade;
use Alcys\Core\Db\Facade\SelectFacade;
use Alcys\Core\Db\Facade\UpdateFacade;
use Alcys\Core\Db\Factory\DbFactory;

class AlcysDb
{
	/**
	 * @var DbFactory
	 */
	private $dbFactory;

	/**
	 * @var \PDO
	 */
	private $pdo;


	/**
	 * Initialize the database api.
	 *
	 * @param string $connectionString
	 * @param string $user
	 * @param string $pw
	 */
	public function __construct($connectionString, $user, $pw)
	{
		$this->pdo       = new \PDO($connectionString, $user, $pw);
		$this->dbFactory = new DbFactory();
	}


	/**
	 * Get an prepare instance of a select facade for an easy access to the database.
	 *
	 * @param string      $tableName  The name of the table from which should select.
	 * @param string|null $tableAlias (Optional) If exists an alias name.
	 *
	 * @return SelectFacade An object with helper method to communicate with your database.
	 */
	public function select($tableName, $tableAlias = null)
	{
		return $this->dbFactory->facade('Select', $this->pdo, $tableName, $tableAlias);
	}


	/**
	 * Get an prepare instance of a update facade for an easy access to the database.
	 *
	 * @param string $table The name of the table which should get an update.
	 *
	 * @return UpdateFacade An object with helper method to communicate with your database.
	 */
	public function update($table)
	{
		return $this->dbFactory->facade('Update', $this->pdo, $table);
	}


	/**
	 * Get an prepare instance of a insert facade for an easy access to the database.
	 *
	 * @param string $table The name of the table in which the entries should inserted.
	 *
	 * @return InsertFacade An object with helper method to communicate with your database.
	 */
	public function insert($table)
	{
		return $this->dbFactory->facade('Insert', $this->pdo, $table);
	}


	/**
	 * Get an prepare instance of a delete facade for an easy access to the database.
	 *
	 * @param string $table The name of the table from which should delete.
	 *
	 * @return DeleteFacade An object with helper method to communicate with your database.
	 */
	public function delete($table)
	{
		return $this->dbFactory->facade('Delete', $this->pdo, $table);
	}
}