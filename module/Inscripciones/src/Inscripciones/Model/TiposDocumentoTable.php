<?php

namespace Inscripciones\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Where;

 class TiposDocumentoTable
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

     public function getLastValue(){
         return $this->tableGateway->lastInsertValue;
     }


 }