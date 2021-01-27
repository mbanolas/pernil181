<style>
  #any,#mes{
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555555;
    vertical-align: middle;
    background-color: #ffffff;
    background-image: none;
    border: 1px solid #cccccc;
    border-radius: 4px;
  }
  #selAny,#selMes{
    background-color:lightgray;
    padding-top:5px;
    padding-bottom: 5px;
  }
  #selAny,body > div > div.row.col-xs-12 > div:nth-child(1) > div:nth-child(4){
    padding-left:6px !important;
    padding-right:6px !important;
  }
  #selMes,body > div > div.row.col-xs-12 > div:nth-child(1) > div:nth-child(5){
    padding-left:6px !important;
    padding-right:6px !important;
}
  
</style>
<br><h4>Ventas Prestashop</h4>

<?php if(!isset($_SESSION['inicio']) ||$_SESSION['inicio']=="1970-01-01"){$_SESSION['inicio']=hoy();}  ?>
<?php if(!isset($_SESSION['final']) ||$_SESSION['final']=="3000-01-01"){$_SESSION['final']=hoy();}  ?>

<div class="row col-xs-12">
  <div class="col-xs-4" style="border:4px solid lightgrey">
    <div class="form-group col-xs-12">
      <h4>Seleccionar rango fechas</h4>
    </div>
    <div class="form-group col-xs-6" id='selAny'>
    <label for="sel1">Seleccionar año:</label>
      <?php echo form_dropdown('any', $anys,'',array('id'=>'any')); ?>
    </div>
    <div class="form-group col-xs-6" id='selMes'>
    <label for="sel1">Seleccionar mes:</label>
      <?php echo form_dropdown('mes', $meses,'',array('id'=>'mes')); ?>
    </div>

    <?php if(strtolower($this->session->username) != 'pernilall') { ?>
    <div class="form-group col-xs-6">
      <label for="desde">Desde:</label>
      <input type="date" class="form-control " min="<?php echo date('Y')-1 ?>-01-01" max="<?php echo date('Y-m-d') ?>" id="desde" name="desde" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['inicio'] : ''; ?>">
    </div>
    <div class="form-group col-xs-6">
      <label for="hasta">Hasta:</label>
      <input type="date" class="form-control " min="<?php echo date('Y')-1 ?>-01-01" max="<?php echo date('Y-m-d') ?>"  id="hasta" name="hasta" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['final'] : ''; ?>">
    </div>
    <?php } else { ?>
      <div class="form-group col-xs-6">
      <label for="desde">Desde:</label>
      <input type="date" class="form-control "  min="2016-01-01" max="<?php echo date('Y-m-d') ?>" id="desde" name="desde" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['inicio'] : ''; ?>">
    </div>
    <div class="form-group col-xs-6">
      <label for="hasta">Hasta:</label>
      <input type="date" class="form-control "  min="2016-01-01" max="<?php echo date('Y-m-d') ?>"  id="hasta" name="hasta" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['final'] : ''; ?>">
    </div>
    <?php }  ?>  

    <div class="form-group col-xs-12">
    <button type="button" id="verPedidos" class="btn btn-default">Ver tabla Pedidos Prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>
    <div class="form-group col-xs-12">
    <button type="button" id="verProductos" class="btn btn-default ">Ver tabla Productos Prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>
    <div class="form-group col-xs-12">
    <button type="button" id="verTransportes" class="btn btn-default ">Ver tabla Costes Transportes <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>

  </div>

  <div class="col-xs-1" style="width:5px !important">
  </div>

  <div class="col-xs-4" style="border:4px solid lightgrey">
    <div class="col-xs-12">
      <h4>Introducir núm. pedido</h4>
    </div>
    <div class="form-group col-xs-12">
      <label for="pedido_num">Núm pedido</label>
      <input type="text" class="form-control" id="pedido_num" placeholder="Introducir Núm pedido" value="<?php echo isset($_SESSION['pedido'])?$_SESSION['pedido']:''; ?>">
    </div>
    <div class="form-group col-xs-12">
    <button type="button" id="verPedidosNum" class="btn btn-default">Ver tabla Pedidos Prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>
    <div class="form-group col-xs-12">
    <button type="button" id="verProductosNum" class="btn btn-default ">Ver tabla Productos Prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>
    <div class="form-group col-xs-12">
    <button type="button" id="verTransportesNum" class="btn btn-default ">Ver tabla Costes Transportes <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>
  </div>
</div>









<!--
<div class="row col-xs-12"  >
    <div class="form-group col-xs-2" id='selAny'>
      <label for="sel1">Seleccionar año:</label>
      <?php echo form_dropdown('any', $anys,'',array('id'=>'any')); ?>
    </div>
    <div class="form-group col-xs-2" id='selMes'>
      <label for="sel1">Seleccionar mes:</label>
      <?php echo form_dropdown('mes', $meses,'',array('id'=>'mes')); ?>
    </div>
</div>
<div class="row">
<div class="form-group col-xs-2">
    <label for="desde">Desde:</label>
    <input type="date" class="form-control " id="desde" name="desde" value="<?php echo isset($_SESSION['inicio'])?$_SESSION['inicio']:''; ?>">
</div>  
</div> 
<div class="row">
  <div class="form-group col-xs-2">
    <label for="hasta">Hasta:</label>
    <input type="date" class="form-control " id="hasta" name="hasta" value="<?php echo isset($_SESSION['final'])?$_SESSION['final']:''; ?>">
  </div>
  </div>   
<div class="row"> 
  <div class="form-group col-xs-3">
    <button type="button" id="verPedidos" class="btn btn-default">Ver tabla Pedidos Prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
  </div>  
  <div class="row"> 
  <div class="form-group col-xs-3">
    <button type="button" id="verProductos" class="btn btn-default ">Ver tabla Productos Prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
  </div>  
  <div class="row"> 
  <div class="form-group col-xs-3">
    <button type="button" id="verTransportes" class="btn btn-default ">Ver tabla Costes Transportes <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
  </div>  
  -->

<script>
$(document).ready(function(){

  $('#any, #mes').change(function(){
    var anyActual=parseInt($('#any>option[value="1"]').html());
    var any=anyActual+1-parseInt($('#any').val())
    var mes=$('#mes').val()
    if(any==anyActual+1 || mes==0) return   
    if (mes<10) mes='0'+mes
    $('#desde').val(any+'-'+mes+'-01')
    // alert(any+'-'+mes+'-01')
    var ultimoDiaMes = new Date(any, mes, 0).getDate();
    $('#hasta').val(any+'-'+mes+'-'+ultimoDiaMes)
  })

  $('#desde,#hasta').keyup(function(){
    $('#any').val(0)
    $('#mes').val(0)
  })
  
  $('#verTransportes').click(function(){
        var desde=$('#desde').val()?$('#desde').val():"1970-01-01"
        var hasta=$('#hasta').val()?$('#hasta').val():"3000-01-01"

        if('<?php  echo strtolower($this->session->username)!='pernilall'; ?>'){
          $("#myModalError").css('color','blue')
            if(desde < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Desde debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
            if(hasta < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Hasta debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
      }

        if(desde>hasta) {
                $('.modal-title').html('Información error')
                $('.modal-body>p').html("<h4>La fecha <strong>Hasta</strong> debe ser posterior a la fecha <strong>Desde</strong> </h4>")
                $("#myModalError").modal({backdrop:"static",keyboard:"false"})
                return false
        }
        $("#verTransportes > img").removeClass('hide')
            var url="<?php echo base_url() ?>index.php/gestionTablas/pedidosPrestashopTransportes/"+desde+"/"+hasta
            window.location.href = url;
  })

  $('#verTransportesNum').click(function(){
    var pedido=$('#pedido_num').val()
        if (pedido == "") {
        $('.modal-title').html('Información error')
        $('.modal-body>p').html("<h4><strong>Se debe indicar un número de pedido</strong> </h4>")
        $("#myModalError").modal({
          // backdrop: "static",
          // keyboard: "false"
        })
        return false
      }
        $("#verTransportesNum > img").removeClass('hide')
            var url="<?php echo base_url() ?>index.php/gestionTablas/pedidosPrestashopTransportesNum/"+pedido
            window.location.href = url;
  })

  $('#verPedidos').click(function(){
        var desde=$('#desde').val()?$('#desde').val():"1970-01-01"
        var hasta=$('#hasta').val()?$('#hasta').val():"3000-01-01"
        // alert('desde '+desde+' '+'hasta '+hasta)
        if('<?php  echo strtolower($this->session->username)!='pernilall'; ?>'){
          $("#myModalError").css('color','blue')
            if(desde < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Desde debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
            if(hasta < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Hasta debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
      }



        if(desde>hasta) {
                $('.modal-title').html('Información error')
                $('.modal-body>p').html("<h4>La fecha <strong>Hasta</strong> debe ser posterior a la fecha <strong>Desde</strong> </h4>")
                $("#myModalError").modal({backdrop:"static",keyboard:"false"})
                return false
        }
        $("#verPedidos > img").removeClass('hide')
            var url="<?php echo base_url() ?>index.php/gestionTablas/pedidosPrestashop/"+desde+"/"+hasta
            window.location.href = url;
  })

  $('#verPedidosNum').click(function(){
        var pedido=$('#pedido_num').val()
        if (pedido == "") {
        $('.modal-title').html('Información error')
        $('.modal-body>p').html("<h4><strong>Se debe indicar un número de pedido</strong> </h4>")
        $("#myModalError").modal({
          // backdrop: "static",
          // keyboard: "false"
        })
        return false
      }
        $("#verPedidosNum > img").removeClass('hide')
            var url="<?php echo base_url() ?>index.php/gestionTablas/pedidosPrestashopNum/"+pedido
            window.location.href = url;
  })

  $('#verProductosNum').click(function(){
    

    var pedido=$('#pedido_num').val()
        if (pedido == "") {
        $('.modal-title').html('Información error')
        $('.modal-body>p').html("<h4><strong>Se debe indicar un número de pedido</strong> </h4>")
        $("#myModalError").modal({
          // backdrop: "static",
          // keyboard: "false"
        })
        return false
      }

        $("#verProductosNum > img").removeClass('hide')
          var url="<?php echo base_url() ?>index.php/ventas/analisisVentasNum/"+pedido+"/"+2
          window.location.href = url;
      })

      $('#verProductos').click(function(){
    

    var desde=$('#desde').val()?$('#desde').val():"1970-01-01"
    var hasta=$('#hasta').val()?$('#hasta').val():"3000-01-01"
    
    if('<?php  echo strtolower($this->session->username)!='pernilall'; ?>'){
            $("#myModalError").css('color','blue')
            if(desde < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Desde debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
            if(hasta < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Hasta debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
      }



    if(desde<"2018-02-26" || hasta<"2018-02-26"){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h4>El detalle de productos vendidos <strong>SOLO está disponible desde el 26/02/2018</strong></h4>")
              $("#myModalError").modal({backdrop:"static",keyboard:"false"})
              return false
    }
    if(desde>hasta) {
              $('.modal-title').html('Información error')
              $('.modal-body>p').html("<h4>La fecha <strong>Hasta</strong> debe ser posterior a la fecha <strong>Desde</strong> </h4>")
              $("#myModalError").modal({backdrop:"static",keyboard:"false"})
              return false
      }

      $("#verProductos > img").removeClass('hide')
        var url="<?php echo base_url() ?>index.php/ventas/analisisVentas/"+desde+"/"+hasta+"/"+2
        window.location.href = url;
    })

})



</script>