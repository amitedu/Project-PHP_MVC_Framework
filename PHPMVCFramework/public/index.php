<?php
    require_once __DIR__.'/../vendor/autoload.php';
    use app\core\Application;

    $app = new Application();

    $app->router->get('/', function(){
        echo 'Hello World';
    });

    $app->router->get('/users', function(){
        echo 'Hello User';
    });

    $app->router->get('/contact', function(){
        echo 'Hello Contact';
    });

    $app->run();