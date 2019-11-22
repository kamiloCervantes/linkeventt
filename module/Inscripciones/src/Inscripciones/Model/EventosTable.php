<?php

namespace Inscripciones\Model;

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
     
     public function getUsuarioByPersona($persona_id){
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('personas_id' => $persona_id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function getEventos($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }
     
    
  
     
     public function getEventosCategoriasEmp($eid){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array());
            $sqlSelect->join('categorias_emprendimientos', 'eventos.id = categorias_emprendimientos.cod_evento', array('id', 'nombre_categoria'), 'inner');
            $sqlSelect->where->equalTo('eventos.id', $eid);

            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
            $resultSet = $statement->execute();
            
            $rows = array();
            if($resultSet->count()>0){                
                while($resultSet->next()){
                    $rows[] = $resultSet->current();
                }
            }
//            var_dump($rows);
            return $rows;
     }
     
     
     

     public function saveEventos(Eventos $eventos)
     {
         $data = array(
             'username' => $eventos->username,
             'passwd' => $eventos->passwd,
             'activo' => $eventos->activo,
             'personas_id' => $eventos->personas_id,                   
             'rol' => $eventos->rol,                   
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