<?php

namespace PhpSlides\Route;

use PhpSlides\Instance\MapInterface;
use PhpSlides\Controller\RouteController;

/**
 * Map Configuration
 */
class MapRoute extends RouteController implements MapInterface
{
   private static string|array $route;
   private static string $request_uri;
   private static string $charset;
   private static string $method;

   /**
    * Validating $route methods
    *
    * @param int $method
    * @param string|array $route
    */
   public function match(string $method, string|array $route): bool|array
   {
      $config_file = self::config_file();
      self::$charset = $config_file["charset"];
      self::$method = strtoupper($method);
      /**
       *   ----------------------------------------------
       *   |   Replacing first and last forward slashes
       *   |   $_REQUEST['uri'] will be empty if req uri is /
       *   ----------------------------------------------
       */
      if (!empty($_REQUEST["uri"])) {
         self::$request_uri = preg_replace(
            "/(^\/)|(\/$)/",
            "",
            $_REQUEST["uri"]
         );
      } else {
         self::$request_uri = "/";
      }
      self::$route = is_array($route)
         ? $route
         : preg_replace("/(^\/)|(\/$)/", "", $route);

      // will store all the parameters value in this array
      $req = [];
      $req_value = [];

      // will store all the parameters names in this array
      $paramKey = [];

      // finding if there is any {?} parameter in $route
      if (is_string(self::$route)) {
         preg_match_all("/(?<={).+?(?=})/", self::$route, $paramMatches);
      }

      // if the route does not contain any param call routing();
      if (empty($paramMatches[0]) || is_array(self::$route)) {
         /**
          *   ------------------------------------------------------
          *   |   Check if $callback is a callable function
          *   |   or array of controller, and if not,
          *   |   it's a string of text or html document
          *   ------------------------------------------------------
          */
         return $this->match_routing();
      }

      // setting parameters names
      foreach ($paramMatches[0] as $key) {
         $paramKey[] = $key;
      }

      // exploding route address
      $uri = explode("/", self::$route);

      // will store index number where {?} parameter is required in the $route
      $indexNum = [];

      // storing index number, where {?} parameter is required with the help of regex
      foreach ($uri as $index => $param) {
         if (preg_match("/{.*}/", $param)) {
            $indexNum[] = $index;
         }
      }

      /**
       *   ----------------------------------------------------------------------------------
       *   |   Exploding request uri string to array to get the exact index number value of parameter from $_REQUEST['uri']
       *   ----------------------------------------------------------------------------------
       */
      $reqUri = explode("/", self::$request_uri);

      /**
       *   ----------------------------------------------------------------------------------
       *   |   Running for each loop to set the exact index number with reg expression this will help in matching route
       *   ----------------------------------------------------------------------------------
       */
      foreach ($indexNum as $key => $index) {
         /**
          *   --------------------------------------------------------------------------------
          *   |   In case if req uri with param index is empty then return because URL is not valid for this route
          *   --------------------------------------------------------------------------------
          */

         if (empty($reqUri[$index])) {
            return false;
         }

         // setting params with params names
         $req[$paramKey[$key]] = htmlspecialchars($reqUri[$index]);
         $req_value[] = htmlspecialchars($reqUri[$index]);

         // this is to create a regex for comparing route address
         $reqUri[$index] = "{.*}";
      }

      // converting array to string
      $reqUri = implode("/", $reqUri);

      /**
       *   -----------------------------------
       *   |   replace all / with \/ for reg expression
       *   |   regex to match route is ready!
       *   -----------------------------------
       */
      $reqUri = str_replace("/", "\\/", $reqUri);

      // now matching route with regex
      if (preg_match("/$reqUri/", self::$route)) {
         // checks if the requested method is of the given route
         if (
            strtoupper($_SERVER["REQUEST_METHOD"]) !== self::$method &&
            strtolower(self::$method) !== "dynamic"
         ) {
            http_response_code(405);
            self::log();
            exit("Method Not Allowed");
         }

         $charset = self::$charset;

         http_response_code(200);
         header("Content-Type: */*; charset=$charset");
         self::log();

         return [
            "method" => $method,
            "route" => self::$route,
            "params_value" => $req_value,
            "params" => $req,
         ];
      }

      return false;
   }

   private function match_routing(): bool|array
   {
      $uri = [];
      $str_route = "";

      if (is_array(self::$route)) {
         for ($i = 0; $i < count(self::$route); $i++) {
            $each_route = preg_replace("/(^\/)|(\/$)/", "", self::$route[$i]);
            array_push($uri, $each_route);
         }
      } else {
         $str_route = self::$route;
      }

      if (
         in_array(self::$request_uri, $uri) ||
         self::$request_uri === $str_route
      ) {
         if (
            strtoupper($_SERVER["REQUEST_METHOD"]) !== self::$method &&
            strtolower(self::$method) !== "dynamic"
         ) {
            http_response_code(405);
            self::log();
            exit("Method Not Allowed");
         }

         $method = self::$method;
         $charset = self::$charset;

         http_response_code(200);
         header("Content-Type: */*; charset=$charset");
         self::log();

         return [
            "method" => $method,
            "route" => self::$route,
         ];
      } else {
         return false;
      }
   }
}
