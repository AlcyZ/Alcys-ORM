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

/**
 * Class AutoLoader
 * @Todo Actually not very flexible, will improved later!
 */
class AutoLoader
{
	/**
	 * @var array Registered classes are stored in this array.
	 */
	private static $classes = array();

	private static $scannedDirectories = array();


	/**
	 * Register the directory for the Autoloader.
	 * Scan all files inside the directory recursively and buffer them in the classes property.
	 *
	 * @param string $directory Absolute or relative path to directory that should scanned.
	 * @param bool   $first
	 */
	public static function register($directory, $first = true)
	{
		if($first)
		{
			self::$scannedDirectories[] = $directory;
		}
		$directory = trim($directory, '/');
		$dir       = dir(trim($directory));
		while(false !== $file = $dir->read())
		{
			if($file !== '.' && $file !== '..')
			{
				if(is_dir($directory . DIRECTORY_SEPARATOR . $file))
				{
					self::register($directory . DIRECTORY_SEPARATOR . $file, false);
				}
				elseif(is_file($directory . DIRECTORY_SEPARATOR . $file))
				{
					self::_asd($directory, $file);
				}
			}
		}
		$dir->close();
	}


	/**
	 * This method will call when the system try to instantiate a not included class or interface.
	 * The parsed class name should exist as key. If this is the case, the class will required.
	 *
	 * @param $class
	 */
	public static function load($class)
	{
		//		var_dump('Alcys\\' . $class);
		if(array_key_exists($class, self::$classes) && is_file(self::$classes[$class]))
		{
			require_once self::$classes[$class];
		}
		elseif(array_key_exists('Alcys\\' . $class, self::$classes) && is_file(self::$classes[$class]))
		{
			require_once self::$classes['Alcys\\' . $class];
		}
	}


	private static function _asd($directory, $file)
	{
		$path = $directory . DIRECTORY_SEPARATOR . $file;

		// Cut the .php extension.
		$className = strstr($path, '.php', true);

		// Replace scanned directories with the Alcys - namespace prefix.
		foreach(self::$scannedDirectories as $registeredDir)
		{
			$className = str_replace(trim($registeredDir, '/'), 'Alcys', $className);
		}

		// Replace slashes with backslashes
		$className = str_replace('/', '\\', $className);

		self::$classes[$className] = $path;
	}
}
