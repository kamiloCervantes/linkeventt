<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Inscripciones\Controller\Inscripciones' => 'Inscripciones\Controller\InscripcionesController',
         ),
     ),
     
      // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'inscripciones' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/inscripciones[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Inscripciones\Controller\Inscripciones',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     
     'view_manager' => array(
         'template_path_stack' => array(
             'inscripciones' => __DIR__ . '/../view',
         ),
          'strategies' => array(
            'ViewJsonStrategy',
        ),
     ),
 );