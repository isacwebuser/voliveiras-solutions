<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);


namespace Application;

use Application\Controller\Factory\TicketControllerFactory;
use Application\Model\AttachmentTable;
use Application\Model\Factory\AttachmentTableFactory;
use Application\Model\Factory\TicketTableFactory;
use Application\Model\TicketTable;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'dashboard' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'ticket' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/ticket[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\TicketController::class,
                        'action'     => 'searchTicket',
                    ],
                    'constraints' => [
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '\d+',
                    ]
                ],
            ]
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\DashboardController::class => InvokableFactory::class,
            Controller\TicketController::class => TicketControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            TicketTable::class => TicketTableFactory::class,
            AttachmentTable::class => AttachmentTableFactory::class,
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',

            'application/ticket/create-ticket' => __DIR__ . '/../view/application/ticket/create-ticket.phtml',
            'application/ticket/edit-ticket' => __DIR__ . '/../view/application/ticket/edit-ticket.phtml',
            //Partials
            'application/ticket/form' => __DIR__ . '/../view/application/ticket/form.phtml',
            // Pagination
            'application/ticket/paginator' => __DIR__ . '/../view/application/ticket/paginator.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
