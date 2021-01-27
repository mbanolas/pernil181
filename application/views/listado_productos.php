<?php 
//echo $query;
?>

<div class="col-xs-12 col-sm-4">
<button  style="display: inline;" type="submit" class="btn btn-default btn-mini nuevoProducto" name="productos">
  <span class="" aria-hidden="true"></span> Nuevo Producto
</button>


</div>
<div class="col-xs-12 col-sm-8">
    <h4>Listado Productos</h4>
</div>

<!-- Tabla listado productos -->
<table id="listadoProductos" class="display" cellspacing="0" width="100%">
    <thead>
        <?php $cabeceras=array('Código Producto','Nombre Producto','Familia','Proveedor','Precio',' ');
        foreach($cabeceras as $v){ ?>
        <th class="listadoProducto">
            <?php echo $v ?>
        </th>
        
        <?php
        }
        ?>
</thead>  
<tbody>
    <?php
   
    foreach ($results as $k => $row) { ?>
    
    <tr>
        
        <td class="listadoProducto id_producto">  
            
            <?php echo trim($row->id_producto) ?>
        </td>
        <td class="listadoProducto producto">
            <?php echo $row->producto ?>
        </td>
        <td class="listadoProducto familia">
            <input type="hidden" name="id_familia" value="<?php echo $row->id_familia ?>">
            <?php echo $row->familia ?>
        </td>
        <td class="listadoProducto proveedor">
            <input type="hidden" name="id_proveedor" value="<?php echo $row->id_proveedor ?>">
            <?php echo $row->proveedor ?>
        </td>
        
        <td class="listadoProducto   derecha precio">
            <?php echo $row->precio ?>
        </td>
        <td >
            <button type="button" class="close"  aria-label="Cerrar"><span class="eliminarProducto" >X</span></button>
           <input class="linea" type="hidden" name="linea" value="<?php echo $row->id_producto ?>" />
        </td>
        
    </tr>
        
        <?php
    }
    ?>
</tbody>
</table>
<br />

<br />
<br />




<input type="hidden" id="base" value="<?php echo base_url() ?>" >


<!-- Modal Editar producto -->
<div class="modal " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar Producto</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal producto">
              <fieldset>

                  
                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="id_codigo">Código Producto</label>  
                      <div class="col-md-4">
                          <label style="text-align: left; padding-left: 0px;" class="col-md-4 control-label" id="label_id_producto" ></label> 
                          <input id="linea" type="hidden" name="linea" value="" />
                          <input style="text-align: left" id="id_producto" name="id_producto"  type="hidden"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="producto">Nombre Producto</label>  
                      <div class="col-md-6">
                          <input id="producto" name="producto" type="text" placeholder="Nombre Producto" class="form-control input-md" required=""  value="">

                      </div>
                  </div>

                  <!-- Select Basic -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="familia">Familia</label>
                      <div class="col-md-4">
                          <select id="familia" name="familia" class="form-control">
                              <option value="0">Seleccionar una familia</option>
                              <?php foreach($familias as $k=>$v){
                                  echo "<option value='$v->id_familia'>$v->nombre</option>";
                              }
                              ?>
                          </select>
                      </div>
                  </div>

                  
                  <!-- Select Basic -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="proveedor">Proveedor</label>
                      <div class="col-md-6">
                          <select id="proveedor" name="proveedor" class="form-control">
                              <option value="0">Seleccionar un proveedor</option>
                              <?php foreach($proveedores as $k=>$v){
                                  echo "<option value='$v->id_proveedor'>$v->nombre</option>";
                              }
                              ?>
                          </select>
                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="precio">Precio</label>  
                      <div class="col-md-4">
                          <input id="precio" name="precio" type="text" placeholder="Precio (€)" class="form-control input-md">

                      </div>
                  </div>

                  <!-- Button (Double) -->
                  <div class="form-group">
                      <div class="col-md-4"></div>
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
<div class="modal " id="myModalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Producto</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal productoNuevo">
              <fieldset>
                  
                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="id_codigo">Código Producto</label>  
                      <div class="col-md-4">
                          <input id="linea" type="hidden" name="linea" value="" />
                          <input style="text-align: left" id="id_productoNuevo" name="id_producto" type="text"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="nombre">Nombre Producto</label>  
                      <div class="col-md-6">
                          <input id="productoNuevo" name="producto" type="text" placeholder="Nombre Producto" class="form-control input-md" required="required"  value="">

                      </div>
                  </div>

                  <!-- Select Basic -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="familia">Familia Producto</label>
                      <div class="col-md-4">
                          <select id="familiaNuevo" name="familia" class="form-control" required="required" >
                              <option value="0" selected="selected">Seleccionar una familia</option>
                              <?php foreach($familias as $k=>$v){
                                  echo "<option value='$v->id_familia'>$v->nombre</option>";
                              }
                              ?>
                          </select>
                      </div>
                  </div>

                  <!-- Select Basic -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="proveedor">Nombre Proveedor</label>
                      <div class="col-md-6">
                          <select id="proveedorNuevo" name="proveedor" class="form-control" required="required" >
                              <option value="0">Seleccionar un proveedor</option>
                              <?php foreach($proveedores as $k=>$v){
                                  echo "<option value='$v->id_proveedor'>$v->nombre</option>";
                              }
                              ?>
                          </select>
                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="precio">Precio</label>  
                      <div class="col-md-4">
                          <input id="precioNuevo" name="precio" type="text" placeholder="Precio (€)" class="form-control input-md" required="required" >

                      </div>
                  </div>

                  <!-- Button (Double) -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="guardar"></label>
                      <div class="col-md-8">
                        <button id="guardarNuevo" name="guardarNuevo" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>
                  <label style="text-align: left; color: red" class="modalError col-md-12 control-label" for="guardar"></label>

              </fieldset>
          </form>

          
          
          
      </div>
      <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        
      </div>
       --> 
    </div>
      
  </div>

    
</div>

<!-- Modal Eliminar Producto -->
<div class="modal " id="myModalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Producto</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal productoEliminar">
              <fieldset>


                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="id_codigo">Código Producto</label>  
                      <div class="col-md-6">
                          <label  id="label_id_productoEliminar"></label>
                          <input style="text-align: left" id="id_productoEliminar" name="id_producto" type="hidden"  class="form-control "  >
                         <input style="text-align: left" id="lineaEliminar" name="linea" type="hidden"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="nombre">Nombre Producto </label>  
                      <div class="col-md-6">
                          <label  id="productoEliminar"></label>
                      </div>
                  </div>

                <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="nombre">Familia </label>  
                      <div class="col-md-6">
                          <label  id="nombreFamiliaEliminar"></label>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="nombre">Proveedor </label>  
                      <div class="col-md-6">
                          <label  id="nombreProveedorEliminar"></label>
                      </div>
                  </div>
                  
                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="precio">Precio</label>  
                      <div class="col-md-4">
                          <label  id="precioEliminar"></label>
                      </div>
                  </div>

                  <!-- Button (Double) -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="guardar"></label>
                      <div class="col-md-8">
                        <button id="guardarEliminar" name="guardar" class="btn btn-danger">Eliminar Producto</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>

              </fieldset>
          </form>
      </div>
        <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        
      </div>
       --> 
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuListados').addClass('btn-primary');
  $('#menuListaProductos').addClass('btn-primary');  
})
</script>


<script>
$(document).ready(function () {
    
    $("button#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/producto", //process para grabar
            data: $('form.producto').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                var linea=obj.linea;
                var producto=obj.producto;
                var familia=obj.familia;
                var nombreFamilia=obj.nombreFamilia;
                var nombreProveedor=obj.nombreProveedor;
                var proveedor=obj.proveedor;
                var precio=obj.precio;
                
                var textoFamilia='<input type="hidden" name="id_familia" value="'+familia+'">'+nombreFamilia;
                
                var textoProveedor='<input type="hidden" name="id_proveedor" value="'+proveedor+'">'+nombreProveedor
               
                $('#myModal').modal('hide'); 
                $('input.linea[value='+linea+']').parent().parent().children('.producto').html(producto);
               // $('input.linea[value='+linea+']').parent().parent().children('.familia').children().val(familia);
                $('input.linea[value='+linea+']').parent().parent().children('.familia').html(textoFamilia);
               // $('input.linea[value='+linea+']').parent().parent().children('.proveedor').children().val(proveedor);
                $('input.linea[value='+linea+']').parent().parent().children('.proveedor').html(textoProveedor);
                
                $('input.linea[value='+linea+']').parent().parent().children('.precio').html(precio);
            },
            error: function(){
                alert("fallo");
            }
        });
        
    });
    
    $("button#guardarNuevo").click(function(e){
        e.preventDefault();
        //alert('Hola boton guardarNuevo');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/productoNuevo", //process para grabar
            data: $('form.productoNuevo').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                
                if (obj.salida==false){
                    $('.modalError').text("Producto con código YA existente o falta algún campo");
                }
                else {
                    //alert ("Producto dado de Alta correctamente");
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
            url: "<?php echo base_url() ?>"+"index.php/process/productoEliminar", //process para grabar
            data: $('form.productoEliminar').serialize(),
            success: function(datos){
               // alert(datos);
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
           
            
