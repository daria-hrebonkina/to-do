<?php

namespace core;


use core\Components\Auth;
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
            $twig = new \Twig_Environment($loader, ['debug' => true]);
            $twig->addExtension(new \Twig_Extension_Debug());
            $twig->addGlobal('userIsGuest', Auth::userIsGuest());
            $twig->addGlobal('userId', Auth::getUser()->id ?? null);
        }
        return new Response($twig->render($template, $args));
    }
}