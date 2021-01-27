<style>
.gc-container span.page-number-input-container .page-number-input{
    width:60px !important;
}
</style>

<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $tituloRango=isset($tituloRango)?$tituloRango:'Pedidos Prestashop' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
    <input type="hidden" id="tituloRango" value="<?php echo $tituloRango ?>">
         
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    

    
 


<script>
        
$(document).ready(function(){

    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container')

    //poner pie totales
    $('<tfoot><tr style="border:5px solid blue;"><th style="text-align: left; " colspan="5">Totales selección</th><th id="cantidad">Cantidad</th><th ></th><th id="importe">Importe</th><th id="importe_con_descuento">I con des</th><th id="total_iva">Iva</th><th colspan="6"></tr></tfoot>').insertAfter('tbody')


})
</script>