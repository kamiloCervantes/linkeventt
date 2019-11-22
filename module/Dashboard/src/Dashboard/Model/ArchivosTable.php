<?php

namespace Dashboard\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class ArchivosTable
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

     public function getArchivos($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }   
     

     public function saveArchivos(Archivos $archivos)
     {
         $data = array(
             'nombre' => $archivos->nombre,
             'ruta' => $archivos->ruta,
             'fecha_creacion' => $archivos->fecha_creacion,
         );

         $id = (int) $archivos->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getArchivos($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Archivos id does not exist');
             }
         }
     }

     public function deleteArchivos($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }