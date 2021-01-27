<style type="text/css">
    p,ul {
        padding: 10px;
        font-size: 15px;
        font-weight: normal;
        text-align: left;
        background: #f2f2f2;
    }
   .form-group,input, .box, .box1, .boxT, .boxF{
       /*background: #f2f2f2;*/
       
    }
    
    li{
     /*   padding-bottom: 5px; */
    }
    p.d{
        padding: 50px;
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        background: #f2f2f2;
        height: 250px;
    }
    
    .box label,.box1 label{
        
        padding-bottom: 13px;
    }
    
    .boxS label{
        
        padding-bottom: 0px;
    }
    
     .box1  {
        padding-top: 10px;
        padding-bottom: 30px;
    }
    
     .boxS  {
        padding-top: 0px;
        padding-bottom: 0px;
        padding-left: 10px;
    }
    
    ul {
        padding-bottom: 30px;
    }
     .boxT  {
        margin-left:60px; 
        padding-top: 18px;
        padding-left: 0px;
        padding-bottom: 18px;
    }
    .nombre{
        padding-left: 10px;
        text-align: left;
        
    }
    .nombreS{
        
        margin-bottom: 50px;
        padding-left: 10px;
        padding-bottom:  0px;
        text-align: left;
        color:red;
        
    }
    
    
</style>


<hr>
<div class="container">

    <div class="row">
        <div class="col-md-2">
            <div class="row">
                 
            </div>
        </div>
        <div class="col-md-8 " >
            <div class="row">
                 
            </div>
        </div>
    </div>
    <div class="row" style="background-color: #f2f2f2">

        <div class="col-md-2">
            <div class="row">
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
            </div>
        </div>
        
        <?php echo form_open('',array( 'class' => 'conversiones')); ?>
        <div class="col-md-4" >
            <div class="row ">
                <div class="col-md-4 box1 " >
                    <?php echo form_label('Un día: ', 'dia', array('class' => 'control-label ')); ?>
                </div> 
                <div class="col-md-6 box1 " >
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-4 box " >
                    <?php echo form_label('Desde fecha: ', 'inicio', array('class' => 'control-label ')); ?>

                </div> 
                <div class="col-md-6 box " >
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-4 box " >
                    <?php echo form_label('Hasta fecha: ', 'final', array('class' => 'control-label ')); ?>
                </div> 
                <div class="col-md-6 box " >
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>
                </div> 
            </div>

        </div>
        
        <div class="col-md-12" >
            
            
             <div class="row">
                <?php if(!isset($buscar)) {
                    $buscar="buscarTickets";
                    $buscarTexto="Buscar tickets";
                }
                ?>
                <div class="col-md-4 boxT " >
                    <button class="btn btn-default" id="<?php echo $buscar ?>" ><?php echo $buscarTexto ?><img class="img-responsive ajax-loader2"  style="visibility:hidden"  src="<?php echo base_url('images/ajax-loader.gif') ?>"></button>
                </div> 
                
                <div class="col-md-2 box " style="padding-top: 8px; padding-bottom: 24px;" >
            <img class="img-responsive ajax-loader2 hide"  style="visibility:hidden"  src="<?php echo base_url('images/ajax-loader.gif') ?>">
                </div>
                
                <div class="col-md-6 boxF " >
                    
                </div> 
            </div>
            
        </div>   
        <?php echo form_close(); ?>
        
      <?php  if(isset($seleccionPeriodosDerecha)) echo $seleccionPeriodosDerecha ?>
            
            
</div>
</div>     
<hr>
<div class="container">
<?php  if(isset($seleccionPeriodosAbajo)) echo $seleccionPeriodosAbajo ?>
</div>           
            
            
   
         
   


