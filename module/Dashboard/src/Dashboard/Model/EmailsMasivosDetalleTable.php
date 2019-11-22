<?php

namespace Dashboard\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class EmailsMasivosDetalleTable
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

     public function getEmailsMasivosDetalle($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }   
     
//     public function getEmailsMasivosEvento($eid){
//            $sqlSelect = $this->tableGateway->getSql()->select();
//            $sqlSelect->columns(array('medio', 'fecha', 'url'));
//            $sqlSelect->join('eventos', 'eventos.id = pagos.evento_id', array(), 'inner');
//            $sqlSelect->join('usuarios', 'usuarios.id = pagos.usuario_id', array(), 'inner');
//            $sqlSelect->join('personas', 'personas.id = usuarios.personas_id', array('nombres', 'apellidos', 'email'), 'inner');
//            $sqlSelect->where->equalTo('eventos.id', $eid);
//
//            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
//                      
//            $resultSet = $statement->execute();
//            $rows = array();
//            if($resultSet->count()>0){                
//                while($resultSet->next()){
//                    $rows[] = $resultSet->current();
//                }
//            }
//            
//            return $rows;
//     }

     public function saveEmailsMasivosDetalle(EmailsMasivosDetalle $emailsmasivosdetalle)
     {
         $data = array(
             'usuario_id' => $emailsmasivosdetalle->usuario_id,
             'emails_masivos_id' => $emailsmasivosdetalle->emails_masivos_id,
             'fecha_envio' => $emailsmasivosdetalle->fecha_envio,                                 
         );

         $id = (int) $emailsmasivosdetalle->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getEmailsMasivosDetalle($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('EmailsMasivosDetalle id does not exist');
             }
         }
     }

     public function deleteEmailsMasivosDetalle($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }