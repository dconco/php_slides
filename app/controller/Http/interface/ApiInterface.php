<?php

namespace PhpSlides\Interface;

interface ApiInterface
{
	/**
	 * Handles static method calls to set the API version dynamically.
	 * 
	 * @param string $method The method name which starts with 'v' followed by the version number. Use `_` in place of `.`
	 * @param mixed $args The arguments for the method (not used).
	 * 
	 * @throws \Exception
	 * @return self
	 */
	public static function __callStatic($method, $args): self;


	/**
	 * Assigns a name to the last registered route for easier reference.
	 * 
	 * @param string $name The name to assign to the route.
	 * @return self
	 */
	public function name(string $name): self;


	/**
	 * Defines a new route with a URL and a controller.
	 * 
	 * @param string $url The Base URL of the route.
	 * @param string $controller The controller handling the route.
	 * @return self
	 */
	public function route(string $url, string $controller): self;


	/**
	 * Applies middleware to the current route.
	 * 
	 * @param array $middleware An array of middleware classes.
	 * @return self
	 */
	public function middleware(array $middleware): self;


	/**
	 * Defines a base URL and controller for subsequent route mappings.
	 * 
	 * @param string $url The base URL for the routes.
	 * @param string $controller The controller handling the routes.
	 * @return self
	 */
	public function define(string $url, string $controller): self;


	/**
	 * Maps multiple HTTP methods to a URL with their corresponding controller methods.
	 * 
	 * @param array An associative array where the key is the route and the value is an array with the HTTP method and controller method.
	 * @return self
	 */
	public function map(array $rest_url): self;


	/**
	 * Automatically handles middleware, route, and map finalization when the object is destroyed.
	 */
	public function __destruct();

	 
	public static function v1(): self;
	public static function v1_0(): self;
	public static function v1_1(): self;
	public static function v1_1_1(): self;
	public static function v1_1_2(): self;
	public static function v1_1_3(): self;
	public static function v1_1_4(): self;
	public static function v1_1_5(): self;
	public static function v1_1_6(): self;
	public static function v1_1_7(): self;
	public static function v1_1_8(): self;
	public static function v1_1_9(): self;
	public static function v1_2(): self;
	public static function v1_2_1(): self;
	public static function v1_2_2(): self;
	public static function v1_2_3(): self;
	public static function v1_2_4(): self;
	public static function v1_2_5(): self;
	public static function v1_2_6(): self;
	public static function v1_2_7(): self;
	public static function v1_2_8(): self;
	public static function v1_2_9(): self;
	public static function v1_3(): self;
	public static function v1_3_1(): self;
	public static function v1_3_2(): self;
	public static function v1_3_3(): self;
	public static function v1_3_4(): self;
	public static function v1_3_5(): self;
	public static function v1_3_6(): self;
	public static function v1_3_7(): self;
	public static function v1_3_8(): self;
	public static function v1_3_9(): self;
	public static function v1_4(): self;
	public static function v1_4_1(): self;
	public static function v1_4_2(): self;
	public static function v1_4_3(): self;
	public static function v1_4_4(): self;
	public static function v1_4_5(): self;
	public static function v1_4_6(): self;
	public static function v1_4_7(): self;
	public static function v1_4_8(): self;
	public static function v1_4_9(): self;
	public static function v1_5(): self;
	public static function v1_5_1(): self;
	public static function v1_5_2(): self;
	public static function v1_5_3(): self;
	public static function v1_5_4(): self;
	public static function v1_5_5(): self;
	public static function v1_5_6(): self;
	public static function v1_5_7(): self;
	public static function v1_5_8(): self;
	public static function v1_5_9(): self;
	public static function v1_6(): self;
	public static function v1_6_1(): self;
	public static function v1_6_2(): self;
	public static function v1_6_3(): self;
	public static function v1_6_4(): self;
	public static function v1_6_5(): self;
	public static function v1_6_6(): self;
	public static function v1_6_7(): self;
	public static function v1_6_8(): self;
	public static function v1_6_9(): self;
	public static function v1_7(): self;
	public static function v1_7_1(): self;
	public static function v1_7_2(): self;
	public static function v1_7_3(): self;
	public static function v1_7_4(): self;
	public static function v1_7_5(): self;
	public static function v1_7_6(): self;
	public static function v1_7_7(): self;
	public static function v1_7_8(): self;
	public static function v1_7_9(): self;
	public static function v1_8(): self;
	public static function v1_8_1(): self;
	public static function v1_8_2(): self;
	public static function v1_8_3(): self;
	public static function v1_8_4(): self;
	public static function v1_8_5(): self;
	public static function v1_8_6(): self;
	public static function v1_8_7(): self;
	public static function v1_8_8(): self;
	public static function v1_8_9(): self;
	public static function v1_9(): self;
	public static function v1_9_1(): self;
	public static function v1_9_2(): self;
	public static function v1_9_3(): self;
	public static function v1_9_4(): self;
	public static function v1_9_5(): self;
	public static function v1_9_6(): self;
	public static function v1_9_7(): self;
	public static function v1_9_8(): self;
	public static function v1_9_9(): self;

	public static function v2(): self;
	public static function v2_0(): self;
	public static function v2_1(): self;
	public static function v2_1_1(): self;
	public static function v2_1_2(): self;
	public static function v2_1_3(): self;
	public static function v2_1_4(): self;
	public static function v2_1_5(): self;
	public static function v2_1_6(): self;
	public static function v2_1_7(): self;
	public static function v2_1_8(): self;
	public static function v2_1_9(): self;
	public static function v2_2(): self;
	public static function v2_2_1(): self;
	public static function v2_2_2(): self;
	public static function v2_2_3(): self;
	public static function v2_2_4(): self;
	public static function v2_2_5(): self;
	public static function v2_2_6(): self;
	public static function v2_2_7(): self;
	public static function v2_2_8(): self;
	public static function v2_2_9(): self;
	public static function v2_3(): self;
	public static function v2_3_1(): self;
	public static function v2_3_2(): self;
	public static function v2_3_3(): self;
	public static function v2_3_4(): self;
	public static function v2_3_5(): self;
	public static function v2_3_6(): self;
	public static function v2_3_7(): self;
	public static function v2_3_8(): self;
	public static function v2_3_9(): self;
	public static function v2_4(): self;
	public static function v2_4_1(): self;
	public static function v2_4_2(): self;
	public static function v2_4_3(): self;
	public static function v2_4_4(): self;
	public static function v2_4_5(): self;
	public static function v2_4_6(): self;
	public static function v2_4_7(): self;
	public static function v2_4_8(): self;
	public static function v2_4_9(): self;
	public static function v2_5(): self;
	public static function v2_5_1(): self;
	public static function v2_5_2(): self;
	public static function v2_5_3(): self;
	public static function v2_5_4(): self;
	public static function v2_5_5(): self;
	public static function v2_5_6(): self;
	public static function v2_5_7(): self;
	public static function v2_5_8(): self;
	public static function v2_5_9(): self;
	public static function v2_6(): self;
	public static function v2_6_1(): self;
	public static function v2_6_2(): self;
	public static function v2_6_3(): self;
	public static function v2_6_4(): self;
	public static function v2_6_5(): self;
	public static function v2_6_6(): self;
	public static function v2_6_7(): self;
	public static function v2_6_8(): self;
	public static function v2_6_9(): self;
	public static function v2_7(): self;
	public static function v2_7_1(): self;
	public static function v2_7_2(): self;
	public static function v2_7_3(): self;
	public static function v2_7_4(): self;
	public static function v2_7_5(): self;
	public static function v2_7_6(): self;
	public static function v2_7_7(): self;
	public static function v2_7_8(): self;
	public static function v2_7_9(): self;
	public static function v2_8(): self;
	public static function v2_8_1(): self;
	public static function v2_8_2(): self;
	public static function v2_8_3(): self;
	public static function v2_8_4(): self;
	public static function v2_8_5(): self;
	public static function v2_8_6(): self;
	public static function v2_8_7(): self;
	public static function v2_8_8(): self;
	public static function v2_8_9(): self;
	public static function v2_9(): self;
	public static function v2_9_1(): self;
	public static function v2_9_2(): self;
	public static function v2_9_3(): self;
	public static function v2_9_4(): self;
	public static function v2_9_5(): self;
	public static function v2_9_6(): self;
	public static function v2_9_7(): self;
	public static function v2_9_8(): self;
	public static function v2_9_9(): self;

	public static function v3(): self;
	public static function v3_0(): self;
	public static function v3_1(): self;
	public static function v3_1_1(): self;
	public static function v3_1_2(): self;
	public static function v3_1_3(): self;
	public static function v3_1_4(): self;
	public static function v3_1_5(): self;
	public static function v3_1_6(): self;
	public static function v3_1_7(): self;
	public static function v3_1_8(): self;
	public static function v3_1_9(): self;
	public static function v3_2(): self;
	public static function v3_2_1(): self;
	public static function v3_2_2(): self;
	public static function v3_2_3(): self;
	public static function v3_2_4(): self;
	public static function v3_2_5(): self;
	public static function v3_2_6(): self;
	public static function v3_2_7(): self;
	public static function v3_2_8(): self;
	public static function v3_2_9(): self;
	public static function v3_3(): self;
	public static function v3_3_1(): self;
	public static function v3_3_2(): self;
	public static function v3_3_3(): self;
	public static function v3_3_4(): self;
	public static function v3_3_5(): self;
	public static function v3_3_6(): self;
	public static function v3_3_7(): self;
	public static function v3_3_8(): self;
	public static function v3_3_9(): self;
	public static function v3_4(): self;
	public static function v3_4_1(): self;
	public static function v3_4_2(): self;
	public static function v3_4_3(): self;
	public static function v3_4_4(): self;
	public static function v3_4_5(): self;
	public static function v3_4_6(): self;
	public static function v3_4_7(): self;
	public static function v3_4_8(): self;
	public static function v3_4_9(): self;
	public static function v3_5(): self;
	public static function v3_5_1(): self;
	public static function v3_5_2(): self;
	public static function v3_5_3(): self;
	public static function v3_5_4(): self;
	public static function v3_5_5(): self;
	public static function v3_5_6(): self;
	public static function v3_5_7(): self;
	public static function v3_5_8(): self;
	public static function v3_5_9(): self;
	public static function v3_6(): self;
	public static function v3_6_1(): self;
	public static function v3_6_2(): self;
	public static function v3_6_3(): self;
	public static function v3_6_4(): self;
	public static function v3_6_5(): self;
	public static function v3_6_6(): self;
	public static function v3_6_7(): self;
	public static function v3_6_8(): self;
	public static function v3_6_9(): self;
	public static function v3_7(): self;
	public static function v3_7_1(): self;
	public static function v3_7_2(): self;
	public static function v3_7_3(): self;
	public static function v3_7_4(): self;
	public static function v3_7_5(): self;
	public static function v3_7_6(): self;
	public static function v3_7_7(): self;
	public static function v3_7_8(): self;
	public static function v3_7_9(): self;
	public static function v3_8(): self;
	public static function v3_8_1(): self;
	public static function v3_8_2(): self;
	public static function v3_8_3(): self;
	public static function v3_8_4(): self;
	public static function v3_8_5(): self;
	public static function v3_8_6(): self;
	public static function v3_8_7(): self;
	public static function v3_8_8(): self;
	public static function v3_8_9(): self;
	public static function v3_9(): self;
	public static function v3_9_1(): self;
	public static function v3_9_2(): self;
	public static function v3_9_3(): self;
	public static function v3_9_4(): self;
	public static function v3_9_5(): self;
	public static function v3_9_6(): self;
	public static function v3_9_7(): self;
	public static function v3_9_8(): self;
	public static function v3_9_9(): self;

	public static function v4(): self;
	public static function v4_0(): self;
	public static function v4_1(): self;
	public static function v4_1_1(): self;
	public static function v4_1_2(): self;
	public static function v4_1_3(): self;
	public static function v4_1_4(): self;
	public static function v4_1_5(): self;
	public static function v4_1_6(): self;
	public static function v4_1_7(): self;
	public static function v4_1_8(): self;
	public static function v4_1_9(): self;
	public static function v4_2(): self;
	public static function v4_2_1(): self;
	public static function v4_2_2(): self;
	public static function v4_2_3(): self;
	public static function v4_2_4(): self;
	public static function v4_2_5(): self;
	public static function v4_2_6(): self;
	public static function v4_2_7(): self;
	public static function v4_2_8(): self;
	public static function v4_2_9(): self;
	public static function v4_3(): self;
	public static function v4_3_1(): self;
	public static function v4_3_2(): self;
	public static function v4_3_3(): self;
	public static function v4_3_4(): self;
	public static function v4_3_5(): self;
	public static function v4_3_6(): self;
	public static function v4_3_7(): self;
	public static function v4_3_8(): self;
	public static function v4_3_9(): self;
	public static function v4_4(): self;
	public static function v4_4_1(): self;
	public static function v4_4_2(): self;
	public static function v4_4_3(): self;
	public static function v4_4_4(): self;
	public static function v4_4_5(): self;
	public static function v4_4_6(): self;
	public static function v4_4_7(): self;
	public static function v4_4_8(): self;
	public static function v4_4_9(): self;
	public static function v4_5(): self;
	public static function v4_5_1(): self;
	public static function v4_5_2(): self;
	public static function v4_5_3(): self;
	public static function v4_5_4(): self;
	public static function v4_5_5(): self;
	public static function v4_5_6(): self;
	public static function v4_5_7(): self;
	public static function v4_5_8(): self;
	public static function v4_5_9(): self;
	public static function v4_6(): self;
	public static function v4_6_1(): self;
	public static function v4_6_2(): self;
	public static function v4_6_3(): self;
	public static function v4_6_4(): self;
	public static function v4_6_5(): self;
	public static function v4_6_6(): self;
	public static function v4_6_7(): self;
	public static function v4_6_8(): self;
	public static function v4_6_9(): self;
	public static function v4_7(): self;
	public static function v4_7_1(): self;
	public static function v4_7_2(): self;
	public static function v4_7_3(): self;
	public static function v4_7_4(): self;
	public static function v4_7_5(): self;
	public static function v4_7_6(): self;
	public static function v4_7_7(): self;
	public static function v4_7_8(): self;
	public static function v4_7_9(): self;
	public static function v4_8(): self;
	public static function v4_8_1(): self;
	public static function v4_8_2(): self;
	public static function v4_8_3(): self;
	public static function v4_8_4(): self;
	public static function v4_8_5(): self;
	public static function v4_8_6(): self;
	public static function v4_8_7(): self;
	public static function v4_8_8(): self;
	public static function v4_8_9(): self;
	public static function v4_9(): self;
	public static function v4_9_1(): self;
	public static function v4_9_2(): self;
	public static function v4_9_3(): self;
	public static function v4_9_4(): self;
	public static function v4_9_5(): self;
	public static function v4_9_6(): self;
	public static function v4_9_7(): self;
	public static function v4_9_8(): self;
	public static function v4_9_9(): self;

	public static function v5(): self;
	public static function v5_0(): self;
	public static function v5_1(): self;
	public static function v5_1_1(): self;
	public static function v5_1_2(): self;
	public static function v5_1_3(): self;
	public static function v5_1_4(): self;
	public static function v5_1_5(): self;
	public static function v5_1_6(): self;
	public static function v5_1_7(): self;
	public static function v5_1_8(): self;
	public static function v5_1_9(): self;
	public static function v5_2(): self;
	public static function v5_2_1(): self;
	public static function v5_2_2(): self;
	public static function v5_2_3(): self;
	public static function v5_2_4(): self;
	public static function v5_2_5(): self;
	public static function v5_2_6(): self;
	public static function v5_2_7(): self;
	public static function v5_2_8(): self;
	public static function v5_2_9(): self;
	public static function v5_3(): self;
	public static function v5_3_1(): self;
	public static function v5_3_2(): self;
	public static function v5_3_3(): self;
	public static function v5_3_4(): self;
	public static function v5_3_5(): self;
	public static function v5_3_6(): self;
	public static function v5_3_7(): self;
	public static function v5_3_8(): self;
	public static function v5_3_9(): self;
	public static function v5_4(): self;
	public static function v5_4_1(): self;
	public static function v5_4_2(): self;
	public static function v5_4_3(): self;
	public static function v5_4_4(): self;
	public static function v5_4_5(): self;
	public static function v5_4_6(): self;
	public static function v5_4_7(): self;
	public static function v5_4_8(): self;
	public static function v5_4_9(): self;
	public static function v5_5(): self;
	public static function v5_5_1(): self;
	public static function v5_5_2(): self;
	public static function v5_5_3(): self;
	public static function v5_5_4(): self;
	public static function v5_5_5(): self;
	public static function v5_5_6(): self;
	public static function v5_5_7(): self;
	public static function v5_5_8(): self;
	public static function v5_5_9(): self;
	public static function v5_6(): self;
	public static function v5_6_1(): self;
	public static function v5_6_2(): self;
	public static function v5_6_3(): self;
	public static function v5_6_4(): self;
	public static function v5_6_5(): self;
	public static function v5_6_6(): self;
	public static function v5_6_7(): self;
	public static function v5_6_8(): self;
	public static function v5_6_9(): self;
	public static function v5_7(): self;
	public static function v5_7_1(): self;
	public static function v5_7_2(): self;
	public static function v5_7_3(): self;
	public static function v5_7_4(): self;
	public static function v5_7_5(): self;
	public static function v5_7_6(): self;
	public static function v5_7_7(): self;
	public static function v5_7_8(): self;
	public static function v5_7_9(): self;
	public static function v5_8(): self;
	public static function v5_8_1(): self;
	public static function v5_8_2(): self;
	public static function v5_8_3(): self;
	public static function v5_8_4(): self;
	public static function v5_8_5(): self;
	public static function v5_8_6(): self;
	public static function v5_8_7(): self;
	public static function v5_8_8(): self;
	public static function v5_8_9(): self;
	public static function v5_9(): self;
	public static function v5_9_1(): self;
	public static function v5_9_2(): self;
	public static function v5_9_3(): self;
	public static function v5_9_4(): self;
	public static function v5_9_5(): self;
	public static function v5_9_6(): self;
	public static function v5_9_7(): self;
	public static function v5_9_8(): self;
	public static function v5_9_9(): self;
	public static function v6(): self;
	public static function v6_0(): self;
	public static function v6_1(): self;
	public static function v6_1_1(): self;
	public static function v6_1_2(): self;
	public static function v6_1_3(): self;
	public static function v6_1_4(): self;
	public static function v6_1_5(): self;
	public static function v6_1_6(): self;
	public static function v6_1_7(): self;
	public static function v6_1_8(): self;
	public static function v6_1_9(): self;
	public static function v6_2(): self;
	public static function v6_2_1(): self;
	public static function v6_2_2(): self;
	public static function v6_2_3(): self;
	public static function v6_2_4(): self;
	public static function v6_2_5(): self;
	public static function v6_2_6(): self;
	public static function v6_2_7(): self;
	public static function v6_2_8(): self;
	public static function v6_2_9(): self;
	public static function v6_3(): self;
	public static function v6_3_1(): self;
	public static function v6_3_2(): self;
	public static function v6_3_3(): self;
	public static function v6_3_4(): self;
	public static function v6_3_5(): self;
	public static function v6_3_6(): self;
	public static function v6_3_7(): self;
	public static function v6_3_8(): self;
	public static function v6_3_9(): self;
	public static function v6_4(): self;
	public static function v6_4_1(): self;
	public static function v6_4_2(): self;
	public static function v6_4_3(): self;
	public static function v6_4_4(): self;
	public static function v6_4_5(): self;
	public static function v6_4_6(): self;
	public static function v6_4_7(): self;
	public static function v6_4_8(): self;
	public static function v6_4_9(): self;
	public static function v6_5(): self;
	public static function v6_5_1(): self;
	public static function v6_5_2(): self;
	public static function v6_5_3(): self;
	public static function v6_5_4(): self;
	public static function v6_5_5(): self;
	public static function v6_5_6(): self;
	public static function v6_5_7(): self;
	public static function v6_5_8(): self;
	public static function v6_5_9(): self;
	public static function v6_6(): self;
	public static function v6_6_1(): self;
	public static function v6_6_2(): self;
	public static function v6_6_3(): self;
	public static function v6_6_4(): self;
	public static function v6_6_5(): self;
	public static function v6_6_6(): self;
	public static function v6_6_7(): self;
	public static function v6_6_8(): self;
	public static function v6_6_9(): self;
	public static function v6_7(): self;
	public static function v6_7_1(): self;
	public static function v6_7_2(): self;
	public static function v6_7_3(): self;
	public static function v6_7_4(): self;
	public static function v6_7_5(): self;
	public static function v6_7_6(): self;
	public static function v6_7_7(): self;
	public static function v6_7_8(): self;
	public static function v6_7_9(): self;
	public static function v6_8(): self;
	public static function v6_8_1(): self;
	public static function v6_8_2(): self;
	public static function v6_8_3(): self;
	public static function v6_8_4(): self;
	public static function v6_8_5(): self;
	public static function v6_8_6(): self;
	public static function v6_8_7(): self;
	public static function v6_8_8(): self;
	public static function v6_8_9(): self;
	public static function v6_9(): self;
	public static function v6_9_1(): self;
	public static function v6_9_2(): self;
	public static function v6_9_3(): self;
	public static function v6_9_4(): self;
	public static function v6_9_5(): self;
	public static function v6_9_6(): self;
	public static function v6_9_7(): self;
	public static function v6_9_8(): self;
	public static function v6_9_9(): self;
	public static function v7(): self;
	public static function v7_0(): self;
	public static function v7_0_0(): self;
}