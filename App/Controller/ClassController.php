<?php

namespace PhpSlides\Controller;

use Exception;


/**
 *  ---------------------------------------------------------------
 * 
 *  |   ClassController makes you declear routes rendering class components
 *  
 *  |   In Using Parameters with class and have full functions with router
 * 
 *  ---------------------------------------------------------------
 */
class ClassController extends Controller
{

    /**
     *  @param object|string $class In implementing class constructor from Controller
     *  @param string $method In accessing methods to render to routes
     *  @return mixed From class methods and __invoke function
     */

    protected static function __class(object|string $class, string $method, array|null $param = null)
    {
        try
        {
            if (class_exists($class))
            {
                $instance = new $class;
                $class_info = [
                    'method' => $method,
                    'class_name' => $class,
                    'class_methods' => get_class_methods($instance)
                ];

                return self::class_info($class_info, $param);
            }
            else
            {
                throw new Exception("No Controller class found in - $class", 1);
            }
        }
        catch ( Exception $e )
        {
            print($e->getMessage());
            exit;
        }
    }
}