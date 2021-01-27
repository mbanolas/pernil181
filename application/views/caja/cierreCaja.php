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
    
    #notas{
       
    }
</style>

<?php
echo '<h3>Cierre Caja</h3><hr>';

?>

<div class="container">
    <div class="row">
    <div class="col-lg-6">
        <fieldset>
            

            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Fecha cierre: ', '', array('class' => 'col-lg-5 control-label caja',)); ?>
                    <div class="col-lg-4">
                        <?php echo form_input(array('type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'class' => 'form-control input-sm', 'value' => date('Y-m-d'))) ?>
                        <?php echo form_error('fecha'); ?>
                    </div>
                    <div class='col-lg-2' style="padding-left:0px">
                        <button class='btn btn-default ' id="ir" style="padding:2px 8px 5px 8px">Ir</button>
                    </div>
                    
                </div>
            </div>
            
            <?php $campos=array('Cambio Mañana'=>'cambioManana',
                'Retiro en Metálico'=>'retiroMetalico',
                'Retiro en Tarjetas'=>'retiroTarjeta',
                'Retiro en Vales'=>'retiroVale',
                'Retiro en Cheques'=>'retiroCheque',
                'Retiro en Transferencias'=>'retiroTransferencia',
                'Cambio Noche'=>'cambioNoche',
                'Cobro Atrasos en Metálico'=>'cobroAtrasosMetalico',
                'Cobro Atrasos con Tarjeta'=>'cobroAtrasosTarjeta',
                'Ventas NO cobradas'=>'ventaNoCobrada',
                
                ) ?>
            <div id="formIntro">  
            <?php echo form_open('',array( 'class' => 'cierre')); ?>
            <?php foreach($campos as $k=>$v){ ?>
               
                
                <div class="row">
                <div class="form-group caja">
                    <?php echo form_label("$k: ", '', array('class' => 'col-xs-5 control-label caja_',)); ?>
                    <div class="col-xs-3">
                        <?php echo form_input(array('type' => 'text', 'name' => "$v",  'id' => "$v", 'class' => "form-control input-sm moneda ", 'value' => set_value("$v"))) ?>
                        <?php echo form_error("$v"); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Notas: ', '', array('class' => 'control-label col-xs-5',)); ?>
                    <div class="col-xs-6">
                        <?php echo form_textarea(array('rows' => '2', 'placeholder' => 'Indicar anotaciones, incidencias, ... Máx 300 carácteres', 'name' => 'notas', 'id' => 'notas', 'class' => 'form-control input-sm', 'value' => '')) ?>
                    </div>
                </div>
            </div>
                
            <?php echo form_input(array('type' => 'hidden', 'name' => 'saldoBanco', 'id' => 'saldoBanco',  'value' => '')) ?>
            <?php echo form_input(array('type' => 'hidden', 'name' => 'diferenciaCajaAcumulada', 'id' => 'diferenciaCajaAcumulada',  'value' => '')) ?>
                        
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-offset-5 col-lg-6 submitCaja">
                     <?php echo form_submit('registrarCierreCaja','Ver Cierre Caja','id="registrarCierreCaja"  class="btn btn-success submit"') ?>
                     
                      <?php //echo form_submit('grabarCierreCaja','Grabar  Cierre Caja','id="grabarCierreCaja_"  class="btn btn-success  " disabled="disabled"') ?>

                    </div>
                </div>
            </div>
                <?php echo form_close(); ?>
            </div>
        </fieldset>
    </div>
    
            
    <div class="col-lg-6" id="datosCierre">
                 <h3>Diferencias</h3>
                 <?php $camposInforme=array('De metálico'=>'diferenciaCierreMetalico',
                     'De tarjetas'=>'diferenciaCierreTarjeta',
                     'De vales'=>'diferenciaCierreVale',
                     'De cheques'=>'diferenciaCierreCheque',
                     'De transferencias'=>'diferenciaCierreTransferencia',
                     'Ventas NO cobradas'=>'ventaNoCobrada',
                     'Cobros atrasos en metálico'=>'cobroAtrasosMetalico',
                     'Cobros atrasos con tarjeta'=>'cobroAtrasosTarjeta'
                    // 'Desviación Caja'=>'diferenciaCierreCaja'
                     ); ?>
                 
                 <?php foreach($camposInforme as $k=>$v) { ?>
                 <div class="row">
                     <div class="col-md-6 caja">
                         <h4><?php echo $k ?></h4>
                     </div>
                     <div class="col-md-4 caja ">
                         <h4 class="<?php echo $v ?> derecha">0</h4>
                     </div>
                 </div>
                 <?php } ?>
         
        
            
       
                 <div class="row">
                     <div class="col-md-12 caja " id="botonDetalles" >

                         <?php echo form_submit('', 'Grabar  Cierre Caja', 'id="grabarCierreCaja"  class="btn btn-success  " disabled="disabled"') ?>
                         
                             <?php if ($_SESSION['categoria'] < "2") { ?>
                             <?php echo form_submit('', 'Mostrar detalles', 'id="mostrarDetalles"  class="btn btn-primary  "  ') ?>
                         <?php } ?>
                         <p> </p>
                     </div>
                 </div>
             
                 
           <div  id="detalles">
                 <div id="acumulados">
                     <div class="row">
                         <div class="col-md-6 caja">
                             <h4>Desviaciones Caja Acumuladas</h4>
                         </div>
                         <div class="col-md-4 caja ">
                             <h4 class="desviacionesCajaAcumuladas derecha">0</h4>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 caja">
                             <h4>Tickets Pendientes de Cobro</h4>
                         </div>
                         <div class="col-md-4 caja ">
                             <h4 class="ticketsPendientesCobro derecha">0</h4>
                         </div>
                     </div>
                 </div>
                 
                         <p> </p>
                    <div class="row">
                     <div class="col-md-10 ">
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         RESUMEN TOTALES VENTA <span class="dia"></span>
                                     </th>
                                 </tr>
                                 <?php $camposA=array('Metálico'=>'ventaMetalico',
                                     'Cheques'=>'ventaCheque',
                                     'Vales'=>'ventaVale',
                                     'Tarjetas Crédito'=>'ventaTarjeta',
                                     'Transferencias'=>'ventaTransferencia',
                                     'A cuenta'=>'ventaACuenta',
                                     ) ?>
                                  <?php foreach($camposA as $k=>$v) {?>
                                    <tr>
                                     <td class="col-md-6 izquierda "><?php echo $k ?>: <?php //echo $ticket['cliente']  ?></td>
                                     <td  class="col-md-6 derecha <?php echo $v ?> ">86876</td>
                                 </tr>
                                 <?php } ?>
                                 <tr>
                                     <th class="col-md-6 izquierda ">Total día: <?php //echo $ticket['cliente']  ?></th>
                                     <th  class="col-md-6 derecha totalDia">86876</th>
                                 </tr>
                             </thead>
                         </table>    
                     </div> 
                    </div>
                         <div class="row">
                     <div class="col-md-10 ">
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA METALICO <span class="dia"></span>
                                     </th>
                                 </tr>
                                 <?php
                                 $camposA = array(
                                     'Cambio Noche' => 'cambioNocheInforme',
                                     'Retirado Metálico' => 'retiroMetalicoInforme',
                                     'Cambio Mañana' => 'cambioMananaInforme',
                                     'Venta Metálico'=>'ventaMetalicoInforme',
                                     'Venta A Cuenta'=>'ventaACuentaInforme',
                                     'Venta NO Cobrada' => 'ventaNoCobradaInforme',
                                     'Cobro Atrasos Metálico' => 'cobroAtrasosMetalicoInforme',
                                     'Cobro Atrasos Tarjeta' => 'cobroAtrasosTarjetaInforme',
                                     
                                         )
                                 ?>
<?php foreach ($camposA as $k => $v) { ?>
                                     <tr>
                                         <td class="col-md-6 izquierda "><?php echo $k ?>: <?php //echo $ticket['cliente']   ?></td>
                                         <td  class="col-md-6 derecha <?php echo $v ?>">86876</td>
                                     </tr>
<?php } ?>
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Metálico Caja: <?php //echo $ticket['cliente']   ?></th>
                                     <th  class="col-md-6 derecha diferenciaCierreMetalicoCajaInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
</div> 
                    </div>
                         <div class="row">
                     <div class="col-md-10 ">
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA TARJETAS CREDITO <span class="dia"></span>
                                     </th>
                                 </tr>
                                 <?php
                                 $camposA = array('Ventas Tarjetas' => 'ventaTarjetaInforme',
                                     'Retirado Tarjetas' => 'retiroTarjetaInforme',
                                         )
                                 ?>
                                    <?php foreach ($camposA as $k => $v) { ?>
                                     <tr>
                                         <td class="col-md-6 izquierda "><?php echo $k ?>: <?php //echo $ticket['cliente']  ?></td>
                                         <td  class="col-md-6 derecha <?php echo $v ?>">86876</td>
                                     </tr>
                                    <?php } ?>
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Tarjetas: <?php //echo $ticket['cliente']   ?></th>
                                     <th  class="col-md-6 derecha diferenciaCierreTarjetaInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
</div> 
                    </div>
                         <div class="row">
                     <div class="col-md-10 ">
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA VALES <span class="dia"></span>
                                     </th>
                                 </tr>
                                 <?php
                                 $camposA = array('Ventas Vales' => 'ventaValeInforme',
                                     'Retirado Vales' => 'retiroValeInforme',
                                         )
                                 ?>
                                 <?php foreach ($camposA as $k => $v) { ?>
                                     <tr>
                                         <td class="col-md-6 izquierda "><?php echo $k ?>: <?php //echo $ticket['cliente']   ?></td>
                                         <td  class="col-md-6 derecha <?php echo $v ?>">86876</td>
                                     </tr>
                                <?php } ?>
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Vales: <?php //echo $ticket['cliente']   ?></th>
                                     <th  class="col-md-6 derecha diferenciaCierreValeInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
</div> 
                    </div>
                         <div class="row">
                     <div class="col-md-10 ">
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA TRANSFERENCIAS <span class="dia"></span>
                                     </th>
                                 </tr>
                                <?php
                                $camposA = array('Ventas Transferencias' => 'ventaTransferenciaInforme',
                                    'Retirado Transferencias' => 'retiroTransferenciaInforme',
                                        )
                                ?>
                                <?php foreach ($camposA as $k => $v) { ?>
                                     <tr>
                                         <td class="col-md-6 izquierda "><?php echo $k ?>: <?php //echo $ticket['cliente']   ?></td>
                                         <td  class="col-md-6 derecha <?php echo $v ?>">86876</td>
                                     </tr>
                                <?php } ?>
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Transferencias: <?php //echo $ticket['cliente']   ?></th>
                                     <th  class="col-md-6 derecha diferenciaCierreTransferenciaInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
</div> 
                    </div>
                         <div class="row">
                     <div class="col-md-10 ">
                         <table class="table ticket" >
                             <thead>
                                 <tr>
                                     <th colspan="2" class="col-md-12 izquierda">
                                         CAJA CHEQUES <span class="dia"></span>
                                     </th>
                                 </tr>
                                 <?php
                                 $camposA = array('Ventas Cheques' => 'ventaChequeInforme',
                                     'Retirado Cheques' => 'retiroChequeInforme',
                                         )
                                 ?>
                                <?php foreach ($camposA as $k => $v) { ?>
                                     <tr>
                                         <td class="col-md-6 izquierda "><?php echo $k ?>: <?php //echo $ticket['cliente']   ?></td>
                                         <td  class="col-md-6 derecha <?php echo $v ?>">86876</td>
                                     </tr>
                                <?php } ?>
                                 <tr>
                                     <th class="col-md-6 izquierda ">Diferencia Cierre Cheques: <?php //echo $ticket['cliente']   ?></th>
                                     <th  class="col-md-6 derecha diferenciaCierreChequeInforme">86876</th>
                                 </tr>
                             </thead>
                         </table>   
                     </div>
                         </div>
                     </div>
       
    </div>        
     
            
            </div>        
      

</div>

       
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
    
                $('.ventaMetalico').empty()
                $('.ventaTarjeta').empty()
                $('.ventaACuenta').empty()
                $('.ventaTransferencia').empty()
                $('.ventaVale').empty()
                $('.ventaCheque').empty()
                $('.totalDia').empty()
    
    
    $('#formIntro').hide();
    $('#detalles').hide();
    
    $('#ir').click(function(){
        fecha=$('#fecha').val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/caja/leerDatosCierreCaja", 
            data: {fecha:fecha },
            success: function(datos){
                //alert(datos); 
                var d=$.parseJSON(datos);
               
              //  var cambioManana=formateoNumero(d.cambioManana)
                var retiroMetalico=formateoNumero(d.retiroMetalico)
                var retiroTarjeta=formateoNumero(d.retiroTarjeta)
                var retiroTransferencia=formateoNumero(d.retiroTransferencia) 
                var retiroVale=formateoNumero(d.retiroVale)
                var retiroCheque=formateoNumero(d.retiroCheque)
                var cambioNoche=formateoNumero(d.cambioNoche)
                var cobroAtrasosMetalico=formateoNumero(d.cobroAtrasosMetalico)
                var cobroAtrasosTarjeta=formateoNumero(d.cobroAtrasosTarjeta)
                var ventaNoCobrada=formateoNumero(d.ventaNoCobrada)
                var diferenciaCajaAcumulada=formateoNumero(d.diferenciaCajaAcumulada)
                var saldoBanco=formateoNumero(d.saldoBanco)
                
           //     $('#cambioManana').val(cambioManana);
                $('#retiroMetalico').val(retiroMetalico);
                $('#retiroTarjeta').val(retiroTarjeta);
                $('#retiroTransferencia').val(retiroTransferencia);
                $('#retiroVale').val(retiroVale);
                $('#retiroCheque').val(retiroCheque);
                $('#cambioNoche').val(cambioNoche);
                $('#cobroAtrasosMetalico').val(cobroAtrasosMetalico);
                $('#cobroAtrasosTarjeta').val(cobroAtrasosTarjeta);
                $('#ventaNoCobrada').val(ventaNoCobrada);
                $('#saldoBanco').val(saldoBanco);
                $('#diferenciaCajaAcumulada').val(diferenciaCajaAcumulada);
        
              $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/caja/leerDatosAnterioresCaja", 
            data: {fecha:fecha },
            success: function(datos){
                //alert('datos anteriores '+datos); 
                var d=$.parseJSON(datos);
                //alert(d.cambioNocheAnterior)
                
                var cambioNocheAnterior=formateoNumero(d.cambioNocheAnterior)
                var saldoBanco=formateoNumero(d.saldoBanco)
                var diferenciaCajaAcumulada=formateoNumero(d.diferenciaCajaAcumulada)
                        
                $('#cambioManana').val(cambioNocheAnterior);
                $('#saldoBanco').val(saldoBanco);
                $('#diferenciaCajaAcumulada').val(diferenciaCajaAcumulada);
                 },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
              
              
                 },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        
        
        
        $('#formIntro').show();
        return false;
    })
    
    function formateoNumero(numero){
        numero=parseFloat(numero/100);
        if(numero==0) return ''
        if(isNaN(numero)) return ''
        numero=numero.toFixed(2)+' €'
        return numero
    }
    
    //muestra el número si es 0
    function formateoNumero0(numero){
        numero=parseFloat(numero/100);
        
        if(isNaN(numero)) return '0.00 €'
        numero=numero.toFixed(2)+' €'
        return numero
    }
    
    $('#grabarCierreCaja').click(function(e){
        formmodified=0
        e.preventDefault()
        var set= " fecha='"+fecha+"'" +
                 ", cambioManana='"+cambioManana+"'" +
                 ", ventaDia='"+ventaDia+"'" +
                 ", ventaMetalico='"+ventaMetalico+"'" +
                 ", ventaTarjeta='"+ventaTarjeta+"'" +
                 ", ventaACuenta='"+ventaACuenta+"'" +
                 ", ventaTransferencia='"+ventaTransferencia+"'" +
                 ", ventaVale='"+ventaVale+"'" +
                 ", ventaCheque='"+ventaCheque+"'" +
                 ", retiroMetalico='"+retiroMetalico+"'" +
                 ", retiroTarjeta='"+retiroTarjeta+"'" +
                 ", retiroVale='"+retiroVale+"'" +
                 ", retiroCheque='"+retiroCheque+"'" +
                 ", retiroTransferencia='"+retiroTransferencia+"'" +
                 ", cambioNoche='"+cambioNoche+"'" +
                 ", cobroAtrasosMetalico='"+cobroAtrasosMetalico+"'" +
                 ", cobroAtrasosTarjeta='"+cobroAtrasosTarjeta+"'" +
                 ", diferenciaMetalico='"+diferenciaMetalico+"'" +
                 ", diferenciaTarjeta='"+diferenciaTarjeta+"'" +
                  ", diferenciaTransferencia='"+diferenciaTransferencia+"'" +
                  ", diferenciaVale='"+diferenciaVale+"'" +
                  ", diferenciaCheque='"+diferenciaCheque+"'" +
                   ", ventaNoCobrada='"+ventaNoCobrada+"'" +
                  ", diferenciaCaja='"+diferenciaCaja+"'" +
                 ", saldoBanco='"+saldoBanco+"'" +
                 ", diferenciaCajaAcumulada='"+diferenciaCajaAcumulada+"'" +
                 ", notas='"+notas+"'" 
           
          //alert(set)
           
   
    $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/caja/grabarDatosCaja", 
            data: {fecha: fecha,
                    set:set
                   },      
            success: function(datos){
                var d=$.parseJSON(datos);
                location.reload();
                //alert(d)
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
    
    locate.href="<?php echo base_url() ?>"+"index.php/caja/cierreCaja"
    return false
    
    })
    
    
     $('#registrarCierreCaja').click(function(e){
         fecha=$('#fecha').val();
         saldoBanco=$('#saldoBanco').val();
        
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/caja/buscarDatosCaja", 
            data: $('form.cierre').serialize()+'&fecha='+ fecha ,
            success: function(datos){
                //alert('hola '+datos)
                var resultados=$.parseJSON(datos);
                //alert('hola '+resultados)
                totalDia=resultados['resultado'][0]
                console.log('totalDia '+totalDia)
                ventaMetalico=resultados['resultado'][1]-resultados['resultado'][20]
                 console.log('ventaMetalico '+ventaMetalico)
                  console.log("resultados['resultado'][1] "+resultados['resultado'][1])
                 console.log("resultados['resultado'][20] "+resultados['resultado'][20])
                ventaTarjeta=resultados['resultado'][4]
//                console.log("ventaTarjeta "+ventaTarjeta)
                ventaACuenta=resultados['resultado'][6]
                ventaTransferencia=resultados['resultado'][5]
                ventaVale=resultados['resultado'][3]
                ventaCheque=resultados['resultado'][2]
                
                notas=resultados['post']['notas']
                
                 cambioManana=parseFloat(resultados['post']['cambioManana'])*100 || 0
                 retiroMetalico=parseFloat(resultados['post']['retiroMetalico'])*100 || 0 
                 retiroTarjeta=parseFloat(resultados['post']['retiroTarjeta'])*100 || 0
                 retiroTransferencia=parseFloat(resultados['post']['retiroTransferencia'])*100 || 0
                 retiroVale=parseFloat(resultados['post']['retiroVale'])*100 || 0
                 retiroCheque=parseFloat(resultados['post']['retiroCheque'])*100 || 0
                 cambioNoche=parseFloat(resultados['post']['cambioNoche'])*100 || 0
                 cobroAtrasosMetalico=parseFloat(resultados['post']['cobroAtrasosMetalico'])*100 || 0
                 cobroAtrasosTarjeta=parseFloat(resultados['post']['cobroAtrasosTarjeta'])*100 || 0
                 ventaNoCobrada=parseFloat(resultados['post']['ventaNoCobrada'])*100 || 0
                saldoBancoAnterior=parseFloat(resultados['post']['saldoBanco'])*100 || 0
                diferenciaCajaAcumuladaAnterior=parseFloat(resultados['post']['diferenciaCajaAcumulada'])*100 || 0
                
                ventaDia=resultados['resultado'][0];//cambioNoche+retiroMetalico+retiroTarjeta+retiroTransferencia+retiroVale+retiroCheque-cambioManana+ventaNoCobrada-cobroAtrasosMetalico-cobroAtrasosTarjeta
                diferenciaMetalico=cambioNoche+retiroMetalico-cambioManana-ventaMetalico-ventaACuenta+ventaNoCobrada-cobroAtrasosMetalico
                diferenciaTarjeta=retiroTarjeta-ventaTarjeta-cobroAtrasosTarjeta
                diferenciaTransferencia=-ventaTransferencia+retiroTransferencia
                diferenciaVale=-ventaVale+retiroVale
                diferenciaCheque=-ventaCheque+retiroCheque
                saldoBanco=saldoBancoAnterior-ventaNoCobrada+cobroAtrasosMetalico+cobroAtrasosTarjeta
                diferenciaCaja=ventaDia-ventaMetalico-ventaTarjeta-ventaACuenta-ventaTransferencia-ventaVale-ventaCheque
                diferenciaCajaAcumulada=diferenciaCajaAcumuladaAnterior+diferenciaCaja
                
                $('.ventaMetalico').empty()
                $('.ventaTarjeta').empty()
                $('.ventaACuenta').empty()
                $('.ventaTransferencia').empty()
                $('.ventaVale').empty()
                $('.ventaCheque').empty()
                $('.totalDia').empty()
                
                if(ventaMetalico) $('.ventaMetalico').html(formateoNumero0(ventaMetalico))
                if(ventaTarjeta) $('.ventaTarjeta').html(formateoNumero0(ventaTarjeta))
                if(ventaACuenta) $('.ventaACuenta').html(formateoNumero0(ventaACuenta)) 
                if(ventaTransferencia) $('.ventaTransferencia').html(formateoNumero0(ventaTransferencia))
                if(ventaVale) $('.ventaVale').html(formateoNumero0(ventaVale))
                if(ventaCheque) $('.ventaCheque').html(formateoNumero0(ventaCheque))
                if(totalDia) $('.totalDia').html(formateoNumero0(totalDia))
                
                
                $('.cambioMañanaInforme').html(formateoNumero0(cambioManana))
                $('.retiroMetalicoInforme').html(formateoNumero0(retiroMetalico))
                $('.retiroTarjetaInforme').html(formateoNumero0(retiroTarjeta))
                $('.retiroValeInforme').html(formateoNumero0(retiroVale))
                $('.retiroChequeInforme').html(formateoNumero0(retiroCheque))
                $('.retiroTransferenciaInforme').html(formateoNumero0(retiroTransferencia))
                $('.cambioNocheInforme').html(formateoNumero0(cambioNoche))
                $('.cobroAtrasosMetalicoInforme').html(formateoNumero0(cobroAtrasosMetalico))
                $('.cobroAtrasosTarjetaInforme').html(formateoNumero0(cobroAtrasosTarjeta))
                $('.ventaNoCobradaInforme').html(formateoNumero0(ventaNoCobrada))
                
                
                $('.cambioNocheInforme').html(formateoNumero0(cambioNoche))   
                $('.retiroMetalicoInforme').html(formateoNumero0(retiroMetalico)) 
                $('.cambioMananaInforme').html(formateoNumero0(-cambioManana)) 
                $('.ventaMetalicoInforme').html(formateoNumero0(-ventaMetalico)) 
                $('.ventaACuentaInforme').html(formateoNumero0(-ventaACuenta)) 
                $('.ventaNoCobradaInforme').html(formateoNumero0(ventaNoCobrada)) 
                $('.diferenciaCierreMetalicoCajaInforme').html(formateoNumero0(diferenciaMetalico)) 
                
                
                 
                 
                 
                
                $('.ventaTarjetaInforme').html(formateoNumero0(ventaTarjeta))
                $('.ventaValeInforme').html(formateoNumero0(ventaVale))
                $('.ventaChequeInforme').html(formateoNumero0(ventaCheque))
                $('.ventaTransferenciaInforme').html(formateoNumero0(ventaTransferencia))
                
                

                $('.diferenciaCierreMetalico').html(formateoNumero0(cambioNoche+retiroMetalico-cambioManana-ventaMetalico-ventaACuenta+ventaNoCobrada-cobroAtrasosMetalico))
                $('.diferenciaCierreTarjeta').html(formateoNumero0(retiroTarjeta-ventaTarjeta))
                $('.diferenciaCierreVale').html(formateoNumero0(ventaVale-retiroVale))
                $('.diferenciaCierreCheque').html(formateoNumero0(ventaCheque-retiroCheque))
                $('.diferenciaCierreTransferencia').html(formateoNumero0(ventaTransferencia-retiroTransferencia))
                
                $('.cobroAtrasosMetalico').html(formateoNumero0(cobroAtrasosMetalico))
                $('.cobroAtrasosTarjeta').html(formateoNumero0(cobroAtrasosTarjeta))
                $('.ventaNoCobrada').html(formateoNumero0(ventaNoCobrada))
                
                
                $('.desviacionesCajaAcumuladas').html(formateoNumero0(diferenciaCajaAcumulada)) 
                $('.ticketsPendientesCobro').html(formateoNumero0(saldoBanco)) 
                
                $('.diferenciaCierreTarjetaInforme').html(formateoNumero0(ventaTarjeta-retiroTarjeta))
                $('.diferenciaCierreTransferenciaInforme').html(formateoNumero0(ventaTransferencia-retiroTransferencia))
                $('.diferenciaCierreValeInforme').html(formateoNumero0(ventaVale-retiroVale))
                $('.diferenciaCierreChequeInforme').html(formateoNumero0(ventaCheque-retiroCheque))
                $('.diferenciaCierreMetalicoInforme').html(formateoNumero0(cambioNoche+retiroMetalico-cambioManana-ventaMetalico-ventaACuenta+ventaNoCobrada-cobroAtrasosMetalico))
               
                
                $('#datosCierre').show()
                $('#grabarCierreCaja').removeAttr('disabled')
 
                 },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        
        //$('#formIntro').show();
        return false;
    })
    
    //variables globales
    formmodified = 0;
    
    
    $('#datosCierre').hide();
   // $('#datosInforme').hide();
    
        $('.moneda').click(function(){
                    $('#error').html('')
                    if($(this).val('NaN')) 
                        {$(this).val('')}
        })

    $('#mostrarDetalles').click(function(e){
        
        e.preventDefault()
        if ( $('#detalles').is(':visible') ){$('#detalles').hide();$(this).html('Mostrar detalles')}
        else {$('#detalles').show(); $(this).html('Ocultar detalles')}
        
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
       //alert(valor)
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
       // document.getElementById("error").innerHTML = "";
      //  $('#datosCierre').hide();
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


