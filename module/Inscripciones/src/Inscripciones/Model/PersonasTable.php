<?php

namespace Inscripciones\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class PersonasTable
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

     public function getPersonas($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }
     
     public function getEventoPersona($uid){
          $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array());
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array(), 'inner');
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array(), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array('eid' => 'id'), 'inner');
            $sqlSelect->where->equalTo('usuarios.id',$uid);
            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
            $resultSet = $statement->execute();

            return $resultSet->current();
     }
     
     public function getEventoPersonaCode($eid,$code){
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('nombres','apellidos','nrodoc','email','telefono','empresa','cargo'));
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array(), 'inner');
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('inscripcion_id' => 'id'), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array('eid' => 'id'), 'inner');
            $sqlSelect->join('pagos', 'usuarios.id = pagos.usuario_id', array('code', 'pagos_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('pagos.code',$code) 
                    ->nest()
                    ->equalTo('eventos.id',$eid)
                    ->unnest();
            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
            $resultSet = $statement->execute();

            return $resultSet->current();
     }
     
      public function getPersonasEvento($eid){
            
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('nombres','apellidos','telefono','email','empresa','ciudad','cargo'));
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('fecha_inscripcion'), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array('user_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('eventos.id',$eid)
                    ->nest()
                    ->equalTo('usuarios.rol',1)
                    ->unnest();
            $sqlSelect->order('fecha_inscripcion DESC');
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
     
      public function getPersonaUid($uid){
            
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('nombres','apellidos','telefono','email','empresa','ciudad','cargo','nrodoc'));
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array(), 'inner');
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('inscripcion_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('usuarios.id',$uid);
            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
            $resultSet = $statement->execute();
                       
            return $resultSet->current();
     }
     
      public function getPersonaCode($uid,$eid){            
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('nombres','apellidos','telefono','email','empresa','ciudad','cargo','nrodoc'));
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('fecha_inscripcion'), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array(), 'inner');
            $sqlSelect->join('pagos', 'usuarios.id = pagos.usuario_id', array('code', 'pagos_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('eventos.id',$eid)
                    ->nest()
                    ->equalTo('usuarios.id',$uid)
                    ->unnest();
            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
            $resultSet = $statement->execute();
                       
            return $resultSet->current();
     }
     
      public function getPersonasEventoPagos($eid){            
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('id','nombres','apellidos','email','nrodoc'));
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('fecha_inscripcion'), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array('user_id' => 'id'), 'inner');
            $sqlSelect->join('pagos', 'usuarios.id = pagos.usuario_id', array('code', 'pagos_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('eventos.id',$eid)
                    ->nest()
                    ->equalTo('usuarios.rol',1)
                    ->unnest();
            $sqlSelect->order('fecha_inscripcion DESC');
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
     
      public function getPersonaEventoPago($eid, $uid){       
//          var_dump($eid);
//          var_dump($uid);
            $sqlSelect = $this->tableGateway->getSql()->select();
            $sqlSelect->columns(array('id','nombres','apellidos','email','nrodoc'));
            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('fecha_inscripcion'), 'inner');
            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array(), 'inner');
            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array('user_id' => 'id'), 'inner');
            $sqlSelect->join('pagos', 'usuarios.id = pagos.usuario_id', array('code', 'pagos_id' => 'id'), 'inner');
            $sqlSelect->where->equalTo('eventos.id',$eid)
                    ->nest()
                    ->equalTo('usuarios.id',$uid)
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
     
      public function getPersonasEventoNoPagos($eid){      
            $sql = sprintf("select personas.id,nombres,apellidos,email,fecha_inscripcion,usuarios.id as user_id from personas
            inner join usuarios on usuarios.personas_id = personas.id
            inner join inscripciones_eventos on inscripciones_eventos.personas_id = personas.id
            inner join eventos on inscripciones_eventos.eventos_id = eventos.id
            where eventos.id = %s 
            and usuarios.id not in (
                select usuario_id from pagos
                where pagos.evento_id = %s
                ) order by fecha_inscripcion desc", $eid, $eid);
            $result = $this->getAdapter()->getDriver()->getConnection()->execute($sql);
            return $result;
//            $sqlSelect = $this->tableGateway->getSql()->select();
//            $sqlSelect->columns(array('id','nombres','apellidos','email'));
//            $sqlSelect->join('inscripciones_eventos', 'personas.id = inscripciones_eventos.personas_id', array('fecha_inscripcion'), 'inner');
//            $sqlSelect->join('eventos', 'eventos.id = inscripciones_eventos.eventos_id', array(), 'inner');
//            $sqlSelect->join('usuarios', 'personas.id = usuarios.personas_id', array(), 'inner');
//            $sqlSelect->where->equalTo('eventos.id',$eid)
//                    ->nest()
//                    ->equalTo('usuarios.rol',1)                 
//                    ->unnest();
//            $sqlSelect->order('fecha_inscripcion DESC');
//            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
//            $resultSet = $statement->execute();
//            $rows = array();
//            if($resultSet->count()>0){                
//                while($resultSet->next()){
//                    $rows[] = $resultSet->current();
//                }
//            }
//            
//            return $rows;
     }
     
     public function savePersonas(Personas $personas)
     {
         $data = array(
             'nombres' => $personas->nombres,
             'apellidos' => $personas->apellidos,
             'email' => $personas->email,         
             'tipos_documento_id' => $personas->tipos_documento_id,         
             'nrodoc' => $personas->nrodoc,         
             'genero' => $personas->genero,         
             'telefono' => $personas->telefono,         
             'ocupacion' => $personas->ocupacion,         
             'ciudad' => $personas->ciudad,         
             'empresa' => $personas->empresa,         
             'cargo' => $personas->cargo,         
             'aceptaterminos' => $personas->aceptaterminos, 
             'categoria' => $personas->categoria, 
         );

         $id = (int) $personas->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             $persona = $this->getPersonas($id);
             
             $data = array(
                'nombres' => $personas->nombres == '' ? $persona->nombres : $personas->nombres,
                'apellidos' => $personas->apellidos == '' ? $persona->apellidos : $personas->apellidos,
                'email' => $personas->email == '' ? $persona->email : $personas->email,
                'tipos_documento_id' => $personas->tipos_documento_id == '' ? $persona->tipos_documento_id : $personas->tipos_documento_id,
                'nrodoc' => $personas->nrodoc == '' ? $persona->nrodoc : $personas->nrodoc,
                'email' => $personas->email == '' ? $persona->email : $personas->email,
                'genero' => $personas->genero == '' ? $persona->genero : $personas->genero,
                'telefono' => $personas->telefono == '' ? $persona->telefono : $personas->telefono,
                'ocupacion' => $personas->ocupacion == '' ? $persona->ocupacion : $personas->ocupacion,
                'ciudad' => $personas->ciudad == '' ? $persona->ciudad : $personas->ciudad,
                'empresa' => $personas->empresa == '' ? $persona->empresa : $personas->empresa,
                'aceptaterminos' => $personas->aceptaterminos == '' ? $persona->aceptaterminos : $personas->aceptaterminos,            
                'categoria' => $personas->categoria == '' ? $persona->categoria : $personas->categoria, 
            );
             
             if ($persona) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Personas id does not exist');
             }
         }
     }

     public function deletePersonas($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }