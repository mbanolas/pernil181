
<br />

<h4>Definir productos en pack </h4>

<div class="row">
    <div class="col-sm-2">
        <label  class="col-sm-12 form-control-label">Filtro packs </label>
        <div class="input-group">
            <input type="text" id="buscarPacks" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
            <div class="input-group-btn">
                <button class="btn btn-default btn-sm" id="buscarPack" ><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </div>  


    <div class="col-sm-8">
        <label for="producto" class="col-sm-12 form-control-label">Pack</label>
        <?php echo form_dropdown('pack', '', '', array('width' => '100%', 'id' => 'pack', 'class' => ' form-control input-sm ')); ?>
    </div>
    
</div>


<div id="productosPara" class='hide'>
    <div  ><h4 style='color:green;background-color:lightgreen; padding-left:5px;border:2px solid green;'><span id='tituloPack'></span></h4></div>
    <br>
    <div class="row">
        <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro productos </label>
            <div class="input-group">
                <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscarProducto" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>  
        <div class="col-sm-4">
            <label for="embalaje" class="col-sm-12 form-control-label">Producto componente Pack</label>
            <?php echo form_dropdown('producto', '', '', array('width' => '100%', 'id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>
       
    </div>
    
     <hr>
     <div class="hide" id="datosPack">
         <div class="row " >
         <div class="col-sm-2">
             <h5>Código producto</h5>
         </div>
         <div class="col-sm-4">
             <h5>Descripción</h5>
         </div>
         <div class="col-xs-1">
             <h5>Cantidad</h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5>Coste (€) </h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5>PVP (€)</h5>
         </div>
         <div class="col-sm-1">
             <h5>Descuento (%)</h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5>PVP en pack (€)</h5>
         </div>
     </div> 
         
         
   <div id="componentesPack">  
   </div>  
      <div class="row">
         <div class="col-sm-2">
             <h5>Total Pack</h5>
         </div>
         <div class="col-sm-4">
             <h5></h5>
         </div>
         <div class="col-xs-1" style="text-align: right;padding-right: 48px">
             <h5 id="totalCantidad" >0</h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5 id="totalCoste" >0.000</h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5 id="totalPVP" >0.000</h5>
         </div>
         <div class="col-sm-1">
             <h5 ></h5>
         </div> 
         <div class="col-sm-1" style="text-align: right;">
             <h5 id="totalPVPPack" >0.000</h5>
         </div>
     </div> 
      <div class="row" style="margin-top:0px">
         <div class="col-sm-2" >
             <h5 style="margin-top:0px">Márgenes (%)</h5>
         </div>
         <div class="col-sm-4">
             <h5></h5>
         </div>
         <div class="col-xs-1" style="text-align: right;padding-right: 48px">
             <h5  style="margin-top:0px"></h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5  style="margin-top:0px"></h5>
         </div>
         <div class="col-sm-1" style="text-align: right;">
             <h5 id="margen" style="margin-top:0px">0.00</h5>
         </div>
         <div class="col-sm-1">
             <h5 style="margin-top:0px"></h5>
         </div> 
         <div class="col-sm-1" style="text-align: right;">
             <h5 id="margenPack" style="margin-top:0px" >0.00</h5>
         </div>
     </div> 
         
     <div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10" id="botones">
      <button type="submit" class="btn btn-default" id="registrarPack">Registrar Pack Productos</button>
      <button type="submit" class="btn btn-default cancelarPack" >Cancelar</button>
    </div>
  </div>
         <br><br>
</div>
</div>

<style>
    .cantidad{
        width:40px;
    } 
    .descuento{
        width:50px;
    } 
    label.coste{
        text-align: right;
    }
    
    .modal-content{
        width:750px;
        margin-left: -105px;
    }
    
   
    
      .modal-content{
    width:1000px;
    left:-52px;
    
      }
</style>
<script>
    
   
    var cambios=false;
    
    var dataFromParent;   
                
    $(document).ready(function () {
        
    var id_pe_producto_pack=$('select#pack').val()   
    //alert('id_pe_producto_pack '+id_pe_producto_pack)
    var productoCambio=window.location.href.substring(window.location.href.indexOf('packs')+6);
    var id_pe_producto_pack=productoCambio  
    //alert('id_pe_producto '+id_pe_producto)
    
   
    
    
    var nombreId=""
    
    $('.cancelarPack').click(function(){
        window.location.replace("<?php echo base_url() ?>"+"index.php/gestionTablasProductos/packs")
    })
    
    filtroProductos("",'producto')
    
    filtroProductos("",'pack')
    
    filtroProductos("",'embalaje')
    
   
    
    function lineaComponenteEmbalaje(codigo,nombre,tipo_unidad,precio){
        
        var linea= '<div class="row">'
            linea+= '<div class="col-sm-2">'
            linea+= '     <label class="codigo">'+codigo+'</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-4">'
            linea+= '    <label>'+nombre+' ('+tipo_unidad+')</label>'
            linea+= '</div>'
            //linea+= '<div class="form-group form-group-sm">'
            linea+= '<div class="col-sm-2">'
            linea+= '  <input class=" cantidad" type="text"  value=0>'
            linea+= '  <input class="precio" type="hidden"  value="'+precio+'">'
            linea+= '</div>'
           // linea+= '</div>'
            
            linea+= '<div class="col-sm-1">'
            linea+= '    <label class="coste">0</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '<input type="checkbox" name="tienda[]" class="tienda"  value="1" checked>'
            linea+= ' </div>'
            
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '     <input type="checkbox" name="online[]" class="online"  value="0" >'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '    <a href="" class="eliminar">Eliminar</a>'
            linea+= '</div>'
            linea+= ' </div> '
            return linea
    }
        
    function lineaComponentePack(codigo,nombre,cantidad,precio,pvp,iva,descuento,pvpPack){
        var linea= '<div class="row">'
            linea+= '<div class="col-sm-2">'
            linea+= '     <label class="codigo">'+codigo+'</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-4">'
            linea+= '    <label>'+nombre+'</label>'
            linea+= '</div>'
            //linea+= '<div class="form-group form-group-sm">'
            linea+= '<div class="col-sm-1">'
            linea+= '  <input class="cantidad" type="text"  value=0>'
            linea+= '  <input class="precio" type="hidden"  value="'+precio+'">'
            linea+= '  <input class="pvp" type="hidden"  value="'+pvp+'">'
            linea+= '  <input class="iva" type="hidden"  value="'+iva+'">'
            linea+= '</div>'
           // linea+= '</div>'
            
            linea+= '<div class="col-sm-1" style="text-align:right; ">'
            linea+= '    <label class="coste">0</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align:right; ">'
            linea+= '    <label class="pvpProducto">0</label>'
            linea+= '</div>'
            
            linea+= '<div class="col-sm-1" >'
            linea+= '  <input class=" descuento" type="text"  value=0.00>'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align:right; ">'
            linea+= '    <label class="pvpProductoPack">0</label>'
            linea+= '</div>'
            
            
            
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '    <a href="" class="eliminar">Eliminar</a>'
            linea+= '</div>'
            linea+= ' </div> '
            return linea
    }

    function lineaComponentePackCompleta(codigo,nombre,cantidad,precio,pvp,iva,descuento,pvpPack){
        cantidad/=1000
        descuento/=1000
        pvpPack=cantidad*pvp
        pvpPack=pvpPack-pvpPack*descuento/100
        descuento=numeral(descuento).format('0.00')
        pvpPack=numeral(pvpPack).format('0.00')
        precio=numeral(precio).format('0.000')
        pvp=numeral(pvp).format('0.000')
        var linea= '<div class="row">'
            linea+= '<div class="col-sm-2">'
            linea+= '     <label class="codigo">'+codigo+'</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-4">'
            linea+= '    <label>'+nombre+'</label>'
            linea+= '</div>'
            //linea+= '<div class="form-group form-group-sm">'
            linea+= '<div class="col-sm-1">'
            linea+= '  <input class="cantidad" type="text"  value="'+cantidad+'">'
            linea+= '  <input class="precio" type="hidden"  value="'+precio*1000+'">'
            linea+= '  <input class="pvp" type="hidden"  value="'+pvp*1000+'">'
            linea+= '  <input class="iva" type="hidden"  value="'+iva+'">'
            linea+= '</div>'
           // linea+= '</div>'
            
            linea+= '<div class="col-sm-1" style="text-align: right;">'
            linea+= '    <label class="coste" >'+numeral(precio*cantidad).format('0.000')+'</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align: right;">'
            linea+= '    <label class="pvpProducto">'+numeral(pvp*cantidad).format('0.00')+'</label>'
            linea+= '</div>'
            
            linea+= '<div class="col-sm-1">'
            linea+= '  <input class=" descuento" type="text"  value="'+descuento+'">'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align: right;">'
            
            linea+= '    <label class="pvpProductoPack" >'+pvpPack+'</label>'
            linea+= '</div>'
            
            
            
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '    <a href="" class="eliminar">Eliminar</a>'
            linea+= '</div>'
            linea+= ' </div> '
            return linea
    }
    
    function lineaComponenteEmbalajeCompleta(codigo,nombre,tipo_unidad,precio,cantidad,tienda,online){
        
        var linea= '<div class="row">'
            linea+= '<div class="col-sm-2">'
            linea+= '     <label class="codigo">'+codigo+'</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-4">'
            linea+= '    <label>'+nombre+' ('+tipo_unidad+')</label>'
            linea+= '</div>'
            //linea+= '<div class="form-group form-group-sm">'
            linea+= '<div class="col-sm-2">'
            linea+= '  <input class=" cantidad" type="text"  value='+cantidad+'>'
            linea+= '  <input class="precio" type="hidden"  value="'+precio+'">'
            linea+= '</div>'
           // linea+= '</div>'
            var coste=numeral(precio*cantidad).format('0.000')
            linea+= '<div class="col-sm-1">'
            linea+= '    <label class="coste">'+coste+'</label>'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            var checked=""
            if(tienda==1) checked="checked"
            linea+= '<input type="checkbox" name="tienda[]" class="tienda"  value="1" '+checked+'>'
            linea+= ' </div>'
            checked=""
            if(online==1) checked="checked"
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '     <input type="checkbox" name="online[]" class="online"  value="0" '+checked+'>'
            linea+= '</div>'
            linea+= '<div class="col-sm-1" style="text-align:center;">'
            linea+= '    <a href="" class="eliminar">Eliminar</a>'
            linea+= '</div>'
            linea+= ' </div> '
            return linea
    }
    
    // CLEARABLE INPUT
    function tog(v){return v?'addClass':'removeClass';} 
        
    function filtroProductos(filtro,id){
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/stocks/getProductosFiltro", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert('filtroProductos'+id+datos)
            var datos=$.parseJSON(datos);
             $('select#'+id+' option').remove();
             
             
             if (typeof dataFromParent === "undefined") {
                //console.log(productoCambio)  
                $('#'+id).append('<option value="0">'+'Seleccionar un '+id+'</option>')
                $.each(datos['productos'], function(index, value){
                 var id_pe_producto=value['id']
                 var nombre =value['nombre']
                 var codigo_producto=value['codigo_producto']
                  var selected=""
                  if(id_pe_producto==productoCambio) {
                      selected="selected='selected'" 
                    }   
                 var option='<option value="'+id_pe_producto+'" '+selected+'>'+nombre+' ('+codigo_producto+')'+'</option>'
                 $('#'+id).append(option)
             })
                
            }else{
             $.each(datos['productos'], function(index, value){
                 var id_pe_producto=value['id']
                 var nombre =value['nombre']
                 var codigo_producto=value['codigo_producto']
                 //console.log('dataFromParent '+dataFromParent)
                 if(id_pe_producto==dataFromParent) {
                     //console.log(id_pe_producto)
                     //console.log(id_pe_producto==dataFromParent)
                     var selected=""
                    if(id_pe_producto==productoCambio) selected="selected='selected'" 
                    
                    var option='<option value="'+id_pe_producto+' '+selected+'">'+nombre+' ('+codigo_producto+')'+'</option>'
                     $('#'+id).append(option)
                 }
             })
            // alert($('select#producto').val())
             $('select#producto').trigger('change')
            }
             
            $('#buscarProductos').css('color','black') 
            $('#buscarPacks').css('color','black') 
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
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
    
    $(document).on('input', '.clearable', function(){
        
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){
            nombreId=$(this).attr('id')
            //alert('hola x'+nombreId)
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc')  
            if(nombreId=='buscarProductos')
                filtroProductos(" ",'producto')
            if(nombreId=='buscarPacks')
                filtroProductos(" ",'pack')
            if(nombreId=='buscarEmbalajes')
                filtroProductos(" ",'embalaje')
        });
        
        
    //filtrado productosFinales 
    $('#buscar__').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
        $('#buscarProductos').css('color','black')
        $('select#producto').focus()
    })
    
     $('#buscarPack').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarPacks').val()
        filtroProductos(filtro,'pack')
        $('#buscarPacks').css('color','black')
        $('select#pack').focus()
    })
    
    $('#buscarProducto').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
        $('#buscarProductos').css('color','black')
        $('select#producto').focus()
    })
    
    $('#buscarProductos').click(function()
    {
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
    
    $('#buscarPacks').click(function()
    {
        var filtro=$('#buscarPacks').val()
        filtroProductos(filtro,'pack')
        $('#buscarPacks').css('color','black')
    }).keydown(function(ev){
        if ( ev.which == 13 || ev.which == 9) {
            ev.preventDefault();
            var filtro=$(this).val()
            filtroProductos(filtro,'pack')
            $(this).css('border','1px solid #ccc')  
            $(this).css('color','black')
            $('select#pack').focus()
        }
    })
    
    $('select#embalaje').change(function(){
        var id_pe_producto=$(this).val()
        //alert(id_pe_producto)
        $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/getProducto", 
            data:{'id_pe_producto':id_pe_producto},
            success:function(datos){
            //alert(datos)
            $('#datosEmbalaje').removeClass('hide')
            var datos=$.parseJSON(datos);
            
            var precio=datos['datos']['precio_compra']/1000
            var linea =lineaComponenteEmbalaje(datos['datos']['codigo_producto'],datos['datos']['nombre'],datos['datos']['tipo_unidad'],precio)
            $('#componentesEmbalajes').append(linea)
            $('input#buscarEmbalajes').val('')
            // $('#tituloProducto').html("<h4>Embalajes para el producto: "+datos['codigo_producto']+' (Cód báscula: '+datos['codigo_bascula']+") "+datos['nombre']+"</h4>")
            //$('#embalajesPara').removeClass('hide')
            //alert(datos['codigo_producto']+' (Cód báscula: '+datos['codigo_bascula']+") "+datos['nombre'])
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }        
        })
        
        })  
    
    
    $('select#producto').change(function(){
        var id_pe_producto=$(this).val()
        //alert('select#producto change '+id_pe_producto)
        cambios=true
        $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/getProducto", 
            data:{'id_pe_producto':id_pe_producto},
            success:function(datos){
            //alert(datos)
            $('#datosPack').removeClass('hide')
            //$('#datosPack').addClass('hide')
            // $('#componentesPack').html("")
            
            var datos=$.parseJSON(datos);
            //codigo,nombre,cantidad,precio,pvp,descuento,pvpPack
            var linea =lineaComponentePack(datos['datos']['codigo_producto'],datos['datos']['nombre'],0,datos['datos']['precio_compra'],datos['datos']['tarifa_venta'],datos['datos']['iva'],0,0)
            $('#componentesPack').append(linea)
            $('input#buscarProductos').val('')
            $('select#producto').val(0)
            /*
            $.each(datos['productosPack'], function(index, value){
                $('#datosPack').removeClass('hide')
                var linea =lineaComponenteEmbalajeCompleta(value['codigo_embalaje'],value['nombre'],value['tipo_unidad'],value['precio'],value['cantidad']/1000,value['tienda'],value['online'])
                $('#componentesPack').append(linea)
            })
            calcularSumas()
        */
            //alert(datos['embalajes'][0]['cantidad'])
            //alert(datos['embalajes'][1]['cantidad'])
            
           // $('#tituloPack').html("<h4>Pack productos: "+datos['datos']['codigo_producto']+' '+datos['datos']['nombre']+"</h4>")
            $('#productosPara').removeClass('hide')
            $('input#buscarProductos').val('')
            
            },
            error: function(){
                alert("Error en el proceso. select#producto change. Informar");
            }        
        })
        
        })
        
    $('select#pack').change(function(){
        id_pe_producto_pack=$(this).val()
        //alert('select#producto change '+id_pe_producto_pack)
        $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/getProductosPack", 
            data:{'id_pe_producto_pack':id_pe_producto_pack},
            success:function(datos){
            //alert(datos)
            $('#datosPack').addClass('hide')
            $('#componentesPack').html("")
            
            var datos=$.parseJSON(datos);
            $.each(datos['productosPack'], function(index, value){
                $('#datosPack').removeClass('hide')
                // function lineaComponentePackCompleta(codigo,nombre,cantidad,precio,pvp,descuento,pvpPack){
                var linea =lineaComponentePackCompleta(value['codigo_producto'],value['nombre'],value['cantidad'],value['precio'],value['pvp']/1000,value['iva'],value['descuento'])
                //alert(linea)
                $('#componentesPack').append(linea)
            })
            calcularSumas()
            //alert(datos['embalajes'][0]['cantidad'])
            //alert(datos['embalajes'][1]['cantidad'])
            
            $('#tituloPack').html("<h4>Pack productos: "+datos['datos']['codigo_producto']+' '+datos['datos']['nombre']+"</h4>")
            $('#productosPara').removeClass('hide')
            $('input#buscarProductos').val('')
            
            },
            error: function(){
                alert("Error en el proceso. select#producto change. Informar");
            }        
        })
        
        })    
        
   
       
      //filtrado productosFinales 
    $('#buscarEmbalaje').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarEmbalajes').val()
        filtroProductos(filtro,'embalaje')
        $('#buscarEmbalajes').css('color','black')
        $('select#embalaje').focus()
    })
    
    
    
    $('#buscarEmbalajes').click(function()
    {
        var filtro=$('#buscarEmbalajes').val()
        filtroProductos(filtro,'embalaje')
        $('#buscarEmbalajes').css('color','black')
    }).keydown(function(ev){
        if ( ev.which == 13 || ev.which == 9) {
            ev.preventDefault();
            var filtro=$(this).val()
            filtroProductos(filtro,'embalaje')
            $(this).css('border','1px solid #ccc')  
            $(this).css('color','black')
            $('select#embalaje').focus()
        }
    })
    
       
      
        
        $('body').delegate('.eliminar','click',function(e)  
        {
            e.preventDefault()
           $(this).parent().parent().remove()
           calcularSumas()
           cambios=true   
        })
       
        $('body').delegate('.cantidad','keyup',function()  
       {
        cambios=true   
        
        var cantidad=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().val())
        
        var precio=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().next().val())
        var pvp=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().next().next().val())
        if(isNaN(cantidad)){
            cantidad=0
            //$(this).val(0)
        }
        
        var coste=cantidad*precio/1000
        var totalPVP=cantidad*pvp/1000
        var descuento=parseFloat($(this).parent().next().next().next().children().val())
        var totalPVPPack=totalPVP-totalPVP*descuento/100;
        
        
        $(this).parent().parent().children('div:nth-child(4)').children().html(numeral(coste).format("0.00"))
        $(this).parent().parent().children('div:nth-child(5)').children().html(numeral(totalPVP).format("0.00"))
        $(this).parent().parent().children('div:nth-child(7)').children().html(numeral(totalPVPPack).format("0.00"))
        
        calcularSumas()
        })
        
        $('body').delegate('.descuento','keyup',function()  
       {
        cambios=true      
        var cantidad=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().val())
        var precio=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().next().val())
        var pvp=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().next().next().val())
        var coste=cantidad*precio/1000
        var totalPVP=cantidad*pvp/1000
        var descuento=isNaN(parseFloat($(this).val()))?0:parseFloat($(this).val())
        var totalPVPPack=totalPVP-totalPVP*descuento/100;
        $(this).parent().parent().children('div:nth-child(4)').children().html(numeral(coste).format("0.00"))
        $(this).parent().parent().children('div:nth-child(5)').children().html(numeral(totalPVP).format("0.00"))
        $(this).parent().parent().children('div:nth-child(7)').children().html(numeral(totalPVPPack).format("0.00"))
        
        calcularSumas()
        })
        
        $('[data-dismiss="modal"]').click(function(){
           window.location.replace("<?php echo base_url() ?>"+"index.php/gestionTablasProductos/packs");

        })
        
        
        
        $('#registrarPack').click(function(){
            
            var codigos=[]
            $('.codigo').each(function(i,e)  {
                codigos[i]=$(this).html()
                //alert(codigos[i])
                
            })
           /* 
            if(codigos.length===0){
                console.log('codigos.length '+codigos.length)
            }
            */
            var cantidades=[]
            $('.cantidad').each(function(i,e)  {
                cantidades[i]=$(this).val()
                //alert(cantidades[i])
            })
            
            var descuentos=[]
            $('.descuento').each(function(i,e)  {
                descuentos[i]=$(this).val()
                //alert(descuentos[i])
            })
            
            var totalPrecio_compra=$('#totalCoste').html()*1000
            var totalTarifa_ventaPack=$('#totalPVPPack').html()*1000
            var totalTarifa_venta=$('#totalPVP').html()*1000
            var margenPack=$('#margenPack').html()*1000
            var margen=$('#margen').html()*1000
            
            //alert('totalTarifa_ventaPack '+totalTarifa_ventaPack)
            //alert('totalTarifa_venta '+totalTarifa_venta)
            //alert('totalPrecio_compra '+totalPrecio_compra)
            //alert('id_pe_producto_pack '+id_pe_producto_pack)
            
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/registrarPack", 
            data:{'margen':margen,'margenPack':margenPack,'totalTarifa_venta':totalTarifa_venta,'totalTarifa_ventaPack':totalTarifa_ventaPack,'totalPrecio_compra':totalPrecio_compra,'id_pe_producto_pack':id_pe_producto_pack,codigos:codigos,cantidades:cantidades,descuentos:descuentos},
            success:function(datos){
            //alert(datos)
            cambios=false
            
            
            if (datos){
                var producto=$('#tituloPack').html()
                
                $('#myModal').css('color','black')
                $('.modal-title').html('Información')
                $('.modal-body>p').html("<h4>Registrados Pack </h4>"+ producto)
                $("#myModal").modal({backdrop:"static",keyboard:"false"})
                return false
            }
             
            
            var datos=$.parseJSON(datos);
            
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }        
        })
            
        })
        
         $('#registrarEmbalaje').click(function(){
            
            var codigos=[]
            $('.codigo').each(function(i,e)  {
                codigos[i]=$(this).html()
                
            })
            
            if(codigos.length===0){
                console.log('codigos.length '+codigos.length)
            }
            
            var cantidades=[]
            $('.cantidad').each(function(i,e)  {
                cantidades[i]=$(this).val()
            })
            
            var tiendas=[]
            $('.tienda').each(function(i,e)  {
                if($(this).is(':checked'))
                    tiendas[i]=1
                else
                    tiendas[i]=0
            })
            
            var onlines=[]
            $('.online').each(function(i,e)  {
                if($(this).is(':checked'))
                    onlines[i]=1
                else
                    onlines[i]=0
            })
            
            
            
            //alert('id_pe_producto '+id_pe_producto)
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/registrarEmbalaje", 
            data:{'id_pe_producto':id_pe_producto,codigos:codigos,cantidades:cantidades,tiendas:tiendas,onlines:onlines},
            success:function(datos){
            //alert(datos)
            
            if (datos){
                var producto=$('#tituloProducto').html()
                
                $('#myModal').css('color','black')
                $('.modal-title').html('Información')
                $('.modal-body>p').html("<h4>Registrados</h4>"+ producto+'<h4> y mismos códigos báscula </h4>')
                $("#myModal").modal({backdrop:"static",keyboard:"false"})
                return false
            }
              
            
            var datos=$.parseJSON(datos);
            
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }        
        })
            
        })
        
        function calcularSumas(){
            var totalCantidad=0
            var totalCoste=0
            var totalPVP=0
            var totalPVPPack=0
            
            
            
            var pvp=[]
            var base=0
            var pvpPack=[]
            var basePack=0
            var coste=0
            var iva=[]
            
            $('.cantidad').each(function (index, value){
                //alert(parseFloat($(value).val()))
                if(!isNaN(parseFloat($(value).val())))
                    totalCantidad+= parseFloat($(value).val());
                //alert(totalCantidad)
            })
            
            $('.iva').each(function (index, value){
                iva[index]=parseFloat($(value).val()/1000)
            })
            
            $('.coste').each(function (index, value){
                totalCoste+= parseFloat($(value).html());
            })
            
            $('.pvpProducto').each(function (index, value){
                base+=parseFloat($(value).html())/(1+iva[index]/100)
               
                
                totalPVP+= parseFloat($(value).html());
            })
            $('.pvpProductoPack').each(function (index, value){
                pvpPack[index]=parseFloat($(value).html())
                basePack+=parseFloat($(value).html())/(1+iva[index]/100)
                totalPVPPack+= parseFloat($(value).html())
                //Math.round(parseFloat($(value).html()*1000));
                
            })
           //totalPVPPack/=1000;
            //alert(totalPVPPack)
            var margen=base==0 ? 0:(base-totalCoste)/base*100
            var margenPack=basePack==0 ? 0:(basePack-totalCoste)/basePack*100
            
            
        $('#totalCantidad').html(numeral(totalCantidad).format("0"))
         $('#totalCoste').html(numeral(totalCoste).format("0.000"))
         $('#totalPVP').html(numeral(totalPVP).format("0.00"))
         $('#totalPVPPack').html(numeral(totalPVPPack).format("0.00"))
         $('#margen').html(numeral(margen).format("0.00"))
         $('#margenPack').html(numeral(margenPack).format("0.00"))
        // $('#totalPvpProductos').html(numeral(totalOnline).format("0.000"))
        
        }
        
        if  (productoCambio !== "") {
        //alert('productoCambio '+productoCambio)
        $('select#pack').val(productoCambio)
        //alert('productoCambio2 '+productoCambio)
         $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/getProductosPack", 
            data:{'id_pe_producto_pack':productoCambio},
            success:function(datos){
            //alert('productoCambio'+productoCambio+'  '+datos)
            $('#datosPack').addClass('hide')
            $('#componentesPack').html("")
            
            var datos=$.parseJSON(datos);
            $.each(datos['productosPack'], function(index, value){
                $('#datosPack').removeClass('hide')
                // function lineaComponentePackCompleta(codigo,nombre,cantidad,precio,pvp,descuento,pvpPack){
                var linea =lineaComponentePackCompleta(value['codigo_producto'],value['nombre'],value['cantidad'],value['precio'],value['pvp']/1000,value['iva'],value['descuento'])
                //alert(linea)
                $('#componentesPack').append(linea)
            })
            calcularSumas()
            //alert(datos['embalajes'][0]['cantidad'])
            //alert(datos['embalajes'][1]['cantidad'])
            
            $('#tituloPack').html("<h4>Pack productos: "+datos['datos']['codigo_producto']+' '+datos['datos']['nombre']+"</h4>")
            $('#productosPara').removeClass('hide')
            $('input#buscarProductos').val('')
            $('select#producto').val(0)
            },
            error: function(){
                alert("Error en el proceso. select#producto change. Informar");
            }        
        })
    // ...
    }
    
    window.onbeforeunload=confirmExit
     
     function confirmExit() {
        if (cambios ) 
        {
            return 'Existen cambios NO registrados.'
           
        }
    }
        
        
    })     
</script>