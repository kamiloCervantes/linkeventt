<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Session\SessionManager;

class LoginController extends AbstractActionController
{
    protected $usuariosTable;
    protected $personasTable;

    public function indexAction()
    {
        $this->layout('layout/login');
        session_start();
//        var_dump($_SESSION);
//        if($_SESSION['usersession.uid'] > 0){
//            return $this->redirect()->toRoute('dashboard', array(                  
//                            'action' => 'index'
//                        ));
//        }
        $view = new ViewModel();
//        echo password_verify('98668286', '$2y$10$GxI9VvY1uBBbzTqIEly3MevMRzZfSzCKNzgAxW6cxFh4LJFGpdb0S');
        if($this->request->isPost()){
            $username = new Input('username');
            $passwd = new Input('passwd');
            
            $filter = new InputFilter();
            $filter->add($username) 
            ->add($passwd)
            ->setData($_POST);    
            
            if($filter->isValid()){
                $user = $this->getUsuariosTable()->getUsuariosByUsername($filter->getValue('username'));
//                var_dump($user);
                if($user){
                   if($this->getUsuariosTable()->verificarUser($filter->getValue('passwd'), $user)){
                       //redirigir a dashboard
                       $persona = $this->getPersonasTable()->getPersonas($user->personas_id);

                       session_start();
                       $_SESSION['usersession.uid'] = $user->id; 
                       $_SESSION['usersession.rol'] = $user->rol; 
                       $_SESSION['usersession.nombres'] = $persona->nombres; 
                       $_SESSION['usersession.apellidos'] = $persona->apellidos; 
                       $_SESSION['usersession.email'] = $persona->email; 
                       return $this->redirect()->toRoute('dashboard', array(                  
                            'action' => 'index'
                        ));
                   }
                   else{
                       $view->setVariable('error', 'La clave ingresada no coincide');
                   }
                }
                else{
                    $view->setVariable('error', 'Usuario o contraseña incorrecta');
                }
  
            }
        }
        
        return $view;
        
    }
    
    public function logoutAction(){
        session_start();
        session_destroy();
        return $this->redirect()->toRoute('login', array(                  
            'action' => 'index'
        ));
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

}

