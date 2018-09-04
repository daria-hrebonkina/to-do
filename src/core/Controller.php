<?php

namespace core;


use core\Components\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * @var Request $request
     */
    protected $request;

    public function __construct(array $route_params = [])
    {
        $this->route_params = $route_params;
        $this->request = Request::createFromGlobals();
    }
    /**
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void or @throws \Exception
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';
        if (!method_exists($this, $method)) {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
        if (is_bool($response = $this->beforeAction())  && $response !== false) {
            $response = call_user_func_array([$this, $method], $args);
            $this->afterAction();
            return $response;
        }
        return $response;
    }
    /**
     * @return bool|Response
     */
    protected function beforeAction()
    {
        if($this->route_params['allow'] == '@' && !Auth::getUser()) {
            return new Response('', 302, ['Location' => '/sign-in']);
        }
        return true;
    }
    /**
     * @return void
     */
    protected function afterAction()
    {
    }
}