<?php

namespace Dashboard\Model;

 class EmailsMasivosDetalle
 {
     public $id; 
     public $usuario_id;
     public $emails_masivos_id;
     public $fecha_envio;



    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $now = new \DateTime('now');
         $hoy = $now->format('Y-m-d h:i:s');
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->emails_masivos_id = (!empty($data['emails_masivos_id'])) ? $data['emails_masivos_id'] : null;
         $this->usuario_id = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
         $this->fecha_envio = (!empty($data['fecha_envio'])) ? $data['fecha_envio'] : $hoy;
     }
 }