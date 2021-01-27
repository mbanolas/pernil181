<style>
  #any,
  #mes {
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

  #selAny,
  #selMes {
    background-color: lightgray;
    padding-top: 5px;
    padding-bottom: 5px;
  }
</style>
<br>
<h4>Facturas Acreedores</h4>
<br>
<h4>Seleccionar rango fechas</h4>

<?php if (!isset($_SESSION['inicio']) || $_SESSION['inicio'] == "1970-01-01") {
  // $_SESSION['inicio'] = hoy();
}  ?>
<?php if (!isset($_SESSION['final']) || $_SESSION['final'] == "3000-01-01") {
  // $_SESSION['final'] = hoy();
}  ?>
<div class="row col-xs-12">
  <div class="form-group col-xs-2" id='selAny'>
    <label for="sel1">Seleccionar año:</label>
    <?php echo form_dropdown('any', $anys, '', array('id' => 'any')); ?>
  </div>
  <div class="form-group col-xs-2" id='selMes'>
    <label for="sel1">Seleccionar mes:</label>
    <?php echo form_dropdown('mes', $meses, '', array('id' => 'mes')); ?>
  </div>
</div>

<!-- <div class="row">
  <div class="form-group col-xs-2">
    <label for="desde">Desde:</label>
    <input type="date" class="form-control " id="desde" name="desde" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['inicio'] : ''; ?>">
  </div>
</div>
<div class="row">
  <div class="form-group col-xs-2">
    <label for="hasta">Hasta:</label>
    <input type="date" class="form-control " id="hasta" name="hasta" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['final'] : ''; ?>">
  </div>
</div> -->

<?php if(strtolower($this->session->username) != 'pernilall') { ?>
    <div class="form-group col-xs-2">
      <label for="desde">Desde:</label>
      <input type="date" class="form-control " min="<?php echo date('Y')-1 ?>-01-01" max="<?php echo date('Y-m-d') ?>" id="desde" name="desde" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['inicio'] : ''; ?>">
    </div>
    <div class="form-group col-xs-2">
      <label for="hasta">Hasta:</label>
      <input type="date" class="form-control " min="<?php echo date('Y')-1 ?>-01-01" max="<?php echo date('Y-m-d') ?>"  id="hasta" name="hasta" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['final'] : ''; ?>">
    </div>
    <?php } else { ?>
      <div class="form-group col-xs-2">
      <label for="desde">Desde:</label>
      <input type="date" class="form-control "  min="2016-01-01" max="<?php echo date('Y-m-d') ?>" id="desde" name="desde" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['inicio'] : ''; ?>">
    </div>
    <div class="form-group col-xs-2">
      <label for="hasta">Hasta:</label>
      <input type="date" class="form-control "  min="2016-01-01" max="<?php echo date('Y-m-d') ?>"  id="hasta" name="hasta" value="<?php echo isset($_SESSION['inicio']) ? $_SESSION['final'] : ''; ?>">
    </div>
    <?php }  ?> 


<div class="row">
  <div class="form-group col-xs-12">
    <?php if (!isset($_SESSION['tipo'])) { ?>
      <label class="radio-inline"><input type="radio" name="claseFacturas" checked id="todas">Todas las facturas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="pagadas">Sólo Facturas Pagadas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="sinPagar">Sólo Facturas Pendientes Pago</label>
    <?php } ?>
    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'todas') { ?>
      <label class="radio-inline"><input type="radio" name="claseFacturas" checked id="todas">Todas las facturas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="pagadas">Sólo Facturas Pagadas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="sinPagar">Sólo Facturas Pendientes Pago</label>
    <?php } ?>
    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'pagadas') { ?>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="todas">Todas las facturas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" checked id="pagadas">Sólo Facturas Pagadas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="sinPagar">Sólo Facturas Pendientes Pago</label>
    <?php } ?>
    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'sinPagar') { ?>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="todas">Todas las facturas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" id="pagadas">Sólo Facturas Pagadas</label>
      <label class="radio-inline"><input type="radio" name="claseFacturas" checked id="sinPagar">Sólo Facturas Pendientes Pago</label>
    <?php } ?>

  </div>
</div>
<div class="row">
  <div class="form-group col-xs-3">
    <button type="button" id="verFacturasAcreedores" class="btn btn-default">Ver tabla Facturas Acreedores <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
</div>
<div class="row">
  <div class="form-group col-xs-3">
    <button type="button" id="nuevaFacturaAcreedor" class="btn btn-default">Nueva Factura Acreedor <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
</div>
<!-- <div class="row"> 
  <div class="form-group col-xs-3">
    <button type="button" id="verProductos" class="btn btn-default">Ver tabla Productos Tienda <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
  </div>   -->

<script>
  $(document).ready(function() {

    $('#any, #mes').change(function() {
      // console.log($('#any').val())
      var anyActual = parseInt($('#any>option[value="1"]').html());
      var any = anyActual + 1 - parseInt($('#any').val())
      if($('#mes').val()==0){
        $('#desde').val(any + '-' + '01' + '-01')
        $('#hasta').val(any + '-' + '12'+ '-' + '01')
      }
      if($('#any').val()==0){
        $('#mes').val(0)
        $('#desde').val('dd/mm/aaaa')
        $('#hasta').val('dd/mm/aaaa')
      }
      var mes = $('#mes').val()
      if (any == anyActual + 1 || mes == 0) return
      if (mes < 10) mes = '0' + mes
      $('#desde').val(any + '-' + mes + '-01')
      // alert(any+'-'+mes+'-01')
      var ultimoDiaMes = new Date(any, mes, 0).getDate();
      $('#hasta').val(any + '-' + mes + '-' + ultimoDiaMes)
    })

    $('#desde,#hasta').keyup(function() {
      $('#any').val(0)
      $('#mes').val(0)
    })


    var tipo = "<?php echo isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '' ?>"


    $('#verFacturasAcreedores').click(function() {
      var desde = $('#desde').val() ? $('#desde').val() : "1970-01-01"
      var hasta = $('#hasta').val() ? $('#hasta').val() : "3000-01-01"
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
      tipo = ""
      if ($('#todas').is(':checked')) tipo = 'todas'
      if ($('#pagadas').is(':checked')) tipo = 'pagadas'
      if ($('#sinPagar').is(':checked')) tipo = 'sinPagar'

      if (desde > hasta) {
        $('.modal-title').html('Información error')
        $('.modal-body>p').html("<h4>La fecha <strong>Hasta</strong> debe ser posterior a la fecha <strong>Desde</strong> </h4>")
        $("#myModalError").modal({
          backdrop: "static",
          keyboard: "false"
        })
        return false
      }
      $("#verFacturasAcreedores > img").removeClass('hide')
      var url = "<?php echo base_url() ?>index.php/gestionTablas/facturasAcreedores/" + tipo + "/" + desde + "/" + hasta
      window.location.href = url;
    })

    $('#nuevaFacturaAcreedor').click(function() {
      var url = "<?php echo base_url() ?>index.php/gestionTablas/facturasAcreedores/todas/1970-01-01/3000-01-01/add"
      window.location.href = url;
    })



  })
</script>