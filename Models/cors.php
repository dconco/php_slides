<?php
// Allow cross-origin requests from any origin
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods (e.g., GET, POST, OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, PUT, UPDATE, OPTIONS, DELETE");

// Allow specific HTTP headers
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

//Handle preflight requests (OPTIONS method)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS")
{
    // Return 200 OK status for preflight requests
    http_response_code(200);
    exit;
}