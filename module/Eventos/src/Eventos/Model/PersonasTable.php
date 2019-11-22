<?php

namespace Eventos\Model;

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
     
     public function savePersonas(Personas $personas)
     {
         $data = array(
             'nombre_completo' => $personas->nombre_completo,             
             'email' => $personas->email,         
             'telefono' => $personas->telefono,         
         );

         $id = (int) $personas->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             $persona = $this->getPersonas($id);
             
//             $data = array(
//                'nombres' => $personas->nombres == '' ? $persona->nombres : $personas->nombres,
//                'apellidos' => $personas->apellidos == '' ? $persona->apellidos : $personas->apellidos,
//                'email' => $personas->email == '' ? $persona->email : $personas->email,
//                'tipos_documento_id' => $personas->tipos_documento_id == '' ? $persona->tipos_documento_id : $personas->tipos_documento_id,
//                'nrodoc' => $personas->nrodoc == '' ? $persona->nrodoc : $personas->nrodoc,
//                'email' => $personas->email == '' ? $persona->email : $personas->email,
//                'genero' => $personas->genero == '' ? $persona->genero : $personas->genero,
//                'telefono' => $personas->telefono == '' ? $persona->telefono : $personas->telefono,
//                'ocupacion' => $personas->ocupacion == '' ? $persona->ocupacion : $personas->ocupacion,
//                'ciudad' => $personas->ciudad == '' ? $persona->ciudad : $personas->ciudad,
//                'empresa' => $personas->empresa == '' ? $persona->empresa : $personas->empresa,
//                'aceptaterminos' => $personas->aceptaterminos == '' ? $persona->aceptaterminos : $personas->aceptaterminos,            
//                'categoria' => $personas->categoria == '' ? $persona->categoria : $personas->categoria, 
//            );
             
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