<?php

namespace Dashboard\Controller;

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



class DashboardController extends AbstractActionController
{
    protected $usuariosTable;
    protected $personasTable;
    protected $pagosTable;
    protected $emailsmasivosTable;
    protected $emailsmasivosdetalleTable;
    protected $archivosTable;

    public function indexAction()
    {
        $view = new ViewModel();       
        session_start();
        $uid = $_SESSION['usersession.uid'];
        if($uid > 0){
            $user = $this->getUsuariosTable()->getUsuarios($uid);
            $persona = $this->getPersonasTable()->getPersonas($user->personas_id);
            $view->setVariable('persona', $persona);
            $view->setVariable('rol', $user->rol);
            if($user->rol == 1){
//                 $this->layout('layout/dashboard3');
            }
            else{
//                $this->layout('layout/dashboard2');                
                $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
                $inscritos = $this->getPersonasTable()->getPersonasEvento($evento['evento_id']);                
                $view->setVariable('inscritos', $inscritos);
            }
            
        }
        else{
            //logout y redirect a pagina principal
        }
        
        return $view;
    }
    
    public function emailsAction(){
        
    }
    
    public function startemailAction(){
        
    }
    
    public function estadisticaseventoAction(){
        $uid = $_SESSION['usersession.uid'];       
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $estadisticas = $this->getEventosTable()->getEstadisticasEvento($evento['evento_id']);
    }
    
    public function refrescarsesionAction(){
        $view = new JsonModel(); 
        session_start();
        $view->setVariable('status', 'ok');
        return $view;
    }
    
    public function getrecordatoriospagosAction(){        
        $view = new JsonModel(); 
        session_start();
        $uid = $_SESSION['usersession.uid'];       
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $pagos = $this->getPersonasTable()->getPersonasEventoNoPagos($evento['evento_id']); 
        $view->setVariable('pagos', $pagos);
        return $view;
    }
    
    public function getenviousuariosAction(){        
        $view = new JsonModel(); 
        session_start();
        $uid = $_SESSION['usersession.uid'];       
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $inscritos = $this->getPersonasTable()->getPersonasEvento($evento['evento_id']); 
        $view->setVariable('inscritos', $inscritos);
        return $view;
    }
    
    public function getenvioentradasAction(){        
        $view = new JsonModel(); 
        session_start();
        $uid = $_SESSION['usersession.uid'];       
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $entradas = $this->getPersonasTable()->getPersonasEventoPagos($evento['evento_id']); 
        $view->setVariable('entradas', $entradas);
        return $view;
    }
    
    public function generateqrcodesAction(){    
        $view = new ViewModel(); 
        session_start();
        $uid = $_SESSION['usersession.uid'];       
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $entradas = $this->getPersonasTable()->getPersonasEventoPagos($evento['evento_id']); 
        $data = array();
        foreach($entradas as $e){
            $code = md5($e['nrodoc']);
//            var_dump('pago_id: '.$e['pagos_id'].' ;Codigo: '.$code);
            $this->getPagosTable()->savePagos(new Pagos(array(
                'id' => $e['pagos_id'],
                'code' => $code               
            )));
            $client = new Client('http://qrgen.linkeventt.com?eid='.$evento['evento_id'].'&i='.$code);
            $response = $client->send();
        }
   
        return $view;
    }
    
    public function mailtestcontentAction(){
        $view = new ViewModel(); 
        $this->layout('layout/mailtest');
        $htmlMarkup = '';
        $user = 97;
        $eid = 1;
        $tipo_envio = 3;
        switch($tipo_envio){
            case 1:
                $htmlMarkup = $this->emailtemplate1();
                break;
            case 2:
                $persona = $this->getPersonasTable()->getPersonaUid($user);
                $htmlMarkup = $this->emailtemplate2(array(
                    'email' => $persona['email'],
                    'nrodoc' => $persona['nrodoc'],
                    'inscripcion_id' => $persona['inscripcion_id']
                ));
                break;
            case 3:
                $persona = $this->getPersonasTable()->getPersonaCode($user,$eid);
                $htmlMarkup = $this->emailtemplate3($persona['code'], $persona['nrodoc']);
                break;
        }
        $view->setVariable('htmltpl',$htmlMarkup);
        return $view;
    }
    
    public function enviarticketAction(){
        $view = new JsonModel();
        $pagos_id = $this->params()->fromPost('id');
        $asunto = 'Entradas evento 4to Foro Internacional de Energías Renovables';
        $pago = $this->getPagosTable()->getPagos($pagos_id);
        $persona = $this->getPersonasTable()->getPersonaUid($pago->usuario_id);
        $htmlMarkup = $this->emailtemplate3($pago->code, $persona['nrodoc']);
        $html = new MimePart($htmlMarkup);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));


        $mail = new Mail\Message();
        $mail->setBody($body);
        $mail->setFrom('noreply@linkeventt.com', 'Linkeventt');
        $mail->addTo($persona['email']);
        $mail->addTo('genmotionmakers@gmail.com');
        $mail->setSubject($asunto);

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
        $view->setVariable('status', 'ok');
        
        return $view;
    }
    
    public function guardarenviomasivoAction(){
        $view = new JsonModel(); 
        session_start();
        $uid = $_SESSION['usersession.uid'];      
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $adapter = $this->getPersonasTable()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();         
        $etiqueta = new Input('etiqueta');
        $etiqueta->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        $tipo_envio = new Input('tipo_envio');
        $tipo_envio->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits());
        $usuarios = new Input('usuarios');
        $usuarios->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\StringLength(array('max' => 255)));
        
        $filter = new InputFilter();
        $filter->add($etiqueta) 
        ->add($tipo_envio) 
        ->add($usuarios) 
        ->setData($_POST);   
        
        if ($filter->isValid()) {
            try{
                $this->getEmailsMasivosTable()->saveEmailsMasivos(new EmailsMasivos(array(
                    'evento_id' => $evento['evento_id'],
                    'etiqueta' => $filter->getValue('etiqueta'),
                    'emisor_id' => $uid,         
                    'tipo_envio' => $filter->getValue('tipo_envio'),    
                )));
          
                
                $email_masivos_id = $this->getEmailsMasivosTable()->getLastValue();
          
                
                $usuarios = explode(',', $filter->getValue('usuarios'));
                $asunto = '';
                foreach($usuarios as $user){
                    //enviar email
                    $htmlMarkup = '';
                    $persona = $this->getPersonasTable()->getPersonaUid($user);
                    $email_persona = $persona['email'];
           
                    switch($filter->getValue('tipo_envio')){
                        case 1:
                            $asunto = 'Finalizar inscripción 4to Foro Internacional de Energías Renovables';
                            $htmlMarkup = $this->emailtemplate1();
                            break;
                        case 2:
                            $asunto = 'Datos de acceso Plataforma 4to Foro Internacional de Energías Renovables';
                            
                            $htmlMarkup = $this->emailtemplate2(array(
                                'email' => $persona['email'],
                                'nrodoc' => $persona['nrodoc'],
                                'inscripcion_id' => $persona['inscripcion_id'],
                            ));
                            break;
                        case 3:
                            $asunto = 'Ticket para entrar al evento 4to Foro Internacional de Energías Renovables';
                            $persona = $this->getPersonasTable()->getPersonaCode($user,$evento['evento_id']);
                            $htmlMarkup = $this->emailtemplate3($persona['code'], $persona['nrodoc']);
                            break;
                    }
                    
                    $html = new MimePart($htmlMarkup);
                    $html->type = "text/html";

                    $body = new MimeMessage();
                    $body->setParts(array($html));


                    $mail = new Mail\Message();
                    $mail->setBody($body);
                    $mail->setFrom('noreply@linkeventt.com', 'Linkeventt');
                    $mail->addTo($email_persona);
                    $mail->addTo('genmotionmakers@gmail.com');
                    $mail->setSubject($asunto);

                    $transport = new Mail\Transport\Sendmail();
                    $transport->send($mail);
                    //guardar en bd
                
                    $this->getEmailsMasivosDetalleTable()->saveEmailsMasivosDetalle(new EmailsMasivosDetalle(array(
                        'usuario_id' => $user,
                        'emails_masivos_id' => $email_masivos_id          
                    )));
                }
                $connection->commit();
                $view->setVariable('status', 'ok');
            } catch (Exception $ex) {
                if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
                    $connection->rollback();
                }
                else{
                    $view->setVariable('fail', 1);
                    $view->setVariable('error_messages', $ex->getMessage());
                }
            }
           
        }
        else{
             $view->setVariable('fail', 1);
             $view->setVariable('error_messages', $filter->getMessages());
        }
        
        return $view;
    }
    
    public function emailtemplate1(){
        //Recordatorio de pago
        $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail_1.html');
        $htmlMarkup = str_replace('%host_path%', 'http://linkeventt.com/assets/email', $htmlMarkup);
        $htmlMarkup = str_replace('%title%', 'FINALIZAR INSCRIPCIÓN', $htmlMarkup);
        $htmlMarkup = str_replace('%msg%', 'Gracias por registrarse al evento <strong>4to Foro Internacional de Energías Renovables</strong>. Le recordamos muy respetuosamente las formas de pago disponibles para finalizar su inscripción. En caso de que ya haya cancelado la inscripción hacer caso omiso de la información.<br/>'
                . ' 1) Ingresar a la plataforma Linkeventt (<a href="http://linkeventt.com/welcome">http://linkeventt.com/welcome</a>) con sus datos de acceso y seleccionar la opción Pagar. Allí encontrará un botón de pago en línea a través de PayU.'
                , $htmlMarkup);
        $htmlMarkup = str_replace('%msg2%', '<br/>2) Consignar $50.000 en la cuenta de ahorros No.68100024482 de Bancolombia a nombre de Sinergia Proyectos Sostenibles
        Nit: 890903938 y enviar copia del comprobante de pago a soporte@linkeventt.com.'
                , $htmlMarkup);
        return $htmlMarkup;
    }
    
    public function emailtemplate2($data){
        //envio de usuarios
        $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail.html');
        $htmlMarkup = str_replace('%host_path%', 'http://linkeventt.com/assets/email', $htmlMarkup);
        $htmlMarkup = str_replace('%title%', 'Inscripción Exitosa', $htmlMarkup);
        $htmlMarkup = str_replace('%msg%', 'Gracias por registrarse al evento <strong>4to Foro Internacional de Energías Renovables</strong>. Ahora te proponemos dos opciones para finalizar tu inscripción: <br/>'
                . ' 1) Ingresa a nuestra plataforma dando clic en VERIFICAR e ingresa de la siguiente manera:'
                , $htmlMarkup);
        $htmlMarkup = str_replace('%user%', $data['email'], $htmlMarkup);
        $htmlMarkup = str_replace('%passwd%', $data['nrodoc'], $htmlMarkup);
        $htmlMarkup = str_replace('%msg2%', '<br/>2) Consigna $50.000 en la cuenta de ahorros No.68100024482 de Bancolombia a nombre de Sinergia Proyectos Sostenibles
        Nit: 890903938 y enviar copia del comprobante de pago a soporte@linkeventt.com con tus datos personales.'
                , $htmlMarkup);
        $htmlMarkup = str_replace('%url%', 'http://linkeventt.com/inscripciones/verificar/'.$data['inscripcion_id'], $htmlMarkup);
        $htmlMarkup = str_replace('%btn_label%', 'VERIFICAR', $htmlMarkup);
        return $htmlMarkup;
    }
    
    public function emailtemplate3($code,$nrodoc){ 
        //envio de entradas
        $htmlMarkup = file_get_contents(ROOT_PATH.'/assets/email/template_mail_2.html');
        $htmlMarkup = str_replace('%host_path%', 'http://linkeventt.com/assets/email', $htmlMarkup);
        $htmlMarkup = str_replace('%title%', 'Entradas para el 4to Foro Internacional de Energías Renovables', $htmlMarkup);
        $htmlMarkup = str_replace('%msg%', 'Gracias por registrarse al evento <strong>4to Foro Internacional de Energías Renovables</strong>. Puedes obtener tus entradas a través de las siguientes opciones: <br/>'
                . ' 1) Ingresa a la plataforma Linkeventt (<a href="http://linkeventt.com/welcome">http://linkeventt.com/welcome</a>) con tus datos de acceso y en la opción ENTRADAS podrás descargar tu tiquete para asistir al evento.'
                , $htmlMarkup);

        $htmlMarkup = str_replace('%msg2%', '<br/>2) Descarga el ticket de tu entrada haciendo click en el siguiente botón:'
                , $htmlMarkup);
        $htmlMarkup = str_replace('%url%', 'http://pdfgen.linkeventt.com/?code='.$code.'&nrodoc='.$nrodoc, $htmlMarkup);
        $htmlMarkup = str_replace('%btn_label%', 'DESCARGAR TICKET', $htmlMarkup);
        return $htmlMarkup;
    }
    
    public function reporteinscritosAction(){
        $view = new ViewModel(); 
        $view->setTerminal(true);
        $date = date('YmdHis');
        $filename = "inscritos$date.csv";
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array(
            'Nombres', 'Apellidos','Telefono','Email', 'Empresa', 'Ciudad','Cargo','Fecha inscripcion'
        ));
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        session_start();
        $uid = $_SESSION['usersession.uid'];
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $inscritos = $this->getPersonasTable()->getPersonasEvento($evento['evento_id']); 


        foreach($inscritos as $in){
            fputcsv($fp, array_map("utf8_decode", $in));
        }
        return $view;
    }
    
    public function reportepagosAction(){
        $view = new ViewModel(); 
        $view->setTerminal(true);
        $date = date('YmdHis');
        $filename = "pagos$date.csv";
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array(
            'Medio (1->Payu:2->Consignacion)', 'Fecha','Comprobante','Nombres', 'Apellidos', 'Email'
        ));
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        session_start();
        $uid = $_SESSION['usersession.uid'];
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $pagos = $this->getPagosTable()->getPagosEvento($evento['evento_id']);

        foreach($pagos as $p){
            fputcsv($fp, array_map("utf8_decode", $p));
        }
        return $view;
    }
    
    public function entradasAction(){
        $view = new ViewModel();       
        session_start();
        $uid = $_SESSION['usersession.uid'];
         if($uid > 0){
            $user = $this->getUsuariosTable()->getUsuarios($uid);
            $persona = $this->getPersonasTable()->getPersonas($user->personas_id);
            $evento = $this->getPersonasTable()->getEventoPersona($user->personas_id);
            $pago = $this->getPersonasTable()->getPersonaEventoPago($evento['eid'],$uid);
//            var_dump($pago);
            $view->setVariable('pago', $pago);
            $view->setVariable('persona', $persona);
            
             
        }
        return $view;
    }
    
    public function registrarpagoAction(){
        $view = new ViewModel();
        session_start();
        $uid = $_SESSION['usersession.uid'];       
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $pagos = $this->getPersonasTable()->getPersonasEventoNoPagos($evento['evento_id']); 
        $view->setVariable('pagos', $pagos);
        return $view;
    }
    
    public function guardarpagoAction(){
        $view = new JsonModel();
        session_start();
        $uid = $_SESSION['usersession.uid'];    
        $evento = $this->getUsuariosTable()->getEventosUsuario($uid);
        $persona = $this->getPersonasTable()->getPersonas($uid);
        
        $medio = new Input('medio');
        $medio->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits);
        $valor = new Input('valor');
        $valor->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits());
        $fecha = new Input('fecha');
        $fecha->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Date());
        $cod_seguimiento = new Input('cod_seguimiento');
        $cod_seguimiento->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits());
        $comprobante = new Input('comprobante');
        $comprobante->setAllowEmpty(true);
        $comprobante->getValidatorChain()
               ->attach(new Validator\Digits());
        $usuario_id = new Input('usuario_id');
        $usuario_id->getValidatorChain()
               ->attach(new Validator\NotEmpty())
               ->attach(new Validator\Digits());
        
        $filter = new InputFilter();
        $filter->add($medio) 
        ->add($valor) 
        ->add($fecha) 
        ->add($cod_seguimiento) 
        ->add($comprobante) 
        ->add($usuario_id) 
        ->setData($_POST);   
        
        if ($filter->isValid()) {
            try{
                $persona2 = $this->getPersonasTable()->getPersonaUid($filter->getValue('usuario_id'));
                $code = md5($persona2['nrodoc']);
                $this->getPagosTable()->savePagos(new Pagos(array(
                    'evento_id' => $evento['evento_id'],
                    'usuario_id' => $filter->getValue('usuario_id'),
                    'medio' => $filter->getValue('medio'),
                    'valor' => $filter->getValue('valor'),
                    'fecha' => $filter->getValue('fecha'),
                    'cod_seguimiento' => $filter->getValue('cod_seguimiento'),                        
                    'code' => $code,                         
                    'archivo_id' => $filter->getValue('comprobante')       
                )));
                
                $client = new Client('http://qrgen.linkeventt.com?eid='.$evento['evento_id'].'&i='.$code);
                $response = $client->send();

                $asunto = 'Ticket para entrar al evento 4to Foro Internacional de Energías Renovables';
                $htmlMarkup = $this->emailtemplate3($code, $persona2['nrodoc']);
                $html = new MimePart($htmlMarkup);
                $html->type = "text/html";

                $body = new MimeMessage();
                $body->setParts(array($html));


                $mail = new Mail\Message();
                $mail->setBody($body);
                $mail->setFrom('noreply@linkeventt.com', 'Linkeventt');
                $mail->addTo($persona2['email']);
                $mail->addTo('genmotionmakers@gmail.com');
                $mail->setSubject($asunto);

                $transport = new Mail\Transport\Sendmail();
                $transport->send($mail);
                
                $view->setVariable('status', 'ok');
            } catch (Exception $ex) {
                if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
                    $view->setVariable('fail', 1);
                    $view->setVariable('error_messages', $ex->getMessage());
                }
                else{
                    $view->setVariable('fail', 1);
                    $view->setVariable('error_messages', $ex->getMessage());
                }
            }
        }
        else{
            $view->setVariable('fail', 1);
            $view->setVariable('error_messages', $filter->getMessages());
        }
        
        return $view;
    }
    
    
    public function confirmarasistenciaAction(){
        $view = new ViewModel();       
        $id = $this->params()->fromRoute('id');
        $insc_id = $this->getPagosTable()->getInscripcionPago($id);     
        $this->getInscripcionesEventosTable()->confirmarAsistenciaEventos($insc_id['id']);     
        return $view;
    }
    
    public function subirarchivoAction(){
        $view = new ViewModel();
        $view->setTerminal(true);
        if($_FILES['comprobante']['error'] == UPLOAD_ERR_OK){
            $nombre = time().'-'.$_FILES['comprobante']['name'];
            $rel_path = '/files/'.$nombre;
            $real_path = ROOT_PATH.$rel_path;
            if(move_uploaded_file($_FILES['comprobante']['tmp_name'], $real_path)){
                $this->getArchivosTable()->saveArchivos(new Archivos(array(
                    'nombre' => $nombre,
                    'ruta' => $rel_path,
                )));
                echo $this->getArchivosTable()->getLastValue();
            }
        }
        return $view;
    }
    
    public function ingresosAction(){
        
        $view = new ViewModel();
        session_start();
        $uid = $_SESSION['usersession.uid'];
        
        if($uid > 0){
            $evento = $this->getUsuariosTable()->getEventosUsuario($uid);            
            $pagos = $this->getPagosTable()->getPagosEvento($evento['evento_id']);
            $view->setVariable('pagos', $pagos);
//            var_dump($pagos);
        }
        
        return $view;
    }
    
    public function ticketscortesiaAction(){
        
        $view = new ViewModel();
        session_start();
        $uid = $_SESSION['usersession.uid'];
        
        if($uid > 0){
            $evento = $this->getUsuariosTable()->getEventosUsuario($uid);            
            $pagos = $this->getPagosTable()->getTicketsCortesiaEvento($evento['evento_id']);
            $view->setVariable('pagos', $pagos);
//            var_dump($pagos);
        }
        
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

}

