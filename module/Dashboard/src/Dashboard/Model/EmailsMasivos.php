<?php

namespace Dashboard\Model;

 class EmailsMasivos
 {
     public $id; 
     public $evento_id;
     public $etiqueta;
     public $emisor_id;
     public $fecha_envio;
     public $tipo_envio;   


    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $now = new \DateTime('now');
         $hoy = $now->format('Y-m-d h:i:s');
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->evento_id = (!empty($data['evento_id'])) ? $data['evento_id'] : null;
         $this->etiqueta = (!empty($data['etiqueta'])) ? $data['etiqueta'] : null;
         $this->emisor_id = (!empty($data['emisor_id'])) ? $data['emisor_id'] : null;
         $this->fecha_envio = (!empty($data['fecha_envio'])) ? $data['fecha_envio'] : $hoy;
         $this->tipo_envio = (!empty($data['tipo_envio'])) ? $data['tipo_envio'] : null;

    
     }
 }