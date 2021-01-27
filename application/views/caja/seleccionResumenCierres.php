<div class="col-md-5"  id='cuadroResultados' style="background-color: #f2f2f2; display:none" >
            <div class="row  " style='padding-top:12px'>
                <div class="col-md-3  " >
                    <?php echo form_label('Núm. cierres: ', 'numDias', array('class' => 'control-label ')); ?>
                </div> 
                <div class="col-md-3 text-right " >
                    <?php echo form_label('', 'numCierres', array('class' => 'control-label ','id'=>'numDias')); ?> 
                </div> 
            </div>
            <div class="row  " style='padding-top:12px'>
                <div class="col-md-12  " >
                    <?php echo form_label('Núm. cierres: ', 'numDias', array('id'=>'error'  ,'class' => 'control-label ')); ?>
                </div> 
                
            </div>
                
            <div class="row  " style='padding-top:12px'>
                <div class="col-md-12  " >
                    <?php echo form_label('', 'numDias', array('id'=>'error'  ,'class' => 'control-label ')); ?>
                </div> 
                
            </div>

            
            <div class="row">
                <div class="col-md-5  " >
       <!--           <?php echo anchor('', 'Mostrar Datos Cierre', array('class' => "btn btn-default")); ?>
            -->   
               <button class="btn btn-default" id="mostrarInformeCierre" >Mostrar informe Cierre</button>
                </div> 
                <div class="col-md-6  " >
                    
                </div> 
            </div>
        </div>

