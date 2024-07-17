<?php


namespace PhpSlides\Resources;

use PhpSlides\view;
use PhpSlides\Route;
use PhpSlides\Controller\Controller;

abstract class Resources extends Controller
{

	protected static mixed $action = null;

	protected static ?array $middleware = null;

	protected static ?array $method = null;

	protected static ?array $view = null;

	protected static ?string $use = null;

	protected static ?string $file = null;

	protected static array|bool $map_info = false;

	/**
	 * Get's all full request URL
	 *
	 * @static $root_dir
	 * @var string $root_dir
	 * @return string
	 */
	protected static string $request_uri;

	protected static function __method (): void
	{
		$route = self::$method['route'];
		$method = self::$method['method'];
		$callback = self::$method['callback'];

		Route::any($route, $callback, $method);
	}

	protected static function __view (): void
	{
		$route = self::$view['route'];
		$view = self::$view['view'];

		/**
		 *   ----------------------------------------
		 *   |   Replacing first and last forward slashes
		 *   |   $_REQUEST['uri'] will be empty if req uri is /
		 *   ----------------------------------------
		 */
		$uri = [];
		$str_route = '';
		$reqUri = strtolower(
		 preg_replace("/(^\/)|(\/$)/", '', self::$request_uri)
		);

		if (is_array($route))
		{
			for ($i = 0; $i < count($route); $i++)
			{
				$each_route = preg_replace("/(^\/)|(\/$)/", '', $route[$i]);
				array_push($uri, strtolower($each_route));
			}
		}
		else
		{
			$str_route = strtolower(preg_replace("/(^\/)|(\/$)/", '', $route));
		}

		if (in_array($reqUri, $uri) || $reqUri === $str_route)
		{
			if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'GET')
			{
				http_response_code(405);
				self::log();
				exit('Method Not Allowed');
			}

			// render view page to browser
			print_r(view::render($view));
			self::log();
			exit();
		}
	}

	protected function __middleware (): void
	{
		$use = self::$use;
		$file = self::$use;
		$action = self::$action;

		$view = self::$view;
		$method = self::$method;
		$middleware = self::$middleware ?? [];

		$params = self::$map_info['params'] ?? null;
		$request = new Request($params);

		for ($i = 0; $i < count((array) $middleware); $i++)
		{
			include_once dirname(__DIR__) . '/configs/middlewares.php';

			if (array_key_exists($middleware[$i], $middlewares))
			{
				$middleware = $middlewares[$middleware[$i]];
			}
			else
			{
				throw new Exception('No Registered Middleware as `' . $middleware[$i] . '`');
			}

			if (!class_exists($middleware))
			{
				throw new Exception(
				 "Middleware class does not exist: `{$middleware}`"
				);
			}
			$mw = new $middleware();

			if ($mw instanceof MiddlewareInterface)
			{
				$next = function () use ($use, $file, $action, $view, $method)
				{
					if ($use !== null)
					{
						self::__use();
					}
					elseif ($file !== null)
					{
						self::__file();
					}
					elseif ($action !== null)
					{
						self::__action();
					}
					elseif ($view !== null)
					{
						self::__view();
					}
					elseif ($method !== null)
					{
						self::__method();
					}
					else
					{
						throw new Exception('Cannot use middleware with this method');
					}
				};

				$mw->handle($request, $next);
			}
			else
			{
				throw new Exception(
				 'Middleware class must implements `MiddlewareInterface`'
				);
			}
		}
	}

	protected function __file (): void
	{
		$file = self::$file;

		if (array_key_exists('params', self::$map_info))
		{
			$GLOBALS['params'] = self::$map_info['params'];

			print_r(view::render($file));
			self::log();
			exit();
		}
	}

	protected function __use (): void
	{
		$controller = self::$use;

		if (!preg_match('/(?=.*Controller)(?=.*::)/', $controller))
		{
			exit('invalid parameter $controller Controller type');
		}

		[ $c_name, $c_method ] = explode('::', $controller);

		$cc = 'PhpSlides\\Controller\\' . $c_name;

		if (class_exists($cc))
		{
			if (array_key_exists('params', self::$map_info))
			{
				$GLOBALS['params'] = self::$map_info['params'];
				$params_value = self::$map_info['params_value'];

				$cc = new $cc();
				print_r($cc->$c_method(...$params_value));
			}
			else
			{
				$cc = new $cc();
				print_r($cc->$c_method());
			}
		}
		else
		{
			echo "No class controller found as: `$cc`";
		}

		self::log();
		exit();
	}

	protected function __action (): void
	{
		$action = self::$action;

		if (array_key_exists('params', self::$map_info))
		{
			$GLOBALS['params'] = self::$map_info['params'];
			$params_value = self::$map_info['params_value'];

			if (is_callable($action))
			{
				$a = $action(...$params_value);
				print_r($a);
			}
			elseif (preg_match('/(?=.*Controller)(?=.*::)/', $action))
			{
				self::$use = $action;
				$this->__use();
			}
			else
			{
				print_r($action);
			}
		}
		else
		{
			if (is_callable($action))
			{
				print_r($action());
			}
			elseif (preg_match('/(?=.*Controller)(?=.*::)/', $action))
			{
				self::$use = $action;
				$this->__use();
			}
			else
			{
				print_r($action);
			}
		}

		self::log();
		exit();
	}
}