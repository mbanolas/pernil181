<!--<br />-->

<h3>Factura Proveedor </h3>
<?php $pedidos[0]='Seleccionar un pedido'; 
$albaranes[0]='Seleccionar un albarán';
?>
<input type="hidden" value="" id="num_albaranes">             
  <div class="form-horizontal">
      <div class="row">
          <div class="col-sm-3">
            <label  class="col-sm-12 form-control-label">Filtro proveedores/acreedores </label>
            <div class="input-group">
                <input type="text" id="buscarProveedores" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn " >
                    <button class="btn btn-default btn-sm" id="buscarProveedor" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
       
          <div class="col-sm-3">
            <label for="proveedor" class="col-sm-12 form-control-label">Proveedor/Acreedor: </label>
            <?php echo form_dropdown('proveedor', $proveedoresAcreedores, '', array('width'=>'100%','id' => 'proveedor', 'class' => ' form-control input-sm ')); ?>
        </div>
          
          <div class="col-sm-3" id='pedidosIniciales' >
            <label for="pedidos" class="col-sm-12 form-control-label">Albarán proveedor/acreedor: </label>
            
            <?php echo form_dropdown('albaran', $albaranes, '', array('disabled'=>'disabled','width'=>'100%','id' => 'albaran', 'class' => ' form-control input-sm ')); ?>
        </div>
      </div>
   
      
      <div class="form-group"> 
        <div class="col-sm-offset-0 col-sm-10" id="botones">
          <button type="submit" class="btn btn-default" id="prepararFactura">Preparar factura proveedor</button>
          <button type="submit" class="btn btn-default cancelarFactura" >Cancelar</button>
        </div>
    </div>
  </div>
      
<div class="hide" id="nuevaFactura">   
    <hr>
    <div class="row " >
        <label  class="col-sm-2 " id="tipo">Nueva Factura</label>
        <label  class=" col-sm-3_ " id="f3_">Núm Factura</label>
        <input type="text" name="numFactura" id="numFactura" class="col-sm-2_" value="" placeholder="Núm factura">
       
        <label class="col-sm-1_ " id="numPedido"></label>
        <?php $hoy=date("Y-m-d"); ?>
        <label  class=" col-sm-1_ " id="f1_">Fecha</label>
        <input type="date" name="fechaFactura" id="fechaFactura" class="col-sm-2_" value="<?php echo $hoy ?>" placeholder="Fecha pedido">
        <label  class=" col-sm-3_ " id="f3_">Otros costes</label>
        <input type="text" name="otros" id="otrosCostes" class="col-sm-2_" value="" placeholder="Otros costes">
        <label  class=" col-sm-3_ " id="f3_">Total factura</label>
        <label class="col-sm-1_ " id="totalFactura">0.00</label>
    </div>
</div>  
    
      
 <div class="hide" id="datosFactura">
      <div class="form-group row " >
        <label  class="col-sm-12 form-control-label">Introducir linea producto</label>
      </div>
     <!-- Introducir datos producto -->
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
       
        <div class="col-sm-3">
            <label for="producto" class="col-sm-12 form-control-label">Producto</label>
                    <?php echo form_dropdown('producto', $optionsProductos, '', array('width'=>'100%','id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>
      <div class="col-sm-1">
            <label for="cantidad" class="col-sm-12 form-control-label">Cantidad</label>
            <input disabled type="text" name="cantidad" id="cantidad" class="input-sm form-control" placeholder="cantidad">
        </div>
        <div class="col-sm-1c">
              <label for="" class="col-sm-12 form-control-label">Und</label>
              <p for="" class="col-sm-12 form-control-label_"  id="tipoUnidad"></p>
          </div>  
          <div class="col-sm-1">
              <label for="" class="col-sm-12 form-control-label">Iva</label>
              <p for="" class="col-sm-12 form-control-label_"  id="tipoIva"></p>
          </div>
       <div class="col-sm-1">
            <label for="precio" class="col-sm-12 form-control-label">Precio</label>
            <input disabled type="text" name="precio" id="precio" class="input-sm form-control" placeholder="precio">
        </div> 
          <div class="col-sm-1">
            <label for="descuento" class="col-sm-12 form-control-label">Descuento</label>
            <input disabled type="text" name="descuento" id="descuento" class="input-sm form-control" placeholder="descuento">
        </div>
          <div class="col-sm-1">
            <label for="importe" class="col-sm-12 form-control-label">Importe</label>
            <p for="" class="col-sm-12 form-control-label_"  id="importe"></p>
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
            <label for="producto" class="col-sm-12 form-control-label">Producto</label>
        </div>
      <div class="col-sm-1 derecha aj">
            <label for="cantidad" class="col-sm-12 form-control-label derecha aj">Cantidad</label>
        </div>
     <div class="col-sm-1 derecha aj und">
            <label for="und" class="col-sm-12 form-control-label derecha aj ">Und</label>
        </div>
      <div class="col-sm-1 derecha aj">
            <label for="precio" class="col-sm-12 form-control-label derecha aj">Precio</label>
        </div>
     <div class="col-sm-1 derecha aj">
            <label for="descuento" class="col-sm-12 form-control-label derecha aj">Dto.</label>
        </div>
     <div class="col-sm-1 derecha aj">
            <label for="importe" class="col-sm-12 form-control-label derecha aj">Importe</label>
        </div>
     <div class="col-sm-1 derecha aj">
            <label for="importe" class="col-sm-12 form-control-label derecha aj">% IVA</label>
        </div>
      <div class="col-sm-1" id="addLinea">
            <p for="ventaA" class="col-sm-12 form-control-label"> </p>
        </div>
 </div>

<div id="lineasProductos">
    

</div>
      <br>    
<div id="tablaIvas">
    <div class="container">
        <div class="col-sm-4">
            <table class="table">
    <thead>
      <tr>
        <th>Tipo IVA %</th>
        <th>Base</th>
        <th>IVA</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td id="iva1">4 %</td>
        <td id="base1"></td>
        <td id="totalIva1"></td>
        <td id="total1"></td>
      </tr>
      <tr>
        <td id="iva2">10 %</td>
        <td id="base2"></td>
        <td id="totalIva2"></td>
        <td id="total2"></td>
      </tr>
      <tr>
        <td id="iva3">21 %</td>
        <td id="base3"></td>
        <td id="totalIva3"></td>
        <td id="total3"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th ></th>
        <th id="totalBases"></th>
        <th id="totalIvas"></th>
        <th id="totalTotales"></th>
      </tr>
      </tfoot>
      
    
  </table>
        </div>
    </div>

</div>
    
<div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10" id="botones">
      <button type="submit" class="btn btn-default" id="registrarFactura">Registrar Factura Proveedor</button>
      <button type="submit" class="btn btn-default cancelarFactura" >Cancelar</button>
    </div>
  </div>
      <br>
 </div>    
<br>

<!-- style -->
<style>
    
    label.derecha,div.derecha {
        text-align: right;
    }
    
    label.derecha.aj {
        padding-right: 0px;;
    }
    
    .form-control-label span{
        font-weight: normal;
        font-size: 16px;
    }
    
    select.input-sm{
        height: 25px;
        
    }
    select#producto{
        width:100%;
    }
    .input-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
    input#buscarProductos{
        width:100%;
    }
    .reset{
       padding-left: 28px;
       padding-top:  4px;
    }
    
    .anadir{
       padding-left: 28px;
       padding-top:  20px;
    }
    
    .eliminar{
       padding-left: 28px;
       padding-top:  20px;
    }
    
    input.input-sm{
        border: 1px solid #cccccc;
    }
    hr{
        margin-top:5px;
    }
    .titulo{
        font-weight: bold;
    }
    .componentesCantidadVer,cantidadComponente{
        text-align: right;
    }
    
    #addLinea{
        padding-top:20px;
    }
     
    #totalCantidad,#importeTotal{
        font-weight: bold;
    }
    .col-sm-1b{
        position:relative;
        min-height:1px;
        paddign-right:0px;
        padding-left:0px;
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
    
    a#buscar{
        padding-left:5px;
    }
    
    p.cantidad{
        text-align: right;
    }
    .und{
        padding-right: 30px;
    }
    
    
    
    
    
     .btn-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
    
    #fechaFactura,#otrosCostes,#numFactura{
        border: 1px solid #cccccc;
        margin-left:5px;
        margin-right:20px;
        height: 25px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    #totalFactura{
        margin-left:10px;
    }
   
    #nuevaFactura{
        border-bottom:  2px solid lightgray;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    
    input.sinPedido{
        
        background-color:lightpink;
    }
    input.sinAlbaran{
        
        background-color:lightcoral;
    }
</style>
<script type="text/javascript" src="<?php echo base_url('js/push.min.js') ?>"></script>

<script>
        
$(document).ready(function () {
    
    function notificacion(body,url){
        Push.create('Registrando una factura',{
            body:body,
            icon: "http://localhost/pernil181/images/pernil1812.png",
            timeout:3000,
            onClose:function () {
                   window.location.href = url;
                } 
        });
    }
    
     
    
    $('#buscarProveedores').focus()
    
    tablaIvas()

    function tablaIvas(){
        return false
        
        var otrosCostes=$('#otrosCostes').val()
        if(isNaN(otrosCostes)) {
            otrosCostes=0
            $('#otrosCostes').val(0)
        }
        var base1=0
        var base2=0
        var base3=0
        
        var total1=0
        var total2=0
        var total3=0
        
        var totalIva1=0
        var totalIva2=0
        var totalIva3=0
        
        var totalBases=0
        var totalIvas=0
        var totalTotales=0
        
        
        
        base3=parseFloat(otrosCostes)
        total3=parseFloat((otrosCostes*1.21).toFixed(2))
        totalIva3=parseFloat((total3-base3).toFixed(2))
        totalBases=parseFloat(base3.toFixed(2))
        totalIvas=parseFloat(totalIva3.toFixed(2))
        totalTotales=parseFloat(total3.toFixed(2))
        
        
      
        var cantidad=[]
        $('.cantidad').each(function(i,e)  {
            cantidad[i]=$(this).val()
        })
        var descuento=[]
        $('.descuento').each(function(i,e)  {
            descuento[i]=$(this).val()
        })
        var importe=[]
        $('.importe').each(function(i,e)  {
            importe[i]=$(this).val()
        })
        var tipoIva=[]
        $('.iva').each(function(i,e)  {
            tipoIva[i]=$(this).html()
            //alert(tipoIva[i])
        })
        $.each(importe, function(index, value){
            //alert(tipoIva[index])
           switch (tipoIva[index]){
               case 4: 
                   
                   break
               case 10:
                   
                   break
               case 21:
                   
                   break
           }
        })
        
        $('#base1').html(base1)
        $('#base2').html(base2)
        $('#base3').html(base3.toFixed(2))
        $('#total1').html(total1)
        $('#total2').html(total2)
        $('#total3').html(total3)
        $('#totalIva1').html(totalIva1)
        $('#totalIva2').html(totalIva2)
        $('#totalIva3').html(totalIva3)
        $('#totalBases').html(totalBases.toFixed(2))
        $('#totalIvas').html(totalIvas.toFixed(2))
        $('#totalTotales').html(totalTotales.toFixed(2))
        
        
        
    }
   
    
    //filtrado productosFinales 
    $('#buscarProductos').click(function(){
        $(this).val('')
        filtroProductos("",'producto')
    })
    
    $('#buscar').click(function(){
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
    })
    
    $('#resetProductos').click(function(){
        $('#buscarProductos').val('')
        filtroProductos("",'producto')
    })
    
  function filtroProductos(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/stocks/getProductosFiltro", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             $('select#'+id+' option').remove();
             $('#'+id).append('<option value="0">'+'Seleccionar un producto'+'</option>')
             $.each(datos['productos'], function(index, value){
                 var id_pe_producto=value['id']
                 var nombre =value['nombre']
                 var codigo_producto=value['codigo_producto']
                 var option='<option value="'+id_pe_producto+'">'+nombre+' ('+codigo_producto+')'+'</option>'
                 $('#'+id).append(option)
             })
            $('#buscarProductos').css('color','black') 
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
   function lineaProducto(codigo,nombre,cantidad,tipoUnidad,precio,descuento,importe,tipoIva=0,fechaCaducidad,sinAlbaran=false){
        var linea='<div class="row ">'
            linea+='<div class="col-sm-2">'
            linea+='<p  class="col-sm-12 codigo_producto">'+codigo+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-3">'
            linea+='<p  class="col-sm-12 ">'+nombre+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            linea+='<input type="text"  class="col-sm-12 derecha aj cantidad" value="'+cantidad+'">'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            linea+='<p  class="col-sm-12 derecha aj tipoUnidad">'+tipoUnidad+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            linea+='<input type="text"  class="col-sm-12 derecha aj precio '+ sinAlbaran+'" value="'+precio+'">'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            linea+='<input type="text"  class="col-sm-12 derecha aj descuento '+ sinAlbaran+'" value="'+descuento+'">'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            importe=cantidad*precio
            importe=importe-importe*descuento/100
            if(!isNaN(importe)) {
                importe=numeral(importe).format('0.00')
                }  
                else{importe=numeral(0).format('0.00')}
            linea+='<p  class="col-sm-12 derecha aj importe" >'+importe+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            linea+='<p  class="col-sm-12 derecha aj iva text-right tipoIva" >'+tipoIva+'</p>'
            linea+='</div>'
            linea+='<input type="hidden" class="fechaCaducidad" value="'+fechaCaducidad+'">'
            linea+='<div class="col-sm-1" id="eliminarLinea">'
            linea+='<a href="#" class="eliminar"  >Eliminar </a>'
            linea+='</div>'
            linea+='</div>'
  return linea
   } 
   
   $('#producto').change(function(){
       //alert("$('#producto').change(function()")
       $('#cantidad').val('')
       var id_pe_producto=$(this).val()
       if (id_pe_producto==0) {
           $('#cantidad').val('')
           $('#precio').val('')
           $('#descuento').val('')
            $('#cantidad').attr('disabled')
            $('#precio').attr('disabled')
            $('#descuento').attr('disabled')
            return false;
        }
        $('#cantidad').removeAttr('disabled')
        $('#precio').removeAttr('disabled')
        $('#descuento').removeAttr('disabled')
        var id_proveedor=$('#proveedor').val()
        
       $.ajax({
            url: "<?php echo base_url() ?>"+"index.php/productos/getDatosCompraProducto/"+id_pe_producto,
            success: function(datos){
                //alert(datos)
               var datos=$.parseJSON(datos)
               $('#tipoUnidad').html(datos['tipoUnidad'])
               $('#tipoIva').html(datos['tipoIva'])
               if($('#proveedor').val()!=datos['id_proveedor_1']){
                    
                    if(!datos['nombre_proveedor_1']) datos['nombre_proveedor_1']="Sin información"
                    if(!datos['nombre_proveedor_2']) datos['nombre_proveedor_2']="Sin información"
                    if(!datos['nombre_proveedor_3']) datos['nombre_proveedor_3']="Sin información"
                    var nombreProveeedor=$('option[value="'+id_proveedor+'"]').html()
                    var nombreProducto=$('option[value="'+id_pe_producto+'"]').html()
                    var texto='<strong>El proveedor '+nombreProveeedor+' NO es el mismo que la última compra registrada del producto'+nombreProducto+'.</strong><br><br>'
                    texto+='Datos proveedores:<br><br>'
                    texto+='<table class="table table-striped">'
                    texto+='<thead><tr><th class="text-left">Proveedores </th><th class="text-left">Precios (€)</th><th class="text-left">Descuento (%)</th></tr></thead>'
                    texto+='<tbody>'
                    texto+='<tr><td class="text-left">'+datos['nombre_proveedor_1']+'</td><td class="text-left">'+datos['precio']+'</td><td class="text-left">'+datos['descuento']+'</td></tr>'
                    texto+='<tr><td class="text-left">'+datos['nombre_proveedor_2']+'</td><td class="text-left">'+datos['precio_2']+'</td><td class="text-left">'+'no previsto'+'</td></tr>'
                    texto+='<tr><td class="text-left">'+datos['nombre_proveedor_3']+'</td><td class="text-left">'+datos['precio_3']+'</td><td class="text-left">'+'no previsto'+'</td></tr>'
                    texto+='</tbody>'
                     texto+='</table>'
                    $("#myModal").css('color','black')
                    $('.modal-title').html('Información') 
                    $('.modal-body>p').html(texto)
                    $("#myModal").modal() 
                    return false;
                } 
               $('#precio').val(datos['precio'])
               $('#descuento').val(datos['descuento'])
               calcularImporte()
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })    
   })
   
   function calcularImporte(){
   //calcula y pone el importe en la linea de productos
   var cantidad=$('#cantidad').val()
   cantidad=cantidad.replace(",","")
   var precio=$('#precio').val() 
   precio=precio.replace(",","")
   var descuento=$('#descuento').val()
   descuento=descuento.replace(",","")
   var importe=cantidad*precio-cantidad*precio*descuento/100
   importe=importe.toFixed(2)
   $('#importe').html(importe)
   }
   
   function calcularNuevoImporte(cantidad,precio,descuento){
       var importe=cantidad*precio-cantidad*precio*descuento/100
        importe=importe.toFixed(2)
        return importe
    }
   
   $('#cantidad').keyup(function(){
       calcularImporte()
   })
   $('#precio').keyup(function(){
       calcularImporte()
   })
   $('#descuento').keyup(function(){
       calcularImporte()
   })
   
   
   $('#prepararFactura').click(function(){
       var id_albaran=$('#albaran').val();
       var proveedor=$('#proveedor').val();
       if(proveedor==0){
        $("#myModal").css('color','red')   
        $('.modal-title').html('Información')
        $('.modal-body>p').html('Seleccionar el proveedor de la factura')
        $("#myModal").modal({backdrop:"static",keyboard:"false"})
        return false
    }  
       if(id_albaran==0){
        $('.modal-title').html('Información')
        $('#pregunta').css('color','black')
        $('.modal-body>p').html('No se ha seleccionado ningún albarán<br>¿Continuar introduciendo datos directamente? ')
        $("#pregunta").modal({backdrop:"static",keyboard:"false"})
        $('#nuevaFactura').removeClass('hide')
        return false
    }   
        $('#datosFactura').removeClass('hide')
        $('#otrosDatosFacura').removeClass('hide')
        $('#nuevaFactura').removeClass('hide')
        $('#numFactura').focus()
                
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosAlbaran",
            data: {id_albaran:id_albaran},
            success: function(datos){
                //alert (datos)
               var datos=$.parseJSON(datos)
                
                var otrosCostes=$('#otrosCostes').val()*1+parseFloat(datos['otrosCostes']);
                $('#otrosCostes').val(otrosCostes.toFixed(2))
                 
                var num_albaran=""
              
                $.each(datos['lineas'], function(index, value){
                    var sinPedido=''
                    num_albaran=value['num_albaran']
                    if(value['precio']=='100,000.00') {
                        value['precio']='---'
                        sinPedido='sinPedido'
                    }
                    if(value['descuento']=='100,000.00')value['descuento']='---'
                    //alert(value['precio']+' '+value['descuento'])
                    var linea=lineaProducto(value['codigo_producto'],value['nombreSinCodigo'],value['cantidad'],value['tipoUnidad'],value['precio'],value['descuento'],value['total'],value['tipoIva'],value['fecha_caducidad'],sinPedido)
                    $('#lineasProductos').append(linea)
                    sumaImportes()
                })
                var separador=""
                if($('#num_albaranes').val()) separador=', '
                $('#num_albaranes').val($('#num_albaranes').val()+separador+num_albaran)
                $('#datosFactura').removeClass('hide')
                $('#otrosDatosFacura').removeClass('hide')
                $('#nuevaFactura').removeClass('hide')
                //alert('$(#num_albaranes).val(): '+$('#num_albaranes').val())
                tablaIvas()
            },
            error: function(){
                alertaError("Información importante","Error en el proceso preparación Factura proveedor. Informar");
            }
        })      
        
        
        
        
   })
   
   $('#continuar').click(function(){
       $('#lineasProductos').html("")
       $('#datosFactura').removeClass('hide')
       $('#otrosDatosFacura').removeClass('hide')
   })
   
   $('#pedido').change(function(){
       $('#datosFactura').addClass('hide')
       $('#otrosDatosFacura').addClass('hide')
       $('#lineasProductos').html("")
   })
   
   $("select#proveedor").on('focus', function () {
        // Store the current value on focus and on change
        previous = this.value;
    }).change(function() {
        // Do something with the previous value after the change
        if(cambios) {
           $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Datos factura NO registrados.<br>Cancelar factura para cambiar a otra.')
            $("#myModal").modal() 
            $(this).val(previous)
           return false
       }
       cambioProveedor()
    });
   
   
   function cambioProveedor(){
    //    alert('cambio proveedor')
       $('#datosFactura').addClass('hide')
       $('#otrosDatosFacura').addClass('hide')
       $('#nuevaFactura').addClass('hide')
       var proveedor=$('#proveedor').val()
       
       if(proveedor>0) {
           //leer datos de los pedidos del proveedor seleccionado
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getAlbaran",
            data: {proveedor:proveedor, tipo:true},
            success: function(datos){
                //alert (datos)
               var datos=$.parseJSON(datos)
                $('select#albaran').children('option').remove()
                var option0='No existen albaranes'
                if(Object.keys(datos['options']).length>0) option0='Seleccionar un albaran'
                $('select#albaran').append('<option value="0">'+option0+'</option>')
                $.each(datos['options'], function(index, value){
                   // console.log(value)
                 var option='<option value="'+datos['ids'][index]+'">'+value+'</option>'
                 $('select#albaran').append(option)
                  $('#albaran').removeAttr('disabled')
             })
            
            },
            error: function(){
                alertaError("Información importante","Error en el proceso consultar albaranes. Informar");
            }
        })        
       }
       if(proveedor==0){
           // $('#pedidosIniciales').html(pedidosIniciales)
           $('#albaran').attr('disabled','disabled')
       
       }
   }
   
   $('#proveedor__').change(function(){
       $('#datosFactura').addClass('hide')
       $('#otrosDatosFacura').addClass('hide')
       var proveedor=$(this).val()
       if(proveedor>0) {
           //leer datos de los pedidos del proveedor seleccionado
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getPedidos",
            data: {proveedor:proveedor, tipo:true},
            success: function(datos){
               // alert (datos)
               var datos=$.parseJSON(datos)
                $('select#pedido').children('option').remove()
                var option0='No existen pedidos'
                if(datos.length>0) option0='Seleccionar un pedido'
                $('select#pedido').append('<option value="0">'+option0+'</option>')
                $.each(datos, function(index, value){
                 var option='<option value="'+value['id']+'">Pedido núm '+value['numPedido']+' '+value['fecha']+' '+value['importe']+' €</option>'
                 $('select#pedido').append(option)
             })
            
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })        
           
           
           
       }
       if(proveedor==0){
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getPedidos",
            data: {proveedor:proveedor, tipo:true},
            success: function(datos){
               // alert (datos)
               var datos=$.parseJSON(datos)
                $('select#pedido').children('option').remove()
                var option0='No existen pedidos'
                if(datos.length>0) option0='Seleccionar un pedido'
                $('select#pedido').append('<option value="0">'+option0+'</option>')
                $.each(datos, function(index, value){
                 var option='<option value="'+value['id']+'">Pedido núm '+value['numPedido']+' '+value['fecha']+' '+value['importe']+' €</option>'
                 $('select#pedido').append(option)
             })
            
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })        
       }
   })
   
   
   
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

    //filtrado proveedores
    $('#buscarProveedores').click(function(ev){
        $(this).val('')
        cambioProveedor()
        filtroProveedores("",'proveedor')
    }).keydown(function(ev){
        if ( ev.which == 13 || ev.which == 9) {
            ev.preventDefault();
            var filtro=$(this).val()
            filtroProveedores(filtro,'proveedor')
            $(this).css('border','1px solid #ccc')  
            $(this).css('color','black')
            $('select#proveedor').focus()
        }
    })
    
    
    $('#buscarProveedor').click(function(e){
        var filtro=$('#buscarProveedores').val()
        filtroProveedores(filtro,'proveedor')
        $('select#proveedor').focus()
    })
    
    function filtroProveedores(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getProveedoresAcreedoresFiltrados", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            
             $('select#'+id+' option').remove();
             $('#'+id).append('<option value="0">Seleccionar un proveedor / acreedor</option>')
             $.each(datos['proveedores'], function(index, value){
                 $('#'+id).append('<option value="'+value['id_proveedor']+'">'+value['nombre']+'</option>')
             })
             $('#buscarProveedores').css('color','black')
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
   
   
   
   
   $('.cancelarFactura').click(function(){
     window.location.href = "<?php echo base_url() ?>" + "index.php/compras/facturasProveedoresEntreFechas";
   })
   
   $('#registrarFactura').click(function(e){
       e.preventDefault()
       
       $(this).attr('disabled','disabled')
       var proveedor=$('#proveedor').val()
       var numFactura=$('#numFactura').val()
      
       if(numFactura==''){
            $("#myModal").css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar número de factura.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled')
            return false;
        }
       var totalFactura=$('#totalFactura').html()
       var otrosCostes=$('#otrosCostes').val()
       var num_albaranes=$('#num_albaranes').val()
       
       var fecha=$('#fechaFactura').val()
       
        if(fecha==''){
            $("#myModal").css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar la fecha de la factura.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled')
            return false;
        }
       //alert(proveedor+pedido+fecha)
       var codigo_producto=[]
        $('.codigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).html()
        })
        if(codigo_producto.length==0){
            $("#myModal").css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No ha introducido ningún producto.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled')
            return false;
        }
        
       var cantidad=[]
        $('.cantidad').each(function(i,e)  {
            cantidad[i]=$(this).val()
            
        })
        
        var tipoUnidad=[]
        $('.tipoUnidad').each(function(i,e)  {
            tipoUnidad[i]=$(this).html()
        })
        var precio=[]
        var salir=0
        $('.precio').each(function(i,e)  {
            precio[i]=$(this).val()
           
            if(cantidad[i]!=0 && precio[i]=='---'){
                $("#myModal").css('color','red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Falta introducir el precio de algún producto sin pedido.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                salir=1
                $(this).removeAttr('disabled')
                return false;
            }
        })
        if(salir) {
            $(this).removeAttr('disabled')    
            return;
        }
        
        var descuento=[]
        $('.descuento').each(function(i,e)  {
            descuento[i]=$(this).val()
            if(cantidad[i]!=0 && descuento[i]=='---'){
                $("#myModal").css('color','red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Falta introducir el descuento de algún producto sin pedido.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                salir=1
                return;
            }
        })
        
        if(salir) {
            $(this).removeAttr('disabled')    
            return;
        }
        var importe=[]
        $('.importe').each(function(i,e)  {
            importe[i]=$(this).html()
        })
        
       
        
        var tipoIva=[]
        $('.tipoIva').each(function(i,e)  {
            tipoIva[i]=$(this).html()
        })
        
        var fechaCaducidad=[]
        $('.fechaCaducidad').each(function(i,e)  {
            fechaCaducidad[i]=$(this).val()
        })
        
        
        
        var lineas={}
        var pos=0
        for (var i = 0; i < codigo_producto.length; ++i){
            if(cantidad[i]!=0){
            lineas[pos]={"codigo_producto":codigo_producto[i],
                        "cantidad":cantidad[i],
                        "tipoUnidad":tipoUnidad[i],
                        "precio":precio[i],
                        "descuento":descuento[i],
                        "importe":importe[i],
                        "fecha_caducidad":fechaCaducidad[i],
                        "tipoIva":tipoIva[i]
                       }
                   }
                   pos++
        }
        
        var venta={}
        venta={
                    "proveedor":proveedor,
                    "num_albaranes":num_albaranes,
                    "numFactura":numFactura,
                    "otrosCostes":parseInt(otrosCostes*100),
                    "fecha":fecha,
                    "totalFactura":parseInt(totalFactura*100),
                    "tipoIva":2100,
                    "lineas":lineas
                }
        if(!salir)  {      
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarFacturaProveedor",
            data: venta,
            success: function(datos){
               //alert('datos '+datos)
               var datos=$.parseJSON(datos)
               cambios=false
               // var direccion="<?php echo base_url() ?>ventas/"+datos
               // window.open(direccion)
               
                notificacion('Factura Registrada correctamente',"<?php echo base_url() ?>" + "index.php/compras/facturasProveedoresEntreFechas")
                $("#myModal").css('color','black')
                $('#myModal').on('hidden.bs.modal', function () {
                   window.location.href = "<?php echo base_url() ?>" + "index.php/compras/facturasProveedoresEntreFechas";
                }) 
                
                
                $('#myModal').css('color','blue')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Factura Registrada Correctamente.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                
            },
            error: function(){
                alert("Error: Información importante","Error en el proceso grabado lineas facturas proveedor. Informar");
            }
        })        
        }
        
   })
   
   $('#anadir').click(function(e){
       var id_producto=$('#producto').val()
       if(id_producto==0){
           $("#myModal").css('color','red')
           $('.modal-title').html('Información ')
            $('.modal-body>p').html("No ha seleccionado ningún producto.\n")
            $("#myModal").modal()  
            return false
       }
       var tipoIva=$('#tipoIva').html()
       var cantidad=$('#cantidad').val()
       if (!cantidad) 
           {
            $("#myModal").css('color','black')   
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar la cantidad.\n")
            $("#myModal").modal()  
            return false
       }
       cantidad=cantidad.replace(',','.')
       var precio=$('#precio').val()
       if (!precio) 
           {
            $("#myModal").css('color','black')    
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar el precio.\n")
            $("#myModal").modal()  
            return false
       }
       //alert(precio)
       precio=precio.replace(',','')
       //alert(precio)
       precio=numeral(precio).format('0.000');
       var descuento=$('#descuento').val()
       if (!descuento) 
           {
            $("#myModal").css('color','black')   
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar el descuento.\n")
            $("#myModal").modal()  
            return false
       }
       descuento=descuento.replace(',','')
       descuento=numeral(descuento).format('0.00');
       
       var importe=$('#importe').html()
       importe=importe.replace(',','')
       var tipoUnidad=$('#tipoUnidad').html()
       
       if(tipoUnidad=="Und"){
           if(parseInt(cantidad)*1000!=parseFloat(cantidad)*1000){
               $("#myModal").css('color','red')
               $('.modal-title').html('Información ')
                $('.modal-body>p').html("La cantidad debe ser un número entero, sin decimales.\n")
                $("#myModal").modal()  
                return false
           }
            cantidad=parseInt(cantidad)
        }
       else {
           cantidad=parseInt(parseFloat(cantidad)*1000)/1000
           cantidad=cantidad.toFixed(3)
       }
       
       cambios=true
       
      // alert(cantidad)
       
       var producto=$('#producto').children('option[value="'+id_producto+'"]').html()
       var codigo_producto=producto.substr(-14,13)
       var nombre_producto=producto.substr(0,producto.length-15)
       var sinAlbaran=true
       var linea=lineaProducto(codigo_producto,nombre_producto,cantidad,tipoUnidad,precio,descuento,importe,tipoIva,'',sinAlbaran)
       $('#lineasProductos').append(linea)
       
       $('#producto').val(0)
       $('#cantidad').val(0)
       $('#precio').val(0)
       $('#importe').html(0)
       
      // $('.x').trigger('click')
       $('#buscarProductos_').trigger('blur')
       
       sumaImportes()
       tablaIvas()
   })
   
   
   
   function aplicarFiltro(filtro,id){
       // filtroProductos(filtro,id)
    }
    
    
    
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    
     
    var id_pe_producto
    
    
    filtroProductos("",'producto')
   // var cantidadTotal=sumaCantidades()
    
    var partidas=$('.cantidad').length
    
    
    
     
    
    
    $('body').delegate('.eliminar','click',function()  {
        $(this).parent().parent().remove()
        sumaImportes()
    })
   
    
    function sumaCantidades_(){
        var total=0
        $('.cantidad').each(function (index, value) { 
            var valor=$(value).html()
            if (valor=="") valor="0"
            var cantidad=parseInt(valor)
            if (cantidad=="") cantidad="0"
            total+=parseInt(cantidad)
        });
        if(isNaN(total)) total=0
        $('#totalCantidad').html(total)
        return total
    }
    
    function sumaImportes(){
        
        
        var total=parseFloat($('#otrosCostes').val())
        if(isNaN(total)) {
            total=0
            $('#otrosCostes').val(0)
        }
        var tipoIva={}
        var base1=0
        var base2=0
        var base3=0
        var totalIva1=0
        var totalIva2=0
        var totalIva3=0
        var total1=0
        var total2=0
        var total3=0
        var totalBases=0
        var totalIvas=0
        var totalTotales=0
        
        base3=parseFloat($('#otrosCostes').val())
        
        $('.tipoIva').each(function (index, value) { 
            var valor=$(this).html()
            if($(this).html()=="") valor=0
            tipoIva[index]=valor
            if (tipoIva[index]=="") tipoIva[index]="0"
        })
        $('.importe').each(function (index, value) { 
            
            var valor=$(value).html()
            if (valor=="") valor="0"
            var importe=parseFloat(valor)
            total+=importe
            
            switch(tipoIva[index]){
                case '4':
                    base1+=importe
                  break;  
                case '10':
                    base2+=importe
                break;  
                case '21':
                    base3+=importe
                    break;  
            }
        });
        totalIva1=base1*0.04
        totalIva2=base2*0.10
        totalIva3=base3*0.21
       
        $('#base1').html(base1.toFixed(2))
        $('#base2').html(base2.toFixed(2))
        $('#base3').html(base3.toFixed(2))
        
        
        $('#totalIva1').html(totalIva1.toFixed(2))
        $('#totalIva2').html(totalIva2.toFixed(2))
        $('#totalIva3').html(totalIva3.toFixed(2))
        
        base1=$('#base1').html()
        base2=$('#base2').html()
        base3=$('#base3').html()
        totalIva1=$('#totalIva1').html()
        totalIva2=$('#totalIva2').html()
        totalIva3=$('#totalIva3').html()
        total1=parseFloat(base1)+parseFloat(totalIva1)
        total2=parseFloat(base2)+parseFloat(totalIva2)
        total3=parseFloat(base3)+parseFloat(totalIva3)
        totalBases=parseFloat(base1)+parseFloat(base2)+parseFloat(base3)
        totalIvas=parseFloat(totalIva1)+parseFloat(totalIva2)+parseFloat(totalIva3)
        totalTotales=parseFloat(total1)+parseFloat(total2)+parseFloat(total3)
        
        $('#total1').html(total1.toFixed(2))
        $('#total2').html(total2.toFixed(2))
        $('#total3').html(total3.toFixed(2))
        
        $('#totalBases').html(totalBases.toFixed(2))
        $('#totalIvas').html(totalIvas.toFixed(2))
        $('#totalTotales').html(totalTotales.toFixed(2))
        
        $('#totalFactura').html(totalTotales.toFixed(2))
        
        return total
    }
    
    
    
    $('body').delegate('.cantidad','keyup',function()  
        {  
            cambios=true;
            var cantidad=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().val())
            var precio=parseFloat($(this).parent().parent().children('div:nth-child(5)').children().val())
            var descuento=parseFloat($(this).parent().parent().children('div:nth-child(6)').children().val())
            var tipoIva=parseFloat($(this).parent().parent().children('div:nth-child(8)').children().html())
            console.log(cantidad)
            
            var importe=calcularNuevoImporte(cantidad,precio,descuento)
            $(this).parent().parent().children('div:nth-child(7)').children().html(importe)
            
            tablaIvas()
            
            sumaImportes()
            
        }); 
    
    $('body').delegate('.precio','keyup',function()  
        {  
            cambios=true;
            var cantidad=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().val())
            var precio=parseFloat($(this).parent().parent().children('div:nth-child(5)').children().val())
            var descuento=parseFloat($(this).parent().parent().children('div:nth-child(6)').children().val())

            var importe=calcularNuevoImporte(cantidad,precio,descuento)
            $(this).parent().parent().children('div:nth-child(7)').children().html(importe)
            
            sumaImportes()
            
        }); 
    
    $('body').delegate('.descuento','keyup',function()  
        {  
            cambios=true;
            var cantidad=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().val())
            var precio=parseFloat($(this).parent().parent().children('div:nth-child(5)').children().val())
            var descuento=parseFloat($(this).parent().parent().children('div:nth-child(6)').children().val())

            var importe=calcularNuevoImporte(cantidad,precio,descuento)
            $(this).parent().parent().children('div:nth-child(7)').children().html(importe)
            
            sumaImportes()
            
        }); 
    
    
    
    $('body').delegate('#otrosCostes','keyup',function()  
        {  
            cambios=true;
            sumaImportes()
            tablaIvas()
            
            
        });
    
    
   
   
   
   window.onbeforeunload=confirmExit
     
     function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han registrado.'
           
        }
    }
    
    
   
    
})
</script>