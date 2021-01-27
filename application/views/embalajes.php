
<br />

<h4>Definir embalajes </h4>

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


    <div class="col-sm-8">
        <label for="producto" class="col-sm-12 form-control-label">Producto</label>
        <?php echo form_dropdown('producto', '', '', array('width' => '100%', 'id' => 'producto', 'class' => ' form-control input-sm ')); ?>
    </div>
    
</div>
<div id="embalajesPara" class='hide'>
    <div  ><h4 style='color:blue;background-color:lightblue; padding-left:5px;border:2px solid blue;'><span id='tituloProducto'></span></h4></div>
    <br>
    <div class="row">
        <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro embalajes </label>
            <div class="input-group">
                <input type="text" id="buscarEmbalajes" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscarEmbalaje" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>  
        <div class="col-sm-4">
            <label for="embalaje" class="col-sm-12 form-control-label">Envase y Embalaje</label>
            <?php echo form_dropdown('embalaje', '', '', array('width' => '100%', 'id' => 'embalaje', 'class' => ' form-control input-sm ')); ?>
        </div>
       
    </div>
     <hr>
     <div class="hide" id="datosEmbalaje">
         <div class="row " >
         <div class="col-sm-2">
             <h5>Código embalaje</h5>
         </div>
         <div class="col-sm-4">
             <h5>Descripción</h5>
         </div>
         <div class="col-xs-2">
             <h5>Cantidad</h5>
         </div>
         <div class="col-sm-1">
             <h5>Coste (€) </h5>
         </div>
         <div class="col-sm-1">
             <h5>Tienda</h5>
         </div>
         <div class="col-sm-1">
             <h5>Online</h5>
         </div>
     </div> 
   <div id="componentesEmbalajes">  
  
   </div>  
      <div class="row">
         <div class="col-sm-2">
             <h5>Total Embalaje</h5>
         </div>
         <div class="col-sm-4">
             <h5></h5>
         </div>
         <div class="col-xs-2">
             <h5></h5>
         </div>
         <div class="col-sm-1">
             <h5></h5>
         </div>
         <div class="col-sm-1">
             <h5 id="totalTienda" style="text-align: right;padding-right: 30px">0.00</h5>
         </div>
         <div class="col-sm-1">
             <h5 id="totalOnline" style="text-align: right;padding-right: 45px">0.00</h5>
         </div>
     </div> 
     
     <div class="form-group"> 
    <div class="col-sm-offset-0 col-sm-10" id="botones">
      <button type="submit" class="btn btn-default" id="registrarEmbalaje">Registrar Embalaje</button>
      <button type="submit" class="btn btn-default cancelarEmbalaje" >Cancelar</button>
    </div>
  </div>
         <br><br>
</div>
</div>

<script>
    
   
    
    var dataFromParent;   
                
    $(document).ready(function () {
        
    var id_pe_producto=$('select#producto').val()   
    //alert('id_pe_producto '+id_pe_producto)
    var productoCambio=window.location.href.substring(window.location.href.indexOf('embalajes')+10);
    var id_pe_producto=productoCambio  
    //alert('id_pe_producto '+id_pe_producto)
    
    var nombreId=""
    
    $('.cancelarEmbalaje').click(function(){
        window.location.replace("<?php echo base_url() ?>"+"index.php/gestionTablasProductos/embalajes")
    })
    
    filtroProductos("",'producto')
    
    
    
   
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
            if(nombreId=='buscarEmbalajes')
                filtroProductos(" ",'embalaje')
        });
        
        
    //filtrado productosFinales 
    $('#buscar').click(function(e){
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
    
    
    
    $('select#producto').change(function(){
        id_pe_producto=$(this).val()
        //alert('select#producto change '+id_pe_producto)
        $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/getProducto", 
            data:{'id_pe_producto':id_pe_producto},
            success:function(datos){
            //alert(datos)
            $('#datosEmbalaje').addClass('hide')
            $('#componentesEmbalajes').html("")
            
            var datos=$.parseJSON(datos);
            $.each(datos['embalajes'], function(index, value){
                $('#datosEmbalaje').removeClass('hide')
                var linea =lineaComponenteEmbalajeCompleta(value['codigo_embalaje'],value['nombre'],value['tipo_unidad'],value['precio'],value['cantidad']/1000,value['tienda'],value['online'])
                $('#componentesEmbalajes').append(linea)
            })
            calcular()
            //alert(datos['embalajes'][0]['cantidad'])
            //alert(datos['embalajes'][1]['cantidad'])
            
            $('#tituloProducto').html("<h4>Embalajes para el producto: "+datos['datos']['codigo_producto']+' (Cód báscula: '+datos['datos']['codigo_bascula']+") "+datos['datos']['nombre']+"</h4>")
            $('#embalajesPara').removeClass('hide')
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
       
        $('body').delegate('.tienda','click',function()  
       {
        
        calcular()
        })
        
        $('body').delegate('.online','click',function()  
       {
        calcular()
        })
        
        $('body').delegate('.eliminar','click',function(e)  
        {
            e.preventDefault()
           $(this).parent().parent().remove()
           calcular()
        })
       
        $('body').delegate('.cantidad','keyup',function()  
       {
        var cantidad=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().val())
        var precio=parseFloat($(this).parent().parent().children('div:nth-child(3)').children().next().val())
        var coste=cantidad*precio
        $(this).parent().parent().children('div:nth-child(4)').children().html(numeral(coste).format("0.000"))
       
        calcular()
        })
        
        $('[data-dismiss="modal"]').click(function(){
           window.location.replace("<?php echo base_url() ?>"+"index.php/gestionTablasProductos/embalajes");

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
        
        function calcular(){
            var totalTienda=0
            var totalOnline=0
            $('.coste').each(function (index, value){
            var coste = parseFloat($(value).html());
            if($(value).parent().next().children().is(':checked')){
                totalTienda+=coste
            }
            if($(value).parent().next().next().children().is(':checked')){
                totalOnline+=coste
            }
        })
        $('#totalTienda').html(numeral(totalTienda).format("0.000"))
         $('#totalOnline').html(numeral(totalOnline).format("0.000"))
        
        }
        
        if  (productoCambio !== "") {
        //alert('productoCambio '+productoCambio)
        $('select#producto').val(productoCambio)
        //alert('productoCambio2 '+productoCambio)
         $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/productos/getProducto", 
            data:{'id_pe_producto':productoCambio},
            success:function(datos){
            //alert('productoCambio'+productoCambio+'  '+datos)
            $('#datosEmbalaje').addClass('hide')
            $('#componentesEmbalajes').html("")
            
            var datos=$.parseJSON(datos);
            $.each(datos['embalajes'], function(index, value){
                $('#datosEmbalaje').removeClass('hide')
                var linea =lineaComponenteEmbalajeCompleta(value['codigo_embalaje'],value['nombre'],value['tipo_unidad'],value['precio'],value['cantidad']/1000,value['tienda'],value['online'])
               // alert(value['precio'])
                $('#componentesEmbalajes').append(linea)
            })
            calcular()
            //alert(datos['embalajes'][0]['cantidad'])
            //alert(datos['embalajes'][1]['cantidad'])
            
             $('#tituloProducto').html("<h4>Embalajes para el producto: "+datos['datos']['codigo_producto']+' (Cód báscula: '+datos['datos']['codigo_bascula']+") "+datos['datos']['nombre']+"</h4>")
            $('#embalajesPara').removeClass('hide')
            
            },
            error: function(){
                alert("Error en el proceso. select#producto change. Informar");
            }        
        })
    // ...
    }
        
    })     
</script>