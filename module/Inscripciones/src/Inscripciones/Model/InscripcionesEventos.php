<?php

namespace Inscripciones\Model;

 class InscripcionesEventos
 {
     public $id; 
     public $fecha_inscripcion;
     public $personas_id;
     public $eventos_id;
     public $confirmar;
     public $asistencia;
    
  
     
     
    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->fecha_inscripcion = (!empty($data['fecha_inscripcion'])) ? $data['fecha_inscripcion'] : null;
         $this->personas_id = (!empty($data['personas_id'])) ? $data['personas_id'] : null;
         $this->eventos_id = (!empty($data['eventos_id'])) ? $data['eventos_id'] : null;
         $this->confirmar = (!empty($data['confirmar'])) ? $data['confirmar'] : null;
         $this->asistencia = (!empty($data['asistencia'])) ? $data['asistencia'] : 0;
         

     }
 }