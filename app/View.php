<?php
    namespace App;

    class View {
        public static function render () {
            ob_start();

            include("templates/templates.php");
            $content = ob_get_contents();

            ob_end_flush();

            return $content;
        }
    }