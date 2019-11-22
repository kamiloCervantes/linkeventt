<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Eventos\Controller\Eventos' => 'Eventos\Controller\EventosController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'evento' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/eventos[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Eventos\Controller\Eventos',
                         'action'     => 'index',
                     ),
                 ),
             ),             
             'evento_ap' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/evento[/:evento]',
                     'constraints' => array(
                         'evento' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Eventos\Controller\Eventos',
                         'action'     => 'index',
                     ),
                 ),
             ),             
                       
           
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'evento' => __DIR__ . '/../view/',
         ),
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );