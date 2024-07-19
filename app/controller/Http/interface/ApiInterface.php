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
}
