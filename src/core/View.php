<?php

namespace core;


use Symfony\Component\HttpFoundation\Response;

class View
{
    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return Response
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;
        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem( dirname(__DIR__) . '/Views');
            $twig = new \Twig_Environment($loader);
        }
        return new Response($twig->render($template, $args));
    }
}