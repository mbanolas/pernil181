<div class="container">

<h2>Ticket venta - cambiar forma de pago <span class="label label-default"></span></h2>
<h3>Datos ticket <span class="label label-default"></span></h3>
<hr>
<div class="row">
    <div class="col-md-2">Ticket núm:</div>
    <div class="col-md-10" ><?php echo $num_ticket ?></div>
    <input type="hidden" name="num_ticket" id="num_ticket" value="<?php echo $num_ticket ?>">
  </div>
  <div class="row">
    <div class="col-md-2">Fecha:</div>
    <div class="col-md-10"><?php echo fechaEuropea($fecha) ?></div>
    <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha ?>">

  </div>
  <div class="row">
    <div class="col-md-2">Importe:</div>
    <div class="col-md-10"><?php echo number_format($total/100,2) ?></div>
  </div>
  <div class="row">
    <div class="col-md-2">Cliente:</div>
    <div class="col-md-10" id="cliente"><?php echo $nombre_cliente ?></div>
  </div>
  <div class="row">
    <div class="col-md-2">Forma de pago:</div>
    <div class="col-md-10" id="forma_pago"><?php echo $forma_pago ?></div>
  </div>
  <br>
  <div class="form-group">
  <label for="sel1">Cambio forma de pago:</label>
  <select class="form-control" id="formas_pago" name="formas_pago">
      <?php foreach($formas_pago as $k=>$v){
          $selected="";
          if($k==$id_forma_pago_ticket) $selected="selected"; 
          echo '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
      } ?>
    
  </select>
  <div class="alert alert-success hide" id="mensaje">

    </div>
    <div class="alert alert-danger hide" id="mensaje_danger">

   </div>
   <div class="alert alert-warning hide" id="mensaje_warning">
      Para guardar la nueva forma de pago, pulsar Guardar nueva forma pago
</div>
</div>

<button type="button" id="guardar" class="btn btn-primary">Guardar nueva forma pago <img  class=" loader hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>


</div>

<script>
$(document).ready(function () {

    $('#formas_pago').change(function(){
        console.log($(this).val())
        console.log($('#formas_pago > option[value="'+$(this).val()+'"]').html())
        $('#mensaje').addClass('hide')
      $('#mensaje_danger').addClass('hide')
      $('#mensaje_warning').removeClass('hide')
        $("#forma_pago").html($('#formas_pago > option[value="'+$(this).val()+'"]').html())

    })

   

    $('button#guardar').click(function(e){
            
      $('#guardar > img ').removeClass('hide')

            if($('#formas_pago').val()==0){
                $('.modal-title').html('Información');
                $('.modal-body >p').html('Se debe seleccionar una forma de pago')
                $("#myModal").modal() 

            }
            // var num_factura=$('input#num_factura').val()
            var num_ticket=$('#num_ticket').val()
            var fecha=$('#fecha').val()
            var id_forma_pago=$('#formas_pago').val()
            var forma_pago=$('#formas_pago').html()
            console.log('num_ticket '+num_ticket)
            console.log('fecha '+fecha)
            console.log('id_forma_pago '+id_forma_pago)
            console.log('forma_pago '+forma_pago)
            $.ajax({
            // async: false,    
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/grabarFormaPago", 
            data:{'num_ticket':num_ticket, 'fecha':fecha,'id_forma_pago':id_forma_pago, 'forma_pago':forma_pago},
            success: function(datos){
                $('#mensaje_warning').addClass('hide')
                $("#guardar > img").addClass('hide')
              if (datos) {
            $('#mensaje').html('<strong> Forma de pago cambiada correctamente</strong>')
            $('#mensaje').removeClass('hide')

          } else {
            $('#mensaje_danger').html('<strong> No se ha posido completar la grabación correctamente.<br>Informar al administrador</strong>')
            $('#mensaje_danger').removeClass('hide')
          }
            },
            error: function(){
              $('#loader').addClass('hide')
              $('.modal-title').html('Información de ERROR');
                    $('.modal-body >p').html('Error en ajax tickets/grabarCliente.<br>Informar al administrador')
                    $("#myModal").modal() 
            }
        })
    })

//     $('#myModal').on('hidden.bs.modal', function () {
//       $("#guardar > img").addClass('hide')
//       window.location = "<?php echo base_url() ?>index.php/gestionTablas/ticketsTiendaEntreFechas";
// });

})

</script>