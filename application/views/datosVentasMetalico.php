<?php  
$results=$resultsNoMetalico;
$resultsTotales=$resultsNoMetalicoTotales;

var_dump($getDatosVentasBokaTotalesFormaPago);

//caso de NO existir 'NoMetálicos' , recoje los totales.
if(empty($results)) $results=$resultsTodas

?>



<h3>VENTAS EN METALICO </h3><?php echo $getDatosVentasBokaTotalesFormaPago['metalico'] ?>

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
                             <?php foreach($resultsTodas as $k=>$v) {
                                 $bases[$k]=$v->bases;
                                 $ivas[$k]=$v->ivas;
                                 $totales[$k]=$v->totales;
                             }
                            foreach($results as $k=>$v) {
                             $v->bases=$bases[$k]-$v->bases;
                             $v->ivas=$ivas[$k]-$v->ivas;
                             $v->totales=$totales[$k]-$v->totales;

                             ?>
                        <tr>
                           
                            <td data-halign="right"><?php echo formato2decimales($v->tipos/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->totales/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                        <?php 
                                 $bases=$resultsTodasTotales->bases;
                                 $ivas=$resultsTodasTotales->ivas;
                                 $totales=$resultsTodasTotales->totales;
                             $resultsTotales->bases=$bases-$resultsTotales->bases;
                             $resultsTotales->ivas=$ivas-$resultsTotales->ivas;
                             $resultsTotales->totales=$totales-$resultsTotales->totales;

                             ?>
                        
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->totales/100) ?></th>
                            
                        </tr>

                        </tbody>
                    </table>

 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTienda').addClass('btn-primary');  
})
</script>

