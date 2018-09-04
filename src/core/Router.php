<?php

namespace core;


use Symfony\Component\HttpFoundation\Response;

class Router
{
    /**
     * Associative array of routes (the routing table)
     * @var array
     */
    protected $routes = [];
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $params = [];

    public function __construct()
    {
        $this->getRoutes();
    }

    /**
     * Get all the routes from the routing table
     *
     * @return array
     */
    public function getRoutes()
    {
        $this->routes = include __DIR__.'/../config/routes.php';
        return $this->routes;
    }
    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL
     *
     * @return boolean  true if a match found, false otherwise
     */
    public function match($url)
    {
        $routes = $this->routes;
        if(array_key_exists($url, $this->routes)) {
            $this->params = $routes[$url];
            return true;
        }
        return false;
    }
    /**
     * Get the currently matched parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
    /**
     * Dispatch the route, creating the controller object and running the
     * action method
     *
     * @param string $url The route URL
     *
     * @return Response|void
     */
    public function dispatch($url)
    {
        if ($this->match(trim($url, '/'))) {
            $controller = $this->params['controller'];
            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);
                $action = $this->params['action'];
                return $controller_object->$action();
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }
}