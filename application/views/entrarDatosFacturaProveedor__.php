<h3>Entrar datos factura proveedor</h3>
<?php 
$optionsProveedores=$proveedores['options'];
$optionsProductos=$productos['options'];
  ?>      
<form action="<?php echo base_url() ?>/index.php/compras/grabarFacturaCompleta_" method="POST">  
                    <div class="box box-primary col-lg-12">  
                        
                        <div class="box-body col-lg-6">  
                            <div class="form-group">  
                                <h4>Proveedor </h4>
                            <?php echo form_dropdown('proveedor', $optionsProveedores,'',array('id'=>'proveedor', 'class' => 'form-control')); ?>
                            </div>  
                        </div> 
                        <input type="hidden" value="0" id="codigoProveedor" >
                    </div>
    
    <div id="entrarFactura" class="hide">
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
                            <th>Precio</th>  
                            <th>Descuento</th>  
                            <th>Precio Anterior</th>  
                            <th>Descuento Anterior</th>  
                            <th>Total</th>  
                            <th><!--<input type="button" value="+" id="add" class="btnbtn-primary">--></th>  
                        </thead>  
                        <tbody class="detail">  
                            <tr>  
                                <td class="no">--</td>  
                                <td><?php echo form_dropdown('producto', $optionsProductos,'',array('class' => 'form-control','id'=>'producto')); ?></td>  
                                <td><input type="text" class="form-control quantity" name="cantidad" id="cantidad"></td>  
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
</form>  

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
    
    $('#proveedor').click(function(){
        $('#entrarFactura').removeClass('hide')
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
            $('#myModalVolver').css('color','blue')    
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Factura proveedor registrada correctamente.')
            $("#myModalVolver").modal()  
            
            //alert('Factura proveedor registrada correctamente.')
            //document.location.reload(true);
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas facturas. Informar");
            }
        })
    })
    
    
    
    $('.volver_').click(function(e){
        document.location.reload(true);
    })
    
    
    
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
 
     $('#otrosCostes').change(function(){
         totalFactura()
     })



    function addnewrow()   
        {  
        var cantidad=$('#cantidad').val()-0
        
        if(cantidad==0 || cantidad==""){
            alerta('Información importante','Falta introducir la cantidad antes de añalir la línea.')
            return false;
        }
        cantidad=cantidad.toFixed(3)
        var n=($('.detail tr').length-3)+1;
        
        
        var precio=(($('#precio').val())*1).toFixed(2)
        var descuento=(($('#descuento').val())*1).toFixed(2)
        var precioAnterior=(($('#precioAnterior').html())*1).toFixed(2)
        var descuentoAnterior=(($('#descuentoAnterior').html())*1).toFixed(2)
        var total=(($('#total').val())*1).toFixed(2)
        var codigo_producto=$('#producto').val()
        var producto=$('#producto option[value="'+codigo_producto+'"]').html()      //nombreProducto
        
        //alert('codigo_producto '+codigo_producto)
        
        $('#cantidad').val('')
        $('#precio').val('')
        $('#descuento').val('')
        $('#precioAnterior').html('')
        $('#descuentoAnterior').html('')
        $('#total').val('')
        $('#producto option[value="0"]').attr('selected','selected')
       nombreProducto="Sin código"
        
        var tr = '<tr>'+  
        '<td class="no">'+n+'</td>'+  
        '<td class="izda">'+producto+'<input type="hidden" class="codigo_producto" value="'+codigo_producto+'"></td>'+  
        '<td class="cantidades">'+cantidad+'</td>'+
        '<td class="precios">'+precio+'</td>'+
        '<td class="descuentos">'+descuento+'</td>'+  
        '<td >'+precioAnterior+'</td>'+
        '<td >'+descuentoAnterior+'</td>'+  
        '<td class="totales">'+total+'</td>'+   
        '<td><a href="#" class="remove">Eliminar</td>'+  
        '</tr>';  
        $('.detail').append(tr); 
        totalFactura()
        }
        
        
        $('body').delegate('.remove','click',function()  
        {  
            $(this).parent().parent().remove(); 
            totalFactura()
        }); 
    
    
    function totalFactura()  
        {  
            var t=0;  
            $('.totales').each(function(i,e)   
            {  
                
            var amt =$(this).html()-0;  
            t+=amt;  
            });  
            var otrosCostes=$('#otrosCostes').val()-0
            t=t+otrosCostes
            
            $('#totalFactura').html(t.toFixed(2));  
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

 

