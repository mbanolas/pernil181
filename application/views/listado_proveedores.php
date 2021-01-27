<?php 
//echo $query;
?>

<div class="container ">
    <div class="row">
<div class="col-xs-12 col-sm-4">
<button  style="display: inline;" type="submit" class="btn btn-default btn-mini nuevoProveedor" name="proveedores">
  <span class="" aria-hidden="true"></span> Nuevo Proveedor
</button>


</div>
<div class="col-xs-12 col-sm-8">
    <h4>Listado Proveedores</h4>
</div>
</div>
</div>
<div class="container listado">
<!-- Tabla listado productos -->
<table id="listadoProveedores" class="display" cellspacing="0" width="100%">
    <thead>
        <?php $campos=array('id_proveedor', 'id_contable','nombre', 'domicilio', 'cp', 'poblacion', 'provincia', 'pais', 'telefono', 'cif', 'fax', 'email1', 'email2', 'contacto', 'web', 'telefono2', 'movil', 'otros', 'nota', 'fechaAlta', 'fechaModificacion'); ?>

        <?php $cabeceras=array('Códigp proveedor', 'Código contable','Nombre', 'Domicilio', 'C.P.', 'Poblacion', 'Provincia', 'País', 'Teléfono', 'CIF', 'Fax', 'Email 1', 'Email 2', 'Contacto', 'Web', 'Teléfono 2', 'Móvil', 'Otros', 'Nota', 'Fecha Alta', 'Fecha Modificación');;
        $cabeceras[]=' ';
        foreach($cabeceras as $v){ ?>
        <th class="listadoProveedor">
            <?php echo $v ?>
        </th>
        
        <?php
        }
        ?>
</thead>  
<tbody>
    <?php
   
    foreach ($results as $k => $row) {
        $row->fechaAlta=  fechaEuropea($row->fechaAlta);
        $row->fechaModificacion=  fechaEuropea($row->fechaModificacion);
        ?>
        
    <tr>
      
        <?php foreach($campos as $k=> $v){ ?>
        <td class="listadoProveedor <?php echo $v ?>">  
            <?php echo $row->$v ?>
        </td>
        
        <?php } ?>
        <!--
        <td class="listadoProveedor id_proveedor">  
            <?php echo $row->id_proveedor ?>
        </td>
        <td class="listadoProveedor nombre">
            <?php echo $row->nombre ?>
        </td>
        -->
        
        
        <td >
            <button type="button" class="close"  aria-label="Cerrar"><span class="eliminarProveedor" >X</span></button>
            <input class="linea" type="hidden" name="linea" value="<?php echo $row->id_proveedor ?>" />
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
        <h4 class="modal-title" id="myModalLabel">Editar Proveedor</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal proveedor">
              <fieldset >

                  <!-- Text input-->
                  <div class="form-group" >
                      <label class="col-md-4 control-label" for="id_codigo">Código Proveedor</label>  
                      <div class="col-md-4" style="padding: 0px; margin: 0px;">
                          <label class="col-md-4 control-label" id="label_id_proveedor" style="padding: 7px 0px 0 0; margin: 0px;text-align: left;"></label> 
                          <input id="linea" type="hidden" name="linea" value="" />
                          <input style="text-align: left" id="id_proveedor" name="id_proveedor" type="hidden"  class="form-control "  >
                      </div>
                  </div>

                  <!-- Text input-->
                  <?php for($i=1;$i<sizeof($campos);$i++) {?>
                  <div class="form-group input-sm" style="padding: 0px; margin: 0px;">
                      <?php $f= "for='$campos[$i]'" ; ?>
                      <label class="col-md-4 control-label" <?php echo $f ?>> <?php echo $cabeceras[$i] ?></label>  
                      <div class="col-md-6 " style="padding: 0px; margin: 0px;">
                          <?php $id= "id='$campos[$i]'" ; ?>
                          <input  <?php echo $id ?> name="<?php echo $campos[$i] ?>" type="text" placeholder="<?php echo $cabeceras[$i] ?>" class="form-control input-sm" required=""  value="">
                      </div>
                  </div>
                  
                  <?php } ?>
                 
                  <!-- Button  -->
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
<div class="modal " id="myModalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Proveedor</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal proveedorNuevo">
              <fieldset>
                  
                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="id_codigo">Código Proveedor</label>  
                      <div class="col-md-4">
                          <input id="linea" type="hidden" name="linea" value="" />
                          <input style="text-align: left" id="id_proveedorNuevo" name="id_proveedor" type="text"  class="form-control "  >

                      </div>
                  </div>

                  
                  <!-- Text input-->
                  <?php for($i=1;$i<sizeof($campos);$i++) {?>
                  <div class="form-group input-sm" style="padding: 0px; margin: 0px;">
                      <?php $f= "for='$campos[$i]'" ; ?>
                      <label class="col-md-4 control-label" <?php echo $f ?>> <?php echo $cabeceras[$i] ?></label>  
                      <div class="col-md-6 " style="padding: 0px; margin: 0px;">
                          <?php $id= "id='$campos[$i]'.Nuevo" ; ?>
                          <input  <?php echo $id ?> name="<?php echo $campos[$i] ?>" type="text" placeholder="<?php echo $cabeceras[$i] ?>" class="form-control input-sm" required=""  value="">
                      </div>
                  </div>
                  <?php } ?>
                  <!-- Text input
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="nombre">Nombre Proveedor</label>  
                      <div class="col-md-6">
                          <input id="proveedorNuevo" name="nombre" type="text" placeholder="Nombre Proveedor" class="form-control input-md" required="required"  value="">

                      </div>
                  </div>
                 -->
                  <!-- Button  -->
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
    </div>
  </div>
</div>

<!-- Modal Eliminar Producto -->
<div class="modal " id="myModalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Proveedor</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal proveedorEliminar">
              <fieldset>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="id_proveedor">Código Proveedor</label>  
                      <div class="col-md-6">
                          <label  id="label_id_proveedorEliminar"></label>
                          <input style="text-align: left" id="id_proveedorEliminar" name="id_proveedorEliminar" type="hidden"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="nombre">Nombre Proveedor </label>  
                      <div class="col-md-6">
                          <label  id="proveedorEliminar"></label>
                      </div>
                  </div>
                  
                  <!-- Button (Double) -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="guardar"></label>
                      <div class="col-md-8">
                        <button id="guardarEliminar" name="guardar" class="btn btn-danger">Eliminar Proveedor</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>

              </fieldset>
          </form>
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
  $('#menuListaProveedores').addClass('btn-primary');    
})
</script>

<script>
$(document).ready(function () {
    
   $("button#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/proveedor", //process para grabar
            data: $('form.proveedor').serialize(),
            success: function(datos){
               // alert(datos);
                var obj = $.parseJSON(datos);
                var linea=obj.linea;
                $('#myModal').modal('hide'); 
                for (var campo in obj.resultados){
                    $('input.linea[value='+linea+']').parent().parent().children('td.'+campo).html(obj.resultados[campo]);
                }
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
            url: "<?php echo base_url() ?>"+"index.php/process/proveedorNuevo", //process para grabar
            data: $('form.proveedorNuevo').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                if (obj.salida==false){
                    $('.modalError').text("Proveedor con código y/o nombre YA existente o falta algún campo");
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
            url: "<?php echo base_url() ?>"+"index.php/process/proveedorEliminar", //process para grabar
            data: $('form.proveedorEliminar').serialize(),
            success: function(datos){
                //alert(datos);
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
           
            
