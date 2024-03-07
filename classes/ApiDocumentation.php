<?php

/**
 * Class ApiDocumentation
 *
 * A utility class for extracting and formatting API documentation from PHPDoc comments in a class.
 * This class is designed to retrieve and process PHPDoc comments for specific methods of a class,
 * making it easier to generate or access API documentation programmatically.
 *
 */
class ApiDocumentation
{
	/**
	 * Extracts and formats API documentation from PHPDoc comments in a class.
	 *
	 * This method uses reflection to access the PHPDoc comments of the 'patch' and 'delete' methods
	 * of the 'ConstructionStages' class. It processes the comments, cleans them up, and returns them
	 * in a structured format suitable for API documentation purposes.
	 *
	 * @return array An associative array where keys are the method names ('patch', 'delete') (my methods) and values are the formatted documentation strings.
	 */
	public function get()
	{
		$result = [];

		$reflector = new ReflectionClass('ConstructionStages');
		$functions = ['patch', 'delete'];
		foreach ($functions as $function) {
			$comment = $reflector->getMethod($function)->getDocComment();
			$lines = preg_split("/((\r?\n)|(\r\n?))/", $comment);
			foreach ($lines as $line) {
				$line = trim($line, " \n\r\t\v\0*/");
				if (empty($line))
					continue;
				if (!isset($result[$function]))
					$result[$function] = '';
				$result[$function] .= $line;
			}
		}

		return $result;
	}
}