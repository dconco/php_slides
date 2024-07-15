<?php

declare(strict_types=1);

namespace PhpSlides\Http;

use Exception;
use PhpSlides\Route\MapRoute;
use PhpSlides\Controller\Controller;
use PhpSlides\Interface\ApiInterface;
use PhpSlides\Interface\MiddlewareInterface;

final class Api extends Controller implements ApiInterface
{
	public static string $BASE_URL = '/api/';

	private static string $version = 'v1';

	private static array|bool $map_info;

	private static array $allRoutes;

	private static array $regRoute;

	private static ?array $route = null;

	private static ?array $define = null;

	private static ?array $middleware = null;

	public static function __callStatic($method, $args): self
	{
		if (str_starts_with($method, 'v')) {
			$method_v = str_replace('_', '.', $method);
			self::$version = $method_v;

			return new self();
		} else {
			throw new Exception("Invalid version method `$method`");
		}
	}

	public function name(string $name): self
	{
		add_route_name($name, end(self::$regRoute));
		self::$allRoutes[$name] = end(self::$regRoute);

		return $this;
	}

	public function route(string $url, string $controller): self
	{
		$uri = strtolower(
			self::$BASE_URL . self::$version . '/' . trim($url, '/')
		);

		self::$regRoute[] = $uri;

		$match = new MapRoute();
		self::$map_info = $match->match('dynamic', $uri);

		if (self::$map_info) {
			self::$map_info['method'] = $_SERVER['REQUEST_METHOD'];

			self::$route = [
				'url' => $uri,
				'controller' => $controller
			];
		}

		return $this;
	}

	private function __route(): void
	{
		print_r(self::__routeSelection());
		exit(0);
	}

	private function __routeSelection(Request $request = null)
	{
		$route = self::$route;
		$info = self::$map_info;

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
					$r_method = 'default';
				} else {
					self::log();
					exit('Request method not allowed.');
				}
				break;
		}

		if ($cc instanceof ApiController) {
			if ($request === null) {
				$request = new Request($params);
			}

			self::log();
			return $cc->$r_method($request);
		} else {
			throw new Exception(
				'Api controller class must implements `ApiController`'
			);
		}
	}

	public function middleware(array $middleware): self
	{
		if (self::$map_info) {
			self::$middleware = $middleware;
		}

		return $this;
	}

	private function __middleware(): void
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

	public function define(string $url, string $controller): self
	{
		self::$define = [
			'url' => $url,
			'controller' => $controller
		];

		return $this;
	}

	private function map(array $rest_url): void
	{
		$define = self::$define;

		if ($define !== null) {
			$url = $define['url'];
			$controller = $define['controller'];

			$regUrl = [];
			foreach ($rest_url as $route => $method) {
				$full_url = $url . $route;

				$uri = strtolower(
					self::$BASE_URL . self::$version . '/' . trim($full_url, '/')
				);

				$regUrl[] = $uri;
			}
			self::$regRoute[] = $regUrl;

			$match = new MapRoute();
			self::$map_info = $match->match('dynamic', $uri);

			if (self::$map_info) {
				self::$map_info['method'] = $_SERVER['REQUEST_METHOD'];

				self::$route = [
					'url' => $uri,
					'controller' => $controller
				];
			}
		}
	}

	public function __destruct()
	{
		if (self::$middleware !== null) {
			self::__middleware();
		}

		if (self::$route !== null) {
			self::__route();
		}
	}
}
