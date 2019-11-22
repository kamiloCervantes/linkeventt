<?php

namespace Services\Model;

 class Documentos
 {
     public $id;
     public $nombre_documento;
     public $descripcion;
     public $url;
     public $blob_content;
     public $mime_type;
     public $tipo_almacenamiento;
     public $hash;
     public $referrer;
   
   
     
     
    public function __construct($data) {
        $this->exchangeArray($data);
    }

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre_documento = (!empty($data['nombre_documento'])) ? $data['nombre_documento'] : null;
         $this->descripcion = (!empty($data['descripcion'])) ? $data['descripcion'] : null;
         $this->url = (!empty($data['url'])) ? $data['url'] : null;
         $this->blob_content = (!empty($data['blob_content'])) ? $data['blob_content'] : null;
         $this->mime_type = (!empty($data['mime_type'])) ? $data['mime_type'] : null;
         $this->tipo_almacenamiento = (!empty($data['tipo_almacenamiento'])) ? $data['tipo_almacenamiento'] : null;
         $this->hash = (!empty($data['hash'])) ? $data['hash'] : null;
         $this->referrer = (!empty($data['referrer'])) ? $data['referrer'] : null;
        
     
     }
 }