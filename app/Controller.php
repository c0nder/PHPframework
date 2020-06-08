<?php

    namespace App;

    class Controller {
        private $className;
        private $method;

        public function setClassName($className) {
            $this->className = 'Controllers\\' . $className;
        }

        public function setMethod($methodName) {
            $this->method = $methodName;
        }

        public function call($args) {
            $class = new $this->className();

            if (method_exists($class, $this->method)) {
                call_user_func_array(
                    [
                        $class,
                        $this->method
                    ],
                    $args
                );
            }
        }
    }