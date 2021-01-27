<h3>Pedidos a Proveedores</h3>
<?php 
$optionsProveedores=$proveedores['options'];
//$optionsProductos=$productos['options'];
$optionsPedidos=array();

  ?>      
    
    <div class="box box-primary col-lg-12">  
        <div class="container">
            <h4>Proveedor </h4>
            <div class="row">
                
                <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro proveedores </label>
            <div class="input-group">
                <input type="text" id="buscarProveedores" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscarProveedor" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
          
       
          <div class="col-sm-3">
            <label for="proveedor" class="col-sm-12 form-control-label">Proveedor: </label>
            <?php echo form_dropdown('proveedor', $optionsProveedores, '', array('width'=>'100%','id' => 'proveedor', 'class' => ' form-control input-sm ')); ?>
         
          </div>
                
           <div class="col-sm-2">     
            <button type="button" class="btn btn-primary btn-sm" id="searchPedidos" >Buscar Pedidos </button>
            <button type="button" class="btn btn-default btn-sm" id="addPedido" >Nuevo pedido</button>       
           </div>
                <!--
                <div class="box-body col-lg-4"> 
                    Buscar: <input type="text" id="buscarProveedores" class="form-control input-sm" value="" >
                </div> 
                 -->   
            </div>
        </div>
        <!--
           <div class="container">
              <div class="row">  
                <input type="hidden" value="0" id="codigoProveedor" >
                
                <div class="box-body_ col-lg-4"> 
                    <?php echo form_dropdown('proveedor', $optionsProveedores, '', array('id' => 'proveedor', 'class' => 'form-control input-sm')); ?>
                </div>
                    <button type="button" class="btn btn-primary btn-sm" id="searchPedidos" >Buscar Pedidos </button>
                    <button type="button" class="btn btn-default btn-sm" id="addPedido" >Nuevo pedido</button>
                </div> 
               <hr>
            </div>
        -->
        </div>

        <!-- selector pedidos -->
        <div class="container hide" id="selectorPedidos">
            <div class="row">
                <div class="box-body col-lg-6"> 
                    <h4>Pedido</h4>
                </div>
            </div>
            <div class="row"> 
                <div class="box-body col-lg-5" >
                    
                    <?php echo form_dropdown('pedido', $optionsPedidos, '', array('id' => 'pedido', 'class' => 'form-control input-sm')); ?>
                </div>
                <div class="box-body col-lg-2"> 
                    <button type="button" class="btn btn-info btn-sm" id="readPedido" >Ver </button>
                    <button type="button" class="btn btn-primary btn-sm" id="updatePedido" >Modificar </button>
                    <button type="button" class="btn btn-danger btn-sm" id="deletePedido" >Eliminar </button>
                </div>    
                <input type="hidden" value="0" id="codigoProveedor" >
            </div>
             
        </div>
        <br><br>
        <hr>

     <div class="hide" id="nuevoPedido">
      <div class="form-group row " >
          <br><br> <label  class="col-sm-12 form-control-label">Introducir linea producto</label>
      </div>
      <div class="row">
          <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro productos </label>
            <div class="input-group">
                <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscar" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
         
        <div class="col-sm-4">
            <label for="producto" class="col-sm-12 form-control-label">Producto</label>
                    <?php echo form_dropdown('producto', $optionsProductos, '', array('width'=>'100%','id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>
      <div class="col-sm-2">
            <label for="cantidad" class="col-sm-12 form-control-label">Cantidad</label>
            <input type="text" name="cantidad" id="cantidad" class="input-sm form-control" placeholder="cantidad">
        </div>
        <div class="col-sm-1c">
              <label for="" class="col-sm-12 form-control-label">Und</label>
              <p for="" class="col-sm-12 form-control-label_"  id="tipoUnidad"></p>
 
          </div>  
          
      
      <div class="col-sm-1" id="addLinea">
            <label  class="col-sm-12 form-control-label"> </label>
            <a href="#" class="" id="anadir" >Añadir </a>

        </div>
      </div>
                <hr>     
 <div class="row">
        <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Código </label>
        </div>
        <div class="col-sm-3">
            <label for="ventaA" class="col-sm-12 form-control-label">Producto</label>
        </div>
      <div class="col-sm-2 derecha aj">
            <label for="ventaA" class="col-sm-12 form-control-label derecha aj">Cantidad</label>
        </div>
     <div class="col-sm-1 derecha aj und">
            <label for="ventaA" class="col-sm-12 form-control-label derecha aj ">Und</label>
        </div>
      
     
      <div class="col-sm-1" id="addLinea">
            <p for="ventaA" class="col-sm-12 form-control-label"> </p>
        </div>
 </div>

<div id="lineasProductos">
    

</div>
<br>
<div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10">
      <button type="submit" class="btn btn-default" id="registrarAlbaran">Registrar Pedido Proveedor</button>
      <button type="submit" class="btn btn-default" id="cancelarAlbaran">Cancelar</button>
    </div>
  </div>
</div>
    
    

    <!-- formulario Nuevo pedido -->
    <div class="container">
    <div class="hide" id="nuevoPedido_"><h4>Nuevo Pedido</h4>
                        <div class="box-body col-lg-3"> 
                            
                            <div class="form-group">  
                                <h4>Núm Pedido </h4>
                                <input class="form-control input-sm" type="number" value="<?php echo $siguiente ?>" name="numPedido" id="numPedido" disabled>
                            </div>  
                        </div> 
                        <div class="box-body col-lg-3">  
                            <div class="form-group">  
                                <h4>Fecha </h4>
                                <input class="form-control input-sm" type="date" value="<?php echo $hoy ?>" name="fechaPedido" id="fechaPedido">
                            </div>  
                        </div> 
                    
        <br/>  
        <div class="box box-primary col-lg-12"> 
                    <h4>Preparación línea </h4>
        </div>
        
                    <table class="table table-bordered_ table-hover table-striped">
                        
                        <thead>  
                            <th class="col-md-1">Nu</th>  
                            <th class="col-md-1">Producto</th>  
                            <th class="col-md-1">Cantidad</th> 
                            <th class="col-md-1">Und/Kg</th> 
                            <th class="col-md-1">Precio</th>  
                            <th class="col-md-1">Descuento</th>  
                            <th class="col-md-1">Precio Anterior</th>  
                            <th class="col-md-1">Descuento Anterior</th>  
                            <th class="col-md-1">Total</th>  
                            <th><!--<input type="button" value="+" id="add" class="btnbtn-primary">--></th>  
                        </thead>  
                        <tbody class="detailNueva"> 
                            <tr>  
                                <td class="no"></td>  
                                <td >
                                    <div class="row">
          <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro productos </label>
            <div class="input-group">
                <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscar" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
         
        <div class="col-sm-4">
            <label for="producto" class="col-sm-12 form-control-label">Producto</label>
                    <?php echo form_dropdown('producto', $optionsProductos, '', array('width'=>'100%','id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>
                                    </div>
      <div class="col-sm-2">
            <label for="cantidad" class="col-sm-12 form-control-label">Cantidad</label>
            <input type="text" name="cantidad" id="cantidad" class="input-sm form-control" placeholder="cantidad">
        </div>
        <div class="col-sm-1c">
              <label for="" class="col-sm-12 form-control-label">Und</label>
              <p for="" class="col-sm-12 form-control-label_"  id="tipoUnidad"></p>
 
          </div>  
          
      
      <div class="col-sm-1" id="addLinea">
            <label  class="col-sm-12 form-control-label"> </label>
            <a href="#" class="" id="anadir" >Añadir </a>

        </div>
      </div>
                                </td>
                                <td class="separacion  hide" id="und_caja">(x und/caja)</td>   
                                <td class="separacion"></td>   
                                <td class="separacion"></td>   
                                <td class="separacion"></td>    
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                            </tr> 
                            <tr>  
                                <td class="no">--</td>  
                                <td ><?php echo form_dropdown('pedido', $optionsProductos,'',array('class' => 'form-control input-sm','id'=>'producto')); ?></td>  
                                <td ><input type="text" class="form-control input-sm quantity" name="cantidad" id="cantidad"></td>  
                                <td ><?php echo form_dropdown('tipoUnidad', array('Und'=>'Und','Kg'=>'Kg'),'',array('class' => 'form-control input-sm','id'=>'tiposUnidades')); ?></td>  
                                <td ><input type="text" class="form-control input-sm price" name="precio" id="precio"></td>  
                                <td ><input type="text" class="form-control input-sm discount" name="descuento" id="descuento"></td> 
                                <td  id="precioAnterior"></td>  
                                <td  id="descuentoAnterior"></td> 
                                <td ><input type="text" class="form-control input-sm total" name="total" id="total"></td> 
                                <td ><a href="#" class="addLinea">Añadir</a></td>  
                            </tr> 
                            
                            <tr>  
                                <td class="separacion" style="color:white;">-</td> 
                                
                                <td class="separacion"></td> 
                                <td class="separacion"></td>   
                                <td class="separacion"></td>   
                                <td class="separacion"></td>   
                                <td class="separacion"></td>    
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                            </tr> 
                            <tr>
                                <td class="separacionTitulo" colspan="10"><h4>Lineas pedido</h4></td>
                            </tr>
</tbody>  

  
</table>  
        <div class="container">
            <div class="row">
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 >Otros costes</h4>
            </div>  
        </div> 
        <div class="box-body col-lg-2">  
            <div class="form-group">  
               <input class="form-control input-sm" type="text" value="" name="otrosCostes" id="otrosCostes">

            </div>  
        </div> 
            </div>
        </div>
        <div class="container">
            <div class="row">
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 >Total pedido</h4>
            </div>  
        </div> 
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 id="totalPedido">0.00</h4>
            </div>  
        </div> 
            </div>
        </div>
        
        <div class="container">
                <button type="submit" class="btn btn-success" id="registrarPedido" >Registrar pedido</button>
        </div>
        
        </div>
        <br />
    </div>


<!-- ver pedido -->
<div class="container">
      <div id="datosPedido" class="hide"><h4>Datos Pedido</h4>
            <div class="col-lg-4">
                <br />  
            <table class="table table-bordered_ table-hover_ ">
                <tbody>
                    <tr >
                        <th class="izda">Pedido núm:</th>
                        <td class="izda" id="readNumPedido">Sin número pedido
                    </tr>
                    <tr>
                        <th class="izda">Proveedor:</th>
                        <td class="izda" id="readNombre">
                    </tr>
                    <tr>
                        <th class="izda">Fecha:</th>
                        <td class="izda" id="readFecha">Sin fecha de pedido
                    </tr>
                </tbody>
            </table>
                </div>
           
            
            <table class="table table-bordered_ table-hover table-striped">
                        
                        <thead>  
                            <th>No</th>  
                            <th class="izda">Producto</th>  
                            <th>Cantidad</th> 
                            <th>Und/Kg</th>
                            <th>Precio</th>  
                            <th>Descuento</th>  
                            <th>Total</th>  
                        </thead>  
                        <tbody class="detailVer">  
                      
</tbody>  

  
</table>  
            <div class="col-lg-4">
                <br />  
            <table class="table table-bordered_ table-hover ">
                <tbody>
                    <tr >
                        <th class="izda">Otros costes:</th>
                        <td class="izda_" id="readOtrosCostes"></td>
                    </tr>
                    <tr>
                        <th class="izda">Total Pedido:</th>
                        <td class="izda_" ><span ><h4 id="readTotalPedido"></h4></span></td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
        </div>
</div>   


<!-- Modificar Pedido -->
<div class="container">
    <!-- formulario Modificacion pedido -->
    <div class="hide" id="modificacionPedido"><h4>Editar Pedido Núm <spam id="editNumPedido"></spam></h4>
                        
    <div class="box-body col-lg-3"> 
        <div class="row">
        <div class="form-group_"> 
            <label for="fechaPedido" class="col-lg-2 control-label">Fecha</label>
            <input class="form-control input-sm" type="date" value="" name="fechaPedido" id="editFechaPedido">
        </div> 
        </div>
    </div> 
                    
       
        <div class="box box-primary col-lg-12"> 
                    <h4>Preparación línea modificacion</h4>
        </div>
        
                    <table class="table table-bordered table-hover table-striped">
                        
                        <thead>  
                            <th>No</th> 
                            <th>Producto</th>  
                            <th>Cantidad</th>  
                            <th>Und/Kg</th>  
                            <th>Precio</th>  
                            <th>Descuento</th>  
                            <th>Precio Anterior</th>  
                            <th>Descuento Anterior</th>  
                            <th>Total</th>  
                            <th><!--<input type="button" value="+" id="add" class="btnbtn-primary">--></th>  
                        </thead>  
                        <tbody class="detailModificacion"> 
                            <tr>  
                                <td class="no"></td>  
                                <td >Buscar: <input type="text" class="form-control input-sm" id="buscarEditProductos" value=""  ></td>
                                <td class="separacion  hide" id="editUnd_caja">(x und/caja)</td>     
                                <td class="separacion"></td> 
                                  
                                <td class="separacion"></td>   
                                <td class="separacion"></td>    
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                            </tr> 
                            
                            
                            
                            <tr>  
                                <td class="no">--</td> 
                                <td><?php echo form_dropdown('pedido', $optionsProductos,'',array('class' => 'form-control input-sm','id'=>'editProducto')); ?></td>  
                                <td><input type="text" class="form-control input-sm quantity" name="cantidad" id="editCantidad"></td>  
                                <td><?php echo form_dropdown('tipoUndad', array('Und'=>'Und','Kg'=>'Kg'),'',array('class' => 'form-control input-sm','id'=>'editTiposUnidades')); ?></td>  
                                <td><input type="text" class="form-control input-sm price" name="precio" id="editPrecio"></td>  
                                <td><input type="text" class="form-control input-sm discount" name="descuento" id="editDescuento"></td> 
                                <td id="editPrecioAnterior"></td>  
                                <td id="editDescuentoAnterior"></td> 
                                <td><input type="text" class="form-control input-sm total" name="total" id="editTotal"></td> 
                                <td><a href="#" class="addLinea">Añadir</a></td>  
                            </tr> 
                            
                            <tr>  
                                <td class="separacion" style="color:white;">-</td>  
                                <td class="separacion"></td> 
                                <td class="separacion"></td>   
                                <td class="separacion"></td>   
                                <td class="separacion"></td>   
                                <td class="separacion"></td>    
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                                <td class="separacion"></td> 
                            </tr> 
                            <tr>
                                <td class="separacionTitulo" colspan="10"><h4>Lineas pedido</h4></td>
                            </tr>
</tbody>  

  
</table>  
        
        
        <div class="container">
            <div class="row">
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 >Otros costes</h4>
            </div>  
        </div> 
        <div class="box-body col-lg-2">  
            <div class="form-group">  
               <input class="form-control input-sm" type="text" value="" name="otrosCostes" id="editOtrosCostes">

            </div>  
        </div> 
            </div>
        </div>
        <div class="container">
            <div class="row">
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 >Total pedido</h4>
            </div>  
        </div> 
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 id="editTotalPedido">0.00</h4>
            </div>  
        </div> 
            </div>
        </div>
        
        <div class="container">
           <div class="container">
                <button type="submit" class="btn btn-success" id="registrarModificacionPedido" >Modificar pedido</button>
           </div>
        </div>
        
        </div>
        <br />
    </div>





<script>
$(document).ready(function () {
    
    var paginaRedirigir=""
    var typeTrigger=""
    
    
     $('input.searchable-input').keyup(function(){
    if($(this).val()){
        $(this).css('border-color','#444')
        $(this).css('border-style','dashed')
        $(this).css('color','red')
    }
    else{
        $(this).css('border','1px solid #ccc')  
        $(this).css('color','black')
    }
    }) 
    
     // CLEARABLE INPUT
        function tog(v){return v?'addClass':'removeClass';} 
        var nombreId
        
        $(document).on('input', '.clearable', function(){
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){
            nombreId=$(this).attr('id')
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc')  
            if(nombreId=='buscarProveedores')
                filtroProveedores(" ",'proveedor')
            if(nombreId=='buscarProductos')
                filtroProductos(" ",'producto')
            
        });
/*
    $('#buscarProveedor').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarProveedores').val()
        filtroProveedores(filtro,'proveedor')
        $('#buscarProveedores').css('color','black')
    })
    
    //filtrado productosFinales 
    $('#buscarProveedores').click(function(){
        var filtro=$('#buscarProveedores').val()
        filtroProveedores(filtro,'proveedor')
        $('#buscarProveedores').css('color','black')
    //filtroProductos("",'producto')
    })
    
    function filtroProveedores(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getProveedoresFiltrados", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             $('select#'+id+' option').remove();
             $.each(datos['options'], function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#'+id).append(option)
             })
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    */
    
    
    
    $('#editNumPedido, #editFechaPedido, #editOtrosCostes').change(function(){
        $('#editPedidoExcel').addClass('hide')
    })
    $('#numPedido, #fechaPedido, #otrosCostes').change(function(){
        $('#pedidoExcel').addClass('hide')
    })
    
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    $('#numPedido, #fechaPedido, #otrosCostes, #producto, #cantidad, #precio, #descuento').change(function(){
        cambios=true
    })
    $('#editNumPedido, #editFechaPedido, #editOtrosCostes, #editProducto, #editCantidad, #editPrecio, #editDescuento').change(function(){
        cambios=true
    })
    
    
    window.onbeforeunload=confirmExit
    function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han guardado.'
        }
    }
           
    
    $('#addPedido').click(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        var proveedor=$('#proveedor').val()
        if(proveedor==0){
            
            $('.modal-title').html('Información importante')
            $('.modal-body>p').html("No ha seleccionado el proveedor.\nSe debe de seleccionar antes de introducir la información del pedido")
            $("#myModal").modal()  
            return false
        }
        $('input#buscarProveedores').val('')
        $('.infoLineas').remove()
        $('.editInfoLineas').remove()
        $('#selectorPedidos').addClass('hide')
        $('#datosPedido').addClass('hide')
        $('#nuevoPedido').removeClass('hide')
        $('#modificacionPedido').addClass('hide')
        
    })
    
    $('#updatePedido').click(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        $('.infoLineas').remove()
         $('.editInfoLineas').remove()
        $('#datosPedido').addClass('hide')
        $('#nuevoPedido').addClass('hide')
        $('#modificacionPedido').removeClass('hide')
        
    })
    
    $('#readPedido').click(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        
        $('.infoLineas').remove()
        $('.editInfoLineas').remove()
        $('#datosPedido').removeClass('hide')
        $('#nuevoPedido').addClass('hide')
        $('#modificacionPedido').addClass('hide')
        
    })
    
    $('select#proveedor').change(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        $('#selectorPedidos').addClass('hide')
        $('#nuevoPedido').addClass('hide')
        $('#datosPedido').addClass('hide')
        $('#modificacionPedido').addClass('hide')
    })
    
    $('select#pedido').change(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        $('#nuevoPedido').addClass('hide')
        $('#datosPedido').addClass('hide')
        $('#modificacionPedido').addClass('hide')
    })
    
    
    $('#searchPedidos').click(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
       $('#nuevoPedido').addClass('hide')
       $('#datosPedido').addClass('hide')
       $('#modificacionPedido').addClass('hide')
       
        var proveedor=$('select#proveedor').val()
        if(proveedor==0){
            alerta('Información','Se debe seleccionar un proveedor.')
            return false;
        }
        
         $('select#pedido option').remove()
         
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getPedidos",
            data: {proveedor:proveedor},
            success: function(datos){
                var resultado=$.parseJSON(datos)
                //alert(resultado)
               // var op='<option  value="0">Seleccionar un pedido</option>'
                // $('select#pedido').append(op)
                $.each(resultado, function(index, value) {
                    op='<option  value="'+index+'">'+value+'</option>'
                    $('select#pedido').append(op)
                }); 
                $('#selectorPedidos').removeClass('hide')
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas pedidos. Informar");
            }
        })
    })
    
    $('#registrarModificacionPedido').click(function(e){
        e.preventDefault()

        var proveedor=$('select#proveedor').val()
        if(proveedor==0){
            alerta('Información','Seleccionar provevedor.')
            return false;
        }
        
        var idPedido=$('select#pedido').val()
        if(idPedido==0){
            alerta('Información','Seleccionar un pedido.')
            return false;
        }
        
        var editNumPedido=$('#editNumPedido').html()
        var editFechaPedido=$('input#editFechaPedido').val()
        
        var editOtrosCostes=$('#editOtrosCostes').val()
        var editTotalPedido=$('#editTotalPedido').html()
        
        var codigo_producto=[]
        $('.editCodigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).val()
        })
        
        var cantidades=[]
        $('.editCantidades').each(function(i,e)  {
            cantidades[i]=$(this).html()-0
        })
        
       var tiposUnidades=[]
        $('.editTiposUnidades').each(function(i,e)  {
            tiposUnidades[i]=$(this).html()
        })
        var precios=[]
        $('.editPrecios').each(function(i,e)  {
            precios[i]=$(this).html()-0
        })
        var descuentos=[]
        $('.editDescuentos').each(function(i,e)  {
            descuentos[i]=$(this).html()-0
        })
        
        var totales=[]
        $('.editTotales').each(function(i,e)  {
            totales[i]=$(this).html()-0
        })
        
        var lineas={}
        for (var i = 0; i < totales.length; ++i){
            lineas[i]={"codigo_producto":codigo_producto[i],
                        "cantidades":cantidades[i],
                        "tiposUnidades":tiposUnidades[i],
                        "precios":precios[i], 
                        "descuentos":descuentos[i],
                        "totales":totales[i], 
                       }
        }
        var pedido={}
       // alert('editNumPedido '+editNumPedido)
        
        pedido={   "idPedido":idPedido,
                    "proveedor":proveedor,
                    "numPedido":editNumPedido,
                    "fechaPedido":editFechaPedido,
                    "totalPedido":editTotalPedido ,
                    "otrosCostes":editOtrosCostes ,
                    "lineas":lineas
                }
        
        
        $('#editPedidoExcel').val('')
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/updatePedidoCompleta",
            data: pedido,
            success: function(datos){
               // alert('datos '+datos)
               var datos=$.parseJSON(datos)
               var direccion="<?php echo base_url() ?>pedidos/"+datos
              // alert(direccion)
                window.open(direccion)
                
                 
               
            $('#myModal').on('hidden.bs.modal', function () {
                          window.location.href = "<?php echo base_url() ?>" + "index.php/compras/pedidoProveedor";
            })    
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Registrada modificación Pedido proveedor correctamente.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            
            
            
             
                 //       $('#myModal').modal()
                        
            $('#editPedidoExcel').val('Pedido '+editNumPedido+'.xls')
            $('#editPedidoExcel').removeClass('hide')
            cambios=false
            //alert('Pedido proveedor registrada correctamente.')
            //document.location.reload(true);
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas pedidos. Informar");
            }
        })
    
    })


    $('#registrarPedido').click(function(e){
        e.preventDefault()

        var proveedor=$('select#proveedor').val()
        if(proveedor==0){
            alerta('Información','Seleccionar provevedor.')
            return false;
        }
        
        var numPedido=$('input#numPedido').val()
        var fechaPedido=$('input#fechaPedido').val()
        
        var otrosCostes=$('#otrosCostes').val()
        var totalPedido=$('#totalPedido').html()
       
        var codigo_producto=[]
        $('.codigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).val()
        })
        
        var cantidades=[]
        $('.cantidades').each(function(i,e)  {
            cantidades[i]=$(this).html()-0
        })
        
        var tiposUnidades=[]
        $('.tiposUnidades').each(function(i,e)  {
            tiposUnidades[i]=$(this).html() 
        })
        
        
        
        var precios=[]
        $('.precios').each(function(i,e)  {
            precios[i]=$(this).html()-0
        })
        var descuentos=[]
        $('.descuentos').each(function(i,e)  {
            descuentos[i]=$(this).html()-0
        })
        
        var totales=[]
        $('.totales').each(function(i,e)  {
            totales[i]=$(this).html()-0
        })
        
        var lineas={}
        for (var i = 0; i < totales.length; ++i){
            lineas[i]={"codigo_producto":codigo_producto[i],
                        "cantidades":cantidades[i],
                        "tiposUnidades":tiposUnidades[i],
                        "precios":precios[i], 
                        "descuentos":descuentos[i],
                        "totales":totales[i], 
                       }
        }
        var pedido={}
        pedido={
                    "proveedor":proveedor,
                    "numPedido":numPedido,
                    "fechaPedido":fechaPedido,
                    "totalPedido":totalPedido ,
                    "otrosCostes":otrosCostes ,
                    "lineas":lineas
                }
        
        $('#pedidoExcel').val('')
        
        //alert('pedido.proveedor '+pedido.proveedor)
        //alert('pedido.lineas[1].totales '+pedido.lineas[1].totales)
        
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarPedidoCompleta",
            data: pedido,
            success: function(datos){
                //alert('datos '+datos)
               var datos=$.parseJSON(datos)
               cambios=false
                var direccion="<?php echo base_url() ?>pedidos/"+datos
                window.open(direccion)
                
                $('#myModal').on('hidden.bs.modal', function () {
                   window.location.href = "<?php echo base_url() ?>" + "index.php/compras/pedidoProveedor";
                })    
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Registrado Pedido proveedor y bajado correctamente.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas pedidos. Informar");
            }
        })
    })
    
    
    
    /* funciones utilizados en nuevoPedido */  
    $('.addLinea').click(function(e){  
        e.preventDefault()
        $('#editPedidoExcel').addClass('hide')
        $('#pedidoExcel').addClass('hide')
        addnewrow();  
    }); 
    
    var nombreProducto="Sin código"

    $('#precio').blur(function(){
        $('#total').attr('disabled','disabled')
    })
    $('#descuento').blur(function(){
        $('#total').attr('disabled','disabled')
    })
    $('#total').blur(function(){
        $('#precio').attr('disabled','disabled')
        $('#descuento').attr('disabled','disabled')
    })
    
    $('#producto').change(function(){
       var id_pe_producto=$(this).val()
       if (id_pe_producto==0) return false;
       $.ajax({
            url: "<?php echo base_url() ?>"+"index.php/productos/getUnidad/"+id_pe_producto,
            success: function(datos){
                alert(datos)
               var datos=$.parseJSON(datos)
               $('#tipoUnidad').html(datos)
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado Albarán. Informar");
            }
        })    
   })
    
    $('#producto').change(function(){
    var proveedor=$('#proveedor').val()
    var producto=$('#producto').val()
    
    alert(proveedor)
    alert(producto)
    $.ajax({
            url: "<?php echo base_url() ?>"+"index.php/productos/getUnidad/"+id_pe_producto,
            success: function(datos){
                alert(datos)
               var datos=$.parseJSON(datos)
               $('#tipoUnidad').html(datos)
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado Albarán. Informar");
            }
        })    
    
    
    
    
    
    
    })
    
    
    $('#producto').blur(function(){
        
        
        var proveedor=$('#proveedor').val()
        if(proveedor==0){
            $('.modal-title').html('Información importante')
            $('.modal-body>p').html("No ha seleccionado el proveedor.\nSe debe de seleccionar antes de introducir la información del pedido")
            $("#myModal").modal()  

            $(this).val('')
            $('#proveedor').focus()
            return false
        }
        $('#total').removeAttr('disabled')
        $('#precio').removeAttr('disabled')
        $('#descuento').removeAttr('disabled')
        
        var producto=$('#producto').val()
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getPrecioCompra", 
            data: {producto:producto, proveedor:proveedor},
            success: function(datos){
                //alert(datos)
                precio=$.parseJSON(datos);
                precio_ultimo=(parseFloat(precio['precio_ultimo'])/1000).toFixed(2)
                descuento=(parseFloat(precio['descuento'])/1000).toFixed(2)
                $('#precioAnterior').html(precio_ultimo)
                $('#descuentoAnterior').html(descuento)
                $('#precio').val(precio_ultimo)
                $('#descuento').val(descuento)
                $und_caja=(parseFloat(precio['und_caja'])/1000).toFixed(2)
                $('#und_caja').html($und_caja+ ' u/c')
                $('#und_caja').removeClass('hide')
                },
            error: function(){
                alertaError("Información importante","Error en el proceso. Informar");
            }
        });
    })
    
     $('#editProducto').blur(function(){
        
        var proveedor=$('#proveedor').val()
        if(proveedor==0){
            $('.modal-title').html('Información importante')
            $('.modal-body>p').html("No ha seleccionado el proveedor.\nSe debe de seleccionar antes de introducir la información del pedido")
            $("#myModal").modal()  

            $(this).val('')
            $('#proveedor').focus()
            return false
        }
        $('#editTotal').removeAttr('disabled')
        $('#editPrecio').removeAttr('disabled')
        $('#editDescuento').removeAttr('disabled')
        
        var producto=$('#editProducto').val()
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getPrecioCompra", 
            data: {producto:producto, proveedor:proveedor},
            success: function(datos){
                //alert(datos)
                precio=$.parseJSON(datos);
                precio_ultimo=(parseFloat(precio['precio_ultimo'])/1000).toFixed(2)
                descuento=(parseFloat(precio['descuento'])/1000).toFixed(2)
                $('#editPrecioAnterior').html(precio_ultimo)
                $('#editDescuentoAnterior').html(descuento)
                $('#editPrecio').val(precio_ultimo)
                $('#editDescuento').val(descuento)
                $und_caja=(parseFloat(precio['und_caja'])/1000).toFixed(2)
                $('#editUnd_caja').html($und_caja+ ' u/c')
                $('#editUnd_caja').removeClass('hide')
                },
            error: function(){
                alertaError("Información importante","Error en el proceso. Informar");
            }
        });
    })

    $('body').delegate('#cantidad,#precio,#descuento','keyup',function()  
    {  
    var tr=$(this).parent().parent();  
    var qty=tr.find('#cantidad').val();  
    var price=tr.find('#precio').val();  

    var dis=tr.find('#descuento').val();  
    var amt =(qty * price)-(qty * price *dis)/100;  
    tr.find('#total').val(amt.toFixed(2));  

    }); 
    
    $('body').delegate('#editCantidad,#editPrecio,#editDescuento','keyup',function()  
    {  
    var tr=$(this).parent().parent();  
    var qty=tr.find('#editCantidad').val();  
    var price=tr.find('#editPrecio').val();  

    var dis=tr.find('#editDescuento').val();  
    var amt =(qty * price)-(qty * price *dis)/100;  
    tr.find('#editTotal').val(amt.toFixed(2));  

    });
    
    
 
     $('#otrosCostes').change(function(){
         totalPedido()
     })
     
     $('#editOtrosCostes').change(function(){
         totalPedido()
     })

    function addnewrow()   
        { 
        $('input#buscarProductos').val('')
        $('input#buscarEditProductos').val('')
        $('#und_caja').addClass('hide')
        $('#editUnd_caja').addClass('hide')
        buscarProductos('')
        buscarEditProductos('')
        var cantidad=$('#cantidad').val()-0
        var tipoUnidad=$('#tiposUnidades').val()
        var editCantidad=$('#editCantidad').val()-0
        var editTipoUnidad=$('#editTiposUnidades').val()
        
        if(cantidad==0 && cantidad=="" && editCantidad==0 && editCantidad==""){
            alerta('Información importante','Falta introducir la cantidad antes de añalir la línea.')
            return false;
        }
        cantidad=cantidad.toFixed(3)
        editCantidad=editCantidad.toFixed(3)
        var n=($('.infoLineas').length-0)+1;
        
        
        var precio=(($('#precio').val())*1).toFixed(2)
        var descuento=(($('#descuento').val())*1).toFixed(2)
        var precioAnterior=(($('#precioAnterior').html())*1).toFixed(2)
        var descuentoAnterior=(($('#descuentoAnterior').html())*1).toFixed(2)
        var total=(($('#total').val())*1).toFixed(2)
        var codigo_producto=$('#producto').val()
        var producto=$('#producto option[value="'+codigo_producto+'"]').html()      //nombreProducto
        
        var editPrecio=(($('#editPrecio').val())*1).toFixed(2)
        var editDescuento=(($('#editDescuento').val())*1).toFixed(2)
        var editPrecioAnterior=(($('#editPrecioAnterior').html())*1).toFixed(2)
        var editDescuentoAnterior=(($('#editDescuentoAnterior').html())*1).toFixed(2)
        var editTotal=(($('#editTotal').val())*1).toFixed(2)
        var editCodigo_producto=$('#editProducto').val()
        var editProducto=$('#editProducto option[value="'+editCodigo_producto+'"]').html()      //nombreProducto
        
        //alert('codigo_producto '+codigo_producto)
        
        
        
        var tr = '<tr class="infoLineas">'+  
        '<td class="no">'+n+'</td>'+  
     
        '<td class="izda">'+producto+'<input type="hidden" class="codigo_producto" value="'+codigo_producto+'"></td>'+  
        '<td class="cantidades">'+cantidad+'</td>'+
        '<td class="tiposUnidades">'+tipoUnidad+'</td>'+
        '<td class="precios">'+precio+'</td>'+
        '<td class="descuentos">'+descuento+'</td>'+  
        '<td >'+precioAnterior+'</td>'+
        '<td >'+descuentoAnterior+'</td>'+  
        '<td class="totales">'+total+'</td>'+   
        '<td><a href="#" class="remove">Eliminar</td>'+  
        '</tr>';  
        $('.detailNueva').append(tr); 
        
        var tr = '<tr class="editInfoLineas">'+  
        '<td class="no">'+n+'</td>'+ 
       
        '<td class="izda">'+editProducto+'<input type="hidden" class="editCodigo_producto" value="'+editCodigo_producto+'"></td>'+  
        '<td class="editCantidades">'+editCantidad+'</td>'+
        '<td class="editTiposUnidades">'+editTipoUnidad+'</td>'+
        '<td class="editPrecios">'+editPrecio+'</td>'+
        '<td class="editDescuentos">'+editDescuento+'</td>'+  
        '<td >'+editPrecioAnterior+'</td>'+
        '<td >'+editDescuentoAnterior+'</td>'+  
        '<td class="editTotales">'+editTotal+'</td>'+   
        '<td><a href="#" class="remove">Eliminar</td>'+  
        '</tr>';  
        $('.detailModificacion').append(tr); 
        
        $('#cantidad').val('')
        $('#tiposUnidades').val('Und')
        $('#precio').val('')
        $('#descuento').val('')
        $('#precioAnterior').html('')
        $('#descuentoAnterior').html('')
        $('#total').val('')
        $('#producto option[value="0"]').attr('selected','selected')
        
        $('#editCantidad').val('')
        $('#editTiposUnidades').val('Und')
        $('#editPrecio').val('')
        $('#editDescuento').val('')
        $('#editPrecioAnterior').html('')
        $('#editDescuentoAnterior').html('')
        $('#editTotal').val('')
        $('#editProducto option[value="0"]').attr('selected','selected')
       
    nombreProducto="Sin código"
        
        totalPedido()
        }
        
        $('body').delegate('.remove','click',function()  
        {  
            $(this).parent().parent().remove(); 
            $('#pedidoExcel').addClass('hide')
            totalPedido()
        }); 
        
        $('body').delegate('.editRemove','click',function()  
        {  
            $(this).parent().parent().remove(); 
            $('#editPedidoExcel').addClass('hide')
            totalPedido()
        }); 
    
    function totalPedido()  
        {  
            var t=0; 
            var editT=0;
            $('.totales').each(function(i,e)   
            {  
                
            var amt =$(this).html()-0;  
            t+=amt;  
            });  
            
            $('.editTotales').each(function(i,e)   
            {  
                
            var amt =$(this).html()-0;  
            editT+=amt;  
            });  
            
            var otrosCostes=$('#otrosCostes').val()-0
            var editOtrosCostes=$('#editOtrosCostes').val()-0
            t=t+otrosCostes
            editT=editT+editOtrosCostes
            
            $('#totalPedido').html(t.toFixed(2)); 
            $('#editTotalPedido').html(editT.toFixed(2)); 
        }  
        
    /* fin funciones utilizados en nuevoPedido */    
    
    
    
     $('#readPedido').click(function(){
        $('#selectorPedidos').removeClass('hide')
       // $('#nuevoPedido').addClass('hide')
        
        var id_pedido=$('#pedido').val()
        if(id_pedido==0) { alerta('Información','Seleccione un pedido') }
        else{
           //alerta('Información','Pedido seleccionada '+id_pedido)
          // $('#datosPedido').removeClass('hide')
           $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosPedido", 
            data: {id_pedido:id_pedido },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                
                $('#readNumPedido').html(datos['numPedido'])
                $('#readNombre').html(datos['nombre'])
                $('#readFecha').html(datos['fecha'])
                $('#readOtrosCostes').html(datos['otrosCostes'])
                $('#readTotalPedido').html(datos['totalPedido'])
                var body=""
                var lineas=datos['lineas']
                var sumaTotales=0
                for(var i=0; i<lineas.length;i++){
                    sumaTotales+=parseFloat(lineas[i]['total'])
                body+="<tr class='infoLineas'><td>"+(i+1)+"</td>"+
                           "<td class='izda'>"+lineas[i]['nombre']+"</td>"+
                           "<td class=''>"+lineas[i]['cantidad']+"</td>"+
                           "<td class=''>"+lineas[i]['tipoUnidad']+"</td>"+
                           "<td class=''>"+lineas[i]['precio']+"</td>"+
                           "<td class=''>"+lineas[i]['descuento']+"</td>"+
                           "<td class=''>"+lineas[i]['total']+"</td>"+
                           "</tr>"
                }
                body+="<tr class='infoLineas'><td></td>"+
                           "<td class='izda'></td>"+
                           "<td class=''></td>"+
                           "<td class=''></td>"+
                           "<td class=''></td>"+
                           "<td class=''></td>"+
                           "<td class=''><strong>"+sumaTotales.toFixed(2)+"</strong></td>"+
                           "</tr>"
                $('.detailVer').append(body)
               // $('#datosPedido').removeClass('hide')
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        }
    })
    
    
    $('#deletePedido').click(function(){
        var id_pedido=$('#pedido').val()
        
        if(id_pedido==0) { alerta('Información','Seleccione un pedido') }
        else{
             $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosPedido", 
            data: {id_pedido:id_pedido },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                $('.modal-title').html('Eliminar Pedido')
                $('.modal-body>p').html('¿Desea eliminar el pedido '+datos['numPedido']+' de<br />'+datos['nombre']+' de <br />Importe: '+datos['totalPedido']+'?')
                $("#confirm-delete").modal('show')  
                
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
            })
            
        }
    })
    
    
    
    $('.btn-ok-abandonar').click(function(e){
        cambios=false
        $(paginaRedirigir).trigger( typeTrigger );
        
    })
    
    
    //borrando pedido
    $('.btn-borrado').click(function(){
        var id_pedido=$('#pedido').val()
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/borrarPedido", 
            data: {id_pedido:id_pedido },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
               // alert('Pedido borrada correctamente.')
           // document.location.reload(true);
           $("#confirm-delete").modal('hide')  
              $('.modal-title').html('Información')
            $('.modal-body>p').html('Pedido borrado correctamente.')
            $("#myModalVolver").modal({backdrop:"static",keyboard:"false"}) 
                
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
            })
    })
    
    function buscarProductos(filtro){
    //var filtro=$(this).val()
    //alert('Hola blur '+$(this).val())
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getProductosFiltrados", 
        data:{filtro:filtro},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             //alert(datos['nombres'])
             $( "select#producto option" ).remove();
             var option='<option value="0">Seleccionar un producto</option>'
             $('#producto').append(option)
             $.each(datos['nombres'], function(index, value){
                 var option='<option value="'+datos['ids'][index]+'">'+value+' ('+datos['ids'][index]+')</option>'
                 $('#producto').append(option)
             })
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    
    }
    
    $('#buscarProductos').blur(function(){
    var filtro=$(this).val()
    buscarProductos(filtro)
    })
    
    $('#buscarEditProductos').blur(function(){
    var filtro=$(this).val()
    buscarEditProductos(filtro)
    })
    
    function buscarEditProductos(filtro){
    
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getProductosFiltrados", 
        data:{filtro:filtro},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             //alert(datos['nombres'])
             $( "select#editProducto option" ).remove();
             var option='<option value="0">Seleccionar un producto</option>'
             $('#editProducto').append(option)
             $.each(datos['nombres'], function(index, value){
                 var option='<option value="'+datos['ids'][index]+'">'+value+' ('+datos['ids'][index]+')</option>'
                 $('#editProducto').append(option)
             })
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    
    }
    
    
    $('#buscarProveedores').blur(function(){
    var filtro=$(this).val()
    //alert('Hola blur '+$(this).val())
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getProveedoresFiltrados", 
        data:{filtro:filtro},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             //alert(datos['nombres'])
             $( "select#proveedor option" ).remove();
             var option='<option value="0">Seleccionar un proveedor</option>'
             $('#proveedor').append(option)
             $.each(datos['nombres'], function(index, value){
                 var option='<option value="'+datos['ids'][index]+'">'+value+'</option>'
                 $('#proveedor').append(option)
             })
            $('#buscarProveedores').css('color','black')

            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    
    })
    
    $('buttom#addPedido').click(function(){
    
    })
    
    $('#updatePedido').click(function(){
        var id_pedido=$('#pedido').val()
       
        if(id_pedido==0) { alerta('Información','Seleccione un pedido') }
        else{
           //alerta('Información','Pedido seleccionada '+id_pedido)
           $('#modificacionPedido').removeClass('hide')
           $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosPedido", 
            data: {id_pedido:id_pedido },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                
                $('#editNumPedido').html(datos['numPedido'])
                $('#nombre').html(datos['nombre'])
                var fecha=datos['fecha']
                fecha=fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2)
                
                $('#editFechaPedido').val(fecha)
                
                $('#editOtrosCostes').val(datos['otrosCostes'])
                $('#editTotalPedido').html(datos['totalPedido'])
                var body=""
                var lineas=datos['lineas']
                
                
                var sumaTotales=0
                for(var i=0; i<lineas.length;i++){
                    sumaTotales+=parseFloat(lineas[i]['total'])
                    
                    
                    
                    
                body+="<tr class='editInfoLineas'><td>"+(i+1)+"</td>"+
                           "<td class='izda'>"+lineas[i]['nombre']+'<input type="hidden" class="editCodigo_producto" value="'+lineas[i]['codigo_producto']+'">'+"</td>"+
                           "<td class='editCantidades'>"+lineas[i]['cantidad']+"</td>"+
                           "<td class='editTiposUnidades'>"+lineas[i]['tipoUnidad']+"</td>"+
                           "<td class='editPrecios'>"+lineas[i]['precio']+"</td>"+
                           "<td class='editDescuentos'>"+lineas[i]['descuento']+"</td>"+
                           "<td class=''></td>"+
                           "<td class=''></td>"+
                           "<td class='editTotales'>"+lineas[i]['total']+"</td>"+
                           '<td><a href="#" class="editRemove">Eliminar</td>'+  
                           "</tr>"
                }
                
                $('.detailModificacion').append(body)
                
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        }
    })
    
    
})





</script>

<style type="text/css">
    
    td, th{
        text-align: left;
    }
    .table-bordered > tbody > tr > td.separacion{
        border-left: 1px solid white;
        border-right: 1px solid white;
        //color:white;
    }
    .separacionTitulo{
        border: 1px solid white;
        text-align: left;
        
    }
    .izda{
       text-align: left; 
    }
    
    .table > tbody > tr > td {
     vertical-align: middle;
     }
     select#producto,select#tiposUnidades,input#cantidad,input#precio,input#descuento,input#total,input#buscarProductos {
         margin-bottom: 2px;
         margin-top: 2px;
     }
     select#editProducto,select#editTiposUnidades,input#editCantidad,input#editPrecio,input#editDescuento,input#editTotal,input#editBuscarProductos {
         margin-bottom: 2px;
         margin-top: 2px;
     }
     
      select.input-sm{
        height: 25px;
        
    }
    
    .input-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
    
    
    
   
    
    .btn-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
    
    @media (min-width:760px){
        .col-sm-1b{
            float:left;
        }   
        .col-sm-1b{
           width:2%; 
        }
        .col-sm-1c{
            float:left;
        }   
        .col-sm-1c{
           width:5%; 
        }
    }
    
    #addLinea{
        padding-top:20px;
    }
    
</style>

 

