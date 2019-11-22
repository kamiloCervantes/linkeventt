<?php

namespace Eventos\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class EventosTable
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

     public function saveEventos(Eventos $eventos)
     {
         $data = array(
             'nombre_evento' => $eventos->nombre_evento,
             'organizador' => $eventos->organizador,
             'fecha_creacion' => $eventos->fecha_creacion,
             'fecha_evento' => $eventos->fecha_evento,
             'evento_padre' => $eventos->evento_padre,
             'alias' => $eventos->alias,
                           
         );

         $id = (int) $eventos->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getEventos($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Eventos id does not exist');
             }
         }
     }

     public function deleteEventos($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }     
    


 }