<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
        $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; 
$results=$resultsTodas;
$resultsTotales=$resultsTodasTotales;

$results2=$resultsTodas2;
$resultsTotales2=$resultsTodasTotales2;

?>

<h3>TODAS LAS VENTAS</h3>

<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            <th></th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            <th data-halign="right">Diferencias<br />Importes</th>
                            <th data-halign="right">Diferencias<br />IVAs</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($results as $t=>$v) {?>
                        <tr>
                            <td data-halign="right"><?php echo formato2decimales($results[$t]->tipos/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results[$t]->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results[$t]->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results[$t]->totales/100) ?></td>
                            <td></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$t]->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$t]->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$t]->totales/100) ?></td>
                            
                            <td data-halign="right"><?php echo formato2decimales($results2[$t]->totales/100-$results[$t]->totales/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$t]->ivas/100-$results[$t]->ivas/100) ?></td>
                        </tr>
                          <?php } ?>
                        
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->totales/100) ?></th>
                            <th></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->totales/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales2->totales/100-$resultsTotales->totales/100)) ?></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales2->ivas/100-$resultsTotales->ivas/100)) ?></th>
                        </tr>
                        </tbody>
                    </table>


<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuDiferenciasTienda').addClass('btn-primary');  
})
</script>
