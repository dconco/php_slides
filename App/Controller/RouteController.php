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
		$file_path = Route::$root_dir . '/phpslides.config.json';

		// checks if the config file exist in project root directory
		if ($file_path) {
			// get json files and convert it to an array
			$config_file = file_get_contents($file_path);
			$config_file = json_decode($config_file, true);

			return $config_file;
		} else {
			throw new Exception(
				'URL request failed. Configuration file for PhpSlides is not found in the root of your project',
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
			// get and make generated file name & directory
			$gen_file = explode('/', $filename);
			$new_name = explode('.', end($gen_file), 2);
			$new_name = $new_name[0] . '.generated.' . $new_name[1];

			$gen_file[count($gen_file) - 1] = $new_name;
			$gen_file = implode('/', $gen_file);

			$file_contents = file_get_contents($filename);

			$pattern = '/<include\s+path=["|\']([^"]+)["|\']\s*!?\s*\/>/';

			// replace <include> match elements
			$file_contents = preg_replace_callback(
				$pattern,
				function ($matches) {
					$path = trim($matches[1]);
					return "<? slides_include(__DIR__ . '/$path') ?>";
				},
				$file_contents,
			);

			// replace <? elements
			$file_contents = preg_replace_callback(
				'/<\? ([^?]*)\?>/s',
				function ($matches) {
					$val = trim($matches[1]);
					$val = trim($val, ';');
					return "<?php print_r($val) ?>";
				},
				$file_contents,
			);

			try {
				$file = fopen($gen_file, 'w');
				fwrite($file, $file_contents);
				fclose($file);

				ob_start();
				include $gen_file;
				$output = ob_get_clean();
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			} finally {
				unlink($gen_file);
			}

			if ($output != false) {
				return $output;
			} else {
				return '';
			}
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
		string $method = '*',
	) {
		$uri = [];
		$str_route = '';
		$reqUri = preg_replace("/(^\/)|(\/$)/", '', $_REQUEST['uri']);

		if (is_array($route)) {
			for ($i = 0; $i < count($route); $i++) {
				$each_route = preg_replace("/(^\/)|(\/$)/", '', $route[$i]);
				array_push($uri, $each_route);
			}
		} else {
			$str_route = preg_replace("/(^\/)|(\/$)/", '', $route);
		}

		if (in_array($reqUri, $uri) || $reqUri === $str_route) {
			if (
				strtoupper($_SERVER['REQUEST_METHOD']) !== strtoupper($method) &&
				$method !== '*'
			) {
				http_response_code(405);
				self::log();
				exit('Method Not Allowed');
			}

			header('Content-Type: */*');
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
		$log_path = Route::$root_dir . '/.log';

		// set current date format
		$date = new DateTime('now');
		$date = date_format($date, 'D, d-m-Y H:i:s');

		// get request method type
		$method = $_SERVER['REQUEST_METHOD'];

		// get request url
		$uri = '/' . $_REQUEST['uri'];

		// get status response code for each request
		$http_code = http_response_code();

		//    protocol code for request header
		$http_protocol = $_SERVER['SERVER_PROTOCOL'];

		// all content messages to log
		$content = "$method  $http_protocol  $http_code  $uri  $date\n";

		if (Route::$log === true) {
			$log = fopen($log_path, 'a');
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
		array|null $param = null,
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
		$method = $class_info['method'];
		$class_name = $class_info['class_name'];
		$class_methods = $class_info['class_methods'];

		$class = new $class_name();

		for ($i = 0; $i < count($class_methods); $i++) {
			if (empty($method) || $method === '__invoke') {
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
					1,
				);
			}
		}
	}
}
