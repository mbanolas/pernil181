<h3>Ventas Directas</h3>

<?php echo $periodosVentasVD ?>






<script>
$(document).ready(function () {
    
    //$('#hoy').attr('selected','selected');
    //$('#hoy').attr('selected','selected');
     $('.obteniendo').hide();
    //$('#hoy').attr('selected','selected');
    $('#listadoBoka').click(function(){
        
        $('.obteniendo').show();
    })
  
   
   $('form.fechas').submit(function(){
       $('.ajax-loader2').css('visibility','visible');
   })
   
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
    
    function fechaEuropea(fecha){
        return fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
    }
    
    function buscarPedidosVD(){
        $('#listadoBoka').attr('disabled','disabled')
        $('#numTickets').html('-')
        $('.ajax-loader').show()
        
         $('#esperar').html('Procesando Datos');
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/getPedidosVD", 
            data: $('form.fechas').serialize(),
            success: function(datos){
                //alert(datos)
                var valores=$.parseJSON(datos);
                //alert(valores['numPedidos'])
                $('#numPedidos').html(valores['numPedidos'])
                var fechaDesde=valores['fechaPrimerPedido']==''?'':' ('+fechaEuropea(valores['fechaPrimerPedido'])+')'
                var fechaHasta=valores['fechaUltimoPedido']==''?'':' ('+fechaEuropea(valores['fechaUltimoPedido'])+')'

                var desde=valores['primerPedido']+fechaDesde
                $('#desde').html(desde)
                $('#pedidosDesde').val(desde)
                var hasta=valores['ultimoPedido']+fechaHasta
                $('#hasta').html(hasta)
                $('#pedidosHasta').val(hasta)
                
                ponerPeriodo();
                $('#esperar').html('');
                $('#listadoBoka').removeAttr('disabled')
                $('.ajax-loader').hide()
        },
            error: function(){
                $('#esperar').html('Error en el proceso');
            }
        });
    }
    
    $('#ticketsFaltan').click(function(e){
    e.preventDefault()
    $("#myModal").modal({                   
                       "backdrop"  : "static",
                       "keyboard"  : true,
                       "show"      : true                     
                     });
    })
    
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
        buscarPedidosVD();
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
        buscarPedidosVD();
        ponerPeriodo();
    })
   
   $('#hoy').change(function(){
       
        hoy();
   })
   
   $('#ayer').change(function(){
        ayer();
   })
   
   $('#mesActual').change(function(){
        mesActual();
   })
   
   $('#mesAnterior').change(function(){
        mesAnterior();
   })
   
   $('#añoAnterior').change(function(){
        añoAnterior();
   })
   
   $('#añoActual').change(function(){
        añoActual();
   })
   
   $('#semanaActual').change(function(){
        semanaActual();
   })
   
   $('#semanaAnterior').change(function(){
        semanaAnterior();
   })
   
   function toFecha(f){
        var d=f.getDate()<10?'0'+f.getDate():f.getDate();
        var m=f.getMonth()+1;
        var m=m<10?"0"+m:m;
        var y=f.getFullYear();
        var fecha=y + "-" + m + "-" + d;
        return fecha;
   }
   
   
   $('#mesActual, #añoActual, #hoy, #ayer, #semanaActual, #semanaAnterior, #añoAnterior, #mesAnterior').click(function(e){
       //e.preventDefault();
      // buscarPedidosVD();
     //  ponerPeriodo();
       //$(this).attr('checked','checked');
   })
   
   function hoy(){
      
    var f=new Date();
    var hoy=f.valueOf(f);
    var f=new Date(hoy);
    fecha=toFecha(f);
    $('#dia').val(fecha);  
    $('#inicio').val(fecha);
    $('#final').val(fecha);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha);
     buscarPedidosVD();
       // ponerPeriodo();
    };
   
   function ayer(){
      
    var f=new Date();
    var hoy=f.valueOf(f);
    var f=new Date(hoy-24*60*60*1000);
    fecha=toFecha(f);
    $('#dia').val(fecha);  
    $('#inicio').val(fecha);
    $('#final').val(fecha);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha);
     buscarPedidosVD();
        //ponerPeriodo();
    };
    
   function mesActual(){
       
        var f=new Date();
        var principioMes=new Date(f.getFullYear(),f.getMonth(),1,0,0,0,0);
        var principioMesSiguiente=new Date(f.getFullYear(),f.getMonth()+1,1,0,0,0,0);
        fecha=toFecha(principioMes);
        var s=principioMesSiguiente.valueOf(principioMesSiguiente);
        var f=new Date(s-24*60*60*1000);
        fecha2=toFecha(f);
    $('#dia').val("");  
    $('#inicio').val(fecha);
    $('#final').val(fecha2);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha2);
    buscarPedidosVD();
    }
    
   function mesAnterior(){
        var f=new Date();
        var principioMes=new Date(f.getFullYear(),f.getMonth(),1,0,0,0,0);
        var principioMesAnterior=new Date(f.getFullYear(),f.getMonth()-1,1,0,0,0,0);
        fecha=toFecha(principioMesAnterior);
        var s=principioMes.valueOf(principioMes);
        var f=new Date(s-24*60*60*1000);
        fecha2=toFecha(f);
    $('#dia').val("");  
    $('#inicio').val(fecha);
    $('#final').val(fecha2);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha2);
    buscarPedidosVD();
    }
   
   function añoActual(){
        var f=new Date();
        var principioAño=new Date(f.getFullYear(),0,1,0,0,0,0);
        var principioAñoSiguiente=new Date(f.getFullYear()+1,0,1,0,0,0,0);
        fecha=toFecha(principioAño);
        var s=principioAñoSiguiente.valueOf(principioAñoSiguiente);
        var f=new Date(s-24*60*60*1000);
        fecha2=toFecha(f);
    $('#dia').val("");    
    $('#inicio').val(fecha);
    $('#final').val(fecha2);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha2);
    buscarPedidosVD();
    }
   
   function añoAnterior(){
        var f=new Date();
        var principioAño=new Date(f.getFullYear(),0,1,0,0,0,0);
        var principioAñoAnterior=new Date(f.getFullYear()-1,0,1,0,0,0,0);
        fecha=toFecha(principioAñoAnterior);
        var s=principioAño.valueOf(principioAño);
        var f=new Date(s-24*60*60*1000);
        fecha2=toFecha(f);
    $('#dia').val("");  
    $('#inicio').val(fecha);
    $('#final').val(fecha2);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha2);
    buscarPedidosVD();
    }
   
   function semanaActual(){
        var f=new Date();
        var dia=f.getDay()-1;
        if(dia<0) dia=6;
        //alert(dia);
    var hoy=f.valueOf(f);
    var f=new Date(hoy-dia*24*60*60*1000);
    fecha=toFecha(f);
    var f=new Date(hoy-(dia-6)*24*60*60*1000);
    fecha2=toFecha(f);
    $('#dia').val("");  
    $('#inicio').val(fecha);
    $('#final').val(fecha2);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha2);
    buscarPedidosVD();
    }
   
   function semanaAnterior(){
        var f=new Date();
        var dia=f.getDay()-1;
        if(dia<0) dia=6;
    var hoy=f.valueOf(f);
    var f=new Date(hoy-(dia+7)*24*60*60*1000);
    fecha=toFecha(f);
    var f=new Date(hoy-(dia+1)*24*60*60*1000);
    fecha2=toFecha(f);
    $('#dia').val("");  
    $('#inicio').val(fecha);
    $('#final').val(fecha2);
    $('.inicioR').val(fecha);
    $('.finalR').val(fecha2);
    buscarPedidosVD();
    }
   
   
   
    
    
    $('input[type="radio"]').removeAttr('checked');
   buscarPedidosVD();
   ponerPeriodo();
    
    
})
</script>  