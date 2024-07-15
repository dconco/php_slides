<?php


namespace PhpSlides\Auth;

class AuthHandler
{
	private static $authorizationHeader;

	private static function getAuthorizationHeader ()
	{
		$headers = getallheaders();
		self::$authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;
	}

	/**
	 * Get Basic Authentication Credentials
	 */
	public static function BasicAuthCredentials (): ?array
	{
		self::getAuthorizationHeader();

		if (self::$authorizationHeader && strpos(self::$authorizationHeader, 'Basic ') === 0)
		{
			$base64Credentials = substr(self::$authorizationHeader, 6);
			$decodedCredentials = base64_decode($base64Credentials);

			list( $username, $password ) = explode(':', $decodedCredentials, 2);
			return [ 'username' => $username, 'password' => $password ];
		}
		return null;
	}

	/**
	 * Get Bearer Token Authentication
	 */
	public static function BearerToken (): ?string
	{
		self::getAuthorizationHeader();

		if (self::$authorizationHeader && strpos(self::$authorizationHeader, 'Bearer ') === 0)
		{
			$token = substr(self::$authorizationHeader, 7);
			return $token;
		}
		return null;
	}
}