
<?php if(($exito)) { ?>
<h3><?php echo $resultado ?></h3>
<?php }else{ ?>
  <h2 style="color:red;"><?php echo $resultado ?></h2>  
<?php } ?>



<?php if($productoNoExistente){ ?>
<h3 id="errorProducto" style="color:red;">Error</h3><h3 id="errorProducto2" style="color:red;">El producto <?php echo intVal($productoNoExistente) ?> No existe en la base de datos de productos</h3>
<h3 style="color:red;">Introducir el producto en la base de datos y volver a subir el archivo Boka.</h3>

<?php }else{ ?>

<ul>
<?php 
//echo $linea;
$itemCast = array('file_name' => 'Nombre Archivo',
    'file_type' => 'Tipo Archivo',
    'file_path' => 'Directorio Archivo',
    'full_path' => 'Nombre completo Archivo',
    'raw_name' => 'Nombre Archivo',
    'orig_name' => 'Nombre Archivo Original',
    'client_name' => 'Nombre Cliente',
    'file_ext' => 'Extensión Archivo',
    'file_size' => 'Tamaño Archivo',
);
foreach ($upload_data as $item => $value) {
if(isset($itemCast[$item])) { echo "<li>$itemCast[$item]: $value</li>";}
}

echo '<br /><br />'.'TOTAL '.$tickets.' Tickets'.'<br /><br />'; 
echo $codigosSubidos;
?>
    
    </ul>

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
                        foreach ($base as $t => $vt) {
                            $totalBase+=$vt/100;
                            $totalIVA+=$iva[$t]/100;
                            $totalTotal+=$total[$t]/100;
                            ?>
                        <tr>
                            <td data-halign="right"><?php echo $t ?></td>
                            <td data-halign="right"><?php echo $vt/100 ?></td>
                            <td data-halign="right"><?php echo $iva[$t]/100 ?></td>
                            <td data-halign="right"><?php echo $total[$t]/100 ?></td>
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
<hr>
<!--
<h3>Discrepancias entre precios Base de Datos de productos y precios Básculas</h3>
<br>
<?php echo $resultadoPreciosTarifas ?>
-->
<br>

<?php echo form_open('inicio',array('role'=>'form')) ?>
   <input type="submit" class="btn btn-primary btn-lg" value="Ver informe sobre stocks" >
    
</form>

<?php } ?>


<script>
$(document).ready(function () {
    
   if($('#errorProducto').html()=='Error'){
     $('#myModal').css('color','red')   
    $('.modal-title').html('Información importante')
    $('.modal-body>p').html($('#errorProducto2').html()+'<br />Introducir el producto en la base de datos y volver a subir el archivo Boka.')
    $("#myModal").css('color','red')
    $("#myModal").modal()  
    }
})
</script>
