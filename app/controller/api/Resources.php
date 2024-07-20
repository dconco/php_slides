<?php


namespace PhpSlides\Http\Resources;

use Exception;
use PhpSlides\Http\Request;
use PhpSlides\Http\ApiController;
use PhpSlides\Controller\Controller;
use PhpSlides\Interface\MiddlewareInterface;

abstract class ApiResources extends Controller
{
	protected static array|bool $map_info = false;

	protected static array $allRoutes;

	protected static array $regRoute;

	protected static ?array $route = null;

	protected static ?array $define = null;

	protected static ?array $middleware = null;

	protected static ?array $map = null;

	protected function __route(): void
	{
		print_r(self::__routeSelection());
		exit();
	}

	protected function __routeSelection(Request $request = null)
	{
		$info = self::$map_info;
		$route = self::$route ?? self::$map;

		$method = $_SERVER['REQUEST_METHOD'];
		$controller = $route['controller'];

		if (!class_exists($controller)) {
			http_response_code(405);
			throw new Exception(
				"Api controller class `$controller` does not exist."
			);
		}

		$params = $info['params'] ?? null;

		if (!class_exists($controller)) {
			throw new Exception(
				"Api controller class does not exist: `$controller`"
			);
		}
		$cc = new $controller();

		$r_method = '';
		$method = strtoupper($_SERVER['REQUEST_METHOD']);

		if (isset($route['c_method'])) {
			$r_method = $route['c_method'];
			goto EXECUTE;
		}

		switch ($method) {
			case 'GET':
				global $r_method;
				$r_method = $params !== null ? 'show' : 'index';
				break;

			case 'POST':
				$r_method = 'store';
				break;

			case 'PUT':
				$r_method = 'update';
				break;

			case 'PATCH':
				$r_method = 'patch';
				break;

			case 'DELETE':
				$r_method = 'destroy';
				break;

			default:
				if (method_exists($cc, '__default')) {
					$r_method = '__default';
				} else {
					http_response_code(405);
					self::log();
					exit('Request method not allowed.');
				}
				break;
		}

		EXECUTE:
		if ($cc instanceof ApiController) {
			if ($request === null) {
				$request = new Request($params);
			}

			$response = $cc->$r_method($request);
			$r_method = 'error';
			$response = !$response ? $cc->$r_method($request) : $response;

			self::log();
			return $response;
		} else {
			throw new Exception(
				'Api controller class must implements `ApiController`'
			);
		}
	}

	protected function __middleware(): void
	{
		$middleware = self::$middleware ?? [];
		$response = '';

		$params = self::$map_info['params'] ?? null;
		$request = new Request($params);

		for ($i = 0; $i < count((array) $middleware); $i++) {
			include_once dirname(__DIR__) . '/../../configs/middlewares.php';

			if (array_key_exists($middleware[$i], $middlewares)) {
				$middleware = $middlewares[$middleware[$i]];
			} else {
				throw new Exception(
					'No Registered Middleware as `' . $middleware[$i] . '`'
				);
			}

			if (!class_exists($middleware)) {
				throw new Exception(
					"Middleware class does not exist: `{$middleware}`"
				);
			}
			$mw = new $middleware();

			if ($mw instanceof MiddlewareInterface) {
				$next = function (Request $request) {
					return self::__routeSelection($request);
				};

				$response = $mw->handle($request, $next);
			} else {
				throw new Exception(
					'Middleware class must implements `MiddlewareInterface`'
				);
			}
		}

		print_r($response);
		self::log();
		exit();
	}

	protected function __map(Request $request = null): void
	{
		print_r(self::__routeSelection($request));
		exit();
	}
}
