<?php

namespace Tualo\Office\DynamicRoutes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\DS\DSTable;

use Tualo\Office\PUG\PUG2;
use Tualo\Office\PUG\PUGRenderingHelper;

use Tualo\Office\CMS\CMSMiddlewareHelper;

class DR extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return 'basic';
    }

    public static function register()
    {
        Route::add('/dr/(?P<route>.*)', function ($matches) {

            $session = TualoApplication::get('session');
            $db = $session->getDB();

            try {
            } catch (\Exception $e) {
                http_response_code(500);
                echo $e->getMessage();
            }
        }, ['get', 'post', 'put', 'delete'], false, [], self::scope());
    }
}
