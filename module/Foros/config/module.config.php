<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Foros\Controller\Foros' => 'Foros\Controller\ForosController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'foros' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/eventos[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Foros\Controller\Foros',
                         'action'     => 'index',
                     ),
                 ),
             ),
             'patrocinio' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/patrocinio',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'patrocinio',
                    ],
                ],
            ],
             'agenda' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/agenda',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'agenda',
                    ],
                ],
            ],
             'patrocinio_oro' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/patrocinio/oro',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'oro',
                    ],
                ],
            ],
             'patrocinio_plata' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/patrocinio/plata',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'plata',
                    ],
                ],
            ],
             'patrocinio_otros' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/patrocinio/otros',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'otros',
                    ],
                ],
            ],
             'patrocinio_diamante' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/patrocinio/diamante',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'diamante',
                    ],
                ],
            ],
             'concurso' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia/concurso',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'concurso',
                    ],
                ],
            ],
             'contacto' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/foroenergia/contacto[/:id]',
                    'constraints' => array(                         
                         'id'     => '[0-9]+',
                     ),
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'contacto',
                    ],
                ],
            ],
             'foroenergia' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/foroenergia',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'foroenergia',
                    ],
                ],
            ],
             'monetizatuemprendimiento' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/monetizatuemprendimiento',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'monetizatuemprendimiento',
                    ],
                ],
            ],
             'agendam' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/monetizatuemprendimiento/agenda',
                    'defaults' => [
                        'controller' => 'Foros\Controller\Foros',
                        'action'     => 'agendam',
                    ],
                ],
            ],
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'foros' => __DIR__ . '/../view',
         ),
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );