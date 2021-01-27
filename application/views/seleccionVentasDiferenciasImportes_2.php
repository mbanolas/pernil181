

<h3>Seleccionar periodo Listado Diferencias Importes Ventas</h3>


<?php echo $periodosVentas ?>
       

  
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
$(document).ready(function () {
    
    $("#loading").ajaxStart(function () {
    $(this).show();
 });

 $("#loading").ajaxStop(function () {
   $(this).hide();
 });
    
    
    
    
    //$('#hoy').attr('selected','selected');
   
    $('#mostrarTicket').click(function(e){
        var ticket=$('select#tickets').val();
        if (ticket==-1){
            e.preventDefault();
           
            $("#myModal").modal({                   
      "backdrop"  : "static",
      "keyboard"  : true,
      "show"      : true                     
    });
            
        }
       //alert($('select#tickets').val());
    })
    
    function buscarTickets(){
         $('#esperar').html('Procesando Datos');
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/getTickets", 
            data: $('form.fechas').serialize(),
            success: function(datos){
                //alert(datos)
                
                var valores=$.parseJSON(datos);
                var num =valores['num'];
                
                var numeracion1=""
                var numeracion2=""
                var numeracion3=""
                var num1=valores['num1'];
                    if(num1>0) {
                        numeracion1='(Del '+valores['primero1']['RASA']+' al '+valores['ultimo1']['RASA']+')'
                        if(parseInt(valores['ultimo1']['RASA'])-parseInt(valores['primero1']['RASA'])!=parseInt(num1)-  1){
                           $('#balanza1').parent().attr('style','color:red') 
                    } else $('#balanza1').parent().attr('style','color:black') 
                    }
                    
                    var num2=valores['num2'];
                if(num2>0) {
                        numeracion2='(Del '+valores['primero2']['RASA']+' al '+valores['ultimo2']['RASA']+')'
                        if(parseInt(valores['ultimo2']['RASA'])-parseInt(valores['primero2']['RASA'])!=parseInt(num2)-  1){
                           $('#balanza2').parent().attr('style','color:red') 
                    } else $('#balanza2').parent().attr('style','color:black') 
                    
                }
                    var num3=valores['num3'];
                if(num3>0) {
                        numeracion3='(Del '+valores['primero3']['RASA']+' al '+valores['ultimo3']['RASA']+')'
                        if(parseInt(valores['ultimo3']['RASA'])-parseInt(valores['primero3']['RASA'])!=parseInt(num3)-  1){
                           $('#balanza3').parent().attr('style','color:red') 
                    } else $('#balanza3').parent().attr('style','color:black') 
                    }
                $('option').remove();
               // alert('hola'+num);
                if (num==0){
                    $('<option value='+'-1'+' >Ningún ticket en estas fechas</option>').appendTo('select#tickets');
                    $('#numTickets').html(0); 
                }else {
                var tickets=valores['tickets'];
                $('#numTickets').html(num); 
                 $('#balanza1').html(num1); 
                  $('#balanza2').html(num2); 
                   $('#balanza3').html(num3); 
                   $('#numeracion1').html(numeracion1); 
                   $('#numeracion2').html(numeracion2); 
                   $('#numeracion3').html(numeracion3); 
                   $('#periodoBalanza1').val('Balanza 1: '+num1+' '+numeracion1)
                   $('#periodoBalanza2').val('Balanza 2: '+num2+' '+numeracion2)
                   $('#periodoBalanza3').val('Balanza 3: '+num3+' '+numeracion3)
                   $('#periodoBalanzaTodas').val('Núm Tickets: '+num)
                
                $.each(tickets, function( index, value ) {
                   $('<option value='+value+' >'+value+'</option>').appendTo('select#tickets');
                 //  $('<input type="hidden" value='+index+' name='+index+' >').appendTo('.pasarTickets');
                 // $('<input type="hidden" value="'+value+'" name="ticketsPeriodo[]" >').appendTo('.pasarTickets');
                   
                });
            }
                ponerPeriodo();
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
        buscarTickets();
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
        buscarTickets();
        ponerPeriodo();
    })
    
        

    
    $('#mesActual, #añoActual, #hoy, #ayer, #semanaActual, #semanaAnterior, #añoAnterior, #mesAnterior').click(function(e){
       
       //e.preventDefault();
       buscarTickets();
       ponerPeriodo();
       //$(this).attr('checked','checked');
   })
    
    
    $('input[type="radio"]').removeAttr('checked');
   buscarTickets();
   ponerPeriodo();
    
    
})
</script>  