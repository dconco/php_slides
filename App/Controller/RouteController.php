<?php

namespace PhpSlides\Controller;

use DateTime;
use Exception;
use PhpSlides\Route;

class RouteController
{
   /**
    *    ----------------------------------------------------------------------------------
    *    |
    *    |    `config_file` allows you to write configurations in `phpslides.config.json` file.
    *
    *        @return array|bool an `array` data retrieve from json data gotten from the config files
    *    |
    *    ----------------------------------------------------------------------------------
    */
   protected static function config_file(): array|bool
   {
      $file_path = Route::$root_dir . "/phpslides.config.json";

      // checks if the config file exist in project root directory
      if ($file_path) {
         // get json files and convert it to an array
         $config_file = file_get_contents($file_path);
         $config_file = json_decode($config_file, true);

         return $config_file;
      } else {
         throw new Exception(
            "URL request failed. Configuration file for PhpSlides is not found in the root of your project"
         );
      }
   }

   /**
    *    -----------------------------------------------------------
    *    |
    *    @param mixed $filename The file which to gets the contents
    *    @return mixed The executed included file received
    *    |
    *    -----------------------------------------------------------
    */
   public static function slides_include($filename)
   {
      if (is_file($filename)) {
         $file_contents = file_get_contents($filename);

         $root = strtolower(Route::$root_dir . "/");
         $root = str_replace("c:\\", "c:\\\\", $root);

         $find = "/configs/autoload.php";
         $self = htmlspecialchars($_SERVER["PHP_SELF"]);
         $view = substr_replace(
            $self,
            "/",
            strrpos($self, $find),
            strlen($find)
         );

         $pattern = '/<include\s+path="([^"]+)"\s*!?\s*\/>/';

         // replace <include> match elements
         $file_contents = preg_replace_callback(
            $pattern,
            function ($matches) {
               $path = trim($matches[1]);
               return "<? slides_include('$path') ?>";
            },
            $file_contents
         );

         // replace <? elements
         $file_contents = preg_replace_callback(
            "/<\? ([^?]*)\?>/s",
            function ($matches) {
               $val = trim($matches[1]);
               return "<?php print_r($val) ?>";
            },
            $file_contents
         );

         $file_contents = str_replace("::view/", $view, $file_contents);
         $file_contents = str_replace("::root/", $root, $file_contents);

         try {
            $tempFileName = tempnam(sys_get_temp_dir(), "slides_");
            $file = fopen($tempFileName, "w");
            fwrite($file, $file_contents);
            fclose($file);

            ob_start();
            include $tempFileName;
            $output = ob_get_clean();
            unlink($tempFileName);
         } catch (Exception $e) {
            throw new Exception($e->getMessage(), 1);
         }

         if ($output != false) {
            return $output;
         } else {
            throw new Exception("Empty file.");
         }
      } else {
         return false;
      }
   }

   /**
    *    ==============================
    *    |    Don't use this function!!!
    *    |    --------------------
    *    ==============================
    */
   protected static function routing(
      array|string $route,
      mixed $callback,
      string $method = "*"
   ) {
      $uri = [];
      $str_route = "";
      $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);

      if (is_array($route)) {
         for ($i = 0; $i < count($route); $i++) {
            $each_route = preg_replace("/(^\/)|(\/$)/", "", $route[$i]);
            array_push($uri, $each_route);
         }
      } else {
         $str_route = preg_replace("/(^\/)|(\/$)/", "", $route);
      }

      if (in_array($reqUri, $uri) || $reqUri === $str_route) {
         if (
            strtoupper($_SERVER["REQUEST_METHOD"]) !== strtoupper($method) &&
            $method !== "*"
         ) {
            http_response_code(405);
            self::log();
            exit("Method Not Allowed");
         }

         header("Content-Type: */*");
         http_response_code(200);

         return $callback;
      } else {
         return false;
      }
   }

   /**
    *    ---------------------------------
    *    |
    *    |    log all request to `.log` file
    *    |
    *    ---------------------------------
    */
   protected static function log()
   {
      $log_path = Route::$root_dir . "/.log";

      // set current date format
      $date = new DateTime("now");
      $date = date_format($date, "D, d-m-Y H:i:s");

      // get request method type
      $method = $_SERVER["REQUEST_METHOD"];

      // get request url
      $uri = "/" . $_REQUEST["uri"];

      // get status response code for each request
      $http_code = http_response_code();

      //    protocol code for request header
      $http_protocol = $_SERVER["SERVER_PROTOCOL"];

      // all content messages to log
      $content = "$method  $http_protocol  $http_code  $uri  $date\n";

      if (Route::$log === true) {
         $log = fopen($log_path, "a");
         fwrite($log, $content);
         fclose($log);
      }
   }

   /**
    *    Don't use this function!!!
    *
    *    @param object|string $class In implementing class constructor from controller
    *    @param string $method In accessing methods to render to routes
    *    @return mixed From class methods and __invoke function
    */
   protected static function controller(
      object|string $class,
      string $method,
      array|null $param = null
   ) {
      return ClassController::__class($class, $method, $param);
   }

   /**
    *    ==============================
    *    |    Don't use this function!!!
    *    |    --------------------
    *    ==============================
    */
   protected static function class_info(array $class_info, array|null $param)
   {
      $method = $class_info["method"];
      $class_name = $class_info["class_name"];
      $class_methods = $class_info["class_methods"];

      $class = new $class_name();

      for ($i = 0; $i < count($class_methods); $i++) {
         if (empty($method) || $method === "__invoke") {
            return $param != null ? $class(...$param) : $class();
         } elseif ($method === $class_methods[$i]) {
            return $param != null
               ? $class->$method(...$param)
               : $class->$method();
         } elseif (
            count($class_methods) - 1 === $i &&
            $method !== $class_methods
         ) {
            throw new Exception(
               "No controller method found as '$method'. Try using __invoke method.",
               1
            );
         }
      }
   }
}
