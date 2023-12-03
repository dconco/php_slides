<?php

namespace PhpSlides;

use Exception;
use PhpSlides\Controller\RouteController;

/**
 *  -------------------------------------------------------------------------------
 *  
 *  |   CREATE A NEW ROUTE
 * 
 * 
 *  |   Create route & api that accept different methods and render to the client area
 * 
 * 
 * @author Dave Conco <concodave@gmail.com>
 * @link https://github.com/dconco/php_slides
 * @category api, router, php router, php
 * @package php_slides
 * @version ${1:1.0.0}
 * @return void
 * |
 * 
 *  ---------------------------------------------------------------------------------
 */

final class Route extends RouteController
{

    /**
     *  ----------------------------------------------------------------------------------------------------------
     *  
     *  |   `$log` method prints logs in `.log` file in the root of the project each time any request has been received, when setted to true.
     *  |   It's been setted to true by default, can be changed anytime.
     * 
     * 
     *  @staticvar bool $log
     *  @var bool $log
     *  @return bool
     *  | 
     * 
     *  ---------------------------------------------------------------------------------------------------------
     */
    public static $log = true;



    /**
     *  ---------------------------------------------------------------------------------------------------------
     *  
     *  |   If `$request_log` is set to true, it prints logs in `.log` file in the root of the project each time any request has been received.
     *  |   It's been setted to true by default
     *
     *
     *  |   This function handles getting files request and describe the type of request to handle according to `phpslides.config.json` file in the root of the project,
     *  |   for more security, it disallow users in navigating to wrong paths or files of the project.
     *
     *
     *  |   This config method must be called before writing any other Route method or codes.
     *  
     *  ---------------------------------------------------------------------------------------------------------
     */
    public static function config(bool $request_log = true)
    {
        try
        {
            $dir = dirname(__DIR__);
            $req = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);
            $url = explode('/', $req);

            $config_file = self::config_file();


            if (!empty($config_file))
            {
                $config = $config_file['public'];

                // checks if the all url / match the key in json
                $url_val = "";
                $config_ext = [];


                // loop over the requested url folders
                foreach ($url as $index => $value)
                {

                    /**
                     *  -----------------------------------------------
                     *  |   Checks if array key from url exists in the config file
                     *  -----------------------------------------------
                     */
                    if (array_key_exists($url[$index], $config))
                    {
                        $url_val .= $value . '/';
                        $config_ext = [ ...$config[$value] ];
                    }
                    elseif ($index === count($url) - 1)
                    {
                        $url_val .= $url[$index];
                        $req_ext = explode('.', $url[$index]);
                        $req_ext = end($req_ext);

                        /**
                         *  ----------------------------------------------------------------------------------
                         *  |   Checks if requested extension is decaleared in the config file or * which signifies all types of extension
                         *  ----------------------------------------------------------------------------------
                         */
                        if (in_array($req_ext, $config_ext) || in_array('*', $config_ext))
                        {
                            if (file_exists($dir . '/public/' . $url_val) && filetype($dir . '/public/' . $url_val) === 'file')
                            {
                                http_response_code(200);
                                $charset = $config_file["charset"];
                                header("Content-type: */*, charset=$charset");
                                print_r(file_get_contents($dir . '/public/' . $url_val));
                                self::log();
                                exit;
                            }
                        }
                    }
                }


                /**
                 *  ------------------------------------------------------------------------
                 *  |   If request url is a file from / and is in the root directory of public folder
                 *  ------------------------------------------------------------------------
                 */
                if (
                array_key_exists('/', $config)
                && count($url) === 1
                && file_exists($dir . '/public/' . $url[0])
                && filetype($dir . '/public/' . $url[0]) === 'file'
                )
                {
                    $req_ext = explode('.', $url[0]);
                    $req_ext = end($req_ext);
                    $root = $config['/'];


                    /**
                     *  ---------------------------------------------------------------------------------------------
                     *  |   checks if the requested file extension is available in the config files or * which signifies all types of extension
                     *  ---------------------------------------------------------------------------------------------
                     */
                    for ($i = 0; $i < count($root); $i++)
                    {
                        if ($root[$i] === $req_ext || $root[$i] === '*')
                        {
                            http_response_code(200);
                            $charset = $config_file["charset"];
                            header("Content-type: */*, charset=$charset");
                            print_r(file_get_contents($dir . '/public/' . $url[0]));
                            self::log();
                            exit;
                        }
                    }
                }

                $request_log == true ? self::$log = true : self::$log = false;
            }
        }
        catch ( Exception $e )
        {
            print($e->getMessage());
            exit;
        }
    }




    /**
     *  ------------------------------------------------------------------------
     *  
     *  |   ANY REQUEST FROM ROUTE
     * 
     * 
     *  |   Accept all type of request or any other method
     * 
     * 
     *  |   Cannot evaluate `{ url parameters }` in route if it's an array 
     *  
     *  ------------------------------------------------------------------------
     */
    public static function any(array|string $route, $callback, $method = '*')
    {
        // will store all the parameters value in this array

        $req = [];
        $req_value = [];

        // will store all the parameters names in this array
        $paramKey = [];

        // finding if there is any {?} parameter in $route
        if (is_string($route))
        {
            preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);
        }

        // if the route does not contain any param call routing();

        if (empty($paramMatches[0]) || is_array($route))
        {
            $callback = self::routing($route, $callback);
            self::log();
            print_r(is_callable($callback) ? $callback() : $callback);

            exit;
        }


        // setting parameters names
        foreach ($paramMatches[0] as $key)
        {
            $paramKey[] = $key;
        }

        /** 
         *  ----------------------------------------------
         *  |   Replacing first and last forward slashes
         *  |   $_REQUEST['uri'] will be empty if req uri is /
         *  ----------------------------------------------
         */

        if (!empty($_REQUEST["uri"]))
        {
            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);
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
         *  ----------------------------------------------------------------------------------
         *  |   Exploding request uri string to array to get the exact index number value of parameter from $_REQUEST['uri']
         *  ----------------------------------------------------------------------------------
         */

        $reqUri = explode("/", $reqUri);


        /**
         *  ----------------------------------------------------------------------------------
         *  |   Running for each loop to set the exact index number with reg expression this will help in matching route
         *  ----------------------------------------------------------------------------------
         */

        foreach ($indexNum as $key => $index)
        {
            /** 
             *  --------------------------------------------------------------------------------
             *  |   In case if req uri with param index is empty then return because url is not valid for this route
             *  --------------------------------------------------------------------------------
             */

            if (empty($reqUri[$index]))
            {
                return;
            }

            // setting params with params names
            $req[$paramKey[$key]] = $reqUri[$index];
            $req_value[] = $reqUri[$index];

            // this is to create a regex for comparing route address
            $reqUri[$index] = "{.*}";
        }

        // converting array to string
        $reqUri = implode("/", $reqUri);

        /** 
         *  -----------------------------------
         *  |   replace all / with \/ for reg expression
         *  |   regex to match route is ready!
         *  -----------------------------------
         */

        $reqUri = str_replace("/", "\\/", $reqUri);

        // now matching route with regex
        if (preg_match("/$reqUri/", $route))
        {
            // checks if the requested method is of the given route
            if ($_SERVER['REQUEST_METHOD'] !== $method || $method !== '*')
            {
                http_response_code(405);
                self::log();
                exit('Method Not Allowed');
            }

            $charset = self::config_file()['charset'];
            header("Content-type: */*, charset=$charset");
            http_response_code(200);

            print_r($callback(...$req_value));
            self::log();
            exit;
        }
    }



    /**
     *  ---------------------------------------------------------------------------
     *  
     *  |   VIEW ROUTE METHOD 
     * 
     *  |   Route only needs to return a view; you may provide an array for multiple request
     *     
     *  ---------------------------------------------------------------------------
     */
    public static function view(array|string $route, string $view)
    {

        /**
         *  ----------------------------------------
         *  |   Replacing first and last forward slashes
         *  |   $_REQUEST['uri'] will be empty if req uri is /
         *  ----------------------------------------
         */

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

            if ($_SERVER['REQUEST_METHOD'] !== 'GET')
            {
                http_response_code(405);
                self::log();
                exit('Method Not Allowed');
            }


            // render view page to browser
            $view = view::render($view);

            if ($view)
            {
                $charset = self::config_file()['charset'];
                header("Content-type: */*, charset=$charset");
                http_response_code(200);
                print_r($view);
                self::log();

                exit;
            }
        }
    }




    /**
     *  --------------------------------------------------------------
     * 
     *  |   REDIRECT ROUTE METHOD
     * 
     *  |   This method redirects the routes url to the giving url directly
     * 
     * ---------------------------------------------------------------
     */
    public static function redirect(string $route, string $new_url, int $code = 301)
    {
        if (!empty($_REQUEST["uri"]))
        {
            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);
        }
        else
        {
            $reqUri = "/";
        }

        if ($reqUri === $route)
        {
            header("Location: " . $new_url, true, $code);
            exec('');
        }
    }




    /**
     *  --------------------------------------------------------------
     * 
     *  |   GET ROUTE METHOD
     * 
     *  |   Cannot evaluate { url parameters } in route if it's an array 
     * 
     *  --------------------------------------------------------------
     */
    public static function get(array|string $route, $callback)
    {
        self::any($route, $callback, 'GET');
    }



    /**
     *  --------------------------------------------------------------
     * 
     *  |   POST ROUTE METHOD
     * 
     *  |   Cannot evaluate { url parameters } in route if it's an array 
     * 
     *   --------------------------------------------------------------
     */
    public static function post(array|string $route, $callback)
    {
        self::any($route, $callback, 'POST');
    }



    /**
     *  --------------------------------------------------------------
     * 
     *  |   UPDATE ROUTE METHOD
     * 
     *  |   Cannot evaluate { url parameters } in route if it's an array 
     * 
     *  --------------------------------------------------------------
     */
    public static function update(array|string $route, $callback)
    {
        self::any($route, $callback, 'UPDATE');
    }


    /**
     *  --------------------------------------------------------------
     * 
     *  |   DELETE ROUTE METHOD
     * 
     *  |   Cannot evaluate { url parameters } in route if it's an array 
     * 
     *  --------------------------------------------------------------
     */
    public static function delete(array|string $route, $callback)
    {
        self::any($route, $callback, 'DELETE');
    }



    /**
     *  --------------------------------------------------------------
     * 
     *  |   Not Found Error
     * 
     *  |   This route serves as 404, which executes whenever there're no matching routes from the request url
     *  |   which takes a callback parameter that is rendered to the webpage
     * 
     * --------------------------------------------------------------
     */
    public static function notFound($callback)
    {
        $charset = self::config_file()['charset'];
        header("Content-type: */*, charset=$charset");
        http_response_code(404);

        print_r(is_callable($callback) ? $callback() : $callback);
        self::log();
    }
}




/**
 *  --------------------------------------------------------------
 * 
 *  |   Router View
 * 
 *  |   which control the public url and validating
 * 
 *  --------------------------------------------------------------
 */
final class view
{


    /**
     *  --------------------------------------------------------------
     * 
     *  |   Render views and parse public url in views
     * 
     * @param string $view
     * @return string return the file gotten from the view parameters
     * 
     *  --------------------------------------------------------------
     */
    public static function render(string $view): string
    {
        try
        {
            // split :: into array and extract the folder and files
            $file = preg_split('/(::)|::/', $view);
            $view_path = '';

            foreach ($file as $index => $item)
            {
                if ($index !== count($file) - 1)
                {
                    $view_path .= '/' . $item;
                }
                else
                {
                    $view_path .= '/';
                }
            }

            $file_uri = dirname(__DIR__) . $view_path . $file[count($file) - 1];

            if (is_file($file_uri . '.view.php'))
            {
                return file_get_contents($file_uri . '.view.php');
            }
            else if (is_file($file_uri))
            {
                return file_get_contents($file_uri);
            }
            else
            {
                throw new Exception("No view controller path found called `$view`");
            }
        }
        catch ( Exception $e )
        {
            print($e->getMessage());
            return false;
        }
    }
}