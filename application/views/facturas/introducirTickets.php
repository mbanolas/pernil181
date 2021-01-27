<style type="text/css">
 /*
    div{
        border:1px solid red;
    }
  */  
  .ajax-loader{
         width: 20px;
        height: 20px;
        margin-left: 10px;
    }
    #agrupar, #no_any{
        margin-left: 12px;
    }
</style>

<hr>
    <h3>Introducir los números de tickets de la factura</h3>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-3">
                    <?php echo form_label('Núm ticket: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-4" style='padding-left:0px;'>
                    <?php echo form_input(array('type' => 'text', 'name' => 'numTicket', 'id' => 'numTicket', 'class' => 'form-control input-sm', 'value' => '')) ?>
                </div>
                <div class="col-md-5" >
                    <button id="buscar" style="text-align: left;"  class="btn btn-default btn-sm" >
                        <span class="" aria-hidden="true">Buscar</span>
                    </button>
                </div>
            </div>
            
            <div class="row" >
                <div class="col-md-3">
                    <?php echo form_label('Seleccionar Ticket: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-6" style='padding-left:0px;'>
                    <?php echo form_dropdown('ticketsNum', array(), '', array('data-toggle' => "dropdown", 'class' => 'form-control btn btn-default dropdown-toggle input-sm', 'id' => 'ticketsNum')) ?>
                </div>
                <div class="col-md-3" style='padding-left:0px;'>
                    <button id="añadir" style="text-align: left;" type="submit" class="btn btn-primary btn-sm hide" >
                        <span class="" aria-hidden="true">Añadir a la factura</span>
                    </button>
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-9" id='erroresNumTickets' style="padding-left:0px;color:red"></div>
            </div>
       </div> 
        <div class="col-md-6">
     <!--       <?php echo form_open('#', array('role' => 'form', 'class' => 'factura form-horizontal')); ?> -->
    <form role="form" class="factura form-horizontal" >
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <h4>Tickets en factura</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-11">                
                    <h5><span id="numCliente">Núm Cliente</span></h5>
                    <h5><span id="nombreCliente">Nombre Cliente</span></h5>
                    <h5><span id="empresaCliente">Empresa</span></h5>
                    <div id="ticketsEnFacturaInputs"></div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-11">  
                    <ul id="ticketsEnFactura">
                    </ul>
                </div>
             </div>
            
             <div class="row">
                 <!-- <div class="    col-md-1"></div> -->
                <div class="    col-md-5">
                    <?php echo form_label('Núm Factura: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-3">
                    <?php echo form_input(array('type' => 'text', 'name' => 'numFactura', 'id' => 'numFactura', 'class' => 'form-control input-sm', 'value' => $numFactura, 'placeholder' => 'Num factura Baaxxx')) ?>
                    <?php if ((int)$numFactura == 0) $n = '';
                    else $n = $numFactura; ?>
                    <?php echo form_input(array('type' => 'hidden', 'name' => 'numFacturaValido', 'id' => 'numFacturaValido', 'class' => 'form-control input-sm', 'value' => $n)) ?>
                </div>
                <div class="col-md-4" style="padding-right:0px">
                    <?php echo form_label('No control año: ', '', array('class' => 'control-label ', )); ?>
                    <?php echo form_checkbox(array('name' => 'no_any', 'id' => 'no_any', 'checked' => false, 'class' => '')) ?>

                </div>
                
            </div>
            <div class="row" >
                <!-- <div class="    col-md-1"></div> -->
                <div class="col-md-5">
                    <?php echo form_label('Fecha factura: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-3">
                    <?php echo form_input(array('type' => 'date', 'name' => 'fechaFactura', 'id' => 'fechaFactura', 'class' => 'form-control input-sm', 'value' => $hoy)) ?>
                </div>
                <div class="col-md-4" ></div>                
            </div> 
            <div class="row" >
                <!-- <div class="    col-md-1"></div> -->
                <div class="col-md-5">
                    <?php echo form_label('Transporte con IVA: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-3">
                    <?php echo form_input(array('type' => 'text', 'name' => 'transporte', 'id' => 'transporte', 'class' => 'form-control input-sm factura', 'value' => '')) ?>
                </div>
                 <div class="col-md-4" ></div>                
            </div> 
         <div class="row" style="padding-bottom: 10px">
             <!-- <div class="    col-md-1"></div> -->
                <div class="col-md-5" style="padding-right:0px">
                    <?php echo form_label('Agrupar prod. iguales: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-3" style="padding-top:5px;padding-left:3px;">
                       <?php echo form_checkbox(array('name' => 'agrupar', 'id' => 'agrupar', 'checked' => true, 'class' => '')) ?>
                </div>
                <div class="col-md-4" ></div>   
         </div>
            
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-9" id='error' style="color:red"></div>
            </div>
            
            
            
            
           
            <div class="row">
                <div class="col-md-1"></div>
                
                <div class="col-md-11">
            <a href="#" class="btn btn-primary btn-sm" id="generarFactura" >
                
            Generar Factura <img class="ajax-loader hide"   src="<?php echo base_url('images/ajax-loader-2.gif') ?>"></a>
        
        </div>
                
            </div>
    </form>
            
              
    
    
    
   




    
 
    
    
    

<script>
    $(document).ready(function(){
        
       // $('#ticketsFecha').hide();
       
    $('select#tickets').change(function(){$('#error').html('')})   
    $('#numTicket').focus()

    var cambios=false 
   
    

    window.onbeforeunload=function(e) {
        console.log(e)
        var dialogText="No se ha generado la factura todavía. ¿Quiere abandonar la página?"
        if (cambios ) {
            e.returnValue=dialogText
            alert(dialogText)
            return dialogText
        }
            
    }
    
    


    $('#generarFactura').attr('disabled','disabled');
    
   var cliente="";
   
   $('#fecha').click(function(){
       $('#numTicket').val('')
        $('#error').html('')
        $('#erroresNumTickets').html('')
        $('option').remove();
        
    })

    $('#numTicket').click(function(){
        $(this).val('')
        $('#fecha').val('')
        $('#error').html('')
        $('#erroresNumTickets').html('')
        $('option').remove();
        $('#añadir').addClass('hide')
        $(this).focus()
    })
    
    // $('#numFactura_').change(function(){
        
    //    // var reg=/^2[0-9][0-9][0-9]\/[0-9][0-9][0-9][0-9][0-9]$/
    //    var y=new Date().getFullYear()
    //     var aa=y.toString().substring(2,4)

    //     var reg=new RegExp("^B"+aa+"[0-9]{3}$"); 
    //     var numFactura=$(this).val();
    //     if(!reg.test(numFactura)){
    //         alert("El formato del número de la factura es incorrecto ("+numFactura+"):\nDebe ser Baaxxx (aa dos últimos digitos del año)")
    //         $(this).val('');
    //     }
    //     //comprobamos si existe el núm de factura
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url() ?>"+"index.php/facturas/comprobarId_factura", 
    //         data:{'numFactura':numFactura},
    //         success: function(datos){
    //             if (datos>0){
    //                 alert("Este número de factura ("+numFactura+") YA existe. \n\nCambie el número.")
    //                 $('#numFactura').val('');
    //             }else{
    //                 $('#numFacturaValido').val(numFactura);
    //             }
    //         },
    //         error: function(){
    //             alert("Error en el proceso. Informar");
    //         }
    //     });         
        
        
    // })
    
    
    $('#generarFactura').click(function(e){
        e.preventDefault()
        $('.ajax-loader').removeClass('hide')
        var numFactura=$('#numFactura').val();
        var y=new Date().getFullYear()
        var aa=y.toString().substring(2,4)

        var reg=new RegExp("^B"+aa+"[0-9]{3}$"); 
        var no_any=($('input#no_any').is(':checked'))
        console.log(no_any)
        if(!no_any){
        //comprueba formato número factura
        if(!reg.test(numFactura)){
            $('.ajax-loader').addClass('hide')
           alert("El formato del número de la factura es incorrecto ("+numFactura+").\nDebe ser Baaxxx (aa dos últimos digitos del año actual).\nSi se desea utilizar otro año, marcar 'No control año'")
            return false;
        }
        }
        
        //Comprueba si numero fatura existe. SI no existe genera la factura
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/facturas/comprobarId_factura", 
            data:{'numFactura':numFactura},
            success: function(datos){
                if (datos>0){
                    $('.ajax-loader').addClass('hide')
                    alert("Este número de factura ("+numFactura+") YA existe. \n\nCambiar el número.")
                    
                    return false;
                    $('#numFactura').val('');
               }else{
                  // alert("<?php echo base_url() ?>"+"index.php/facturas/excelFactura");
                   $.ajax({
                       type: "POST",
                       url: "<?php echo base_url() ?>"+"index.php/facturas/excelFactura", 
                       data: $('form.factura').serialize(),
                       success:function(datos){
                           //alert(datos);
                           $('.ajax-loader').addClass('hide')
                          var datos=$.parseJSON(datos)  

                          if (datos==false) {alert("Tickets duplicados en esta factura.\nFactura no generada.\nActualizar la página y escoger los tickets correctos.");}
                           else{
                           
                          //  alert("Factura generada correctamente");
                           var direccion="<?php echo base_url() ?>index.php/GestionTablas/facturas";
                           //alert(direccion)
                          // direccion=direccion.replace(/"/g, "");
                        //   alert(direccion)
                        //window.open(direccion )
                        // similar behavior as an HTTP redirect
                        cambios=false
window.location.assign(direccion);

// similar behavior as clicking on a link
//window.location.href = "http://stackoverflow.com";
                    }
                       },
                       error: function(){
                       
                    }
                       
                   })
             }
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }
           })
    })
    
    // $('form.factura_').submit(function(e){
      
    //    //e.preventDefault();
    //     var numFactura=$('#numFactura').val();
    //     var y=new Date().getFullYear()
    //     var aa=y.toString().substring(2,4)

    //     var reg=new RegExp("^B"+aa+"[0-9]{3}$"); 
    //     //var numFactura=$(this).val();
    //     if(!reg.test(numFactura)){
    //         alert("El formato del número de la factura es incorrecto ("+numFactura+")..\nDebe ser Baaxxx (aa dos últimos digitos del año)")

    //        // $(this).val('');
    //         e.preventDefault();
    //         return false;
    //     }
        
        
    //     else{
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url() ?>"+"index.php/facturas/comprobarId_factura", 
    //         data:{'numFactura':numFactura},
    //         success: function(datos){
               
    //             if (datos>1){
    //                 alert("Este número de factura ("+numFactura+") YA existe. \n\nCambie el número.")
    //                 e.preventDefault();
    //                 return false;
    //             }else{
                     
    //                 $('#numFacturaValido').val(numFactura);
    //                // redirect("<?php echo base_url() ?>"+"index.php/facturas/excelFactura")
    //             }
    //         },
    //         error: function(){
    //             alert("Error en el proceso. Informar");
    //         }
    //     });  
    //     }
        
        
        
  
    // }) 
    
 
    
    $('#fecha').change(function(e){
        e.preventDefault();
        var fecha=$('#fecha').val();
       // alert('fecha '+fecha);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/facturas/getTicketsDiaClientes", 
            data: {'fecha':fecha},
            success: function(datos){
                //alert(datos)
                var tickets=$.parseJSON(datos);
                if(!tickets.length) $('#error').html('No existen tickets en esa fecha y Cliente asignado. Compruebe que está cargado el Boka y/o que el ticket tiene asignado un cliente')
               
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
                alert("Error en el proceso. Informar");
            }
        });
       
            
    });

    $('#numTicket').keyup(function(e){
        if(e.keyCode==13){
           $('#buscar').trigger('click')       
        }
    })

    
    $('#buscar').click(function(e){
        e.preventDefault();
        var numTicket=$('#numTicket').val();
        //alert(fecha);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/facturas/getTicketsDiaFechaClientes", 
            data: {'numTicket':numTicket},
            success: function(datos){
                //alert(datos.length)
               
                var fechas=$.parseJSON(datos);
               // alert(fechas)
               

                
                $('#num2').html(fechas.length+' tickets')
                if(!fechas.length) $('#erroresNumTickets').html('No existen tickets en este número y Cliente asignado. Compruebe que está cargado el Boka y/o que el ticket tiene asignado un cliente')
                $.each(fechas, function( index, value ) {
                   var n=fechas[index].indexOf(' ')+1
                    var num= fechas[index].substring(0,n)
                    var dia=fechas[index].substring(n+8,n+10)
                    var mes=fechas[index].substring(n+5,n+7)
                    var año=fechas[index].substring(n+0,n+4)
                    var resto=fechas[index].substring(n+10)
                    var fechaEuropea=num+dia+'/'+mes+'/'+año+resto;
                   $('<option value="'+fechas[index]+'" >'+fechaEuropea+'</option>').appendTo('select#ticketsNum');
                });
                if(!$('select#ticketsNum > option').html()) return
                $('#añadir').removeClass('hide')

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
        if (ticket == null){
            ticket=$('#ticketsNum').val();
        }
       // alert(ticket)
       // alert(cliente);
        //obtenemos datos ticket
        
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/facturas/datosTicket", 
            data: {ticket:ticket},
            success: function(datos){
                //alert(datos);
                var resultado=$.parseJSON(datos);
                //alert(resultado['rasa'])
                if(resultado['rasa']!=""){
                    if(cliente==""){
                        cliente=resultado['id_cliente']
                       // alert(cliente);
                        $('#numCliente').html('Núm cliente: '+resultado['id_cliente'])
                        $('#nombreCliente').html('Nombre cliente: '+resultado['nombre'])
                        $('#empresaCliente').html('Empresa: '+resultado['empresa'])
                        $('<li>Ticket núm: '+ticket+' - Importe: '+resultado['importe']/100+' € </li>').appendTo('ul#ticketsEnFactura');
                        $('<input type="hidden" name="ticketsFactura[]" value="'+ticket+'">').appendTo('div#ticketsEnFacturaInputs');
                        $('#solicitud').show();
                        $('#generarFactura').removeAttr('disabled');
                        $('#numTicket').trigger('click')
                        cambios=true
                    }else{
                        if(cliente!=resultado['id_cliente']) {
                        $('#erroresNumTickets').html("El ticket seleccionado NO pertenece al mismo cliente, pertenece a "+resultado['id_cliente']+"-"+resultado['nombre'])
                        }else{
                            
                        $('<li>Ticket núm: '+ticket+' - Importe: '+resultado['importe']/100+' € </li>').appendTo('ul#ticketsEnFactura');
                        $('<input type="hidden" name="ticketsFactura[]" value="'+ticket+'">').appendTo('div#ticketsEnFacturaInputs');
                    }
                    }
                }else{
                    $('#error').html('Este ticket NO está asignado a un cliente. Introduzca el cliente en ticket y volver a generar factura.')
                }
                $('#ticket').val('')
                 },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
      
       
       
       
       
       
       
       
       
       
       
       
    
    
    });  
    
    
    });
    </script>
