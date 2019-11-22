<?php

namespace Inscripciones\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class UsuariosTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {       
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }   
      
     public function getAdapter(){
         return $this->tableGateway->getAdapter();
     }
     
     public function getUsuarioByPersona($persona_id){
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('personas_id' => $persona_id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function getUsuarios($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }
     
     public function getUsuariosByUsername($username)
     {
         $id  = (int) $id;
         
         $rowset = $this->tableGateway->select(array('username' => $username));
         $row = $rowset->current();       
         return $row;
     }
     
     public function verificarUser($pw, $userdata){
         $passwd_check = false;
         if($userdata != null){
             $passwd_check = password_verify($pw, $userdata->passwd);
         }
         return $passwd_check;
     }
     
     public function getEventosUsuario($uid){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('id'));
            $sqlSelect->join('admin_eventos', 'usuarios.id = admin_eventos.usuario_id', array(), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = admin_eventos.evento_id', array('nombre_evento', 'evento_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('usuarios.id', $uid);

            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
            $resultSet = $statement->execute();
            
            return $resultSet->current();
     }
     
     
     

     public function saveUsuarios(Usuarios $usuarios)
     {
         $data = array(
             'username' => $usuarios->username,
             'passwd' => $usuarios->passwd,
             'activo' => $usuarios->activo,
             'personas_id' => $usuarios->personas_id,                   
             'rol' => $usuarios->rol,                   
         );

         $id = (int) $usuarios->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getUsuarios($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Usuarios id does not exist');
             }
         }
     }

     public function deleteUsuarios($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }