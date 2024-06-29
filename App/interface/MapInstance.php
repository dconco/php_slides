<?php

namespace PhpSlides\Instance;

use PhpSlides\Route\MapRoute;

/**
 * undocumented class
 *
 * @package default
 * @author `g:snips_author`
 */
interface MapInstance
{
   /**
    * Validating $route methods
    *
    * @param string $method
    * @param string|array $route
    */
   public function match(string $method, string|array $route): bool|array;
}
