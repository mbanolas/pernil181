<style>
td,th{
    text-align: left;
}

    </style>

<br>
<h4>Listado de Pedidos Prestashop pendientes de enviar tracking</h4>
<br>

<?php if(!count($pendientes)) { ?>
    Ningún pedido pendiente de Tracking
<?php }
else {
    echo form_open('envioTrackingPrestashop/enviar');
    echo '<table class="table">';
    echo '<thead><tr><th>'.form_checkbox('', '', TRUE, array('class'=>'todos')).'Enviar</th><th>Pedido</th><th>Nombre</th><th>País</th><th>Email</th><th>Idioma</th><th>Reference</th><th>Shop name</th></tr></thead>';
foreach($pendientes as $k=>$v){
    echo '<tr>';
    $pedido=$v['num_pedido'];
    $idioma="ESP";
    switch($v["customer_id_language"]){
        case 1:
        $idioma="ENG"; break;
        case 2:
        $idioma="FRA"; break;
        case 3:
        $idioma="ESP"; break;
        case 4:
        $idioma="ENG"; break;
        case 5:
        $idioma="ITA"; break;
        case 6:
        $idioma="CAT"; break;
        

    }
    echo '<td>'.form_checkbox($pedido, $pedido, TRUE, array("class"=>"pedido")).'</td>';
    echo '<td>'.$pedido.'</td>';
    echo '<td>'.$v["delivery_firstname"].' '.$v["delivery_lastname"].'</td>';
    echo '<td>'.$v["delivery_country"].'</td>';
    echo '<td>'.$v["customer_email"].'</td>';
    echo '<td>'.$v["customer_id_language"].'-'.$idioma.'</td>';
    echo '<td>'.$v["reference"].'</td>';
    echo '<td>'.$v["shop_name"].'</td>';

    //echo $pedido.' '.$v['customer_email'].' '.$v['delivery_firstname'].' '.$v['delivery_lastname'];
    echo '</tr>';
}   echo '</table>';
    echo '<button type="submit" class="btn btn-primary">Preparar datos para enviar emails tracking</button>';
     echo form_close();
}
?>
<script>
$(document).ready(function () {

    $('input.todos').click(function(){
        if( $(this).is(':checked') ) {
            $( "input.pedido" ).prop( "checked", true );
        }else{
            $( "input.pedido" ).prop( "checked", false );
        }

    })
})
</script>