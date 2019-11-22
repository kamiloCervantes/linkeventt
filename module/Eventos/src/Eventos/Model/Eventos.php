<?php

namespace Eventos\Model;

 class Eventos
 {
     public $id; 
     public $nombre_evento;
     public $organizador;
     public $fecha_creacion;
     public $fecha_evento;
     public $evento_padre;
     public $alias;
     


    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre_evento = (!empty($data['nombre_evento'])) ? $data['nombre_evento'] : null;
         $this->organizador = (!empty($data['organizador'])) ? $data['organizador'] : null;
         $this->fecha_creacion = (!empty($data['fecha_creacion'])) ? $data['fecha_creacion'] : null;
         $this->fecha_evento = (!empty($data['fecha_evento'])) ? $data['fecha_evento'] : null;
         $this->evento_padre = (!empty($data['evento_padre'])) ? $data['evento_padre'] : null;
         $this->alias = (!empty($data['alias'])) ? $data['alias'] : null;         
     }
 }