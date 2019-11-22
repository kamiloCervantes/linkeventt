<?php

namespace Inscripciones\Model;

 class Personas
 {
     public $id; 
     public $nombres;
     public $apellidos;
     public $tipos_documento_id;
     public $nrodoc;
     public $genero;
     public $telefono;
     public $ocupacion;
     public $ciudad;
     public $empresa;
     public $cargo;
     public $email;
     public $aceptaterminos;
     public $categoria;
     
    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombres = (!empty($data['nombres'])) ? $data['nombres'] : null;
         $this->apellidos = (!empty($data['apellidos'])) ? $data['apellidos'] : null;
         $this->tipos_documento_id = (!empty($data['tipos_documento_id'])) ? $data['tipos_documento_id'] : null;
         $this->nrodoc = (!empty($data['nrodoc'])) ? $data['nrodoc'] : null;
         $this->genero = (!empty($data['genero'])) ? $data['genero'] : null;
         $this->telefono = (!empty($data['telefono'])) ? $data['telefono'] : null;
         $this->ocupacion = (!empty($data['ocupacion'])) ? $data['ocupacion'] : null;
         $this->ciudad = (!empty($data['ciudad'])) ? $data['ciudad'] : null;
         $this->empresa = (!empty($data['empresa'])) ? $data['empresa'] : null;
         $this->cargo = (!empty($data['cargo'])) ? $data['cargo'] : null;
         $this->email = (!empty($data['email'])) ? $data['email'] : null;
         $this->aceptaterminos = (!empty($data['aceptaterminos'])) ? $data['aceptaterminos'] : null;
         $this->categoria = (!empty($data['categoria'])) ? $data['categoria'] : null;

     }
 }