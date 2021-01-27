<style>
    .ajax-loader{
         width: 20px;
        height: 20px;
        margin-left: 10px;
    }
</style>
<br><h4>Seleccionar Datos Boka del ticket:</h4>

<form action="<?php echo base_url() ?>index.php/gestionBoka/ticket" method="post">
  <div class="form-group col-md-4">
    <label for="numTicket">Número ticket tienda:</label>
    <input type="number" class="form-control" id="numTicket" aria-describedby="ticketHelp" placeholder="Introducir núm ticket">
  </div>
  <div class="form-group col-md-12 hide"  id="seleccionarFechas"> 
      <label for="numTicket">Seleccionar fecha:</label>
      <div class="form-group" id="fechas">
    </div>
  </div>
  <div class="form-group col-md-12">
    
      </div>
    <br>
  <div class="form-group col-md-8 " > 
  <button type="submit" class="btn btn-default procesar">Procesar <img class=" ajax-loader hide"   src="<?php echo base_url('images/ajax-loader.gif') ?>"></a></button>
  </div>
</form>

<script>
$(document).ready(function(){
    
    $('#numTicket').focus()

    $('#numTicket').keyup(function(){
        var numTicket=$('#numTicket').val()
        if(numTicket!=0){
            $('#mostrarTodo').prop('checked',false)
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/gestionBoka/getFechasTicket",
            data: {numTicket:numTicket},
            success: function(datos){
               // alert (datos)
               var datos=$.parseJSON(datos)
             //  if(datos.length>0){
                   $('#fechas').empty()
                   $('#seleccionarFechas').removeClass('hide')
                  // $('#seleccionarFechas').css('visibility','visible')
               
               
               $.each(datos,function(index,value){
               
                   $('#fechas').append('<div class="form-check"><input class="form-check-input" type="radio" name="bonuTicket" id="radios'+index+'" value="'+value.BONU+'" checked><label class="form-check-label" for="gridRadios1"> &nbsp;&nbsp;'+value.ZEIS+'</label></div>')
               })
              // }
   
            },
            error: function(){
                alertaError("Información importante","Error al buscar fecha/s del ticket. Informar");
            }
        })      
    });
    
    $('#mostrarTodo').change(function(){
         if ($(this).is(':checked')) {
             
             $('#fechas').empty()
             $('#seleccionarFechas').css('visibility','hidden')
             $('#numTicket').val("")
        }
    })
    
    $('.procesar').click(function(e){
        //e.preventDefault()
        $('.ajax-loader').removeClass('hide')
    })
    


})
    
    </script>


