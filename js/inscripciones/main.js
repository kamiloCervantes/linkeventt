/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var inscripciones = {};


inscripciones.init = function(){
    $('#form-inscripcion').validate({
        rules: {
            nombres: {
                required: true,
            },
            apellidos: {
                required: true,
            },
            tipodoc: {
                required: true,
            },
            nrodoc: {
                required: true,
            },
            genero: {
                required: true,
            },
            telefono: {
                required: true,
                digits: true
            },
            ocupacion: {
                required: true,
            },
            ciudad: {
                required: true,
            },
            empresa: {
                required: true,
            },
            cargo: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            email2: {
                required: true,
                email: true,
                equalTo: "#email"
            },
        }
    });
    
    
    $('#inscribirme').on('click', function(e){
        e.preventDefault();
        if($('#form-inscripcion').valid()){
            $('#form-inscripcion')[0].submit();
        }else{
            return false;
        }
        
    });              
}


$(inscripciones.init);

