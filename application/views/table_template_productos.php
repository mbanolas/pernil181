<style>


.ftitle{
    font-size: 20px;
   
}

.grocery-crud-table tbody tr td{
    text-align: left;
}

h4.modal-title{
    font-weight: bold;
}

.row {
    margin-right: -10px;
    margin-left: -10px;
}

.table-label div{
    font-weight: bold;
    font-size:24px;
}

.container{
    padding-left: 0px;
    padding-right: 0px;
}





.gc-container {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: auto;
    margin-left: auto;
}

td{
        text-align: left;
    }
    


</style>

 <span id="wn-unsupported" class="hidden">API not supported</span>  
 
<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
   
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                
                
                    <?php echo $output; ?>
                </div>
            </div>

    

    
<style>
    .numero{
        text-align: right;
    }
    .centrado{
        text-align:center;
    }
    
    .modal-content {
	width: 1000px;
	left: -222px;
}
    div.form-group{
        margin-bottom: 2px;
    }
    
    #field-fecha_alta,#field-fecha_proveedor_2{
        width:100px;
    }
    #field-nombre,
    #field-url_producto,
    #field-url_imagen_portada{
        width:90%;
        padding-left: 5px;
    }
    
    #field-peso_real,#field-unidades_caja,#field-stock_minimo,#field-precio_ultimo_peso,
        #field-precio_ultimo_unidad,
        #field-unidades_precio,
        #field-tarifa_venta_peso,
        #field-tarifa_venta_unidad,
        #field-descuento_1_compra,
        #field-beneficio_recomendado{
        text-align: right;
        width:100px;
        padding-right: 5px;
    }
    
    #field-id_producto,#field-anada,#field-codigo_ean{
        text-align: left;
        width:100px;
        padding-left: 5px;
    }
    #field-codigo_producto,#field-codigo_ean{
        text-align: left;
        width:160px;
        padding-left: 5px;
    }
    
    img#img_producto_read {
    position: absolute;
    left: 150px;
    top: 5px;
    z-index: -1;
    border-style: solid;
    border-color:lightgrey;
    border-width: 1px;
    border-radius: 10px;
    height: 190px;
    width:190px;
}

img#img_producto {
    position: absolute;
    left: 350px;
    top: 5px;
    z-index: -1;
    border-style: solid;
    border-color:lightgrey;
    border-width: 1px;
    border-radius: 10px;
    height: 190px;
    width:190px;
}

img#column{
    height: 100px;
    width:100px;
    border-radius: 0px;
    position:relative;
    margin: 0px;
    padding: 0px;
    text-align: left;
}

    
    
    
</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>

     $(document).ready(function(){
         
       
    var datosP=[];
    var nombre;
    var totalUnidades;
    var codigoBascula
    
    function drawChart() {
        var data = google.visualization.arrayToDataTable(datosP);
        var options = {
            legend: {position: 'none'},
          chart: {
            title: 'Unidades vendidas últimos 12 meses: '+totalUnidades+' Unidades - Código Bascula: '+codigoBascula,
            subtitle: 'Ventas mensuales',
          }
        };
        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        //chart.draw(data, options);
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    
    $('[data-dismiss]').click(function(){
        location.reload();
    })
    
    var grande=false;
    var x=0;
    var y=0;
    var last={}
     $('body').delegate('img.imagenProducto','click',function(e) {
       // x = $(this).position();
        //y = $(this).position(); 
        console.log($(this).attr('src'))
        if($(this).attr('src')=="") return false;
        
        if(!grande) {
            $(this).css({ 'height': 200 + 'px', 'width': 200 + 'px' });
            $(this).css({
            position: 'absolute',
           
        });
            $(this).css('z-index','1')
            $(this).css('left',1100+'px')
            last=$(this)
            grande=true; 
        }
        else{
            if(last){
                last.css({ 'height': 100 + 'px', 'width': 100 + 'px' });
                last.css('position','relative')
                last.css('left',0+'px')
                $(this).css('z-index','1')
            }
            $(this).css({ 'height': 100 + 'px', 'width': 100 + 'px' });
            $(this).css('position','relative')
            $(this).css('left',0+'px')
          // $(this).css('z-index','1')
            grande=false; 
        }
         
      })
    
    //var nuevoProducto=$('.crud-form div div div div div').html().trim()=="Añadir Productos";     
    var verProductos=$('.table-label').children().html().trim()=="Ver Productos";    
     
    //pone fecha en caso de no existir (add new)      
    if($('#field-fecha_alta').val()=="") $('#field-fecha_alta').val('<?php echo date('d/m/Y'); ?>')
    if($('#field-unidades_precio').val()=="0") $('#field-unidades_precio').val('1')
         
    $('#ver_url_imagen_portada').attr('href',$('#field-url_imagen_portada').val())
    $('#ver_url_producto').attr('href',$('#field-url_producto').val())
    
    $('#img_producto').attr('src',$('#field-url_imagen_portada').val())
    
    $('#field-url_imagen_portada').blur(function(){
        $('#ver_url_imagen_portada').attr('href',$(this).val())
        $('#img_producto').attr('src',$('#field-url_imagen_portada').val())
    })    
    $('#field-url_producto').blur(function(){
        $('#ver_url_producto').attr('href',$(this).val())
        
    })  
    
    $('#field-id_producto').removeClass('form-control')
    $('#field-nombre').removeClass('form-control')
    $('#field-codigo_ean').removeClass('form-control')
    $('#field-unidades_precio').removeClass('form-control')
    
    $('#field-fecha_alta').removeClass('form-control').parent().children('a').children().remove()
    $('#field-fecha_proveedor_2').removeClass('form-control').parent().children('a').children().remove()
    $('#field-fecha_proveedor_3').removeClass('form-control').parent().children('a').children().remove()

     
     
     $('#field-codigo_producto').parent().children('span').css('color','red')
     $('#field-codigo_producto').parent().children('span').css('font-weight','bold')
     $('#field-codigo_producto').parent().css('margin-top','6px')
     $('#field-codigo_producto').css('color','red')
     $('#field-codigo_producto').css('font-weight','bold')
      
    var usuario=<?php echo $_SESSION['categoria']; ?>;
    if (usuario==2 || usuario==3) $(' a[href*="/add"]').addClass('hide');  //no pueden añadir registro
        
    
    
    selectFamilias()
    
    function selectFamilias(){
        var familia=$('select#field-id_familia').val()
        var grupo=$('#field-id_grupo').val()
        //alert('hola '+grupo)
        $.ajax({
            type:"POST",
            url:"<?php echo base_url() ?>"+"index.php/productos/getFamilias", 
            data:{grupo:grupo},
            success:function(datos){
                 //alert(datos);
                var datos=$.parseJSON(datos)  
                $('select#field-id_familia').parent().attr('id','maba')
                $('#maba').empty();
                var seleccion='<select id="field-id_familia" name="id_familia" class="chosen-select"  style="width: 300px;    ">'
                seleccion+='<option value="'+0+'">'+'Seleccionar Familia'+'</option>';
                $(datos).each(function(index, value){
                    var sel=''
                    if(value[0]==familia) sel='selected="selected"';
                    seleccion+='<option value="'+value[0]+'" '+sel+'>'+value[1]+'</option>';
                })
               seleccion+='</select>';
               $('#maba').append(seleccion)
            },
            error: function(){
                alert('Error búsqueda familias. Informar')
            } 
        })
    }
    
    $('#field-id_grupo').change(function(){
        $('select#field-id_familia').val(0)
        selectFamilias()
        
    })    
        
    //$('.gc-export').removeAttr('data-url')
    //$('.gc-export').attr('class','btn btn-default t5 mi-excel ')
    
    
    $('body').delegate('a.codigo_bascula','click',function(e) {
        e.preventDefault()
        var id_producto=$(this).html()
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/productos/getInfoCodigoBascula/"+id_producto,
            data: {id_producto:id_producto},
            success: function(datos){
               // alert ('datos '+datos)
               var datos=$.parseJSON(datos)
               var info="<table><thead><tr><th width='120' style='text-align:left'>Códigos 13 </th>\n\
                        <th width='300' style='text-align:left'> Nombre </th>\n\
                        <th width='70' style='text-align:right'> Unidad </th>\n\
                        <th width='70' style='text-align:right'> Peso </th>\n\
                        <th width='300' style='text-align:center'> Proveedor </th>\n\
                        <th width='70' style='text-align:right'> Coste </th>\n\
                        <th width='70' style='text-align:right'> PVP </th>\n\
                        <th width='70' style='text-align:right'> Status </th>\n\
                        <th width='70' style='text-align:right'> R.Coste </th>\n\
                        <th width='70' style='text-align:right'> R.PVP </th>\n\
                        </thead><tbody>"
             var coste_peso=[];
             var pvp_peso=[];
             $.each(datos['codigos_producto'], function(index, value){
                  info+='<tr><td> '+value+' </td>'; 
                  info+='<td style="text-align:left"> '+datos['nombres'][index]+' </td>'; 
                  info+='<td style="text-align:right"> '+datos['tipoUnidad'][index]+' </td>'; 
                  info+='<td style="text-align:right"> '+datos['pesos'][index].toFixed(3)+' </td>'; 
                  info+='<td style="text-align:center"> '+datos['proveedor'][index]+' </td>';
                  info+='<td style="text-align:right"> '+datos['precio'][index].toFixed(3)+' </td>';
                  info+='<td style="text-align:right"> '+datos['pvp'][index].toFixed(2)+' </td>';
                  info+='<td style="text-align:right"> '+datos['status'][index]+' </td>';
                  if(datos['status'][index]==1) coste_peso[index]=datos['precio'][index]/datos['pesos'][index]
                  info+='<td style="text-align:right"> '+coste_peso[index].toFixed(3)+' </td>';
                  if(datos['status'][index]==1) pvp_peso[index]=datos['pvp'][index]/datos['pesos'][index]
                  info+='<td style="text-align:right"> '+pvp_peso[index].toFixed(3)+' </td>';
                  info+='</tr>';
                })
                info+='</tbody></table><br>';  
             
             var coste_peso_promedio=coste_peso.reduce(function(valorAnterior, valorActual, indice, vector){
                    return valorAnterior + valorActual;
             })
             coste_peso_promedio=coste_peso_promedio/coste_peso.length;
             var pvp_peso_promedio=pvp_peso.reduce(function(valorAnterior, valorActual, indice, vector){
                    return valorAnterior + valorActual
             })
             pvp_peso_promedio=pvp_peso_promedio/pvp_peso.length;
             if($.isNumeric(coste_peso_promedio)) coste_peso_promedio=coste_peso_promedio.toFixed(3)
             if($.isNumeric(pvp_peso_promedio)) pvp_peso_promedio=pvp_peso_promedio.toFixed(3)
             var observaciones="Observaciones: <strong>Coste promedio </strong>= "+coste_peso_promedio+" - <strong>PVP promedio </strong>= "+pvp_peso_promedio
             
             datosP=[]
                    var pares=[]
                       pares.push('Mes')
                       pares.push('Total')
                       datosP.push(pares)
                   $.each(datos['datos']['und'], function(key, value){
                      var pares=[]
                      pares.push(value['fecha'])
                      pares.push(value['und']+value['undP']+value['undVD'])
                       datosP.push(pares)
                   })
                   //nombre=datos['datos']['nombre']
                   totalUnidades=0
                   totalUnidades+=datos['datos']['totalUnidadesT']
                   totalUnidades+=datos['datos']['totalUnidadesP']
                   totalUnidades+=datos['datos']['totalUnidadesVD']
                   codigoBascula=id_producto
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);  
                
                    $('#columnchart_material').removeClass('hide');
             
             
             
             $('#myModal').css('color','blue')
             $('.modal-title').html('Información Producto')
             $('.modal-body>p').html('Códigos asociados al código báscula '+'<strong>'+id_producto+'</strong><br><br>'+info+observaciones)
             $('#myModal').modal({
                    backdrop: 'static',
                    keyboard: false
              }) 
             return false
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas venta Directa. Informar");
            }
        })    
        
        
    }) 
    
    
    
    $('a.mi-excel').click(function(e){
            e.preventDefault()
            
           var buscadores=[]
          $('input.searchable-input').each(function( index ) {
              buscadores.push($(this).val())
              //alert($(this).val())
          }) 
            
        // alert('hola') 
        $.ajax({
        type: "POST",
        url: "https://localhost/pernil181/index.php/productos/bajarExcelProductos", 
        data: {buscadores:buscadores,},
        success:function(datos){
          // alert(datos);
           var datos=$.parseJSON(datos)  
          // alert(datos);
           
           
           var direccion="<?php echo base_url() ?>"+datos
                        mywindow=window.open(direccion )
                       // window.location.reload();
                        window.close();
                        
        },
        error: function(){
        }  
        })
          
          
          
                 
       
    })
         
         
         

    //______________________________________
    $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nuevo Producto')
    $(' a[href*="/add"]').attr('id','nuevo')
    
    
    function getUnidadCodigoProducto(codigo_producto){
         $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/productos/getUnidadCodigoProducto/"+codigo_producto, 
        data: {},
        success:function(datos){
           //alert(datos);
           var datos=$.parseJSON(datos)  
           if(datos=='Und') {
                $('#read_precio_ultimo_peso').parent().parent().css('display','none')
                $('#read_tarifa_peso').parent().parent().css('display','none')
                $('#read_stock_minimo').html('Unidades')
                $('#read_unidades_precio').html('Unidades')
                $('#read_unidades_caja').html('Unidades')
           }
           else {
               $('#read_precio_ultimo_unidad').parent().parent().css('display','none')
               $('#read_tarifa_unidad').parent().parent().css('display','none')
               $('#read_stock_minimo').html('Kg')
               $('#read_unidades_precio').html('Kg')
               $('#read_unidades_caja').html('Kg')
            }
           
                        
        },
        error: function(){
        }  
        })
    }
    
    
  
    
    $( window ).load(function() {
        //caso de ver información productos
        $('div.container').addClass('container-fluid')
        $('div.container-fluid').removeClass('container')
        if(verProductos){
            $('#img_producto_read').attr('src',$('#read_url_imagen_portada').html())
            var codigo_producto=($('#field-codigo_producto').html())
            getUnidadCodigoProducto(codigo_producto)
        }
        
        
        //cuando se edita, evita cambiar el tipo de precio unidad/peso
        if($('.table-label').children().html().trim()=='Editar Productos'){
            if($('input[name="precio_ultimo_unidad"]').val()!=""){
                 $('div.precio_ultimo_peso_form_group').addClass('hide')
                 $('div.precio_peso_2_form_group').addClass('hide')
                 $('div.precio_peso_3_form_group').addClass('hide')
                 $('div.tarifa_venta_peso_form_group').addClass('hide')
            }
            if($('input[name="precio_ultimo_peso"]').val()!=""){
                 $('div.precio_ultimo_unidad_form_group').addClass('hide')
                 $('div.precio_unidad_2_form_group').addClass('hide')
                 $('div.precio_unidad_3_form_group').addClass('hide')
                 $('div.tarifa_venta_unidad_form_group').addClass('hide')
            }
        }
           
    });
   
    
    

   
    $('[rel="peso_real"]').removeClass('text-left')
   $('[rel="peso_real"]').addClass('text-right') 
   $('[rel="tarifa_venta"]').removeClass('text-left')
   $('[rel="tarifa_venta"]').addClass('text-right') 
   $('[rel="precio_ultimo"]').removeClass('text-left')
   $('[rel="precio_ultimo"]').addClass('text-right') 
   $('[rel="descuento_1_compra"]').addClass('text-right')      
   $('[rel="margen_real_producto"]').addClass('text-right') 
   
  
 
  
  $('.readonly_label option').remove();
  
 
    
   
    
    
     })
    
    
    
    </script>
 
   
   
    
      