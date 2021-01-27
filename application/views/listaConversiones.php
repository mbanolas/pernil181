
<h3 style="margin-top:5px;">Seleccionar Tickets para mostrar y modificar (sólo pago en metálico, sin cliente, sin descuentos, sin pago a cuenta y valor venta no negatico)</h3>

            <div class="row">
            <div class="col-md-4" >
                <?php echo form_label('Seleccionar los códigos conversión ', '', array('class' => 'control-label ')); ?>
            </div>
        </div>
            <br /> 
            <?php $codFinales=array(); 
                foreach($conversiones['codigosIniciales'] as $k=>$v){
                $id=$conversiones['ids'][$k];
                $f=$conversiones['codigosFinales'][$k];
                if(!in_array($f,$codFinales) && !$this->productos_->esCodigoBokaUnico($f)) $codFinales[]=$f;
                $ni=$conversiones['nombresIniciales'][$k];
                $nf=$conversiones['nombresFinales'][$k];
                $activa=$conversiones['activas'][$k];
                $conversion="$v -> $f  $ni > $nf";
                
                //echo $conversion.'<br />';
                echo form_checkbox('conversion', $id, $activa,array('class'=>'conversiones','codigo_inicial'=>$v,'codigo_final'=>$f)); echo $conversion.'<br />';              
            }
            ?>
            <br>
            <?php 
            sort($codFinales);
            if(count($codFinales)){
                echo '<h5>Observaciones: </h5>';
                if(count($codFinales)==1){
                    echo '<h5>El código final '.$v.' NO está activo actualmente o no es código único báscula</h5>';
                }else{
                    echo '<h5>Los siguentes códigos NO están activos actualmente o no es código único báscula: '.implode ( ', ' ,  $codFinales ).'.';
                } 
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
            url: "<?php echo base_url() ?>"+"index.php/conversion/guardarConversiones", 
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
