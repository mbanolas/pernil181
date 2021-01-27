<style type="text/css">

   .col-md-11{
       /*  border:1px solid red; */
    }
    

</style>
<h3><?php echo $titulo ?></h3>
<input type="hidden" value="<?php echo $idMenu ?>" id="idMenu">
<hr>
<div class="row" >
    <div class="col-md-6">
        <div class="row-full" >
            <div class="col-md-1">
             
            </div>
           <?php echo form_open($segmentos ,array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?>

            <div class="col-md-11">
                <h4>Introducir fecha y seleccionar ticket procesado</h4>

                <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('Fecha: ', '', array('class' => 'control-label ',)); ?>
                    </div>
                    <div class="col-md-5">
                    <?php echo form_input(array('type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'class' => 'form-control', 'value' => '')) ?>
                    </div>
                    <div class="col-md-4" id="num1"></div>
                </div>
                <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('Seleccionar Ticket: ', '', array('class' => 'control-label ','style'=>'text-align:left;padding-top:0px;')); ?>
                    </div>
                    <div class="col-md-7">
                        <?php echo form_dropdown('ticket', array(), '', array('data-toggle' => "dropdown", 'class' => ' form-control btn-large btn btn-default dropdown-toggle', 'id' => 'tickets')) ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('', '', array('class' => 'control-label ',)); ?>
                    </div>
                    <div class="col-md-7">
                        <button id="mostrarTicket" style="display: inline; margin-top: 5px" type="submit" class="btn btn-primary btn-mini" >
                            <span class="" aria-hidden="true"></span> Mostrar Ticket
                        </button>                    
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('', '', array('class' => 'control-label ',)); ?>
                    </div>
                    <div class="col-md-7">
                         <?php echo form_label('', '', array('id'=>'erroresFecha',   'class' => 'control-label ',)); ?>
                   
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
            <?php echo \form_close(); ?>
 
            
            
            
        </div>
    </div>
    <div class="col-md-6">
<div class="row-full" >
               <?php echo form_open($segmentos ,array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?>
            <div class="col-md-11">
              <h4>Introducir núm ticket y seleccionar ticket procesado</h4>
                <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('Núm ticket: ', '', array('class' => 'control-label ',)); ?>
                    </div>
                    <div class="col-md-5">
                    <?php echo form_input(array('type' => 'text', 'name' => 'numTicket', 'id' => 'numTicket', 'class' => 'form-control', 'value' => '')) ?>
                    </div>
                    <div class="col-md-4" id="num2"></div>
                </div> 
                <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('Seleccionar Ticket: ', '', array('class' => 'control-label ','style'=>'text-align:left;padding-top:0px;')); ?>
                    </div>
                    <div class="col-md-7">
                        <?php echo form_dropdown('ticket', array(), '', array('data-toggle' => "dropdown", 'class' => ' form-control btn-large btn btn-default dropdown-toggle', 'id' => 'fechas')) ?>
                    </div>
                </div>
              <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('', '', array('class' => 'control-label ',)); ?>
                    </div>
                    <div class="col-md-7">
                        <button id="mostrarTicket2" style="display: inline;margin-top: 5px" type="submit" class="btn btn-primary btn-mini" >
                            <span class="" aria-hidden="true"></span> Mostrar Ticket
                        </button>                    
                    </div>
                    <div class="col-md-3"></div>
                </div>
              <div class="row" >
                    <div class="col-md-3">
                    <?php echo form_label('', '', array('class' => 'control-label ',)); ?>
                    </div>
                    <div class="col-md-7">
                        <?php echo form_label('', '', array('id'=>'erroresNumTicket','class' => 'control-label ',)); ?>
                   
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
                <?php echo \form_close(); ?>

            <div class="col-md-1">
                
            </div>
        </div>    </div>
</div>


    
    <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTickets').addClass('btn-primary');
  $('#'+$('#idMenu').val()).addClass('btn-primary');  
})
</script>
    <script>
    $(document).ready(function(){
        
       // $('#ticketsFecha').hide();
       
    $('select#ticket').change(function(){$('#error').html('')})   
    /*    
    var ticket;
    
    $('#solicitud').hide();
    $('#ticket').click(function(){
        $('#error').html('')
    })
    */
   
   var cliente="";
   
   $('#fecha').click(function(){
      
        $('#erroresFecha').html('')
        $('#erroresNumTicket').html('')
        $('option').remove();
        $('#numTicket').val('')
        $('#num1, #num2').html('')
    })
    
    $('#numTicket').click(function(){
        $('#erroresFecha').html('')
        $('#erroresNumTicket').html('')
        $('option').remove();
        $('#fecha').val('')
        $('#num1, #num2').html('')

        
    })
    
    
    
    $('#fecha').change(function(e){
        e.preventDefault();
        var fecha=$('#fecha').val();
       // alert('fecha cambio '+fecha);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/facturas/getTicketsDiaFecha", 
            data: {'fecha':fecha},
            success: function(datos){

                var tickets=$.parseJSON(datos);
                
                
               // alert('salida de getTicketsDia '+tickets)
                $('#num1').html(tickets.length+' tickets')

                if(!tickets.length) $('#erroresFecha').html('No existen tickets en esa fecha. Compruebe que está cargado el Boka')
                $.each(tickets, function( index, value ) {
                    var n=tickets[index].indexOf(' ')+1
                    var num= tickets[index].substring(0,n)
                    var dia=tickets[index].substring(n+8,n+10)
                    var mes=tickets[index].substring(n+5,n+7)
                    var año=tickets[index].substring(n+0,n+4)
                    var resto=tickets[index].substring(n+10)
                    var fechaEuropea=num+dia+'/'+mes+'/'+año+resto;
                   $('<option value="'+tickets[index]+'" >'+fechaEuropea+'</option>').appendTo('select#tickets');
                });
                $('#ticketsFecha').show();
            },
            error: function(){
                alert("Error en el proceso. Informar a MABA");
            }
        });
       
            
    });
    
    
     $('#numTicket').change(function(e){
     
        e.preventDefault();
        var numTicket=$('#numTicket').val();
        //alert(numTicket)
        $.ajax({
            type: "POST",
           // url: "<?php echo base_url() ?>"+"index.php/facturas/getTicketsDiaFecha", 
           url: "<?php echo base_url() ?>"+"index.php/facturas/getFechasNumTicket", 
            data: {'numTicket':numTicket},
            success: function(datos){
                //alert(datos)
               
                var fechas=$.parseJSON(datos);
                $('#num2').html(fechas.length+' tickets')
                if(!fechas.length) $('#erroresNumTickets').html('Este núm tickets no existe. Compruebe que está cargado el Boka')
                $.each(fechas, function( index, value ) {
                   var n=fechas[index].indexOf(' ')+1
                    var num= fechas[index].substring(0,n)
                    var dia=fechas[index].substring(n+8,n+10)
                    var mes=fechas[index].substring(n+5,n+7)
                    var año=fechas[index].substring(n+0,n+4)
                    var resto=fechas[index].substring(n+10)
                    var fechaEuropea=num+dia+'/'+mes+'/'+año+resto;
                   $('<option value="'+fechas[index]+'" >'+fechaEuropea+'</option>').appendTo('select#fechas');
                });
                $('#ticketsFecha').show();
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
       
            
    });
    
    
    $('#añadir').click(function(e){
        e.preventDefault();
        var ticket=$('#tickets').val();
        alert('ticket Mostrar Ticket '+ticket)
       // alert(cliente);
        //obtenemos datos ticket
        
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/mostrarUnTicket", 
            data: {ticket:ticket},
            success: function(datos){
                //alert(datos);
                var resultado=$.parseJSON(datos);
                //alert(resultado['rasa'])
                if(resultado['rasa']!=""){
                   $('<li>Ticket núm: '+ticket+' - Importe: '+resultado['importe']+' € </li>').appendTo('ul#ticketsEnFactura');
                   $('<input type="hidden" name="ticketsFactura[]" value="'+ticket+'">').appendTo('div#ticketsEnFacturaInputs');
                }
                else{
                    $('#error').html('Este ticket NO está asignado a un cliente. Introduzca el cliente en ticket y volver a generar factura.')
                }
             },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        
    });  
    
   

$('#mostrarTicket').click(function(e){
        var ticket=$('#tickets').val();
        $('#erroresNumTicket').html('')
        if(!ticket){
            $('#erroresFecha').html('No se ha seleccionado ningún ticket.')
            e.preventDefault()
        }
    })
    
    $('#mostrarTicket2').click(function(e){
        
        var ticket=$('#fechas').val();
        $('#erroresFecha').html('')
       // alert(ticket)
        if(!ticket){
            $('#erroresNumTicket').html('No se ha seleccionado ningún ticket.')
            e.preventDefault()
        }
    })
    
    
    });
    </script>
