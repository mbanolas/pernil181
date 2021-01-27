

<h3>Seleccionar periodo Listado Caja</h3>


<?php  echo $seleccionPeriodos ?>

 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuCaja').addClass('btn-primary');
  $('#menuInformacionCierresCaja').addClass('btn-primary');  
})
</script>

<script>
$(document).ready(function () {
    
    $('#error').html('');
    $('#mostrarInformeCierre').removeAttr('disabled')

    function buscarDiasCierre(){
        
        $('#mostrarInformeCierre').removeAttr('disabled')
        $('img.ajax-loader2').css('visibility','visible')
        $('#listadoCaja').attr('disabled','disabled')
        $('.ajax-loader').show()
         $('#esperar').html('Procesando Información');
        var inicio=$('#inicio').val()
        var final=$('#final').val()
        
        console.log('buscarDiasCierre')
        if('<?php  echo strtolower($this->session->username)!='pernilall'; ?>'){
            $("#myModalError").css('color','blue')
            if(inicio < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Desde debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              $('img.ajax-loader2').css('visibility','hidden')
              return false
            }
            if(final < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Hasta debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              $('img.ajax-loader2').css('visibility','hidden')

              return false
            }
      }
      console.log('buscarDiasCierre completado')



         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/caja/contarDias", 
            data: {inicio:inicio,final:final},
            success: function(datos){
                $('img.ajax-loader2').css('visibility','hidden')
                var valores=$.parseJSON(datos);
                $('#error').html('')
                if (valores==0) {
                    $('#error').html('Ningún periodo Seleccionado');
                    $('#mostrarInformeCierre').attr('disabled','disabled')
                }
                $('#numDias').html(valores)
                $('#cuadroResultados').css('display','')
                $('img.ajax-loader2').css('visibility','hiddden')
                
               // var num =valores['num'];
                
               // ponerPeriodo();
               // $('#esperar').html('');
               // $('#listadoCaja').removeAttr('disabled')
               // $('.ajax-loader').hide()
        },
            error: function(){
                $('#esperar').html('Error en el proceso');
                $('img.ajax-loader2').css('visibility','hidden')
            }
        });
    }
    
    $('#buscarDiasCierre').click(function(){
    
        buscarDiasCierre();
    })
    
    $('#mostrarInformeCierre').click(function(){
        var inicio=$('#inicio').val()
        var final=$('#final').val()
        window.location.href = "<?php echo base_url() ?>"+"index.php/caja/listaCajaGrocery/"+inicio+"/"+final 
    })
    
    /*
    
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
        buscarDiasCierre();
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
        buscarDiasCierre();
        ponerPeriodo();
    })
    
    $('#mesActual, #añoActual, #hoy, #ayer, #semanaActual, #semanaAnterior, #añoAnterior, #mesAnterior').click(function(e){
       buscarDiasCierre();
       ponerPeriodo();
   })
    
    
    $('input[type="radio"]').removeAttr('checked');
   buscarDiasCierre();
   ponerPeriodo();
    
    */
    
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
    
    
    
    
})
</script>  