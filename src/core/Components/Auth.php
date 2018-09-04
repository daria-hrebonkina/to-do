<?php

namespace core\Components;


use Models\User;

class Auth
{
    private static $errors;

    public static function getUser()
    {
        if(!empty($_SESSION['user']) && $_SESSION['user']->id) {
            return User::findOne((int) $_SESSION['user']->id);
        }

        return null;
    }

    public static function signIn($login, $password)
    {
        $user = User::findOne(['login' => $login]);
        if($user) {
            if(password_verify($password, $user->password)) {
                $_SESSION['user'] = $user;
                return true;
            }
            static::addError('Bad password');
            return false;
        }
        static::addError('Bad login');
        return false;
    }

    public static function getErrors()
    {
        return static::$errors;
    }

    private static function addError($error)
    {
        static::$errors[] = $error;
    }

    public static function signUp($login, $password)
    {
        $nonUnique = User::findOne(compact('login'));
        if($nonUnique) {
            static::addError('This email is already in use.');
            return false;
        }
        $user = User::create(compact('login', 'password'));
        if(!$user) {
            return false;
        }

        $_SESSION['user'] = $user;
        return $user;
    }
}