<?php

namespace Foros\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alpha;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class ForosController extends AbstractActionController
{
    protected $tiposdocumentoTable;

    public function indexAction()
    {
        return new ViewModel();
    }
    
    
    
    public function foroenergiaAction()
    {
        $result = new ViewModel();
        $result->setTerminal(true);
        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
        $tiposdocumento_arr = array();
        foreach($tiposdocumento as $d){
            $tiposdocumento_arr[] = $d;
        }

        $result->setVariable('tiposdocumento', $tiposdocumento_arr);
        return $result;
    }
    
    
    public function monetizatuemprendimientoAction()
    {
        $result = new ViewModel();
        $result->setTerminal(true);
        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
        $tiposdocumento_arr = array();
        foreach($tiposdocumento as $d){
            $tiposdocumento_arr[] = $d;
        }

        $result->setVariable('tiposdocumento', $tiposdocumento_arr);
        return $result;
    }
    
    public function agendaAction(){ 
        
    }
    
    
    public function agendamAction(){ 
        
    }
    
    public function patrocinioAction(){
        
    }
    
    public function oroAction(){
        
    }
    
    public function plataAction(){
        
    }
    
    public function diamanteAction(){
        
    }
    
    public function otrosAction(){
        
    }
    
    public function concursoAction(){
        
    }
    
    public function enviarmensajeAction(){
        $view = new ViewModel();
        $view->setTerminal(true);
        var_dump($this->getRequest()->isPost());
        var_dump($_POST);
        if($this->getRequest()->isPost()){
            echo "hola";
            $nombre = new Input('nombres');
            $email = new Input('email');       
            $msg = new Input('mensaje');      
            $asunto = new Input('asunto');      

            $filter = new InputFilter();
            $filter->add($nombre)
            ->add($email)
            ->add($asunto)
            ->add($msg)      
            ->setData($_POST);

            if($filter->isValid()){
                $htmlMarkup = 'Nuevo contacto en 4to Foro Internacional de Energías Renovables. <br/> Nombre: '.$filter->getValue('nombres').'<br/> Email: '.$filter->getValue('email').'<br/>Asunto: '.$filter->getValue('asunto').'<br/><br/>'.$filter->getValue('mensaje');

                $html = new MimePart($htmlMarkup);
                $html->type = "text/html";

                $body = new MimeMessage();
                $body->setParts(array($html));


                $mail = new Mail\Message();
                $mail->setBody($body);
                $mail->setFrom($filter->getValue('email'), $filter->getValue('nombres'));
                $mail->addTo('genmotionmakers@gmail.com', 'Genmotion Makers');
                $mail->setSubject('Nuevo contacto en Linkeventt');

                $transport = new Mail\Transport\Sendmail();
                $transport->send($mail);

                return $this->redirect()->toRoute('contacto', array(
                    
                    'id' => 1
                ));
            }
            else{
                return $this->redirect()->toRoute('contacto', array(   
                    
                    'id' => 0
                ));
            }
        }
        else{
            //redirigir a contacto
            return $this->redirect()->toRoute('contacto', array(
                    
                ));
        }
        return $view;
    }
    
    public function contactoAction(){
        $view = new ViewModel();
        $view->setVariable('default', 1);
        $data = $this->params()->fromRoute('id');
        if($data && $data >= 0){
            $view->setVariable('default', 0);
            if($data == 0){
                $view->setVariable('fail', 1);
            }
            if($data == 1){
                $view->setVariable('success', 1);
            }
        }
        return $view;
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

