<?php 

?>


<div id="datos_ticket">
<h3>Cambiar cliente y/o sistema de pago</h3>
<hr>
<div id='ticket'>
    <div id="div1">
        </div>
    <div id="div2">
            
        </div>
<h3>Ticket núm <?php echo $ticket['numero'].' '.$ticket['fecha'] ?><span style="font-weight: lighter">&nbsp;&nbsp;&nbsp;&nbsp; (<?php echo ($posicion) ?>/<?php echo $totalNumTickets ?>)</span> </h3>
    
<?php echo form_open('',array('role'=>'form', 'class'=> 'cambios form-horizontal')) ; ?> 
    <input type="hidden" name="numTicket" value="<?php echo $ticket['numero'].' '.$ticket['fecha'] ?>  ">
<?php 
$ticketsPeriodo[$posicion]=substr($ticketsPeriodo[$posicion],0,strpos($ticketsPeriodo[$posicion],'- Total:'));

 ?>
<?php if ($ticket['cliente'] === "") $ticket['cliente'] = "-1"; ?>
<?php if ($ticket['nombreCliente'] === "") $ticket['nombreCliente'] = "-1"; ?>
<input type="hidden" value="<?php echo $ticket['cliente'] ?>" id="id_cliente_h"> 


 <div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr><th colspan="3" class="col-md-12 izquierda"><?php echo $ticket['modo'] ?></th></tr>
                
                <?php //if ($ticket['empresa'] == "") $ticket['empresa'] = "Sin empresa asignada"; ?>
                <tr>
                    <td class="col-md-6 izquierda ">Núm Cliente: </td>
                    <td class="col-md-6 izquierda "><?php echo form_dropdown('cliente', $id_clientes, $ticket['cliente'],array('id'=>'id_cliente','class'=>'cambiar')); ?></td>
                </tr>
                            
                <tr>
                    <td class="col-md-6 izquierda ">Cliente: <?php echo $ticket['nombreCliente'] ?></td>
                    <td class="col-md-6 izquierda "><?php echo form_dropdown('', $clientes, $ticket['cliente'],array('id'=>'empresa','class'=>'cambiar')); ?></td>
                </tr>                

                <tr >
                    <td class="col-md-4 izquierda">Spdto <?php echo $ticket['subDepartamento'] ?></td>
                    <td class="col-md-4 centro">Caja <?php echo $ticket['numCaja'] ?></td>
                    <td class="col-md-4">#<?php echo $ticket['referencia'] ?>
                        <input type="hidden" name="referencia" value="<?php echo $ticket['referencia'] ?>" >
                    </td>
                        
                </tr>
                <tr>
                    <td class="col-md-4 izquierda"><?php echo $ticket['fecha'] ?>
                        <input type="hidden" name="fecha" value="<?php echo fechaEuropeaToBaseDatos($ticket['fecha']) ?>" >
                    </td>
                    <td class="col-md-4 centro"><?php echo $ticket['numero'] ?>/<?php echo $ticket['numCaja'] ?> </td>
                    <td class="col-md-4">Depe <?php echo $ticket['dependiente'] ?></td>

                </tr>
            </thead>
        </table>    
    </div>
    
</div>


<div class="row">
<?php if ($ticket['piezas']) { ?>
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
               <tr style="border:2px solid grey;">
                    <th class="col-md-3">Pza.</th>
                    <th class="col-md-3">I.V.A.</th>
                    <th class="col-md-3">€/Pza</th>
                    <th class="col-md-3">€</th>
                </tr> 
            </thead>
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                        <?php if ($v =="1" || $v=="3" ){ ?> 
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo $ticket['productos'][$k] ?></th>
                            </tr>
                            <tr>
                                <td class="col-md-3"><?php echo $ticket['unidades'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['preciosUnitarios'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>
                            <?php if ($ticket['descuentos'][$k] !=0) { ?>  
                             <tr>
                                 <td colspan="3" class="col-md-3 izquierda">Su ventaja</td>
                                
                                <td class="col-md-3"><?php echo $ticket['descuentos'][$k] ?></td>
                            </tr>
                            
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
<?php } ?>  
    
</div>

 <div class="row">
<?php if ($ticket['pesados']) { ?>

    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr style="border:2px solid grey;">
                    <th class="col-md-3">kg</th>
                    <th class="col-md-3">I.V.A.</th>
                    <th class="col-md-3">€/kg</th>
                    <th class="col-md-3">€</th>
                </tr> 
            </thead>
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                        <?php if ($v =="0" || $v=="4" ){ ?> 
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo $ticket['productos'][$k] ?></th>
                            </tr>
                            <tr>
                                <td class="col-md-3"><?php echo $ticket['pesos'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['preciosUnitarios'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>

                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
     <?php } ?>  


 </div>

<!-- añadir al final productos las entragas negativas cod 999998 -->    
<div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                        <?php if ( $v=='2') { ?>  
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo ucfirst(strtolower($ticket['productos'][$k])) ?></th>
                            </tr>
                            <tr>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>
                            
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
      

 </div>    
<!-- fin añadir al final productos las entragas negativas cod 999998 -->      

 <div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            
            <tbody>
                <tr>
                    <th  class="col-md-3 izquierda ticketTotal">
                        <?php echo $ticket['numPartidasTicket'] ?> Part
                    </th>
                    <th class="col-md-3 centro ticketTotal">
                        Suma
                    </th>
                    <th class="col-md-3 centro ticketTotal">
                        €
                    </th>
                    <th class="col-md-3" style="font-size: 20px">
                        <?php echo $ticket['totalTicket'] ?>
                    </th>
                </tr>
                <?php 
                ksort($ticket['formaPago']);
                foreach($ticket['formaPago'] as $k => $v) { ?>
                <tr>
                    <td colspan="2" class="col-md-3 izquierda">
                        <?php 
                       
                        if ($k!=20){
                            
                           echo 'Forma de pago: '.form_dropdown('formaPago', $formasPago, $ticket['formaPago'][$k],array('id'=>'formaPago','class'=>'cambiar')); 
                        }
                        else {
                        echo $ticket['formaPago'][$k];
                        }
                        ?>
                    </td>
                    
                    <td class="col-md-3 centro">
                        <?php echo $ticket['importeFormaPago'][$k]!=""?"€":"" ?>
                    </td>
                    <td class="col-md-3">
                        <?php echo $ticket['importeFormaPago'][$k] ?>
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td colspan="4" class="col-md-3 izquierda">
                        En la suma se incluye
                    </td>
                  
                </tr>
                <tr>
                    <td class="col-md-3 izquierda">
                    </td>
                    <td class="col-md-3">
                        I.V.A.
                    </td>
                    <td class="col-md-3">
                        Neto
                    </td>
                    <td class="col-md-3">
                        Bruto
                    </td>
                </tr>
                
                <?php foreach ($ticket['tipoIvasSum'] as $k => $v) { ?>
                    <tr>
                        <td colspan="4" class="col-md-3 izquierda"><?php echo $v ?>% <?php echo $ticket['textos'][$k] ?></td>
                    </tr>
                    <tr>
                        <td class="col-md-3"></td>
                        <td class="col-md-3"><?php echo $ticket['ivas'][$k] ?></td>
                        <td class="col-md-3"><?php echo $ticket['netos'][$k] ?></td>
                        <td class="col-md-3"><?php echo $ticket['brutos'][$k] ?></td>
                    </tr>
                <?php } ?>
                 <?php if ($ticket['sumaIvas']>0){ ?>   
                    <tr>
                        <td class="col-md-3">Suma</td>
                        <td style="border-top:1px solid black" class="col-md-3"><?php echo $ticket['sumaIvas'] ?></td>
                        <td class="col-md-3"></td>
                        <td class="col-md-3"></td>
                    </tr>
                 <?php } ?>
                 
                <tr>
                    <td colspan="4" class="col-md-3 izquierda"><?php echo $ticket['fechaCierre'] ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="col-md-3 izquierda">ATES PER <?php echo $ticket['nombreDependiente'] ?></td>
                </tr>    
                <tr>
                    <th   colspan="6" class="col-md-3 " style="padding-top:10px" >
                        <button style="display: inline;" type="submit" class="btn btn-primary btn-mini" id="guardarModificaciones">
                                <span class="" aria-hidden="true"></span> Guardar modificaciones
                        </button>
                    </th>  
                </tr>    
                    
                    
            </tbody>
        </table>    
        <?php echo \form_close(); ?>
    </div>
   
</div>
    </div>
 </div>
    
<hr>
<br /><br />



<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTickets').addClass('btn-primary');
  $('#menuCambiarTickets').addClass('btn-primary');  
})
</script>


    
    