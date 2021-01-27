<?php  
$results=$resultsACuenta;
$resultsTotales=$resultsACuentaTotales;

?>



<h3>VENTAS A CUENTA</h3>

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
                         <?php foreach($resultsTodas as $k=>$v) {?>
                        <tr>
                            <td data-halign="right"><?php echo formato2decimales($v->tipos/100) ?></td>
                            <?php $encontrado=false; 
                                foreach($results as $k1=>$v1) {
                                if ($v->tipos==$v1->tipos) $encontrado=$v1;} 
                                    if($encontrado){
                                        ?>
                                        <td data-halign="right"><?php echo formato2decimales($encontrado->bases/100) ?></td>
                                        <td data-halign="right"><?php echo formato2decimales($encontrado->ivas/100) ?></td>
                                        <td data-halign="right"><?php echo formato2decimales($encontrado->totales/100) ?></td>
                             <?php } else { ?>
                                        <td data-halign="right"><?php echo formato2decimales(0) ?></td>
                                        <td data-halign="right"><?php echo formato2decimales(0) ?></td>
                                        <td data-halign="right"><?php echo formato2decimales(0) ?></td>
                            <?php  } ?>
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

 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTienda').addClass('btn-primary');  
})
</script>

