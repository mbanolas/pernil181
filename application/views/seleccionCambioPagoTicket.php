<style type="text/css">
    /*
    .row{
       
    }
    .form-horizontal .control-label{
        padding-top: 0;
    } 
    */
</style>

<?php
echo '<h3>Modificar Sistema Pago del Ticket y/o Cliente</h3><hr>';

?>
    <form class='numTicket form-horizontal' action='' method='post' >
<div class="row">
        
    <div class="col-md-2">
        <?php echo form_label('Núm Ticket: ', '', array('class' => 'control-label ','style'=>'font-size:18px')); ?>
    </div>
    <div class="col-md-2">
        <?php  echo form_input(array('type'=>'text','name'=>'ticket','id'=>'ticket','class'=>'form-control','value'=>'' )) ?>
    </div>
    <div class="col-md-1">
<button id="buscar" style="display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini" >
            <span class="" aria-hidden="true"></span> Buscar
        </button>
    </div>
    <div class="col-md-7" id='error' style="color:red"></div>
</div>

<div id="datosTicket">

<div class="row"><div class="col-md-12"><p>&nbsp;</p></div></div>
<div class="row">
        <div class="col-md-2">
                  <?php echo form_label('Núm Ticket: ', '', array('class' => 'control-label ','style'=>'font-size:18px')); ?>
        </div>
        <div class="col-md-4">
                  <?php echo form_label(' ', 'numTicket', array('id' => 'numTicket','style'=>'font-size:18px')); ?>
        </div>
    
    
</div>
<div class="row">
        <div class="col-md-2">
                  <?php echo form_label('Núm Referencia: ', '', array('class' => 'control-label ','style'=>'font-size:18px')); ?>
        </div>
        <div class="col-md-10">
                  <?php echo form_label(' ', 'BONU', array('id' => 'referencia','style'=>'font-size:18px')); ?>
        </div>
</div>   
<div class="row">
        <div class="col-md-2">
                  <?php echo form_label('Importe: ', '', array('class' => 'control-label ','style'=>'font-size:18px')); ?>
        </div>
        <div class="col-md-10">
                  <?php echo form_label(' ', 'BONU', array('id' => 'importe', 'style'=>'font-size:18px')); ?>
        </div>
</div> 

 <div class="row">
        <div class="col-md-2">
                  <?php echo form_label('Cliente: ', '', array('class' => 'control-label ','style'=>'font-size:18px')); ?>
        </div>
        <div class="col-md-6">
                  <?php  echo form_dropdown('cliente',array(),'4',array('data-toggle'=>"dropdown",  'class'=>'form-control btn btn-default dropdown-toggle','id'=>'clientes')); ?>
            <input type="hidden" id="idBokaCliente" name="idBokaCliente" value="">
        </div>
</div> 
<div class="row" style="padding-top:12px">
        <div class="col-md-2">
                  <?php echo form_label('Forma de Pago: ', '', array('class' => 'control-label ','style'=>'font-size:18px')); ?>
            <input type="hidden" id="idBokaFormaPago" name="idBokaFormaPago" value="">
        </div>
        <div class="col-md-2">
                  <?php  echo form_dropdown('formaPago',array('1'=>'Metálico','2'=>'Cheque','3'=>'Vale','4'=>'Tarjeta de Crédito','5'=>'Transferencia bancaria','6'=>'A cuenta'),'4',array('data-toggle'=>"dropdown",  'class'=>'form-control btn btn-default dropdown-toggle','id'=>'formaPagos')); ?>
        </div>
</div> 

<div class="row" style="padding-top:12px">
        <div class="col-md-4" style="text-align: center;padding-top:20px;">
        <button id="guardar" style="display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini" >
            <span class="" aria-hidden="true"></span> Guardar cambios
        </button>
    </div>
</div> 
</div>
   </form>
        
<br />
<br />

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTickets').addClass('btn-primary');
  $('#menuCambioPagoTickets').addClass('btn-primary');  
})
</script>

 <script>
$(document).ready(function(){
    
    $('#datosTicket').hide();
    
    formmodified = 0;
    $('select#clientes').change(function(){
        formmodified = 1;
    });
    $('select#formaPagos').change(function(){
        formmodified = 1;
    });
    
    
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (formmodified == 1) 
        {
            return "No ha guardado los cambios.";
        }
    }
    
    $('#ticket').click(function(e){
        document.getElementById("error").innerHTML = "";
        $('#datosTicket').hide();
    })
    
    
    
    $('#buscar').click(function(e){
        
        $('#datosTicket').hide();
        
       if(isNaN(parseInt($('#ticket').val()))){
           document.getElementById("error").innerHTML = "Introduzca un núm ticket válido.";
           return false;
       }
            
        
        if ($('#ticket').val().trim()=="" || parseInt($('#ticket').val())==0){
            document.getElementById("error").innerHTML = "Introduzca un número de ticket.";
            return false;
        }
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/buscarDatosTicket", 
            data: $('form.numTicket').serialize(),
            success: function(datos){
                alert( datos)
                try {
                var valores=$.parseJSON(datos);
                $('#clientes').children('option').remove();
               
                var numCliente=valores['clienteTicket'][0]['SNR1']
                if(numCliente % 10!=7){
                    numCliente=-1;
                }
                else{
                    numCliente=parseInt(numCliente/10)
                }
                var totalTicket=valores['clienteTicket'][0]['BT20']
                var tipoPago=valores['tipoPago'][0]['PAR1']
                
                
                 $('#formaPagos').children('option').removeAttr('selected'); 
                  $('#formaPagos').children('option[value="'+tipoPago+'"]').attr('selected','selected'); 
                 
                 $('#importe').html((totalTicket/100).toFixed(2))
                 $('#referencia').html(valores['tipoPago'][0]['BONU'])
                 $('#numTicket').html(valores['clienteTicket'][0]['RASA'])
                 
                 $('#idBokaCliente').val(valores['clienteTicket'][0]['id'])
                 $('#idBokaFormaPago').val(valores['tipoPago'][0]['id'])
                 
                 //alert(valores['clienteTicket'][0]['id'])
                 //alert(valores['tipoPago'][0]['id'])

                $('<option value="-1" >Sin Cliente asignado</option>').appendTo('select#clientes');
                var selected=""
                $.each(valores['clientes'],function(index,value){
                    selected="";
                    if(value['id_cliente']==numCliente) selected="selected='selected'"
                    $('<option '+selected+' value='+value['id_cliente']+' >'
                            +value['empresa']+' ('+value['nombre']+') '+value['id_cliente']+'</option>').appendTo('select#clientes');
                })
                
                
                
                $('#datosTicket').show();
                
                
                }
                catch(err) {
                    //alert("Algo va mal"+err.message);
                    document.getElementById("error").innerHTML = "Este número de ticket no existe.";
                }
                
            
                
               
                        },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        //alert("Hola pendiene de implementar guardar modificaciones");
    })
    
    
     $('#guardar').click(function(e){
        
        $('#datosTicket').hide();
        formmodified = 0;
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/grabarDatosTicket", 
            data: $('form.numTicket').serialize(),
            success: function(datos){
                alert($.parseJSON(datos)['mensaje']);
                // var valores=$.parseJSON(datos);
               // alert(valores['mensaje'])
                //alert('Se ha grabado el cambio de pago correctamente')
                        },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        //alert("Hola pendiene de implementar guardar modificaciones");
    })
})
</script>

