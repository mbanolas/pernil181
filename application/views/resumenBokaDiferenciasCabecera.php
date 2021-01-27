<?php
$inicio=date('d/m/Y',  strtotime($inicio));
$final=date('d/m/Y',  strtotime($final));
echo "<h4>Resumen Diferencias Ventas Tienda del $inicio al $final</h4>";

//var_dump($results);
?>

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuDiferenciasTienda').addClass('btn-primary');  
})
</script>
