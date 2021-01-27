

<h3>Seleccionar datos Boka a mostrar</h3>
<?php $numTickets=sizeof($hoy) ;
       
        ?>

<div class="row">
    <div class="col-xs-12 col-md-8">
         <?php echo form_open('tickets/mostrarTicket',array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?>

         
<ul style="list-style:none;" >
    <li>
        <?php echo form_radio('hoy','hoy',true,'id="hoy"').' Hoy'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','ayer',false,'id="ayer"').' Ayer'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Semana actual',false,'id="semanaActual"').' Semana actual'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Semana_anterior',false,'id="semanaAnterior"').' Semana anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Mes actual',false,'id="mesActual"').' Mes actual'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Mes anterior',false,'id="mesAnterior"').' Mes anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Año actual',false,'id="añoActual"').' Año actual'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Año anterior',false,'id="añoAnterior"').' Año anterior'; ?>
    </li>
    
</ul>
  
        
        <div class="form-group">
            <div class="col-sm-2">
            <?php echo form_label('Un día: ', 'dia', array('class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => '', 'type' => 'date',)) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2">
            <?php echo form_label('Desde fecha: ', 'inicio', array('class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => '', 'type' => 'date',)) ?>
            </div>
        </div >     
        
           <div class="col-sm-2">
            <?php echo form_label('Hasta fecha: ', 'final', array('class' => 'control-label ')); ?>
           </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => '', 'type' => 'date',)) ?>
            </div>
           
  <!--      
        <div class="row">
    <div class="col-xs-12 col-md-8">
    <?php
    
    
    $data = array(
        'name'          => 'inicio',
        'id'            => 'inicio',
        'value'         => '',
        'type'=>'date',
       
        );

    echo  "Desde fecha: ".form_input($data);
   
?>
    </div></div>


    <div class="col-xs-12 col-md-8">
<?php
    $data = array(
        'name'          => 'final',
        'id'            => 'final',
        'value'         => '',
        'type'=>'date'
        );

    echo   "Hasta fecha: ".form_input($data);
    ?>
    
    </div>
        
        -->
    <div class="row">
    <div class="col-xs-12 col-md-8">    
    Núm Tickets: <span id="numTickets"><?php echo $numTickets ?></span>
    </div></div>
    
<div class="row">
    <div class="col-xs-12 col-md-8">
    Seleccionar un Ticket: 
    <select id="tickets" name="tickets" class="btn btn-default btn-primary" >
        <?php if ($numTickets==0){ ?>
            <option value='0'>Ningún ticket en estas fechas</option>
            
        <?php }else{
            
        }
        ?>
    </select>
</div></div>
        
    <div class="pasarTickets">  
    </div>
<br />
  
        
<button id="mostrarTicket" style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
  <span class="" aria-hidden="true"></span> Mostrar Ticket
</button>
    

  <?php
    echo \form_close();
?> 
    
 <input type="hidden" name="inicio" value="" class="inicioR">
 <input type="hidden" name="final" value="" class="finalR">

  
    </div>
</div>    
<br /><br />
<!-- set up the modal to start hidden and fade in and out -->
<div id="myModal" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Aviso</h4>
            </div>
            <!-- dialog body -->
            <div class="modal-body">
                No se ha seleccionado <strong>ningún ticket.</strong><br />
                Selecciona un rango de fechas apropiado y después seleccione un ticket.
            </div>
            <!-- dialog buttons -->
            <div class="modal-footer"><button type="button" class="btn btn-primary " data-dismiss="modal" >Cerrar</button></div>
        </div>
    </div>
</div>    


<script>
$(document).ready(function () {
    
    $('#hoy').attr('selected','selected');
   
    $('#mostrarTicket').click(function(e){
        var ticket=$('select#tickets').val();
        if (ticket==0){
            e.preventDefault();
           
            $("#myModal").modal({                    // wire up the actual modal functionality and show the dialog
      "backdrop"  : "static",
      "keyboard"  : true,
      "show"      : true                     // ensure the modal is shown immediately
    });
            
        }
        
        
        
       //alert($('select#tickets').val());
    })
    $('#dia').change(function(e){
        var dia=($(this).val());
        $('#inicio').val(dia);
        $('#final').val(dia);
        var periodo='#'+$(this).attr('id');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/getTickets", 
            data: $('form.fechas').serialize(),
            success: function(datos){
                //var periodo="#".this);
                $(periodo).attr('checked','checked');
                var valores=$.parseJSON(datos);
                var num =valores['num'];
                $('option').remove();
               // alert('hola'+num);
                if (num==0){
                    $('<option value='+'0'+' >Ningún ticket en estas fechas</option>').appendTo('select#tickets');
                }else {
                var tickets=valores['tickets'];
                $('#numTickets').html(num); 
                
                $.each(tickets, function( index, value ) {
                   $('<option value='+index+' >'+value+'</option>').appendTo('select#tickets');
                 //  $('<input type="hidden" value='+index+' name='+index+' >').appendTo('.pasarTickets');
                  $('<input type="hidden" value="'+index+'" name="ticketsPeriodo[]" >').appendTo('.pasarTickets');
                   
                });
            }
                
        },
            error: function(){
                alert("fallo");
            }
        });
    })
    $('#mesActual, #añoActual, #hoy, #ayer, #semanaActual, #semanaAnterior, #añoAnterior, #mesAnterior').click(function(e){
        
        var periodo='#'+$(this).attr('id');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/getTickets", 
            data: $('form.fechas').serialize(),
            success: function(datos){
                //var periodo="#".this);
                $(periodo).attr('checked','checked');
                var valores=$.parseJSON(datos);
                var num =valores['num'];
                $('option').remove();
               // alert('hola'+num);
                if (num==0){
                    $('<option value='+'0'+' >Ningún ticket en estas fechas</option>').appendTo('select#tickets');
                }else {
                var tickets=valores['tickets'];
                $('#numTickets').html(num); 
                
                $.each(tickets, function( index, value ) {
                   $('<option value='+index+' >'+value+'</option>').appendTo('select#tickets');
                 //  $('<input type="hidden" value='+index+' name='+index+' >').appendTo('.pasarTickets');
                  $('<input type="hidden" value='+index+' name="ticketsPeriodo[]" >').appendTo('.pasarTickets');
                   
                });
            }
                
        },
            error: function(){
                alert("fallo");
            }
        });
   })
    
    $("button#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/cliente", //process para grabar
            data: $('form.cliente').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                var linea=obj.linea;
                var cliente=obj.cliente;
                var empresa=obj.empresa;
                //alert(linea+' '+familia);
                $('#myModal').modal('hide'); 
              $('input.linea[value='+linea+']').parent().prev().html(empresa);
              $('input.linea[value='+linea+']').parent().prev().prev().html(cliente);
            },
            error: function(){
                alert("fallo");
            }
        });
        
    });
    
    
})
</script>  

