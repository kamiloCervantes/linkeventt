<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Login\Controller\Login' => 'Login\Controller\LoginController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'login' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/login[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Login\Controller\Login',
                         'action'     => 'index',
                     ),
                 ),
             ),
             'logout' => array(
                 'type'    => 'literal',
                 'options' => array(
                     'route'    => '/logout',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Login\Controller\Login',
                         'action'     => 'logout',
                     ),
                 ),
             ),
             'auth_login' => array(
                 'type'    => 'literal',
                 'options' => array(
                     'route'    => '/auth/login',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Login\Controller\Login',
                         'action'     => 'index',
                     ),
                 ),
             ),
             'welcome_login' => array(
                 'type'    => 'literal',
                 'options' => array(
                     'route'    => '/welcome',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Login\Controller\Login',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'login' => __DIR__ . '/../view/',
         ),
       
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );