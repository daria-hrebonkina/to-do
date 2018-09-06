<?php
return [
    '' => [
        'controller' => \Controllers\TaskController::class,
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
    'logout' => [
        'controller' => \Controllers\IndexController::class,
        'action' => 'logout',
        'allow' => '@'
    ],
    'tasks' => [
        'controller' => \Controllers\TaskController::class,
        'action' => 'index',
        'allow' => '@'
    ],
    'create-project' => [
        'controller' => \Controllers\ProjectController::class,
        'action' => 'create',
        'allow' => '@'
    ],
    'create-task' => [
        'controller' => \Controllers\TaskController::class,
        'action' => 'create',
        'allow' => '@'
    ],
    'update-task' => [
        'controller' => \Controllers\TaskController::class,
        'action' => 'update',
        'allow' => '@'
    ],
    'update-project' => [
        'controller' => \Controllers\ProjectController::class,
        'action' => 'update',
        'allow' => '@'
    ],
    'delete-task' => [
        'controller' => \Controllers\TaskController::class,
        'action' => 'delete',
        'allow' => '@'
    ],
    'delete-project' => [
        'controller' => \Controllers\ProjectController::class,
        'action' => 'delete',
        'allow' => '@'
    ],
    'archive' => [
        'controller' => \Controllers\TaskController::class,
        'action' => 'archive',
        'allow' => '@'
    ]
];