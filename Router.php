<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc;


use lcgwhat\phpmvc\exception\NotFoundException;

class Router
{
    /**
     * @var Response $response
     */
    public $response;

    /**
     * @var Request $request
     */
    public  $request;

    /**
     * @var array
     * [
     *  'get' => [
     *          '/' => $callback,
     *          'contract' => $callback
     *  ],
     *  'post' => ['/' => 'callback']
     * ];
     */
    protected $routes = [];
    /**
     * Route constructor.
     * @param $request Request
     * @param $response Response
     */
    public function __construct( $request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }



    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post( $path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            Application::$app->response->setCode(404);
            throw new NotFoundException();

        }

        if ($callback instanceof \Closure || is_array($callback)) {
            if (is_array($callback)) {
                Application::$app->controller = new $callback[0]();
                Application::$app->controller->action = $callback[1];
                $controller  = Application::$app->controller;
                foreach ($controller->getMiddlewares() as $middleware) {
                    $middleware->execute();
                }
                $callback[0] = $controller;
            }
            return @call_user_func($callback, $this->request, $this->response);
        }

        return Application::$app->view->renderView($callback);
    }

}
