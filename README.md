Alcys-ORM
=========
Ein PHP-ORM System, Test Driven Entwickelt, 100% Code-Coverage und inklusive API Dokumentation.
Momentant wird nur MySql unterstützt!

Einbindung
----------
Man kann einfach den Autoloader bzw. jeden anderen PSR-0 kompatiblen Autoloader benutzen und ihn registrieren.

```php
<?php
require_once 'Autoloader.php';

spl_autoload_register(
	array('Autoloader', 'load')
);

Autoloader::register('src/');
```
Eine etwas genauere Beschreibung folgt wenn ich die Zeit dafür finde. Außerdem bietet sie aktuell 
(in der ersten Stabilen Version) noch nicht viel Extra-Funktionalitäten. Trotzdem sollte der Funkionsumfang
schon für die meisten Projekte reichen, sofern nicht zu komplexe MySql-Querys benötigt werden.

Benutzung
---------
Man kann sich einfach ein Objekt der Klasse Alcys\Core\Db\Service\AlcysDb instantiieren. Es verfügt über Methoden,
die Objekte zur Ausführung von SELECT, UPDATE, INSERT und DELETE Statements zurückgeben.
```php
$db = new Alcys\Core\Db\Service\AlcysDb('mysql:host=localhost;dbname=db_name', 'db_user', 'db_password')

$select = $db->select('table_name'); # Object for select statements
$update = $db->update('table_name'); # Object for update statements
$insert = $db->insert('table_name'); # Object for insert statements
$delete = $db->delete('table_name'); # Object for delete statements
```

Jedes dieser Objekte verfügt über Methoden um den Query Objekt-Orientiert zu erzeugen. Um den Befehl auszuführen, stehen
folgende Methoden zur Verfügung:
```php
$select->fetch();
$update->execute();
$insert->execute();
$delete->execute();
```

Es wird immer der Tabellenname genommen, der im Constructor übergeben worden ist. Zusätzliche Tabellen können mithilfe 
der table() Methode gesetzt werden.
Bei einem Select Befehl sind, falls nicht anders mit Hilfe der column() Methode gesetzt, alle Spalten (\*) gewählt.

Beispiele
---------
Simpler Select Befehl:
```php
# SELECT `test_column`, `column` AS `clm` FROM `test_table` AS `my_table` ORDER BY `clm` DESC;
$select = $db->select(`test_table`, `my_table`);

$resultArray = $select->column('test_column')
					  ->column('column', 'clm')
					  ->orderBy('clm', 'desc')
					  ->fetch();
```

Simpler Update Befehl:
```php
# UPDATE `test_table` SET `column` = "value", `clm` = "val" LIMIT 4, 15;
$update = $db->update(`test_table`);

$update->column('column')->value('value')
       ->column('clm')->value('val')
       ->limit('4', '15')
       ->execute();
```

Simpler Insert Befehl:
```php
# INSERT INTO `test_table` (`column`, `clm`) VALUES ("val", "value"), ("any_value", "this_value");
$insert = $db->insert(`test_table`);

$insert->columns(array('column', 'clm'))
       ->values(array('val', 'value'))
       ->values(array('any_value', 'this_value'))
       ->execute();
```

Simpler Delete Befehl:
```php
# DELETE FROM `test_table` ORDER BY `column` ASC LIMIT 0, 5;
$delete = $db->delete(`test_table`);

$delete->orderBy('column')
       ->limit('0', '5')
       ->execute();
```

Where Bedingungen
-----------------
Alle Statement Objekte, außer das InsertStatement, verfügen über die 'condition' Methode. Sie gibt ein Objekt zurück,
dass Methoden zur Erstellung einer Bedingung beinhaltet. Das Objekt übergibt man anschließend einfach der 'where' Methode.
```php
# WHERE `column_name` != 'value'

$delete->where($delete->condition()->notEqual('column_name', 'value'));
```

Falls man zwei Spalten mit einander vergleichen möchte, übergibt man als dritten Parameter einfach 'column'.
```php
# WHERE `first_column` >= `second_column`

$update->where($update->condition()->greaterEqual('first_column, 'second_column', 'column');
```

Wenn man mehrere Bedingungen setzen möchte, dann kann man einfach zur Verknüpfung die Methoden 'logicAnd' und 'logicOr' aufrufen.
Sie müssen zwischen jeder Bedingung stehen, sonst wird eine Exception geworfen.
```php
# WHERE `first_column` = 'a' AND `cl` != `clm` OR `column` < 5

$condition = $select->condition();
$condition->equal('first_column', 'a')->logicAnd()->notEqual('cl', 'clm', 'column')->logicOr()->lower('column', 5);

$select->where($condition)->fetch();
```

Bedingungen mit like folgt ..


Joins
-----
In der aktuellen Version funktionieren Joins nur bei Select Statement. Die funktionalität für weitere Statements wird aber noch kommen.

Das Select Statement verfügt über die Methode 'joinBuilder', die ein Objekt zur Erstellung des Joins zurückgibt, was der 'join' Methoden
übergeben wird.

```php
# LEFT JOIN `table_name` USING `column`

$select->join($select->joinBuilder()->left('table_name')->using('column'));
```

Bei der 'on' Methode müssen als Parameter zwei Assoc-Arrays übergeben werden, in denen der key 'table' und 'column' existiert,
sonst wird eine Exception geworfen.
```php
# INNER JOIN `table` ON (`tbl`.`cl`, `table`.`clm`);

$firstColumn = array('table' => 'tbl', 'column' => 'cl');
$secondColumn = array('table' => 'table', 'column' => 'clm');

$select->join($select->joinBuilder()->inner('table')->on($firstColumn, $secondColumn));

```


Bevor man die Methode 'on' oder 'using' aufruft, muss entweder 'inner', 'left[Outer]' oder 'right[Outer]' aufgerufen
werden, ansonsten wird eine Exception geworfen!

Wenn man eine Tabelle mit allen equivalenten Spaltennamen joinen möchte, kann man die 'natural' Methode benutzen.
Als ersten Parameter gibt man den Tabellennamen an, als zweiten kann Optional entweder 'inner', 'left[Outer]' oder 'right[Outer]' gesetzt werden.
```php
# NATURAL JOIN `table`
$select->join($select->joinBuilder()->natural('table'));

# NATURAL RIGHT JOIN `table`
$select->join($select->joinBuilder()->natural('table', 'right'));
```
