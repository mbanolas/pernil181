<style>




.table-label div {
	font-weight: bold;
	font-size: 24px;
}

tbody td:nth-child(2),
tbody td:nth-child(3),
tbody td:nth-child(4),
tbody td:nth-child(5),
tbody td:nth-child(6),
tbody td:nth-child(7){
    text-align: right;  
}

tbody td:nth-child(8){
    text-align: left;
}

thead th:nth-child(2),
thead th:nth-child(3),
thead th:nth-child(4),
thead th:nth-child(5),
thead th:nth-child(6),
thead th:nth-child(7){
    text-align: right;  
}

thead th:nth-child(8){
    text-align: left;
}

</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.4/numeral.min.js"></script>

<script>
$(document).ready(function(){
    
 $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Definir Embalaje')
 $(' a[href*="/add"]').attr('id','definir')
 
 
 $('body').delegate('ul li>  a[href*="embalajes/read"]','click',function(e)  
        {  
            
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            //alert(id)
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/productos/getDatosEmbalajes", 
        data:{id:id},
        success:function(datos){
          //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos['datos']['codigo_producto'])
            if(!datos['proveedor']) datos['proveedor']='---'
            var tabla="<table class='table'>"
                tabla+="<tr ><td style='text-align:left;'><strong>Código Producto</strong></td>"
                tabla+="<td style='text-align:left;'>"+datos['datos']['codigo_producto']+"</td></tr>"
                tabla+="<tr><td style='text-align:left;'><strong>Código Báscula</strong></td>"
                tabla+="<td style='text-align:left;'>"+datos['datos']['codigo_bascula']+"</td></tr>"
                tabla+="<tr><td style='text-align:left;'><strong>Nombre Producto</strong></td>"
                tabla+="<td style='text-align:left;'>"+datos['datos']['nombre']+"</td>"
                tabla+="<tr><td style='text-align:left;'><strong>Precio  + Embalaje Tienda (€)</strong></td>"
                var total=parseInt(datos['datos']['precio_compra'])
                total+=parseInt(datos['datosPeEmbalajes']['precio_embalaje_tienda'])
                var precio=numeral(total/1000).format('0.000')+" ("+numeral(datos['datos']['precio_compra']/1000).format('0.000')+" + "+numeral(datos['datosPeEmbalajes']['precio_embalaje_tienda']/1000).format('0.000')+")"
                tabla+="<td style='text-align:left;'>"+precio+"</td>"
                tabla+="<tr><td style='text-align:left;'><strong>Precio + Embalaje Online (€) </strong></td>"
                total=parseInt(datos['datos']['precio_compra'])
                total+=parseInt(datos['datosPeEmbalajes']['precio_embalaje_online'])
                var precio=numeral(total/1000).format('0.000')+" ("+numeral(datos['datos']['precio_compra']/1000).format('0.000')+" + "+numeral(datos['datosPeEmbalajes']['precio_embalaje_online']/1000).format('0.000')+")"
                tabla+="<td style='text-align:left;'>"+precio+"</td>"
                tabla+="<tr><td style='text-align:left;'><strong>Tarifa PVP (€)</strong></td>"
                tabla+="<td style='text-align:left;'>"+numeral(datos['datos']['tarifa_venta']/1000).format('0.00')+"</td>"
                tabla+="<tr><td style='text-align:left;'><strong>IVA (%)</strong></td>"
                tabla+="<td style='text-align:left;'>"+numeral(datos['datos']['iva']/1000).format('0.00')+"</td>"
                tabla+="<tr><td style='text-align:left;'><strong>Margen Tienda (%)</strong></td>"
                tabla+="<td style='text-align:left;'>"+numeral(datos['datosPeEmbalajes']['margen_tienda']/1000).format('0.00')+"</td>"
                tabla+="<tr><td style='text-align:left;'><strong>Margen Online (%)</strong></td>"
                tabla+="<td style='text-align:left;'>"+numeral(datos['datosPeEmbalajes']['margen_online']/1000).format('0.00')+"</td>"
                tabla+="</tr>"
                tabla+='</table>'
                tabla+='Embalajes'
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left'>Código 13</th>"
                tabla2+='<th class="text-left">Descripción</th>'
                tabla2+='<th class="text-right">Cantidad</th>'
                //tabla2+='<th class="text-right">Coste</th>'
                tabla2+='<th class="text-center">Tienda</th>'
                tabla2+='<th class="text-center">Online</th>'
                tabla2+='</tr>'
                
                
                for(var i=0;i<datos['embalajes'].length;i++){
                   
                    if(datos['embalajes'][i]['tipo_unidad']=='Und')  
                    tabla2+="<tr><td class='text-left'>"+datos['embalajes'][i]['codigo_embalaje']+"</td>"
                    tabla2+="<td style='text-align:left;'>"+datos['embalajes'][i]['nombre']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['embalajes'][i]['cantidad']/1000+" "+datos['embalajes'][i]['tipo_unidad']+"</td>"
                    //tabla2+="<td class='text-right'>"+numeral(datos['embalajes'][i]['cantidad']/1000*datos['embalajes'][i]['precio']).format('0.000')+"</td>"
                    var tienda=datos['embalajes'][i]['tienda']==1?numeral(datos['embalajes'][i]['cantidad']/1000*datos['embalajes'][i]['precio']).format('0.000'):" "
                    var online=datos['embalajes'][i]['online']==1?numeral(datos['embalajes'][i]['cantidad']/1000*datos['embalajes'][i]['precio']).format('0.000'):" "
                    tabla2+="<td class='text-center'>"+tienda+"</td>"
                    tabla2+="<td class='text-center'>"+online+"</td>"
                    
                    
                    tabla2+="</tr>"
                }
                 tabla2+="<tr><th class='text-left'>Totales</th>"
                tabla2+="<th class='text-left'></th>"
                
                tabla2+="<th class='text-left'></th>"
                tabla2+="<th class='text-rigth'>"+numeral(datos['datosPeEmbalajes']['precio_embalaje_tienda']/1000).format('0.000')+"</th>"
                tabla2+="<th class='text-rigth'>"+numeral(datos['datosPeEmbalajes']['precio_embalaje_online']/1000).format('0.000')+"</th>"
                tabla2+="</tr>"
                tabla2+='</table>'
                
                
            
            $('.modal-title').html('Embalajes Producto')
            $('.modal-body').html(tabla+tabla2)
            $("#myModal").modal()
            
            
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
            
    })
 
 //introducir datos embalajes producto
 $('body').delegate(' a[href*="/add"]','click',function(e) {  
        e.preventDefault()
        window.location.replace("<?php echo base_url() ?>"+"index.php/productos/embalajes");
 })
    
//editar datos embalajes producto
 $('body').delegate(' a[href*="/edit"]','click',function(e) {  
        e.preventDefault()
        var href=$(this).attr('href')
        var pos=href.lastIndexOf("/");
        var id=href.substring(pos+1);
        
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/productos/getIdPeProductoEmbalaje", 
        data:{id:id},
        success:function(datos){
            // alert(datos)
             
            var datos=$.parseJSON(datos);
            //alert(datos)
            //alert("<?php echo base_url() ?>"+"index.php/productos/embalajes/"+datos)
            var output = datos;
           //  alert('dataFromParent'+datos)
            window.location.replace("<?php echo base_url() ?>"+"index.php/productos/embalajes/"+datos);
           // var OpenWindow = window.open("<?php echo base_url() ?>"+"index.php/productos/embalajes");
           
           // OpenWindow.dataFromParent = output; // dataFromParent is a variable in child.html
           // OpenWindow.init();
          
           //window.close();
            
            
             
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
        
        /*
        var output = id;
        var OpenWindow = window.open("<?php echo base_url() ?>"+"index.php/productos/embalajes");
        OpenWindow.dataFromParent = output; // dataFromParent is a variable in child.html
        //OpenWindow.init();
        window.close();
        //window.location.replace("<?php echo base_url() ?>"+"index.php/productos/embalajes");
        */
 })    

})
</script>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  
?>

<input type="hidden" id="titulo" value="<?php echo $titulo ?>">
   
<div class="row">
    <div class="col-xs-<?php echo $col_bootstrap ?>">

        <?php echo $output; ?>
    </div>
</div>
