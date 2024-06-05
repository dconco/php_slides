<?php

declare(strict_types=1);

namespace PhpSlides;

use Exception;
use PhpSlides\Controller\Controller;



/**
 *   -------------------------------------------------------------------------------
 *
 *   CREATE A NEW ROUTE
 *
 *   Create route & api that accept different methods and render to the client area
 *
 *   @author Dave Conco <concodave@gmail.com>
 *   @link https://github.com/dconco/php_slides
 *   @category api, router, php router, php
 *   @copyright 2023 - 2024 Dave Conco
 *   @package PhpSlides
 *   @version ^1.0.0
 *   @return void
 * |
 *
 *   -------------------------------------------------------------------------------
 */

final class Route extends Controller
{

    /**
     *  `$log` method prints logs in `.log` file in the root of the project each time any request has been received, when setted to true.
     *   It's been setted to true by default, can be changed anytime.
     *
     *   @static $log
     *   @var bool $log
     *   @return bool
     */
    public static bool $log;

    /**
     *   Gets the full location of project root directory
     * 
     *   @static $root_dir
     *   @var string $root_dir
     *   @return string Location directory of project with `__DIR__`
     */
    public static string $root_dir;

    /**
     * Get's all full request URL
     * 
     * @static $root_dir
     * @var string $root_dir
     * @return string
     */
    public static string $request_uri;



    /**
     *   ------------------------------------------------------
     *   |
     *   |   Get the file extension content-type with mime
     *
     *   @param string $filename File path or file resources
     *   @return bool|string Returns the MIME content type for a file as determined by using information from the magic.mime file.
     *   |
     *   ------------------------------------------------------
     */
    public static function file_type(string $filename): bool|string
    {
        if (is_file($filename))
        {
            if (!extension_loaded('fileinfo'))
            {
                throw new Exception(
                        'Fileinfo extension is not enabled. Please enable it in your php.ini configuration.',
                );
            }

            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($file_info, $filename);
            finfo_close($file_info);

            $file_ext = explode('.', $filename);
            $file_ext = strtolower(end($file_ext));

            if ($file_type === 'text/plain' || $file_type === 'application/octet-stream')
            {
                switch ($file_ext)
                {
                    case 'css':
                        return 'text/css';
                    case 'csv':
                        return 'text/csv';
                    case 'htm':
                        return 'text/htm';
                    case 'html':
                        return 'text/html';
                    case 'js':
                        return 'application/javascript';
                    case 'pdf':
                        return 'application/pdf';
                    case 'doc':
                        return 'application/msword';
                    case 'docx':
                        return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                    case 'xls':
                        return 'application/vnd.ms-excel';
                    case 'xlsx':
                        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    case 'json':
                        return 'application/json';
                    case 'md':
                        return 'text/markdown';
                    case 'ppt':
                        return 'application/mspowerpoint';
                    case 'pptx':
                        return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                    case 'swf':
                        return 'application/x-shockwave-flash';
                    case 'ai':
                        return 'application/postscript';
                    case 'odt':
                        return 'application/vnd.oasis.opendocument.text';

                    default:
                        return $file_type;
                }
            }
            else
            {
                return $file_type;
            }
        }
        else
        {
            return false;
        }
    }




    /**
     *   ---------------------------------------------------------------------------------------------------------
     *
     *   |   If `$request_log` is set to true, it prints logs in `.log` file in the root of the project each time any request has been received.
     *   |   It's been setted to true by default
     *
     *
     *   |   This function handles getting files request and describe the type of request to handle according to `phpslides.config.json` file in the root of the project,
     *   |   for more security, it disallow users in navigating to wrong paths or files of the project.
     *
     *
     *   |   This config method must be called before writing any other Route method or codes.
     *   |
     *
     *   @param bool $request_log The parameter indicates request logger to prints out logs output on each received request
     *
     *   ---------------------------------------------------------------------------------------------------------
     */
    public static function config(bool $request_log = true)
    {
        try
        {
            self::$log = $request_log;
            self::$root_dir = htmlspecialchars(dirname(getcwd()));
            self::$request_uri = htmlspecialchars($_REQUEST['uri']);

            $dir = self::$root_dir;
            $req = preg_replace("/(^\/)|(\/$)/", '', self::$request_uri);
            $url = explode('/', $req);

            $req_ext = explode('.', end($url));
            $req_ext = strtolower(end($req_ext));

            $file = self::slides_include($dir . '/public/' . $req);
            $file_type = $file ? self::file_type($dir . '/public/' . $req) : null;

            $config_file = self::config_file();

            $charset = $config_file['charset'];

            /**
             *   ----------------------------------------------
             *   |   Config File & Request Router configurations
             *   ----------------------------------------------
             */
            if (!empty($config_file) && $file_type != null)
            {
                $config = $config_file['public'];
                $accept = true;

                // loop over the requested URL folders
                foreach ($url as $index => $value)
                {

                    /**
                     *   -----------------------------------------------
                     *   |   Checks if array key from URL exists in the config file
                     *   -----------------------------------------------
                     */
                    if (array_key_exists($value, $config))
                    {
                        if (in_array($req_ext, $config[$value]) && $accept != false)
                        {
                            $accept = $req_ext;

                            /**
                             *   -----------------------------------------------
                             *   |   Checks if the next array key from URL exists in the config file
                             *   -----------------------------------------------
                             */
                            if (array_key_exists($url[$index + 1], $config))
                            {
                                continue;
                            }
                            /**
                             *   -----------------------------------------------
                             *   |   Performs the logic for accepting current file
                             *   -----------------------------------------------
                             */
                            else
                            {
                                http_response_code(200);
                                header("Content-Type: $file_type");

                                print_r($file);
                                self::log();

                                exit();
                            }
                        }
                        /**
                         *   -----------------------------------------------------------
                         *   |   Checks if * or image exists in the config file of the $value
                         *   |   Then it accept all types of files or all types of image in the childrens folder
                         *   -----------------------------------------------------------
                         */
                        elseif (
                        in_array('*', $config[$value]) ||
                        (in_array('image', $config[$value]) &&
                        preg_match('/(image\/*)/', $file_type)) ||
                        (in_array('video', $config[$value]) &&
                        preg_match('/(video\/*)/', $file_type)) ||
                        (in_array('audio', $config[$value]) &&
                        preg_match('/(audio\/*)/', $file_type) &&
                        $accept != false)
                        )
                        {
                            $accept = '*';

                            if (array_key_exists($url[$index + 1], $config))
                            {
                                continue;
                            }
                            /**
                             *  -----------------------------------------------
                             *  |     Performs the logic for accepting current file
                             *  -----------------------------------------------
                             */
                            else
                            {
                                http_response_code(200);
                                header("Content-Type: $file_type");

                                print_r($file);
                                self::log();

                                exit();
                            }
                        }
                        else
                        {
                            $accept = false;
                        }
                    }
                }

                /**
                 *   ------------------------------------------------------------------------
                 *   |   If request URL is a file from / and is in the root directory of public folder
                 *   ------------------------------------------------------------------------
                 */
                if (
                array_key_exists('/', $config) &&
                count($url) === 1 &&
                is_file($dir . '/public/' . $url[0])
                )
                {
                    $req_ext = explode('.', $url[0]);
                    $req_ext = strtolower(end($req_ext));
                    $root = $config['/'];

                    /**
                     *   ---------------------------------------------------------------------------------------------
                     *   |   checks if the requested file extension is available in the config files or * which signifies all types of extension
                     *   ---------------------------------------------------------------------------------------------
                     */
                    for ($i = 0; $i < count($root); $i++)
                    {
                        $root1 = strtolower($root[$i]);

                        if (
                        $root1 === $req_ext ||
                        $root1 === '*' ||
                        ($root1 === 'image' && preg_match('/(image\/*)/', $req_ext)) ||
                        ($root1 === 'video' && preg_match('/(video\/*)/', $req_ext)) ||
                        ($root1 === 'audio' && preg_match('/(audio\/*)/', $req_ext))
                        )
                        {
                            http_response_code(200);
                            header("Content-Type: $file_type");

                            print_r($file);
                            self::log();
                            exit();
                        }
                    }
                }
            }


            /**
             *   ----------------------------------------------
             *   | Generates Bin files
             *   ----------------------------------------------
             */
            $gen_codes1 = "<?php\n\n";
            $gen_codes2 = "/**\n *   ----------------------------------------------------------------------------------------------------------------\n";
            $gen_codes3 = " *   |  This codes in bin directory are generated by PhpSlides.\n";
            $gen_codes4 = " *   |  They're regenerated on every page renderings or APIs.\n *   |  And every request received.\n";
            $gen_codes5 = " *   |  Used in making APIs ready for developers to be able to deal with.\n *   |  So accordingly to slides protection from handling files, and other slides inbuilt function,\n";
            $gen_codes6 = " *   |  It's not adviceable or recommended in deleting or changing some files in this bin directory or any other generated folder from slides project,\n";
            $gen_codes7 = " *   |  to prevent occured errors in file handling, compatibility in security based web performance.\n";
            $gen_codes8 = " *   ----------------------------------------------------------------------------------------------------------------\n */\n\n";
            $gen_codes9 = "(function ()\n{\n\t@'generated pages';\n})();";

            $concatted = $gen_codes1 . $gen_codes2 . $gen_codes3 . $gen_codes4 . $gen_codes5 . $gen_codes6 . $gen_codes7 . $gen_codes8;


            !is_dir($dir . '/App/bin') && mkdir($dir . '/App/bin');
            file_put_contents($dir . '/App/bin/BLOBS.php', "<?php\n\n$gen_codes9");
            file_put_contents($dir . '/App/bin/README.php', $concatted . $gen_codes9);


            /**
             *   ----------------------------------------------
             *   | Includes The Web Api
             *   ----------------------------------------------
             */
            ob_start();
            $web_file = include $dir . '/src/web.php';
            ob_end_clean();

            $data = $concatted . $gen_codes9 . "\n\ndefine('SLIDES_VERSION', '1.2.1');\n";

            $arr_num = [];
            $arr_const_types = [];
            $arr_keys = array_keys($web_file);
            $arr_vals = array_values($web_file);

            // Looping over the web.php array
            // Generate random array number key to store in bin const_types
            for ($i = 0; $i < count($web_file); $i++)
            {
                $rand = rand(0, 999);
                $key_capitalized = strtoupper($arr_keys[$i]);
                $data .= "define('$key_capitalized', $rand);\n";

                if (in_array($rand, $arr_num))
                {
                    $rand = rand(0, 9999);
                    array_push($arr_num, $rand);
                    $arr_const_types[$rand] = $arr_vals[$i];
                }
                else
                {
                    array_push($arr_num, $rand);
                    $arr_const_types[$rand] = $arr_vals[$i];
                }
            }

            // Add the const_types information to the file
            $const_types_data = $concatted . "return ('" . bin2hex(serialize($arr_const_types)) . "');";
            file_put_contents($dir . '/App/bin/const_types.php', $const_types_data);

            // Add the defined constants data to the bin file
            file_put_contents($dir . '/App/bin/constant.php', $data);

            // Gets the api.php file contents
            include_once $dir . '/App/bin/constant.php';
            include_once $dir . '/routes/api.php';

            $request_log == true ? (self::$log = true) : (self::$log = false);
        }
        catch ( Exception $e )
        {
            print $e->getMessage();
            exit();
        }
    }


    /**
     *   ------------------------------------------------------------------------
     *
     *   |   ANY REQUEST FROM ROUTE
     *
     *   |   Accept all type of request or any other method
     *
     *   |   Cannot evaluate `{?} URL parameters` in route if it's an array
     *   |
     *
     *   @param array|string $route This describes the URL string to check if it matches the request URL, use array of URLs for multiple request
     *   @param mixed $callback Can contain any types of data to return to the client side/browser.
     *
     *   ------------------------------------------------------------------------
     */
    public static function any(array|string $route, mixed $callback, string $method = '*')
    {

        /**
         *   --------------------------------------------------------------
         *
         *   |   Not Found Error
         *
         *   |   This * route serves as 404, which executes whenever there're no matching routes from the request url
         *   |   which takes a callback parameter that is rendered to the webpage
         *
         * --------------------------------------------------------------
         */

        if ((is_array($route) && in_array('*', $route)) || $route === '*')
        {
            header('HTTP/1.0 404 Not Found');
            header('Content-Type: */*');

            print_r(is_callable($callback) ? $callback() : $callback);
            self::log();
            exit();
        }

        // will store all the parameters value in this array
        $req = [];
        $req_value = [];

        // will store all the parameters names in this array
        $paramKey = [];

        // finding if there is any {?} parameter in $route
        if (is_string($route))
        {
            preg_match_all('/(?<={).+?(?=})/', $route, $paramMatches);
        }

        // if the route does not contain any param call routing();
        if (empty($paramMatches[0]) || is_array($route))
        {

            /**
             *   ------------------------------------------------------
             *   |   Check if $callback is a callable function
             *   |   or array of controller, and if not,
             *   |   it's a string of text or html document
             *   ------------------------------------------------------
             */
            $callback = self::routing($route, $callback, $method);

            if ($callback)
            {
                if (
                is_array($callback) &&
                (preg_match('/(Controller)/', $callback[0], $matches) &&
                count($matches) > 1)
                )
                {
                    print_r(
                        self::controller(
                            $callback[0],
                            count($callback) > 1 ? $callback[1] : '',
                        ),
                    );
                }
                else
                {
                    print_r(is_callable($callback) ? $callback() : $callback);
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
         *   ----------------------------------------------
         *   |   Replacing first and last forward slashes
         *   |   $_REQUEST['uri'] will be empty if req uri is /
         *   ----------------------------------------------
         */

        if (!empty(self::$request_uri))
        {
            $route = preg_replace("/(^\/)|(\/$)/", '', $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", '', self::$request_uri);
        }
        else
        {
            $reqUri = '/';
        }

        // exploding route address
        $uri = explode('/', $route);

        // will store index number where {?} parameter is required in the $route
        $indexNum = [];

        // storing index number, where {?} parameter is required with the help of regex
        foreach ($uri as $index => $param)
        {
            if (preg_match('/{.*}/', $param))
            {
                $indexNum[] = $index;
            }
        }

        /**
         *   ----------------------------------------------------------------------------------
         *   |   Exploding request uri string to array to get the exact index number value of parameter from $_REQUEST['uri']
         *   ----------------------------------------------------------------------------------
         */
        $reqUri = explode('/', $reqUri);

        /**
         *   ----------------------------------------------------------------------------------
         *   |   Running for each loop to set the exact index number with reg expression this will help in matching route
         *   ----------------------------------------------------------------------------------
         */
        foreach ($indexNum as $key => $index)
        {
            /**
             *   --------------------------------------------------------------------------------
             *   |   In case if req uri with param index is empty then return because URL is not valid for this route
             *   --------------------------------------------------------------------------------
             */

            if (empty($reqUri[$index]))
            {
                return;
            }

            // setting params with params names
            $req[$paramKey[$key]] = htmlspecialchars($reqUri[$index]);
            $req_value[] = htmlspecialchars($reqUri[$index]);

            // this is to create a regex for comparing route address
            $reqUri[$index] = '{.*}';
        }

        // converting array to string
        $reqUri = implode('/', $reqUri);

        /**
         *   -----------------------------------
         *   |   replace all / with \/ for reg expression
         *   |   regex to match route is ready!
         *   -----------------------------------
         */
        $reqUri = str_replace('/', '\\/', $reqUri);

        // now matching route with regex
        if (preg_match("/$reqUri/", $route))
        {
            // checks if the requested method is of the given route
            if (
            strtoupper($_SERVER['REQUEST_METHOD']) !== strtoupper($method) &&
            $method !== '*'
            )
            {
                http_response_code(405);
                self::log();
                exit('Method Not Allowed');
            }

            http_response_code(200);
            header('Content-Type: */*');

            if (
            is_array($callback) &&
            (preg_match('/(Controller)/', $callback[0], $matches) &&
            count($matches) > 1)
            )
            {
                print_r(
                        self::controller(
                                $callback[0],
                                count($callback) > 1 ? $callback[1] : '',
                                $req_value,
                        ),
                );
            }
            else
            {
                print_r(is_callable($callback) ? $callback(...$req_value) : $callback);
            }

            self::log();
            exit();
        }
    }



    /**
     *   ---------------------------------------------------------------------------
     *
     *   |   VIEW ROUTE METHOD
     *
     *   |   Route only needs to return a view; you may provide an array for multiple request
     *
     *   |   View Route does not accept `{?} URL parameters` in route, use GET method instead
     *
     *           @param array|string $route This describes the URL string to render, use array of strings for multiple request
     *           @param string $view It renders this param, it can be functions to render, view:: to render or strings of text or documents
     *   |
     *
     *   ---------------------------------------------------------------------------
     */
    public static function view(array|string $route, string $view)
    {

        /**
         *   ----------------------------------------
         *   |   Replacing first and last forward slashes
         *   |   $_REQUEST['uri'] will be empty if req uri is /
         *   ----------------------------------------
         */

        $uri = [];
        $str_route = '';
        $reqUri = preg_replace("/(^\/)|(\/$)/", '', self::$request_uri);

        if (is_array($route))
        {
            for ($i = 0; $i < count($route); $i++)
            {
                $each_route = preg_replace("/(^\/)|(\/$)/", '', $route[$i]);
                array_push($uri, $each_route);
            }
        }
        else
        {
            $str_route = preg_replace("/(^\/)|(\/$)/", '', $route);
        }

        if (in_array($reqUri, $uri) || $reqUri === $str_route)
        {
            if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'GET')
            {
                http_response_code(405);
                self::log();
                exit('Method Not Allowed');
            }

            // render view page to browser
            $view = view::render($view) !== 'null' ? view::render($view) : $view;

            if ($view != 'null')
            {
                print_r($view);
                self::log();
                exit();
            }
        }
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   REDIRECT ROUTE METHOD
     *
     *   |   This method redirects the routes URL to the giving URL directly
     *
     *   @param string $route The requested url to redirect
     *   @param string $new_url The new URL route to redirect to
     *   @param int $code The code for redirect method, 301 for permanent redirecting & 302 for temporarily redirect.
     *
     * ---------------------------------------------------------------
     */
    public static function redirect(string $route, string $new_url, int $code = 302)
    {
        if (!empty(self::$request_uri))
        {
            $route = preg_replace("/(^\/)|(\/$)/", '', $route);
            $new_url = preg_replace("/(^\/)|(\/$)/", '', $new_url);
            $reqUri = preg_replace("/(^\/)|(\/$)/", '', self::$request_uri);
        }
        else
        {
            $reqUri = '/';
            $new_url = preg_replace("/(^\/)|(\/$)/", '', $new_url);
        }

        if ($reqUri === $route)
        {
            header("Location: $new_url", true, $code);
            exit();
        }
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   GET ROUTE METHOD
     *
     *   |   Cannot evaluate {?} URL parameters in route if it's an array
     *
     *   --------------------------------------------------------------
     */
    public static function get(array|string $route, $callback)
    {
        self::any($route, $callback, 'GET');
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   POST ROUTE METHOD
     *
     *   |   Cannot evaluate {?} URL parameters in route if it's an array
     *
     *   --------------------------------------------------------------
     */
    public static function post(array|string $route, $callback)
    {
        self::any($route, $callback, 'POST');
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   PUT ROUTE METHOD
     *
     *   |   Cannot evaluate {?} URL parameters in route if it's an array
     *
     *   --------------------------------------------------------------
     */
    public static function put(array|string $route, $callback)
    {
        self::any($route, $callback, 'PUT');
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   UPDATE ROUTE METHOD
     *
     *   |   Cannot evaluate {?} URL parameters in route if it's an array
     *
     *   --------------------------------------------------------------
     */
    public static function update(array|string $route, $callback)
    {
        self::any($route, $callback, 'UPDATE');
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   PATCH ROUTE METHOD
     *
     *   |   Cannot evaluate {?} URL parameters in route if it's an array
     *
     *   --------------------------------------------------------------
     */
    public static function patch(array|string $route, $callback)
    {
        self::any($route, $callback, 'PATCH');
    }



    /**
     *   --------------------------------------------------------------
     *
     *   |   DELETE ROUTE METHOD
     *
     *   |   Cannot evaluate {?} URL parameters in route if it's an array
     *
     *   --------------------------------------------------------------
     */
    public static function delete(array|string $route, $callback)
    {
        self::any($route, $callback, 'DELETE');
    }
}



/**
 *   --------------------------------------------------------------
 *
 *   |   Router View
 *
 *   |   which control the public URL and validating
 *
 *   --------------------------------------------------------------
 */
final class view extends Controller
{

    /**
     *   --------------------------------------------------------------
     *
     *   |   Render views and parse public URL in views
     *
     * @param string $view
     * @return mixed return the file gotten from the view parameters
     *
     *   --------------------------------------------------------------
     */
    final public static function render(string $view): mixed
    {
        try
        {
            // split :: into array and extract the folder and files
            $file = preg_replace('/(::)|::/', '/', $view);
				$file = strtolower(trim($file, '\/\/'));
            $view_path = '/views/' . $file;

            $file_uri = Route::$root_dir . $view_path;

            if (is_file($file_uri . '.view.php') && !preg_match('/(..\/)/', $view))
            {
                $file_type = Route::file_type($file_uri . '.view.php');
                header("Content-Type: $file_type");

                return self::slides_include($file_uri . '.view.php');
            }
            elseif (is_file($file_uri) && !preg_match('/(..\/)/', $view))
            {
                $file_type = Route::file_type($file_uri);
                header("Content-Type: $file_type");

                return self::slides_include($file_uri);
            }
            else
            {
                throw new Exception("No view controller path found called `$view`");
            }
        }
        catch ( Exception $e )
        {
            print $e->getMessage();
            return 'null';
        }
    }
}
