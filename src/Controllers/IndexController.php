<?php

namespace Controllers;

use core\Components\Auth;
use core\Controller;
use core\View;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function signInAction()
    {
        $request = $this->request;
        if($request->isMethod('post')) {
            $user = Auth::signIn($request->get('login'), $request->get('password'));
            if(!$user) {
                return View::renderTemplate('Index/sign-in.html', ['errors' => Auth::getErrors()]);
            }

            return new Response('', 302, ['Location' => '/index']);
        }

        return View::renderTemplate('Index/sign-in.html');
    }

    public function signUpAction()
    {
        $request = $this->request;
        if($request->isMethod('post')) {
            $passwordHash = password_hash($request->get('password'), PASSWORD_DEFAULT);
            $user = Auth::signUp($request->get('login'), $passwordHash);
            if(!$user) {
                return View::renderTemplate('Index/sign-up.html', ['errors' => Auth::getErrors()]);
            }

            return new Response('', 302, ['Location' => '/project/index']);
        }

        return View::renderTemplate('Index/sign-up.html');
    }
}