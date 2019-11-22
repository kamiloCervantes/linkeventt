/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var pagos_main = {};


pagos_main.init = function(){
    
    $('#grid').bootgrid({
        rowCount:20,
            labels: {
                infos: "Mostrando del {{ctx.start}} al {{ctx.end}} de {{ctx.total}} registros",
                loading: "Cargando...",
                noResults: "¡No se encontraron resultados!",
                refresh: "Actualizar",
                search: "Buscar"
            },
            sortable: false,
            formatters: {
                "file_download": function(column, row)
                {      
                    return "<a href='"+row.url+"' target='_blank'>Descargar</a>";
                },
                "enviar_ticket": function(column, row)
                {      
                    return "<a href='#' data-id='"+row.ticket+"' class='enviar_ticket'>Ticket</a>";
                },
            }

    }).on('loaded.rs.jquery.bootgrid', function(e){
        $('.enviar_ticket').on('click',function(ev){
           ev.preventDefault();
           var id = $(this).data('id');
           var jqxhr = $.post('/dashboard/enviarticket', {
               id : id
           });
           
           jqxhr.done(function(){
              alert("El mensaje fue enviado correctamente."); 
           });
        });
    });
    
   
}

$(pagos_main.init);

