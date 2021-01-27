<br />
<h3>Entradas productos - Albarán </h3>
<?php $pedidos[0]='Seleccionar un pedido..'; ?>

                
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
            <label for="pedidos" class="col-sm-12 form-control-label">Pedidos proveedor/acreedor: </label>
            
            <?php echo form_dropdown('pedido', $pedidos, '', array('disabled'=>'disabled','width'=>'100%','id' => 'pedido', 'class' => ' form-control input-sm ')); ?>
        </div>
      </div>
      
      <div class="form-group"> 
        <div class="col-sm-offset-0 col-sm-10" id="botones">
            <button type="text" class="btn btn-default" id="prepararAlbaran">Preparar Albarán</button>
            <button type="submit" class="btn btn-default" id="cancelarAlbaran">Cancelar</button>
        </div>
    </div>
  </div>
      
  <div class="hide" id="nuevoAlbaran">   
    <hr>
    <div class="row" >
        <label  class="col-sm-3 " >NUEVO Albarán</label>
        <label class="col-sm-2_ " >Núm Albarán</label>
        <input type="text" name="numAlbaran" id="numAlbaran" class="col-sm-2_ " value="" placeholder="Núm Albarán">
        <?php $hoy=date("Y-m-d"); ?>
        <label  class=" col-sm-2_ " id="n2">Fecha</label>
        <input type="date" name="fecha" id="fecha" class="col-sm-2_  " value="<?php echo $hoy ?>" placeholder="Fecha albarán">
    </div>
  </div>  
     
      
 <div class="hide" id="datosAlbaran">
      <div class="form-group row " >
        
        <label  class="col-sm-12 form-control-label">Introducir linea producto</label>
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
       <div class="col-sm-2">
              <label for="" class="col-sm-12 form-control-label">Fecha caducidad</label>
            <input type="date" name="fechaCaducidad" id="fechaCaducidad" class="input-sm form-control" placeholder="Fecha caducidad">
 
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
<div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10" id="botones">
      <button type="submit" class="btn btn-default" id="registrarAlbaran">Registrar Albarán (Entrada Tienda)</button>
      <button type="submit" class="btn btn-default" id="cancelarAlbaran2">Cancelar Albarán sin registrar</button>
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
        /* paddign-right:0px; */
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
    
    #numAlbaran,#fecha{
        border: 1px solid #cccccc;
        margin-left:10px;
        margin-right:50px;
        height: 25px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
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
    
   #nuevoAlbaran{
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
            if(nombreId=='buscarProductos'){
                filtroProductos(" ",'producto')
            }     
        });

    
    
   
    
    



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
    
    
    $('#buscar').change(function(e){
        
        $('#inventario').addClass('hide')
    })
    
    
   function lineaProducto(codigo,nombre,cantidad,tipoUnidad,fechaCaducidad){
        var linea='<div class="row">'
            linea+='<div class="col-sm-2">'
            linea+='<p  class="col-sm-12 codigo_producto">'+codigo+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-3">'
            linea+='<p  class="col-sm-12 ">'+nombre+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-2 derecha aj">'
           // linea+='<p  class="col-sm-12 derecha aj cantidad">'+cantidad+'</p>'
            linea+='<input type="text"  class="col-sm-12 derecha aj cantidad" value="'+cantidad+'">'
            linea+='</div>'
            linea+='<div class="col-sm-1 derecha aj">'
            linea+='<p  class="col-sm-12 derecha aj tipoUnidad">'+tipoUnidad+'</p>'
            linea+='</div>'
            linea+='<div class="col-sm-2 derecha aj">'
            linea+='<input type="date"  class="col-sm-12 derecha aj fechaCaducidad" value="'+fechaCaducidad+'">'
            linea+='</div>'
            linea+='<div class="col-sm-1" id="eliminarLinea">'
            linea+='<a href="#" class="eliminar"  >Eliminar </a>'
            linea+='</div>'
            linea+='</div>'
  return linea
   } 
   
   $('#producto').change(function(){
       
       var id_pe_producto=$(this).val()
       
       if (id_pe_producto==0) return false;
       $.ajax({
            url: "<?php echo base_url() ?>"+"index.php/productos/getUnidad/"+id_pe_producto,
            success: function(datos){
               var datos=$.parseJSON(datos)
               $('#tipoUnidad').html(datos)
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado Albarán. Informar");
            }
        })    
   })
   
   $('#prepararAlbaran').click(function(){
        
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
            $('#myModal').css('color','black')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No se ha seleccionado ningún pedido<br>¿Continuar introduciendo datos directamente? ')
            $("#pregunta").modal({backdrop:"static",keyboard:"false"})
            return false
        }   
        
        prepararAlbaran(true)
       
   })
   
   function prepararAlbaran(nuevo=false){
       
       
       var id_pedido=$('#pedido').val();
       $('#lineasProductos').html('')
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosPedido",
            data: {id_pedido:id_pedido},
            success: function(datos){
            //    alert (datos)
               var datos=$.parseJSON(datos)
               // alert(datos['id_proveedor'])
                //$('#proveedor').val(datos['id_proveedor'])
                $.each(datos['lineas'], function(index, value){
                    var linea=lineaProducto(value['codigo_producto'],value['nombreSinCodigo'],value['cantidad'],value['tipoUnidad'],'')
                    $('#lineasProductos').append(linea)
                })
                
                $('#datosAlbaran').removeClass('hide')
                $('#nuevoAlbaran').removeClass('hide')
                $('#numAlbaran').focus()
            
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })    
    }
   
   
   $('#continuar').click(function(){
       $('#lineasProductos').html("")
       $('#datosAlbaran').removeClass('hide')
       $('#nuevoAlbaran').removeClass('hide')
       $('#numAlbaran').focus()
      
   })

   var previous;

    $("select#proveedor").on('focus', function () {
        // Store the current value on focus and on change
        previous = this.value;
    }).change(function() {
        // Do something with the previous value after the change
        if(cambios) {
           $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Datos albarán NO registrados.<br>Cancelar albarán para cambiar a otro.')
            $("#myModal").modal() 
            $(this).val(previous)
           return false
       }
       cambioProveedor()
    });
   
   $("select#pedido").on('focus', function () {
        // Store the current value on focus and on change
        previous = this.value;
    }).change(function() {
        // Do something with the previous value after the change
        if(cambios) {
           $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Datos albarán NO registrados.<br>Cancelar albarán para cambiar a otro.')
            $("#myModal").modal() 
            $(this).val(previous)
           return false
       }
       $('#datosAlbaran').addClass('hide')
       $('#nuevoAlbaran').addClass('hide')
       
    });
   
   
    function cambioProveedor(){
       
       $('#datosAlbaran').addClass('hide')
       $('#nuevoAlbaran').addClass('hide')
       var proveedor=$('#proveedor').val()
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
   }
   
    $('#cancelarAlbaran, #cancelarAlbaran2').click(function(){
     window.location.href = "<?php echo base_url() ?>" + "index.php/gestionTablas/albaranes";
   })
   
   /*
   $('#cancelarAlbaran, #cancelarAlbaran2').click(function(){
     //window.location.href = "<?php echo base_url() ?>" + "index.php/inicioMenu";
    // location.reload();
   })
   */
   
   $('#registrarAlbaran').click(function(){
       //alert('hola')
       var proveedor=$('#proveedor').val()
       var pedido=$('#pedido').val()
       //alert('pedido '+pedido)
       
       var fecha=$('#fecha').val()
        if(fecha==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar la fecha del albarán.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
       var numAlbaran=$('#numAlbaran').val()
        if(numAlbaran==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Falta indicar el número del albarán.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
       
       
       var codigo_producto=[]
        $('.codigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).html()
            // alert(codigo_producto[i])
        })
        if(codigo_producto.length==0){
            $('#myModal').css('color','black')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('No ha introducido ningún producto.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
        }
        
       var cantidad=[]
        $('.cantidad').each(function(i,e)  {
            cantidad[i]=$(this).val()
            cantidad[i]=cantidad[i].replace(",", "");
        // alert(cantidad[i])
        })
        
        var fechaCaducidad=[]
        $('.fechaCaducidad').each(function(i,e)  {
            fechaCaducidad[i]=$(this).val()
        })
        
        var tipoUnidad=[]
        $('.tipoUnidad').each(function(i,e)  {
            tipoUnidad[i]=$(this).html()
        })
        
        var lineas=[]
        for (var i = 0; i < codigo_producto.length; ++i){
            //alert(cantidad[i])
            lineas[i]={"codigo_producto":codigo_producto[i],
                        "cantidad":cantidad[i],
                        "tipoUnidad":tipoUnidad[i],
                        "fechaCaducidad":fechaCaducidad[i]
                       }
            // alert(lineas[i]['codigo_producto']+' '+lineas[i]['cantidad'])           
        }
        var venta={}
        venta={     "numAlbaran":numAlbaran,
                    "proveedor":proveedor,
                    "pedido":pedido,
                    "fecha":fecha,
                    "lineas":lineas
                }
         //alert('venta '+venta['lineas'])       
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarAlbaran",
            data: venta,
            success: function(datos){
              //alert(datos)
               var datos=$.parseJSON(datos)
               // alert('datos2 '+datos)
               cambios=false
               // var direccion="<?php echo base_url() ?>ventas/"+datos
               // window.open(direccion)
                
                $('#myModal').on('hidden.bs.modal', function () {
                   window.location.href = "<?php echo base_url() ?>" + "index.php/gestionTablas/albaranes";
                })   
                $('#myModal').css('color','blue')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Albarán registrado y entrado en tienda.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas albarán. Informar");
            }
        })        
        
        
   })
   
   function today(){
     var today = new Date();
     var dd = today.getDate();
     var mm = today.getMonth()+1; //January is 0!
     var yyyy = today.getFullYear();

     if(dd<10) {
         dd='0'+dd
        } 

        if(mm<10) {
            mm='0'+mm
        } 
    return yyyy+'-'+mm+'-'+dd    
    }
   
   $('#continuarCaducado').click(function(){
       anadir()
    })
   
   $('body').delegate('.fechaCaducidad','blur',function()  
        {  
       var diff = new Date($(this).val()) - new Date(today());
       
       if(diff<7*24*60*60*1000){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Producto YA CADUCADO o CADUCA en menos de UNA SEMANA.<br>Si no lo desea introducir, elimine la línea<br>')
            $("#myModal").modal({backdrop:"static",keyboard:"false"})
            return false
        }
        }); 
   
   
   
   function anadir(){
       var id_producto=$('#producto').val()
       
       var fechaCaducidad=$('#fechaCaducidad').val()
        var cantidad=$('#cantidad').val()
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
       //para añadir todos los pesos del código
       
       $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/productos/getProductoPesos/"+id_producto, 
        data:{id_pe_producto:id_producto},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            $.each(datos, function(index, value){
               console.log(value['codigo_producto'])
               var cantidadEntrada=0
               var fechaCaducidadEntrada=''
               if(value['id_pe_producto']==id_producto) {
                   cantidadEntrada=cantidad
                   fechaCaducidadEntrada=fechaCaducidad
               }
               var peso_real=(value['peso_real']/1000).toLocaleString('en-US', {minimumFractionDigits: 3})+' Kg'
               var linea=lineaProducto(value['codigo_producto'],value['nombre']+' - '+peso_real,cantidadEntrada,value['tipoUnidad'],fechaCaducidadEntrada)
               $('#lineasProductos').append(linea)
             })
            
       
            $('#producto').val(0)
            $('#cantidad').val(0)
            $('#precio').val(0)
            $('#fechaCaducidad').val('')
       
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
       
       
       
       
       
     //  $('.x').trigger('click')
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
       if (!cantidad) 
           {
            $('#myModal').css('color','red')   
           $('.modal-title').html('Información ')
            $('.modal-body>p').html("Falta indicar la cantidad.\n")
            $("#myModal").modal()  
            return false
       }
       var fechaCaducidad=$('#fechaCaducidad').val()
       var hoy=today()
       
       var date1=new Date(fechaCaducidad)
       var date2=new Date(hoy)
       var diff = date1 - date2;
      // alert(diff)
      
       if(diff<7*24*60*60*1000){
           $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Producto YA CADUCADO o CADUCA en menos de UNA SEMANA.<br><br>¿Desea continuar?')
            $("#preguntaCaducado").modal({backdrop:"static",keyboard:"false"})
            //$('#tipo').html('NUEVO pedido')
            //$('#numPedido').html($('#siguiente').val())  
            return false
        }
        
        
       anadir()
       
       
   })
   
   
   
   
    
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    
     
    var id_pe_producto
    
    
     filtroProductos("",'producto')
     
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
    
    $('select#producto').change(function(){ $('#inventario').addClass('hide');})
    
    $('body').delegate('.eliminar','click',function()  {
        $(this).parent().parent().remove()
    })
   
    $('body').delegate('.cantidad ,#numAlbaran, #fecha','keyup',function()  {
        cambios=true
    })
   
    $('body').delegate('#fecha','change',function()  {
        cambios=true
    })
   
    
    
    
    
    
    
    
    
    
   
   
   
   window.onbeforeunload=confirmExit
     
     function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han registrado.'
           
        }
    }
    
   
    
})
</script>