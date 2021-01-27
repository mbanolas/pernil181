

<h3 id="errorProducto2"><?php echo $resultado ?></h3>

<?php if(!$pedidosSubidos) { ?>
<!--
<?php if($productoNoExistente){  ?>
<h3 id="errorProducto">Error</h3>
<?php } ?>
-->

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


?>
    
    </ul>

<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="left" style="text-align: left;">Pedido-Valid</th>
                            <th data-halign="right" style="text-align: left;">Códigos</th>
                            <th data-halign="left" style="text-align: left;">Nombres</th>
                            <th data-halign="right">Cantidades</th>
                            <th data-halign="right">Importes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($codigos as $k=>$v){ ?>
                        <tr style="color:<?php echo $codigosPack[$k]?'blue':'black'?>">
                            <td data-halign="left" style="text-align: left;"><?php echo $pedidos[$k].' - '.$validos[$k] ?></td>
                            <td data-halign="left" style="text-align: left;"><?php echo $v ?></td>
                            <td data-halign="left" style="text-align: left;"><?php echo $nombres[$k] ?></td>
                            <?php if(!$codigosPack[$k]) { ?>
                            <td data-halign="right"><?php echo $cantidades[$k] ?></td>
                            <td data-halign="right"><?php echo number_format($importes[$k],2) ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        
                        </tbody>
                    </table>

<?php echo form_open('inicio',array('role'=>'form')) ?>
   <input type="submit" class="btn btn-primary btn-lg" value="Ver informe sobre stocks" >
    
</form>
<?php } ?>

<script>
$(document).ready(function () {
    
   if($('#errorProducto').html()=='Error'){
    $('.modal-title').html('Información importante')
    $('.modal-body>p').html($('#errorProducto2').html())
    $("#myModal").css('color','red')
    $("#myModal").modal()  
    }
})
</script>



