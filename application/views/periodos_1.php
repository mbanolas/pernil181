<div class="row">
    <div class="col-xs-12 col-md-8">
         <?php echo form_open($segmentos ,array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?>
        <?php $this->session->set_userdata('periodo', ''); ?>
        <!-- botones selectores periodos --> 
        <ul style="list-style:none;" >
    <li>
        <?php echo form_radio('periodo','hoy',$this->session->periodo=='hoy'?true:false,'id="hoy"').' Hoy'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','ayer',$this->session->periodo=='ayer'?true:false,'id="ayer"').' Ayer'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Semana actual',$this->session->periodo=='Semana actual'?true:false,'id="semanaActual"').' Semana actual'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Semana_anterior',$this->session->periodo=='Semana_anterior'?true:false,'id="semanaAnterior"').' Semana anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Mes actual',$this->session->periodo=='Mes actual'?true:false,'id="mesActual"').' Mes actual'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Mes anterior',$this->session->periodo=='Mes anterior'?true:false,'id="mesAnterior"').' Mes anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Año actual',$this->session->periodo=='Año actual'?true:false,'id="añoActual"').' Año actual'; ?>
    </li>
    <li>
        <?php echo form_radio('periodo','Año anterior',$this->session->periodo=='Año anterior'?true:false,'id="añoAnterior"').' Año anterior'; ?>
    </li>
    
</ul>
  
        
            <div class="col-sm-2">
            <?php echo form_label('Un día: ', 'dia', array('class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
            </div>
        
        <div class="row">
            <div class="form-group">
                <div class="row">
                <div class="col-sm-2">
                <?php echo form_label('Desde fecha: ', 'inicio', array('class' => 'control-label ')); ?>
                </div>
                <div class="col-sm-3">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>
                </div>
                </div>



               <div class="col-sm-2 ">
                <?php echo form_label('Hasta fecha: ', 'final', array('class' => 'control-label ')); ?>
               </div>
                <div class="col-sm-3 ">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>
                </div>

            </div>
        </div>
        
        <table>
            <tr>
                <th>
                 <?php echo form_label('Núm Tickets: ', 'final', array('class' => 'control-label ')); ?>

                </th>
                <td>
                        <?php echo form_label('', '', array('style' => 'text-align:right', 'id' => 'numTickets', 'class' => 'control-label')) ?>
                </td>
                <td>
                </td>
            </tr>
            <!--
            <tr>
                <th>
                        <?php echo form_label('Balanza 1: ', 'final', array('class' => 'control-label ')); ?>

                </th>
                <td>
                        <?php echo form_label('', '', array('style' => 'text-align:right', 'id' => 'balanza1', 'class' => 'control-label')) ?>
                </td>
                                <td>
                        <?php echo form_label('', '', array('style' => 'text-align:right', 'id' => 'numeracion1', 'class' => 'control-label')) ?>
                </td>

            </tr>
            <tr>
                <th>
                        <?php echo form_label('Balanza 2: ', 'final', array('class' => 'control-label ')); ?>

                </th>
                <td>
                        <?php echo form_label('', '', array('style' => 'text-align:right', 'id' => 'balanza2', 'class' => 'control-label')) ?>
                </td>
                                                <td>
                        <?php echo form_label('', '', array('style' => 'text-align:right', 'id' => 'numeracion2', 'class' => 'control-label')) ?>
                </td>

            </tr>
            <tr>
                <th>
                        <?php echo form_label('Balanza 3: ', 'final', array('class' => 'control-label ')); ?>

                </th>
                <td>
                        <?php echo form_label(' ', '', array('style' => 'text-align:right', 'id' => 'balanza3', 'class' => 'control-label')) ?>
                </td>
                                                <td>
                        <?php echo form_label(' ', '', array('style' => 'text-align:right', 'id' => 'numeracion3', 'class' => 'control-label')) ?>
                </td>

            </tr>
            -->
        </table>
        
          
        
        <div class="row">
        <div class="form-group">
            <div class="col-sm-2">
                <?php echo form_label('Seleccionar un Ticket: ', '', array('style'=>'text-align:left; padding-top:0',  'class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-4">
                
                <?php  if (sizeof($this->session->ticketsPeriodo) != 0) {
                    echo form_dropdown('tickets',$this->session->ticketsPeriodo,$this->session->tickets,array('data-toggle'=>"dropdown",  'class'=>'form-control btn btn-default dropdown-toggle','id'=>'tickets'));
                }  
                else {
                   echo form_dropdown('tickets',array('-1'=>'Ningún ticket en estas fechas'),'-1',array('data-toggle'=>"dropdown",  'class'=>'form-control btn btn-default dropdown-toggle','id'=>'tickets'));

                }?>
              
            </div>
        </div>
        </div>
        
            <div class="col-sm-2">
                <?php echo form_label('', '', array('style'=>'text-align:left; padding-top:0; color:red', 'id'=>'esperar',  'class' => 'control-label ')); ?>
            </div>
            <div class="col-sm-3">
                <button id="<?php echo $idBoton ?>" style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
                    <span class="" aria-hidden="true"></span> <?php echo $nombreBoton ?>
                </button>
            </div>
        
        <!-- Para colocar los inputs hide de los tickets -->
        <div class="pasarTickets">  
         </div>
        
        <?php echo \form_close(); ?>
        
    </div>
</div>
