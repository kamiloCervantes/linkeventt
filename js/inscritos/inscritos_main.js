/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var inscritos_main = {};


inscritos_main.init = function(){
    $('#grid').bootgrid({
        rowCount:20,
            labels: {
                infos: "Mostrando del {{ctx.start}} al {{ctx.end}} de {{ctx.total}} registros",
                loading: "Cargando...",
                noResults: "¡No se encontraron resultados!",
                refresh: "Actualizar",
                search: "Buscar"
            },

    });
}

$(inscritos_main.init);

