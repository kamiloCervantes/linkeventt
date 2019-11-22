<?php

namespace Dashboard\Model;

 class Archivos
 {
     public $id; 
     public $nombre;
     public $ruta;
     public $fecha_creacion;



    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $now = new \DateTime('now');
         $hoy = $now->format('Y-m-d h:i:s');
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre = (!empty($data['nombre'])) ? $data['nombre'] : null;
         $this->ruta = (!empty($data['ruta'])) ? $data['ruta'] : null;
         $this->fecha_creacion = (!empty($data['fecha_creacion'])) ? $data['fecha_creacion'] : $hoy;
     }
 }