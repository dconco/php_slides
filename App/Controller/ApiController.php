<?php

declare(strict_types=1);

namespace PhpSlides;

/**
 *  ---------------------------------------------------------------
 *
 *  |  API Web Route
 *
 *  |  This function allows you to create an API with Route Method
 *
 *  ---------------------------------------------------------------
 */
final class Api extends Route
{
  final public static function view(array|string $route, string $callback)
  {
    exit("Method not available");
  }

  final public static function redirect(
    string $route,
    string $new_url,
    int $code = 302
  ) {
    exit("Method not available");
  }

  /**
   *  --------------------------------------------------------------
   *
   *  |  GET ROUTE METHOD
   *
   *  |  Cannot evaluate { URL parameters } in route if it's an array
   *
   *  --------------------------------------------------------------
   */
  public static function get(array|string $route, $callback)
  {
    self::any($route, $callback, "GET");
  }
}
