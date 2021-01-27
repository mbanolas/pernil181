<style type="text/css">
    /*
    .row{
       
    }
    .form-horizontal .control-label{
        padding-top: 0;
    } 
    */
</style>


<?php
echo '<h3>Caja Inicializada correctamente</h3><hr>';
extract($_POST);

?>
<div class='container'>
    <div class='row'>
        <div class='col-xs-3 izquierda'><h4>Fecha</h4></div>
        <div class='col-xs-2 text-right'>
            <h4><?php echo fechaEuropeaSinHora($fecha) ?></h4>
        </div>
    </div>
    <div class='row'>
        <div class='col-xs-3 izquierda'><h4>Cambio Noche</h4></div>
        <div class='col-xs-2 text-right'>
            <h4><?php echo "$cambioNoche €" ?></h4>
        </div>
        </div>
    <div class='row'>
        <div class='col-xs-3 izquierda'><h4>Saldo pendientes cobro</h4></div>
        <div class='col-xs-2 text-right'>
            <h4><?php echo "$saldoBanco €" ?></h4>
        </div>
        </div>
    <div class='row'>
        <div class='col-xs-3 izquierda'><h4>Desviación caja acumulada</h4></div>
        <div class='col-xs-2 text-right'>
            <h4><?php echo "$diferenciaCajaAcumulada €" ?></h4>
        </div>
        </div>
    <div class='row'>
        <div class='col-xs-3 izquierda'><h4>Nota</h4></div>
        <div class='col-xs-4 izquierda'>
            <h4><?php echo $notas ?></h4>
        </div>
    </div>
</div>

    