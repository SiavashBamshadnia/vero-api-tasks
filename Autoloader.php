<?php

class Autoloader
{
	public static function register()
	{
		spl_autoload_register(function ($class) {
			$file = "classes/{$class}.php";
			if (file_exists($file)) {
				require $file;
				return true;
			}
			$file = "classes/validation/{$class}.php";
			if (file_exists($file)) {
				require $file;
				return true;
			}
			$file = "classes/exceptions/{$class}.php";
			if (file_exists($file)) {
				require $file;
				return true;
			}
			return false;
		});
	}
}