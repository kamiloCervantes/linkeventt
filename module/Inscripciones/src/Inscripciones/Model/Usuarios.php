<?php

namespace Inscripciones\Model;

 class Usuarios
 {
     public $id; 
     public $username;
     public $passwd;
     public $activo;
     public $personas_id;
     public $rol;


    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->username = (!empty($data['username'])) ? $data['username'] : null;
         $this->passwd = (!empty($data['passwd'])) ? $data['passwd'] : null;
         $this->activo = (!empty($data['activo'])) ? $data['activo'] : null;
         $this->personas_id = (!empty($data['personas_id'])) ? $data['personas_id'] : null;
         $this->rol = (!empty($data['rol'])) ? $data['rol'] : null;
     }
 }