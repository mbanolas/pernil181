<style>


.ftitle{
    font-size: 20px;
   
}

.grocery-crud-table tbody tr td{
    text-align: left;
}

</style>

<?php
//para incluir título en cabecera tabla
$titulo = isset($titulo) ? $titulo : 'Sin Título';
$col_bootstrap = isset($col_bootstrap) ? $col_bootstrap : 10;
?>
<input type="hidden" id="titulo" value="<?php echo $titulo ?>">
<input type="hidden" id="categoria" value="<?php echo $this->session->categoria ?>">
<div class="row">
    <div class="col-xs-<?php echo $col_bootstrap ?>">


<?php echo $output; ?>
    </div>
</div>



<script>
$(document).ready(function () {

$('div.container').addClass('container-fluid')
$('div.container-fluid').removeClass('container')

})
</script>