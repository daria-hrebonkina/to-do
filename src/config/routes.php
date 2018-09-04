<?php
return array(
    'index' => [
        'controller' => \Controllers\IndexController::class,
        'action' => 'index',
        'allow' => '@'
    ],
    'sign-in' => [
        'controller' => \Controllers\IndexController::class,
        'action' => 'signIn',
        'allow' => '*'
    ],
    'sign-up' => [
        'controller' => \Controllers\IndexController::class,
        'action' => 'signUp',
        'allow' => '*'
    ],
);