<h3>Facturas Proveedores</h3>
<?php 
$optionsProveedores=$proveedores['options'];
$optionsProductos=$productos['options'];
$optionsFacturas=array();
  ?>      
    
    <div class="box box-primary col-lg-12">  
        <div class="container">
            <h4>Proveedor </h4>
            <div class="row">
                <div class="box-body col-lg-4"> 
                    Buscar: <input type="text" id="buscarProveedores" class="form-control input-sm" value="" >
                  <!--  <button type="button" class="btn btn-primary btn-sm" id="filtrarProveedores" >Filtrar Proveedores </button> -->
                </div> 
            </div>
            <div class="row">
                <div class="box-body col-lg-4"> 
                    <?php echo form_dropdown('proveedor', $optionsProveedores, '', array('id' => 'proveedor', 'class' => 'form-control')); ?>
                </div>    
                <input type="hidden" value="0" id="codigoProveedor" >
                <div class="box-body_ col-lg-6"> 
                    <button type="button" class="btn btn-primary" id="searchFacturas" >Buscar facturas </button>
                    <button type="button" class="btn btn-default" id="addFactura" >Nueva factura</button>
                </div> 
            </div>
        </div>
        <!-- selector facturas -->
        <div class="container hide" id="selectorFacturas">
            <div class="row">
                <div class="box-body col-lg-6"> 
                    <h4>Factura</h4>
                </div>
            </div>
            <div class="row"> 
                <div class="box-body col-lg-6" >
                    <?php echo form_dropdown('factura', $optionsFacturas, '', array('id' => 'factura', 'class' => 'form-control')); ?>
                </div>
                <div class="box-body col-lg-6"> 
                    <button type="button" class="btn btn-info" id="readFactura" >Ver </button>
                    <button type="button" class="btn btn-primary" id="updateFactura" >Modificar </button>
                    <button type="button" class="btn btn-danger" id="deleteFactura" >Eliminar </button>
                </div>    
                <input type="hidden" value="0" id="codigoProveedor" >
            </div>
             
        </div>
        <hr>
    </div>

    <div class="container">
    <!-- formulario Nueva factura -->
    <div class="hide" id="nuevaFactura"><h4>Nueva Factura</h4>
                        <div class="box-body col-lg-3"> 
                            
                            <div class="form-group">  
                                <h4>Núm Factura </h4>
                                <input class="form-control" type="text" value="" name="numFactura" id="numFactura">
                            </div>  
                        </div> 
                        <div class="box-body col-lg-3">  
                            <div class="form-group">  
                                <h4>Fecha </h4>
                                <input class="form-control" type="date" value="" name="fechaFactura" id="fechaFactura">
                            </div>  
                        </div> 
                    
        <br/>  
        <div class="box box-primary col-lg-12"> 
                    <h4>Preparación línea </h4>
        </div>
        
                    <table class="table table-bordered_ table-hover table-striped">
                        
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
                        <tbody class="detailNueva"> 
                            <tr>  
                                <td class="no"></td>  
                                <td >Buscar: <input type="text" class="form-control input-sm" id="buscarProductos" value=""  ></td>
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
                                <td class="no">--</td>  
                                <td><?php echo form_dropdown('producto', $optionsProductos,'',array('class' => 'form-control','id'=>'producto')); ?></td>  
                                <td><input type="text" class="form-control quantity" name="cantidad" id="cantidad"></td>  
                                <td ><?php echo form_dropdown('tipoUnidad', array('Und'=>'Und','Kg'=>'Kg'),'',array('class' => 'form-control input-sm','id'=>'tiposUnidades')); ?></td>  
                                <td><input type="text" class="form-control price" name="precio" id="precio"></td>  
                                <td><input type="text" class="form-control discount" name="descuento" id="descuento"></td> 
                                <td id="precioAnterior"></td>  
                                <td id="descuentoAnterior"></td> 
                                <td><input type="text" class="form-control total" name="total" id="total"></td> 
                                <td><a href="#" class="addLinea">Añadir</td>  
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
                                <td class="separacionTitulo" colspan="9"><h4>Lineas factura</h4></td>
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
               <input class="form-control" type="text" value="" name="otrosCostes" id="otrosCostes">

            </div>  
        </div> 
            </div>
        </div>
        <div class="container">
            <div class="row">
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 >Total factura</h4>
            </div>  
        </div> 
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 id="totalFactura">0.00</h4>
            </div>  
        </div> 
            </div>
        </div>
        
        <div class="container">
        
                <button type="submit" class="btn btn-success" id="registrarFactura" >Registrar factura</button>
            
        </div>
        </div>
        <br />
    </div>

<div class="container">
    <!-- ver factura -->
      <div id="datosFactura" class="hide"><h4>Datos Factura</h4>
            <div class="col-lg-4">
                <br />  
            <table class="table table-bordered_ table-hover_ ">
                <tbody>
                    <tr >
                        <th class="izda">Factura núm:</th>
                        <td class="izda" id="readNumFactura">Sin número factura
                    </tr>
                    <tr>
                        <th class="izda">Proveedor:</th>
                        <td class="izda" id="readNombre">
                    </tr>
                    <tr>
                        <th class="izda">Fecha:</th>
                        <td class="izda" id="readFecha">Sin fecha de factura
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
                        <th class="izda">Total Factura:</th>
                        <td class="izda_" ><span ><h4 id="readTotalFactura"></h4></span></td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
        </div>
</div>   



<div class="container">
    <!-- formulario Modificacion factura -->
    <div class="hide" id="modificacionFactura"><h4>Editar Factura</h4>
                        <div class="box-body col-lg-3"> 
                            
                            <div class="form-group">  
                                <h4>Núm Factura </h4>
                                <input class="form-control" type="text" value="" name="numFactura" id="editNumFactura">
                            </div>  
                        </div> 
                        <div class="box-body col-lg-3">  
                            <div class="form-group">  
                                <h4>Fecha </h4>
                                <input class="form-control" type="date" value="" name="fechaFactura" id="editFechaFactura">
                            </div>  
                        </div> 
                    
        <br/>  
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
                                <td class="no">--</td>  
                                <td><?php echo form_dropdown('producto', $optionsProductos,'',array('class' => 'form-control','id'=>'editProducto')); ?></td>  
                                <td><input type="text" class="form-control quantity" name="cantidad" id="editCantidad"></td>  
                                <td><?php echo form_dropdown('tipoUndad', array('Und'=>'Und','Kg'=>'Kg'),'',array('class' => 'form-control input-sm','id'=>'editTiposUnidades')); ?></td>  
                                <td><input type="text" class="form-control price" name="precio" id="editPrecio"></td>  
                                <td><input type="text" class="form-control discount" name="descuento" id="editDescuento"></td> 
                                <td id="editPrecioAnterior"></td>  
                                <td id="editDescuentoAnterior"></td> 
                                <td><input type="text" class="form-control total" name="total" id="editTotal"></td> 
                                <td><a href="#" class="addLinea">Añadir</td>  
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
                                <td class="separacionTitulo" colspan="9"><h4>Lineas factura</h4></td>
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
               <input class="form-control" type="text" value="" name="otrosCostes" id="editOtrosCostes">

            </div>  
        </div> 
            </div>
        </div>
        <div class="container">
            <div class="row">
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 >Total factura</h4>
            </div>  
        </div> 
        <div class="box-body col-lg-2">  
            <div class="form-group">  
                <h4 id="editTotalFactura">0.00</h4>
            </div>  
        </div> 
            </div>
        </div>
        
        <div class="container">
        
                <button type="submit" class="btn btn-success" id="registrarModificacionFactura" >Modificar factura</button>
            
        </div>
        </div>
        <br />
    </div>


<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuCompras').addClass('btn-primary');
  $('#menuFacturaProveedor').addClass('btn-primary');  
})
</script>


<script>
$(document).ready(function () {
    
    var paginaRedirigir=""
    var typeTrigger=""
    //control cambios antes de abandonar la página
    var cambios=false
    
    $('#numFactura, #fechaFactura, #otrosCostes, #producto, #cantidad, #precio, #descuento').change(function(){
        cambios=true
    })
    $('#editNumFactura, #editFechaFactura, #editOtrosCostes, #editProducto, #editCantidad, #editPrecio, #editDescuento').change(function(){
        cambios=true
    })
    
    
    
    window.onbeforeunload=confirmExit
     
     function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han guardado.'
           
        }
    }
           
    
    $('#addFactura').click(function(){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        var proveedor=$('#proveedor').val()
        if(proveedor==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información importante')
            $('.modal-body>p').html("No ha seleccionado el proveedor.\nSe debe de seleccionar antes de introducir la información de la factura")
            $("#myModal").modal()  
            return false
        }
        $('.infoLineas').remove()
        $('.editInfoLineas').remove()
        $('#selectorFacturas').addClass('hide')
        $('#datosFactura').addClass('hide')
        $('#nuevaFactura').removeClass('hide')
        $('#modificacionFactura').addClass('hide')
        
    })
    
    $('#updateFactura').click(function(){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        $('.infoLineas').remove()
         $('.editInfoLineas').remove()
        $('#datosFactura').addClass('hide')
        $('#nuevaFactura').addClass('hide')
        $('#modificacionFactura').removeClass('hide')
        
    })
    
    $('#readFactura').click(function(e){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        
        $('.infoLineas').remove()
        $('.editInfoLineas').remove()
        $('#datosFactura').removeClass('hide')
        $('#nuevaFactura').addClass('hide')
        $('#modificacionFactura').addClass('hide')
        
    })
    
    $('select#proveedor').change(function(){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        $('#selectorFacturas').addClass('hide')
        $('#nuevaFactura').addClass('hide')
        $('#datosFactura').addClass('hide')
        $('#modificacionFactura').addClass('hide')
    })
    
    $('select#factura').change(function(){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
        $('#nuevaFactura').addClass('hide')
        $('#datosFactura').addClass('hide')
        $('#modificacionFactura').addClass('hide')
    })
    
    
    $('#searchFacturas').click(function(){
        if(cambios){
            paginaRedirigir= $(this) 
            typeTrigger=e.type
            $("#confirm-cambios").modal('show')  
            return false
        }
       $('#nuevaFactura').addClass('hide')
       $('#datosFactura').addClass('hide')
       $('#modificacionFactura').addClass('hide')
       
        var proveedor=$('select#proveedor').val()
        if(proveedor==0){
            alerta('Información','Se debe seleccionar un proveedor.')
            return false;
        }
        
         $('select#factura option').remove()
         
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getFacturas",
            data: {proveedor:proveedor},
            success: function(datos){
                var resultado=$.parseJSON(datos)
                alert(resultado)
                $.each(resultado, function(index, value) {
                    var op='<option  value="'+index+'">'+value+'</option>'
                    $('select#factura').append(op)
                }); 
                $('#selectorFacturas').removeClass('hide')
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas facturas. Informar");
            }
        })
    })
    
    $('#registrarModificacionFactura').click(function(e){
        e.preventDefault()

        var proveedor=$('select#proveedor').val()
        if(proveedor==0){
            alerta('Información','Seleccionar provevedor.')
            return false;
        }
        
        var idFactura=$('select#factura').val()
        if(idFactura==0){
            alerta('Información','Seleccionar una factura.')
            return false;
        }
        
        var editNumFactura=$('input#editNumFactura').val()
        var editFechaFactura=$('input#editFechaFactura').val()
        
        var editOtrosCostes=$('#editOtrosCostes').val()*100
        var editTotalFactura=$('#editTotalFactura').html()*100
        
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
        var factura={}
        factura={   "idFactura":idFactura,
                    "proveedor":proveedor,
                    "numFactura":editNumFactura,
                    "fechaFactura":editFechaFactura,
                    "totalFactura":editTotalFactura ,
                    "otrosCostes":editOtrosCostes ,
                    "lineas":lineas
                }
        
        
        
        //alert('factura.proveedor '+factura.proveedor)
        //alert('factura.lineas[1].totales '+factura.lineas[1].totales)
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/updateFacturaCompleta",
            data: factura,
            success: function(datos){
                //alert('datos '+datos)
                var resultado=$.parseJSON(datos)
                //alert('resultado '+resultado['totalFactura'])
            $('#myModal').css('color','blue')    
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Registrada modificación Factura proveedor correctamente.')
            $("#myModalVolver").modal({backdrop:"static",keyboard:"false"})  
            
            cambios=false
            //alert('Factura proveedor registrada correctamente.')
            //document.location.reload(true);
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas facturas. Informar");
            }
        })
    
    })


    $('#registrarFactura').click(function(e){
        e.preventDefault()

        var proveedor=$('select#proveedor').val()
        if(proveedor==0){
            alerta('Información','Seleccionar provevedor.')
            return false;
        }
        
        var numFactura=$('input#numFactura').val()
        var fechaFactura=$('input#fechaFactura').val()
        
        var otrosCostes=$('#otrosCostes').val()*100
        var totalFactura=$('#totalFactura').html()*100
       
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
        var factura={}
        factura={"proveedor":proveedor,
                    "numFactura":numFactura,
                    "fechaFactura":fechaFactura,
                    "totalFactura":totalFactura ,
                    "otrosCostes":otrosCostes ,
                    "lineas":lineas
                }
        
        
        
        //alert('factura.proveedor '+factura.proveedor)
        //alert('factura.lineas[1].totales '+factura.lineas[1].totales)
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarFacturaCompleta",
            data: factura,
            success: function(datos){
                //alert('datos '+datos)
                var resultado=$.parseJSON(datos)
                //alert('resultado '+resultado['totalFactura'])
            $('#myModal').css('color','blue')    
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Factura proveedor registrada correctamente.')
            $("#myModalVolver").modal({backdrop:"static",keyboard:"false"})  
            
            cambios=false
            //alert('Factura proveedor registrada correctamente.')
            //document.location.reload(true);
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas facturas. Informar");
            }
        })
    })
    
    
    
    /* funciones utilizados en nuevaFactura */  
    $('.addLinea').click(function(e){  
        e.preventDefault()
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
    
    $('#producto').blur(function(){
        
        var proveedor=$('#proveedor').val()
        if(proveedor==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información importante')
            $('.modal-body>p').html("No ha seleccionado el proveedor.\nSe debe de seleccionar antes de introducir la información de la factura")
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
         totalFactura()
     })
     
     $('#editOtrosCostes').change(function(){
         totalFactura()
     })

    function addnewrow()   
        {  
        var cantidad=$('#cantidad').val()-0
        var editCantidad=$('#editCantidad').val()-0
        
        if(cantidad==0 && cantidad=="" && editCantidad==0 && editCantidad==""){
            alerta('Información importante','Falta introducir la cantidad antes de añalir la línea.')
            return false;
        }
        cantidad=cantidad.toFixed(3)
        editCantidad=editCantidad.toFixed(3)
        var tipoUnidad=$('#tiposUnidades').val()
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
        buscarProductos('')
        buscarEditProductos('')
        
        
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
        '<td class="tiposUnidades">'+tipoUnidad+'</td>'+
        '<td class="editPrecios">'+editPrecio+'</td>'+
        '<td class="editDescuentos">'+editDescuento+'</td>'+  
        '<td >'+editPrecioAnterior+'</td>'+
        '<td >'+editDescuentoAnterior+'</td>'+  
        '<td class="editTotales">'+editTotal+'</td>'+   
        '<td><a href="#" class="remove">Eliminar</td>'+  
        '</tr>';  
        $('.detailModificacion').append(tr); 
        
        $('#cantidad').val('')
        $('#precio').val('')
        $('#descuento').val('')
        $('#precioAnterior').html('')
        $('#descuentoAnterior').html('')
        $('#total').val('')
        $('#producto option[value="0"]').attr('selected','selected')
        
        $('#editCantidad').val('')
        $('#editPrecio').val('')
        $('#editDescuento').val('')
        $('#editPrecioAnterior').html('')
        $('#editDescuentoAnterior').html('')
        $('#editTotal').val('')
        $('#editProducto option[value="0"]').attr('selected','selected')
       
    nombreProducto="Sin código"
        
        totalFactura()
        }
        
        $('body').delegate('.remove','click',function()  
        {  
            $(this).parent().parent().remove(); 
            totalFactura()
        }); 
        
        $('body').delegate('.editRemove','click',function()  
        {  
            $(this).parent().parent().remove(); 
            totalFactura()
        }); 
    
    function totalFactura()  
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
            
            $('#totalFactura').html(t.toFixed(2)); 
            $('#editTotalFactura').html(editT.toFixed(2)); 
        }  
        
    /* fin funciones utilizados en nuevaFactura */    
    
    
    
     $('#readFactura').click(function(){
        $('#selectorFacturas').removeClass('hide')
       // $('#nuevaFactura').addClass('hide')
        
        var id_factura=$('#factura').val()
        if(id_factura==0) { alerta('Información','Seleccione una factura') }
        else{
           //alerta('Información','Factura seleccionada '+id_factura)
          // $('#datosFactura').removeClass('hide')
           $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                
                $('#readNumFactura').html(datos['numFactura'])
                $('#readNombre').html(datos['nombre'])
                $('#readFecha').html(datos['fecha'])
                $('#readOtrosCostes').html(datos['otrosCostes'])
                $('#readTotalFactura').html(datos['totalFactura'])
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
               // $('#datosFactura').removeClass('hide')
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        }
    })
    
    
    $('#deleteFactura').click(function(){
        var id_factura=$('#factura').val()
        
        if(id_factura==0) { alerta('Información','Seleccione una factura') }
        else{
             $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                $('.modal-title').html('Eliminar Factura')
                $('.modal-body>p').html('¿Desea eliminar la factura '+datos['numFactura']+' de<br />'+datos['nombre']+' de <br />Importe: '+datos['totalFactura']+'?')
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
    
    
    //borrando factura
    $('.btn-borrado').click(function(){
        var id_factura=$('#factura').val()
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/borrarFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
               // alert('Factura borrada correctamente.')
           // document.location.reload(true);
           $("#confirm-delete").modal('hide')  
              $('.modal-title').html('Información')
            $('.modal-body>p').html('Factura borrada correctamente.')
            $("#myModalVolver").modal({backdrop:"static",keyboard:"false"}) 
                
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
            })
    })
    
    $('#updateFactura').click(function(){
        var id_factura=$('#factura').val()
       
        if(id_factura==0) { alerta('Información','Seleccione una factura') }
        else{
           //alerta('Información','Factura seleccionada '+id_factura)
           $('#modificacionFactura').removeClass('hide')
           $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                
                $('#editNumFactura').val(datos['numFactura'])
                $('#nombre').html(datos['nombre'])
                var fecha=datos['fecha']
                fecha=fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2)
                
                $('#editFechaFactura').val(fecha)
                
                $('#editOtrosCostes').val(datos['otrosCostes'])
                $('#editTotalFactura').html(datos['totalFactura'])
                var body=""
                var lineas=datos['lineas']
                var sumaTotales=0
                for(var i=0; i<lineas.length;i++){
                    sumaTotales+=parseFloat(lineas[i]['total'])
                    
                    
                    
                    
                body+="<tr class='editInfoLineas'><td>"+(i+1)+"</td>"+
                           "<td class='izda'>"+lineas[i]['nombre']+'<input type="hidden" class="editCodigo_producto" value="'+lineas[i]['codigo_producto']+'">'+"</td>"+
                           "<td class='editCantidades'>"+lineas[i]['cantidad']+"</td>"+
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
})





</script>

<style type="text/css">
    .table-bordered > tbody > tr > td.separacion{
        border-left: 1px solid white;
        border-right: 1px solid white;
        color:white;
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
     select#producto,input#cantidad,input#precio,input#descuento,input#total {
         margin-bottom: 0px;
     }
    
</style>

 

