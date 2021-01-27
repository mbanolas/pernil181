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
        padding-top: 6px;
        padding-left: 0px;
        padding-bottom: 8px;
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
            
            
            
            <div class="row">
                <?php if(!isset($buscar)) {
                    $buscar="buscarTickets";
                    $buscarTexto="Buscar tickets";
                }
                ?>
                <div class="col-md-4 boxT " >
                    <button type="submit" class="btn btn-default" id="<?php echo $buscar ?>NO"  ><?php echo $buscarTexto ?></button>
                </div> 
                
                <div class="col-md-2 box " style="padding-top: 8px; padding-bottom: 24px;" >
            <img class="img-responsive ajax-loader2"  style="visibility:hidden"  src="<?php echo base_url('images/ajax-loader.gif') ?>">
                </div>
                
                <div class="col-md-6 boxF " >
                    
                </div> 
            </div>
            
        </div>
        
        
      <?php  if(isset($seleccionPeriodosDerecha)) echo $seleccionPeriodosDerecha ?>
            
            
</div>
</div>     
<hr>
<div class="container">
<?php  if(isset($seleccionPeriodosAbajo)) echo $seleccionPeriodosAbajo ?>
</div>



  
</div>

            
<script>
$(document).ready(function () {
    
    var inicio
    var final
    
    
    function alerta(titulo="Información",mensaje){
        $('.modal-title').html(titulo)
        $('.modal-body>p').html(mensaje)
        $("#myModal").modal()
    }
    
    function alertaError(titulo="Información importante",mensaje){
        
        $('.modal-title').html(titulo)
        $('.modal-body>p').html(mensaje)
        $("#myModalError").modal()
    }
    
    function meses(fecha){
        var ano=parseInt(fecha.substr(0,4))
        var mes=parseInt(fecha.substr(5,2))
        var dia=fecha.substr(8,2)
        var meses=(ano-1970)*12+mes
    return parseInt(meses)
    }
    
    $('#buscarTicketsNO').click(function(e){
        //alert('hola')
        inicio=$('#inicio').val()
        final=$('#final').val()
        if (inicio>final){
             $('#inicio').val(final)
             $('#final').val(inicio)
        }
        inicio=$('#inicio').val()
        final=$('#final').val()
        
        if(!inicio || !final){
            e.preventDefault()
            alerta('Información',inicio+' '+final)
        }
       
        var difMeses=meses(final) - meses(inicio)
        if(difMeses>4){
           // e.preventDefault()
           // alerta('Información','El periodo debe de ser de máximo 4 meses. Cambiar las fechas de inicio y/o final')
        }
        
        if(!inicio || !final){
            e.preventDefault()
            alerta('Información.','No se ha seleccionado ningún periodo. Faltan indicar<br /> - Desde fecha y/o<br /> - Hasta fecha.')
        }
        var conversiones=[]
        conversiones=$('input.conversiones')
        conversiones.each(function(index,element) {
                console.log($(this).val());
                conversiones.push($(this).val())
        });
        
        
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/conversionN/getResumenTickets", 
            data: {inicio:inicio,final:final,conversiones:conversiones},
            success: function(datos){
               alert('hola 1'+datos)
               if(datos==0){
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información ')
                    $('.modal-body>p').html("Ningún ticket en el periodo o ningún ticket convertible")
                    $("#myModal").modal() 
                    $('img.ajax-loader2').css('visibility','hidden')
                    return false
                }
               var valores=$.parseJSON(datos);
               
               
               
        },
            error: function(){
                $('img.ajax-loader2').css('visibility','hidden')
                $('#esperar').html('Error en el proceso');
            }
        });
        
        
        
      //  $('.img-responsive ajax-loader2').css('visibility','')
        
    })
})  
</script>            
   
         
   


