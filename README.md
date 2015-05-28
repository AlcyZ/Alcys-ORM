Alcys-ORM
=========
Ein PHP-ORM System, Test Driven Entwickelt, 96% Code-Coverage und inklusive API Dokumentation.
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
Eine etwas genauere Beschreibung folgt wenn ich die Zeit dafür finde :) Außerdem bietet sie aktuell 
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

$delete->columns(array('column', 'clm'))
	   ->orderBy('column')
	   ->limit('0', '5');
```