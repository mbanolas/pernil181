<style type="text/css">
    p,ul {
        padding: 10px;
        font-size: 15px;
        font-weight: normal;
        text-align: left;
        background: #f2f2f2;
    }
   .form-group,input, .box{
       background: #f2f2f2;
       
    }
    
    div{
        border: 0px solid #f2f2f2; 
    }
    li{
        /* padding-bottom: 5px; */
    }
    p.d{
        padding: 50px;
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        background: #f2f2f2;
        height: 250px;
    }
    
    .etiqueta{
        
    }
    #agrupar{
        text-align: left;
    }
    .boxFondo{
        background-color: #F2F2F2;
        
    }
    
</style>
 <?php $hidden=isset($agrupar)?'':'hidden' ?>
 <?php echo form_open($segmentos ,array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?>

    <div class="row">
    <div class="col-md-4" style="padding-top: 0px; padding-bottom: 0px"><p>Periodos</p>
    <ul style="list-style:none; padding-bottom: 55px" >
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
        
    </div>
     <div class="col-md-4 " >
         <p >Fechas</p>
        
         <div class="col-md-5 box " style="padding-left: 10px;padding-top: 20px; padding-bottom: 28px">
            <?php echo form_label('  Un día: ', 'dia', array('class' => 'control-label etiqueta')); ?>
         </div>
         <div class="col-md-7 box " style="padding-top: 20px; padding-bottom: 15px">
            <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
         </div>
        
         <div class="col-md-5 box " style="padding-left: 10px;padding-top: 0px; padding-bottom: 13px">
                <?php echo form_label('Desde fecha: ', 'inicio', array('class' => 'control-label ')); ?>
         </div>
         <div class="col-md-7 box " style="padding-top: 0px; padding-bottom: 0px">
                <?php $hoy=date('Y-m-d') ?>
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>
         </div>
         <div class="col-md-5 box " style="padding-left: 10px;padding-top: 0px; padding-bottom: 13px">
                <?php echo form_label('Hasta fecha: ', 'final', array('class' => 'control-label ')); ?>
         </div>
         <div class="col-md-7 box " style="padding-top: 0px; padding-bottom: 0px">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>
         </div>
         <!--
         <div <?php //echo $hidden ?>>
            <div  class="col-md-5 box " style="padding-left: 10px;padding-top: 10px; padding-bottom: 0px">
                   <?php //echo form_label('Agrupar por códigos báscula: ', 'agrupar', array('id'=>'agrupar','class' => 'control-label hide')); ?>
            </div>
            <div  class="col-md-7 box " style="padding-top: 34px; padding-bottom: 3px">
                       <?php $data = array(
                                   'name'=> 'agrupar',
                                   'id'=> 'checkBoxAgrupar',
                                   'value'=> 'accept',
                                   'checked'=> FALSE
                                    );
                                   //echo form_checkbox($data); ?>
            </div>
         </div>
        -->
         <div class="col-md-12 box " style="padding-top: 0px; padding-bottom: 12px;padding-left: 30px;"></div>
         <div class="col-md-12 box " style="padding-top: 0px; padding-bottom: 0px;"></div>
         <div class="obteniendo col-md-5 box" style="margin-bottom: 10px;padding-left:10px;         padding-top:0px; padding-bottom: 16px;">
            <?php echo form_label('Obteniendo Datos', 'final', array('style' => 'text-align:right',  'class' => 'control-label','id'=>'esperar2')) ?>
         </div>
         <div class="obteniendo col-md-7 box" style=" padding-top:0px; padding-bottom: 10px;">
             <img class=" img-responsive ajax-loader2"   src="<?php echo base_url('images/ajax-loader.gif') ?>">
         </div>
     </div>
   
     
    <div class="col-md-4">
        <p >Resultados</p>
        <div class="boxFondo col-md-12">
        <div class="col-md-5 box " style="padding-left: 10px;padding-top: 20px; padding-bottom: 10px">
            <?php echo form_label('Núm Tickets: ', 'final', array('class' => 'control-label ',)); ?> 
        </div>
        <div class="col-md-7 box box1" style="padding-top: 20px; padding-bottom: 10px">
            <?php echo form_label('0', 'final', array('style' => 'text-align:right', 'id' => 'numTickets', 'class' => 'control-label')) ?>
        </div>
       
        
        <div class="col-md-5 box " style="padding-left: 10px;padding-top: 10px; padding-bottom: 0px">
            <?php echo form_label('Balanza 1: ', 'final', array('class' => 'control-label ',)); ?> 
        </div>
        <div class="col-md-7 box box1" style="padding-top: 10px; padding-bottom: 0px">
            <?php echo form_label('0', 'final', array('style' => 'text-align:right', 'id' => 'balanza1', 'class' => 'control-label')) ?>
            <span id="numeracion1"></span>
            <input type="hidden" id="periodoBalanza1" name="periodoBalanza1" value="">
        </div>
        <div class="col-md-5 box " style="padding-left: 10px;padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('Balanza 2: ', 'final', array('class' => 'control-label ',)); ?> 
        </div>
        <div class="col-md-7 box box1" style="padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('0', 'final', array('style' => 'text-align:right', 'id' => 'balanza2', 'class' => 'control-label')) ?>
            <span id="numeracion2"></span>
            <input type="hidden" id="periodoBalanza2" name="periodoBalanza2" value="">
        </div>
        <div class="col-md-5 box " style="padding-left: 10px;padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('Balanza 3: ', 'final', array('class' => 'control-label ',)); ?> 
        </div>
        <div class="col-md-7 box box1" style="padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('0', 'final', array('style' => 'text-align:right', 'id' => 'balanza3', 'class' => 'control-label')) ?>
            <span id="numeracion3"></span>
            <input type="hidden" id="periodoBalanza3" name="periodoBalanza3" value="">
        </div>
        
        <div class="col-md-5 box " style="padding-left: 10px;padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('Días manuales: ', 'final', array('class' => 'control-label ',)); ?> 
        </div>
        <div class="col-md-7 box box1" style="padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('0', 'final', array('style' => 'text-align:right', 'id' => 'manuales', 'class' => 'control-label')) ?>
            <span id="numeracion3"></span>
            <input type="hidden" id="periodoManuales" name="periodoManuales" value="">
        </div>
        
       <div class="col-md-12 box" style="padding-top: 0px; padding-bottom: 0px">
            <?php echo form_label('', 'final', array('style' => 'text-align:right',  'class' => 'control-label')) ?>
            <span ></span>
            <input type="hidden" id="periodoBalanzaTodas" name="periodoBalanzaTodas" value="">
            
        </div>
        <div class="col-md-12 box" style="padding-top: 0px; ">
            <?php echo form_label('', 'final', array('style' => 'text-align:right',  'class' => 'control-label','id'=>'esperar')) ?>
            <img class="img-responsive ajax-loader"   src="<?php echo base_url('images/ajax-loader.gif') ?>">
        </div>
        
        <div class="col-md-12 box" style="padding-left: 10px;padding-top: 0px; padding-bottom:  10px;">
        <button id="ticketsFaltan" style="padding-top:10px;display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini" >
            <span class="" aria-hidden="true"></span> Ver Tickets que faltan
        </button>        </div>
        </div>
    </div>
</div>
    <div class="col-md-4">
    </div>


    <div class="col-md-4" style="text-align: center;padding-top:0px;">
        <button id="<?php echo $idBoton ?>" style="display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini" >
            <span class="" aria-hidden="true"></span> <?php echo $nombreBoton ?>
        </button>
    </div>
    <div class="col-md-4">
    </div>
    


 <?php echo \form_close(); ?>
<style>
    
    
</style>
    