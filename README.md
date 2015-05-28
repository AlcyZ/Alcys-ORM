Alcys-ORM
=========
Ein PHP-ORM System, Test Driven Entwickelt, 96% Code-Coverage und inklusive API Dokumentation.

Einleitung
==========
Man kann einfach den Autoloader bzw. jeden anderen PSR-0 kompatiblen Autoloader benutzen und ihn registrieren.

```php
<?php
require_once 'Autoloader.php';

spl_autoload_register(
	array('Autoloader', 'load')
);
```
