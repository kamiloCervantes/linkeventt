<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    
    protected $tiposdocumentoTable;
    
    public function indexAction()
    {
        $evento = $this->params()->fromRoute('evento');
        switch($evento){
            case 'foroenergia':
                $this->redirect()->toRoute(
                    'foroenergia'                          
                );
                break;
            case '':
               
                break;
            default:
                $this->redirect()->toRoute(
                    'evento_ap', array(
                        'action' => 'index',
                        'evento' => $evento
                    )                          
                );
                break;
        }
        
//        $result = new ViewModel();
//        $result->setTerminal(true);
//        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
//        $tiposdocumento_arr = array();
//        foreach($tiposdocumento as $d){
//            $tiposdocumento_arr[] = $d;
//        }
//
//        $result->setVariable('tiposdocumento', $tiposdocumento_arr);
//        return $result;
    }
    
    
    public function getTiposDocumentoTable()
     {
        if (!$this->tiposdocumentoTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->tiposdocumentoTable = $sm->get('Inscripciones\Model\TiposDocumentoTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->tiposdocumentoTable;
     }
}
