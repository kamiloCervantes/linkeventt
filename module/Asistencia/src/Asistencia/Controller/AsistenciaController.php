<?php

namespace Asistencia\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Dashboard\Model\EmailsMasivos;
use Dashboard\Model\EmailsMasivosDetalle;
use Dashboard\Model\Pagos;
use Dashboard\Model\Archivos;
use Zend\Http\Client;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alpha;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;



class AsistenciaController extends AbstractActionController
{
    protected $usuariosTable;
    protected $personasTable;
    protected $pagosTable;
    protected $emailsmasivosTable;
    protected $emailsmasivosdetalleTable;
    protected $archivosTable;
    protected $inscripcioneseventosTable;

    public function indexAction()
    {
//        $this->setLayout('layout/asistencia');
        $view = new ViewModel();       
        $code = $this->params()->fromRoute('code');
        $evento = $this->params()->fromRoute('evento');
        $persona = $this->getPersonasTable()->getEventoPersonaCode($evento,$code);
        $view->setVariable('persona', $persona);
        
        return $view;
    }   
    
    public function confirmarasistenciaAction(){
        $view = new ViewModel();       
        $id = $this->params()->fromRoute('id');
        $this->getInscripcionesEventosTable()->confirmarAsistenciaEventos($id);     
        return $view;
    }
 
    public function getUsuariosTable()
     {
        if (!$this->usuariosTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->usuariosTable = $sm->get('Inscripciones\Model\UsuariosTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->usuariosTable;
     }
     
     public function getPersonasTable()
     {
        if (!$this->personasTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->personasTable = $sm->get('Inscripciones\Model\PersonasTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->personasTable;
     }
     public function getPagosTable()
     {
        if (!$this->pagosTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->pagosTable = $sm->get('Dashboard\Model\PagosTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->pagosTable;
     }
     public function getEmailsMasivosTable()
     {
        if (!$this->emailsmasivosTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->emailsmasivosTable = $sm->get('Dashboard\Model\EmailsMasivosTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->emailsmasivosTable;
     }
     public function getEmailsMasivosDetalleTable()
     {
        if (!$this->emailsmasivosdetalleTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->emailsmasivosdetalleTable = $sm->get('Dashboard\Model\EmailsMasivosDetalleTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->emailsmasivosdetalleTable;
     }
     public function getArchivosTable()
     {
        if (!$this->archivosTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->archivosTable = $sm->get('Dashboard\Model\ArchivosTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->archivosTable;
     }
     
     public function getInscripcionesEventosTable()
     {
        if (!$this->inscripcioneseventosTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->inscripcioneseventosTable = $sm->get('Inscripciones\Model\InscripcionesEventosTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->inscripcioneseventosTable;
     }

}

