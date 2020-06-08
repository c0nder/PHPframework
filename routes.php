<?php

    use App\Router;

    Router::addRoute('/user', 'TestController.test', 'get');
    Router::addRoute('/user/{user_id}', 'TestController.user', 'get');
    Router::addRoute('/user/{user_id}/{category}', 'TestController.test', 'get');