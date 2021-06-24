<?php

namespace User;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use User\Controller\Factory\IndexControllerFactory;
use User\Model\Factory\SessionTableFactory;
use User\Model\Factory\UserTableFactory;
use User\Model\SessionTable;
use User\Model\UserTable;

return [

    'router' => [
        'routes' => [
            'user' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/user',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'register'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '[/:action][/token/:token]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'token' => '[a-f0-9]{32}$'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class
        ]
    ],
    'service_manager' => [
        'factories' => [
            UserTable::class=>UserTableFactory::class,
            SessionTable::class => SessionTableFactory::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => [
            'user/template_layout/layout_auth' => __DIR__.'/../view/template_layout/layout_auth.phtml',
            'user/index/new-password'  => __DIR__.'/../view/user/index/new-password.phtml',
            'user/index/confirmed-email' => __DIR__.'/../view/user/index/confirmed-email.phtml',
            'user/index/recovered-password' => __DIR__.'/../view/user/index/recovered-password.phtml',
            'user/index/index'      => __DIR__.'/../view/user/index/index.phtml',
            'user/index/register'      => __DIR__.'/../view/user/index/register.phtml',
        ],
        'template_path_stack' => [
            __DIR__.'/../view',
        ],
    ],
];