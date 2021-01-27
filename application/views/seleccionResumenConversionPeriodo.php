<style type="text/css">
    .tconversion{
        padding-right: 10px;
    
    }
    .convertido{
        font-weight: bold;
    }
    th{
        padding-left:20px;
    }
    td.marcar{
        padding-right: 17px;        
    }
    #target2{
        text-align: center;
        background-color: yellow;
    }
    table tbody tr{
        font-size:14px;
    }
    h5{
        color:blue;
    }

</style>
<div class="col-md-12"  id='cuadroResultados' style="background-color: #f2f2f2; display:none" >
            <div class="row">
                <div class="col-md-12" style="padding-top:10px" >
                    <?php echo form_label('Tickets modificables: pago metálico, sin cliente, sin descuentos', 'ticketModificables', array('class' => 'control-label ')); ?> 
                </div>
            </div>
    <div class="row">
        <!-- resumen tickets totales del periodo y los que son modificables -->
        <div class="col-md-12"  id="tablaResumen" >
        </div>
    </div>
    <hr>
    <div class="row">
        <!-- resumen valores de acuerdo con conversiones activas -->
        <div class="col-md-12"  id="tabla" >
        </div>
    </div>
    <hr>
    <div class="row">
         <!-- resumen valores de tickets convertibles -->
        <div class="col-md-12"  id="tablaLineas" >
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12" >
           <?php echo form_label('', 'target', array('class' => 'control-label ', 'id'=>'target')); ?> 
       <!--    <?php  echo form_input('target', '','style="margin-left: 20px;" id="target2"') ?> -->
           <?php  echo form_input('target', '','style="margin-left: 20px;" id="reduccionActual" disabled') ?> 
       <!--     <button class="btn btn-default" id="prepararConversion" style="margin-left: 20px; margin-right: 20px">Preparar Conversión</button>
            <img class="img-responsive ajax-loader3"  style="display:inline ;visibility:hidden;"  src="<?php echo base_url('images/ajax-loader.gif') ?>">
                <?php echo form_label('', 'target', array('class' => 'control-label ', 'id'=>'obtenido')); ?> -->
            <button class="btn btn-default"  disabled id="registrarConversion" style="margin-left: 20px; margin-right: 20px">Registrar Resultados</button>
             <img class="img-responsive ajax-loader4"  style="display:inline; visibility:hidden;"  src="<?php echo base_url('images/ajax-loader.gif') ?>">
                <?php echo form_label('', 'target', array('class' => 'control-label ', 'id'=>'obtenido')); ?> 
        
        </div>
        <div class="col-md-12" >
            <button class="btn btn-default" id="restaurarValores" style="margin-left: 20px; margin-right: 20px">Restaurar valores periodo</button>
            <img class="img-responsive ajax-loader5"  style="display:inline ; visibility:hidden;"  src="<?php echo base_url('images/ajax-loader.gif') ?>">
         <!--   <?php echo form_label('(0) restaura valores periodo', 'target', array('class' => 'control-label ', 'id'=>'')); ?> -->
        </div>
    </div>
    
    
    <hr>
    <!-- Tabla conversiones 
    <div class="row">
        <div class="col-md-10  " >
    <?php foreach($cambios['conversiones'] as $k=>$v){
            if ($cambios['activa'][$k]==1) {$checked='checked';
            $color='blue';
            } else {$checked='';
            $color='black';
            }
            echo "<input type='checkbox'  disabled name='check_conversiones[]' $checked value=''><span style='color:$color'>".$v." - ".$cambios['nombreInicio'][$k]." > ".$cambios['nombreFinal'][$k]." (".$cambios['pesos'][$k]." g) "."</span><br />";
    } ?>
        </div>
        </div>
    <hr>
   -->

            
            <div class="row">
                <div class="col-md-5  " >
       <!--           <?php echo anchor('conversion/mostrarTicketModificar', 'Mostrar Tickets para modificarlos', array('class' => "btn btn-default")); ?>
            
               <button class="btn btn-default" id="mostrarTickets" >Botón Convertir</button>
       -->   
                </div> 
                <div class="col-md-6  " >
                    
                </div> 
            </div>
        </div>







<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuConversionesN').addClass('btn-primary');
  $('#menuConversionPeriodo').addClass('btn-primary');  
})
</script>

<script>
$(document).ready(function () {
    
    var inicio
    var final
    
    function alerta(titulo="Información",mensaje){
        
        $('.modal-title').html(titulo)
        $('.modal-body>p').html(mensaje)
        $("#myModal").modal()
    }
    function alertaError(titulo="Información importante",mensaje){
        
        $('.modal-title').html(titulo)
        $('.modal-body>p').html(mensaje)
        $("#myModalError").modal()
    }
    
        // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
        
    $('#marcarTodos').click(function(){
        //console.log('marcarTodos')
        if($(this).prop('checked')){
            //alert('click')
        }
    }) 

     $('div#tabla').delegate('.lineaTicket','click',function(){
        //console.log('lineaTicket')
        if($(this).prop('checked')){
            //console.log('marcado')
            $('#reduccionActual').val(totalDiferencias().toFixed(2))
        }
        else{
            //console.log('NO marcado')
            $('#reduccionActual').val(totalDiferencias().toFixed(2))
        }
    }) 


    function totalDiferencias(){
        
        var totalDiferencias=0
        $('.lineaTicket').each(function(index,element){
            if($(this).prop('checked')){
                //console.log($(this).parent().prev().html())
                totalDiferencias+=Number($(this).parent().prev().html())
            }
        })
        //console.log('hola totalDiferencias = '+totalDiferencias)
        //$('input#target2').val(totalDiferencias.toFixed(2))
        return totalDiferencias
    }

    $('div#tabla').delegate('#marcarTodos','click',function(){
        //console.log('marcarTodos')
        if($(this).prop('checked')){
            $('.lineaTicket').attr('checked','checked')
            $('#reduccionActual').val(totalDiferencias().toFixed(2))
        }
        else{
            $('.lineaTicket').removeAttr('checked')
            $('#reduccionActual').val(totalDiferencias().toFixed(2))
        }
    })  



    $('#buscarTickets').click(function(){    
        buscarTickets()
    })

    var maximo=0
    
    function verificarTarget2(){
        var target2=$('input#target2').val()
        target2=parseFloat(target2)
        if (target2==0){
            alerta('Información','No existen tickets que se puedan efectuar conversiones.\nRevisar el periodo o los criterios de convesión')
            return false;
        }      
        if (isNaN(target2)){
            alerta('Información','Indique un objetivo válido superior a 0 o 0 para restaurar periodo');
            return false;
        }
       
       
        //alert('Valor de target2 numero'+target2+'   '+parseFloat(target2))
        if(target2>maximo){
            alerta('Información','El objetivo indicado ('+target2+') es superior al valor máximo ('+maximo+').');
            return false;
        }
       if(target2<0){
            alerta('Información','Objetivo debe ser superior a 0');
            return false;
        }
        if(target2==0){
            if(confirm('Desea restaurar los valores originales del periodo?')){
                inicio=$('#inicio').val()
                final=$('#final').val() 
                $('img.ajax-loader3').css('visibility','visible')
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url() ?>"+"index.php/conversion/restaurarConversiones", 
                        data: {inicio:inicio,final:final},
                        success:function(datos){
                             $('img.ajax-loader3').css('visibility','hidden')
                             alerta('Información','Conversiones restauradas con éxito.')
                        },
                        error:function(){
                            alerta('Información','Error en la restauración conversión. Informar al Administrador')
                        },
                    }) 
              }
              else {
                return false;
              }
        }   
        return true;
    }
    
    $('#añoActual').parent().addClass('hide')
    $('#añoAnterior').parent().addClass('hide')
   

    $('button#restaurarValores').click(function(){
        $('img.ajax-loader5').css('visibility','visible')
        $('#tabla').html('')
        $.ajax({
            type:"POST",
            url:"<?php echo base_url() ?>"+"index.php/conversion/restaurarValores", 
            data:{inicio:inicio,final:final},
            success:function(datos){
                $('#cuadroResultados').html('')
                $('img.ajax-loader5').css('visibility','hidden') 
              var valores=$.parseJSON(datos);
             
              window.location.href = '<?php echo base_url() ?>'+'uploads/conversiones/'+valores;
              alerta("Informacion","Restauración Valores realizada correctamente <br>Generado archivos Boka en archivo:<br>"+valores)
             // window.location.href = '<?php echo base_url() ?>'+'index.php/inicio';
              
            },
            error:function(){
                $('img.ajax-loader5').css('visibility','hidden')
                alerta('Información','Error en el registro Conversiones (restauración). Reducir el múmero de días y volver a procesar.')
                //alert('Error en el registro Conversiones. Informar al Administrador')
            },
        })
    });
    
    $('input#target2').click(function(){
        $('.lineaTicket').removeAttr('checked')
    })

    function prepararConversion(){
        //console.log('prepararConversion inicio '+inicio)
        //console.log('prepararConversion final '+final)
       
        $("#tabla table tbody tr td").css('color','black')
        $("#tabla table tbody tr td").css('font-weight','normal')
        $("#tabla table tbody tr td").css('background-color','white')
        if(!verificarTarget2()) return false;
        $('img.ajax-loader3').css('visibility','visible')
        $('input.lineaTicket').removeAttr('checked')
        $('#reduccionActual').val(totalDiferencias().toFixed(2))
        var objetivo=parseFloat($('input#target2').val())
        //console.log(objetivo)
        $('img.ajax-loader3').css('visibility','visible')
        
        var max=$("#tabla table tbody tr").length
        var min = 2;
       
        while((parseFloat($('#reduccionActual').val())<objetivo))  {
            //console.log('entrada '+parseFloat($('#reduccionActual').val()))
            //console.log(objetivo)

            var random = Math.floor(Math.random() * (max - min + 1)) + min;   
            $("#tabla table tbody tr:nth-child("+random+") td.marcar input").attr('checked','checked')
            $("#tabla table tbody tr:nth-child("+random+") td").css('color','blue')
            $("#tabla table tbody tr:nth-child("+random+") td").css('font-weight','bold')
            $("#tabla table tbody tr:nth-child("+random+") td").css('background-color','lightyellow')
            $('#reduccionActual').val(totalDiferencias().toFixed(2))  
            //console.log('salida '+parseFloat($('#reduccionActual').val()))    
        }     
       
       $('#registrarConversion').removeAttr('disabled')
       $('#target2').val($('#reduccionActual').val())
       $('img.ajax-loader3').css('visibility','hidden') 
   
    }

    $('button#prepararConversion').click(function(){
        //console.log('prepararConversion inicio '+inicio)
        //console.log('prepararConversion final '+final)
        if(!verificarTarget2()) return false;
        $('img.ajax-loader3').css('visibility','visible')
        $('input.lineaTicket').removeAttr('checked')
        $('#reduccionActual').val(totalDiferencias().toFixed(2))
        var objetivo=parseFloat($('input#target2').val())
        //console.log(objetivo)
        $('img.ajax-loader3').css('visibility','visible')
        
        var max=$("#tabla table tbody tr").length
        var min = 2;
       
        while((parseFloat($('#reduccionActual').val())<objetivo))  {
            var random = Math.floor(Math.random() * (max - min + 1)) + min;   
            $("#tabla table tbody tr:nth-child("+random+") td.marcar input").attr('checked','checked')
            $('#reduccionActual').val(totalDiferencias().toFixed(2))  
                
        }     
       
       $('#registrarConversion').removeAttr('disabled')
       $('#target2').val($('#reduccionActual').val())
       $('img.ajax-loader3').css('visibility','hidden') 
    })

    $('#registrarConversion').removeAttr('disabled')
    
    $('.lineaTicket').attr('disabled','disabled')




    $('#registrarConversion').click(function(){
        $('img.ajax-loader4').css('visibility','visible')
       
        var productosFinales=[]
        var lineasBoka=[]
        
        $("#tabla table tbody tr td.marcar input[checked='checked']").each(function(i,v){
            productosFinales.push($(this).parent().parent().children().eq(6).html())
            lineasBoka.push($(this).val())

        })

        $.ajax({
            type:"POST",
            url:"<?php echo base_url() ?>"+"index.php/conversion/updateTickets", 
            data:{lineasBoka:lineasBoka,productosFinales:productosFinales,inicio:inicio,final:final},
            success:function(datos){
                //alert(datos)
              var valores=$.parseJSON(datos);
              $('img.ajax-loader4').css('visibility','hidden')
              window.location.href = '<?php echo base_url() ?>'+'uploads/conversiones/'+valores;
              alerta("Informacion","Conversión realizada correctamente <br>Generado archivos Boka en archivo:<br>"+valores)
            },
            error:function(){
                $('img.ajax-loader4').css('visibility','hidden')
                alerta('Información','Error en el registro Conversiones. Reducir el múmero de días y volver a procesar.')
            },
        })
    })
    
    
    function meses(fecha){
        var ano=parseInt(fecha.substr(0,4))
        var mes=parseInt(fecha.substr(5,2))
        var dia=fecha.substr(8,2)
        var meses=(ano-1970)*12+mes
    return parseInt(meses)
    }
    
    function buscarTickets(){
         inicio=$('#inicio').val()
         final=$('#final').val()  
         if (inicio>final){
             $('#inicio').val(final)
             $('#final').val(inicio)
        }
        inicio=$('#inicio').val()
        final=$('#final').val()

        var date1 = new Date(inicio);
        var date2 = new Date(final);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
        //alert(diffDays);
        
        //var difMeses=meses(final) - meses(inicio)
        
        if (diffDays>15){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
             $('.modal-body>p').html('El periodo es de '+diffDays+' debe de ser de máximo 15 días. Cambiar las fechas de inicio y/o final')
             $("#myModal").modal()
             return false;
        }
         
         
         if(!inicio || !final){
             $('#myModal').css('color','red')
             $('.modal-title').html('Información')
             $('.modal-body>p').html('No se ha seleccionado ningún periodo. Faltan indicar<br /> - Desde fecha y/o<br /> - Hasta fecha.')
             $("#myModal").modal()
             return false;
         }
         
        
        $('img.ajax-loader2').css('visibility','visible')
        
        
        $('#numTickets').html(0)
         inicio=$('#inicio').val()
         final=$('#final').val()

         /*
         $('#procesando').html('Procesando Tickets. Esperar');
         $('#mostrarTicketModificar').attr('disabled','disabled')
          $('#obtenido').html("")
         */
          
          var codigosIniciales=[]
          var codigosFinales=[]
          $('.conversiones[checked="checked"]').each(function(){
                if(jQuery.inArray($(this).attr('codigo_inicial'), codigosIniciales) == -1){
                codigosIniciales.push($(this).attr('codigo_inicial'))
                codigosFinales.push($(this).attr('codigo_final'))
            }
          })
        
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/conversion/getResumenTickets", 
            data: {inicio:inicio,final:final,codigosIniciales:codigosIniciales,codigosFinales:codigosFinales},
            success: function(datos){
               //alert(datos)
               if(datos==0){
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información ')
                    $('.modal-body>p').html("Ningún ticket en el periodo o ningún ticket convertible")
                    $("#myModal").modal() 
                    $('img.ajax-loader2').css('visibility','hidden')
                    return false
                }
               var valores=$.parseJSON(datos);
               
               //alert(valores)
               
               $('#tabla').html(valores['tabla'])
               $('#target').html('Objetivo reducción (máximo <span id="maximaReduccion">'+totalDiferencias().toFixed(2)+'</span> €)')
              maximo=totalDiferencias().toFixed(2)
              $('#reduccionActual').val(maximo)
               
               
              
               
               $('#cuadroResultados').css('display','')
               $('img.ajax-loader2').css('visibility','hidden')
               $('#target2').focus();
               //document.getElementById("target2").focus();
               
               window.scrollTo(0,document.body.scrollHeight);
               
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
            
              //alert(mesAnteriorInicio);
                
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