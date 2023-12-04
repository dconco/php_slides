<?php

/**
 * Configure your setting for cross-origin resource sharing
 */

// Allow cross-origin requests from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

// ...you can add as many header as needed


/**
 * Handle preflight requests (OPTIONS method)
 */
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS")
{
    http_response_code(200);
    exit;
}