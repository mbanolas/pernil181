

<br />
<!-- Seleccion producto -->
<div class="box box-primary col-lg-12 " id="seleccionar">  
        <div class="container">
            <h4>Inventario Productos </h4>
            <div class="row">
                <div class="col-sm-2">
                   <label  class="col-sm-12 form-control-label">Filtro productos </label>
                </div>
                <div class="col-sm-4">
                    <label for="producto" class="col-sm-12 form-control-label">Producto</label>
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

                <div class="col-sm-5">
                    <?php echo form_dropdown('producto', $optionsProductos, '', array('width' => '100%', 'id' => 'producto', 'class' => ' form-control input-sm ')); ?>
                </div>

                 <div class="col-sm-2">
                                         <button type="button" class="btn btn-default btn-sm" id="addInventario" >Entrar Inventario </button>
                 </div>
                <input type="hidden" value="0" id="codigoFormula" >
              
            </div> 
        
            </div>
        </div>
<hr>      

<div class="container hide" id="inventario">
    <h4>Inventario </h4>
    <h3>Producto: <span id="codigo_producto"></span> - <span id="nombre"></span></h3>
    <h4 id="partidas">Partidas</h4>
    <div class="row">
        <div class="box-body_ col-sm-2 titulo stockActual_"><label></label></div>
        <div class="box-body_ col-sm-2 titulo stockActual_"><label>Inventario nuevo</label></div>
        <div class="box-body_ col-sm-1 titulo stockActual_"></div>
        <div class="box-body_ col-sm-2 titulo"><label>Fecha caducidad</label></div>
    </div>
    <div id="cantidades">
       
    </div>
    
     <br />
            <div class="row " id="botonesAcciones">
                <div class="box-body col-lg-2 titulo">Total: <span id="total"></span></div>
                <div class="box-body col-lg-2 titulo">
                  <button type="button" class="btn btn-info btn-sm" id="registrarInventario" >Registrar Inventario</button>
                </div>
                <div class="box-body col-lg-2 titulo">
                  <button type="button" class="btn btn-danger btn-sm" id="cancelarInventario" >Cancelar (Salir sin registrar)</button>
                </div>
            </div>

</div>






            
           
            <hr>
           
        
</div>




<!-- style -->
<style>
    select.input-sm{
        height: 25px;
        
    }
    select#producto{
        width:100%;
    }
    .input-sm {
    height: 25px;
    padding: 0px 10px;
    }
    input#buscarProductos{
        width:70%;
    }
    .reset{
       padding-left: 28px;
       padding-top:  4px;
    }
    
    .anadir{
       padding-left: 28px;
       padding-right: 0px;
       padding-top:  3px;
       width:6%
    }
    
    .eliminar{
       padding-left: 28px;
       padding-top:  3px;
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
    
       .btn-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
      
    .stockActual{
        text-align: right;
    }
    
    .cantidadActual{
        padding-right: 15px;
    }
    
    input.cantidad, input.fechaCaducidad{
        padding-right: 15px;
        border: 1px solid #cccccc;
        background-color: lightyellow;
        height: 25px;
        line-height: 30px;
        padding: 0px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    
    input.cantidad:focus, input.fechaCaducidad:focus{
        border-color: #66afe9;
        outline: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
    }   
    
.col-sm-1{
    width:20px;
    }
</style>

<script>
        
$(document).ready(function () {
    
    $('#buscarProductos').focus()
   
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
        });
   
   
    //control cambios antes de abandonar la página
    var cambios=false
    
     if(true){
     var otro='<div class="row">'+
            ' <div >'+
                '<span  class="col-sm-2  cantidadActual" ></span>'+
            '</div>'+
            '<div >'+
            '<input type="text"   class="col-sm-2  cantidad" value="" >'+
            '</div>'+
            '<div >'+
                '<span  class="col-sm-1"    ></span> ' +          
            '<div>'+
            '<input type="date"  class="col-sm-2  fechaCaducidad" value="" >'+
           
            '</div>'+
            '<div>'+
                '<a  class="anadir col-sm-2 otro " >Otro</a>' +
             '</div>'+
            '<div>'  + 
            '<a  class="eliminar col-sm-3" >Eliminar</a> '+
            '</div>'+
        '</div>'+
    '</div>'
    }  //otro
            
    
     
    var id_pe_producto
    var tipoUnidad=""
 
    filtroProductos(" ",'producto')
    //var cantidadTotal=sumaCantidades()
    
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
                 var option='<option value="'+id_pe_producto+'">'+codigo_producto+' - '+nombre+'</option>'
                 $('#'+id).append(option)
             })
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
 
    $('select#producto').change(function(){ 
        if(cambios==true){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Se han realizado entradas NO registradas.<br>Registrarlas o Cancelar antes de continuar")
            $("#myModal").modal() 
            return false
        }
        $('#inventario').addClass('hide');
    })
    
    
    
    $('body').delegate('.eliminar','click',function()  {
       // if (partidas==1) return false
       
        $(this).parent().parent().parent().remove()
        $('.otro').last().removeClass('hide')
        if($('.otro').length==0){
            $('#cantidades').append(otro)
        }
        sumaCantidades()
       
    })
    
    function sumaCantidades(){
        var total=0
        $('.cantidad').each(function (index, value) { 
            var valor=$(value).val()
            if (valor=="") valor="0"
          //  var cantidad=parseInt(valor)
           // if (cantidad=="") cantidad="0"
            total+=parseFloat(valor)
        });
        if(isNaN(total)) total=0
        if(tipoUnidad=='Und')
            $('#total').html(total.toFixed(0)+' '+tipoUnidad)
        if(tipoUnidad=='Kg')
            $('#total').html(total.toFixed(3)+' '+tipoUnidad)
        return total
    }
   
    
    $('body').delegate('.otro','click',function(e)  
    {
        e.preventDefault()
        $('.otro').addClass('hide')
        $('#cantidades').append(otro)
    })
    
    $('body').delegate('.cantidad','keyup',function()  
        {  
           var cantidad=$(this).val() 
           if(tipoUnidad=="Und"){
           if(!isNaN(parseInt(cantidad)) && parseInt(cantidad)*1000!=parseFloat(cantidad)*1000){
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
       cambios=true;
       sumaCantidades()
        }); 
    
    
    
    $('#addInventario').click(function(){
         
        var producto=$('select#producto').val()
        if(producto==0){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Debe seleccionar un producto.")
            $("#myModal").modal() 
            return false
        }
        if(cambios==true){
            $('#myModal').css('color','red')
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Se han realizado cambios NO registrados.<br>Registrar o Cancelar antes de continuar")
            $("#myModal").modal() 
            return false
        }
        
        $('#inventario').removeClass('hide');
       // $('.cantidad').first().focus()
        id_pe_producto=producto
        var texto=$('select#producto option[value="'+producto+'"]').html().trim()
        
        var codigo_producto=texto.substr(-14).substr(0,13) 
        var nombre=texto.substr(0,texto.length-16)
        $('#codigo_producto').html(codigo_producto)
        $('#nombre').html(nombre)
        
        //leer datos actuales inventario
        //alert('inventarios '+id_pe_producto)
        
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/readInventario",
            data: {id_pe_producto:id_pe_producto},
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos)
                $('#cantidades').html("")
                $.each( datos['result'], function( key, value ) {
                    $('.otro').addClass('hide')
                    $('#cantidades').append(otro)
                    $('.cantidad').last().val(value['cantidad']/1000)
                   // $('.cantidadActual').last().html(value['cantidad']/1000)
                    $('.fechaCaducidad').last().val(value['fecha_caducidad_stock'])
                });
                if($('.otro').length==0) $('#cantidades').append(otro)
                tipoUnidad=datos['tipoUnidad']
                var textoUnidad="Unidades (Und)"
                if(tipoUnidad=="Kg") textoUnidad="Peso (Kg)"
                $('#partidas').html('Partidas - '+textoUnidad)
                sumaCantidades()
            },
            error: function(){
                alert("Error en el proceso lectura producto en inventario. Informar");
            },
          })
        
        
    })
    
    
    
    $('#cancelarInventario').click(function(){
        location.reload();
    })
    
    
   $('#registrarInventario').click(function(){
       var cantidades=[]
       var totalCantidades=0
      // alert(cantidades.length)
       var fechasCaducidades=[]
       $('.cantidad').each(function (index, value) { 
            var valor=$(value).val()
            if (valor=="") valor="0"
            cantidades.push(parseFloat(valor))
           totalCantidades+=parseFloat(valor)
        });
        $('.fechaCaducidad').each(function (index, value) { 
            var valor=$(value).val()
            if (valor=="") valor="0000-00-00"
            fechasCaducidades.push(valor)
        });
        
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/registrarInventario",
            data: {id_pe_producto:id_pe_producto,cantidades:cantidades,fechasCaducidades:fechasCaducidades},
            success: function(datos){
                $('#myModal').css('color','blue')
                $('.modal-title').html('Información ')
                $('.modal-body>p').html("Datos Inventario Registrados.")
                $("#myModal").modal() 
                cambios=false
                 $('#inventario').addClass('hide');
                 $('#producto').val(0)
                //var datos=$.parseJSON(datos)
                //location.reload();
            },
            error: function(){
                alert("Error en el proceso registro inventario. Informar");
            },
          })
       
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