<?php

namespace PhpSlides\Controller;

use DateTime;
use Exception;
use PhpSlides\Route;

class RouteController
{

    /**
     *  ============================================================
     *  |   `config_file` allows you to write configurations in `phpslides.config.json` file.
     *  |
     *  |   @return array|bool an `array` data retrieve from json data gotten from the config files
     *  ============================================================
     */
    protected static function config_file(): array|bool
    {
        $dir = dirname(__DIR__);

        $file_path = $dir . '../../' . '/phpslides.config.json';

        // checks if the config file exist in project root directory
        if (file_exists($file_path))
        {
            // get json files and convert it to an array
            $config_file = file_get_contents($file_path);
            $config_file = json_decode($config_file, true);

            return $config_file;
        }
        else
        {
            throw new Exception('URL request failed. Configuration file for PhpSlides is not found in the root of your project');
        }
    }




    /**
     *  ==============================
     *  |   --------------------
     *  |   --------------------
     *  ==============================
     */
    protected static function routing(array|string $route, $callback)
    {

        $uri = [];
        $str_route = '';
        $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);

        if (is_array($route))
        {
            for ($i = 0; $i < count($route); $i++)
            {
                $each_route = preg_replace("/(^\/)|(\/)/", "", $route[$i]);
                array_push($uri, $each_route);
            }
        }
        else
        {
            $str_route = preg_replace("/(^\/)|(\/)/", "", $route);
        }

        if (in_array($reqUri, $uri) || $reqUri === $str_route)
        {
            $charset = self::config_file()['charset'];
            header("Content-type: */*, charset=$charset");
            http_response_code(200);

            return $callback;
        }
    }



    /**
     *  ========================
     *  | log all request to `.log` file
     *  ========================
     */
    protected static function log()
    {
        $log_path = dirname(__DIR__) . '/.log';

        // set current date format
        $date = new DateTime('now');
        $date = date_format($date, 'D, d-m-Y H:i:s');

        // get request method type
        $method = $_SERVER["REQUEST_METHOD"];

        // get request url
        $uri = '/' . $_REQUEST["uri"];

        // get status response code for each request
        $http_code = http_response_code();

        //  protocol code for request header
        $http_protocol = $_SERVER["SERVER_PROTOCOL"];

        // all content messages to return 
        $content = "$method\t\t\t $http_protocol\t\t\t $http_code\t\t\t $uri\t\t\t $date\n\n";


        if (Route::$log == true)
        {
            $log = fopen($log_path, 'a');
            fwrite($log, $content);
            fclose($log);
        }
    }

}