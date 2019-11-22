<?php

namespace Dashboard\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class PagosTable
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

     public function getPagos($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     

     
     public function getPagosEvento($eid){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('medio', 'fecha', 'url','id'));
            $sqlSelect->join('eventos', 'eventos.id = pagos.evento_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'usuarios.id = pagos.usuario_id', array(), 'inner');
            $sqlSelect->join('personas', 'personas.id = usuarios.personas_id', array('nombres', 'apellidos', 'email','nrodoc'), 'inner');
            $sqlSelect->where->equalTo('eventos.id', $eid)
                    ->nest()
                    ->greaterThan('pagos.valor',0)
                    ->unnest();

            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
                      
            $resultSet = $statement->execute();
            $rows = array();
            if($resultSet->count()>0){                
                while($resultSet->next()){
                    $rows[] = $resultSet->current();
                }
            }
            
            return $rows;
     }
     public function getTicketsCortesiaEvento($eid){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('medio', 'fecha', 'url','id'));
            $sqlSelect->join('eventos', 'eventos.id = pagos.evento_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'usuarios.id = pagos.usuario_id', array(), 'inner');
            $sqlSelect->join('personas', 'personas.id = usuarios.personas_id', array('nombres', 'apellidos', 'email','nrodoc'), 'inner');
            $sqlSelect->where->equalTo('eventos.id', $eid)
                    ->nest()
                    ->equalTo('pagos.valor',0)
                    ->unnest();

            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
                      
            $resultSet = $statement->execute();
            $rows = array();
            if($resultSet->count()>0){                
                while($resultSet->next()){
                    $rows[] = $resultSet->current();
                }
            }
            
            return $rows;
     }
     public function getTicketsEvento($eid){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('medio', 'fecha', 'url','id'));
            $sqlSelect->join('eventos', 'eventos.id = pagos.evento_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'usuarios.id = pagos.usuario_id', array(), 'inner');
            $sqlSelect->join('personas', 'personas.id = usuarios.personas_id', array('nombres', 'apellidos', 'email','nrodoc'), 'inner');
            $sqlSelect->where->equalTo('eventos.id', $eid);

            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
                      
            $resultSet = $statement->execute();
            $rows = array();
            if($resultSet->count()>0){                
                while($resultSet->next()){
                    $rows[] = $resultSet->current();
                }
            }
            
            return $rows;
     }
     
     public function getInscripcionPago($id){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array());
            $sqlSelect->join('eventos', 'eventos.id = pagos.evento_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'usuarios.id = pagos.usuario_id', array(), 'inner');
            $sqlSelect->join('personas', 'personas.id = usuarios.personas_id', array('nombres', 'apellidos', 'email','nrodoc'), 'inner');
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('id'), 'inner');
            $sqlSelect->where->equalTo('pagos.id', $id);

            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
                      
            $resultSet = $statement->execute();
           
            
            return $resultSet->current();
     }
     
     

     public function savePagos(Pagos $pagos)
     {
         $data = array(
             'evento_id' => $pagos->evento_id,
             'usuario_id' => $pagos->usuario_id,
             'medio' => $pagos->medio,
             'valor' => $pagos->valor,
             'fecha' => $pagos->fecha,
             'cod_seguimiento' => $pagos->cod_seguimiento,
             'url' => $pagos->url,                         
             'code' => $pagos->code,                         
             'archivo_id' => $pagos->archivo_id                         
         );

         $id = (int) $pagos->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             $pago = $this->getPagos($id);
             
             $data = array(
                'evento_id' => $pagos->evento_id == '' ? $pago->evento_id : $pagos->evento_id,                       
                'usuario_id' => $pagos->usuario_id == '' ? $pago->usuario_id : $pagos->usuario_id,                       
                'medio' => $pagos->medio == '' ? $pago->medio : $pagos->medio,                       
                'valor' => $pagos->valor == '' ? $pago->valor : $pagos->valor,                       
                'fecha' => $pagos->fecha == '' ? $pago->fecha : $pagos->fecha,                       
                'cod_seguimiento' => $pagos->cod_seguimiento == '' ? $pago->cod_seguimiento : $pagos->cod_seguimiento,                       
                'url' => $pagos->url == '' ? $pago->url : $pagos->url,                       
                'code' => $pagos->code == '' ? $pago->code : $pagos->code,                       
            );
             if ($pago) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Pagos id does not exist');
             }
         }
     }

     public function deletePagos($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }