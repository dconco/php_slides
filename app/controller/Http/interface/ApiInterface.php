<?php

namespace PhpSlides\Interface;

interface ApiInterface
{
	public static function __callStatic ($method, $args): self;

	public function route (string $url, string $controller): self;

	public function middleware (array $middleware): self;

	public function __destruct ();
}