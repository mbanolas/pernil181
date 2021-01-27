<style type="text/css">
    .caja,.col-md-4{
        border:1px solid white;
    }
    label.caja{
        
    }
    .form-horizontal .control-label{
        padding-top: 4px;
        padding-bottom:  10px;
    }
    #error{
        color:red;
        font-weight: bold;
    }
    .moneda{
        text-align: right;
    }
    /*
    .row{
       
    }
    .form-horizontal .control-label{
        padding-top: 0;
    } 
    */
</style>

<?php
echo '<h3>Inicializar Caja</h3><hr>';

?>

<div class="container">
    <div class="col-lg-10">
        <fieldset>
            <?php echo form_open(); ?>

            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Fecha inicio: ', '', array('class' => 'col-lg-3 control-label caja',)); ?>
                    <div class="col-lg-3">
                        <?php echo form_input(array('type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'class' => 'form-control input-sm', 'value' => date('Y-m-d'))) ?>
                        <?php echo form_error('fecha'); ?>

                    </div>
                    <div class="col-lg-6"> </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Cambio Noche: ', '', array('class' => 'col-xs-3 control-label caja_',)); ?>
                    <div class="col-xs-2">
                        <?php echo form_input(array('type' => 'text', 'name' => 'cambioNoche', 'id' => 'cambioNoche', 'class' => 'form-control input-sm moneda', 'value' => set_value('cambioNoche') ==''?0:set_value('cambioNoche'))) ?>
                        <?php echo form_error('cambioNoche'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Saldo banco: ', '', array('class' => 'col-xs-3 control-label caja',)); ?>
                    <div class="col-xs-2">
                        <?php echo form_input(array('type' => 'text', 'name' => 'saldoBanco', 'id' => 'saldoBanco', 'class' => 'form-control input-sm moneda', 'value' => set_value('saldoBanco') ==''?0:set_value('saldoBanco'))) ?>
                        <?php echo form_error('saldoBanco'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Desviación caja acumulada: ', '', array('class' => 'col-xs-3 control-label caja',)); ?>
                    <div class="col-xs-2">
                        <?php echo form_input(array('type' => 'text', 'name' => 'diferenciaCajaAcumulada', 'id' => 'diferenciaCajaAcumulada', 'class' => 'form-control input-sm moneda', 'value' => set_value('diferenciaCajaAcumulada') ==''?0:set_value('diferenciaCajaAcumulada'))) ?>
                        <?php echo form_error('diferenciaCajaAcumulada'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Notas: ', '', array('class' => 'control-label col-xs-3',)); ?>
                    <div class="col-xs-6">
                        <?php echo form_textarea(array('rows' => '2', 'placeholder' => 'Indicar anotaciones, incidencias, ... Máx 300 carácteres', 'name' => 'notas', 'id' => 'notas', 'class' => 'form-control input-sm', 'value' => set_value('notas') ==''?'':set_value('notas'))) ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-3 col-lg-10">
                        <button type="submit" class="btn btn-success submit"  >Inicializar Caja</button>
                    </div>
                </div>
                <?php echo form_close(); ?>

        </fieldset>
    </div>

</div>



         
                   


<!-- <div class="row"><div class="col-md-12"><p>&nbsp;</p></div></div> -->


  
 
        
<br />
<br />

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuCaja').addClass('btn-primary');
  $('#menuInicializarCaja').addClass('btn-primary');  
})
</script>

 <script>
$(document).ready(function(){
    
    //variables globales
    var formmodified = 0;
    
    
    
        $('.moneda').click(function(){
                    $('#error').html('')
                    if($(this).val('NaN')) 
                        {$(this).val('')}
        })
        
    function fecha(fecha){
    return new Date(fecha)    
    }   
    
    $('.submit').click(function(e){
        var fecha=$('#fecha').val();
        fecha=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
        if (confirm('Se eliminarán todos los registros de caja de la fecha '+fecha+' y siguientes. ¿Desea continuar?'))
        formmodified=0
        else {
        e.preventDefault();    
        }
    
    })
    
    $('#notas').change(function(){
        var valor=$(this).val()
       // alert(valor)
        valor=valor.substr(0, 300);
        $(this).val(valor)
        formmodified=1
    })
    
    $('#fecha #cambioNoche, #saldoBanco, #notas, #diferenciaCajaAcumulada').change(function(){
                formmodified=1
    })
    
    
   
    
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (formmodified == 1) 
        {
            return "No ha procesado o grabado los cambios.";
        }
    }
    
  
})
</script>


