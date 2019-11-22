<?php

namespace Eventos\Model;

 class Errorlog
 {
     public $id; 
     public $msg;
     public $fecha_creacion;
     
    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->msg     = (!empty($data['msg'])) ? $data['msg'] : null;
         $this->fecha_creacion     = (!empty($data['fecha_creacion'])) ? $data['fecha_creacion'] : null;
   

     }
 }