
<?php //var_dump($results2) ?>

<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
        $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; ?>
<h3>PRODUCTOS VENDIDOS EN EL PERIODO</h3>


<table class="table" id="tablaProductos">
                        <thead>
                        <tr >
                            <th data-halign="right">Código</th>
                            <th class="izquierda" data-halign="left">Nombre</th>
                            <th data-halign="right">Partidas</th>
                            <th data-halign="right">Peso (Kg)</th>
                            <th data-halign="right">Importe (€)</th>
                            <th data-halign="right">Base (€)</th>
                            <th data-halign="right">IVA (€)</th>
                            <th data-halign="right">Tipo IVA (%)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        foreach ($productos as $k => $v) {
                            $importe=formato2decimales(($v['importe'])/100);
                            $iva=formato2decimales($v['iva']/100);
                           
                            $base=formato2decimales(($v['base'])/100);
                            $ivaPorcentaje=($v['ivaPorcentaje'])/100;
                            if($base!=0){
                                 $tipo=formato2decimales($ivaPorcentaje);
                             }else $tipo='---';
                            $peso=formato3decimales($v['peso']/1000);  $peso=$peso==0?" ":$peso; 
                            
                            ?>
                        <tr>
                            <td data-halign="right"><?php echo $v['codigo'] ?></td>
                            <td class="izquierda" data-halign="left"><?php echo $v['nombre'] ?></td>
                            <td data-halign="right"><?php echo $v['unidades'] ?></td>
                            
                            <td data-halign="right"><?php echo $peso ?></td>
                            <td data-halign="right"><?php echo formato2decimales(($v['importe'])/100); ?></td>
                            <td data-halign="right"><?php echo $base ?></td>
                            <td data-halign="right"><?php echo $iva ?></td>
                            <td data-halign="right"><?php echo $tipo ?></td>
                        </tr>
                        
                        <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="sumas" data-halign="right"><?php echo count($productos) ?></th>
                            <th class="izquierda" data-halign="left"></th>
                            <th class="sumas" data-halign="right"><?php echo ($productosTotales->row()->unidades) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato3decimales($productosTotales->row()->peso/1000) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato2decimales($productosTotales->row()->importe/100) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato2decimales($productosTotales->row()->base/100) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato2decimales($productosTotales->row()->iva/100) ?></th>
                            <?php  //if($totalBase!=0) $promedioTipo=formato2decimales($totalIVA/$totalBase*100) ?>
                            <th class="sumas" data-halign="right"><?php  echo formato2decimales($productosTotales->row()->ivaPorcentaje/100) ?> </th>
                        </tr>
                        </tfoot>
                    </table>
<br/>
<?php echo form_open('listados/seleccionVentasProductos',array('role'=>'form')) ?>
   <input style="display: inline;" type="submit" class="btn btn-primary btn-mini" value="Otra Selección" >
    



<br />
<br />
<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuProductosTienda').addClass('btn-primary');  
})
</script>

