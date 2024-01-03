<?php

declare(strict_types=1);

namespace PhpSlides;

use Exception;
use PhpSlides\Route;
use PhpSlides\Controller\Controller;



/**
 *    ---------------------------------------------------------------
 *
 *    |    API Web Route
 *
 *    |    This function allows you to create an API with Route Method
 *
 *    ---------------------------------------------------------------
 */
final class Api extends Controller
{

    /**
     *    ------------------------------------------------------------------------
     *
     *    |    ANY REQUEST FROM API ENDPOINT
     *
     *    |    Accept all type of request or any other method
     *
     *    |    Cannot evaluate `{?} URL parameters` in api route if it's an array
     *    |
     *
     *    @param array|string $route This describes the URL string to check if it matches the request URL, use array of URLs for multiple request
     *    @param mixed $class_method Can contain any types of data to return to the client side.
     *
     *    ------------------------------------------------------------------------
     */
    final public static function any(array|string $route, int $class_method, string $method = "*")
    {
        try
        {
            $dir = Route::$root_dir;

            // will store all the parameters value in this array
            $req = [];
            $req_value = [];

            // will store all the parameters names in this array
            $paramKey = [];


            // Gets all constant int values wrapped in an array
            ob_start();
            $bin_const_types = include $dir . "/App/bin/const_types.php";
            ob_end_clean();

            $bin_const_types = unserialize(hex2bin(($bin_const_types)));
            $const_types_method = $bin_const_types[$class_method][1];
            $const_types_class = $bin_const_types[$class_method][0];

            // finding if there is any {?} parameter in $route
            if (is_string($route))
            {
                preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);
            }

            // if the route does not contain any param call routing();
            if (empty($paramMatches[0]) || is_array($route))
            {

                /**
                 *    ------------------------------------------------------
                 *    |    Check if $class_method is a callable function
                 *    |    or array of controller, and if not,
                 *    |    it's a string of text or html document
                 *    ------------------------------------------------------
                 */

                $class_method = self::routing($route, $class_method, $method);

                if ($class_method)
                {
                    if (
                    (preg_match("/(Controller)/", $const_types_class, $matches) && count($matches) > 1) &&
                    (preg_match("/(Api)/", $const_types_class, $matches) && count($matches) > 0)
                    )
                    {
                        http_response_code(200);
                        header("Content-Type: application/json");

                        print_r(self::controller($const_types_class, $const_types_method));
                    }
                    else
                    {
                        throw new Exception("Invalid api controller class or not existing: " . $const_types_class);
                    }

                    self::log();
                    exit();
                }
                else
                {
                    return;
                }
            }

            // setting parameters names
            foreach ($paramMatches[0] as $key)
            {
                $paramKey[] = $key;
            }

            /**
             *    ----------------------------------------------
             *    |    Replacing first and last forward slashes
             *    |    $_REQUEST['uri'] will be empty if req uri is /
             *    ----------------------------------------------
             */

            if (!empty(Route::$request_uri))
            {
                $route = preg_replace("/(^\/)|(\/$)/", "", $route);
                $reqUri = preg_replace("/(^\/)|(\/$)/", "", Route::$request_uri);
            }
            else
            {
                $reqUri = "/";
            }

            // exploding route address
            $uri = explode("/", $route);

            // will store index number where {?} parameter is required in the $route
            $indexNum = [];

            // storing index number, where {?} parameter is required with the help of regex
            foreach ($uri as $index => $param)
            {
                if (preg_match("/{.*}/", $param))
                {
                    $indexNum[] = $index;
                }
            }

            /**
             *    ----------------------------------------------------------------------------------
             *    |    Exploding request uri string to array to get the exact index number value of parameter from $_REQUEST['uri']
             *    ----------------------------------------------------------------------------------
             */
            $reqUri = explode("/", $reqUri);

            /**
             *    ----------------------------------------------------------------------------------
             *    |    Running for each loop to set the exact index number with reg expression this will help in matching route
             *    ----------------------------------------------------------------------------------
             */
            foreach ($indexNum as $key => $index)
            {

                /**
                 *    --------------------------------------------------------------------------------
                 *    |    In case if req uri with param index is empty then return because URL is not valid for this route
                 *    --------------------------------------------------------------------------------
                 */

                if (empty($reqUri[$index]))
                {
                    return;
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
             *    -----------------------------------
             *    |    replace all / with \/ for reg expression
             *    |    regex to match route is ready!
             *    -----------------------------------
             */
            $reqUri = str_replace("/", "\\/", $reqUri);

            // now matching route with regex
            if (preg_match("/$reqUri/", $route))
            {
                // checks if the requested method is of the given route
                if (
                strtoupper($_SERVER["REQUEST_METHOD"]) !== strtoupper($method) &&
                $method !== "*"
                )
                {
                    http_response_code(405);
                    self::log();
                    exit("Method Not Allowed");
                }

                if (
                (preg_match("/(Controller)/", $const_types_class, $matches) && count($matches) > 1) &&
                (preg_match("/(Api)/", $const_types_class, $matches) && count($matches) > 0)
                )
                {
                    http_response_code(200);
                    header("Content-Type: application/json");

                    print_r(
                        self::controller($const_types_class, $const_types_method, [
                            ...$req_value,
                        ]),
                    );
                }
                else
                {
                    throw new Exception("Invalid api controller class or not existing: " . $const_types_class);
                }

                self::log();
                exit();
            }
        }
        catch ( Exception $e )
        {
            http_response_code(500);
            print_r($e->getMessage());
            exit();
        }
    }


    /**
     *    --------------------------------------------------------------
     *
     *    |    GET API ROUTE METHOD
     *
     *    |    Cannot evaluate {?} URL parameters in API route if it's an array
     *
     *    --------------------------------------------------------------
     */
    public static function get(
        array|string $route,
        int $class_method,
    ) {
        self::any($route, $class_method, "GET");
    }



    /**
     *    --------------------------------------------------------------
     *
     *    |    POST API ROUTE METHOD
     *
     *    |    Cannot evaluate {?} URL parameters in API route if it's an array
     *
     *    --------------------------------------------------------------
     */
    public static function post(
        array|string $route,
        int $class_method,
    ) {
        self::any($route, $class_method, "POST");
    }



    /**
     *    --------------------------------------------------------------
     *
     *    |    PUT API ROUTE METHOD
     *
     *    |    Cannot evaluate {?} URL parameters in API route if it's an array
     *
     *    --------------------------------------------------------------
     */
    public static function put(
        array|string $route,
        int $class_method,
    ) {
        self::any($route, $class_method, "PUT");
    }



    /**
     *    --------------------------------------------------------------
     *
     *    |    UPDATE API ROUTE METHOD
     *
     *    |    Cannot evaluate {?} URL parameters in API route if it's an array
     *
     *    --------------------------------------------------------------
     */
    public static function update(
        array|string $route,
        int $class_method,
    ) {
        self::any($route, $class_method, "UPDATE");
    }



    /**
     *    --------------------------------------------------------------
     *
     *    |    PATCH API ROUTE METHOD
     *
     *    |    Cannot evaluate {?} URL parameters in API route if it's an array
     *
     *    --------------------------------------------------------------
     */
    public static function patch(
        array|string $route,
        int $class_method,
    ) {
        self::any($route, $class_method, "PATCH");
    }



    /**
     *    --------------------------------------------------------------
     *
     *    |    DELETE API ROUTE METHOD
     *
     *    |    Cannot evaluate {?} URL parameters in API route if it's an array
     *
     *    --------------------------------------------------------------
     */
    public static function delete(
        array|string $route,
        int $class_method,
    ) {
        self::any($route, $class_method, "DELETE");
    }
}
