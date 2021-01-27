<?php 
//echo $query;
?>




<div class="col-xs-12 col-sm-4">
<button  style="display: inline;" type="submit" class="btn btn-default btn-mini nuevoFamilia" name="familias">
  <span class="" aria-hidden="true"></span> Nueva Familia
</button>


</div>
<div class="col-xs-12 col-sm-8">
    <h4>Listado Familias</h4>
</div>

<!-- Tabla listado familias -->
<table id="listadoFamilias" class="display" cellspacing="0" width="100%">
    <thead>
        <?php $cabeceras=array('Código Familia','Nombre Familia',' ');
        foreach($cabeceras as $v){ ?>
        <th class="listadoFamilia">
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
        
        <td class="listadoFamilia id_familia">  
            
            <?php echo $row->id_familia ?>
        </td>
        <td class="listadoFamilia familia">
            <?php echo $row->familia ?>

        </td>
        
        <td >
            <button type="button" class="close"  aria-label="Cerrar"><span class="eliminarFamilia" >X</span></button>
           <input class="linea" type="hidden" name="linea" value="<?php echo $row->id_familia ?>" />
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

<!-- Modal Editar familia -->
<div class="modal " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar Famila</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal familia">
              <fieldset>


                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="id_familia">Código familia</label>  
                      <div class="col-md-4">
                          <label style="text-align: left; padding-left: 0px;" class="col-md-4 control-label" id="label_id_familia" ></label> 
                          <input id="linea" type="hidden" name="linea" value="" />
                          <input style="text-align: left" id="id_familia" name="id_familia" type="hidden"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="familia">Nombre Familia</label>  
                      <div class="col-md-6">
                          <input id="familia" name="familia" type="text" placeholder="Nombre Familia" class="form-control input-md" required=""  value="jjgjgjgkj">

                      </div>
                  </div>

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

<!-- Modal Nuevo familia -->
<div class="modal " id="myModalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nueva Familia</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal familiaNuevo">
              <fieldset>
                  
                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="id_familia">Código Familia</label>  
                      <div class="col-md-4">
                          <input id="linea" type="hidden" name="linea" value="" />
                          <input style="text-align: left" id="id_familiaNuevo" name="id_familia" type="text"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="familia">Nombre Familia</label>  
                      <div class="col-md-6">
                          <input id="familiaNuevo" name="familia" type="text" placeholder="Nombre Familia" class="form-control input-md" required="required"  value="">

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

<!-- Modal Eliminar familia -->
<div class="modal " id="myModalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Familia</h4>
      </div>
      <div class="modal-body">
          
          <form class="form-horizontal familiaEliminar">
              <fieldset>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="id_codigo">Código familia</label>  
                      <div class="col-md-6">
                          <label  id="label_id_familiaEliminar"></label>
                         <input style="text-align: left" id="id_familiaEliminar" name="id_familiaEliminar" type="hidden"  class="form-control "  >

                      </div>
                  </div>

                  <!-- Text input-->
                  <div class="form-group">
                      <label class="col-md-4 control-label titulo" for="nombre">Nombre familia </label>  
                      <div class="col-md-6">
                          <label  id="familiaEliminar"></label>
                      </div>
                  </div>

                  <!-- Button (Double) -->
                  <div class="form-group">
                      <label class="col-md-4 control-label" for="guardar"></label>
                      <div class="col-md-8">
                        <button id="guardarEliminar" name="guardar" class="btn btn-danger">Eliminar familia</button>
                        <button type="button" class="btn btn-default " data-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                      </div>
                  </div>

              </fieldset>
          </form>
      </div>
       
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuListados').addClass('btn-primary');
  $('#menuListaFamilias').addClass('btn-primary');    
})
</script>

<script>
$(document).ready(function () {
    
    $("button#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/familia", //process para grabar
            data: $('form.familia').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                var linea=obj.linea;
                var familia=obj.familia;
                //alert(linea+' '+familia);
                $('#myModal').modal('hide'); 
              $('input.linea[value='+linea+']').parent().prev().html(familia);
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
            url: "<?php echo base_url() ?>"+"index.php/process/familiaNuevo", //process para grabar
            data: $('form.familiaNuevo').serialize(),
            success: function(datos){
                //alert(datos);
                var obj = $.parseJSON(datos);
                if (obj.salida==false){
                    $('.modalError').text("Familia con código y/o nombre YA existente o falta algún campo");
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
        //alert('eliminar');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/process/familiaEliminar", //process para grabar
            data: $('form.familiaEliminar').serialize(),
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
           
            
