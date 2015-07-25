Alcys-ORM
=========
A test driven developed PHP-ORM system, 100½ code coverage with API documentation.
Actually, i only support MySql, but other driver will follow.


Installation
------------
Download and extract the zip package. Copy the src/ directory to your project and use
a PSR-0 or PSR-4 autoloader to require the files. In the package root directory exist a
small and simple PSR-0 autoloader, which also could used.
```php
<?php
require_once 'Autoloader.php';

spl_autoload_register(
    array('Autoloader', 'load');
);

Autoloader::register('src/');
```

Usage
-----
Instantiate an object of the class Alcys\Core\Db\Service\AlcysDb. There are some methods
to create whether SELECT, INSERT, DELETE or UPDATE statement objects which communicate with the database.
```php
$db = new Alcys\Core\Db\Service\AlcysDb('mysql:host=localhost;dbname=db_name', 'db_user', 'db_password');

$select = $db->select('table_name'); # Object for select statements
$update = $db->update('table_name'); # Object for update statements
$insert = $db->insert('table_name'); # Object for insert statements
$delete = $db->delete('table_name'); # Object for delete statements
```

The table name is required to pass it as argument through the create method. To add other tables, 
simple use the table() method which exists in each statement object.
If the columns method, which only not exist in the delete statement, will not called, a wildcard (\*) will 
add for the columns.

Examples
--------
Simple select query:
```php
# SELECT `test_column`, `column` AS `clm` FROM `test_table` AS `my_table` ORDER BY `clm` DESC;
$select = $db->select(`test_table`, `my_table`);

$resultArray = $select->column('test_column')
					  ->column('column', 'clm')
					  ->orderBy('clm', 'desc')
					  ->fetch();
```

Simple update query:
```php
# UPDATE `test_table` SET `column` = "value", `clm` = "val" LIMIT 4, 15;
$update = $db->update(`test_table`);

$update->column('column')->value('value')
       ->column('clm')->value('val')
       ->limit('4', '15')
       ->execute();
```

Simple insert query:
```php
# INSERT INTO `test_table` (`column`, `clm`) VALUES ("val", "value"), ("any_value", "this_value");
$insert = $db->insert(`test_table`);

$insert->columns(array('column', 'clm'))
       ->values(array('val', 'value'))
       ->values(array('any_value', 'this_value'))
       ->execute();
```

Simple delete query:
```php
# DELETE FROM `test_table` ORDER BY `column` ASC LIMIT 0, 5;
$delete = $db->delete(`test_table`);

$delete->orderBy('column')
       ->limit('0', '5')
       ->execute();
```

Where-Conditions
----------------
All statement objects, instead of the InsertStatement, have a 'condition' method which return an object
for the creation of conditions. After call some methods of the object to build the condition, pass it to
the 'where' method.
```php
# WHERE `column_name` != 'value'

$delete->where($delete->condition()->notEqual('column_name', 'value'));
```

If you want to compare two columns, pass as third argument the string 'column'.
```php
# WHERE `first_column` >= `second_column`

$update->where($update->condition()->greaterEqual('first_column, 'second_column', 'column');
```

To connect multiple conditions, you have to invoke the methods 'logicAnd' and 'logicOr' between
the other condition methods.
If they not called between conditions, an exception will thrown.
```php
# WHERE `first_column` = 'a' AND `cl` != `clm` OR `column` < 5

$condition = $select->condition();
$condition->equal('first_column', 'a')->logicAnd()->notEqual('cl', 'clm', 'column')->logicOr()->lower('column', 5);

$select->where($condition)->fetch();
```

Like conditions will follow ..

Joins
-----
At the current version, joins are only implemented in the select statement. The functionality is implemented for other
statements in the next version.

The method 'joinBuilder' returns an object for the creation of the join expression. This have to pass to the statements 
'join' method.
```php
# LEFT JOIN `table_name` USING `column`

$select->join($select->joinBuilder()->left('table_name')->using('column'));
```

The arguments for the 'on' method has to be two assoc arrays with keys 'table' and 'column'.
If the arrays are invalid, an exception will thrown.
```php
# INNER JOIN `table` ON (`tbl`.`cl`, `table`.`clm`);

$firstColumn = array('table' => 'tbl', 'column' => 'cl');
$secondColumn = array('table' => 'table', 'column' => 'clm');

$select->join($select->joinBuilder()->inner('table')->on($firstColumn, $secondColumn));

```

Before calling the 'on' or 'using' method, you have to invoke whether the 'inner', 'left[Outer]' or 'right[Outer]' method,
otherwise an exception will thrown.

If you want to join a table with all equivalent column names, you can use the 'natural' method.
The first argument is the table name, the optional second can be whether 'inner', 'left[Outer]' or 'right[Outer].
```php
# NATURAL JOIN `table`
$select->join($select->joinBuilder()->natural('table'));

# NATURAL RIGHT JOIN `table`
$select->join($select->joinBuilder()->natural('table', 'right'));
```

