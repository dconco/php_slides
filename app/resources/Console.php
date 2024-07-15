<?php

declare(strict_types=1);

namespace PhpSlides\Http;

use Closure;
use PhpSlides\Interface\MiddlewareInterface;

class Console
{
	public static function showHelp(): string
	{
		return file_get_contents(
			dirname(__DIR__) . '/controller/template/commands/Commands.txt',
		);
	}

	public static function createController($cn, $ct): void
	{
		/**
		 * Converts controller class to CamelCase
		 * Adds Controller if its not specified
		 */
		$cn = strtoupper($cn[0]) . substr($cn, 1, strlen($cn));
		$cn = str_ends_with($cn, 'Controller') ? $cn : $cn . 'Controller';

		// create class name and namespace
		$namespace = 'PhpSlides\\Controller';
		$classname = $namespace . '\\' . $cn;

		$content = file_get_contents(
			dirname(__DIR__) . '/controller/template/controller/Controller.txt',
		);
		$strict =
			$ct === '-s' || $ct === '--strict'
				? "declare(strict_types=1);\n\n"
				: '';

		$content = str_replace('ControllerName', $cn, $content);
		$content = str_replace(
			'<?php',
			"<?php\n\n" . $strict . 'namespace ' . $namespace . ';',
			$content,
		);

		// checks if controller file or class already exists
		if (file_exists(dirname(__DIR__) . '/../controller/' . $cn . '.php')) {
			exit(
				"\033[31mFile name already exists: \033[4m`controller/" .
					$cn .
					".php`\033[0m\n"
			);
		} elseif (class_exists($classname)) {
			exit("\033[31mController class already exists: $cn\033[0m\n");
		}

		if (
			!file_put_contents(
				dirname(__DIR__) . '/../controller/' . $cn . '.php',
				$content,
			)
		) {
			exit("\033[31mError while creating controller: $cn\033[0m\n");
		}

		echo shell_exec('composer dump-autoload');
		sleep(1);
	}

	public static function createApiController($cn, $ct): void
	{
		/**
		 * Converts controller class to CamelCase
		 * Adds Controller if its not specified
		 */
		$cn = strtoupper($cn[0]) . substr($cn, 1, strlen($cn));
		$cn = str_ends_with($cn, 'Controller') ? $cn : $cn . 'Controller';

		// create class name and namespace
		$namespace = 'PhpSlides\\Controller';
		$classname = $namespace . '\\' . $cn;

		$content = file_get_contents(
			dirname(__DIR__) . '/controller/template/api/ApiController.txt',
		);
		$strict =
			$ct === '-s' || $ct === '--strict'
				? "declare(strict_types=1);\n\n"
				: '';

		$req = Request::class;
		$api_c = ApiController::class;
		$use = "\n\nuse $req;\nuse $api_c;";

		$content = str_replace('ControllerName', $cn, $content);
		$content = str_replace(
			'<?php',
			"<?php\n\n" . $strict . 'namespace ' . $namespace . ';' . $use,
			$content,
		);

		// checks if controller file or class already exists
		if (
			file_exists(dirname(__DIR__) . '/../controller/api/' . $cn . '.php')
		) {
			exit(
				"\033[31mFile name already exists: \033[4m`controller/api/" .
					$cn .
					".php`\033[0m\n"
			);
		} elseif (class_exists($classname)) {
			exit("\033[31mController class already exists: $cn\n\033[0m");
		}

		if (
			!file_put_contents(
				dirname(__DIR__) . '/../controller/api/' . $cn . '.php',
				$content,
			)
		) {
			exit("\033[31mError while creating api controller: $cn\033[0m\n");
		}

		echo shell_exec('composer dump-autoload');
		sleep(1);
	}

	public static function createMiddleware($cn, $ct): void
	{
		/**
		 * Converts middleware class to CamelCase
		 * Adds Middleware if its not specified
		 */
		$cn = strtoupper($cn[0]) . substr($cn, 1, strlen($cn));
		$cn = str_ends_with($cn, 'Middleware') ? $cn : $cn . 'Middleware';

		// create class name and namespace
		$namespace = 'PhpSlides\\Middleware';
		$classname = $namespace . '\\' . $cn;

		$content = file_get_contents(
			dirname(__DIR__) . '/controller/template/middleware/Middleware.txt',
		);
		$strict =
			$ct === '-s' || $ct === '--strict'
				? "declare(strict_types=1);\n\n"
				: '';

		$req = Request::class;
		$mwc = MiddlewareInterface::class;
		$use = "\n\nuse Closure;\nuse $req;\nuse $mwc;";

		$content = str_replace('MiddlewareName', $cn, $content);
		$content = str_replace(
			'<?php',
			"<?php\n\n" . $strict . 'namespace ' . $namespace . ';' . $use,
			$content,
		);

		// checks if middleware file or class already exists
		if (file_exists(dirname(__DIR__) . '/../middleware/' . $cn . '.php')) {
			exit(
				"\033[31mFile name already exists: \033[4m`middleware/" .
					$cn .
					".php`\033[0m\n"
			);
		} elseif (class_exists($classname)) {
			exit("Middleware class already exists: $cn\n");
		}

		if (
			!file_put_contents(
				dirname(__DIR__) . '/../middleware/' . $cn . '.php',
				$content,
			)
		) {
			exit("\033[31mError while creating middleware: $cn\n\033[0m");
		}

		echo shell_exec('composer dump-autoload');
		sleep(1);
	}

	public static function finally(): void
	{
		exit("\n");
	}
}
