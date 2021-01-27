<?php 
//echo $query;
?>




<div class="container ">
<div class="col-xs-12 col-sm-4">
<button  style="display: inline;" type="submit" class="btn btn-default btn-mini nuevoCliente" name="clientes">
  <span class="" aria-hidden="true"></span> Nuevo Cliente
</button>


</div>
<div class="col-xs-12 col-sm-8">
    <h4>Listado Clientes </h4>
    <input id="base_url" type="hidden" name="linea" value="<?php echo base_url() ?>" />
</div>
<!-- Tabla listado clientes -->

<table id="listadoClientes" class="display" cellspacing="0" width="100%">
    <thead>
        <?php foreach ($cabecerasLista as $k => $v) { ?>
        <th class="listadoCliente">
            <?php echo $v ?>
        </th>
    <?php } ?>
    <th class="listadoCliente">
        <?php echo ' ' ?>
    </th>
</thead>  
<tbody>
    <?php
   
    foreach ($results as $k => $row) { ?>
    <tr>
        <?php foreach($camposLista as $kc => $vc) { ?>
            <td class="listadoCliente <?php echo $vc ?>">  
                <?php echo $row->$vc ?>
        <?php }?>
        
        <td >
            <button type="button" class="close"  aria-label="Cerrar"><span class="eliminarCliente" >X</span></button>
            <input class="linea" type="hidden" name="linea" value="<?php echo $row->id_cliente ?>" />
            
        </td>
    </tr>
   <?php } ?>
</tbody>
</table>
</div>
<br />

<br />
<br />




<input type="hidden" id="base" value="<?php echo base_url() ?>" >

<!-- Modal Editar producto -->
<div class="modal modal-cliente" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-cliente">
    <div class="modal-content modal-content-cliente">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar Cliente</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal cliente">
              <fieldset>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-3 control-label" for="id_codigo">Código Cliente</label>  
                      <div class="col-md-4">
                          <label class="col-md-9 control-label" id="label_id_cliente" ></label> 
                          <input style="text-align: left" id="id_cliente" name="id_cliente" type="hidden"  class="form-control "  >

                      </div>
                  </div>
                  
                  <?php foreach ($camposBD as $kc => $vc) { if($kc>0) {?>
                      <div class="form-group_">
                      <label class="col-md-3 control-label" for="<?php echo $vc ?>"><?php echo $cabecerasBD[$kc] ?></label>  
                      <div class="col-md-3">
                          <input id="<?php echo $vc ?>" name="<?php echo $vc ?>" type="text" placeholder="<?php echo $cabecerasBD[$kc] ?>" class="form-control input-md" required=""  value="<?php echo $vc ?>">
                      </div>
                  </div>
                  <?php }} ?>
                  
              
               
                  <!-- Button (Double) -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="guardar"></label>
                      <div class="col-md-8">
                        <button id="guardar" name="guardar" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>

              </fieldset>
          </form>
          
      </div>
    </div>
  </div>
</div>

<!-- Modal Nuevo producto -->
<div class="modal modal-cliente" id="myModalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content modal-content-cliente">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Cliente</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal clienteNuevo">
              <fieldset>
                  
                  <!--
                     <div class="form-group">
                      <label class="col-md-3 control-label" for="id_codigo">Código Cliente</label>  
                      <div class="col-md-4">
                          <label class="col-md-9 control-label" id="label_id_cliente" ></label> 
                          <input style="text-align: left" id="id_cliente" name="id_cliente" type="hidden"  class="form-control "  >

                      </div>
                  </div>
                  -->
                  <?php foreach ($camposBD as $kc => $vc) { if($kc>-1) {?>
                      <div class="form-group_">
                      <label class="col-md-3 control-label" for="<?php echo $vc ?>"><?php echo $cabecerasBD[$kc] ?></label>  
                      <div class="col-md-3">
                          <input id="<?php echo $vc.'Nuevo' ?>" name="<?php echo $vc ?>" type="text" placeholder="<?php echo $cabecerasBD[$kc] ?>" class="form-control input-md" required=""  value="<?php echo $vc ?>">
                      </div>
                  </div>
                  <?php }} ?>
                  
                  
                  
                 
                  <!-- Button (Double) 
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="guardar"></label>
                      <div class="col-md-8">
                        <button id="guardarNuevo" name="guardarNuevo" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>
                  <label style="text-align: left; color: red" class="modalError col-md-12 control-label" for="guardar"></label>
-->
              </fieldset>
          </form>
          <div class="row">
                      
                      <div class="col-md-offset-5  col-md-6">
                        <button id="guardarNuevo" name="guardarNuevo" class="btn btn-success">Guardar Cliente</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
          <label style="text-align: left; color: red" class="modalError col-md-12 control-label" for="guardar"></label>
                  </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Eliminar Producto -->
<div class="modal modal-cliente" id="myModalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content modal-content-cliente">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Cliente</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal clienteEliminar">
              <fieldset>
                          <input style="text-align: left" class="id_clienteEliminar" name="id_cliente" type="hidden"  class="form-control "  >

                  <?php foreach ($camposBD as $kc => $vc) { if($kc>-1) {?>
                      
                      <label class="col-md-2 control-label" for="<?php echo $vc ?>"><?php echo $cabecerasBD[$kc] ?></label>  
                      <div class="col-md-4">
                          <label id="<?php echo $vc.'Eliminar' ?>" name="<?php echo $vc ?>"  class="form-control input-md"   ></label>
                      </div>
                  
                  <?php }} ?>
                  
                 
                   
                  <!-- Button (Double) -->
                  
                  
              </fieldset>
          </form>
          <div class="row">
                      
                      <div class="col-md-offset-5  col-md-6">
                        <button id="guardarEliminar" name="guardar" class="btn btn-danger">Eliminar Cliente</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuListados').addClass('btn-primary');
  $('#menuListaClientes').addClass('btn-primary');    
})
</script>

<script>
$(document).ready(function () {
    
   $("button#guardar").click(function(e){
       //alert('Hola')
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/cliente", //process para grabar
            data: $('form.cliente').serialize(),
            success: function(datos){
                
                var obj = $.parseJSON(datos);
                location.reload();
            },
            error: function(){
                alert("fallo");
            }
        });
        
    });
    
    $("button#guardarNuevo").click(function(e){
        e.preventDefault();
        //alert('Hola boton guardarNuevo');
        //alert($('form.familiaNuevo').serialize());
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/clienteNuevo", //process para grabar
            data: $('form.clienteNuevo').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                if (obj.salida==false){
                    $('.modalError').text("Cliente con código y/o nombre YA existente");
                }
                else {
                    //alert ("familia dado de Alta correctamente");
                    $('#myModalNuevo').modal('hide'); 
                    location.reload();
                }   
            },
            error: function(){
                alert("fallo");
            }
        });
        
    });
    
    $("button#guardarEliminar").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/clienteEliminar", //process para grabar
            data: $('form.clienteEliminar').serialize(),
            success: function(datos){
               // alert(datos);
                var obj = $.parseJSON(datos);
                
                $('#myModalEliminar').modal('hide'); 
                    location.reload();
               
            },
            error: function(){
                alert("fallo");
            }
        });
        
    });
    
});

</script>
            
