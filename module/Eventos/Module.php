<?php
namespace Eventos;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Eventos\Model\Errorlog;
use Eventos\Model\ErrorlogTable;
use Eventos\Model\Eventos;
use Eventos\Model\EventosTable;
use Eventos\Model\Personas;
use Eventos\Model\PersonasTable;
use Inscripciones\Model\Usuarios;
use Inscripciones\Model\UsuariosTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;



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
                 'Eventos\Model\EventosTable' =>  function($sm) {
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
                 'Eventos\Model\ErrorlogTable' =>  function($sm) {
                     $tableGateway = $sm->get('ErrorlogTableGateway');
                     $table = new ErrorlogTable($tableGateway);
                     return $table;
                 },
                 'ErrorlogTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Errorlog());
                     return new TableGateway('error_log', $dbAdapter, null, $resultSetPrototype);
                 },                                           
                 'Eventos\Model\PersonasTable' =>  function($sm) {
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
    
    public function init(ModuleManager $manager)
    {
        // Get event manager.
        $eventManager = $manager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method. 
        $sharedEventManager->attach(__NAMESPACE__, 'dispatch', 
                                    [$this, 'onDispatch'], 100);
    }

    // Event listener method.
    public function onDispatch(MvcEvent $event)
    {        
        // Get controller to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        // Get fully qualified class name of the controller.
        $controllerClass = get_class($controller);
        // Get module name of the controller.
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        // Switch layout only for controllers belonging to our module.
        if ($moduleNamespace == __NAMESPACE__) {
//            $viewModel = $event->getViewModel();
//            $viewModel->setTemplate('layout/asistencia');
        }        
    }
}
