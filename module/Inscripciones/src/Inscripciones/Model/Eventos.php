<?php

namespace Inscripciones\Model;

 class Eventos
 {
     public $id; 
     public $nombre_evento;
     


    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre_evento = (!empty($data['nombre_evento'])) ? $data['nombre_evento'] : null;
         
     }
 }