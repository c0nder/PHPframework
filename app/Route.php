<?php
    namespace App;

    class Route {
        private $path;
        private $pathRegexp;

        public $requestMethod;
        public $controller;
        public $controllerMethod;
        public $arguments = [];

        public function isEquals($path) {
            return preg_match($this->pathRegexp, $path);
        }

        public function routeToRegularExpression($path) {
            $regularExpression = '/^';

            $path = preg_replace('/\//', '\/', $path);
            $path = preg_replace('/(\{.+?\})/', '([0-9A-Za-z]+[\/]?)', $path);

            $regularExpression .= $path;
            $regularExpression .= '$/';

            return $regularExpression;
        }

        public function setArgumentsFromPath($path) {
            preg_match_all('/\{(.+?)\}/', $this->path, $routeArgumentNames);
            preg_match($this->pathRegexp, $path, $routeArgumentValues);

            if (count($routeArgumentNames) > 1 && count($routeArgumentValues) > 1) {
                unset($routeArgumentNames[0]);
                unset($routeArgumentValues[0]);

                $this->arguments = array_combine($routeArgumentNames[1], $routeArgumentValues);
            }
        }

        public function setPath($path) {
            $this->path = $path;
            $this->pathRegexp = $this->routeToRegularExpression($path);
        }
    }