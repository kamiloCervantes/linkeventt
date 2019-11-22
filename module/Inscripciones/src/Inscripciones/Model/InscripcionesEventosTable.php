<?php

namespace Inscripciones\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class InscripcionesEventosTable
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

     public function getInscripcionesEventos($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }
     
     public function confirmarInscripcionesEventos($confirmado, $id){
         $this->tableGateway->update(array('confirmar' => $confirmado), array('id' => $id));
     }
     
     public function confirmarAsistenciaEventos($id){
         $this->tableGateway->update(array('asistencia' => 1), array('id' => $id));
     }


     public function saveInscripcionesEventos(InscripcionesEventos $inscripcioneseventos)
     {
         $data = array(
             'personas_id' => $inscripcioneseventos->personas_id,
             'eventos_id' => $inscripcioneseventos->eventos_id,         
         );

         $id = (int) $inscripcioneseventos->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getInscripcionesEventos($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('InscripcionesEventos id does not exist');
             }
         }
     }

     public function deleteInscripcionesEventos($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }