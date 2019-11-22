<?php
namespace Inscripciones;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Inscripciones\Model\TiposDocumento;
use Inscripciones\Model\TiposDocumentoTable;
use Inscripciones\Model\Personas;
use Inscripciones\Model\PersonasTable;
use Inscripciones\Model\InscripcionesEventos;
use Inscripciones\Model\InscripcionesEventosTable;
use Inscripciones\Model\Usuarios;
use Inscripciones\Model\UsuariosTable;
use Inscripciones\Model\Eventos;
use Inscripciones\Model\EventosTable;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Inscripciones\Model\TiposDocumentoTable' =>  function($sm) {
                     $tableGateway = $sm->get('TiposDocumentoTableGateway');
                     $table = new TiposDocumentoTable($tableGateway);
                     return $table;
                 },
                 'TiposDocumentoTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new TiposDocumento());
                     return new TableGateway('tipos_documento', $dbAdapter, null, $resultSetPrototype);
                 },                 
                 'Inscripciones\Model\PersonasTable' =>  function($sm) {
                     $tableGateway = $sm->get('PersonasTableGateway');
                     $table = new PersonasTable($tableGateway);
                     return $table;
                 },
                 'PersonasTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Personas());
                     return new TableGateway('personas', $dbAdapter, null, $resultSetPrototype);
                 },                 
                 'Inscripciones\Model\InscripcionesEventosTable' =>  function($sm) {
                     $tableGateway = $sm->get('InscripcionesEventosTableGateway');
                     $table = new InscripcionesEventosTable($tableGateway);
                     return $table;
                 },
                 'InscripcionesEventosTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new InscripcionesEventos());
                     return new TableGateway('inscripciones_eventos', $dbAdapter, null, $resultSetPrototype);
                 },                 
                 'Inscripciones\Model\UsuariosTable' =>  function($sm) {
                     $tableGateway = $sm->get('UsuariosTableGateway');
                     $table = new UsuariosTable($tableGateway);
                     return $table;
                 },
                 'UsuariosTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Usuarios());
                     return new TableGateway('usuarios', $dbAdapter, null, $resultSetPrototype);
                 },                 
                 'Inscripciones\Model\EventosTable' =>  function($sm) {
                     $tableGateway = $sm->get('EventosTableGateway');
                     $table = new EventosTable($tableGateway);
                     return $table;
                 },
                 'EventosTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Eventos());
                     return new TableGateway('eventos', $dbAdapter, null, $resultSetPrototype);
                 },                 
                              
             ),
         );
     }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
