<?php
    namespace App;

    class Request {
        private $uri;
        private $query = [];
        private $requestMethod;

        public function __construct()
        {
            $this->setQuery();
            $this->setURI();
            $this->setRequestMethod();
        }

        private function setQuery() {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $queryString = explode('&', $_SERVER['QUERY_STRING']);

                foreach ($queryString as $param) {
                    $paramArray = explode('=', $param);
                    $this->query[$paramArray[0]] = isset($paramArray[1]) ? $paramArray[1] : null;
                }
            }
        }

        private function setURI() {
            $this->uri = $_SERVER['REQUEST_URI'];

            if (mb_strpos($this->uri, '?') !== false) {
                $this->uri = explode('?', $this->uri)[0];
            }
        }

        private function setRequestMethod() {
            $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        }

        public function get($needle) {
            if (array_key_exists($needle, $this->query)) {
                return $this->query[$needle];
            }

            return null;
        }

        public function exists($needle) {
            if (array_key_exists($needle, $this->query)) {
                return true;
            }

            return false;
        }

        public function getURI() {
            return $this->uri;
        }

        public function getRequestMethod() {
            return $this->requestMethod;
        }
    }