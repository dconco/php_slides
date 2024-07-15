<?php

declare(strict_types=1);

namespace PhpSlides\Interface;

use stdClass;

interface RequestInterface
{
	public function __construct (array $routeParam);

	public function urlParam (): object;

	public function urlQuery (): stdClass;

	public function headers (?string $name): array|string;

	public function Auth (): stdClass;

	public function body (): ?array;

	public function get (string $name): ?string;

	public function post (string $name): ?string;

	public function request (string $name): ?string;

	public function files (string $name): ?object;

	public function cookie (?string $name): string|object|null;

	public function method (): string;

	public function uri (): string;

	public function url (): object;
}