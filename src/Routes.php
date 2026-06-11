<?php

namespace Tualo\Office\DynamicRoutes;

use Tualo\Office\Basic\TualoApplication as App;

class Routes
{
    private static bool $initalized = false;
    private static ?Routes $instance = null;
    private static $dbInstance = null;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
            self::$instance->initialize();
        }
        return self::$instance;
    }

    private function initialize(): void
    {
        if (!self::$initalized) {
            $session = TualoApplication::get('session');
            self::$dbInstance = $session->getDB();

            self::cachePath();
            self::$initalized = true;
        }
    }

    private function cachePath(): string
    {
        $path = (string)App::get('basePath') . '/cache/dynamicroutes';
        if (!file_exists($path)) mkdir($path, 0777, true);

        return $path;
    }

    private function exists(string $file): bool
    {
        return file_exists($this->cachePath() . '/' . $file);
    }

    public function canRun(string $route, string $method): bool
    {
        $sql = 'select 
            dynamic_routes.route 
        from 
            dynamic_routes
            join dynamic_routes_methods on dynamic_routes_methods.route_id = dynamic_routes.id
        where 
            dynamic_routes.route = {route} and dynamic_routes_methods.method = {method}';

        $result = self::$dbInstance->singleValue($sql, ['route' => $route, 'method' => $method], 'route');

        if ($result) {
            if (preg_match('#^' . $result . '$#', $route, $matches)) {
                return true;
            }
        }
        return false;
    }

    public function run(string $route, string $method): bool
    {
        $sql = 'select 
            dynamic_routes.id,
            dynamic_routes.route,
            dynamic_routes.template,
            dynamic_routes.checksum
        from 
            dynamic_routes
            join dynamic_routes_methods on dynamic_routes_methods.route_id = dynamic_routes.id
        where 
            dynamic_routes.route = {route} and dynamic_routes_methods.method = {method}';

        $result = self::$dbInstance->singleRow($sql, ['route' => $route, 'method' => $method], 'route');

        if ($result) {

            if (!$this->exists($result['id'] . '.php')) {
                file_put_contents($this->cachePath() . '/' . $result['id'] . '.php', $result['template']);
            }

            if (file_exists($this->cachePath() . '/' . $result['id'] . '.php')) {
                $checksum = md5_file($this->cachePath() . '/' . $result['id'] . '.php');
                if ($checksum !== $result['checksum']) {
                    file_put_contents($this->cachePath() . '/' . $result['id'] . '.php', $result['template']);
                }
            }



            if (preg_match('#^' . $result['route'] . '$#', $route, $matches)) {

                include $this->cachePath() . '/' . $result['id'] . '.php';
                return true;
            }
        }
        return false;
    }
}
