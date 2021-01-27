
<br />

<h4>Transformaciones </h4>
<input type="hidden" id="siguiente" value="<?php echo $siguiente; ?>"> 
                
  <div class="form-horizontal">
      <div class="row">
          <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro transfor. </label>
            <div class="input-group">
                <input type="text" id="buscarTransformaciones" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscarTransformacion" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
         
          <div class="col-sm-5">
            <label for="pedidos" class="col-sm-12 form-control-label">Transformación. </label>
            
            <?php echo form_dropdown('transformacion', array(), '', array('width'=>'100%','id' => 'transformacion', 'class' => ' form-control input-sm ')); ?>
        </div>
          <div class="col-sm-5">
            <label  class="col-sm-12 form-control-label">Nueva Transformación </label>
            <input  type="text"  id="nuevaTransformacion" class=" input-sm" value="" >    
        </div>
          </div>
      
      <div class="row hide">
      
          <div class="col-sm-3 ">
            <label  class="col-sm-12 form-control-label">Concepto </label>
            <input type="text"  id="concepto" class=" input-sm" value="" >    
        </div>
          
          <div class="col-sm-2">
            <label for="Fecha" class="col-sm-12 form-control-label">Fecha </label>
            <?php $hoy=date("Y-m-d"); ?>
        <input type="date"  id="fecha" name="fecha" class="   input-sm" value="<?php echo $hoy ?>" placeholder="Fecha"> 
        </div>
      </div>
      
      <div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10" id="botones">
      <button type="submit" class="btn btn-default" id="prepararTransformacion">Preparar Transformación</button>
      <button type="submit" class="btn btn-default" id="cancelarTransformacion">Cancelar</button>
    </div>
  </div>
  </div>
<div id="panelTransformacion">    
<div class="hide" id="nuevaInfoTransformacion">   
    <hr>
  
    <form class="form-inline ">
        <div class="row " >
            <div class="form-group">
                <label class="col-sm-2_ "id="labelNumTransformacion">Núm Transf. <span id="numTransformacion"><?php echo $siguiente?></span></label>
            </div>      
                <?php $hoy=date("Y-m-d"); ?>
            <div class="form-group">      
                <label  class="col-sm-1_" id="f1___">Fecha</label>
                <input  class="col-sm-2_" type="date" name="fecha" id="fecha"  value="<?php echo $hoy ?>" placeholder="Fecha pedido">
            </div>  
            <div class="form-group"> 
                <label  class="col-sm-1__" id="labelLoteOrigen_">Lote Origen</label>
                <input  class="col-sm-2__ " type="text" name="loteOrigen" id="loteOrigen"  value="" placeholder="Lote Origen">
            </div>      
            <div class="form-group">    
                <label  class="col-sm-1_" id="labelLoteFinal_">Lote Final</label>
                <input  class="col-sm-2_ " type="text" name="loteFinal" id="loteFinal"  value="" placeholder="Lote Final">
            </div>
        </div>

        <div class="row " >
        <div class="form-group">
        <label  class="col-sm-1_" id="labelConcepto">Concepto</label>
        <input  class="col-sm-2_ " type="text" name="conceptoTransformacion" id="conceptoTransformacion"  value="" placeholder="Concepto Transformación">
        </div>
        
        <div class="form-group">
        <label  class="col-sm-2_" id="labelTransPatron">Trans Patrón</label>
        <input  class="col-sm-1_" type="checkbox" name="patron" id="patron"  value="" placeholder="Patrón">
        </div>
    </div>
    </form>
    
   
    
</div>  
</div>       
 <div class="hide" id="datosTransformacion">
      <div class=" row " >
        <label  class="col-sm-12 form-control-label">Introducir linea producto (Cantidad >0 -> Producido / Cantidad <0 -> Consumido)</label>
      </div>
     
      <div class="row" style='background-color:lightyellow;border-top:1px solid black'>
          <div class="col-sm-2">
            <label  >Filtro productos </label>
          </div>
          <div class="col-sm-3">
            <label  >Producto</label>
          </div>
          <div class="col-sm-1">
            <label  >Cantidad</label>
          </div>
          <div class="col-sm-1">
            <label  >Und</label>
          </div>
          <div class="col-sm-1">
            <label style='font-size:0.75em;' >% perd. peso</label>
          </div>
          <div class="col-sm-2">
            <label  >Fecha caducidad</label>
          </div>
          
      </div> 
     
     <div class='row' style='background-color:lightyellow;border-bottom:1px solid black'>
         <div class="col-sm-2">
             <div class="input-group">
                 <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                 <div class="input-group-btn">
                     <button class="btn btn-default btn-sm" id="buscar" ><i class="glyphicon glyphicon-search"></i></button>
                 </div>
             </div>
         </div>  
         <div class="col-sm-3">
             <?php echo form_dropdown('producto', $optionsProductos, '', array('width' => '100%', 'id' => 'producto', 'class' => ' form-control input-sm ')); ?>
         </div>
         <div class="col-sm-1">
             <input contextmenu="colorMenu" type="text" name="cantidad" id="cantidad" class="input-sm form-control" placeholder="cantidad">
         </div>
         <div class="col-sm-1">
             <p for="" class="col-sm-12 form-control-label_"  id="tipoUnidad"></p>
         </div>
         <div class="col-sm-1">
             <input contextmenu="colorMenu" type="text" name="perdida" id="perdida" class="input-sm form-control" placeholder="% perd peso">
         </div>      
         <div class="col-sm-2">
             <input type="date" name="fechaCaducidad" id="fechaCaducidad" class="input-sm form-control" placeholder="Fecha caducidad" value="<?php echo date('Y-m-d', strtotime(' +6 months')); ?>">
         </div> 
         <div class="col-sm-1" id="addLinea_">
             <a href="#" class="" id="anadir" >Añadir </a>
        </div>
     </div> 
     
    
     <br>
     <div id='contenidoTransformacion' class='hide'>
         
      <div class="row" >
          <div class="col-sm-1">
            <label  ></label>
          </div>
          <div class="col-sm-2">
            <label  >Código 13 </label>
          </div>
          <div class="col-sm-3" >
            <label  >Nombre Producto</label>
          </div>
          <div class="col-sm-1">
            <label  >Cantidad</label>
          </div>
          <div class="col-sm-1">
            <label  >Und</label>
          </div>
          <div class="col-sm-1">
            <label style='font-size:0.75em;' >% perd. peso</label>
          </div>
          <div class="col-sm-2">
            <label  >Fecha caducidad</label>
          </div>
          
      </div> 
     

<div id="lineasProductos">
    
    
    

</div>
<br>
<div  id="preciosCompra" <?php if($this->session->categoria  ==2) echo 'class="hide"' ?>>
    
</div>
<br> Registrar precios compra calculados: <input type='checkbox'  id='registrarPrecioNuevo'><br>
<br>
<div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10">
      <button type="submit" class="btn btn-default" id="registrarTransformacion">Registrar Transformación (Salidas/Entrada Tienda)</button>
      <button type="submit" class="btn btn-default" id="cancelarTransformacion2">Cancelar</button>
      
    </div>
  </div>
<br><br>
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
    .Neutro{
        color:grey;
    }
    .Consumido{
        color:red;
    }
     .Producido{
        color:blue;
    }
    
    
      .btn-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }

     #fecha,#concepto,#conceptoTransformacion,#loteOrigen,#loteFinal{
        border: 1px solid #cccccc;
        margin-left:10px;
        margin-right:50px;
        height: 25px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    #labelNumTransformacion{
        margin-left: 40px;
    }
    #labelConcepto{
        margin-left: 40px;
    }
    #labelloteOrigen{
        margin-left: 40px;
    }
    #labelloteFinal{
        margin-left: 40px;
    }
    #numTransformacion{
        margin-left:10px;
        margin-right:50px;
    }
    #patron{
        margin-left: 15px;
    }
    #nuevaInfoTransformacion{
        border-bottom:  2px solid lightgray;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    .fechaCaducidad{
        padding-left: 0px;
        padding-right: 0px;
    }
    a.eliminar{
        padding-left: 0px;
    }
    .eliminarLinea{
        padding-left: 0px;
    }
    .izquierda{
        text-align: left;
    }
    td,th{
        padding-left:10px;
    }
    td{
        text-align: left;
    }

    h4{
        font-weight: bold;
    }
    
   .modal-content{
        width:750px;
        margin-left: -105px;
    }
    
    #conceptoTransformacion{
        padding-left: 5px;
        width:309px;
    }
    
      .modal-content{
    width:1000px;
    left:-52px;
      }
</style>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.4/numeral.min.js"></script>

<script>
        
$(document).ready(function () {
    
    filtroProductos("",'producto')
    filtroTransformaciones("",'transformacion')
    //$('#preciosCompra').addClass('hide')
    
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
            if(nombreId=='buscarTransformaciones')
                filtroTransformaciones(" ",'transformacion')
        });
    
    
    
    $('#transformacion').change(function(){
        $('#datosTransformacion').addClass('hide');
        $('#panelTransformacion').addClass('hide');
        $('#lineasProductos').html('')
        $('#preciosCompra').html('')
        $('#prepararTransformacion').removeAttr('disabled')
        //alert('hola '+$(this).val())
        if($(this).val()>0){ 
            $('#nuevaTransformacion').parent().addClass('hide')
            
        }
        else {
            $('#nuevaTransformacion').parent().removeClass('hide')
        }
    })
    
    
    $('#nuevaTransformacion').change(function(){
        if($(this).val()!="") 
            $('#transformacion').attr('disabled','disabled')
        //alert('hola '+$(this).val())
        else
            $('#transformacion').removeAttr('disabled')
        
    })
    
    
    $('#prepararTransformacion').click(function(){
       // alert('hola ')
      
       $('#panelTransformacion').removeClass('hide');
       $('#preciosCompra').html('')
       var id_transformacion=$('#transformacion').val();
       //alert('id_transformacion '+id_transformacion)
       var nuevaTransformacion=$('#nuevaTransformacion').val();
       nuevaTransformacion=nuevaTransformacion.trim();
       
       if(id_transformacion==0 && nuevaTransformacion==""){
            $('#myModal').css('color','red')
           $('.modal-title').html('Información')
            $('.modal-body>p').html('Se debe seleccionar una transformación o poner un nombre en Nueva transformación ')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            return false;
       }
       $('#nuevaInfoTransformacion').removeClass('hide') 
       var nombre=""
       if(id_transformacion==0) {
           $('#datosTransformacion').removeClass('hide')
           return
       }
          
       /*
       if(id_transformacion==0){
        $('.modal-title').html('Información')
        $('.modal-body>p').html('No se ha seleccionado ninguna transfomación<br>¿Continuar introduciendo nueva Transformación? ')
        $("#pregunta").modal({backdrop:"static",keyboard:"false"})
        return false
    }   
        */
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getTransformacion",
            data: {id:id_transformacion,nombre:nombre},
            success: function(datos){
               // alert (datos)
               var datos=$.parseJSON(datos)
               // alert(datos['id_proveedor'])
               // $('#transformacion').val(datos['nombre_transformacion'])
                $.each(datos['lineas'], function(index, value){
                    var fecha_caducidad=''
                    if(value['cantidad']>0) fecha_caducidad=datos['fechaMas6Meses']
                    var linea=lineaProducto(value['codigo_producto'],value['nombre'],value['cantidad']/1000,value['tipoUnidad'],fecha_caducidad,value['perdida']/1000)
                    $('#lineasProductos').append(linea)
                })
                $('#contenidoTransformacion').removeClass('hide')
                
            $('#prepararTransformacion').attr('disabled')    
            $('#datosTransformacion').removeClass('hide')
            $('#nuevaInfoTransformacion').removeClass('hide') 
            
            verPreciosCompra()
           
            $("#conceptoTransformacion").focus();
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })      
   })
  
    $('#cancelarTransformacion, #cancelarTransformacion2').click(function(){
     var cancelarTransformacion=$('#siguiente').val()
     //alert(cancelarTransformacion)
     $.ajax({
         type: "POST",
         url: "<?php echo base_url() ?>"+"index.php/compras/cancelarTransformacion/"+cancelarTransformacion,
         data: {},
         success: function(datos){
               //alert (datos)
               //var datos=$.parseJSON(datos)
               window.location.href = "<?php echo base_url() ?>" + "index.php/gestionTablas/transformaciones";
            },
            error: function(){
                alertaError("Información importante","Error en el proceso Cancelar Transformacion");
            }
     })
     
   })
    
    
    var lineasProductos=0
    var totalCantidad=0
    var importeTotal=0
    
    
   function lineaProducto(codigo,nombre,cantidad,tipoUnidad,fechaCaducidad,perdida){
            
            var disabled='disabled="disabled" style="background-color:lightgrey" '
            var tipoProducto='Neutro'
            if(cantidad<0) {
                tipoProducto='Consumido'
            }
            if(cantidad>0) {
                tipoProducto='Producido'
                disabled=''
            }
   
            
         
        var linea='<div class="row '+tipoProducto+'">'
        
            linea+='<div class="col-sm-1" id="tipoProductoTrans" style="padding-left:0px;padding-right:0px; text-align: left;">'
            linea+='<p style="padding-left:0px;padding-right:0px; text-align: left;"  class="tipoProductoTrans">'+tipoProducto+' </p>'
            linea+='</div>'
            
            linea+='<div class="col-sm-2 ">'
            linea+='<p  class="codigo_producto">'+codigo+'</p>'
            linea+='</div>'
            
            linea+='<div class="col-sm-3" >'
            linea+='<p  class="nombre" style="padding-left:0px;">'+nombre+'</p>'
            linea+='</div>'
            
            linea+='<div class="col-sm-1 derecha aj tipoProducto">'
            linea+='<input type="text"   class="input-sm form-control derecha aj cantidad '+tipoProducto+'"  value="'+cantidad+'">'
            linea+='</div>'
            
            linea+='<div class="col-sm-1 " >'
            linea+='<p  class="tipoUnidad" >'+tipoUnidad+'</p>'
            linea+='</div>'
            var disabled2=''
            var valPerdida=numeral(perdida).format('0.00')
            if(tipoUnidad!='Kg'){
                disabled2='disabled="disabled"'
                valPerdida=""
             }
            linea+='<div class="col-sm-1  tipoProducto">'
            linea+='<input '+disabled2+' style="text-align:right;" type="text"  class="input-sm form-control derecha aj perdida '+tipoProducto+'" value="'+valPerdida+'">'
            linea+='</div>'
            
            linea+='<div class="col-sm-2 ">'
            linea+='<input style="padding-left:5px;" type="date"  class="input-sm form-control fechaCaducidad '+tipoProducto+'" value="'+fechaCaducidad+'" '+disabled+' >'
            linea+='</div>'
            
           
            linea+='<div class="col-sm-1 eliminarLinea">'
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
               if(datos!="Kg") {
                   $('#perdida').val('')
                   $('#perdida').attr('disabled','disabled')
               }
               else{
                   $('#perdida').removeAttr('disabled')
               }
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })    
   })
   
    
   $('#continuar').click(function(){
       $('#lineasProductos').html("")
       $('#datosTransformacion').removeClass('hide')
   })
   
   $('#pedido').change(function(){
       $('#datosTransformacion').addClass('hide')
       $('#lineasProductos').html("")
   })
   
   $('#proveedor').change(function(){
       $('#datosTransformacion').addClass('hide')
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
   
   $('#cantidad').blur(function(){
        if($(this).val()<0){
            $('#fechaCaducidad').val('')
            $('#fechaCaducidad').attr('disabled','disabled')
            $('#fechaCaducidad').css('background-color','lightgrey')
            if($('#tipoUnidad').html()=='Kg') $('#perdida').removeAttr('disabled')  
        }
        else{
            $('#fechaCaducidad').removeAttr('disabled')
            $('#fechaCaducidad').css('background-color','')
            $('#perdida').attr('disabled','disabled')  
            $('#perdida').val('')  
        }
   })
    
   function lineasPatron(){
       $('.cantidad').val("0")
           $('.cantidad').attr('disabled','disabled')
           $('.cantidad').removeClass('Consumido')
           $('.cantidad').removeClass('Producido')
           $('.cantidad').addClass('Neutro')
           $('.fechaCaducidad').attr('disabled','disabled')
           $('.fechaCaducidad').removeClass('Consumido')
           $('.fechaCaducidad').removeClass('Producido')
           $('.fechaCaducidad').addClass('Neutro')
           
           $('.cantidad').parent().parent().attr('class','row Neutro')
           $('.tipoProductoTrans').html('Neutro')
    }
    function lineasNoPatron(){
      
           $('.cantidad').removeAttr('disabled')
           
           $('.fechaCaducidad').removeAttr('disabled')
          
    }
    
   $('#patron').click(function(){
      $('#registrarPrecioNuevo').removeAttr('checked')
       
       if($(this).is(':checked')) {
           $('#registrarTransformacion').html("Registrar PATRON")
           $('#registrarPrecioNuevo').attr('disabled','disabled')
           lineasPatron()
        }
        else{
           $('#registrarTransformacion').html("Registrar Transformación (Salidas/Entrada Tienda)") 
           $('#registrarPrecioNuevo').removeAttr('disabled')
           lineasNoPatron()
        }
       verPreciosCompra()
   }) 
    
    $('#registrarPrecioNuevo').change(function(){
        if($(this).is(':checked')) {
           // console.log('registrarPrecioNuevo ckecked')
           $('#registrarTransformacion').html('<strong style="color:blue">Registrar PRECIOS y actualizar STOCKS</strong>')
            verPreciosCompra()
           // $('#preciosCompra').removeClass('hide')
            window.location.hash = "registrarTransformacion";
            $('#registrarTransformacion').focus();
        }
        else{
            $('#registrarTransformacion').html('Registrar Transformación (Salidas/Entrada Tienda)')
           // $('#preciosCompra').addClass('hide')
            window.location.hash = "registrarTransformacion";
            $('#registrarTransformacion').focus();
        }
    })
    
    
   function verPreciosCompra(){
       
       $('#preciosCompra').html('')
       
       var codigo_producto=[]
        $('.codigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).html()
        })
        var cantidad=[]
        $('.cantidad').each(function(i,e)  {
            cantidad[i]=$(this).val()
            if($(this).val()=='' || $(this).val()=='-') cantidad[i]=0
        })
       
        
        
        var perdida=[]
        $('.perdida').each(function(i,e)  {
            perdida[i]=$(this).val()
            if($(this).val()=='' || $(this).val()=='-') perdida[i]=0
        })
        var fechaCaducidad=[]
        $('.fechaCaducidad').each(function(i,e)  {
            fechaCaducidad[i]=$(this).val()
            
        })
        
        /*
        var precioNuevo=[]
        $('.precioNuevo').each(function(i,e)  {
            precioNuevo[i]=$(this).html()
            if($(this).html()=='' || $(this).html()=='---') precioNuevo[i]=0
            console.log(precioNuevo[i])
        })
        */
        var lineas={}
        
        for (var i = 0; i < codigo_producto.length; i++){
                lineas[i]={"codigo_producto":codigo_producto[i],
                        "cantidad":cantidad[i],
                        "perdida":perdida[i],
                        "fechaCaducidad":fechaCaducidad[i]
                       }
        }
        var patron=0
       if($('#patron').is(':checked')) patron=1
       
        venta={}
        venta={"lineas":lineas,'patron':patron}
        
        
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/calculoPreciosCompraTransformaciones",
            data: venta,
            success: function(datos){
               // alert (datos)
               if(datos=='false'){
                  // alert("datos=='false'")
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información')
                    $('.modal-body').html('La transformación produce varios productos que NO son del mismo grupo')
                   // $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                    $("#myModal").modal() 
                    datosCorrectos=false
                    return false;
                }
            var datos=$.parseJSON(datos)
             $('#preciosCompra').html('')
            $('#preciosCompra').append(datos);  
            datosCorrectos=true  
            window.location.hash = "registrarTransformacion";  
            },
            error: function(){
                alertaError("Información importante","Error en el proceso calculoPreciosCompra. Informar");
            }
        })        
   
   }
 
    function tablaTransformacion(datos){
       var patron=0
       if($('#patron').is(':checked')) patron=1
        var tabla="<table class='table' >"
                tabla+="<tr><td class='col-md-2'>Transformación</td>"
                tabla+='<td class="col-md-10">'+datos['nombre_transformacion']+'</td><td width="50"> </td><td width="50"> </td></tr>'
                tabla+="<tr><td class='col-md-2'>Concepto</td>"
                tabla+='<td class="col-md-10">'+datos['concepto']+'</td>'
                tabla+="<tr><td class='col-md-2'>Lote Origen</td>"
                tabla+='<td class="col-md-10">'+datos['lote_origen']+'</td>'
                tabla+="<tr><td class='col-md-2'>Lote Final</td>"
                tabla+='<td class="col-md-10">'+datos['lote_final']+'</td></tr>'
                tabla+="<tr><td class='col-md-2'>Fecha</td>"
                if (typeof datos['fecha'] !== "undefined")
                    tabla+='<td class="col-md-10">'+datos['fecha'].substr(8,2)+'/'+datos['fecha'].substr(5,2)+'/'+datos['fecha'].substr(0,4)+'</td><td></td><td></td></tr>'
                else
                    tabla+='<td class="col-md-10"></td><td></td><td></td></tr>'
                tabla+='</table>'
                tabla+='Detalles'
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left col-md-2'>Código</th>"
                tabla2+="<th class='text-left col-md-5'>Producto</th>"
                tabla2+='<th class="text-right col-md-2">Cantidad</th>'
                tabla2+='<th class="text-right col-md-1">Und</th>'
                tabla2+='<th class="text-right col-md-1">% Perdida Peso</th>'
                tabla2+='<th class="text-right col-md-1">Peso Original</th>'
                tabla2+='<th class="text-right col-md-2">Caducidad</th>'
                tabla2+='<th class="text-right col-md-1">Precio Actual</th>'
                tabla2+='<th class="text-right col-md-1">Precio Calculado</th>'
                tabla2+='</tr>'
                
                for(var i=0;i<datos['lineas'].length;i++){
                    if(patron || datos['lineas'][i]['cantidad']!=0){
                    var cantidad=datos['lineas'][i]['cantidad']/1000;
                    if(true || cantidad!=0){
                        var cantOriginal="";
                        var perdida="";
                        if(datos['lineas'][i]['tipoUnidad']=='Und'){
                            cantidad=cantidad.toFixed(0);
                        }
                        else{
                            cantidad=cantidad.toFixed(3);
                            cantOriginal=datos['lineas'][i]['cantidad']/1000+datos['lineas'][i]['cantidad']/1000*datos['lineas'][i]['perdida']/100
                            perdida=numeral(datos['lineas'][i]['perdida']).format('0.00')
                        }
                        var color='grey'
                        if(cantidad<0) color='red'
                        if(cantidad>0) color='blue'
                        tabla2+="<tr style='color:"+color+";'><td class='text-left'>"+datos['lineas'][i]['codigo_producto']+"</td>"
                        tabla2+="<td class='text-left'>"+datos['lineas'][i]['nombre']+"</td>"
                        tabla2+="<td class='text-right'>"+cantidad+"</td>"
                        tabla2+="<td class='text-right'>"+datos['lineas'][i]['tipoUnidad']+"</td>"
                        tabla2+="<td class='text-right'>"+perdida+"</td>"
                        tabla2+="<td class='text-right'>"+cantOriginal+"</td>"
                        tabla2+="<td class='text-right'>"+datos['lineas'][i]['fecha_caducidad']+"</td>"
                        tabla2+="<td class='text-right'>"+datos['lineas'][i]['precioActual']+"</td>"
                        tabla2+="<td class='text-right'>"+datos['lineas'][i]['precioNuevo']+"</td>"
                        tabla2+="</tr>"
                    } 
                    } 
                    
                }
       
                tabla2+='</table>'
                return tabla+tabla2
    }
   
   $('button.exito').click(function(){
       window.location.assign("<?php echo base_url() ?>"+"index.php/gestionTablas/transformaciones");
   })
   
   var venta={}
   
   $('#confirmarTransformacion').click(function(){
         //var preciosCompra=$('#preciosCompra').html()
        var preciosNuevos={}
        
        $('.precioNuevo').each(function(i,e)  {
            if($('#registrarPrecioNuevo').is(':checked')){
                $('.precioNuevo').each(function(i,e)  {
                    preciosNuevos[i]=$(this).html()
                }) 
            }else{
                $('.precioNuevo').each(function(i,e)  {
                    preciosNuevos[i]="--"
                }) 
            }
            //console.log(preciosNuevos[i])
        })
        
        var preciosActuales={}
        $('.precioActual').each(function(i,e)  {
            
            preciosActuales[i]=$(this).html()
        })
        var fechasCaducidades={}

        
        var precios={"venta": venta, "preciosNuevos":preciosNuevos,"preciosActuales":preciosActuales}
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/grabarTransformacion",
            data: precios,
            success: function(datos){
                //alert('datos confirmacion '+datos)
            var datos=$.parseJSON(datos)
               cambios=false
               
                 
                $('#myModalExito').css('color','blue')
                $('.modal-title').html('Información')
                $('.modal-body').html('Transformación registrada y entrado en tienda.')
                $("#myModalExito").modal({backdrop:"static",keyboard:"false"}) 
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas transformaciones. Informar.");
            }
        })    
        
    })
   
   var datos=[]
   
   var datosCorrectos=false;
   
   $('#registrarTransformacion').click(function(){
       if(!datosCorrectos) {
           $('#myModal').css('color','red')
           $('.modal-title').html('Información')
           $('.modal-body').html('Revisar datos introducidos. Algún/os NO son correctos')
           $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
           return false;
        }
       $(this).attr('disabled','disabled');
       var transformacion=$('#transformacion').val()
       if(transformacion==0) transformacion=$('#nuevaTransformacion').val()
       else {
           transformacion=$('#transformacion').children('option[value="'+transformacion+'"]').html()
           var n = transformacion.lastIndexOf("(");
           transformacion=transformacion.substr(0, n);
       }
       
       var concepto=$('#conceptoTransformacion').val()
       var patron=0
       if($('#patron').is(':checked')) patron=1
      // alert('patron '+patron)
       if(concepto==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('Falta indicar el concepto de la transformación.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"})
            $(this).removeAttr('disabled');
            return false;
        }
        var loteOrigen=$('#loteOrigen').val()
        if(loteOrigen==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('Falta indicar el lote origen de la transformación.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"})
            $(this).removeAttr('disabled');
            return false;
        }
        var loteFinal=$('#loteFinal').val()
        if(loteFinal==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('Falta indicar el lote final de la transformación.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"})
            $(this).removeAttr('disabled');
            return false;
        }



       var id_transformacion=$('#numTransformacion').html()

        var fecha=$('#fecha').val()
       if(fecha==''){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('Falta indicar la fecha de la transformación.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled');
            return false;
        }
       
       var precioActualTablaPrecios=[]
        $('.precioActual').each(function(i,e)  {
            precioActualTablaPrecios[i]=$(this).html()
        })
       
       var precioNuevoTablaPrecios=[]
        //comprueba si se quieren grabar los nuevos precios
        if($('#registrarPrecioNuevo').is(':checked')){
            $('.precioNuevo').each(function(i,e)  {
            precioNuevoTablaPrecios[i]=$(this).html()
        }) 
        }else{
            $('.precioNuevo').each(function(i,e)  {
                precioNuevoTablaPrecios[i]="--"
            }) 
        }
       
       var cantidadNegativa=false
       var cantidadPositiva=false
       var cantidad=[]
       var precioActual=[]
       var precioNuevo=[]
       var contTablaPrecios=0;
        $('.cantidad').each(function(i,e)  {
            cantidad[i]=$(this).val()
            if(cantidad[i]>0) cantidadPositiva=true
            if(cantidad[i]<0) cantidadNegativa=true
            if(cantidad[i]!=0){
                precioActual[i]=precioActualTablaPrecios[contTablaPrecios]
                precioNuevo[i]=precioNuevoTablaPrecios[contTablaPrecios]
                contTablaPrecios++
            }
            else{
                precioActual[i]=0
                precioNuevo[i]=0
            }
        })
        /*
        if($('#registrarPrecioNuevo').is(':checked')) {
            $(cantidad).each(function(i,e){
                if(cantidad[i]<0 && precioActual[i]==0){
                    alert(cantidad[i])
                    alert(precioActual[i])
                    
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información')
                    $('.modal-body').html('Revisar datos introducidos. Algún producto consumido tiene precio 0')
                    $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
                    $(this).removeAttr('disabled');
                    
                    return false;
                }

            })
        }   
        */
        if(patron==0 && !cantidadNegativa){
            //alert('cantidadNegativa')
             $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('<p>No se ha introducido ningún producto CONSUMIDO (con valor negativo).</p><p>Si deseas registrar un patrón seleccione Trans Patrón.</p>')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled');
            return;
        }
        if(patron==0 && !cantidadPositiva){
            //alert('cantidadPositiva')
             $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('<p>No se ha introducido ningún producto PRODUCIDO (con valor positivo).</p><p>Si deseas registrar un patrón seleccione Trans Patrón.</p>')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled');
            return ;
        }
       
       var codigo_producto=[]
        $('.codigo_producto').each(function(i,e)  {
            codigo_producto[i]=$(this).html()
        })
        
        var nombre=[]
        $('.nombre').each(function(i,e)  {
            nombre[i]=$(this).html()
        })
        var tipoUnidad=[]
        $('.tipoUnidad').each(function(i,e)  {
            tipoUnidad[i]=$(this).html()
        })
        
        if(codigo_producto.length==0){
             $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body').html('No ha introducido ningún producto.')
            $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
            $(this).removeAttr('disabled');
            return false;
        }
        
       
        
        var perdida=[]
        $('.perdida').each(function(i,e)  {
            perdida[i]=$(this).val()
        })
        
        
        
        
        
        var fechaCaducidad=[]
        $('.fechaCaducidad').each(function(i,e)  {
            fechaCaducidad[i]=$(this).val()
             
        })
        
        
        var lineas={}
        for (var i = 0; i < codigo_producto.length; i++){
            //console.log('precioNuevo[i] = '+precioNuevo[i])
            if(patron || cantidad[i]!=0)
                lineas[i]={"codigo_producto":codigo_producto[i],
                            "cantidad":cantidad[i],
                            "fechaCaducidad":fechaCaducidad[i],
                            "precioNuevo":precioNuevo[i],
                            "precioActual":precioActual[i],
                            "perdida":perdida[i]
                           }
        }
        
        var preciosCompra=$('#preciosCompra').html()
        
        venta={
                    "transformacion":transformacion,
                    "id_transformacion":id_transformacion,
                    "patron":patron,
                    "concepto":concepto,
                    "lote_origen":loteOrigen,
                    "lote_final":loteFinal,
                    "fecha":fecha,
                    "lineas":lineas,
                    "preciosCompra":preciosCompra
                }
                
          datos=[]
          
          datos['nombre_transformacion']=transformacion;
          datos['id_transformacion']=id_transformacion;
          datos['concepto']=concepto;
          datos['lote_origen']=loteOrigen;
          datos['lote_final']=loteFinal;
          datos['fecha']=fecha;
          datos['lineas']=[]
          
          for (var i = 0; i < codigo_producto.length; i++){
              
                datos['lineas'][i]=[]
               
                    datos['lineas'][i]['codigo_producto']=codigo_producto[i]
                    datos['lineas'][i]['nombre']=nombre[i]
                    datos['lineas'][i]['tipoUnidad']=tipoUnidad[i]

                    datos['lineas'][i]['cantidad']=cantidad[i]*1000
                    datos['lineas'][i]['fecha_caducidad']=fechaCaducidad[i]
                    if(datos['lineas'][i]['fecha_caducidad']!="")
                    datos['lineas'][i]['fecha_caducidad']=datos['lineas'][i]['fecha_caducidad'].substr(8,2)+'/'+datos['lineas'][i]['fecha_caducidad'].substr(5,2)+'/'+datos['lineas'][i]['fecha_caducidad'].substr(0,4)
                    console.log(datos['lineas'][i]['fecha_caducidad'])
                    datos['lineas'][i]['precioNuevo']=precioNuevo[i]
                    datos['lineas'][i]['precioActual']=precioActual[i]
                    datos['lineas'][i]['perdida']=perdida[i]
               
          }
          var infoPrecios=""
          if($('#registrarPrecioNuevo').is(':checked')) infoPrecios="<br><h4>Con registro Precios Calculados</h4>"
           $('#confirm-transformacion').css('color','black')
          $('.modal-title').html('Transformación '+datos['id_transformacion'])
          $('.modal-body').html(tablaTransformacion(datos)+infoPrecios)
          $("#confirm-transformacion").modal({backdrop: 'static', keyboard: false})
          
         //ajax para registrar
    
   })
   
   $('button[data-dismiss="modal"').click(function(){
       $('#registrarTransformacion').removeAttr('disabled')
   })
   
   $('#anadir').click(function(e){
       
       var id_producto=$('#producto').val()
       if(id_producto==0){
            $('#myModal').css('color','red')
           $('.modal-title').html('Información ')
            $('.modal-body').html("No ha seleccionado ningún producto.\n")
            $("#myModal").modal()  
            return false
       }
       var cantidad=$('#cantidad').val()
       if (!cantidad && !$('#patron').is(':checked')) 
           {
            $('#myModal').css('color','red')
            $('.modal-title').html('Información ')
            $('.modal-body').html("Falta indicar la cantidad.\n")
            $("#myModal").modal()  
            return false
       }
       if ($('#patron').is(':checked'))  cantidad="0"
       
       cantidad=cantidad.replace(',','.')
       
       var perdida=$('#perdida').val()
       perdida=perdida.replace(',','.')
       
       var tipoUnidad=$('#tipoUnidad').html()
       
       var fechaCaducidad=$('#fechaCaducidad').val()
       
       cambios=true;
       
       if(tipoUnidad=="Und"){
           if(parseInt(cantidad)*1000!=parseFloat(cantidad)*1000){
                $('#myModal').css('color','red')
                $('.modal-title').html('Información ')
                $('.modal-body').html("La cantidad debe ser un número entero, sin decimales.\n")
                $("#myModal").modal()  
                return false
           }
            cantidad=parseInt(cantidad)
        }
       else {
           cantidad=parseInt(parseFloat(cantidad)*1000)/1000
           cantidad=cantidad.toFixed(3)
       }
       
       
       
       //alert(cantidad)
       
       var producto=$('#producto').children('option[value="'+id_producto+'"]').html()
       var codigo_producto=producto.substr(-14,13)
       var nombre_producto=producto.substr(0,producto.length-15)
       
       
       var linea=lineaProducto(codigo_producto,nombre_producto,cantidad,tipoUnidad,fechaCaducidad,perdida)
       $('#lineasProductos').append(linea)
       
       $('#producto').val('')
       $('#cantidad').val('')
       $('#precio').val('')
       $('#fechaCaducidad').val('')
       $('#perdida').val('')
       $('#tipoUnidad').val('')
       
     //  $('.x').trigger('click')
       $('#buscarProductos_').trigger('blur')
       $('#fechaCaducidad').removeAttr('disabled')
       
       verPreciosCompra()
       
      if( $('.cantidad').length>0) $('#contenidoTransformacion').removeClass('hide')
      else $('#contenidoTransformacion').addClass('hide')
      
      if ($('#patron').is(':checked'))  lineasPatron()
      else lineasNoPatron()
       
   })
   
   
   
  
    
    
    
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    
     
    var id_pe_producto
    
    
   
    
    var cantidadTotal=sumaCantidades()
    
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
    
    
    
    //filtrado transformaciones
    $('#buscarTransformaciones_').click(function(){
        $(this).val('')
        filtroTransformaciones("",'transformacion')
    })
    
    $('#buscarTransformaciones').click(function(){
        var filtro=$('#buscarTransformaciones').val()
        filtroTransformaciones(filtro,'transformacion')
        $('#buscarTransformaciones').css('color','black')
    }).keydown(function(ev){
        if ( ev.which == 13 || ev.which == 9) {
            ev.preventDefault();
            var filtro=$(this).val()
            filtroTransformaciones(filtro,'transformacion')
            $(this).css('border','1px solid #ccc')  
            $(this).css('color','black')
            $('select#transformacion').focus()
        }
    })
    
    
    $('#buscarTransformacion_').click(function(){
        var filtro=$('#buscarTransformaciones').val()
        filtroTransformaciones(filtro,'transformacion')
    })
    
    $('#buscarTransformacion').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarTransformaciones').val()
        filtroTransformaciones(filtro,'transformacion')
        $('#buscarTransformaciones').css('color','black')
        $('select#transformacion').focus()
    })
    
    $('#resetTransformaciones').click(function(){
        $('#buscarTransformaciones').val('')
        filtroTransformaciones('','transformacion')
    })
    
    function filtroTransformaciones(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/stocks/getTransformacionesFiltro", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            var datos=$.parseJSON(datos);
             $('select#'+id+' option').remove();
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#'+id).append(option)
             })
             $('#buscarTransformaciones').css('color','black')
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
  
    
    
    $('select#producto').change(function(){ $('#inventario').addClass('hide');})
    
    $('body').delegate('.eliminar','click',function()  {
        $(this).parent().parent().remove()
        verPreciosCompra()
        
        if( $('.cantidad').length>0) $('#contenidoTransformacion').removeClass('hide')
        else $('#contenidoTransformacion').addClass('hide')
    })
    
    $('body').delegate('.cantidad','change',function()  {
       // verPreciosCompra()
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
    
    $('body').delegate('.perdida','keyup',function()  
        {  
           $('#preciosCompra').empty() 
            if($(this).val().trim()=="" || $(this).val().trim()=="-" ) return false
            if(!$.isNumeric( $(this).val() )) {
                
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información ')
                    $('.modal-body').html("El % perdida peso debe ser un número válido.\n")
                    $("#myModal").modal()
                    

                return false
            }
             cambios=true;
             verPreciosCompra()
             
    })

    $('body').delegate('.fechaCaducidad','change',function()  
        {  
             cambios=true;
             verPreciosCompra()
             
    })
    




       var currently = false; 
       $('body').delegate('.cantidad','keyup',function()  
        {  
            
            if(!$(this).hasClass('activetab') && !currently){
                currently = true;
                $('.activetab').removeClass('activetab',500);
                $(this).addClass('activetab',500, "swing", function(){
                    currently = false;
            });
            }

            $('#preciosCompra').empty()
            if($(this).val().trim()=="" || $(this).val().trim()=="-" ) return false
            if(!$.isNumeric( $(this).val() )) {
                
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información ')
                    $('.modal-body').html("La cantidad debe ser un número válido.\n")
                    $("#myModal").modal()
                    
                $th.removeClass('processing');    
                return false
            }
            var tipoUnidad=$(this).parent().parent().children('div:nth-child(4)').children().html()
            
            if(tipoUnidad=='Und'){
                if(!(/^-?\d*?$/.test($(this).val()))){
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información ')
                    $('.modal-body').html("La cantidad debe ser un número entero, sin decimales.\n")
                    $("#myModal").modal() 
                    $(this).focus()
                    $th.removeClass('processing');
                    return false
                }
               
            else{
                if(!(/^-?\d*(\.\d+)?$/.test($(this).val()))){
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información ')
                    $('.modal-body').html("La cantidad debe ser un número número.\n")
                    $("#myModal").modal()
                    $(this).focus()
                    $th.removeClass('processing');
                    return false
                }
            }
            }
            
            
            cambios=true;
            var divColor=$(this).parent().parent()
            var fechaCaducidad=$(this).parent().parent().children('div:nth-child(7)').children()
            console.log(fechaCaducidad)
            var perdida=$(this).parent().parent().children('div:nth-child(6)').children()
            var tipoUnidad=$(this).parent().parent().children('div:nth-child(5)').children()
            
        
            if($(this).val()<0) {
                 divColor.removeClass('Producido')
                 divColor.removeClass('Neutro')
                 divColor.addClass('Consumido')
                 
                 $(this).removeClass('Producido')
                 $(this).removeClass('Neutro')
                 $(this).addClass('Consumido')
                 
                 $(this).parent().next().next().children().removeClass('Producido')
                 $(this).parent().next().next().children().removeClass('Neutro')
                 $(this).parent().next().next().children().addClass('Consumido')
                 
                 $(this).parent().next().next().next().children().removeClass('Producido')
                 $(this).parent().next().next().next().children().removeClass('Neutro')
                 $(this).parent().next().next().next().children().addClass('Consumido')
                
                 
                 divColor.children('div:nth-child(1)').children().html('Consumido')
                 fechaCaducidad.val('')
                 fechaCaducidad.attr('disabled','disabled')
                 fechaCaducidad.css('background-color','lightgrey')
                 if(tipoUnidad.html()=='Kg') perdida.removeAttr('disabled')
                 if(tipoUnidad.html()!='Kg') perdida.attr('disabled','disabled')
                 
            }
            if($(this).val()>0) {
                divColor.addClass('Producido')
                 divColor.removeClass('Consumido')
                 divColor.removeClass('Neutro')
                 
                 $(this).addClass('Producido')
                 $(this).removeClass('Consumido')
                 $(this).removeClass('Neutro')
                 
                 $(this).parent().next().next().children().addClass('Producido')
                 $(this).parent().next().next().children().removeClass('Consumido')
                 $(this).parent().next().next().children().removeClass('Neutro')
                 
                 $(this).parent().next().next().next().children().addClass('Producido')
                 $(this).parent().next().next().next().children().removeClass('Consumido')
                 $(this).parent().next().next().next().children().removeClass('Neutro')
                 
                  divColor.children('div:nth-child(1)').children().html('Producido')
                  fechaCaducidad.removeAttr('disabled')
                  fechaCaducidad.css('background-color','')
                  perdida.attr('disabled','disabled')
                  perdida.val('')
            }
            if($(this).val()==0) {
                divColor.removeClass('Producido')
                 divColor.removeClass('Consumido')
                 divColor.addClass('Neutro')
                 
                 $(this).removeClass('Producido')
                 $(this).removeClass('Consumido')
                 $(this).addClass('Neutro')
                 
                 $(this).parent().next().next().children().removeClass('Producido')
                 $(this).parent().next().next().children().removeClass('Consumido')
                 $(this).parent().next().next().children().addClass('Neutro')
                 
                 $(this).parent().next().next().next().children().removeClass('Producido')
                 $(this).parent().next().next().next().children().removeClass('Consumido')
                 $(this).parent().next().next().next().children().addClass('Neutro')
                 
                  divColor.children('div:nth-child(1)').children().html('Neutro')
                  fechaCaducidad.removeAttr('disabled')
                  fechaCaducidad.css('background-color','')
                  perdida.attr('disabled','disabled')
                  perdida.val('')
            }
          
                verPreciosCompra()
            
           
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