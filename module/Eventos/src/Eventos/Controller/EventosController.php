<?php

namespace Eventos\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alpha;
use Eventos\Model\Eventos;
use Eventos\Model\Personas;
use Eventos\Model\Errorlog;
use Inscripciones\Model\Usuarios;



class EventosController extends AbstractActionController
{
    protected $eventosTable;
    protected $personasTable;
    protected $errorlogTable;


    public function indexAction()
    {
        $view = new ViewModel();       
        $evento = $this->params()->fromRoute('evento');
//        var_dump($evento);
        
        return $view;
    }   
    
    public function createAction(){
        $view = new ViewModel();  
        if($this->request->isPost()){            
            try{                
                $evento = $this->guardarEvento(array('post' => $_POST));                
            } catch (\Exception $ex) {
                if(strrpos(';', $ex->getMessage()) === false){
                    $view->setVariable('error', $err);
                }
                else{
                    $err = explode(';', $ex->getMessage());                
                    $view->setVariable('error', $err[1]);
                    $view->setVariable('field', $err[0]);
                }                
            } 
        }
          
        return $view;
    }
    
    private function enviarEmail(){
        //enviar email
        $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail_3.html');
        $htmlMarkup = str_replace('%host_path%', 'http://linkeventt.com/assets/email', $htmlMarkup);
        $htmlMarkup = str_replace('%title%', 'Inscripción Exitosa', $htmlMarkup);
        $htmlMarkup = str_replace('%msg%', 'Gracias por registrarse al evento <strong>Capacitación "Vive de tus sueños, monetiza tu emprendimiento"</strong>. Ahora te proponemos dos opciones para finalizar tu inscripción: <br/>'
                . ' 1) Consigna $60.000 en la cuenta corriente No.091000610-11 de Bancolombia a nombre de FENALCO CÓRDOBA
        Nit: 891000610-6 y enviar copia del comprobante de pago a monetizatuemprendimiento@linkeventt.com.'
                , $htmlMarkup);
        $htmlMarkup = str_replace('%user%', '', $htmlMarkup);
        $htmlMarkup = str_replace('%passwd%', '', $htmlMarkup);
        $htmlMarkup = str_replace('%msg2%', '<br/>2) Contáctanos al teléfono 3006940366 y solicita un servicio a domicilio sin costo adicional para que puedas realizar el pago desde tu casa.'
                , $htmlMarkup);
        $htmlMarkup = str_replace('%url%', '', $htmlMarkup);
        $htmlMarkup = str_replace('%btn_label%', '', $htmlMarkup);

        $html = new MimePart($htmlMarkup);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));


        $mail = new Mail\Message();
        $mail->setBody($body);
        $mail->setFrom('noreply@linkeventt.com', 'Linkeventt');
        $mail->addTo($filter->getValue('email'));
        $mail->setSubject('Verificar inscripción');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }

    
    private function guardarEvento($data){       
        $adapter = $this->getEventosTable()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction(); 
        $response = array();
        $evento = new Input('evento');
        $evento->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $organizador = new Input('organizador');
        $organizador->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 255)))
            ->attach(new Validator\EmailAddress());           
        $telefono = new Input('telefono');
        $telefono->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\StringLength(array('max' => 255)));
        //input filter
        $input = new InputFilter();
        $input->add($evento)
              ->add($organizador)
              ->add($email)
              ->add($telefono)
              ->setData($data['post']);  
   
        if($input->isValid()){
            try{
                $pw = $this->generate_string(8);
                $this->getPersonasTable()->savePersonas(new Personas(array(
                         'nombre_completo' => $input->getValue('organizador'),
                         'telefono' => $input->getValue('telefono'),
                         'email' => $input->getValue('email'),              
                     )));

                $personas_id = $this->getPersonasTable()->getLastValue(); 
  
                $this->getUsuariosTable()->saveUsuarios(new Usuarios(array(
                         'username' => $input->getValue('email'),
                         'passwd' => password_hash($pw, PASSWORD_BCRYPT), 
                         'email' => $input->getValue('email'),              
                         'personas_id' => $personas_id,              
                         'rol' => 2,              
                     )));

                $usuarios_id = $this->getUsuariosTable()->getLastValue(); 
                $alias = str_replace(' ', '_', strtolower($input->getValue('evento')));
                $this->getEventosTable()->saveEventos(new Eventos(array(
                         'nombre_evento' => $input->getValue('evento'),
                         'organizador' => $usuarios_id,
                         'alias' => $alias,                
                     )));
                
                $response = array(
                    'username' => $input->getValue('email'),
                    'email' => $input->getValue('email'),
                    'nombre_evento' => $input->getValue('evento'),
                    'organizador' => $input->getValue('organizador'),
                    'alias' => $alias,
                    'pw' => $pw
                );

                $connection->commit();
            } catch (\Exception $ex) {
                 try{
                     $this->getErrorlogTable()->saveErrorlog(new Errorlog(array(
                         'msg' => $ex->getMessage().' '.$input->getValue('organizador').';'.$input->getValue('telefono').';'.$input->getValue('email').';'.$input->getValue('evento'))               
                     ));
                     $errorlog_id = $this->getErrorlogTable()->getLastValue();
                     $connection->commit();
                 } catch (Exception $ex) {
                    throw new \Exception("Error al guardar en la base de datos.");
                 }
                 
                throw new \Exception("Error al guardar en la base de datos. Código de error #".$errorlog_id);
            }
            
            return $response;
        }
        else{
            $error_msgs = array();
            foreach($input->getMessages() as $key => $msg){
                foreach($msg as $m){
                    $error_msgs[] = $key.';'.$m;
                }                
            }
            throw new \Exception($error_msgs[0]);     
        }
    }
    
    
 
    private function generate_string($strength = 16) {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';        
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
 
    
    public function getEventosTable()
    {
        if (!$this->eventosTable) {
            echo "holaa";
            try{
                $sm = $this->getServiceLocator();
                $this->eventosTable = $sm->get('Eventos\Model\EventosTable');
                
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->eventosTable;
     }
    
    public function getPersonasTable()
    {
        if (!$this->personasTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->personasTable = $sm->get('Eventos\Model\PersonasTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->personasTable;
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
     
         public function getErrorlogTable()
     {
        if (!$this->errorlogTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->errorlogTable = $sm->get('Inscripciones\Model\ErrorlogTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->errorlogTable;
     }
    
    
}

