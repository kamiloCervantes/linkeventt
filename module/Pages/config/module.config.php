<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Pages\Controller\Pages' => 'Pages\Controller\PagesController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'pages' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/pages[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Pages\Controller\Pages',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'pages' => __DIR__ . '/../view',
         ),
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );