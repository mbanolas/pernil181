<style>
    table{
        width: 220px;
    }

th:first-child,td:first-child {
    text-align: left;
   
}
</style>

<h3>Resultado</h3>

    <h4>Archivo costes transporte subido: <?php echo $archivoSubido ?></h4>
<h4>Transportista: <?php echo $transportista ?></h4>
<table class=' table-hover table-condensed'>
    <thead>
        <tr><th>Pedido</th><th>Base Tarifa</th><th>Base Suplementos</th><th>Base Factura</th></tr>
        <tbody>
        <?php foreach($costesTransportes as $k=>$v) { ?>
            <tr><td><?php echo $v['pedido'] ?></td><td><?php echo number_format($v['base_tarifa'],2) ?></td><td><?php echo number_format($v['base_suplementos'],2) ?></td><td><?php echo number_format($v['base_factura'],2) ?></td></tr>
        <?php } ?> 
        </tbody>   
    </thead>
</table>



