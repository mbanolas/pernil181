
<br />

<h3>Pedido a proveedor</h3>
<?php $pedidos[0]='Seleccionar un pedido'; ?>
<input type="hidden" id="siguiente" value="<?php echo $siguiente; ?>">
           
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
          
          <div class="col-sm-4" id='pedidosIniciales' >
            <label for="pedidos" class="col-sm-12 form-control-label">Pedidos proveedor/acreedor: </label>
            
            <?php echo form_dropdown('pedido', $pedidos, '', array('disabled'=>'disabled','width'=>'100%','id' => 'pedido', 'class' => ' form-control input-sm ')); ?>
        </div>
      </div>
    
     
      <div class="form-group"> 
        <div class="col-sm-offset-0 col-sm-10" id="botones">
            <button type="submit" class="btn btn-default" id="prepararPedido">Nuevo Pedido</button>
            <button type="submit" class="btn btn-default" id="editarPedido">Editar Pedido</button>
            <button type="submit" class="btn btn-default" id="cancelarAlbaran">Cancelar</button>
        </div>
    </div>
  </div>
     
<div class="hide" id="nuevoPedido">   
    <hr>
    <div class="row " >
        <label  class="col-sm-2 " id="tipo"></label>
        <label class="col-sm-2_ "id="f2_">Núm Pedido</label>
        <label class="col-sm-1_ " id="numPedido"></label>
        <?php $hoy=date("Y-m-d"); ?>
        <label  class=" col-sm-1_ " id="f1_">Fecha</label>
        <input type="date" name="fechaPedido" id="fechaPedido" class="col-sm-2_" value="<?php echo $hoy ?>" placeholder="Fecha pedido">
        <label  class=" col-sm-3_ " id="f3_">Otros costes</label>
        <input type="text" name="otros" id="otrosCostes" class="col-sm-2_" value="" placeholder="Otros costes">
    </div>
</div>  
      


 <div class="hide" id="datosPedido">
      <div class="form-group row " >
        <label  class="col-sm-12 form-control-label">Introducir linea producto</label>
      </div>
     <!-- introducir datos producto -->
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
            <label for="producto" class="form-control-label">Producto</label>
                    <?php echo form_dropdown('producto', $optionsProductos, '', array('width'=>'100%','id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>
          <div class="col-sm-1c">
              <label for="" class="form-control-label">Und/caja</label>
              <p for="" class=" form-control-label_"  id="unidadesCaja"></p>
          </div> 
      <div class="col-sm-1" >
            <label for="cantidad" class=" form-control-label">Cantidad</label>
            <input type="text" name="cantidad" id="cantidad" class="input-sm form-control" placeholder="Cantidad" >
        </div>
        <div class="col-sm-1c">
              <label for="" class="form-control-label">Und</label>
              <p for="" class=" form-control-label_"  id="tipoUnidad"></p>
          </div>  
          <div class="col-sm-1">
            <label for="cantidad" class=" form-control-label">Precio</label>
            <input type="text" name="cantidad" id="precio" class="input-sm form-control" placeholder="Precio">
        </div>
          <div class="col-sm-1">
            <label for="cantidad" class=" form-control-label">Dto %</label>
            <input type="text" name="cantidad" id="descuento" class="input-sm form-control" placeholder="Dto">
        </div>
         
      
      <div class="col-sm-1" id="addLinea">
            <label  class="col-sm-12 form-control-label"> </label>
            <a href="#" class="" id="anadir" >Añadir </a>

        </div>
      </div>
      <hr>     
 <div class="row">
        <div class="col-sm-2">
            <label  class=" form-control-label">Código </label>
        </div>
        <div class="col-sm-4">
            <label for="ventaA" class="form-control-label">Producto</label>
        </div>
      <div class="col-sm-1 derecha aj">
            <label for="ventaA" class="form-control-label derecha ">Cantidad</label>
        </div>
     <div class="col-sm-1c und">
            <label  class="col-sm-1c form-control-label   ">Und</label>
        </div>
      <div class="col-sm-1 derecha aj">
            <label  class=" form-control-label derecha ">Precio</label>
        </div>
     <div class="col-sm-1 derecha aj">
            <label  class=" form-control-label derecha ">Dto %</label>
        </div>
     <div class="col-sm-1 derecha aj">
            <label  class=" form-control-label derecha ">Total</label>
        </div>
     
      <div class="col-sm-1" id="addLinea">
            <p for="ventaA" class="col-sm-12 form-control-label"> </p>
        </div>
 </div>

<div id="lineasProductos">
    

</div>
            


<div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10" id="botones">
      <button type="submit" class="btn btn-default" id="registrarPedido">Registrar Pedido y generar documento</button>
      <button type="submit" class="btn btn-default" id="cancelarAlbaran2">Cancelar</button>
    </div>
  </div>
</div>

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
    
    .reset{
       padding-left: 28px;
       padding-top:  4px;
    }
    
    .anadir{
       padding-left: 28px;
       padding-top:  20px;
    }
    
    .eliminar{
        text-align: left;
        padding-left: 0px;
      /* padding-top:  20px; */
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
        #fecha{
            float:left;
        }
        #f1{
           width:6%;  
           padding-right: 5px;
        }
        #f2{
           width:10%;  
           padding-right: 5px;
        }
        #f3{
           width:15%;  
           padding-right: 5px;
           padding-left: 60px;
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

    .total{
        text-align: right;
        font-weight: bold;
        padding-right: 0px;
    }
    
    #fechaPedido,#otrosCostes{
        border: 1px solid #cccccc;
        margin-left:10px;
        margin-right:50px;
        height: 25px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    #numPedido{
        margin-left:10px;
        margin-right:50px;
    }
    #nuevoPedido{
        border-bottom:  2px solid lightgray;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    
</style>

<script>
        
$(document).ready(function () {
    
    $('#buscarProveedores').focus()
    
    $('input.searchable-input').keyup(function(ev){
        if( ev.which == 13 || ev.which == 9 ) {
            return;
        }
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
            if(nombreId=='buscarAcreedores')
                filtroAcreedores(" ",'acreedor')
            
        });

    
   //filtrado proveedores
    $('#buscarProveedores').click(function(ev){
        $(this).val('')
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
    
    
    
    

    //filtrado productosFinales 
    $('#buscar').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
        $('#buscarProductos').css('color','black')
        $('select#producto').focus()
    })
    
    $('#buscarProductos').click(function(){
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
        $('#buscarProductos').css('color','black')
    }).keydown(function(ev){
        if ( ev.which == 13 || ev.which == 9) {
            ev.preventDefault();
            var filtro=$(this).val()
            filtroProductos(filtro,'producto')
            $(this).css('border','1px solid #ccc')  
            $(this).css('color','black')
            $('select#producto').focus()
        }
    })
    
    
    
    
   function lineaProducto(codigo,nombre,cantidad,tipoUnidad,precio,descuento,fechaCaducidad){
        var total=cantidad*precio-cantidad*precio*descuento/100
        total=total.toFixed(2)
        var linea='<div class="row">'
            linea+='<div class="col-sm-2">'
            linea+='<p  class="col-sm-12_ codigo_producto">'+codigo+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-4">'
            linea+='<p  class="col-sm-12_ ">'+nombre+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj_">'
            linea+='<input type="text"  class="col-sm-12 derecha aj cantidad" value="'+cantidad+'">'
            linea+='</div>'
            linea+='<div class=" col-sm-1c ">'
            linea+='<p  class="col-sm-1c   tipoUnidad">'+tipoUnidad+'</p>'
            linea+='</div>'
            
            linea+='<div class="col-sm-1 derecha aj_">'
            linea+='<input type="text"  class="col-sm-12 derecha aj precio" value="'+precio+'">'
            linea+='</div>'
            
            linea+='<div class="col-sm-1 derecha aj_">'
            linea+='<input type="text"  class="col-sm-12 derecha aj descuento" value="'+descuento+'">'
            linea+='</div>'
            
            linea+='<div class="col-sm-1 derecha">'
            linea+='<p  class="derecha  total">'+total+'</p>'
            linea+='</div>'
            
            linea+='<div class="col-sm-1" >'
            linea+='<a href="#" class="col-sm-12 eliminar"  >Eliminar </a>'
            linea+='</div>'
            linea+='</div>'
  return linea
   } 
   
   $('#producto').change(function(){
       
       var id_pe_producto=$(this).val()
       
       if (id_pe_producto==0) return false;
       $.ajax({
            url: "<?php echo base_url() ?>"+"index.php/productos/getDatosCompraProducto/"+id_pe_producto,
            success: function(datos){
               var datos=$.parseJSON(datos)
               
               $('#tipoUnidad').html(datos['tipoUnidad'])
               $('#unidadesCaja').html(datos['unidades_caja'])
               
               var id_proveedor=$('#proveedor').val()
               if(id_proveedor==datos['id_proveedor_1']) {
                   $('#precio').val(datos['precio'])
                   $('#descuento').val(datos['descuento'])
               }
               else {
                 if($('#proveedor').val()!=datos['id_proveedor_1']){
                    $("#myModal").css('color','blck')
                    $('.modal-title').html('Información')
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
                     $('#myModal').css('color','black')
                    $('.modal-body>p').html(texto)
                    $("#myModal").modal() 
            return false;
                }
                }
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado Albarán. Informar");
            }
        })    
   })
   
   function prepararPedido(nuevo=false){
        var id_pedido=$('#pedido').val();
       // alert(id_pedido)
        $('#lineasProductos').html('')
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosPedido",
            data: {id_pedido:id_pedido},
            success: function(datos){
               // alert (datos)
               var datos=$.parseJSON(datos)
               if(nuevo) {
                   $('#tipo').html('NUEVO pedido')
                   $('#numPedido').html(datos['siguiente'])  
               }
               else{
                 $('#tipo').html('EDITAR pedido')
                 $('#numPedido').html(datos['numPedido'])   
               }
               // alert(datos['id_proveedor'])
                //$('#proveedor').val(datos['id_proveedor'])
                $.each(datos['lineas'], function(index, value){
                    var linea=lineaProducto(value['codigo_producto'],value['nombreSinCodigo'],value['cantidad'],value['tipoUnidad'],value['precio'],value['descuento'],'')
                    $('#lineasProductos').append(linea)
                })
                
                $('#datosPedido').removeClass('hide')
                $('#nuevoPedido').removeClass('hide')
                
                $('#producto').val(0)
                $('#cantidad').val(0)
                $('#precio').val(0)
                $('#descuento').val(0)
                $('#fechaCaducidad').val('')
                $('#unidadesCaja').html('')
            
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })      
        
    }
   
   
   $('#prepararPedido').click(function(){
       
       var id_proveedor=$('#proveedor').val();
       if(id_proveedor==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No se ha seleccionado ningún proveedor / acreedor.')
            $("#myModal").modal()  
            return false
        }
        
       var id_pedido=$('#pedido').val();
       if(id_pedido==0){
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No se ha seleccionado ningún pedido<br>¿Continuar introduciendo datos directamente? ')
            $("#pregunta").modal({backdrop:"static",keyboard:"false"})
            $('#tipo').html('NUEVO pedido')
            $('#numPedido').html($('#siguiente').val())  
            return false
        }  
       
       prepararPedido(true) //tipo nuevo pedido
        
   })
   
   $('#editarPedido').click(function(){
       var id_proveedor=$('#proveedor').val();
       if(id_proveedor==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No se ha seleccionado ningún proveedor.')
            $("#myModal").modal()  
            return false
        }
        var id_pedido=$('#pedido').val();
       if(id_pedido==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No se ha seleccionado ningún pedido para editarlo.')
            $("#myModal").modal()  
            return false
        }
       prepararPedido(false) //tipo editarPedido
   })
   
   
   $('#continuar').click(function(){
       $('#lineasProductos').html("")
       $('#nuevoPedido').removeClass('hide')
       $('#datosPedido').removeClass('hide')
   })
   
   $('#pedido').change(function(){
       $('#datosPedido').addClass('hide')
       $('#nuevoPedido').addClass('hide')
       
       $('#lineasProductos').html("")
   })
   
   $('#proveedor').change(function(){
       
       $('#producto').val(0)
       $('#cantidad').val(0)
       $('#precio').val(0)
       $('#descuento').val(0)
       $('#fechaCaducidad').val('')
       $('#unidadesCaja').html('')
       
       $('#datosPedido').addClass('hide')
       $('#nuevoPedido').addClass('hide')
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
                 var option='<option value="'+value['id']+'">Pedido núm '+value['numPedido']+' - '+value['fecha']+' - '+value['importe']+' €</option>'
                 $('select#pedido').append(option)
                  $('#pedido').removeAttr('disabled')
             })
            
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })        
       }
       if(proveedor==0){
           // $('#pedidosIniciales').html(pedidosIniciales)
           $('#pedido').attr('disabled','disabled')
       
       }
   })
   
   
   
   
   $('#cancelarAlbaran, #cancelarAlbaran2').click(function(){
     window.location.href = "<?php echo base_url() ?>" + "index.php/inicioMenu";
   })
   
   $('#registrarPedido').click(function(){
       
      
       
       var proveedor=$('#proveedor').val()
       
       var numPedido=$('#numPedido').html()
       
       var otrosCostes=$('#otrosCostes').val()
       
       var fechaPedido=$('#fechaPedido').val()
        if(fechaPedido==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar la fecha de la venta.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
       
       var codigo_producto=[]
        $('.codigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).html()
        })
        if(codigo_producto.length==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No ha introducido ningún producto.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
        
       var cantidades=[]
        $('.cantidad').each(function(i,e)  {
            cantidades[i]=$(this).val()
        })
        var precios=[]
        $('.precio').each(function(i,e)  {
            precios[i]=$(this).val()
        })
        var descuentos=[]
        $('.descuento').each(function(i,e)  {
            descuentos[i]=$(this).val()
        })
        var totalPedido=+otrosCostes;
        var totales=[]
        $('.total').each(function(i,e)  {
            totales[i]=$(this).html()
            totalPedido+=+totales[i];
        })
        
        var tiposUnidades=[]
        $('.tipoUnidad').each(function(i,e)  {
            tiposUnidades[i]=$(this).html()
        })
        
        var lineas={}
        for (var i = 0; i < codigo_producto.length; ++i){
            //alert(cantidad[i])
            lineas[i]={"codigo_producto":codigo_producto[i],
                        "cantidades":cantidades[i],
                        "tiposUnidades":tiposUnidades[i],
                        "precios":precios[i],
                        "descuentos":descuentos[i],
                        "totales":totales[i]
                       }
            //alert(lineas[i]['cantidad'])           
        }
        
        //alert('proveedor '+proveedor)
        var pedido={}
        pedido={
                    "proveedor":proveedor,
                    "numPedido":numPedido,
                    "otrosCostes":otrosCostes,
                    "totalPedido":totalPedido,
                    "fechaPedido":fechaPedido,
                    "lineas":lineas
                }
         //alert('venta '+venta['lineas'])       
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarPedidoCompleta",
            data: pedido,
            success: function(datos){
                //alert('datos '+datos)
               var datos=$.parseJSON(datos)
               // alert('datos2 '+datos)
               cambios=false
               
               $('#datosPedido').addClass('hide')
                $('#nuevoPedido').addClass('hide')
       
                $('#lineasProductos').html("")
                $('#proveedor').val(0)
                $('#pedido').val(0)
                $('#proveedor').trigger( "change" );
                
               
                var direccion="<?php echo base_url() ?>pedidos/"+datos
               //alert(direccion)
                window.open(direccion)    
                $("#myModal").css('color','blue')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Pedido generado y creado documento Excel')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas pedido. Informar");
            }
        })        
        
        
   })
   
   function anadir(){
    var id_producto=$('#producto').val()
     var cantidad=$('#cantidad').val()
     var precio=$('#precio').val()
       var descuento=$('#descuento').val()
       if (!cantidad) 
           {
               $('#myModal').css('color','red')
           $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar la cantidad.\n")
            $("#myModal").modal()  
            return false
       }
       cantidad=cantidad.replace(',','.')
       var tipoUnidad=$('#tipoUnidad').html()
       
       
       if(tipoUnidad=="Und"){
           if(parseInt(cantidad)*1000!=parseFloat(cantidad)*1000){
               $('#myModal').css('color','red')
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
      
       var fechaCaducidad=$('#fechaCaducidad').val()
      
       
       var linea=lineaProducto(codigo_producto,nombre_producto,cantidad,tipoUnidad,precio,descuento,fechaCaducidad)
       $('#lineasProductos').append(linea)
       
       $('#producto').val(0)
       $('#cantidad').val(0)
       $('#precio').val(0)
       $('#descuento').val(0)
       $('#fechaCaducidad').val('')
       $('#unidadesCaja').html('')
       
      // $('.x').trigger('click')
      // $('#buscarProductos_').trigger('blur')
       
       
   }
   
   $('#anadir').click(function(e){
       var id_producto=$('#producto').val()
       if(id_producto==0){
           $('#myModal').css('color','red')
           $('.modal-title').html('Información ')
            $('.modal-body>p').html("No ha seleccionado ningún producto.\n")
            $("#myModal").modal()  
            return false
       }
       var cantidad=$('#cantidad').val()
       if (!cantidad || cantidad==0) 
           {
           $('#myModal').css('color','red')
           $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar la cantidad.\n")
            $("#myModal").modal()  
            return false
       }
       var unidadesCaja=$('#unidadesCaja').html()
      
       if(unidadesCaja==='0'){
           anadir()
           return false
        }
       var cajas=cantidad/unidadesCaja
       if((cantidad/unidadesCaja) % 1 !==0){
           $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('La cantidad NO es un mútiplo de unidades por caja.<br>¿Desea continuar?')
            $("#preguntaUnidades").modal({backdrop:"static",keyboard:"false"})
            //$('#tipo').html('NUEVO pedido')
            //$('#numPedido').html($('#siguiente').val())  
            return false
        }
       
       anadir()
       
       
   })
   
   $('#continuarUnidades').click(function(){
       anadir()
    })
   
   
    
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    
     
    var id_pe_producto
    
    
   
    var cantidadTotal=sumaCantidades()
    
    var partidas=$('.cantidad').length
    
     filtroProductos("",'producto')
     
    $('#buscar').click(function(){
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
    })
    
    //filtrado productosFinales 
    $('#buscarProductos').click(function(){
    $(this).val('')    
    filtroProductos("",'producto')
    })
    
    $('#resetProductos').click(function(){
        $('#buscarProductos').val("")
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
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
   // $('select#producto').change(function(){ $('#inventario').addClass('hide');})
    
    $('body').delegate('.eliminar','click',function()  {
        $(this).parent().parent().remove()
    })
   
    
    function sumaCantidades(){
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
        var total=0
        $('.importe').each(function (index, value) { 
            var valor=$(value).html()
            if (valor=="") valor="0"
            var importe=parseFloat(valor)
            
            total+=importe
        });
        if(isNaN(total)) total=0
        $('#importeTotal').html(total.toFixed(2))
        return total
    }
    
    
    
    $('body').delegate('.cantidad,.precio,.descuento','keyup',function()  
        {  
           cambios=true;
           var cantidad=($(this).parent().parent().children(':nth-child(3)').children().val())
           var precio=($(this).parent().parent().children(':nth-child(5)').children().val())
           var descuento=($(this).parent().parent().children(':nth-child(6)').children().val())
           var total=cantidad*precio-cantidad*precio*descuento/100
           total=total.toFixed(2)
           $(this).parent().parent().children(':nth-child(7)').children().html(total)
           
          // alert('hola')
           //var posicion=$('.cantidad' ).index( $(this))
           //sumaCantidades()
           // if(posicion<partidas-1) return false
           // $('#cantidades').append(otro)
           // partidas=$('.cantidad').length
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