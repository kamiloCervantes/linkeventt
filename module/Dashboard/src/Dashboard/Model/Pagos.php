<?php

namespace Dashboard\Model;

 class Pagos
 {
     public $id; 
     public $evento_id;
     public $usuario_id;
     public $medio;
     public $valor;
     public $fecha;
     public $cod_seguimiento;
     public $url;
     public $code;
     public $archivo_id;


    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->evento_id = (!empty($data['evento_id'])) ? $data['evento_id'] : null;
         $this->usuario_id = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
         $this->medio = (!empty($data['medio'])) ? $data['medio'] : null;
         $this->valor = (!empty($data['valor'])) ? $data['valor'] : 0;
         $this->fecha = (!empty($data['fecha'])) ? $data['fecha'] : null;
         $this->cod_seguimiento = (!empty($data['cod_seguimiento'])) ? $data['cod_seguimiento'] : null;
         $this->url = (!empty($data['url'])) ? $data['url'] : null;
         $this->code = (!empty($data['code'])) ? $data['code'] : null;
         $this->archivo_id = (!empty($data['archivo_id'])) ? $data['archivo_id'] : null;
    
     }
 }