<?php

declare(strict_types=1);

namespace PhpSlides\Http;

use Exception;
use PhpSlides\Route\MapRoute;
use PhpSlides\Interface\ApiInterface;
use PhpSlides\Http\Resources\ApiResources;

final class Api extends ApiResources implements ApiInterface
{
	public static string $BASE_URL = '/api/';

	private static string $version = 'v1';

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

	public function route (string $url, string $controller): self
	{
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
		}

		return $this;
	}

	public function middleware (array $middleware): self
	{
		if (self::$map_info)
		{
			self::$middleware = $middleware;
		}

		return $this;
	}

	public function define (string $url, string $controller): self
	{
		self::$define = [
		 'url' => $url,
		 'controller' => $controller
		];

		return $this;
	}

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