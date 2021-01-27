<div class="container">

  <h2>Ticket venta - Asignación cliente <span class="label label-default"></span></h2>
  <h3>Datos ticket <span class="label label-default"></span></h3>
  <hr>
  <div class="row">
    <div class="col-md-2">Ticket núm:</div>
    <div class="col-md-10"><?php echo $num_ticket ?></div>
    <input type="hidden" name="num_ticket" id="num_ticket" value="<?php echo $num_ticket ?>">
  </div>
  <div class="row">
    <div class="col-md-2">Fecha:</div>
    <div class="col-md-10"><?php echo fechaEuropea($fecha) ?></div>
    <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha ?>">

  </div>
  <div class="row">
    <div class="col-md-2">Importe:</div>
    <div class="col-md-10"><?php echo number_format($total / 100, 2) ?></div>
  </div>
  <div class="row">
    <div class="col-md-2">Cliente:</div>
    <div class="col-md-10" id="cliente"><?php echo $clientes[$id_cliente] ?></div>
  </div>
  <br>
  <div class="form-group">
    <label for="sel1">Cambio/asignación cliente:</label>
    <select class="form-control" id="clientes" name="cliente">
      <?php foreach ($clientes as $k => $v) {
        $selected = "";
        if ($k == $id_cliente) $selected = "selected";
        echo '<option ' . $selected . ' value="' . $k . '">' . $v . '</option>';
      } ?>

    </select>

    <div class="alert alert-success hide" id="mensaje">

    </div>
    <div class="alert alert-danger hide" id="mensaje_danger">

    </div>
    <div class="alert alert-warning hide" id="mensaje_warning">
      Para guardar el cliente pulsar Guardar cliente
    </div>

  </div>

  <button type="button" id="guardar" class="btn btn-primary">Guardar cliente <img class=" loader hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>


</div>

<script>
  $(document).ready(function() {

    $('#clientes').change(function() {
      $('#mensaje').addClass('hide')
      $('#mensaje_danger').addClass('hide')
      $('#mensaje_warning').removeClass('hide')

      $("#cliente").html($('#clientes > option[value="' + $(this).val() + '"]').html())
    })

    $('button#guardar').click(function(e) {

      $('#guardar > img ').removeClass('hide')
      $('#mensaje_warning').addClass('hide')

      var num_ticket = $('#num_ticket').val()
      var fecha = $('#fecha').val()
      var cliente = $('#clientes').val()

      $.ajax({
        // async: false,    
        type: "POST",
        url: "<?php echo base_url() ?>" + "index.php/tickets/grabarCliente",
        data: {
          'num_ticket': num_ticket,
          'fecha': fecha,
          'cliente': cliente
        },
        success: function(datos) {
          $("#guardar > img").addClass('hide')
          // alert(datos)
          if (datos) {
            $('#mensaje').html('<strong> Cliente cambiado correctamente</strong>')
            $('#mensaje').removeClass('hide')
          } else {
            $('#mensaje_danger').html('<strong> No se ha posido completar la grabación correctamente.<br>Informar al administrador</strong>')
            $('#mensaje_danger').removeClass('hide')
          }
        },
        error: function() {
          $('#loader').addClass('hide')
          $('#mensaje_danger').html('<strong> Error en ajax tickets/grabarCliente.<br>Informar al administrador.<br>Informar al administrador</strong>')
        }
      })
    })
  })
</script>