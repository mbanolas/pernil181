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

<h3>VENTAS DIRECTAS</h3>
<?php
$inicio=date('d/m/Y',  strtotime($inicio));
$final=date('d/m/Y',  strtotime($final));
echo "<h4>Resumen Ventas directas del $inicio al $final</h4>";
//var_dump($resultados);
?>

<?php echo   'Desde Venta Directa núm: '.$desde.'<br />';
        echo 'Hasta Venta Directa núm: '.$hasta.'<br />';
         ?>
<hr>

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
                         <?php foreach($resultados['resultados'] as $k=>$v) {?>
                        <tr>
                           
                            <td data-halign="right"><?php echo formato2decimales($v['tipo_iva']/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v['base']/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v['iva']/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v['total']/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <tr>
                           
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['base']/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['iva']/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['total']/100) ?></th>
                            
                        </tr>
                        </tbody>
                       
                    </table>
<br />


