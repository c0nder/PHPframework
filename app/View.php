<?php
    namespace App;

    class View {
        const VIEW_FOLDER = 'templates/';

        public static function exists(string $layoutPath)
        {
            $path = View::VIEW_FOLDER;
            $path .= str_replace('.', DIRECTORY_SEPARATOR, $layoutPath);
            $path .= '.php';

            if (!file_exists($path)) {
                return false;
            }

            return $path;
        }

        public static function render($filePath, array $params = []) {
            if (!($fullPath = static::exists($filePath))) {
                throw new \RuntimeException('Layout file was not found');
            }

            extract($params);
            ob_start();

            include $fullPath;
            $content = ob_get_contents();

            ob_end_flush();
            return $content;
        }
    }