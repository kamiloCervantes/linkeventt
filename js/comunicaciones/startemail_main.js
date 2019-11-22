/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var startemail_main = {};


startemail_main.init = function(){
   $('#tipo_envio').on('change', function(){
       $('.users-error').remove();
        var tipo_envio = $(this).val();
        var rows = '<tr><td colspan="4">No hay resultados</td></tr>';
        switch(tipo_envio){
            case '1':
                var jqxhr = $.get('/dashboard/getrecordatoriospagos', function(data){    
                    if(data && data.pagos.length > 0){
                         rows = '';
                         $('input[name="checkall"]').prop('checked', true);
                         $.each(data.pagos, function(i,v){
                            rows += startemail_main.getusertemplate(v);
                         });  
                    }                                     
                    $('#ulist tbody').html(rows);
                });
                break;
            case '2':
                var jqxhr = $.get('/dashboard/getenviousuarios', function(data){    
                    if(data && data.inscritos.length > 0){
                         rows = '';
                         $('input[name="checkall"]').prop('checked', true);
                         $.each(data.inscritos, function(i,v){
                            rows += startemail_main.getusertemplate(v);
                         });  
                    }                                     
                    $('#ulist tbody').html(rows);
                });
                break;
            case '3':
                var jqxhr = $.get('/dashboard/getenvioentradas', function(data){    
                    if(data && data.entradas.length > 0){
                         rows = '';
                         $('input[name="checkall"]').prop('checked', true);
                         $.each(data.entradas, function(i,v){
                            rows += startemail_main.getusertemplate(v);
                         });  
                    }                                     
                    $('#ulist tbody').html(rows);
                });
                break;
            case '0':
                $('#ulist tbody').html(rows);
                break;
        }
   });
   
   $('input[name="checkall"]').on('change', function(){
       var check = $(this).prop('checked');
       $('input[name="check"]').prop('checked', check);
   });
   
   $('#form-emailmasivo').validate({
        rules: {
            etiqueta: {
                required: true,
            },
            tipo_envio: {
                required: true,
                min: 1
            }           
        }
    });
   
   $('#form-emailmasivo').on('submit', function(e){
      e.preventDefault();
      $('.users-error').remove();
      var usuarios = [];
      $.each($('input[name="check"]:checked'), function(i,v){
          usuarios.push($(v).val());
      });
      if(usuarios.length == 0){
          $('.usuarios_list p').after('<p class="error users-error">Debe seleccionar al menos un usuario</p>');
      }
      if($('#form-emailmasivo').valid() && usuarios.length > 0){
          var jqxhr = $.post('/dashboard/guardarenviomasivo', {
             etiqueta : $('#etiqueta').val(),
             tipo_envio : $('#tipo_envio').val(),
             usuarios: usuarios.join(',')
          });
          
          jqxhr.done(function(data){
              console.log(data);
          });          
          
          jqxhr.fail(function(err){
              console.log(err);
          });
      }
   });
}

startemail_main.getusertemplate = function(data){
    return '<tr><td><input type="checkbox" name="check" value="'+data.user_id+'" checked></td><td>'+data.nombres+' '+data.apellidos+'</td><td>'+data.email+'</td></tr>';
}

$(startemail_main.init);

