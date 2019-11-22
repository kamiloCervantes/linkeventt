<?php

namespace Dashboard\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class EmailsMasivosTable
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

     public function getEmailsMasivos($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }   
     

     public function saveEmailsMasivos(EmailsMasivos $emailsmasivos)
     {
         $data = array(
             'evento_id' => $emailsmasivos->evento_id,
             'etiqueta' => $emailsmasivos->etiqueta,
             'emisor_id' => $emailsmasivos->emisor_id,
             'fecha_envio' => $emailsmasivos->fecha_envio,
             'tipo_envio' => $emailsmasivos->tipo_envio,                                  
         );
         
        

         $id = (int) $emailsmasivos->id;
         if ($id == 0) {
              var_dump($data);
             var_dump($this->tableGateway->insert($data));
             var_dump($this->tableGateway->lastInsertValue);
         } else {
             if ($this->getEmailsMasivos($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('EmailsMasivos id does not exist');
             }
         }
     }

     public function deleteEmailsMasivos($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }