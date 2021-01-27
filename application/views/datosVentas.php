<style type="text/css">
    .convertidas{
        color:blue; 
    }
    .diferenciasConvertidas{
        color:red; 
    }
</style>

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTienda').addClass('btn-primary');  
})
</script>

<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
        $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; 
$results=$resultsTodas;
$resultsTotales=$resultsTodasTotales;
$resultsStyp6=$resultsTodasStyp6;
$resultsTotalesStyp6=$resultsTodasTotalesStyp6;

$results1=$resultsTodas1;
$resultsTotales1=$resultsTodasTotales1;
$resultsStyp61=$resultsTodasStyp61;
$resultsTotalesStyp61=$resultsTodasTotalesStyp61;

//si existen datos en la tabla auxiliar de tickets antiguos, los calcula
if(!empty($resultsTodasStyp6)){
    $resultsTotales->totales+=$resultsTotalesStyp6->totales;
    $resultsTotales->bases+=$resultsTotalesStyp6->bases;
    $resultsTotales->ivas+=$resultsTotalesStyp6->ivas;
    foreach($results as $k=>$v){
        $results[$k]->totales+=$resultsStyp6[$k]->totales;
        $results[$k]->bases+=$resultsStyp6[$k]->bases;
        $results[$k]->ivas+=$resultsStyp6[$k]->ivas;
    }
}

//si existen datos en la tabla auxiliar de tickets antiguos, los calcula
if(!empty($resultsTodasStyp61)){
    $resultsTotales1->totales+=$resultsTotalesStyp61->totales;
    $resultsTotales1->bases+=$resultsTotalesStyp61->bases;
    $resultsTotales1->ivas+=$resultsTotalesStyp61->ivas;
    foreach($results1 as $k=>$v){
        $results1[$k]->totales+=$resultsStyp61[$k]->totales;
        $results1[$k]->bases+=$resultsStyp61[$k]->bases;
        $results1[$k]->ivas+=$resultsStyp61[$k]->ivas;
    }
}
?>

<h3>TODAS LAS VENTAS</h3>
<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                         <?php foreach($results as $k=>$v) {?>
                        <tr>
                           
                            <td data-halign="right"><?php echo formato2decimales($v->tipos/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->totales/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->totales/100) ?></th>
                            
                        </tr>
                        </tbody>
                    </table>
<br />
<h3>FORMAS DE PAGO</h3>
<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="left">Forma de Pago</th>
                            <th data-halign="right">Importe</th>
                             
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($getDatosVentasBokaTotalesFormaPago['metalico']) { ?>
                            <tr>
                                <th data-halign="left">Metálico</th>
                                <td data-halign="right"><?php echo formato2decimales($getDatosVentasBokaTotalesFormaPago['metalico']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago['tarjeta']) { ?>
                            <tr>
                                <th data-halign="left">Tarjeta</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago['tarjeta']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago['aCuenta']) { ?>
                            <tr>
                                <th data-halign="left">A cuenta</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago['aCuenta']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago['transferencia']) { ?>
                            <tr>
                                <th data-halign="left">Transferencia</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago['transferencia']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago['cheque']) { ?>
                            <tr>
                                <th data-halign="left">Cheque</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago['cheque']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago['vale']) { ?>
                            <tr>
                                <th data-halign="left">Vale</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago['vale']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th data-halign="left"></th>
                                <th data-halign="right"><?php echo  formato2decimales(array_sum($getDatosVentasBokaTotalesFormaPago)/100) ?></th>
                            </tr>
                        
                        </tbody>
</table>
<br />
<hr>

<h3 class="convertidas">TODAS LAS VENTAS CONVERTIDAS</h3>
<table class="table convertidas">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                         <?php foreach($results1 as $k=>$v) {?>
                        <tr>
                           
                            <td data-halign="right"><?php echo formato2decimales($v->tipos/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->totales/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales1->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales1->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales1->totales/100) ?></th>
                            
                        </tr>
                        </tbody>
                    </table>
<br />
<h3 class="convertidas">FORMAS DE PAGO CONVERTIDAS</h3>
<table class="table convertidas" >
                        <thead>
                        <tr>
                            <th data-halign="left">Forma de Pago</th>
                            <th data-halign="right">Importe</th>
                             
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($getDatosVentasBokaTotalesFormaPago1['metalico']) { ?>
                            <tr>
                                <th data-halign="left">Metálico</th>
                                <td data-halign="right"><?php echo formato2decimales($getDatosVentasBokaTotalesFormaPago1['metalico']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago1['tarjeta']) { ?>
                            <tr>
                                <th data-halign="left">Tarjeta</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago1['tarjeta']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago1['aCuenta']) { ?>
                            <tr>
                                <th data-halign="left">A cuenta</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago1['aCuenta']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago1['transferencia']) { ?>
                            <tr>
                                <th data-halign="left">Transferencia</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago1['transferencia']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago1['cheque']) { ?>
                            <tr>
                                <th data-halign="left">Cheque</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago1['cheque']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($getDatosVentasBokaTotalesFormaPago1['vale']) { ?>
                            <tr>
                                <th data-halign="left">Vale</th>
                                <td data-halign="right"><?php echo  formato2decimales($getDatosVentasBokaTotalesFormaPago1['vale']/100) ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th data-halign="left"></th>
                                <th data-halign="right"><?php echo  formato2decimales(array_sum($getDatosVentasBokaTotalesFormaPago1)/100) ?></th>
                            </tr>
                        
                        </tbody>
</table>
<br />
<hr>
<h3 class="diferenciasConvertidas">DIFERENCIAS</h3>
<table class="table diferenciasConvertidas">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                         <?php foreach($results1 as $k=>$v) {?>
                        <tr>
                            <?php $v0=$results[$k]; ?>
                            <td data-halign="right"><?php echo formato2decimales(($v->tipos)/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales(($v0->bases-$v->bases)/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales(($v0->ivas-$v->ivas)/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales(($v0->totales-$v->totales)/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales->bases-$resultsTotales1->bases)/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales->ivas-$resultsTotales1->ivas)/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales->totales-$resultsTotales1->totales)/100) ?></th>
                            
                        </tr>
                        </tbody>
                    </table>
<br />

