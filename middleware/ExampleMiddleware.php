<?php

declare(strict_types=1);

namespace PhpSlides\Middleware;

use Closure;
use PhpSlides\Http\Request;
use PhpSlides\Interface\MiddlewareInterface;

final class ExampleMiddleware implements MiddlewareInterface
{
	public function handle (Request $request, Closure $next)
	{
		return $next($request);
	}
}