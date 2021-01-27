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
<h4>Ventas Tienda</h4>
<br>
<h4>Seleccionar rango fechas</h4>

<?php if (!isset($_SESSION['inicio']) || $_SESSION['inicio'] == "1970-01-01") {
  $_SESSION['inicio'] = hoy();
}  ?>
<?php if (!isset($_SESSION['final']) || $_SESSION['final'] == "3000-01-01") {
  $_SESSION['final'] = hoy();
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

<div class="row">
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
</div>
<div class="row">
  <div class="form-group col-xs-3">
    <button type="button" id="verTickets" class="btn btn-default">Ver tabla Tickets Tienda alternativa<img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div>
</div>
<div class="row">
  <!-- <div class="form-group col-xs-3">
    <button type="button" id="verProductos" class="btn btn-default">Ver tabla Productos Tienda <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
  </div> -->
</div>

<script>
  $(document).ready(function() {

    $('#any, #mes').change(function() {
      var anyActual = parseInt($('#any>option[value="1"]').html());
      var any = anyActual + 1 - parseInt($('#any').val())
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

    $('#verTickets').click(function() {
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




      if (desde > hasta) {
        $('.modal-title').html('Información error')
        $('.modal-body>p').html("<h4>La fecha <strong>Hasta</strong> debe ser posterior a la fecha <strong>Desde</strong> </h4>")
        $("#myModalError").modal({
          backdrop: "static",
          keyboard: "false"
        })
        return false
      }
      $("#verTickets > img").removeClass('hide')
      var url = "<?php echo base_url() ?>index.php/gestionTablas/tickets_/" + desde + "/" + hasta
      window.location.href = url;
    })

    $('#verProductos').click(function() {
      var desde = $('#desde').val() ? $('#desde').val() : "1970-01-01"
      var hasta = $('#hasta').val() ? $('#hasta').val() : "3000-01-01"
      if (desde > hasta) {
        $('.modal-title').html('Información error')
        $('.modal-body>p').html("<h4>La fecha <strong>Hasta</strong> debe ser posterior a la fecha <strong>Desde</strong> </h4>")
        $("#myModalError").modal({
          backdrop: "static",
          keyboard: "false"
        })
        return false
      }
      $("#verProductos > img").removeClass('hide')
      $.ajax({
        type: "POST",
        url : "<?php echo base_url() ?>index.php/ventas/numLineasAnalisisVentas/" + desde + "/" + hasta + "/" + 1,
        data: {
        },
        success: function(datos) {
          // alert(datos);
          // if(datos<=30000){
          if(datos<=30000){
            var url = "<?php echo base_url() ?>index.php/ventas/analisisVentas/" + desde + "/" + hasta + "/" + 1
            window.location.href = url;
          }
          else {
            $('.modal-title').html('Información')
        $('.modal-body>p').html("<h4>La selección supera las 30000 lineas. Seleccione un rango de fechas menor.</h4>")
        $("#myModal").modal({
          backdrop: "static",
          keyboard: "false"
        })
        
            // alert("La selección supera las 30000 lineas. Seleccione un rango de fechas menor.")
            $("#verProductos > img").addClass('hide')
          }
        },
        error: function() {
          alert('Error proceso navegarM. Informar ');
        }
      });

    })



  })
</script>