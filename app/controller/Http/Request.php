<?php

declare(strict_types=1);

namespace PhpSlides\Http;

use stdClass;
use PhpSlides\Auth\AuthHandler;
use PhpSlides\Interface\RequestInterface;

/**
 * Class Request
 *
 * Handles HTTP request data including URL parameters, query strings, headers, authentication, body data, and more.
 */
class Request implements RequestInterface
{
	/**
	 * @var ?array The URL parameters.
	 */
	protected ?array $param;


	/**
	 * Request constructor.
	 *
	 * @param ?array $urlParam Optional URL parameters.
	 */
	public function __construct(?array $urlParam = null)
	{
		$this->param = $urlParam;
	}


	/**
	 * Returns URL parameters as an object.
	 *
	 * @return object The URL parameters.
	 */
	public function urlParam(): object
	{
		return (object) $this->param;
	}


	/**
	 * Parses and returns the query string parameters from the URL.
	 *
	 * @return stdClass The parsed query parameters.
	 */
	public function urlQuery(): stdClass
	{
		$cl = new stdClass();
		$parsed = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

		if (!$parsed) {
			return $cl;
		}
		$parsed = mb_split('&', $parsed);

		$i = 0;
		while ($i < count($parsed)) {
			$p = mb_split('=', $parsed[$i]);
			$key = $p[0];
			$value = $p[1] ?? null;

			$cl->$key = $value;
			$i++;
		}

		return $cl;
	}


	/**
	 * Retrieves headers from the request.
	 *
	 * @param ?string $name Optional header name to retrieve a specific header.
	 * @return array|string The headers, or a specific header value if $name is provided.
	 */
	public function headers(?string $name = null): array|string
	{
		$headers = getallheaders();
		return !$name ? $headers : htmlspecialchars($headers[$name]);
	}


	/**
	 * Retrieves authentication credentials from the request.
	 *
	 * @return stdClass The authentication credentials.
	 */
	public function Auth(): stdClass
	{
		$cl = new stdClass();
		$cl->basic = AuthHandler::BasicAuthCredentials();
		$cl->bearer = AuthHandler::BearerToken();

		return $cl;
	}


	/**
	 * Parses and returns the body of the request as an associative array.
	 *
	 * @return ?array The request body data, or null if parsing fails.
	 */
	public function body(): ?array
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if ($data === null || json_last_error() !== JSON_ERROR_NONE)
			return null;

		$res = [];
		foreach ($data as $key => $value) {
			$key = trim(htmlspecialchars($key));
			$value = trim(htmlspecialchars($value));

			$res[$key] = $value;
		}
		return $res;
	}


	/**
	 * Retrieves a GET parameter by key.
	 *
	 * @param string $key The key of the GET parameter.
	 * @return ?string The parameter value, or null if not set.
	 */
	public function get(string $key): ?string
	{
		if (!isset($_GET[$key]))
			return null;

		$data = trim(htmlspecialchars($_GET[$key]));
		return $data;
	}


	/**
	 * Retrieves a POST parameter by key.
	 *
	 * @param string $key The key of the POST parameter.
	 * @return ?string The parameter value, or null if not set.
	 */
	public function post(string $key): ?string
	{
		if (!isset($_POST[$key]))
			return null;

		$data = trim(htmlspecialchars($_POST[$key]));
		return $data;
	}


	/**
	 * Retrieves a request parameter by key from all input sources.
	 *
	 * @param string $key The key of the request parameter.
	 * @return ?string The parameter value, or null if not set.
	 */
	public function request(string $key): ?string
	{
		if (!isset($_REQUEST[$key]))
			return null;

		$data = trim(htmlspecialchars($_REQUEST[$key]));
		return $data;
	}


	/**
	 * Retrieves file data from the request by name.
	 *
	 * @param string $name The name of the file input.
	 * @return ?object File data, or null if not set.
	 */
	public function files(string $name): ?object
	{
		if (!isset($_FILES[$name]))
			return null;

		$files = $_FILES[$name];
		return (object) $files;
	}


	/**
	 * Retrieves a cookie value by key, or all cookies if no key is provided.
	 *
	 * @param ?string $key Optional cookie key.
	 * @return string|object|null The cookie value, all cookies as an object, or null if key is provided but not found.
	 */
	public function cookie(?string $key = null): string|object|null
	{
		if (!$key)
			return (object) $_COOKIE;
		return htmlspecialchars($_COOKIE[$key]);
	}


	/**
	 * Retrieves the HTTP method used for the request.
	 *
	 * @return string The HTTP method (e.g., GET, POST).
	 */
	public function method(): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}


	/**
	 * Retrieves the URI from the request.
	 *
	 * @return string The URI.
	 */
	public function uri(): string
	{
		return urldecode($_REQUEST['uri']);
	}


	/**
	 * Parses and returns URL components including query and parameters.
	 *
	 * @return object The parsed URL components.
	 */
	public function url(): object
	{
		$uri = $this->uri();
		$parsed = parse_url($uri);

		$parsed['query'] = (array) $this->urlQuery();
		$parsed['param'] = (array) $this->urlParam();

		return (object) $parsed;
	}


	/**
	 * Retrieves the client's IP address.
	 *
	 * @return string The client's IP address.
	 */
	public function ip(): string
	{
		return $_SERVER['REMOTE_ADDR'];
	}


	/**
	 * Retrieves the client's user agent string.
	 *
	 * @return string The user agent string.
	 */
	public function userAgent(): string
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}


	/**
	 * Checks if the request was made via AJAX.
	 *
	 * @return bool True if the request is an AJAX request, false otherwise.
	 */
	public function isAjax(): bool
	{
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}


	/**
	 * Retrieves the URL of the referring page.
	 *
	 * @return string|null The referrer URL, or null if not set.
	 */
	public function referrer(): ?string
	{
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
	}


	/**
	 * Retrieves the server protocol used for the request.
	 *
	 * @return string The server protocol.
	 */
	public function protocol(): string
	{
		return $_SERVER['SERVER_PROTOCOL'];
	}


	/**
	 * Retrieves all input data from GET, POST, and the request body.
	 *
	 * @return array The combined input data.
	 */
	public function all(): array
	{
		$data = array_merge($_GET, $_POST, $this->body() ?? []);
		return array_map('htmlspecialchars', $data);
	}


	/**
	 * Retrieves a parameter from the $_SERVER array.
	 *
	 * @param string $key The key of the server parameter.
	 * @return string|null The server parameter value, or null if not set.
	 */
	public function server(string $key): ?string
	{
		return isset($_SERVER[$key]) ? $_SERVER[$key] : null;
	}


	/**
	 * Checks if the request method matches a given method.
	 *
	 * @param string $method The HTTP method to check.
	 * @return bool True if the request method matches, false otherwise.
	 */
	public function isMethod(string $method): bool
	{
		return strtoupper($this->method()) === strtoupper($method);
	}


	/**
	 * Checks if the request is made over HTTPS.
	 *
	 * @return bool True if the request is HTTPS, false otherwise.
	 */
	public function isHttps(): bool
	{
		return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
	}


	/**
	 * Retrieves the time when the request was made.
	 *
	 * @return int The request time as a Unix timestamp.
	 */
	public function requestTime(): int
	{
		return $_SERVER['REQUEST_TIME'];
	}
}
