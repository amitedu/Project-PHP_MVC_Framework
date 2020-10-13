<?php

namespace app\core;

class Application
{
    public static $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public static Application $app;
    public Controller $controller;

    public function __construct($rootPath, $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db = new Database($config['db']);
        $this->router = new Router($this->request, $this->response);
    }


    public function run()
    {
        echo $this->router->resolve();
    }


    /**
     * Get the value of controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set the value of controller
     *
     * @return  self
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;

        // return $this;
    }
}
