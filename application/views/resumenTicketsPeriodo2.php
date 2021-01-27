
<h3>Seleccionar Tickets para mostrar y modificar (sólo pago en metálico, sin cliente, sin descuentos)</h3>


<?php echo $seleccionPeriodos ?>


<script>
$(document).ready(function () {
    
    $('#buscarTickets').click(function(){
        
        buscarTickets()
    })
    var maximo=0
    
    function verificarTarget2(){
        if (maximo==0){
            alert('No existen tickets que se puedan efectuar conversiones.\nRevisar el periodo o los criterios de convesión')
            return false;
        }
        var target2=$('input#target2').val()
        target2=parseFloat(target2)
        if (isNaN(target2)){
            alert('Indique un objetivo válido superior a 0');
            return false;
        }
       
       
        //alert('Valor de target2 numero'+target2+'   '+parseFloat(target2))
        if(target2>maximo)
            alert('Objetivo superior al valor máximo');
        else if(target2<=0)
            alert('Objetivo debe ser superior a 0');
        else return true;
        return false;
    }
    
    
    $('button#prepararConversion').click(function(){
        if(!verificarTarget2()) return false;
        var objetivo=$('input#target2').val()
        //alert('Inicio proceso conversión - Objetivo: '+objetivo);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/conversion/makeConversiones", 
            data: {objetivo:objetivo},
            success:function(datos){
                alert(datos);
                var valores=$.parseJSON(datos);
                $('#obtenido').html('Obtenido: '+valores['resultados']['difIVA']/100+' €')
                $('#tablaLineas').html(valores['resultados']['tablaLineas'])
                $('#registrarConversion').removeAttr('disabled')
        //alert('Num Lineas actuales: '+valores['difIVA'])
                
            },
            error:function(){
                alert('Error en la conversión. Informar al Administrador')
            },
        })
        
    })
    
    $('#registrarConversion').click(function(){
       
        $.ajax({
            type:"POST",
            url:"<?php echo base_url() ?>"+"index.php/conversion/registrarConversiones", 
            data:{},
            success:function(datos){
                var valores=$.parseJSON(datos);
                //alert('hola datos'+datos);
                
            },
            error:function(){
                alert('Error en el registro Conversiones. Informar al Administrador')
            },
        })
    
    
    })
    
    function buscarTickets(){
        
        $('img.ajax-loader2').css('visibility','visible')
        $('#numTickets').html(0)
         var inicio=$('#inicio').val()
        var final=$('#final').val()
         $('#procesando').html('Procesando Tickets. Esperar');
         $('#mostrarTicketModificar').attr('disabled','disabled')
         
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/conversion/getResumenTickets2", 
            data: {inicio:inicio,final:final},
            success: function(datos){
               alert('hola '+datos)
                
               var valores=$.parseJSON(datos);
               
               //alert(valores)
               $('#tabla').html(valores['tabla'])
               $('#tablaResumen').html(valores['tablaResumen'])
               $('#tablaLineas').html(valores['tablaLineas'])
               
               $('#numTickets').html(valores['num'])
               $('#bruto').html(valores['bruto']+' €')
               $('#iva').html(valores['iva']+' €')
               maximo=valores['totalIVADiferencia']
               $('#target').html('Objetivo reducción IVA (máximo '+valores['totalIVADiferencia']+' €)');
               
               $('#numProductosPesos').html(valores['numProductosPesos'])
               $('#importePesos').html(valores['importePesos']+' €')
               $('#ivaPesos').html(valores['ivaPesos']+' €')
               
               $('#cuadroResultados').css('display','')
               $('img.ajax-loader2').css('visibility','hidden')
               
        },
            error: function(){
                $('img.ajax-loader2').css('visibility','hidden')
                $('#esperar').html('Error en el proceso');
            }
        });
         
      
    }
    
    
    
    
    //gestionar seleccion periodo
    {
    function ponerPeriodo(){
        $('input[name="periodo"]').removeAttr('checked');
        $('#cuadroResultados').css('display','none')
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
        if ( ((inicio-hoy)/unDia==1-diaSemana) && ((final-hoy)/unDia==(6-diaSemana+1)) ){
            $('#semanaActual').attr('checked','cheched');}
                            
        if ( ((inicio-hoy)/unDia==diaSemana-12) && ((final-hoy)/unDia==-diaSemana) )
            $('#semanaAnterior').attr('checked','cheched');
        
    }
    $(' #dia').blur(function(e){
       var dia=($(this).val());
       $('#inicio').val(dia);
       $('#final').val(dia);
       ponerPeriodo();
    })
    $('#inicio').blur(function(e){
       var dia=($(this).val());
       $('#dia').val('');
       if($('#final').val()==''){
           $('#final').val(dia);
           $('#dia').val(dia);
        }
       if($('#final').val()==dia){
           $('#dia').val(dia);
        }
       ponerPeriodo();
    })
    $('#final').blur(function(e){
       var dia=($(this).val());
       $('#dia').val('');
       if($('#inicio').val()==''){
           $('#inicio').val(dia);
           $('#dia').val(dia);
        }
       if($('#inicio').val()==dia){
           $('#dia').val(dia);
        }
       ponerPeriodo();
    })
    $('#hoy').change(function(){
       $('#cuadroResultados').css('display','none')
        hoy();
   })
    $('#ayer').change(function(){
       $('#cuadroResultados').css('display','none')
        ayer();
   })
    $('#mesActual').change(function(){
       $('#cuadroResultados').css('display','none')
        mesActual();
   })
    $('#mesAnterior').change(function(){
       $('#cuadroResultados').css('display','none')
        mesAnterior();
   })
    $('#añoAnterior').change(function(){
       $('#cuadroResultados').css('display','none')
        añoAnterior();
   })
    $('#añoActual').change(function(){
       $('#cuadroResultados').css('display','none')
        añoActual();
   })
    $('#semanaActual').change(function(){
       $('#cuadroResultados').css('display','none')
        semanaActual();
   })
    $('#semanaAnterior').change(function(){
       $('#cuadroResultados').css('display','none')
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
    function hoy(){
    var f=new Date();
    var hoy=f.valueOf(f);
    var f=new Date(hoy);
    fecha=toFecha(f);
    $('#dia').val(fecha);  
    $('#inicio').val(fecha);
    $('#final').val(fecha);
    };
    function ayer(){
    var f=new Date();
    var hoy=f.valueOf(f);
    var f=new Date(hoy-24*60*60*1000);
    fecha=toFecha(f);
    $('#dia').val(fecha);  
    $('#inicio').val(fecha);
    $('#final').val(fecha);
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
    
    }
    }  
    
})
</script>