<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Asistencia\Controller\Asistencia' => 'Asistencia\Controller\AsistenciaController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'asistencia' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/asistencia[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Asistencia\Controller\Asistencia',
                         'action'     => 'index',
                     ),
                 ),
             ),             
             'registrar_asistencia' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/registrarasistencia[/:evento][/:code]',
                     'constraints' => array(
                         'code' => '[a-zA-Z0-9_-]*',
                         'evento'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Asistencia\Controller\Asistencia',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'asistencia' => __DIR__ . '/../view/',
         ),
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );