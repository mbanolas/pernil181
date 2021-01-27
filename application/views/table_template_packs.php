<style>




.table-label div {
	font-weight: bold;
	font-size: 24px;
}



tbody td:nth-child(4),
tbody td:nth-child(5),
tbody td:nth-child(6),
tbody td:nth-child(7),tbody td:nth-child(8){
    text-align: right;  
}

tbody td:nth-child(3){
    text-align: left;
}
tbody td:nth-child(2){
    text-align: center;
}
thead th:nth-child(2){
    text-align: center;
}

thead th:nth-child(4),
thead th:nth-child(5),
thead th:nth-child(6),
thead th:nth-child(7),thead th:nth-child(8){
    text-align: right;  
}

thead th:nth-child(3){
    text-align: left;
}

 .modal-content{
        width:1200px;
        margin-left: -280px;
    }

</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.4/numeral.min.js"></script>

<script>
$(document).ready(function(){
    
 $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Definir Nuevo Pack')
 $(' a[href*="/add"]').attr('id','definir')
 
 
 $('body').delegate('ul li>  a[href*="packs/read"]','click',function(e)  
        {  
            
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            //alert(id)
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/productos/getDatosPacks", 
        data:{id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            //alert(datos['datos']['codigo_producto'])
            if(!datos['proveedor']) datos['proveedor']='---'
            var tabla="<table class='table'>"
                tabla+="<tr ><td style='text-align:left;'><strong>Código Pack</strong></td>"
                tabla+="<td style='text-align:left;'>"+datos['datos']['codigo_producto']+"</td></tr>"
                
                tabla+="<tr><td style='text-align:left;'><strong>Nombre Producto</strong></td>"
                tabla+="<td style='text-align:left;'>"+datos['datos']['nombre']+"</td>"
                
                tabla+="<tr><td style='text-align:left;'><strong>Coste Pack (€)</strong></td>"
                var total=parseInt(datos['datos']['precio_compra'])
                var precio=numeral(total/1000).format('0.000')
                tabla+="<td style='text-align:left;'>"+precio+"</td>"
                
                tabla+="<tr><td style='text-align:left;'><strong>Tarifa PVP Pack (€)</strong></td>"
                tabla+="<td style='text-align:left;'>"+numeral(datos['datos']['tarifa_venta']/1000).format('0.00')+"</td>"
                
                tabla+="<tr><td style='text-align:left;'><strong>Margen Pack (%)</strong></td>"
                tabla+="<td style='text-align:left;'>"+numeral(datos['datosPePacks']['margen_pack']/1000).format('0.00')+"</td>"
                
                //tabla+="<tr><td style='text-align:left;'><strong>Margen Pack (%)</strong></td>"
                //tabla+="<td style='text-align:left;'>"+numeral(datos['datosPeEmbalajes']['margen_tienda']/1000).format('0.00')+"</td>"
                //tabla+="<tr><td style='text-align:left;'><strong>Margen Online (%)</strong></td>"
                //tabla+="<td style='text-align:left;'>"+numeral(datos['datosPeEmbalajes']['margen_online']/1000).format('0.00')+"</td>"
                //tabla+="</tr>"
                tabla+='</table>'
                tabla+='Productos Pack'
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left'>Código 13</th>"
                tabla2+='<th class="text-left">Descripción</th>'
                tabla2+='<th class="text-left">Cant.</th>'
                tabla2+='<th class="text-right">Coste</th>'
                //tabla2+='<th class="text-center">Coste</th>'
                tabla2+='<th class="text-right">PVP </th>'
                tabla2+='<th class="text-right">Desc.(%)</th>'
                tabla2+='<th class="text-right">PVP Pack</th>'
                tabla2+='<th class="text-right">Tipo iva (%)</th>'
                tabla2+='<th class="text-right">Base</th>'
                tabla2+='<th class="text-right">IVA</th>'
                tabla2+='</tr>'
                
                var totalCantidad=0
                var totalPVP=0
                var totalPrecio=0
                var totalPVPPack=0
                var totalBase=0
                var totalIva=0
                for(var i=0;i<datos['packs'].length;i++){
                   
                    //if(datos['embalajes'][i]['tipo_unidad']=='Und')  
                    
                    tabla2+="<tr><td class='text-left'>"+datos['packs'][i]['codigo_producto']+"</td>"
                    tabla2+="<td style='text-align:left;'>"+datos['packs'][i]['nombre']+"</td>"
                    tabla2+="<td class='text-right'>"+numeral(datos['packs'][i]['cantidad']/1000).format(0)+"</td>"
                    totalCantidad+=Number(datos['packs'][i]['cantidad'])
                    var cantPvp=Number(datos['packs'][i]['cantidad']/1000)*Number(datos['packs'][i]['pvp']/1000)
                    var cantPrecio=Number(datos['packs'][i]['cantidad']/1000)*Number(datos['packs'][i]['precio'])
                    //tabla2+="<td class='text-right'>"+numeral(datos['embalajes'][i]['cantidad']/1000*datos['embalajes'][i]['precio']).format('0.000')+"</td>"
                   // var tienda=datos['embalajes'][i]['tienda']==1?numeral(datos['embalajes'][i]['cantidad']/1000*datos['embalajes'][i]['precio']).format('0.000'):" "
                   // var online=datos['embalajes'][i]['online']==1?numeral(datos['embalajes'][i]['cantidad']/1000*datos['embalajes'][i]['precio']).format('0.000'):" "
                    tabla2+="<td class='text-right'>"+numeral(cantPrecio).format('0.00')+"</td>"
                    tabla2+="<td class='text-right'>"+numeral(cantPvp).format('0.00')+"</td>"
                    totalPVP+=Math.round(cantPvp*100)/100
                    totalPrecio+=Math.round(cantPrecio*100)/100
                    tabla2+="<td class='text-right'>"+numeral(datos['packs'][i]['descuento']/1000).format('0.00')+"</td>"
                    var pvpPack=cantPvp-cantPvp*datos['packs'][i]['descuento']/100000
                    tabla2+="<td class='text-right'>"+numeral(pvpPack).format('0.00')+"</td>"
                    var pvpPackN=Number(numeral(pvpPack).format('0.00'))
                    var tipoIva=datos['packs'][i]['iva']/1000
                   
                    var iva=pvpPackN*tipoIva/(100+tipoIva)
                    var base=pvpPackN*100/(100+tipoIva)
                    tabla2+="<td class='text-right'>"+numeral(tipoIva).format('0')+"</td>"
                    tabla2+="<td class='text-right'>"+numeral(base).format('0.00')+"</td>"
                    tabla2+="<td class='text-right'>"+numeral(iva).format('0.00')+"</td>"
                     totalPVPPack+=(pvpPackN)
                     totalBase+=(base)
                     totalIva+=(iva)
                    tabla2+="</tr>"
                }
                 tabla2+="<tr><th class='text-left'>Totales</th>"
                tabla2+="<th class='text-left'></th>"
                
               tabla2+="<th class='text-left'>"+numeral(totalCantidad/1000).format('0')+" "+"</th>"
               tabla2+="<th class='text-right'>"+numeral(totalPrecio).format('0.00')+" "+"</th>"
               tabla2+="<th class='text-right'>"+numeral(totalPVP).format('0.00')+" "+"</th>"
               tabla2+="<th class='text-left'></th>"
               tabla2+="<th class='text-right'>"+numeral(totalPVPPack).format('0.00')+" "+"</th>"
               var tipoIvaPromedio=totalIva/totalBase*100
                tabla2+="<th class='text-right'>"+numeral(tipoIvaPromedio).format('0.00')+"</th>"
               tabla2+="<th class='text-right'>"+numeral(totalBase).format('0.00')+" "+"</th>"
               tabla2+="<th class='text-right'>"+numeral(totalIva).format('0.00')+" "+"</th>"
               // tabla2+="<th class='text-rigth'>"+numeral(datos['datosPeEmbalajes']['precio_embalaje_tienda']/1000).format('0.000')+"</th>"
               // tabla2+="<th class='text-rigth'>"+numeral(datos['datosPeEmbalajes']['precio_embalaje_online']/1000).format('0.000')+"</th>"
                tabla2+="</tr>"
                tabla2+='</table>'
                
                
            
            $('.modal-title').html('Pack Productos')
            $('.modal-body').html(tabla+tabla2)
            $("#myModal").modal()
            
            
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
            
    })
 
 //introducir datos packs producto
 $('body').delegate(' a[href*="/add"]','click',function(e) {  
        e.preventDefault()
        window.location.replace("<?php echo base_url() ?>"+"index.php/productos/packs");
 })
    
//editar datos embalajes producto
 $('body').delegate(' a[href*="/edit"]','click',function(e) {  
        e.preventDefault()
        var href=$(this).attr('href')
        var pos=href.lastIndexOf("/");
        var id=href.substring(pos+1);
        
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/productos/getIdPePack", 
        data:{id:id},
        success:function(datos){
            // alert(datos)
             
            var datos=$.parseJSON(datos);
            //alert(datos)
            //alert("<?php echo base_url() ?>"+"index.php/productos/embalajes/"+datos)
            var output = datos;
           //  alert('dataFromParent'+datos)
            window.location.replace("<?php echo base_url() ?>"+"index.php/productos/packs/"+datos);
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

