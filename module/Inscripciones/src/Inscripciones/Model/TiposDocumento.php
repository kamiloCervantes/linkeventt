<?php

namespace Inscripciones\Model;

 class TiposDocumento
 {
     public $id;
     public $tipo_documento;
     
     
    public function __construct($data = null) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->tipo_documento = (!empty($data['tipo_documento'])) ? $data['tipo_documento'] : null;
        
        
     
     }
 }