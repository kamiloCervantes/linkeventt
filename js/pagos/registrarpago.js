/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var registrarpago = {};


registrarpago.init = function(){
    setInterval(function(){
         var jqxhr = $.post('/dashboard/refrescarsesion');
    }, 300000);
    
    $('#grid').bootgrid({
        rowCount:20,
            labels: {
                infos: "Mostrando del {{ctx.start}} al {{ctx.end}} de {{ctx.total}} registros",
                loading: "Cargando...",
                noResults: "¡No se encontraron resultados!",
                refresh: "Actualizar",
                search: "Buscar"
            },
        formatters: {
                "radio": function(column, row)
                {             
                    return '<input type="radio" name="check" value="'+row.user_id+'">';
                }
            }

    });
    
    $('#form-registrarpago').validate({
        rules: {
            fecha: {
                required: true
            },
            cod_seguimiento: {
                required: true,
                digits: true
            },
            archivo_id: {
                required: true,
                digits: true
            },
            valor: {
                required: true,
                digits: true
            },
            medio: {
                required: true,
                min: 1
            }           
        }
    });
    FilePond.setOptions({
        server: {
            url: '/dashboard/',
            process: {
                url: 'subirarchivo',
                method: 'POST',
                withCredentials: false,
                headers: {},
                timeout: 7000,
                onload: function(res){
                    if(res > 0){
                        $('#archivo_id').val(res);
                    }                    
                },
                onerror: null,
                ondata: null
            }
        }
    });
    $('.comprobante').filepond();
    
    $('.comprobante').filepond('labelIdle', 'Arrastra y suelta tus archivos o <span class="filepond--label-action"> Examina </span>');
    $('.comprobante').filepond('labelInvalidField', 'Se detectaron archivos no válidos.');
    $('.comprobante').filepond('labelFileWaitingForSize', 'Esperando tamaño del archivo');
    $('.comprobante').filepond('labelFileSizeNotAvailable', 'Tamaño no disponible');
    $('.comprobante').filepond('labelFileLoading', 'Cargando');
    $('.comprobante').filepond('labelFileLoadError', 'Error durante carga');
    $('.comprobante').filepond('labelFileProcessing', 'Subiendo');
    $('.comprobante').filepond('labelFileProcessingComplete', 'Subida completa');
    $('.comprobante').filepond('labelFileProcessingAborted', 'Subida cancelada');
    $('.comprobante').filepond('labelFileProcessingError', 'Error procesando archivo');
    $('.comprobante').filepond('labelFileProcessingRevertError', 'Error revirtiendo archivo');
    $('.comprobante').filepond('labelFileRemoveError', 'Error eliminando archivo');
    $('.comprobante').filepond('labelTapToCancel', 'Tap para cancelar');
    $('.comprobante').filepond('labelTapToRetry', 'Tap para reintentar');
    $('.comprobante').filepond('labelTapToUndo', 'Tap para deshacer');
    $('.comprobante').filepond('labelButtonRemoveItem', 'Remover');
    $('.comprobante').filepond('labelButtonAbortItemLoad', 'Abortar');
    $('.comprobante').filepond('labelButtonRetryItemLoad', 'Reintentar');
    $('.comprobante').filepond('labelButtonAbortItemProcessing', 'Cancelar');
    $('.comprobante').filepond('labelButtonUndoItemProcessing', 'Deshacer');
    $('.comprobante').filepond('labelButtonRetryItemProcessing', 'Reintentar');
    $('.comprobante').filepond('labelButtonProcessItem', 'Subir archivo');

  
    $('#form-registrarpago').on('submit', function(e){
      e.preventDefault();
      $('.users-error').remove();
      var usuario = parseInt($('input[name="check"]:checked').val());
      var medio = $('#medio').val();
      var valor = $('#valor').val();
      var fecha = $('#fecha').val();
      var cod_seguimiento = $('#cod_seguimiento').val();
      var archivo_id = $('#archivo_id').val();
      
      if(!(usuario > 0)){
          $('.usuarios_list p').after('<p class="error users-error">Debe seleccionar al menos un usuario</p>');
      }

      
      if($('#form-registrarpago').valid()){
          var jqxhr = $.post('/dashboard/guardarpago', {
             medio : medio,
             valor : valor,
             fecha : fecha,
             cod_seguimiento : cod_seguimiento,
             comprobante : archivo_id,
             usuario_id : usuario
          });
          
          jqxhr.done(function(data){
              console.log(data);   
              alert("Ticket generado!");
          });          
          
          jqxhr.fail(function(err){
              console.log(err);
          });
      }
   });
}

$(registrarpago.init);

