<style type="text/css">
    .caja,.col-md-4{
        border:1px solid white;
    }
    label.caja{
        
    }
    .form-horizontal .control-label{
        padding-top: 4px;
        padding-bottom:  10px;
    }
    #error{
        color:red;
        font-weight: bold;
    }
    .moneda{
        text-align: right;
    }
    /*
    .row{
       
    }
    .form-horizontal .control-label{
        padding-top: 0;
    } 
    */
</style>

<?php
echo '<h3>Cierre Caja</h3><hr>';

?>
<form class='cierre form-horizontal ' action='' method='post' >
    <div class="row-fluid">
        <div class="col-md-6 caja">
            <div class="row-fluid">
                <div class="col-md-4 caja">
                   <?php echo form_label('Fecha cierre: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Cambio Noche: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Retiro en metálico: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Retiro en tarjeta: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Retiro vales: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Retiro cheques: ', '', array('class' => 'control-label caja', )); ?>
                    <?php echo form_label('Retiro transferencias: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Cobro atrasos: ', '', array('class' => 'control-label caja', )); ?>
                   <?php echo form_label('Ventas no cobradas: ', '', array('class' => 'control-label caja', )); ?>

                </div>
                <div class="col-md-4">
                   <?php echo form_input(array('type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'class' => 'form-control input-sm', 'value' =>date('Y-m-d'))) ?>
                   <?php echo form_input(array('type' => 'text', 'name' => 'cambioNoche', 'id' => 'cambioNoche', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>
                   <?php echo form_input(array('type' => 'text', 'name' => 'retiroMetalico', 'id' => 'retiroMetalico', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>
                   <?php echo form_input(array('type' => 'text', 'name' => 'retiroTarjeta', 'id' => 'retiroTarjeta', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>
                   <?php echo form_input(array('type' => 'text', 'name' => 'retiroVale', 'id' => 'retiroVale', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>
                   <?php echo form_input(array('type' => 'text', 'name' => 'retiroCheques', 'id' => 'retiroCheques', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>
                   <?php echo form_input(array('type' => 'text', 'name' => 'retiroTransferencia', 'id' => 'retiroTransferencia', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>

                     <?php echo form_input(array('type' => 'text', 'name' => 'cobroAtrasos', 'id' => 'cobroAtrasos', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>
                     <?php echo form_input(array('type' => 'text', 'name' => 'ventasACuentaCaja', 'id' => 'ventasACuentaCaja', 'class' => 'form-control input-sm moneda', 'value' => '0 €')) ?>

                </div> 
                <div class="col-md-4">
                    <?php echo form_label('Fecha cierre: ', '', array('class' => 'control-label caja','style'=>'visibility:hidden' )); ?>
                   <?php echo form_label('Cambio Noche: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>
                   <?php echo form_label('Retiro en metálico: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>
                   <?php echo form_label('Retiro en tarjeta: ', '', array('class' => 'control-label caja','style'=>'visibility:hidden' )); ?>
                   <?php echo form_label('Retiro vales: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>
                   <?php echo form_label('Retiro cheques: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>
                   <?php echo form_label('Retiro transferencias: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>

                       <?php echo form_label('Cobro atrasos: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>
                       <?php echo form_label('Ventas no cobradas: ', '', array('class' => 'control-label caja', 'style'=>'visibility:hidden' )); ?>
                </div> 
            </div>
            <div class="row-fluid">
                <div class="col-md-4">
                   <?php echo form_label('Notas: ', '', array('class' => 'control-label ', )); ?>
                </div>
                <div class="col-md-8 caja">
                   <?php echo form_textarea(array('rows'=>'2', 'placeholder'=>'Indicar anotaciones, incidencias, ... Máx 300 carácteres','name' => 'notas', 'id' => 'notas', 'class' => 'form-control input-sm', 'value' => '')) ?>
                </div>
                <div class="col-md-4 caja">
                </div>
                <div class="col-md-8 caja">
                <button id="procesarDatos" style="display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini" >
                        <span class="" aria-hidden="true"></span> Procesar datos
                    </button>
                
                <button id="registroDatosCierre" style="display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini " disabled="disabled" >
                        <span class="" aria-hidden="true"></span> Registrar cierre caja
                    </button>
                </div>
                <div class="col-md-4 caja">
                </div>
                <div class="col-md-8 caja">
                    <p id="error">

                    </p>
                </div>
                
            </div>
           
        </div>  
        <div class="col-md-6 caja" id="datosCierre">
            <div class="row-fluid">
                
                 
                 <div class="row-fluis" >
                     <div class="col-md-12 caja">
                         <h3>Diferencias</h3>
                     </div>
                     <div class="col-md-6 caja">
                         <h4>De Metálico</h4>
                     </div>
                     <div class="col-md-6 caja diferenciaCierreMetalicoInforme">
                         <h4>56.89 €</h4>
                     </div>
                     <div class="col-md-6 caja">
                         <h4>De Tarjetas</h4>
                     </div>
                     <div class="col-md-6 caja diferenciaCierreTarjetasInforme">
                         <h4>56.89 €</h4>
                     </div>
                     <div class="col-md-6 caja ">
                         <h4>De Transferencias</h4>
                     </div>
                     <div class="col-md-6 caja diferenciaCierreTransferenciasInforme">
                         <h4>56.89 €</h4>
                     </div>
                     <div class="col-md-6 caja">
                         <h4>De Talones</h4>
                     </div>
                     <div class="col-md-6 caja diferenciaCierreChequesInforme">
                         <h4>56.89 €</h4>
                     </div>
                     <div class="col-md-6 caja ">
                         <h4>De Vales</h4>
                     </div>
                     <div class="col-md-6 caja diferenciaCierreValesInforme">
                         <h4>56.89 €</h4>
                     </div>
                     <div class="col-md-6 caja ">
                         <h4>De Cambio Noche</h4>
                     </div>
                     <div class="col-md-6 caja cambioNoche">
                         <h4>56.89 €</h4>
                     </div>
                     
                     <?php if ($this->session->categoria != 2) { ?>
                     
                     <div class="col-md-12" >
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         RESUMEN TOTALES VENTA <span class="dia"></span>
                                     </th>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">Metálico: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="caja1">86876</td>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">Cheques: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="caja2">86876</td>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">Vales: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="caja3">86876</td>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">Tarjetas Crédito: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="caja4">86876</td>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">Transferencias: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="caja5">86876</td>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">A cuenta: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="caja6">86876</td>
                                 </tr>

                                 <tr>
                                     <th class="col-md-6 izquierda ">Total día: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="caja0">86876</th>
                                 </tr>

                             </thead>
                         </table>    
                         
                         <table class="table ticket" id="datosInformeMetalico" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA METALICO <span class="dia"></span>
                                     </th>
                                 </tr>

                                 <tr>
                                     <td class="col-md-6 izquierda ">Cambio Mañana: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="cambioMañanaInforme">86876</td>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Ventas Metálico: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="ventasMetalicoInforme">86876</td>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Retirado Metálico: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="retiradoMetalicoInforme">86876</td>
                                 </tr>
                                 
                                 
                                 <tr>
                                     <td class="col-md-6 izquierda ">Ventas NO cobradas: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="ventasNoCobradasInforme">86876</td>
                                 </tr>
                                 
                                 <tr>
                                     <td class="col-md-6 izquierda ">Cobros atrasos: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="cobrosAtrasosInforme">86876</td>
                                 </tr> 
                                 <tr>
                                     <th class="col-md-6 izquierda ">Existencia caja: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="existenciaCajaInforme">86876</th>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Cambio Noche: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="cambioNocheInforme">86876</td>
                                 </tr>
                                 
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Metálico: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="diferenciaCierreMetalicoInforme">86876</th>
                                 </tr>
                                 
                                 
                              

                             </thead>
                         </table>   
                         
                          <table class="table ticket" id="datosInformeTarjetas" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA TARJETAS CREDITO <span class="dia"></span>
                                     </th>
                                 </tr>

                                 
                                 <tr>
                                     <td class="col-md-6 izquierda ">Ventas Tarjetas: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="ventasTarjetasInforme">86876</td>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Retirado Tarjetas: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="retiradoTarjetasInforme">86876</td>
                                 </tr>
                                 
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Tarjetas: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="diferenciaCierreTarjetasInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
                         
                           <table class="table ticket" id="datosInformeVales" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA VALES <span class="dia"></span>
                                     </th>
                                 </tr>

                                 
                                 <tr>
                                     <td class="col-md-6 izquierda ">Ventas Vales: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="ventasValesInforme">86876</td>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Retirado Vales: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="retiradoValesInforme">86876</td>
                                 </tr>
                                 
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Vales: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="diferenciaCierreValesInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
                         
                           <table class="table ticket" id="datosInformeTransferencias" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA TRANSFERENCIAS <span class="dia"></span>
                                     </th>
                                 </tr>

                                 
                                 <tr>
                                     <td class="col-md-6 izquierda ">Ventas Transferencias: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="ventasTransferenciasInforme">86876</td>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Retirado Transferencias: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="retiradoTransferenciasInforme">86876</td>
                                 </tr>
                                 
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Vales: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="diferenciaCierreTransferenciasInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
                         
                           <table class="table ticket" id="datosInformeCheques" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA CHEQUES <span class="dia"></span>
                                     </th>
                                 </tr>

                                 
                                 <tr>
                                     <td class="col-md-6 izquierda ">Ventas Vales: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="ventasChequesInforme">86876</td>
                                 </tr>
                                 <tr>
                                     <td class="col-md-6 izquierda ">Retirado Vales: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha " id="retiradoChequesInforme">86876</td>
                                 </tr>
                                 
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Vales: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha " id="diferenciaCierreChequesInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
                         
                     
                     </div>
                     <?php } ?>
                     
                 </div>
                
            </div>
        </div>  
    </div>
</form>


         
                   


<!-- <div class="row"><div class="col-md-12"><p>&nbsp;</p></div></div> -->


  
 
        
<br />
<br />

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuCaja').addClass('btn-primary');
  $('#menuCierreCaja').addClass('btn-primary');  
})
</script>

 <script>
$(document).ready(function(){
    
    //variables globales
    var formmodified = 0;
    
    var fecha;
    var totalCajaInforme;
    var cambioNoche;
    var retiroMetalico;
    var retiroTarjeta;
    var retiroVale;
    var retiroCheques;
    var retiroTransferencia;
    var cobroAtrasos;
    var ventasACuentaCaja;
    var cambioMañana;
    var ventasMetalico;
    var ventasTarjeta;
    var ventasVale;
    var ventasCheque;
    var ventasTransferencia;
    var ventasACuenta;
    var aCuenta;
    var existenciaCaja;
    var desviacionCajaInforme;
    var diferenciaCierreMetalico;
    var desviacionCajaTarjetaInforme;
    var desviacionCajaValeInforme;
    var desviacionCajaChequeInforme;
    var desviacionCajaTransferenciaInforme;
    var diferenciaAnteriorInforme;
    var diferenciaCierreActualInforme;
    var nuevaDiferenciaInforme;
    var diferenciaTarjetas;
    var diferenciaVales
    var diferenciaCheques
    var diferenciaTransferencias
    var bancoSaldoAnterior
    var saldoBanco
    var cobrado
    var ventas
    var notas
    
    


    var notas;
    
    $('#datosCierre').hide();
   // $('#datosInforme').hide();
    
        $('.moneda').click(function(){
                    $('#error').html('')
                    if($(this).val('NaN')) 
                        {$(this).val('')}
        })

    
    $('.moneda').change(function(){
        var valor=parseFloat($(this).val())
        $(this).val(valor.toFixed(2)+' €')
        if(isNaN(valor.toFixed(2))){
            $('#error').html('Algún valor introducido NO es correcto')
        }
        else{
            $('#datosInforme').hide();
            formmodified = 1;
        }
    })
    
    $('#notas').change(function(){
        var valor=$(this).val()
       // alert(valor)
        valor=valor.substr(0, 300);
        $(this).val(valor)
    })
    
    
    
    
   
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
            return "No ha procesado o grabado los cambios.";
        }
    }
    
    $('#fecha').click(function(e){
        document.getElementById("error").innerHTML = "";
      //  $('#datosCierre').hide();
    })
    
    $('#registroDatosCierre').click(function(e){
        e.preventDefault();
       // alert('Falta implementarlo');
        formmodified=0;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/caja/grabarDatosCaja", 
            data: {fecha:fecha,
                    cambioMañana:cambioMañana,
                    ventaMetalico:ventasMetalico,
                    ventaTajeta:ventasTarjeta,
                    ventaACuenta:ventasACuenta,
                    ventaTransferencia:ventasTransferencia  ,
                    ventaVale:ventasVale,
                    ventaCheque:ventasCheque,
                    cobroAtrasos:cobroAtrasos,
                    ventaNoCobrada:ventasACuentaCaja,
                    retiroMetalico:retiroMetalico,
                    retiroTarjeta:retiroTarjeta,
                    retiroVale:retiroVale,
                    retiroCheque:retiroCheques,
                    retiroTransferencia:retiroTransferencia,
                    cambioNoche:cambioNoche,
                    saldoBanco:bancoSaldoAnterior,
                    notas:notas
                    
                   },
            success: function(datos){
              //  alert(datos)
                alert('Cierre caja guardado' )
                
                 },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        
        
        
    });
    
    
    
    $('#procesarDatos').click(function(e){
 
        {
          //$('#datosCierre').hide();
          formmodified=0;
        
       if(isNaN(parseInt($('#fecha').val()))){
           document.getElementById("error").innerHTML = "Introduzca una fecha correcta.";
           return false;
       }

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/buscarDatosCaja", 
            data: $('form.cierre').serialize(),
            success: function(datos){
               // alert('Control desarrollo AJAX \n'+datos)
                var resultados=$.parseJSON(datos);
                var valores=resultados['resultado']  //del ticket
                var datosCaja=resultados['cajaAnterior']  //del listado caja
                   // alert(datosCaja['cambioNoche'])
                    
                //valores['20'] registra las devoluciones (se suponen siempre en metñalico
                var devoluciones=isNaN(parseInt(valores['20']))?0:parseInt(valores['20']);
                 
                valores['1']= parseInt(valores['1'])-devoluciones;
                  // se ponen los datos en resumen ticket 
                  // #caja0=total 
                  // #caja1=metalico
                  // #caja2=cheque
                  // #caja3=vales
                  // #caja4=tarjeta
                  // #caja5=transferencia
                  // #caja6=a cuenta
                 var hayDatos=false;
                 for(var i=0;i<7;i++){
                     var v=parseFloat(valores[i.toString()])/100;
                     v= isNaN(v)?"":v.toFixed(2)+' €'
                     if (v!="") hayDatos=true
                      $('#caja'+i.toString()).html(v);
                 }
                 if (!hayDatos) {
                     $('#error').html("No existen datos balanzas o NO se han subido al programa.")
                     return false;
                 }

                 fecha=$('#fecha').val();

                 $('.dia').html(diaTexto(fecha))
                 
                 
                 cambioMañana=!resultados['cajaAnterior']?0:resultados['cajaAnterior']['cambioNoche'];
                 cambioMañana=parseFloat(cambioMañana);
                 ventasMetalico=parseFloat($('#caja1').html());
                 retiroMetalico=resultados['post']['retiroMetalico'];
                 retiroMetalico=parseFloat(retiroMetalico);
                 retiroMetalico=isNaN(retiroMetalico)?0:retiroMetalico
                 ventasACuentaCaja=resultados['post']['ventasACuentaCaja'];
                 ventasACuentaCaja=parseFloat(ventasACuentaCaja);
                 ventasACuentaCaja=isNaN(ventasACuentaCaja)?0:ventasACuentaCaja
                 cobroAtrasos=resultados['post']['cobroAtrasos'];
                 cobroAtrasos=parseFloat(cobroAtrasos);
                 cobroAtrasos=isNaN(cobroAtrasos)?0:cobroAtrasos
                 existenciaCaja=cambioMañana+ventasMetalico-retiroMetalico-ventasACuentaCaja+cobroAtrasos
                 cambioNoche=resultados['post']['cambioNoche'];
                 cambioNoche=parseFloat(cambioNoche);
                 cambioNoche=isNaN(cambioNoche)?0:cambioNoche
                 diferenciaCierreMetalico=cambioNoche-existenciaCaja
                 
                 ventasTarjeta=$('#caja4').html()
                 if(ventasTarjeta=="") ventasTarjeta=0;
                 ventasTarjeta=parseFloat(ventasTarjeta)
                 retiroTarjeta=resultados['post']['retiroTarjeta'];
                 retiroTarjeta=parseFloat(retiroTarjeta);
                 retiroTarjeta=isNaN(retiroTarjeta)?0:retiroTarjeta
                 diferenciaTarjetas=retiroTarjeta-ventasTarjeta;
                 
                 ventasVale=$('#caja3').html()
                 if(ventasVale=="") ventasVale=0;
                 ventasVale=parseFloat(ventasVale)
                 retiroVale=resultados['post']['retiroVale'];
                 retiroVale=parseFloat(retiroVale);
                 retiroVale=isNaN(retiroVale)?0:retiroVale
                 diferenciaVales=retiroVale-ventasVale;
                 
                 ventasCheque=$('#caja2').html()
                 if(ventasCheque=="") ventasCheque=0;
                 ventasCheque=parseFloat(ventasCheque)
                 retiroCheques=resultados['post']['retiroCheques'];
                 retiroCheques=parseFloat(retiroCheques);
                 retiroCheques=isNaN(retiroCheques)?0:retiroCheques
                 diferenciaCheques=retiroCheques-ventasCheque
                 
                 
                 ventasTransferencia=$('#caja2').html()
                 if(ventasTransferencia=="") ventasTransferencia=0;
                 ventasTransferencia=parseFloat(ventasTransferencia)
                 retiroTransferencia=resultados['post']['retiroTransferencia'];
                 retiroTransferencia=parseFloat(retiroTransferencia);
                 retiroTransferencia=isNaN(retiroTransferencia)?0:retiroTransferencia
                 diferenciaTransferencias=retiroTransferencia-ventasTransferencia; 

                 bancoSaldoAnterior==!resultados['cajaAnterior']?0:resultados['cajaAnterior']['bancoSaldo'];
                 bancoSaldoAnterior=parseFloat(bancoSaldoAnterior)
                 bancoSaldoAnterior=isNaN(bancoSaldoAnterior)?0:bancoSaldoAnterior
                 ventasACuenta=parseFloat($('#caja6').html());
                 if(ventasACuenta=="") ventasACuenta=0;
                 ventasACuenta=parseFloat(ventasACuenta)
                 cobrado=cambioNoche-cambioMañana+retiroMetalico+retiroTarjeta+retiroVale+retiroTransferencia+retiroCheques
                 ventas= ventasMetalico+ventasTarjeta+ventasVale+ventasTransferencia+ventasCheque+ventasACuenta;      
                 saldoBanco=bancoSaldoAnterior+cobrado-ventas
                 saldoBanco=formato2decimales(saldoBanco)
                 
                 notas=$('#notas').val();
                 
                 
                 
              //   alert('cobrado '+cobrado)
              //   alert('ventas '+ventas)
              //   alert('saldoBanco '+saldoBanco)
                 //Colocar información en resumen
                $('#cambioMañanaInforme').html(formato2decimales(cambioMañana)+' €')
                $('#ventasMetalicoInforme').html(formato2decimales(ventasMetalico)+' €')
                $('#retiradoMetalicoInforme').html(formato2decimales(retiroMetalico*-1)+' €') 
                $('#ventasNoCobradasInforme').html(formato2decimales(ventasACuentaCaja*-1)+' €')  
                $('#cobrosAtrasosInforme').html(formato2decimales(cobroAtrasos)+' €')  
                $('#existenciaCajaInforme').html(formato2decimales(existenciaCaja)+' €') 
                $('#cambioNocheInforme').html(formato2decimales(cambioNoche)+' €') 
                $('#diferenciaCierreMetalicoInforme').html(formato2decimales(diferenciaCierreMetalico)+' €') 
                if(diferenciaCierreMetalico<0) {
                     $('#diferenciaCierreMetalicoInforme').css('color','red')
                 }else {
                     $('#diferenciaCierreMetalicoInforme').css('color','black')
                 }
                 
                $('#ventasTarjetasInforme').html(formato2decimales(ventasTarjeta)+' €')
                $('#retiradoTarjetasInforme').html(formato2decimales(retiroTarjeta*-1)+' €') 
                $('#diferenciaCierreTarjetasInforme').html(formato2decimales(diferenciaTarjetas)+' €') 
                if(diferenciaTarjetas<0) {
                     $('#diferenciaCierreTarjetasInforme').css('color','red')
                 }else {
                     $('#diferenciaCierreTarjetasInforme').css('color','black')
                 } 
                 
                $('#ventasValesInforme').html(formato2decimales(ventasVale)+' €')
                $('#retiradoValesInforme').html(formato2decimales(retiroVale*-1)+' €') 
                $('#diferenciaCierreValesInforme').html(formato2decimales(diferenciaVales)+' €') 
                if(diferenciaVales<0) {
                     $('#diferenciaCierreValesInforme').css('color','red')
                 }else {
                     $('#diferenciaCierreValesInforme').css('color','black')
                 } 
                 
                 $('#ventasTransferenciasInforme').html(formato2decimales(ventasTransferencia)+' €')
                $('#retiradoTransferenciasInforme').html(formato2decimales(retiroTransferencia*-1)+' €') 
                $('#diferenciaCierreTransferenciasInforme').html(formato2decimales(diferenciaTransferencias)+' €') 
                if(diferenciaTransferencias<0) {
                     $('#diferenciaCierreTransferenciasInforme').css('color','red')
                 }else {
                     $('#diferenciaCierreTransferenciasInforme').css('color','black')
                 } 
                 
                 $('#ventasChequesInforme').html(formato2decimales(ventasCheque)+' €')
                $('#retiradoChequesInforme').html(formato2decimales(retiroCheques*-1)+' €') 
                $('#diferenciaCierreChequesInforme').html(formato2decimales(diferenciaCheques)+' €') 
                if(diferenciaCheques<0) {
                     $('#diferenciaCierreChequesInforme').css('color','red')
                 }else {
                     $('#diferenciaCierreChequesInforme').css('color','black')
                 } 

                $('#datosCierre').show();
                $('#datosInforme').show();
                $('#registroDatosCierre').removeAttr('disabled')
                formmodified=1;
                        },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
    }
        })
    
    function formato2decimales(valor){
        return parseFloat(valor).toFixed(2)
    }
    function diaTexto(fecha){
        var monthNames = [
                    "Enero", "Febrero", "Marzo",
                    "April", "Mayo", "Junio", "Julio",
                    "Agosto", "Septiembre", "Octubre",
                    "Noviembre", "Diciembre"
                ];
                
                var diasNames = [
                     "Domingo","Lunes", "Martes", "Miércoles",
                    "Jueves", "Viernes", "Sábado"
                ];
    


                var date = new Date(fecha);
                var day = date.getDate();
                var diaSemana=date.getDay();
                var monthIndex = date.getMonth();
                var year = date.getFullYear();

                return diasNames[diaSemana]+', '+day+' '+monthNames[monthIndex]+' '+year;
    }
    
    function informeCaja(){
          $('#datosCierre').hide();
        
       if(isNaN(parseInt($('#fecha').val()))){
           document.getElementById("error").innerHTML = "Introduzca una fecha correcta.";
           return false;
       }

       // e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/buscarDatosCaja", 
            data: $('form.cierre').serialize(),
            success: function(datos){
                //alert('Control desarrollo AJAX \n'+datos)
                var resultados=$.parseJSON(datos);
                    var valores=resultados['resultado']
                    
                //valores['20'] registra las devoluciones (se suponen siempre en metñalico
                $devoluciones=isNaN(parseInt(valores['20']))?0:parseInt(valores['20']);
                valores['1']=parseInt(valores['1'])+$devoluciones
            
                 for(var i=0;i<7;i++){
                     var v=parseFloat(valores[i.toString()])/100;
                     v= isNaN(v)?"":v.toFixed(2)+' €'
                      $('#caja'+i.toString()).html(v);
                 }
                 //lectura valor cambioNocheultimo dia contabilizado o 0 si no existe
                 var cambioMañana=!resultados['cajaAnterior']?0:resultados['cajaAnterior']['cambioNoche'];
                 
                 
                 var cambioNoche=resultados['post']['cambioNoche'];
                 var retiroMetalico=resultados['post']['retiroMetalico'];
                 var retiroTarjeta=resultados['post']['retiroTarjeta'];
                 var retiroVale=resultados['post']['retiroVale'];
                 var cobroAtrasos=resultados['post']['cobroAtrasos'];
                 var ventasACuenta=resultados['post']['ventasACuenta'];
                 
                 $('#cambioMañanaInforme').html(cambioMañana+' €')
                 var ventasMetalico=$('#caja1').html()
                 $('#ventasMetalicoInforme').html(ventasMetalico)
                 $('#cambioNocheInforme').html(cambioNoche)
                 $('#retiroMetalicoInforme').html(retiroMetalico)
                 $('#retiroTarjetasInforme').html(retiroTarjeta)
                 $('#retiroValesInforme').html(retiroVale)
                 $('#cobroAtrasosInforme').html(cobroAtrasos)
                 $('#ventasACuentaInforme').html(ventasACuenta)
                
                $('#datosCierre').show();
              
                        },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
    }
    
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


