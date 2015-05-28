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
```php
# SELECT `test_column`, `column` AS `clm` FROM `test_table` AS `my_table` ORDER BY `clm` DESC;

```