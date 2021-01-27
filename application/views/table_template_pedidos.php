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
    
.table thead tr th:nth-child(1){
    text-align: center;
}

.table thead tr th:nth-child(2){
    text-align: left;
}
.table thead tr th:nth-child(3){
    text-align: left;
} 
.table thead tr th:nth-child(4){
    text-align: left;
} 
.table thead tr th:nth-child(5){
    text-align: left;
} 
.table thead tr th:nth-child(6){
    text-align: left;
} 
.table thead tr th:nth-child(7){
    text-align: left;
} 
.table thead tr th:nth-child(8){
    text-align: left;
} 

 #irAAlbaranes{
    margin-left: 5px;
}

	
     .modal-content{
    width:800px;
    left:-52px;
     }
</style>


<?php //para incluir título en cabecera tabla
   /* $titulo=isset($titulo)?$titulo:'Sin Título' ;
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
       // alert('hola');
       //alert($('button.dropdown-toggle').children())
        
        $('body').delegate('a.delete-row','',function(e) {
            //alert('hola')
            $(this).addClass('borrar-fila') 
            $(this).removeClass('delete-row')
        })
      
       //añadir boton acceso directo a albarán
       $('#gcrud-search-form > div.header-tools > div.floatL.t5 > a').after('<a class="btn btn-default" id="irAAlbaranes"><i class="fa fa-share"></i><span class="hidden-xs floatR l5"> &nbsp;Ir a tabla Albaranes</span></a>')

        $('#irAAlbaranes').click(function(){
            var url="<?php echo base_url() ?>index.php/gestionTablas/albaranes"
            window.location.href = url;
        })
   
   
     
    $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nuevo Pedido a Proveedor')
    
    $('body').delegate(' a[href*="/add"]','click',function(e)  
        {  
            e.preventDefault()
            window.location.replace("<?php echo base_url() ?>"+"index.php/compras/pedidoProveedorNuevo");
        })
        
      //definir ventana modal de 'Ver'  
      $('body').delegate('tr td> div> a[href*="pedidos"]','click',function(e)  
        {  
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getPedido", 
        data:{id:id},
        success:function(datos){
           //alert(datos)
            var datos=$.parseJSON(datos);
            var tabla="<table class='table'>"
                tabla+="<tr><td>Núm pedido</td>"
                tabla+='<td>'+datos['numPedido']+'</td></tr>'
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
                tabla+="<tr><td>Importe total</td>"
                tabla+='<td>'+datos['importe']+'</td>'
                tabla+="</tr>"
                tabla+='</table>'
                tabla+='Detalles'
                
            var tabla2="<table class='table'>" 
                tabla2+="<tr><th class='text-left'>Producto</th>"
                tabla2+='<th class="text-right">Cantidad</th>'
                tabla2+='<th class="text-right">Und</th>'
                tabla2+='<th class="text-right">Precio</th>'
                tabla2+='<th class="text-right">Dto</th>'
                tabla2+='<th class="text-right">Total</th></tr>'
                
                var ttotal=0
                for(var i=0;i<datos['lineas'].length;i++){
                    
                    tabla2+="<tr><td class='text-left'>"+datos['lineas'][i]['nombre']+' ('+datos['lineas'][i]['codigo_producto']+')'+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['cantidad']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['tipoUnidad']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['precio']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['descuento']+"</td>"
                    tabla2+="<td class='text-right'>"+datos['lineas'][i]['total']+"</td>"
                    tabla2+="</tr>"
                    
                    ttotal+=parseFloat(datos['lineas'][i]['total'].replace(/,/g, ""))
                    
                    

                }
                tabla2+="<tr><td>Otros Costes</td>"
                tabla2+="<td> </td>"
                tabla2+="<td> </td>"
                tabla2+="<td> </td>"
                tabla2+="<td> </td>"
                tabla2+="<td class='text-right'>"+(parseFloat(datos['otrosCostes']/100)).toFixed(2)+"</td></tr>"
               // ttotal+=parseFloat(datos['lineas'][i]['total'])
                ttotal+=parseFloat(datos['otrosCostes']/100)
                tabla2+="<tfooter><tr>"
                tabla2+="<th> </th>"
                tabla2+="<th> </th>"
                tabla2+="<th> </th>"
                  tabla2+="<th> </th>"
                tabla2+="<th> </th>"
                tabla2+="<th>"+ttotal.formatMoney(2,'.',',')+"</th>"
                tabla2+="</tr></tfooter>"
                tabla2+='</table>'
                
                
            
            $('.modal-title').html('Pedido a proveedor')
            $('.modal-body').html(tabla+tabla2)
            $("#myModal").modal()
            
            
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
        });   
      
      $('body').delegate('.delete-confirmation-button','click',function(e) {
        e.preventDefault()
        //alert('hola')
        //alert($(this).attr('data-target').substr($(this).attr('data-target').lastIndexOf("/")+1))
        return false
      })
      
      
      
})    
    </script>