<?php

namespace PhpSlides\Interface;

interface ApiInterface
{
	public static function __callStatic ($method, $args): self;

	public function name (string $name): self;

	public function route (string $url, string $controller): self;

	public function middleware (array $middleware): self;

	public function define (string $url, string $controller): self;

	public function map (array $rest_url): self;

	public function __destruct ();
}