

<h3>Seleccionar periodo Listado Diferencias Importes Ventas</h3>

<div class="row">
    <div class="col-xs-12 col-md-8">
         <?php echo form_open('',array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?>

        <!-- botones selectores periodos --> 
        <ul style="list-style:none;" >
    <li>
        <?php echo form_radio('periodo','hoy',$this->session->periodo=='hoy'?true:false,'id="hoy"').' Hoy'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','ayer',$this->session->periodo=='ayer'?true:false,'id="ayer"').' Ayer'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Semana actual',$this->session->periodo=='Semana actual'?true:false,'id="semanaActual"').' Semana actual'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Semana_anterior',$this->session->periodo=='Semana_anterior'?true:false,'id="semanaAnterior"').' Semana anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Mes actual',$this->session->periodo=='Mes actual'?true:false,'id="mesActual"').' Mes actual'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Mes anterior',$this->session->periodo=='Mes anterior'?true:false,'id="mesAnterior"').' Mes anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Año actual',$this->session->periodo=='Año actual'?true:false,'id="añoActual"').' Año actual'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Año anterior',$this->session->periodo=='Año anterior'?true:false,'id="añoAnterior"').' Año anterior'; ?>
    </li>
    
</ul>
  
        
        <div class="form-group">
            <div class="col-sm-2">
            <?php echo form_label('Un día: ', 'dia', array('class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-2">
            <?php echo form_label('Desde fecha: ', 'inicio', array('class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>
            </div>
        </div>
        
        <div class="form-group">
           <div class="col-sm-2">
            <?php echo form_label('Hasta fecha: ', 'final', array('class' => 'control-label ')); ?>
           </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>
            </div>
        </div>   
                
        <div class="form-group">
            <div class="col-sm-2">
                <?php echo form_label('Núm Tickets: ', 'final', array('class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form_label(sizeof($this->session->ticketsPeriodo), '', array('style' => 'text-align:left', 'id' => 'numTickets', 'class' => 'control-label')) ?>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-2">
                <?php echo form_label('', '', array('style'=>'text-align:left; padding-top:0; color:red', 'id'=>'esperar',  'class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">                <button id="listadoDiferenciasBoka" style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
                    <span class="" aria-hidden="true"></span> Listado Diferencias Importes Ventas
                </button>
            </div>
        </div>
        <!-- Para colocar los inputs hide de los tickets -->
        <div class="pasarTickets">  
         </div>
        
        
        <?php echo \form_close(); ?>
        
    </div>
</div>
       
  
 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuDiferenciasTienda').addClass('btn-primary');  
})
</script>

<script>
$(document).ready(function(){
    
     function buscarNumTickets(){
         $('#esperar').html('Procesando Datos');
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/getTickets", 
            data: $('form.fechas').serialize(),
            success: function(datos){
                var valores=$.parseJSON(datos);
                var num =valores['num'];
               // $('option').remove();
               // alert('hola'+num);
                if (num==0){
                  //  $('<option value='+'-1'+' >Ningún ticket en estas fechas</option>').appendTo('select#tickets');
                    $('#numTickets').html(0); 
                }else {
                var tickets=valores['tickets'];
                $('#numTickets').html(num); 
                /*
                $.each(tickets, function( index, value ) {
                   $('<option value='+index+' >'+value+'</option>').appendTo('select#tickets');
                 //  $('<input type="hidden" value='+index+' name='+index+' >').appendTo('.pasarTickets');
                  $('<input type="hidden" value="'+value+'" name="ticketsPeriodo[]" >').appendTo('.pasarTickets');
                   
                });
                */
            }
                $('#esperar').html('');
        },
            error: function(){
                $('#esperar').html('Error en el proceso');
            }
        });
    }
function ponerPeriodo(){
        var today=new Date();
        var inicio=Date.parse($('#inicio').val());
        var final=Date.parse($('#final').val());
        var diaSemana=today.getDay();
        var unDia=24*60*60*1000;
        
        
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        if(dd<10) {
            dd='0'+dd;
        } 
        if(mm<10) {
            mm='0'+mm;
        } 
        today = yyyy+'-'+mm+'-'+dd;
        var hoy=Date.parse(today);
        
        var ayer=hoy-24*60*60*1000;
        
        var mesActualInicio=yyyy+"-"+mm+"-01";
        if (mm==12)
            var mesActualFinal=yyyy+"-12-31";
        else{
            var mesSiguiente=parseInt(mm)+1;
            if(mesSiguiente<10) {
            mesSiguiente='0'+mesSiguiente;
        }
            var mesActualFinal=yyyy+"-"+mesSiguiente+"-01";
            mesActualFinal=Date.parse(mesActualFinal)-24*60*60*1000;
        }
        
        var mesAnteriorInicio="";
        if (mm=='01'){
            var añoAnterior=parseInt(yyyy) 
                añoAnterior=añoAnterior-1;
                mesAnteriorInicio=añoAnterior+"-12-01";
            }
            else{
            var mesAnterior=parseInt(mm)-1;
            if(mesAnterior<10) {
            mesAnterior='0'+mesAnterior;
            }
            mesAnteriorInicio=yyyy+"-"+mesAnterior+"-01";
        }
            
              //  alert(mesAnteriorInicio);
                
            var mesActual=yyyy+"-"+mm+"-01";
            var mesAnteriorFinal=Date.parse(mesActual)-24*60*60*1000;
        
        var añoActualInicio=yyyy+"-01-01";
        var añoActualFinal=yyyy+"-12-31";
        
        var añoAnteriorInicio=yyyy-1+"-01-01";
        var añoAnteriorFinal=yyyy-1+"-12-31";

        if ((inicio==final) && (inicio==hoy))
            $('#hoy').attr('checked','cheched');
        if ((inicio==final) && (inicio==ayer))
            $('#ayer').attr('checked','cheched');
        if (($('#inicio').val()==añoActualInicio) && ($('#final').val()==añoActualFinal))
            $('#añoActual').attr('checked','cheched');
        if (($('#inicio').val()==añoAnteriorInicio) && ($('#final').val()==añoAnteriorFinal))
            $('#añoAnterior').attr('checked','cheched');
        if (($('#inicio').val()==mesActualInicio) && (Date.parse($('#final').val())==mesActualFinal))
            $('#mesActual').attr('checked','cheched');
        if (($('#inicio').val()==mesAnteriorInicio) && (Date.parse($('#final').val())==mesAnteriorFinal))
            $('#mesAnterior').attr('checked','cheched');
        if ( ((inicio-hoy)/unDia==diaSemana-1) && ((final-hoy)/unDia==(6-diaSemana+1)) )
            $('#semanaActual').attr('checked','cheched');
        if ( ((inicio-hoy)/unDia==diaSemana-1-7) && ((final-hoy)/unDia==diaSemana-1-1) )
            $('#semanaAnterior').attr('checked','cheched');
    }
    
    $('#inicio, #dia').change(function(e){
       
       $('input[name="periodo"]').removeAttr('checked');
       $('.inicioR').val($(this).val());
       $('#final').val($(this).val());
       $('.finalR').val($(this).val());
       $('option').remove();
       $('#dia').val($(this).val());
        var dia=($(this).val());
        $('#inicio').val(dia);
        $('#final').val(dia);
        //var periodo='#'+$(this).attr('id');
        e.preventDefault();
        buscarNumTickets();
        ponerPeriodo();
        
    })
    
    $('#final').change(function(e){
       // var dia=($(this).val());
        if(!$('#inicio').val()){
            $('#inicio').val($(this).val());
            $('.inicioR').val($(this).val());
        }
        if ($('#inicio').val()==$(this).val()) 
           $('#dia').val($(this).val());
       else
           $('#dia').val("");
       
       $('input[name="periodo"]').removeAttr('checked');
        e.preventDefault();
        buscarNumTickets();
        ponerPeriodo();
    })
    
    $('#mesActual, #añoActual, #hoy, #ayer, #semanaActual, #semanaAnterior, #añoAnterior, #mesAnterior').click(function(e){
       
       //e.preventDefault();
       buscarNumTickets();
       //$(this).attr('checked','checked');
   })
    
    
    
    })
    </script>