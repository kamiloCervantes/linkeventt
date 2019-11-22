<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Dashboard\Controller\Dashboard' => 'Dashboard\Controller\DashboardController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'dashboard' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/dashboard[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Dashboard\Controller\Dashboard',
                         'action'     => 'index',
                     ),
                 ),
             ),
             'entradas' => array(
                 'type'    => 'literal',
                 'options' => array(
                     'route'    => '/entradas',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Dashboard\Controller\Dashboard',
                         'action'     => 'entradas',
                     ),
                 ),
             ),
             'ingresos' => array(
                 'type'    => 'literal',
                 'options' => array(
                     'route'    => '/ingresos',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Dashboard\Controller\Dashboard',
                         'action'     => 'ingresos',
                     ),
                 ),
             ),
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'dashboard' => __DIR__ . '/../view/',
         ),
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );