<?php
namespace Dashboard;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Dashboard\Model\Pagos;
use Dashboard\Model\PagosTable;
use Dashboard\Model\EmailsMasivos;
use Dashboard\Model\EmailsMasivosTable;
use Dashboard\Model\EmailsMasivosDetalle;
use Dashboard\Model\EmailsMasivosDetalleTable;
use Dashboard\Model\Archivos;
use Dashboard\Model\ArchivosTable;

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
                 'Dashboard\Model\PagosTable' =>  function($sm) {
                     $tableGateway = $sm->get('PagosTableGateway');
                     $table = new PagosTable($tableGateway);
                     return $table;
                 },
                 'PagosTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Pagos());
                     return new TableGateway('pagos', $dbAdapter, null, $resultSetPrototype);
                 },                                            
                 'Dashboard\Model\EmailsMasivosTable' =>  function($sm) {
                     $tableGateway = $sm->get('EmailsMasivosTableGateway');
                     $table = new EmailsMasivosTable($tableGateway);
                     return $table;
                 },
                 'EmailsMasivosTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new EmailsMasivos());
                     return new TableGateway('emails_masivos', $dbAdapter, null, $resultSetPrototype);
                 },                                            
                 'Dashboard\Model\EmailsMasivosDetalleTable' =>  function($sm) {
                     $tableGateway = $sm->get('EmailsMasivosDetalleTableGateway');
                     $table = new EmailsMasivosDetalleTable($tableGateway);
                     return $table;
                 },
                 'EmailsMasivosDetalleTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new EmailsMasivosDetalle());
                     return new TableGateway('email_masivos_usuarios', $dbAdapter, null, $resultSetPrototype);
                 },                                            
                 'Dashboard\Model\ArchivosTable' =>  function($sm) {
                     $tableGateway = $sm->get('ArchivosTableGateway');
                     $table = new ArchivosTable($tableGateway);
                     return $table;
                 },
                 'ArchivosTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Archivos());
                     return new TableGateway('archivos', $dbAdapter, null, $resultSetPrototype);
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
        session_start();
        $rol = $_SESSION['usersession.rol'];
        
        // Get controller to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        // Get fully qualified class name of the controller.
        $controllerClass = get_class($controller);
        // Get module name of the controller.
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        // Switch layout only for controllers belonging to our module.
        if ($moduleNamespace == __NAMESPACE__) {
            $viewModel = $event->getViewModel();
            if($rol == 1){
                $viewModel->setTemplate('layout/dashboard3');  
            }
            else{
                if($rol == 2){
                    $viewModel->setTemplate('layout/dashboard2');  
                }     
                else{
                    $controller->redirect()->toUrl('login');
                }
            }
            
        }        
    }
}
