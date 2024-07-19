<?php

declare(strict_types=1);

namespace PhpSlides\Http;

use Exception;
use PhpSlides\Route\MapRoute;
use PhpSlides\Interface\ApiInterface;
use PhpSlides\Http\Resources\ApiResources;

/**
 * The Api class provides a fluent interface to define API routes,
 * apply middleware, and manage route mapping.
 */
final class Api extends ApiResources implements ApiInterface
{
	/**
	 * The base URL for all API routes. Default is '/api/'
	 * @var string
	 */
	public static string $BASE_URL = '/api/';

	/**
	 * The API version. Default is 'v1'
	 * @var string
	 */
	private static string $version = 'v1';

	/**
	 * Handles static method calls to set the API version dynamically.
	 * 
	 * @param string $method The method name which starts with 'v' followed by the version number. Use `_` in place of `.`
	 * @param mixed $args The arguments for the method (not used).
	 * 
	 * @throws \Exception
	 * @return self
	 */
	public static function __callStatic ($method, $args): self
	{
		if (str_starts_with($method, 'v'))
		{
			$method_v = str_replace('_', '.', $method);
			self::$version = $method_v;

			return new self();
		}
		else
		{
			throw new Exception("Invalid version method `$method`");
		}
	}

	/**
	 * Assigns a name to the last registered route for easier reference.
	 * 
	 * @param string $name The name to assign to the route.
	 * @return self
	 */
	public function name (string $name): self
	{
		if (is_array(end(self::$regRoute)))
		{
			for ($i = 0; $i < count(end(self::$regRoute)); $i++)
			{
				add_route_name($name . '::' . $i, end(self::$regRoute)[$i]);
				self::$allRoutes[$name . '::' . $i] = end(self::$regRoute)[$i];
			}
		}
		add_route_name($name, end(self::$regRoute));
		self::$allRoutes[$name] = end(self::$regRoute);

		return $this;
	}

	/**
	 * Defines a new route with a URL and a controller.
	 * 
	 * @param string $url The Base URL of the route.
	 * @param string $controller The controller handling the route.
	 * @return self
	 */
	public function route (string $url, string $controller): self
	{
		$define = self::$define;

		// checks if $define is set, then assign $define methods to $url & $controller parameters
		$url = ($define !== null) ? (empty($url) ? $define['url'] : $define['url'] . '/' . trim($url, '/')) : $url;

		$uri = strtolower(
		 self::$BASE_URL . self::$version . '/' . trim($url, '/')
		);

		self::$regRoute[] = $uri;

		$match = new MapRoute();
		self::$map_info = $match->match('dynamic', $uri);

		if (self::$map_info)
		{
			self::$map_info['method'] = $_SERVER['REQUEST_METHOD'];

			self::$route = [
			 'url' => $uri,
			 'controller' => $controller
			];

			if ($define !== null && empty($controller))
				self::$route['c_methood'] = $controller;
		}

		return $this;
	}

	/**
	 * Applies middleware to the current route.
	 * 
	 * @param array $middleware An array of middleware classes.
	 * @return self
	 */
	public function middleware (array $middleware): self
	{
		if (self::$map_info)
		{
			self::$middleware = $middleware;
		}

		return $this;
	}

	/**
	 * Defines a base URL and controller for subsequent route mappings.
	 * 
	 * @param string $url The base URL for the routes.
	 * @param string $controller The controller handling the routes.
	 * @return self
	 */
	public function define (string $url, string $controller): self
	{
		self::$define = [
		 'url' => $url,
		 'controller' => $controller
		];

		return $this;
	}

	/**
	 * Maps multiple HTTP methods to a URL with their corresponding controller methods.
	 * 
	 * @param array An associative array where the key is the route and the value is an array with the HTTP method and controller method.
	 * @return self
	 */
	public function map (array $rest_url): self
	{
		$define = self::$define;

		if ($define !== null)
		{
			$url = $define['url'];
			$controller = $define['controller'];

			$method = [];
			$regUrl = [];

			foreach ($rest_url as $route => $c_method)
			{
				$method[] = $c_method[0];
				$full_url = $url . $route;

				$uri = strtolower(
				 rtrim(self::$BASE_URL, '/') . '/' . self::$version . '/' . trim($full_url, '/')
				);

				$regUrl[] = $uri;

				$match = new MapRoute();
				self::$map_info = $match->match($c_method[0], $uri);

				if (self::$map_info)
				{
					self::$map = [
					 'url' => $uri,
					 'c_method' => ltrim($c_method[1], '@'),
					 'controller' => $controller
					];

					break;
				}
			}

			self::$regRoute[] = $regUrl;

			if (!in_array($_SERVER['REQUEST_METHOD'], $method))
			{
				http_response_code(405);
				self::log();
				exit('Method Not Allowed');
			}
		}
		return $this;
	}

	/**
	 * Automatically handles middleware, route, and map finalization when the object is destroyed.
	 */
	public function __destruct ()
	{
		if (self::$middleware !== null)
		{
			self::__middleware();
		}

		if (self::$route !== null)
		{
			self::__route();
		}

		if (self::$map !== null)
		{
			self::__map();
		}
	}
}