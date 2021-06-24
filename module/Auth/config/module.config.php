<?php

use Laminas\Router\Http\Literal;
use Auth\Controller\IndexController;
use Auth\Controller\Factory\IndexControllerFactory;

return[
    'router' => [
        'routes' => [
            'auth.login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'auth.logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'logout'
                    ]
                ]
            ],
        ]
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => [
            'auth/template_layout/layout_auth' => __DIR__.'/../view/template_auth/layout_auth.phtml',
            'auth/index/login'      => __DIR__.'/../view/auth/index/login.phtml',
            'auth/index/logout'      => __DIR__.'/../view/auth/index/logout.phtml',
            'dashboard' => __DIR__ . '/../../application/view/application/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__.'/../view',
        ],
    ],

];