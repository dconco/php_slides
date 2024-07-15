<?php

namespace PhpSlides\Interface;

use Closure;
use PhpSlides\Http\Request;

interface MiddlewareInterface
{
	public function handle (Request $request, Closure $next);
}