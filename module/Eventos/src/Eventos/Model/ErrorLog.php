<?php

namespace Eventos\Model;

 class Personas
 {
     public $id; 
     public $nombre_completo;
     public $telefono;
     public $email;
     
    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre_completo = (!empty($data['nombre_completo'])) ? $data['nombre_completo'] : null;
         $this->telefono = (!empty($data['telefono'])) ? $data['telefono'] : null;
         $this->email = (!empty($data['email'])) ? $data['email'] : null;

     }
 }