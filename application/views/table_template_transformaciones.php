<style>
    
/*  nuevo sonrisas de bombay */

h1 {
		color: #444;
		background-color: transparent;
		
		font-size: 30px;
		font-weight: normal;
		margin: 0 0 14px 0;
		
	}
        
        div.title_top{
                color: #444;
		background-color: transparent;
		border-bottom: 2px solid #D0D0D0;
		/*font-size: 19px; */
		font-weight: normal;
		margin: 15px 0 14px 0;
		
            
            
            color:red;
        }
        
        div.title_2{
                color: #444;
		background-color: transparent;
		border-bottom: 2px solid #D0D0D0;
		/*font-size: 24px;*/
		font-weight: normal;
		margin: 46px 0 14px 0;
		
            
            
            color:red;
        }
        
        nav#mainNav ul {
    padding: 0 /*0 0 0 1.5rem*/;
    background: #BD236C;
    float: right;
    margin: 0;
    width: 100%;
}
ol, ul, dl, address {
    margin-bottom: 1em;
    font-size: 1em;
}
nav#mainNav {
    max-width: 100%;
}
article, aside, aside2, details, figcaption, figure, footer, header, hgroup, nav, section {
    display: block;
}

nav#mainNav ul li, #tools ul li {
    float: left;
    list-style-type: none;
    padding: 1em .5em 1em .5em;
}
 a{
    /*color:white; */
}

nav#mainNav ul li a {
    padding: 0 1.5em;
}
 
 a:hover {
    /*color:white; */
}

div.flexigrid{
    font-size: 14px;
}
.ftitle{
    font-size: 20px;
   
}

td{
        text-align: left;
    }

    h4{
        font-weight: bold;
    }
    /*
   .modal-content{
        width:750px;
        margin-left: -105px;
    }
    */
     .modal-content{
        width:1200px;
        margin-left: -280px;
    }

</style>


<?php //para incluir título en cabecera tabla
/*
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  */?>

    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
       
   
    <div class="container">
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    </div>
    
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.4/numeral.min.js"></script>

    
 


    <script>
        
$(document).ready(function(){
    
    
    //align cabeceras tabla
    $('th:nth-child(1)').css('text-align','left')
    $('th:nth-child(2)').css('text-align','left')
    $('th:nth-child(3)').css('text-align','left')
    $('th:nth-child(4)').css('text-align','left')
    $('th:nth-child(5)').css('text-align','left')
    $('th:nth-child(6)').css('text-align','left')
    $('th:nth-child(7)').css('text-align','left')
    $('th:nth-child(8)').css('text-align','left')
    $('th:nth-child(9)').css('text-align','left')
    
    
    $('.table-label div:first-child').html('<h4><?php echo $titulo ?></h4>')
    $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nueva Transformación')
    
    
    $('body').delegate(' a[href*="/add"]','click',function(e)  
        {  
            e.preventDefault()
            window.location.assign("<?php echo base_url() ?>"+"index.php/stocks/transformaciones");
        })
    
    
    
    $('#deshacer').click(function(){
        window.location.replace("<?php echo base_url() ?>"+"index.php/stocks/deshacerTransformacion"+"/"+idDeshacer);
    })
    
    var idDeshacer;
    function tablaTransformacion_(datos){
        var tabla="<table class='table'>"
                tabla+="<tr><td class='col-md-2'>Transformación</td>"
                tabla+='<td class="col-md-10">'+datos['nombre_transformacion']+'</td><td width="50"> </td><td width="50"> </td></tr>'
                tabla+="<tr><td class='col-md-2'>Concepto</td>"
                tabla+='<td class="col-md-10">'+datos['concepto']+'</td>'
                tabla+="<tr><td class='col-md-2'>Lote Origen</td>"
                tabla+='<td class="col-md-10">'+datos['lote_origen']+'</td>'
                tabla+="<tr><td class='col-md-2'>Lote Final</td>"
                tabla+='<td class="col-md-10">'+datos['lote_final']+'</td>'
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
                tabla2+='<th class="text-right col-md-1">Peso Original_</th>'
                tabla2+='<th class="text-right col-md-2">Caducidad</th></tr>'
                
                for(var i=0;i<datos['lineas'].length;i++){
                    var cantidad=datos['lineas'][i]['cantidad']/1000;
                     var cantOriginal=datos['lineas'][i]['cantidad']/1000+datos['lineas'][i]['cantidad']/1000*datos['lineas'][i]['perdida']/100
                    if(datos['lineas'][i]['tipoUnidad']=='Und'){
                        cantidad=cantidad.toFixed(0);
                        cantOriginal=""
                    }
                    else{
                        cantidad=cantidad.toFixed(3);
                    }
                    var perdida=datos['lineas'][i]['perdida']!=0?numeral(datos['lineas'][i]['perdida']).format('0.00'):""
                   
                    var color='blue'
                    if(cantidad<0) color='red'
                    tabla2+="<tr style='color:"+color+";'><td class='text-left'>"+datos['lineas'][i]['codigo_producto']+"</td>"
                    tabla2+="<td class='text-left'>"+datos['lineas'][i]['nombre']+"</td>"
                    tabla2+="<td class='text-right'>"+cantidad+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['tipoUnidad']+"</td>"
                    tabla2+="<td class='text-right'>"+perdida+"</td>"
                    tabla2+="<td class='text-right'>"+cantOriginal+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['fecha_caducidad']+"</td>"
                    tabla2+="</tr>"
                }
       
                tabla2+='</table>'
                return tabla+tabla2
    }
    
    
    function tablaTransformacion(datos){
        var tabla="<table class='table'>"
                tabla+="<tr><td class='col-md-2'>Transformación</td>"
                tabla+='<td class="col-md-10">'+datos['nombre_transformacion']+'</td><td width="50"> </td><td width="50"> </td></tr>'
                tabla+="<tr><td class='col-md-2'>Concepto</td>"
                tabla+='<td class="col-md-10">'+datos['concepto']+'</td>'
                tabla+="<tr><td class='col-md-2'>Lote Origen</td>"
                tabla+='<td class="col-md-10">'+datos['lote_origen']+'</td>'
                tabla+="<tr><td class='col-md-2'>Lote Final</td>"
                tabla+='<td class="col-md-10">'+datos['lote_final']+'</td>'
                tabla+="<tr><td class='col-md-2'>Fecha</td>"
                tabla+='<td class="col-md-10">'+datos['fecha'].substr(8,2)+'/'+datos['fecha'].substr(5,2)+'/'+datos['fecha'].substr(0,4)+'</td><td></td><td></td></tr>'
                tabla+="<tr><td class='col-md-2'>Patrón</td>"
                var patron="No"
                if(datos['patron']==1) patron="Sí"
                tabla+='<td class="col-md-10">'+patron+'</td><td></td><td></td></tr>'
                tabla+='</table>'
                tabla+='Detalles'
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left col-md-2'>Código</th>"
                tabla2+="<th class='text-left col-md-6'>Producto</th>"
                tabla2+='<th class="text-right col-md-2">Cantidad</th>'
                
                tabla2+='<th class="text-right col-md-2">Und</th>'
                tabla2+='<th class="text-right col-md-1">% Perdida Peso</th>'
                tabla2+='<th class="text-right col-md-1">Peso Original</th>'
                tabla2+='<th class="text-right col-md-2">Caducidad</th>'
                tabla2+='<th class="text-right col-md-2">Precio/Unidad</th>'
                tabla2+='<th class="text-right col-md-2">Precio Trans/Unidad</th>'
                tabla2+='</tr>'
                for(var i=0;i<datos['lineas'].length;i++){
                    var cantidad=datos['lineas'][i]['cantidad']/1000;
                    if(datos['lineas'][i]['tipoUnidad']=='Und'){
                        cantidad=cantidad.toFixed(0);
                    }
                    else{
                        cantidad=cantidad.toFixed(3);
                    }
                    var perdida=datos['lineas'][i]['perdida']!=0?numeral(datos['lineas'][i]['perdida']/1000).format('0.00'):""
                    var cantOriginal=""
                    if(datos['lineas'][i]['perdida']){
                        cantOriginal=datos['lineas'][i]['cantidad']/1000+datos['lineas'][i]['cantidad']/1000*datos['lineas'][i]['perdida']/100000
                        cantOriginal=numeral(cantOriginal).format('0.000')   
                    }
                    if(datos['lineas'][i]['tipoUnidad']=='Und'){
                        cantOriginal="";
                    }
                    
                    var color='blue'
                    if(cantidad<0) color='red'
                    tabla2+="<tr style='color:"+color+";'><td class='text-left'>"+datos['lineas'][i]['codigo_producto']+"</td>"
                    tabla2+="<td class='text-left'>"+datos['lineas'][i]['nombre']+"</td>"
                    tabla2+="<td class='text-right'>"+cantidad+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['tipoUnidad']+"</td>"
                    tabla2+="<td class='text-right'>"+perdida+"</td>"
                    tabla2+="<td class='text-right'>"+cantOriginal+"</td>"
                    console.log(datos['lineas'][i]['fecha_caducidad'])
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['fecha_caducidad']+"</td>"
                    if(datos['lineas'][i]['precio']==0) 
                        datos['lineas'][i]['precio']='---'
                    else
                        datos['lineas'][i]['precio']=numeral(datos['lineas'][i]['precio']/1000).format('0.000')
                    if(datos['lineas'][i]['precioCalculado']==0) 
                        datos['lineas'][i]['precioCalculado']='---'
                    else
                        datos['lineas'][i]['precioCalculado']=numeral(datos['lineas'][i]['precioCalculado']/1000).format('0.000')
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['precio']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['precioCalculado']+"</td>"
                    tabla2+="</tr>"
                }
       
                tabla2+='</table>'
                return tabla+tabla2
    }
    
    $('body').delegate('tr td> div> div> ul > li > a[href*="deshacerTransformacion"]','click',function(e)  
    {
        
        e.preventDefault()
        idDeshacer=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
        
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getTransformacion", 
        data:{id:idDeshacer},
        success:function(datos){
           //alert(datos)
            var datos=$.parseJSON(datos);
            
            $('.modal-title').html('Transformación '+datos['id_transformacion'])
            $('.modal-body').html(tablaTransformacion(datos))
            $("#confirm-deshacer").modal()
  
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
        
       
    });
    
    $('body').delegate('tr td> div> div> ul> li> a[href*="transformaciones"]','click',function(e)  
        {  
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getTransformacion", 
        data:{id:id},
        success:function(datos){
           //alert(datos)
            var datos=$.parseJSON(datos);
            
            $('.modal-title').html('Transformación '+datos['id_transformacion'])
            $('.modal-body').html(tablaTransformacion(datos))
            $("#myModal").modal()
            
            
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
        });
    
     
   
   
   
         
      function load() {
        var titulo=$('#titulo').val()
       // $('div.ftitle').html(titulo)
        };
        
   // window.onload = load;
   
    
    $('select#field-id_pe_producto').attr('style','width:auto;');
    $('input#field-cantidad').attr('style','width:auto;');
    
    
    
    $('#field-id_pe_producto_buscar').change(function(){
    var filtro=$(this).val()
    buscarProductos(filtro)
    })
    
    buscarProductos("")
  
  $('.readonly_label option').remove();
  
  
  function buscarProductos(filtro){
      
    //var filtro=$(this).val()
    //alert('Hola blur '+$(this).val())
    $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getIdProductosFiltrados", 
        data:{filtro:filtro},
        success:function(datos){
           //alert(datos)
            var datos=$.parseJSON(datos);
            // alert(datos['nombres'])
             $( "select#field-id_pe_producto option" ).remove();
             var option='<option value="0">Seleccionar un producto</option>'
             $('#field-id_pe_producto').append(option)
             $.each(datos['nombres'], function(index, value){
                 var option='<option value="'+datos['id_pe_producto'][index]+'">'+value+' ('+datos['ids'][index]+')</option>'
                 $('#field-id_pe_producto').append(option)
             })
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    
    }
    
    
     })
    
    
    
    </script>
    
      