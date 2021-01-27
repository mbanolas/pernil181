<?php echo form_open('conversionN/getResumenTickets', array('role' => 'form')); ?>
<h3 style="margin-top:0px;">Seleccionar Tickets para mostrar y modificar (sólo pago en metálico, sin cliente, sin descuentos)</h3>

            <div class="row">
            <div class="col-md-4" >
                <?php echo form_label('Seleccionar los códigos para la conversión ', '', array('class' => 'control-label ')); ?>
            </div>
        </div>
            <br /> 
            <?php foreach($conversiones['codigosIniciales'] as $k=>$v){
                $id=$conversiones['id'][$k];
                $f=$conversiones['codigosFinales'][$k];
                $ni=$conversiones['nombreInicio'][$k];
                $nf=$conversiones['nombreFinal'][$k];
                $activa=$conversiones['activa'][$k];
                $conversion="$v -> $f  $ni -> $nf";
                
                //echo $conversion.'<br />';
                echo form_checkbox('conversion'.$id, $id, $activa,'class="conversiones"'); echo $conversion.'<br />';
            }
            ?>

            <script>
$(document).ready(function () {
 
    $('.conversiones').click(function(event){
         $('#cuadroResultados').css('display','none')
        event.stopImmediatePropagation();
        valor=$(this).is(':checked')
        id=$(this).val()
        activa=$(this).is(':checked')?1:0
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/conversionN/guardarConversiones", 
            data: {id:id,activa:activa},
            success:function(datos){
                //alert(datos);
             
            },
            error:function(){
                alert('Error en la conversión. Informar al Administrador')
            },
        })
    })
})
 </script>
