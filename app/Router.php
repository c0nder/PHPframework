<?php
    namespace App;

    class Router {
        private static $routes = [];

        public static function addRoute($path, $controller, $requestMethod) {
            $controllerSettings = explode('.', $controller);

            $route = new Route();
            $route->requestMethod = mb_strtoupper($requestMethod);

            $route->controller = new Controller();
            $route->controller->setInstance($controllerSettings[0]);
            $route->controller->setMethod($controllerSettings[1]);

            $route->setPath($path);

            //todo: Можно сделать репозиторий для хранения
            static::$routes[] = $route;
        }

        private static function getRoute($path) {
            $routes = static::$routes;

            foreach ($routes as $route) {
                if ($route->isEquals($path)) {
                    $route->setArgumentsFromPath($path);

                    return $route;
                }
            }

            return false;
        }

        public static function handle(Request $request) {
            $route = static::getRoute($request->getURI());

            if ($route && $route->requestMethod == $request->getRequestMethod()) {
                $route->controller->dispatch($route->arguments);
            }
        }
    }