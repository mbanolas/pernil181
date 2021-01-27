<?php echo $botones ?>
<hr>
    <h3 style="color:red">Ticket núm <?php echo $ticket['numero'].' '.$ticket['fecha'].' PROSESADO'?> </h3>
    
<?php $alternativa=false ?>
<?php //var_dump($ticketsPeriodo) ?>

<div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr><th colspan="3" class="col-md-12 izquierda"><?php echo $ticket['modo'] ?></th></tr>
                <?php if($ticket['cliente']!=="") { ?>
                <tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: <?php echo $ticket['cliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda "><?php echo $ticket['nombreCliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda" style=" border-bottom:1px solid black "><?php echo $ticket['empresa'] ?></td></tr>
                
                <?php } ?>
                <tr >
                    <td class="col-md-4 izquierda">Spdto <?php echo $ticket['subDepartamento'] ?></td>
                    <td class="col-md-4 centro">Caja <?php echo $ticket['numCaja'] ?></td>
                    <td class="col-md-4">#<?php echo $ticket['referencia'] ?></td>
                    
                </tr>
                <tr>
                    <td class="col-md-4 izquierda"><?php echo $ticket['fecha'] ?></td>
                    <td class="col-md-4 centro"><?php echo $ticket['numero'] ?>/<?php echo $ticket['numCaja'] ?> </td>
                    <td class="col-md-4">Depe <?php echo $ticket['dependiente'] ?></td>
                    
                </tr>
            </thead>
        </table>    
    </div>
    <!-- Alternativa -->
    <?php if($alternativa) { ?>
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr><th colspan="3" class="col-md-12 izquierda"><?php echo $ticket['modo'] ?></th></tr>
                <?php if($ticket['cliente']!=="") { ?>
                <tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: <?php echo $ticket['cliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda "><?php echo $ticket['nombreCliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda" style=" border-bottom:1px solid black "><?php echo $ticket['empresa'] ?></td></tr>
                
                <?php } ?>
                <tr >
                    <td class="col-md-4 izquierda">Spdto <?php echo $ticket['subDepartamento'] ?></td>
                    <td class="col-md-4 centro">Caja <?php echo $ticket['numCaja'] ?></td>
                    <td class="col-md-4">#<?php echo $ticket['referencia'] ?></td>
                    
                </tr>
                <tr>
                    <td class="col-md-4 izquierda"><?php echo $ticket['fecha'] ?></td>
                    <td class="col-md-4 centro"><?php echo $ticket['numero'] ?>/<?php echo $ticket['numCaja'] ?> </td>
                    <td class="col-md-4">Depe <?php echo $ticket['dependiente'] ?></td>
                    
                </tr>
            </thead>
        </table>    
    </div>
    <?php } ?>
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
    <!-- Alternativa -->
    <?php if($alternativa) { ?>
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
<!-- Alternativa -->
<?php if($alternativa) { ?>
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
                    <?php echo 'HOLAAAAAAAAAAAAAAA';foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
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
      
<!-- Alternativa -->
<?php if($alternativa) { ?>
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
<?php } ?>
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
                        <?php echo $ticket['formaPago'][$k] ?>
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
                    
                    
                    
            </tbody>
        </table>    
    </div>
    <!-- Alternativa -->
   <?php if($alternativa) { ?> 
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

                <?php ksort($ticket['formaPago']);foreach($ticket['formaPago'] as $k => $v) { ?>
                <tr>
                    <td colspan="2" class="col-md-3 izquierda">
                        <?php echo $ticket['formaPago'][$k] ?>
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
                    
                    
                    
            </tbody>
        </table>    
    </div>   
   <?php } ?> 
</div>
    <hr>

 <?php echo $botones ?>
    


<br /><br />

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTickets').addClass('btn-primary');
  $('#menuCopiaTicketProsesado').addClass('btn-primary');  
})
</script>

