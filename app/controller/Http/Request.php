<?php

declare(strict_types=1);

namespace PhpSlides\Http;

use stdClass;
use PhpSlides\Auth\AuthHandler;
use PhpSlides\Interface\RequestInterface;

class Request implements RequestInterface
{
	protected ?array $param;

	public function __construct (?array $urlParam = null)
	{
		$this->param = $urlParam;
	}

	public function urlParam (): object
	{
		return (object) $this->param;
	}

	public function urlQuery (): stdClass
	{
		$cl = new stdClass();
		$parsed = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

		if (!$parsed)
		{
			return $cl;
		}
		$parsed = mb_split('&', $parsed);

		$i = 0;
		while ($i < count($parsed))
		{
			$p = mb_split('=', $parsed[$i]);
			$key = $p[0];
			$value = $p[1] ?? null;

			$cl->$key = $value;
			$i++;
		}

		return $cl;
	}

	public function headers (?string $name = null): array|string
	{
		$headers = getallheaders();
		return !$name ? $headers : htmlspecialchars($headers[$name]);
	}

	public function Auth (): stdClass
	{
		$cl = new stdClass();
		$cl->basic = AuthHandler::BasicAuthCredentials();
		$cl->bearer = AuthHandler::BearerToken();

		return $cl;
	}

	public function body (): ?array
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if ($data === null || json_last_error() !== JSON_ERROR_NONE)
			return null;

		$res = [];
		foreach ($data as $key => $value)
		{
			$key = trim(htmlspecialchars($key));
			$value = trim(htmlspecialchars($value));

			$res[$key] = $value;
		}
		return $res;
	}

	public function get (string $key): ?string
	{
		if (!isset($_GET[$key]))
			return null;

		$data = trim(htmlspecialchars($_GET[$key]));
		return $data;
	}

	public function post (string $key): ?string
	{
		if (!isset($_POST[$key]))
			return null;

		$data = trim(htmlspecialchars($_POST[$key]));
		return $data;
	}

	public function request (string $key): ?string
	{
		if (!isset($_REQUEST[$key]))
			return null;

		$data = trim(htmlspecialchars($_REQUEST[$key]));
		return $data;
	}

	public function files (string $name): ?object
	{
		if (!isset($_FILES[$name]))
			return null;

		$files = $_FILES[$name];
		return (object) $files;
	}

	public function cookie (?string $key = null): string|object|null
	{
		if (!$key)
			return (object) $_COOKIE;
		return htmlspecialchars($_COOKIE[$key]);
	}

	public function method (): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public function uri (): string
	{
		return urldecode($_REQUEST['uri']);
	}

	public function url (): object
	{
		$uri = $this->uri();
		$parsed = parse_url($uri);

		$parsed['query'] = (array) $this->urlQuery();
		$parsed['param'] = (array) $this->urlParam();

		return (object) $parsed;
	}
}