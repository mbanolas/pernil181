<style type="text/css">
    .tconversion{
        padding-right: 10px;
    
    }
    .convertido{
        font-weight: bold;
    }
</style>
<div class="col-md-12"  id='__cuadroResultados' style="background-color: #f2f2f2; " >
    <div class="row">
                <div class="col-md-12" style="padding-top:10px" >
                    <?php echo form_label('Tickets modificables: pago metálico, sin cliente, sin descuentos', 'ticketModificables', array('class' => 'control-label ')); ?> 
                </div>
    </div>
</div>


<div class="col-md-12"  id='cuadroResultados' style="background-color: #f2f2f2; " >
            <div class="row">
                <div class="col-md-12" style="padding-top:10px" >
                    <?php echo form_label('Tickets modificables: pago metálico, sin cliente, sin descuentos', 'ticketModificables', array('class' => 'control-label ')); ?> 
                </div>
            </div>
    
    <div class="row" >
        <div class="col-md-12"  id="tablaResumen" >
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-12"  id="tabla" >
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-12"  id="tablaLineas" >
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-12" >
           <?php echo form_label('', 'target', array('class' => 'control-label ', 'id'=>'target')); ?> 
            <?php  echo form_input('target', '','style="margin-left: 20px;" id="target2"') ?> 
            <button class="btn btn-default" id="prepararConversion" style="margin-left: 20px; margin-right: 20px">Preparar Conversión</button>
            <?php echo form_label('', 'target', array('class' => 'control-label ', 'id'=>'obtenido')); ?> 
            <button class="btn btn-default"  disabled id="registrarConversion" style="margin-left: 20px; margin-right: 20px">Registrar Resultados</button>
        </div>
    </div>
    
    
    <hr>
    
    <!-- Tabla conversiones -->
    <div class="row" >
        <div class="col-md-10  " >
    <?php foreach($cambios['conversiones'] as $k=>$v){
            if ($cambios['activa'][$k]==1) {$checked='checked';
            $color='blue';
            } else {$checked='';
            $color='black';
            }
            echo "<input type='checkbox'  disabled name='check_conversiones[]' $checked value=''><span style='color:$color'>".$v." - ".$cambios['nombreInicio'][$k]." > ".$cambios['nombreFinal'][$k]." (".$cambios['pesos'][$k]." g) "."</span><br />";
    } ?>
        </div>
        </div>
    
    <hr>
   
    <div class="row">
        <div class="col-md-5  " >
<!--           <?php echo anchor('conversion/mostrarTicketModificar', 'Mostrar Tickets para modificarlos', array('class' => "btn btn-default")); ?>
    -->   
       <button class="btn btn-default" id="mostrarTickets" >Botón Convertir</button>
        </div> 
        <div class="col-md-6  " >

        </div> 
    </div>
    
</div>

