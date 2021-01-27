<h3>Actualización stocks mínimos</h3>
<h4>Los stocks mínimos de los productos se actualizarán con las ventas promedio de tres meses tomados según el siguinte criterio.</h4>
<h5>El 2º  y 3º trimestre ajustarla a la media de los tres meses anteriores.</h5>
<h5>            El 4º trimestre tomar los datos del último trimestre del año anterior.</h5>
<h5>           El 1º trimestre utilizar la media del tercer trimestre del año anterior. </h5>
<?php if($ultimaFechaStocksMinimos) { ?>
<h1>Los datos de stocks mínimos YA fueron calculados y establecidos el <?php setlocale(LC_TIME, 'es_ES.UTF-8'); echo (strftime('%A, %e  de %B  de %Y', strtotime($ultimaFechaStocksMinimos))) ?></h1>
<?php } ?>
<?php echo form_open('stocks/stocksMinimosCalculo', array('class'=>"form-horizontal", 'role'=>"form")); ?>
<h4 class="hide">Cargando datos y calculando, esperar.</h4>
 <img id="cargando" class="hide" src="<?php echo base_url().'images/ajax-loader-w.gif' ?>" alt="Sea paciente" height="42" width="42"> 
 <span id="t1"><input class="btn btn-default" type="submit" id="calcular" value="Obtener datos stocks mínimos"></span>

<?php echo form_close(); ?>
 
 <script>
    $(document).ready(function () {
        $('#calcular').click(function(){
            $('#cargando').removeClass('hide')
            $('h4').removeClass('hide')
            $('#calcular').addClass('hide')
        })
        
        
        
    
    
    })
</script>

