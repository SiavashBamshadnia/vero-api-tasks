<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'Autoloader.php';
Autoloader::register();

try {
	new Api();
} catch (ValidationException $e) {
	// Handle validation errors
	http_response_code(400);
	echo json_encode([
		'error' => $e->getMessage(),
	]);
	error_log($e);
} catch (NotFoundException $e) {
	http_response_code(404);
	echo json_encode([
		'error' => 'No such route',
	]);
	error_log($e);
} catch (Throwable $e) {
	// Handle internal server errors
	http_response_code(500);
	echo json_encode([
		'error' => $e->getMessage(),
	]);
	error_log($e);
}

class Api
{
	private static $db;

	public function __construct()
	{
		self::$db = (new Database())->init();

		$uri = strtolower(trim((string)$_SERVER['REQUEST_URI'], '/'));
		$httpVerb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

		$wildcards = [
			':any' => '[^/]+',
			':num' => '[0-9]+',
		];
		$routes = [
			'get constructionStages' => [
				'class' => 'ConstructionStages',
				'method' => 'getAll',
			],
			'get constructionStages/(:num)' => [
				'class' => 'ConstructionStages',
				'method' => 'getSingle',
			],
			'post constructionStages' => [
				'class' => 'ConstructionStages',
				'method' => 'post',
				'bodyType' => 'ConstructionStagesCreate'
			],
			'patch constructionStages/(:num)' => [
				'class' => 'ConstructionStages',
				'method' => 'patch',
				'bodyType' => 'ConstructionStagesCreate'
			],
			'delete constructionStages/(:num)' => [
				'class' => 'ConstructionStages',
				'method' => 'delete',
				'bodyType' => 'ConstructionStagesCreate'
			],
			'get docs' => [
				'class' => 'ApiDocumentation',
				'method' => 'get',
			]
		];

		if ($uri) {
			$routeFound = false;
			foreach ($routes as $pattern => $target) {
				$pattern = str_replace(array_keys($wildcards), array_values($wildcards), $pattern);
				if (preg_match('#^'.$pattern.'$#i', "{$httpVerb} {$uri}", $matches)) {
					$params = [];
					array_shift($matches);
					if (in_array($httpVerb, ['post', 'patch'])) {
						$data = json_decode(file_get_contents('php://input'));
						$params = [new $target['bodyType']($data)];
					}
					$params = array_merge($params, $matches);
					$response = call_user_func_array([new $target['class'], $target['method']], $params);
					$routeFound = true;
					break;
				}
			}

			if (!$routeFound) {
				throw new NotFoundException();
			} elseif (!isset($response)) {
				http_response_code(204);
				return;
			} else {
				http_response_code(200);
			}
			echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}
	}

	public static function getDb()
	{
		return self::$db;
	}
}