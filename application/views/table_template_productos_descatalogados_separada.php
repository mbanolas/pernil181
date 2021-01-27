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
                <div>
                    <?php echo $output; ?>
                </div>
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
.ir-catalogados{
    margin-right:4px;
}

table.grocery-crud-table th:nth-child(2)
    {
        text-align: center;      
    }
table.grocery-crud-table th:nth-child(4),
table.grocery-crud-table th:nth-child(9)
{
    text-align: left;      
}


</style>


    <script>

    //ventanas modal informando el eliminar un producto
    var id_pe_producto=""
    function eliminarProducto(id,eliminar,texto){
        if(eliminar){
            //alert ("SE ELIMINA " + texto)
            id_pe_producto=id
            $('#pregunta').css('color', 'black')
                $('.modal-title').html('Eliminar producto')
                $('.modal-body>p').html(texto)
                $('#pregunta').modal()

        }else{
            //alert ("NO SE ELIMINA " + texto)
            $('#myModal').css('color', 'red')
                $('.modal-title').html('Eliminar producto')
                $('.modal-body>p').html(texto)
                $('#myModal').modal()
        }
        
    }

     $(document).ready(function(){

    //si se confirma eliminar un producto
    $('#continuar').click(function(){
            //alert('hola se borrar '+id_pe_producto)
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/eliminarProducto/"+id_pe_producto,
                data: {id_pe_producto: id_pe_producto},
                success: function (datos) {
                    //alert(datos);
                    //var datos = $.parseJSON(datos)
                    //alert(datos);
                    location.reload(true);
                },
                error: function () {
                    alert('Error al eliminar producto. Informar')
                }
            })
        })    
        
        
    //eliminar botón búsqueda
    $('input[name="precio_compra"]').addClass('hide')
        $('input[name="tarifa_venta"]').addClass('hide')
        $('input[name="margen_real_producto"]').addClass('hide')
        $('input[name="stock_total"]').addClass('hide')
        $('input[name="valoracion"]').addClass('hide')
        $('input[name="url_imagen_portada"]').addClass('hide')

        //enmarcar y colorear título columna
        $('th[data-order-by="precio_compra"]').css('color','blue')
        $('th[data-order-by="precio_compra"]').css('border','2px solid blue')
        $('th[data-order-by="tarifa_venta"]').css('color','red')
        $('th[data-order-by="tarifa_venta"]').css('border','2px solid red')
        $('th[data-order-by="margen_real_producto"]').css('color','green')
        $('th[data-order-by="margen_real_producto"]').css('border','2px solid green')
    
    $('<a class="btn btn-default t5 ir-catalogados "><i class="fa fa-arrow-circle-right"></i><span class="hidden-xs floatR l5">Ir a Productos CATALOGADOS</span><div class="clear"></div></a>').insertBefore(".mi-clear-filtering");

    $(".ir-catalogados").click(function(){
            var url="<?php echo base_url() ?>index.php/gestionTablasProductos/productos"
            window.location.href = url;
        })
      
    $(' a[href*="/add"]').addClass('hide')  

    $('#gcrud-search-form > div.header-tools > div.floatR > a.btn.btn-default.t5.gc-export').addClass('hide') 
   
     $( window ).load(function() {
        //caso de ver información productos
        $('div.container').addClass('container-fluid')
        $('div.container-fluid').removeClass('container')
        
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
    
    $('body').delegate('a[producto]','click',function(e)  {
        e.preventDefault()
        var producto=$(this).attr('producto');
        
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/productos/activarProducto/"+producto, 
        data:{producto:producto},
        success:function(datos){
           alert(datos)
           var datos=$.parseJSON(datos);
           $(this).parent().parent().parent().remove() 
        },
        error: function(){
                alert("Error en el proceso Activar. Informar");
         }
        })
    })
    
        
    //$('.gc-export').removeAttr('data-url')
    //$('.gc-export').attr('class','btn btn-default t5 mi-excel ')
    
   
    
    $('a.mi-excel_').click(function(e){
        e.preventDefault()
        $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/productos/bajarExcelProductos", 
        data: {},
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
 
   
   
    
      