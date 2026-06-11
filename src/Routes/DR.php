<?php

namespace Tualo\Office\DynamicRoutes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route;
use Tualo\Office\DynamicRoutes\Routes;

class DR extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return 'basic';
    }

    public static function register()
    {
        Route::add('/dr/(?P<route>.*)', function ($matches) {


            try {
                $routeInstance = Routes::getInstance();
                if ($routeInstance->canRun($matches['route'], $_SERVER['REQUEST_METHOD'])) {
                    $routeInstance->run($matches['route'], $_SERVER['REQUEST_METHOD']);
                    return;
                } else {
                    http_response_code(404);
                    echo "Route not found";
                }
            } catch (\Exception $e) {
                http_response_code(500);
                App::logger('DynamicRoutes')->error($e->getMessage());
            }
        }, ['get', 'post', 'put', 'delete'], false, [], self::scope());
    }
}
