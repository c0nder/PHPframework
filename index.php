<?php

    require_once __DIR__ . "/vendor/autoload.php";

    require_once __DIR__ . "/routes.php";

   \App\Router::handle(new \App\Request());