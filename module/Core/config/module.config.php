<?php

use Core\Factories\TransportSmtpFactory;
use Laminas\Form\View\Helper\FormElementErrors;
use Core\Factories\FormElementErrorsFactory;
use Laminas\Session\Storage\SessionArrayStorage;

return [
    'service_manager' => [
        'factories' => [
            'core.transport.smtp' => TransportSmtpFactory::class
        ]
    ],
    // Session configuration.
    'session_config' => [
        // Session name
        'name' => 'session_voliveiras',
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60*60*1,
        //'cookie_lifetime' => 10,
        // Session 2
        'remember_me_seconds' => 60*60,
        // Session data will be stored on server maximum for 30 days.
        //'gc_maxlifetime'     => 60*60*24*30,
        'gc_maxlifetime'     => ini_get('session.gc_maxlifetime'),
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            \Laminas\Session\Validator\RemoteAddr::class,
            \Laminas\Session\Validator\HttpUserAgent::class,
        ],
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],

    'view_helpers' => [
        'factories' => [
            FormElementErrors::class => FormElementErrorsFactory::class
        ]
    ],
    'view_helper_config' => [
        'form_element_errors' => [
            'message_open_format' => '<ul class="list-unstyled"><li class="mdi-block-helper">',
            'message_separator_string' => '</li><li class="mdi-block-helper">',
            'message_close_string' => '</li></ul>'
        ],
        'flashmessenger' => [
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>',
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
    ],
];