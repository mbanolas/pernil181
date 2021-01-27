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
    /*color:white;*/
}

nav#mainNav ul li a {
    padding: 0 1.5em;
}
 
 a:hover {
   /* color:white; */
}


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
    
    .modal-content{
    width:800px;
    left:-52px;
    
}

#irAFacturas{
    margin-left:5px;
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
    

    
 


    <script>
        
$(document).ready(function(){
    //align cabeceras tabla
    $('th:nth-child(1)').css('text-align','left')
    $('th:nth-child(2)').css('text-align','left')
    $('th:nth-child(3)').css('text-align','left')
    $('th:nth-child(4)').css('text-align','left')
   
   //añadir boton acceso directo a facturas proveedores
   $('#gcrud-search-form > div.header-tools > div.floatL.t5 > a').after('<a class="btn btn-default" id="irAFacturas"><i class="fa fa-share"></i><span class="hidden-xs floatR l5"> &nbsp;Ir a tabla Facturas Proveedores</span></a>')

$('#irAFacturas').click(function(){
    var url="<?php echo base_url() ?>index.php/gestionTablas/facturasProveedores"
    window.location.href = url;
}) 
    
    //$('.table-label div:first-child').html('<h4><?php echo $titulo ?></h4>')
    $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nuevo Albarán')
    
    
    $('body').delegate(' a[href*="/add"]','click',function(e)  
        {  
            e.preventDefault()
           
            window.location.replace("<?php echo base_url() ?>"+"index.php/stocks/albaran");
        })
    
    
    $('body').delegate('tr td> div> a[href*="albaranes"]','click',function(e)  
        {  
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            //alert (id)
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getDatosViewAlbaran", 
        data:{id_albaran:id},
        success:function(datos){
           //alert(datos)
            var datos=$.parseJSON(datos);
            if(!datos['proveedor']) datos['proveedor']='---'
            var tabla="<table class='table'>"
                tabla+="<tr><td>Proveedor</td>"
                tabla+='<td>'+datos['proveedor']+'</td></tr>'
                tabla+="<tr><td>Pedido</td>"
                if(datos['pedido']!=='---'){
                    tabla+='<td><a href="<?php echo base_url() ?>/pedidos/'+datos['pedido']+'">'+datos['pedido']+'</a></td></tr>'
                }
                else {
                    tabla+='<td>'+datos['pedido']+'</td></tr>'

                }
                tabla+="<tr><td>Fecha</td>"
                tabla+='<td>'+datos['fecha'].substr(8,2)+'/'+datos['fecha'].substr(5,2)+'/'+datos['fecha'].substr(0,4)+'</td></tr>'
                tabla+="</tr>"
                tabla+='</table>'
                tabla+='Detalles'
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left'>Producto</th>"
                tabla2+='<th class="text-right">Unidades</th>'
                tabla2+='<th class="text-right">Peso (Kg)</th>'
                tabla2+='<th class="text-right">Fecha caducidad</th>'
                
                for(var i=0;i<datos['lineas'].length;i++){
                    var cantidadPeso=""
                    var cantidadUnd=""
                    if(datos['lineas'][i]['tipoUnidad']=='Und')  cantidadUnd=datos['lineas'][i]['cantidad']
                    else {cantidadPeso=datos['lineas'][i]['cantidad']
                        cantidadPeso=parseInt(parseFloat(cantidadPeso)*1000)/1000
                        cantidadPeso=cantidadPeso.toFixed(3)
                           //cantidadPeso=cantidadPeso.toFixed(3) 
                       }
                    if(datos['lineas'][i]['cantidad']!=0) {  
                        //solo se visualiza las lineas que no son 0
                        tabla2+="<tr><td class='text-left'>"+datos['lineas'][i]['nombre']+"</td>"
                        tabla2+="<td class='text-right'>"+cantidadUnd+"</td>"
                        tabla2+="<td class='text-right'>"+cantidadPeso+"</td>"
                        var fecha_caducidad=datos['lineas'][i]['fecha_caducidad']
                        if(fecha_caducidad==='0000-00-00') fecha_caducidad='----'
                        else fecha_caducidad=fecha_caducidad.substr(8,2)+'/'+fecha_caducidad.substr(5,2)+'/'+fecha_caducidad.substr(0,4)
                        tabla2+="<td class='text-right'>"+fecha_caducidad+"</td>"
                        tabla2+="</tr>"
                    }
                    
                }
                
                
                
                tabla2+='</table>'
                
                
            
            $('.modal-title').html('Albarán')
            $('.modal-body').html(tabla+tabla2)
            $("#myModal").modal()
            
            
            
            
        },
        error: function(){
                alert("Error en el proceso Albaranes 1. Informar");
         }
    })
        });
    
     
   
   
    
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
    //alert('Hola blur '+"<?php echo base_url() ?>"+"index.php/compras/getIdProductosFiltrados")
   //alert(filtro)
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
                alert("Error en el proceso Albaranes 2. Informar");
         }
    })
    
    }
    
    
   
    
    
     })
    
    
    
    </script>
    
      