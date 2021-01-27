<style>
  .form-control-plaintext{
    width:500px !important;
  }
</style>

<!-- Modal -->
<div class="modal fade" id="myModalProducto" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header prod</h4>
      </div>
      <div class="modal-body">

      <input type="text" class="form-control " name="codigo_producto" value="0101000108000">

      <div class="errorVerificacion">
        <p>Texto error</p>
        <p>Texto error</p>
      </div>

      </div>
      <div class="hide" id="columnchart_material" style="padding:0 10px; height: 300px; display:block"></div>
      <div class="modal-footer">
        <button type="button" id="grabar" class="btn btn-primary" ><i class="fa fa-floppy-o fa-lg" aria-hidden="true"> </i> Grabar modificaciones <i class="fa fa-spinner fa-pulse fa-lg fa-fw hide"></i></button>
        <button type="button" id="cancelar" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-warning fa-lg" aria-hidden="true"> </i> Cancelar</button>
        <button type="button" id="cerrar" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="myModalError" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header error</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>



<script>
  $(document).ready(function() {
    function alerta(titulo = "Información", mensaje) {

      $('.modal-title').html(titulo)
      $('.modal-body>p').html(mensaje)
      $("#myModal").modal()
    }

    function alertaError(titulo = "Información importante", mensaje) {

      $('.modal-title').html(titulo)
      $('.modal-body>p').html(mensaje)
      $("#myModalError").modal()
    }

    // Jquery draggable
    $('.modal-dialog').draggable({
      handle: ".modal-header"
    });
  })
</script>

<style type="text/css">
  #myModalError {
    color: red;
  }
</style>