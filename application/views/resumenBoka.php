<?php
$inicio=date('d/m/Y',  strtotime($inicio));
$final=date('d/m/Y',  strtotime($final));
echo "<h4>Resumen Ventas Tienda del $inicio al $final</h4>";
//var_dump($results);
?>
<h4>Total tickets: <?php echo $resumen['tickets'] ?></h4>
<h4>Tickets registrados: <?php echo $resumen['codigosSubidos'] ?></h4>

<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <?php
                        $totalBase=0;
                        $totalIVA=0;
                        $totalTotal=0;
                        foreach ($resumen['base'] as $t => $vt) {
                            $totalBase+=$vt/100;
                            $totalIVA+=$resumen['iva'][$t]/100;
                            $totalTotal+=$resumen['total'][$t]/100;
                            ?>
                        <tr>
                            <td data-halign="right"><?php echo $t ?></td>
                            <td data-halign="right"><?php echo $vt/100 ?></td>
                            <td data-halign="right"><?php echo $resumen['iva'][$t]/100 ?></td>
                            <td data-halign="right"><?php echo $resumen['total'][$t]/100 ?></td>
                        </tr>
                        
                        <?php
                        }
                        ?>
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo $totalBase ?></th>
                            <th data-halign="right"><?php echo $totalIVA ?></th>
                            <th data-halign="right"><?php echo $totalTotal ?></th>
                        </tr>
                        </tbody>
                    </table>

<?php echo form_open('listados/seleccionBoka',array('role'=>'form')) ?>
   <input style="display: inline;" type="submit" class="btn btn-primary btn-mini" value="Otra Selección" >
    
</form>





<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTienda').addClass('btn-primary');  
})
</script>





