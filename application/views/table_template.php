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
    


</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
         
    
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    
    

    
 


    <script>
        
$(document).ready(function(){
        
    
/*
   
    $('[rel="peso_real"]').removeClass('text-left')
   $('[rel="peso_real"]').addClass('text-right') 
   $('[rel="tarifa_venta"]').removeClass('text-left')
   $('[rel="tarifa_venta"]').addClass('text-right') 
   $('[rel="precio_ultimo"]').removeClass('text-left')
   $('[rel="precio_ultimo"]').addClass('text-right') 
   $('[rel="descuento_1_compra"]').addClass('text-right')      
   $('[rel="margen_real_producto"]').addClass('text-right')      
         
         
    
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
    
    */
   
    
    
     })
    
    
    
    </script>
    
      