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

<h3>VENTAS ON LINE</h3>
<?php
$inicio=date('d/m/Y',  strtotime($inicio));
$final=date('d/m/Y',  strtotime($final));
echo "<h4>Resumen Ventas online del $inicio al $final</h4>";
//var_dump($resultados);
?>

<?php echo   'Desde: '.$inicio.'<br />';
        echo 'Hasta: '.$final.'<br />';
         ?>
<hr>

<?php echo '<h3>Importes productos</h3>' ?>
<table class="table">
                        <thead>
                        <tr>
                           
                             <th >Tipo IVA</th>
                            <th >Base</th>
                            <th >IVA</th>
                          <!--  <th data-halign="right">Total </th> -->
                            <th >Total</th>
                        </tr>
                        </thead>
                        
                        <tbody>
                         <?php foreach($resultados['resultados'] as $k=>$v) {
                             if(!isset($base[$v['tipo_iva']])) $base[$v['tipo_iva']]=$v['base']; else $base[$v['tipo_iva']]+=$v['base'];
                             if(!isset($iva[$v['tipo_iva']])) $iva[$v['tipo_iva']]=$v['iva']; else $iva[$v['tipo_iva']]+=$v['iva'];
                             if(!isset($total[$v['tipo_iva']])) $total[$v['tipo_iva']]=$v['total']; else $total[$v['tipo_iva']]+=$v['total'];

                             ?>
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
                      <!--      <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['total']/100) ?></th> -->
                      <!--      <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['descuento']/100) ?></th> -->
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['total']/100) ?></th>
                            
                        </tr>
                        </tbody>
                       
                    </table>
<br />
<?php echo '<h3>Importes transportes</h3>' ;?>
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
                         <?php foreach($transportes['resultados'] as $k=>$v) {
                             
                             if(!isset($base[$v['tipo_iva_transporte']/10])) $base[$v['tipo_iva_transporte']/10]=$v['base_transporte']/10; else $base[$v['tipo_iva_transporte']/10]+=$v['base_transporte']/10;
                             if(!isset($iva[$v['tipo_iva_transporte']/10])) $iva[$v['tipo_iva_transporte']/10]=$v['iva_transporte']/10; else $iva[$v['tipo_iva_transporte']/10]+=$v['iva_transporte']/10;
                             if(!isset($total[$v['tipo_iva_transporte']/10])) $total[$v['tipo_iva_transporte']/10]=$v['transporte']/10; else $total[$v['tipo_iva_transporte']/10]+=$v['transporte']/10;

                             
                             ?>
                        <tr>
                           
                            
                            
                            <td data-halign="right"><?php echo formato2decimales($v['tipo_iva_transporte']/1000) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v['base_transporte']/1000) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v['iva_transporte']/1000) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v['transporte']/1000) ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <tr>
                           
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($transportes['resultadosTotal']['base_transporte']/1000) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($transportes['resultadosTotal']['iva_transporte']/1000) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($transportes['resultadosTotal']['transporte']/1000) ?></th>
                            
                        </tr>
                        </tbody>
                       
                    </table>
<br />
<?php echo '<h3>Importes TOTALES (productos + transportes)</h3>' ;?>
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
                         <?php foreach($base as $k=>$v) {?>
                        <tr>
                           
                            <td data-halign="right"><?php echo formato2decimales($k/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($iva[$k]/100) ?></td> 
                            <td data-halign="right"><?php echo formato2decimales($total[$k]/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                        
                        <tr>
                           
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['base']/100+$transportes['resultadosTotal']['base_transporte']/1000) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['iva']/100+$transportes['resultadosTotal']['iva_transporte']/1000) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultados['resultadosTotal']['total']/100+$transportes['resultadosTotal']['transporte']/1000) ?></th>
                            
                        </tr>
                        </tbody>
                       
                    </table>
                    