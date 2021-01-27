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


.table thead tr th:nth-child(1){
    text-align: center;
}

.table thead tr th:nth-child(2){
    text-align: left;
}
.table thead tr th:nth-child(3){
    text-align: right;
}
.table thead tr th:nth-child(4){
    text-align: left;
}
.table thead tr th:nth-child(5){
    text-align: left;
}
.table thead tr th:nth-child(6){
    text-align: center;
}

.modal-content{
    width:800px;
    left:-52px;
    
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
    
    
    
   // $('.table-label div:first-child').html('<h4><?php echo $titulo ?></h4>')
    $(' a[href*="ventasDirectas/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nueva Venta Directa')
    
    
    $('body').delegate(' a[href*="ventasDirectas/add"]','click',function(e)  
        {  
            e.preventDefault()
            window.location.replace("<?php echo base_url() ?>"+"index.php/stocks/ventaDirecta");
        })
    
    
    $('body').delegate('tr td> div> a[href*="ventasDirectas"]','click',function(e)  
        {  
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getVentaDirecta", 
        data:{id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            var tabla="<table class='table'>"
                tabla+="<tr><td class='text-left'>Vendido a</td>"
                tabla+='<td class="text-left">'+datos['vendidoA']+'</td></tr>'
                tabla+="<tr><td class='text-left'>Cliente</td>"
                tabla+='<td class="text-left">'+datos['cliente']+'</td></tr>'
                tabla+="<tr><td class='text-left'>Concepto</td>"
                tabla+='<td class="text-left">'+datos['concepto']+'</td></tr>'
                tabla+="<tr><td class='text-left'>Fecha</td>"
                tabla+='<td class="text-left">'+datos['fecha'].substr(8,2)+'/'+datos['fecha'].substr(5,2)+'/'+datos['fecha'].substr(0,4)+'</td></tr>'
                tabla+="<tr><td class='text-left'>Importe total</td>"
                tabla+='<td class="text-left"><strong>'+(datos['importe_total']/100).toFixed(2)+'</strong></td>'
                tabla+="<tr><td class='text-left'>Coste total</td>"
                tabla+='<td class="text-left"><strong>'+(datos['coste_total']/100).toFixed(2)+'</strong></td>'
                tabla+="<tr><td class='text-left'>PVP total</td>"
                tabla+='<td class="text-left"><strong>'+(datos['pvp_total']/100).toFixed(2)+'</strong></td>'
                tabla+='</tr></table>'
                tabla+='Detalles'
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left'>Producto</th>"
                tabla2+='<th class="text-right">Cantidad</th>'
                tabla2+='<th class="text-right">Precio</th>'
                tabla2+='<th class="text-right">Base</th>'
                tabla2+='<th class="text-right">Iva</th>'
                tabla2+='<th class="text-right">Importe</th>'
                tabla2+='<th class="text-right">%Iva</th>'
                tabla2+='<th class="text-right">Coste</th>'
                tabla2+='<th class="text-right">PVP</th>'
                tabla2+='</tr>'
                for(var i=0;i<datos['lineas'].length;i++){
                    datos['lineas'][i]['nombre']=datos['lineas'][i]['nombre']+" ("+datos['lineas'][i]['codigo_producto']+")"
                    var base=(datos['lineas'][i]['importe']/100).toFixed(2)-(datos['lineas'][i]['iva']/100).toFixed(2);
                    tabla2+="<tr><td class='text-left'>"+datos['lineas'][i]['nombre']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['cantidad']+"</td>"
                    tabla2+="<td class='text-right'>"+(datos['lineas'][i]['precio']/100).toFixed(2)+"</td>"
                    tabla2+="<td class='text-right'>"+(base).toFixed(2)+"</td>"
                    tabla2+="<td class='text-right'>"+(datos['lineas'][i]['iva']/100).toFixed(2)+"</td>"
                    tabla2+="<td class='text-right'>"+(datos['lineas'][i]['importe']/100).toFixed(2)+"</td>"
                    tabla2+="<td class='text-right'>"+(datos['lineas'][i]['tipo_iva']/100).toFixed(2)+"</td>"
                    tabla2+="<td class='text-right'>"+(datos['lineas'][i]['coste']/100).toFixed(2)+"</td>"
                    tabla2+="<td class='text-right'>"+(datos['lineas'][i]['pvp']/100).toFixed(2)+"</td>"
                    tabla2+="</tr>"
                }
                
                
                
                tabla2+='</table>'
                
                
            
            $('.modal-title').html('Venta Directa')
            $('.modal-body').html(tabla+tabla2)
            $("#myModal").modal()
            
            
            
            
            
        },
        error: function(){
                alert("Error en el proceso getVentaDirecta. Informar");
         }
    })
        });
    
    
     })
    
    
    
    </script>
    
      