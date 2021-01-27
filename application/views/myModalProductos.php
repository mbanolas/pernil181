<!-- Modal -->
  <div class="modal fade" id="myModalProductos" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
     <!--     <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
          <div  class="hide" id="columnchart_material" style="padding:0 10px; height: 300px; display:block"></div>
        <div class="modal-footer">
          <h5>Para cerrar la ventana hacer click fuera</h5>
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
        </div>
      </div>
      
    </div>
  </div>

<div class="modal fade" id="myModalError" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
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
    $(document).ready(function () {
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
    
        // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
    })   
</script>  

<style type="text/css">
    #myModalError{
        color:red;
    }
    
</style>
    