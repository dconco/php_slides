<?php

use PhpSlides\Controller\RouteController;

/**
 *    -----------------------------------------------------------
 *   |
 *   @param mixed $filename The file which to gets the contents
 *   @return mixed The executed included file received
 *   |
 *    -----------------------------------------------------------
 */
function slides_include ($filename)
{
	$output = RouteController::slides_include($filename);
	return $output;
}



$routes = [];

/**
 * Give route a name and value
 * 
 * @param string $name Name of the given route to be specified
 * @param string|array $value Named route value
 * @return void
 */
function add_route_name (string $name, string|array $value): void
{
	global $routes;
	$routes[$name] = $value;
}



/**
 * Get Route results from named route
 * 
 * @param string|null $name The name of the route to return
 * @param array|null $param If the route has parameter, give the parameter a value
 * 
 * @return array|object|string returns the route value
 */
function route (string|null $name = null, array|null $param = null): array|object|string
{
	global $routes;

	if ($name === null)
	{
		$route_class = new stdClass();

		foreach ($routes as $key => $value)
		{
			if (preg_match_all("/(?<={).+?(?=})/", $value))
			{
				$route_class->$key = function (string ...$args) use ($routes, $value, $key)
				{
					$route = '';

					if (count($args) === 0)
					{
						$route = $routes[$key];
					}
					else
					{
						for ($i = 0; $i < count($args); $i++)
						{
							if ($i === 0)
							{
								$route = preg_replace("/\{[^}]+\}/", $args[$i], $value, 1);
							}
							else
							{
								$route = preg_replace("/\{[^}]+\}/", $args[$i], $route, 1);
							}
						}
					}
					return $route;
				};
			}
			else
			{
				$route_class->$key = $value;
			}
		}

		return $route_class;
	}
	else
	{
		if (!array_key_exists($name, $routes))
		{
			throw new Exception("No route with specified route name `route('$name')`");
		}
		else
		{
			if ($param === null)
			{
				return $routes[$name];
			}
			else
			{
				$route = '';

				for ($i = 0; $i < count($param); $i++)
				{
					if ($i === 0)
					{
						$route = preg_replace("/\{[^}]+\}/", $param[$i], $routes[$name], 1);
					}
					else
					{
						$route = preg_replace("/\{[^}]+\}/", $param[$i], $route, 1);
					}
				}
				return $route;
			}
		}
	}
}

define("APP_DEBUG", getenv("APP_DEBUG", true));

const GET = "GET";
const PUT = "PUT";
const POST = "POST";
const PATCH = "PATCH";
const DELETE = "DELETE";