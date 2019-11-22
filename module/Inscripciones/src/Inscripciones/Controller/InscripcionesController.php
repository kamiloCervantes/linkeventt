<?php

namespace Inscripciones\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Inscripciones\Model\Personas;
use Inscripciones\Model\InscripcionesEventos;
use Inscripciones\Model\Usuarios;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alpha;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;




class InscripcionesController extends AbstractActionController
{
    protected $tiposdocumentoTable;
    protected $personasTable;
    protected $usuariosTable;
    protected $inscripcioneseventosTable;
    protected $eventosTable;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function presentacionesAction()
    {
        return new ViewModel();
    }

    public function fichatecnicaAction()
    {
        return new ViewModel();
    }
    
    public function tipodocumentosAction(){    
        $data = $this->getTiposDocumentoTable()->fetchAll();
        $data_arr = array();
        foreach($data as $d){
            $data_arr[] = $d;
        }          
            
        return new JsonModel($data_arr);
    }
    
    public function inscripcionescerradasAction(){
        $view = new ViewModel();
        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
        $tiposdocumento_arr = array();
        foreach($tiposdocumento as $d){
            $tiposdocumento_arr[] = $d;
        }
        $view->setVariable('tiposdocumento', $tiposdocumento_arr);
        return $view;
    }
    
    public function registroAction(){  
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
        $formdata = $_POST;
        $view = new ViewModel();
        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
        $tiposdocumento_arr = array();
        foreach($tiposdocumento as $d){
            $tiposdocumento_arr[] = $d;
        }
        $view->setVariable('tiposdocumento', $tiposdocumento_arr);
        if($this->getRequest()->isPost()){
            
        
        $adapter = $this->getPersonasTable()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction(); 
        //nombres,apellidos,tipodoc,nrodoc,genero,telefono,ocupacion,empresa,cargo,email,email2,aceptaterminos
        $nombres = new Input('nombres');
        $nombres->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $apellidos = new Input('apellidos');
        $apellidos->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $tipodoc = new Input('tipodoc');
        $tipodoc->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'tipos_documento',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $nrodoc = new Input('nrodoc');
        $nrodoc->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\Db\NoRecordExists(array(
                        'table' => 'personas',
                        'field' => 'nrodoc',
                        'adapter' => $adapter
                    )));
        $genero = new Input('genero');
        $genero->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha())
               ->attach(new Validator\StringLength(array('max' => 1)));
        $telefono = new Input('telefono');
        $telefono->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\StringLength(array('max' => 45)));
        $ocupacion = new Input('ocupacion');
        $ocupacion->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $empresa = new Input('empresa');
        $empresa->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $cargo = new Input('cargo');
        $cargo->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 255)))
//            ->attach(new Validator\EmailAddress())
            ->attach(new Validator\Db\NoRecordExists(array(
                        'table' => 'personas',
                        'field' => 'email',
                        'adapter' => $adapter
                    )));
                
        $email2 = new Input('email2');
        $email2->getValidatorChain()
            ->attach(new Validator\NotEmpty())
//            ->attach(new Validator\Identical($email))
            ->attach(new Validator\StringLength(array('max' => 255)));
//            ->attach(new Validator\EmailAddress());
       
        
        $aceptaterminos = new Input('aceptaterminos');
        $aceptaterminos->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 2)));
        $evento_id = new Input('evento_id');
        $evento_id->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits())
            ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'eventos',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $xhr = new Input('xhr');
        $xhr->allowEmpty(true);
        $xhr->setRequired(false);
        
              
        
        $filter = new InputFilter();
        $filter->add($nombres) 
        ->add($apellidos) 
        ->add($tipodoc) 
        ->add($nrodoc)
        ->add($genero)
        ->add($telefono)
        ->add($ocupacion)
        ->add($empresa)
        ->add($cargo)
        ->add($email)
        ->add($email2)
        ->add($aceptaterminos)
        ->add($evento_id)
        ->add($xhr)
        ->setData($_POST);       
       
       if ($filter->isValid()) {
           try{
                if($filter->getValue('email') !== $filter->getValue('email2')){
                    throw new \Exception("Los emails no coinciden");
                }
                
                               
               

                $this->getPersonasTable()->savePersonas(new Personas(array(
                     'nombres' => $filter->getValue('nombres'),
                     'apellidos' => $filter->getValue('apellidos'),
                     'tipos_documento_id' => $filter->getValue('tipodoc'),
                     'nrodoc' => $filter->getValue('nrodoc'),
                     'genero' => $filter->getValue('genero'),
                     'telefono' => $filter->getValue('telefono'),
                     'ocupacion' => $filter->getValue('ocupacion'),
                     'empresa' => $filter->getValue('empresa'),
                     'cargo' => $filter->getValue('cargo'),
                     'email' => $filter->getValue('email'),
                     'aceptaterminos' => $filter->getValue('aceptaterminos'),                
                 )));                
                     
                $personas_id = $this->getPersonasTable()->getLastValue();                
            
                
                $this->getInscripcionesEventosTable()->saveInscripcionesEventos(new InscripcionesEventos(array(
                     'personas_id' => $personas_id,
                     'eventos_id' => $filter->getValue('evento_id'),                                   
                 )));     
                
                $inscripciones_id = $this->getInscripcionesEventosTable()->getLastValue(); 
      
                
                $this->getUsuariosTable()->saveUsuarios(new Usuarios(array(
                     'username' => $filter->getValue('email'),                                   
                     'passwd' => password_hash($filter->getValue('nrodoc'), PASSWORD_BCRYPT),                                   
                     'activo' => 0,                                   
                     'personas_id' => $personas_id,
                 )));
          
                $connection->commit();
                
                //enviar email
                $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail.html');
                $htmlMarkup = str_replace('%host_path%', 'http://eventos.genmotionmakers.com/assets/email', $htmlMarkup);
                $htmlMarkup = str_replace('%title%', 'Inscripción Exitosa', $htmlMarkup);
                $htmlMarkup = str_replace('%msg%', 'Gracias por registrarse al evento <strong>4to Foro Internacional de Energías Renovables</strong>. Haga click en <strong>verificar</strong> para activar su inscripción.', $htmlMarkup);
                $htmlMarkup = str_replace('%user%', $filter->getValue('email'), $htmlMarkup);
                $htmlMarkup = str_replace('%passwd%', $filter->getValue('nrodoc'), $htmlMarkup);
                $htmlMarkup = str_replace('%url%', 'http://eventos.genmotionmakers.com/inscripciones/verificar/'.$inscripciones_id, $htmlMarkup);
                $htmlMarkup = str_replace('%btn_label%', 'VERIFICAR', $htmlMarkup);
            
                $html = new MimePart($htmlMarkup);
                $html->type = "text/html";

                $body = new MimeMessage();
                $body->setParts(array($html));


                $mail = new Mail\Message();
                $mail->setBody($body);
                $mail->setFrom('foroenergia@linkeventt.com', 'Foro Energía Linkeventt');
                $mail->addTo($filter->getValue('email'));
                $mail->setSubject('Verificar inscripción');

                $transport = new Mail\Transport\Sendmail();
                $transport->send($mail);
                
                if($filter->getValue('xhr') > 0){
                    //return jsonmodel
                    return new JsonModel(array());
                }
                else{
                    $view->setVariable('success', 1);
                    return $view;
                }
                
           } catch (\Exception $ex) {               
               if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
                    $connection->rollback();
                }
                if($filter->getValue('xhr') > 0){
                    //return jsonmodel
                    return new JsonModel(array());
                }
               else{
                    $view->setVariable('fail', 1);
                    $view->setVariable('formdata', $formdata);
                    $view->setVariable('error_messages', $ex->getMessage());
//                    var_dump($ex->getMessage());
                    return $view;
               }    
           }          
           
           
        } else {
            if(isset($_POST['xhr']) && $_POST['xhr']  > 0){
                //return jsonmodel
                return new JsonModel(array());
            }
           else{
               $view->setVariable('fail', 1);
               $view->setVariable('formdata', $formdata);
               $view->setVariable('error_messages', $filter->getMessages());
//               var_dump($filter->getMessages());
               return $view;
           }
                
        }
        }
        else{
            return $view;
        }
    }
    
    public function foroenergiaAction(){  
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
        $cerrarinscripciones = false;
        if($cerrarinscripciones){
            return $this->redirect()->toRoute('inscripciones', array(                  
                'action' => 'inscripcionescerradas'
            ));
        }
        
        
        $formdata = $_POST;
        $view = new ViewModel();
        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
        $tiposdocumento_arr = array();
        foreach($tiposdocumento as $d){
            $tiposdocumento_arr[] = $d;
        }
        $view->setVariable('tiposdocumento', $tiposdocumento_arr);
        if($this->getRequest()->isPost()){
            
        
        $adapter = $this->getPersonasTable()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction(); 
        //nombres,apellidos,tipodoc,nrodoc,genero,telefono,ocupacion,empresa,cargo,email,email2,aceptaterminos
        $nombres = new Input('nombres');
        $nombres->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $apellidos = new Input('apellidos');
        $apellidos->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $tipodoc = new Input('tipodoc');
        $tipodoc->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'tipos_documento',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $nrodoc = new Input('nrodoc');
        $nrodoc->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\Db\NoRecordExists(array(
                        'table' => 'personas',
                        'field' => 'nrodoc',
                        'adapter' => $adapter
                    )));
        $genero = new Input('genero');
        $genero->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha())
               ->attach(new Validator\StringLength(array('max' => 1)));
        $telefono = new Input('telefono');
        $telefono->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\StringLength(array('max' => 45)));
        $ocupacion = new Input('ocupacion');
        $ocupacion->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $ciudad = new Input('ciudad');
        $ciudad->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $empresa = new Input('empresa');
        $empresa->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $cargo = new Input('cargo');
        $cargo->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 255)))
//            ->attach(new Validator\EmailAddress())
            ->attach(new Validator\Db\NoRecordExists(array(
                        'table' => 'personas',
                        'field' => 'email',
                        'adapter' => $adapter
                    )));
                
        $email2 = new Input('email2');
        $email2->getValidatorChain()
            ->attach(new Validator\NotEmpty())
//            ->attach(new Validator\Identical($email))
            ->attach(new Validator\StringLength(array('max' => 255)));
//            ->attach(new Validator\EmailAddress());
       
        
        $aceptaterminos = new Input('aceptaterminos');
        $aceptaterminos->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 2)));
        $evento_id = new Input('evento_id');
        $evento_id->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits())
            ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'eventos',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $xhr = new Input('xhr');
        $xhr->allowEmpty(true);
        $xhr->setRequired(false);        
              
        
        $filter = new InputFilter();
        $filter->add($nombres) 
        ->add($apellidos) 
        ->add($tipodoc) 
        ->add($nrodoc)
        ->add($genero)
        ->add($telefono)
        ->add($ocupacion)
        ->add($ciudad)
        ->add($empresa)
        ->add($cargo)
        ->add($email)
        ->add($email2)
        ->add($aceptaterminos)
        ->add($evento_id)
        ->add($xhr)
        ->setData($_POST);       
       
       if ($filter->isValid()) {
           try{
                if($filter->getValue('email') !== $filter->getValue('email2')){
                    throw new \Exception("Los emails no coinciden");
                }            

                $this->getPersonasTable()->savePersonas(new Personas(array(
                     'nombres' => $filter->getValue('nombres'),
                     'apellidos' => $filter->getValue('apellidos'),
                     'tipos_documento_id' => $filter->getValue('tipodoc'),
                     'nrodoc' => $filter->getValue('nrodoc'),
                     'genero' => $filter->getValue('genero'),
                     'telefono' => $filter->getValue('telefono'),
                     'ocupacion' => $filter->getValue('ocupacion'),
                     'ciudad' => $filter->getValue('ciudad'),
                     'empresa' => $filter->getValue('empresa'),
                     'cargo' => $filter->getValue('cargo'),
                     'email' => $filter->getValue('email'),
                     'aceptaterminos' => $filter->getValue('aceptaterminos'),                
                 )));                
                     
                $personas_id = $this->getPersonasTable()->getLastValue();                
            
                
                $this->getInscripcionesEventosTable()->saveInscripcionesEventos(new InscripcionesEventos(array(
                     'personas_id' => $personas_id,
                     'eventos_id' => $filter->getValue('evento_id'),                                   
                 )));     
                
                $inscripciones_id = $this->getInscripcionesEventosTable()->getLastValue(); 
      
                
                $this->getUsuariosTable()->saveUsuarios(new Usuarios(array(
                     'username' => $filter->getValue('email'),                                   
                     'passwd' => password_hash($filter->getValue('nrodoc'), PASSWORD_BCRYPT),                                   
                     'activo' => 0,                                   
                     'rol' => 1,                                   
                     'personas_id' => $personas_id,
                 )));
          
                $connection->commit();
                
                //enviar email
                $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail.html');
                $htmlMarkup = str_replace('%host_path%', 'http://linkeventt.com/assets/email', $htmlMarkup);
                $htmlMarkup = str_replace('%title%', 'Inscripción Exitosa', $htmlMarkup);
                $htmlMarkup = str_replace('%msg%', 'Gracias por registrarse al evento <strong>4to Foro Internacional de Energías Renovables</strong>. Ahora te proponemos dos opciones para finalizar tu inscripción: <br/>'
                        . ' 1) Ingresa a nuestra plataforma dando clic en VERIFICAR e ingresa de la siguiente manera:'
                        , $htmlMarkup);
                $htmlMarkup = str_replace('%user%', $filter->getValue('email'), $htmlMarkup);
                $htmlMarkup = str_replace('%passwd%', $filter->getValue('nrodoc'), $htmlMarkup);
                $htmlMarkup = str_replace('%msg2%', '<br/>2) Consigna $50.000 en la cuenta de ahorros No.68100024482 de Bancolombia a nombre de Sinergia Proyectos Sostenibles
                Nit: 890903938 y enviar copia del comprobante de pago a soporte@linkeventt.com.'
                        , $htmlMarkup);
                $htmlMarkup = str_replace('%url%', 'http://linkeventt.com/inscripciones/verificar/'.$inscripciones_id, $htmlMarkup);
                $htmlMarkup = str_replace('%btn_label%', 'VERIFICAR', $htmlMarkup);
            
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
                
                if($filter->getValue('xhr') > 0){
                    //return jsonmodel
                    return new JsonModel(array());
                }
                else{
                    $view->setVariable('success', 1);
                    return $view;
                }
                
           } catch (\Exception $ex) {               
               if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
                    $connection->rollback();
                }
                if($filter->getValue('xhr') > 0){
                    //return jsonmodel
                    return new JsonModel(array());
                }
               else{
                    $view->setVariable('fail', 1);
                    $view->setVariable('formdata', $formdata);
                    $view->setVariable('error_messages', $ex->getMessage());
//                    var_dump($ex->getMessage());
                    return $view;
               }    
           }          
           
           
        } else {
            if(isset($_POST['xhr']) && $_POST['xhr']  > 0){
                //return jsonmodel
                return new JsonModel(array());
            }
           else{
               $view->setVariable('fail', 1);
               $view->setVariable('formdata', $formdata);
               $view->setVariable('error_messages', $filter->getMessages());
//               var_dump($filter->getMessages());
               return $view;
           }
                
        }
        }
        else{
            $view->setVariable('default', 1);
            return $view;
        }
    }
    
    public function monetizatuemprendimientoAction(){  
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
        $cerrarinscripciones = false;
        if($cerrarinscripciones){
            return $this->redirect()->toRoute('inscripciones', array(                  
                'action' => 'inscripcionescerradas'
            ));
        }
        
        
        $formdata = $_POST;
        $view = new ViewModel();
        $tiposdocumento = $this->getTiposDocumentoTable()->fetchAll();
        $tiposdocumento_arr = array();
        foreach($tiposdocumento as $d){
            $tiposdocumento_arr[] = $d;
        }
        $categorias = $this->getEventosTable()->getEventosCategoriasEmp(2);
        $view->setVariable('tiposdocumento', $tiposdocumento_arr);
        $view->setVariable('categorias', $categorias);
        if($this->getRequest()->isPost()){
            
        
        $adapter = $this->getPersonasTable()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction(); 
        //nombres,apellidos,tipodoc,nrodoc,genero,telefono,ocupacion,empresa,cargo,email,email2,aceptaterminos
        $nombres = new Input('nombres');
        $nombres->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $apellidos = new Input('apellidos');
        $apellidos->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha(array('allowWhiteSpace' => true)))
               ->attach(new Validator\StringLength(array('max' => 255)));
        $tipodoc = new Input('tipodoc');
        $tipodoc->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'tipos_documento',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $nrodoc = new Input('nrodoc');
        $nrodoc->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\Db\NoRecordExists(array(
                        'table' => 'personas',
                        'field' => 'nrodoc',
                        'adapter' => $adapter
                    )));
        $genero = new Input('genero');
        $genero->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Alpha())
               ->attach(new Validator\StringLength(array('max' => 1)));
        $telefono = new Input('telefono');
        $telefono->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits())
               ->attach(new Validator\StringLength(array('max' => 45)));
        $ocupacion = new Input('ocupacion');
        $ocupacion->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $ciudad = new Input('ciudad');
        $ciudad->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $empresa = new Input('empresa');
        $empresa->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $cargo = new Input('cargo');
        $cargo->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 255)))
//            ->attach(new Validator\EmailAddress())
            ->attach(new Validator\Db\NoRecordExists(array(
                        'table' => 'personas',
                        'field' => 'email',
                        'adapter' => $adapter
                    )));
                
        $email2 = new Input('email2');
        $email2->getValidatorChain()
            ->attach(new Validator\NotEmpty())
//            ->attach(new Validator\Identical($email))
            ->attach(new Validator\StringLength(array('max' => 255)));
//            ->attach(new Validator\EmailAddress());
       
        
        $aceptaterminos = new Input('aceptaterminos');
        $aceptaterminos->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(array('max' => 2)));
        $evento_id = new Input('evento_id');
        $evento_id->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits())
            ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'eventos',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $categoria_id = new Input('categoria');
        $categoria_id->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits())
            ->attach(new Validator\Db\RecordExists(array(
                        'table' => 'categorias_emprendimientos',
                        'field' => 'id',
                        'adapter' => $adapter
                    )));
        $xhr = new Input('xhr');
        $xhr->allowEmpty(true);
        $xhr->setRequired(false);        
              
        
        $filter = new InputFilter();
        $filter->add($nombres) 
        ->add($apellidos) 
        ->add($tipodoc) 
        ->add($nrodoc)
        ->add($genero)
        ->add($telefono)
        ->add($ocupacion)
        ->add($ciudad)
        ->add($empresa)
        ->add($cargo)
        ->add($email)
        ->add($email2)
        ->add($aceptaterminos)
        ->add($evento_id)
        ->add($xhr)
        ->add($categoria_id)
        ->setData($_POST);       
       
       if ($filter->isValid()) {
           try{
                if($filter->getValue('email') !== $filter->getValue('email2')){
                    throw new \Exception("Los emails no coinciden");
                }            

                $this->getPersonasTable()->savePersonas(new Personas(array(
                     'nombres' => $filter->getValue('nombres'),
                     'apellidos' => $filter->getValue('apellidos'),
                     'tipos_documento_id' => $filter->getValue('tipodoc'),
                     'nrodoc' => $filter->getValue('nrodoc'),
                     'genero' => $filter->getValue('genero'),
                     'telefono' => $filter->getValue('telefono'),
                     'ocupacion' => $filter->getValue('ocupacion'),
                     'ciudad' => $filter->getValue('ciudad'),
                     'empresa' => $filter->getValue('empresa'),
                     'cargo' => $filter->getValue('cargo'),
                     'email' => $filter->getValue('email'),
                     'aceptaterminos' => $filter->getValue('aceptaterminos'),    
                     'categoria' => $filter->getValue('categoria'), 
                 )));                
                     
                $personas_id = $this->getPersonasTable()->getLastValue();                
            
                
                $this->getInscripcionesEventosTable()->saveInscripcionesEventos(new InscripcionesEventos(array(
                     'personas_id' => $personas_id,
                     'eventos_id' => $filter->getValue('evento_id'),                                   
                 )));     
                
                $inscripciones_id = $this->getInscripcionesEventosTable()->getLastValue(); 
      
                
                $this->getUsuariosTable()->saveUsuarios(new Usuarios(array(
                     'username' => $filter->getValue('email'),                                   
                     'passwd' => password_hash($filter->getValue('nrodoc'), PASSWORD_BCRYPT),                                   
                     'activo' => 0,                                   
                     'rol' => 1,                                   
                     'personas_id' => $personas_id,
                 )));
          
                $connection->commit();
                
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
                
                //email team organizador
                $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail_4.html');
                $htmlMarkup = str_replace('%host_path%', 'http://linkeventt.com/assets/email', $htmlMarkup);
                $htmlMarkup = str_replace('%title%', 'Nuevo inscrito', $htmlMarkup);
                $htmlMarkup = str_replace('%msg%', 'Se ha registrado una nueva inscripción en el evento Capacitación "Vive de tus sueños, monetiza tu emprendimiento":' , $htmlMarkup);
           
                $htmlMarkup = str_replace('%msg2%', '<br/>Nombre: '.$filter->getValue('nombres').' '.$filter->getValue('apellidos').'<br/>Teléfono: '.$filter->getValue('telefono').'<br/>Documento: '.$filter->getValue('nrodoc')
                        , $htmlMarkup);
            
                $html = new MimePart($htmlMarkup);
                $html->type = "text/html";

                $body = new MimeMessage();
                $body->setParts(array($html));


                $mail = new Mail\Message();
                $mail->setBody($body);
                $mail->setFrom('noreply@linkeventt.com', 'Linkeventt');
                $mail->addTo('comercialcordoba@fenalco.com.co');
                $mail->addTo('soporte@linkeventt.com');
                $mail->setSubject('Nuevo inscrito');

                $transport = new Mail\Transport\Sendmail();
                $transport->send($mail);
                
                if($filter->getValue('xhr') > 0){
                    //return jsonmodel
                    return new JsonModel(array());
                }
                else{
                    $view->setVariable('success', 1);
                    return $view;
                }
                
           } catch (\Exception $ex) {               
               if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
                    $connection->rollback();
                }
                if($filter->getValue('xhr') > 0){
                    //return jsonmodel
                    return new JsonModel(array());
                }
               else{
                    $view->setVariable('fail', 1);
                    $view->setVariable('formdata', $formdata);
                    $view->setVariable('error_messages', $ex->getMessage());
//                    var_dump($ex->getMessage());
                    return $view;
               }    
           }          
           
           
        } else {
            if(isset($_POST['xhr']) && $_POST['xhr']  > 0){
                //return jsonmodel
                return new JsonModel(array());
            }
           else{
               $view->setVariable('fail', 1);
               $view->setVariable('formdata', $formdata);
               $view->setVariable('error_messages', $filter->getMessages());
               
              
               
               
//               var_dump($filter->getMessages());
               return $view;
           }
                
        }
        }
        else{
            $view->setVariable('default', 1);
            return $view;
        }
    }
    public function verificarAction(){
        $view = new ViewModel();
        try{
            $id = $this->params()->fromRoute('id');            
            if($id > 0){          
                $inscripcion = $this->getInscripcionesEventosTable()->getInscripcionesEventos($id);         
                if($inscripcion->confirmar == null){
                    $this->getInscripcionesEventosTable()->confirmarInscripcionesEventos(1, $id);
                    $view->setVariable('confirmar', 1);
                }
                else{
                    //este evento no se encuentra
                    $view->setVariable('confirmar', 1);
                }

                return $view;
            }
            else{
                //esta url no es vàlida
                $view->setVariable('notvalid', 1);
                return $view;
            }
        } catch (\Exception $ex) {
             $view->setVariable('notvalid', 1);
             return $view;
        }
       
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
     
      public function getEventosTable()
     {
        if (!$this->eventosTable) {
            try{
                $sm = $this->getServiceLocator();
                $this->eventosTable = $sm->get('Inscripciones\Model\EventosTable');
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
           
         }
         return $this->eventosTable;
     }


}

