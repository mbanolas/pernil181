

<!-- Seleccion producto -->
 <form>

     <br />

<h4>Ventas directas </h4>


           
  
  
 <div class="form-group row">
    <label for="concepto" class="col-sm-2 form-control-label">Información venta</label>
  </div>
                
  <div class="form-horizontal">
      <div class="row">
          <div class="col-sm-2">
            <label for="ventaA" class="col-sm-12 form-control-label">Venta A: </label>
            <input type="text"  id="vendidoA" name="ventaA" class="input-sm" value="" placeholder="Destinatario venta"> 
        </div>
          
          <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro clientes </label>
            <div class="input-group">
                <input type="text" id="buscarClientes" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscarC" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
          </div>
          
          <div class="col-sm-3">
            <label for="cliente" class="col-sm-12 form-control-label">Cliente: </label>
            <?php echo form_dropdown('cliente', $clientes, '', array('width'=>'100%','id' => 'cliente', 'class' => ' form-control input-sm ')); ?>
        </div>
          
          <div class="col-sm-3">
            <label for="concepto" class="col-sm-12 form-control-label">Concepto </label>
        <input type="text"  id="concepto" name="concepto" class="   input-sm" value="" placeholder="Concepto - motivo de la venta"> 
        </div>
          
          <div class="col-sm-2">
            <label for="Fecha" class="col-sm-12 form-control-label">Fecha </label>
            <?php $hoy=date("Y-m-d"); ?>
            <input type="date"  id="fecha" name="fecha" class="   input-sm" value="<?php echo $hoy ?>" placeholder="Fecha"> 
        </div>
      </div>
      <hr>
      <div class="form-group row">
    <label  class="col-sm-12 form-control-label">Introducir linea producto</label>
  </div>
      <div class="row">
          <div class="col-sm-2">
              <label  class="col-sm-12 form-control-label">Filtro productos </label>
          </div>
          <div class="col-sm-3">
              <label  class="col-sm-12 form-control-label">Producto </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">Cantidad (unds) </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">Precio (€/und) </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">Coste (€/und) </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">PVP (€/und) </label>
          </div>
      </div>
      
      <div class="row">
          <div class="col-sm-2">
          <div class="input-group">
                <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscar" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
          </div>
          <div class="col-sm-3">
                    <?php echo form_dropdown('producto', $optionsProductos, '', array('width'=>'100%','id' => 'producto', 'class' => ' form-control input-sm ')); ?>
          </div>
          <div class="col-sm-1">
            <input type="text" name="cantidad" id="cantidad" class="input-sm form-control" placeholder="cantidad" >
          </div>
          <div class="col-sm-1">
            <input type="text" name="precio" id="precio" class=" form-control input-sm" placeholder="precio">
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha" id="coste">  </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha" id="pvp">  </label>
          </div>
          <div class="col-sm-1" id="addLinea">
            <a href="#" class="" id="anadir" >Añadir </a>
        </div>
      </div>
    </div>    
    <hr>     
</form>

<div class="row">
    <div class="col-sm-2">
        <label  class="col-sm-12 form-control-label">Código </label>
    </div>
    <div class="col-sm-3">
        <label  class="col-sm-12 form-control-label">Producto</label>
    </div>
    <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">Cantidad (unds) </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">Importe Total </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">Coste Total </label>
          </div>
          <div class="col-sm-1">
              <label  class="col-sm-12 form-control-label derecha">PVP Total </label>
          </div>
</div>

<div id="lineasProductos">
    

</div>

 <div class="row">
        <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Total </label>
        </div>
        <div class="col-sm-3">
            <label  class="col-sm-12 form-control-label"><span id="lineasProductos"></span></label>
        </div>
      <div class="col-sm-1 derecha aj">
            <label  class="col-sm-12 form-control-label derecha aj"><span id="totalCantidad">0</span></label>
        </div>
      <div class="col-sm-1">
            <label  class="col-sm-12 form-control-label derecha"><span id="importeTotal">0.00</span></label>
        </div>
     <div class="col-sm-1">
            <label  class="col-sm-12 form-control-label derecha"><span id="costeTotal">0.00</span></label>
        </div>
      <div class="col-sm-1" >
            <label  class="col-sm-12 form-control-label derecha"><span id="pvpTotal">0.00</span></label>
        </div>
 </div>
<br>

<div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10">
      <button type="submit" class="btn btn-default" id="registrarVenta">Registrar Venta</button>
      <button type="submit" class="btn btn-default" id="cancelarVenta">Cancelar</button>
    </div>
  </div>


<!-- style -->
<style>
    
    label.derecha {
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
    
    .btn-sm {
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
       padding-left: 0px;
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
        padding-top:0px;
        padding-left:0px;
    }
     
    #totalCantidad,#importeTotal,#costeTotal,#pvpTotal{
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
    }
    
    .form-control-label{
      padding-left:0;
      text-align: left;
    }
    a#buscar{
        padding-left:5px;
    }
    #deleteLinea{
        padding-left:0;
    }

</style>

<script>
        
$(document).ready(function () {
   
   $('#vendidoA').focus()
   
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
            if(nombreId=='buscarProductos')
                filtroProductos(" ",'producto')
            if(nombreId=='buscarClientes')
                filtroClientes(" ",'cliente')
        });
     
    filtroProductos("",'producto')
    //filtroClientes(" ",'cliente')
    
    var partidas=$('.cantidad').length
    
    
    
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
    
    
    
     
    //filtrado productosFinales 
    $('#buscarClientes').click(function(){
        var filtro=$('#buscarClientes').val()
        filtroClientes(filtro,'cliente')
        $('#buscarClientes').css('color','black')
    //filtroProductos("",'producto')
    })
    
    $('#buscarC').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarClientes').val()
        filtroClientes(filtro,'cliente')
        $('#buscarClientes').css('color','black')
    })
    
    $('#buscar').click(function(){
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
    })
    
    //filtrado productosFinales 
    $('#buscarProductos').click(function(){
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
    
    function filtroClientes(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/clientes/getClientesFiltro", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             $('select#'+id+' option').remove();
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#'+id).append(option)
             })
        },
        error: function(){
                alert("Error en el proceso 'filtroClientes'. Informar");
         }
    })
    }
 
   
   function linea(codigo_producto,nombre_producto,cantidad,importe,coste,pvp){
       
       var htmlLinea='<div class="row">'
        htmlLinea+='<div class="col-sm-2">'
        htmlLinea+='    <label  class="col-sm-12 form-control-label "><span class="izquierda codigo_producto">'+codigo_producto+'</span> </label>'
        htmlLinea+='</div>'
        htmlLinea+='<input type="hidden" name[]="codigo_producto" value="'+codigo_producto+'">'
        htmlLinea+='<div class="col-sm-3">'
        htmlLinea+='    <label  class="col-sm-12 form-control-label"><span class="izquierda">'+nombre_producto+'</span> </label>'
        htmlLinea+='</div>'
        htmlLinea+='<div class="col-sm-1">'
        htmlLinea+='   <label  class="col-sm-12 form-control-label derecha aj "><span class="derecha cantidad">'+cantidad+'</span> </label>'
        htmlLinea+='</div>'
        htmlLinea+='<input type="hidden" name[]="cantidad" value="'+cantidad+'">'
        htmlLinea+='<div class="col-sm-1">'
        htmlLinea+='<label  class="col-sm-12 form-control-label derecha "><span class="derecha importe">'+importe+'</span> </label>'
        htmlLinea+='</div>'
        htmlLinea+='<input type="hidden" name[]="importe" value="'+importe+'">'
        htmlLinea+='<div class="col-sm-1">'
        htmlLinea+='<label  class="col-sm-12 form-control-label derecha"><span class="derecha coste">'+coste+'</span> </label>'
        htmlLinea+='</div>'
        htmlLinea+='<input type="hidden" name[]="coste" value="'+coste+'">'
        htmlLinea+='<div class="col-sm-1">'
        htmlLinea+='<label  class="col-sm-12 form-control-label derecha"><span class="derecha pvp">'+pvp+'</span> </label>'
        htmlLinea+='</div>'
        htmlLinea+='<input type="hidden" name[]="pvp" value="'+pvp+'">'
        htmlLinea+='<div class="col-sm-1" id="deleteLinea">'
        htmlLinea+='     <a href="#" class="eliminar"  >Eliminar </a>'
        htmlLinea+='</div>'
        htmlLinea+='</div>'
   return htmlLinea
       
   }
   
   
   $('#cancelarVenta').click(function(){
     window.location.href = "<?php echo base_url() ?>" + "index.php/gestionTablas/ventasDirectas";
   })
   
   $('#registrarVenta').click(function(){
       
       var vendidoA=$('#vendidoA').val()
       var cliente=$('#cliente').val()
       if(vendidoA=='' && cliente==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar Vendido A o seleccionar un cliente')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
       
        var concepto=$('#concepto').val()
        if(concepto==''){
             $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar el concepto.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
       
       var fecha=$('#fecha').val()
        if(fecha==''){
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
        
       var cantidad=[]
        $('.cantidad').each(function(i,e)  {
            cantidad[i]=$(this).html()-0
        })
        var precio=[]
        $('.precio').each(function(i,e)  {
            precio[i]=$(this).html()-0
        })
        var importe=[]
        $('.importe').each(function(i,e)  {
            importe[i]=$(this).html()-0
        })
        var coste=[]
        $('.coste').each(function(i,e)  {
            coste[i]=$(this).html()-0
        })
        var pvp=[]
        $('.pvp').each(function(i,e)  {
            pvp[i]=$(this).html()-0
        })
        
        var importeTotal=$('#importeTotal').html()-0
        var costeTotal=$('#costeTotal').html()-0
        var pvpTotal=$('#pvpTotal').html()-0
        
        var lineas={}
        for (var i = 0; i < codigo_producto.length; ++i){
            lineas[i]={"codigo_producto":codigo_producto[i],
                        "cantidad":cantidad[i],
                        "precio":precio[i],
                        "importe":importe[i],
                        "coste":coste[i],
                        "pvp":pvp[i]
                       }
        }
        var venta={}
        venta={
                    "vendidoA":vendidoA,
                    "id_cliente":cliente,
                    "concepto":concepto,
                    "fecha":fecha,
                    "importeTotal":importeTotal,
                    "costeTotal":costeTotal,
                    "pvpTotal":pvpTotal,
                    "lineas":lineas
                }
                
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarVentaCompleta",
            data: venta,
            success: function(datos){
               // alert('datos '+datos)
               var datos=$.parseJSON(datos)
               cambios=false
               // var direccion="<?php echo base_url() ?>ventas/"+datos
               // window.open(direccion)
                
                $('#myModal').on('hidden.bs.modal', function () {
                   window.location.href = "<?php echo base_url() ?>" + "index.php/gestionTablas/ventasDirectas";
                })    
                 $('#myModal').css('color','blue')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Registrada Venta Directa.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })        
        
        
   })
   
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
       if (cantidad==0) 
           {
                $('#myModal').css('color','red')
           $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar la cantidad.\n")
            $("#myModal").modal()  
            return false
       }
       cantidad=cantidad.replace(',','.')
       cantidad=parseInt(cantidad)
       var precio=$('#precio').val()
       
       precio=precio.replace(',','.')
       precio*=100;
       precio=parseInt(precio)
       
       cambios=true;
       var producto=$('#producto').children('option[value="'+id_producto+'"]').html()
       var codigo_producto=producto.substr(-14,13)
       var nombre_producto=producto.substr(0,producto.length-15)
       
       
        var importe=(cantidad)*(precio/100)
        var coste=(cantidad)*($('#coste').html())
        var pvp=(cantidad)*($('#pvp').html())
        
        importe=importe.toFixed(2)
        coste=coste.toFixed(2)
        pvp=pvp.toFixed(2)
     
       var htmlLinea=linea(codigo_producto,nombre_producto,cantidad,importe,coste,pvp)
       
       
       
       
       $('#lineasProductos').append(htmlLinea)
       $('#producto').val(0)
       $('#cantidad').val(0)
       $('#precio').val(0)
       
       $('.x').trigger('click')
       $('#buscarProductos_').trigger('blur')
       
       $('#coste').html('')
       $('#pvp').html('')
       
       sumaCantidades()
       sumaImportes()
       
       
   })
   
    //control cambios antes de abandonar la página
    var cambios=false
     
    var id_pe_producto
    
     
    $('select#producto').change(function(){ 
        var producto=$(this).val()
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/productos/getCostePVP/"+producto,
            data: {producto:producto},
            success: function(datos){
               //alert('datos '+datos)
               var datos=$.parseJSON(datos)
                $('#precio').val(0)
                $('#pvp').html(datos['PVP'].toFixed(2))
                $('#coste').html(datos['coste'].toFixed(2))
                
                /*
                $('#myModal').css('color','blue')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('PVP producto: <strong>'+datos['PVP']+' €/'+datos['tipoUnidad']+'</strong>')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                */
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })    
        
        
        
        $('#inventario').addClass('hide');
    
    })
    
    $('body').delegate('.eliminar','click',function()  {
        $(this).parent().parent().remove()
        sumaCantidades()
        sumaImportes()
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
        
        total=0
        $('.coste').each(function (index, value) { 
            var valor=$(value).html()
            if (valor=="") valor="0"
            var coste=parseFloat(valor)
            
            total+=coste
        });
        
        if(isNaN(total)) total=0
        $('#costeTotal').html(total.toFixed(2))
        
        total=0
        $('.pvp').each(function (index, value) { 
            var valor=$(value).html()
            if (valor=="") valor="0"
            var pvp=parseFloat(valor)
            
            total+=pvp
        });
        
        if(isNaN(total)) total=0
        $('#pvpTotal').html(total.toFixed(2))
        
        
        
        return total
    }
    
    
    
    $('body').delegate('.cantidad','blur',function()  
        {  
            cambios=true;
           var posicion=$('.cantidad' ).index( $(this))
           sumaCantidades()
            if(posicion<partidas-1) return false
            $('#cantidades').append(otro)
            partidas=$('.cantidad').length
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