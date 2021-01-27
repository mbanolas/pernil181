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

    /* para cololear la fila hover */
   
    .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: lightblue;
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

<?php
//para incluir título en cabecera tabla
$titulo = isset($titulo) ? $titulo : 'Sin Título';
$col_bootstrap = isset($col_bootstrap) ? $col_bootstrap : 10;
?>
<input type="hidden" id="titulo" value="<?php echo $titulo; ?>">
<input type="hidden" id="categoria" value="<?php echo $this->session->categoria; ?>">
<input type="hidden" id="mensajeAuxiliar" value="<?php echo $this->session->mensajeAuxiliar; ?>">

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

/* para cambiar el ancho ventana modal */
     .modal-dialog {
        width: 80%;
        margin: 0 auto;
        margin-top: 15px;
    }

    div.form-group{
        margin-bottom: 2px;
    }

    #field-fecha_alta,#field-fecha_proveedor_2{
        width:100px;
    }
    #field-nombre,
    #field-nombre_generico,
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
    #field-beneficio_recomendado,
    #field-descuento_profesionales,
    #field-descuento_profesionales_vip,
    #field-margen_real_producto,
    #field-tarifa_profesionales,
    #field-tarifa_profesionales_vip,
    #field-precio_compra,
    #field-tipo_unidad,
    #field-precio_transformacion_unidad,
    #field-precio_transformacion_peso
    {
        text-align: right;
        width:100px;
        padding-right: 5px;
    }
    input[disabled]{
        background-color:#EDE9EB;
    }

    #field-id_producto,#field-anada,#field-codigo_ean{
        text-align: left;
        width:100px;
        padding-left: 5px;
    }
    #field-codigo_producto{
        text-align: left;
        width:120px;
        padding-left: 5px;
    }
    #field-codigo_ean{
        text-align: left;
        width:150px;
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
        left: 150px;
        top: 5px;
        z-index: 10;
        border-style: solid;
        border-color:lightgrey;
        border-width: 1px;
        border-radius: 10px;
        height: 190px;
        width:190px;
    }

    

    

    #field-cat_nombre,
    #field-cat_marca,
    #field-cat_referencia,
    #field-cat_orden,
    #field-cat_url_producto,
    #field-cat_origen,
    #field-cat_raza,
    #field-cat_curado,
    #field-cat_pesos,
    #field-cat_anada,
    #field-cat_formato,
    #field-cat_unidades_caja,
    #field-cat_ecologica,
    #field-cat_tipo_de_uva,
    #field-cat_volumen,
    #field-cat_variedades,
    #field-cat_descripcion,
    #field-cat_tarifa,
    #field-cat_unidad

    {
        background-color:lightcyan;
        border-radius: 0px;
        height: 0%;
        padding: 4px;

    }

    #field-cat_nombre_en,
    #field-cat_marca_en,
    #field-cat_referencia_en,
    #field-cat_orden_en,
    #field-cat_url_producto_en,
    #field-cat_origen_en,
    #field-cat_raza_en,
    #field-cat_curado_en,
    #field-cat_pesos_en,
    #field-cat_anada_en,
    #field-cat_formato_en,
    #field-cat_unidades_caja_en,
    #field-cat_ecologica_en,
    #field-cat_tipo_de_uva_en,
    #field-cat_volumen_en,
    #field-cat_variedades_en,
    #field-cat_descripcion_en,
    #field-cat_tarifa_en,
    #field-cat_unidad_en

    {
        background-color:#ffe6e6;
        border-radius: 0px;
        height: 0%;
        padding: 4px;

    }

    #field-cat_nombre_fr,
    #field-cat_marca_fr,
    #field-cat_referencia_fr,
    #field-cat_orden_fr,
    #field-cat_url_producto_fr,
    #field-cat_origen_fr,
    #field-cat_raza_fr,
    #field-cat_curado_fr,
    #field-cat_pesos_fr,
    #field-cat_anada_fr,
    #field-cat_formato_fr,
    #field-cat_unidades_caja_fr,
    #field-cat_ecologica_fr,
    #field-cat_tipo_de_uva_fr,
    #field-cat_volumen_fr,
    #field-cat_variedades_fr,
    #field-cat_descripcion_fr,
    #field-cat_tarifa_fr,
    #field-cat_unidad_fr

    {
        background-color:#ddff99;
        border-radius: 0px;
        height: 0%;
        padding: 4px;

    }

    table.grocery-crud-table th,
    table.grocery-crud-table th:nth-child(11),
    table.grocery-crud-table th:nth-child(12){
        text-align: right;      
    }
    table.grocery-crud-table th:nth-child(2){
        text-align: center;      
    }
    table.grocery-crud-table th:nth-child(4),
    table.grocery-crud-table th:nth-child(6),
    table.grocery-crud-table th:nth-child(8)

    {
        text-align: left;      
    }

    .rueda{
        border: 0px;
        background-color: white;
        vertical-align: central;
        display:none;
    }
    
    .paddingTopTexto{
        padding-top:6px;
    }
.ir-descatalogados{
    margin-right:4px;
}


#gcrud-search-form > table > tbody > tr > td:nth-child(7){
    color:blue;
    font-weight: bold;
}


#gcrud-search-form > table > tbody > tr > td:nth-child(9){
    color:red;
    font-weight: bold;
}


#gcrud-search-form > table > tbody > tr > td:nth-child(10){
    color:green;
    font-weight: bold;
}

#gcrud-search-form > table > tbody > tr > td:nth-child(11){
    text-align: right;
}
#gcrud-search-form > table > tbody > tr > td:nth-child(12){
    text-align: right;
}
#gcrud-search-form > table > tbody > tr > td:nth-child(7){
    text-align: right;
}
#nuevo{
    margin-right: 5px;
}
.mi-nuevo-excel {
    border: 2px solid red;
}

</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.4/numeral.min.js"></script>
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

    $(document).ready(function () {
        var ahora=new Date()
        //alert(ahora.getMinutes()+':'+ahora.getSeconds()+'.'+ahora.getMilliseconds())
        // $('#gcrud-search-form > div.header-tools > div.floatR > a.btn.btn-default.t5.mi-clear-filtering').before('<a  href="<?= base_url() ?>index.php/productos/exportExcel" class="btn btn-default t5 botonSuperior mi-nuevo-excel"><i class="fa fa-cloud-download"></i><span class="hidden-xs floatR l5">Exportar selección</span><div class="clear"></div></a>')

        $('#gcrud-search-form > div.header-tools > div.floatR > a.btn.btn-default.t5.gc-export').addClass('hide')
     
        var buscadores=[]
        var href_inicial="<?= base_url() ?>index.php/productos/exportExcel"
        var href=href_inicial
        $('input.searchable-input').each(function( index ) {
            buscadores.push($(this).val())
            
            if($(this).val()=="") {
                href+="/"+"_"
            } else {
                href+="/"+$(this).val().replace("/","-").replace("/","-").replace("/","-").replace("%20"," ")
            }
            // console.log(index+' '+$(this).val())
        })
        $('a.mi-nuevo-excel').attr('href',href)



    $('input.searchable-input').keyup(function(){
        var buscadores=[]
        var href_inicial="<?= base_url() ?>index.php/productos/exportExcel"
        var href=href_inicial
        $('input.searchable-input').each(function( index ) {
            buscadores.push($(this).val())
            if($(this).val()=="") {
                href+="/"+"_"
            } else {
                href+="/"+$(this).val().replace("/","-").replace("/","-").replace("/","-").replace("%20"," ")
            }
            // console.log(index+' '+$(this).val())
        })
        $('a.mi-nuevo-excel').attr('href',href)
    })

     
     
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

        $('div.container').addClass('container-fluid')
        $('div.container-fluid').removeClass('container')


        

/*
        $('body > div.container-fluid > div.row > div > div > div.row > div.delete-confirmation.modal.fade.in > div > div > div.modal-footer > button.btn.btn-default').click(function(){
            console.log('cancel modal')
            window.locatiom.href="http://localhost:8888/pernil181/index.php/gestionTablasProductos/productos"
        })
        $('body > div.container-fluid > div.row > div > div > div.row > div.delete-confirmation.modal.fade.in > div > div > div.modal-footer > button.btn.btn-danger.delete-confirmation-button').click(function(){
            console.log('eliminar modal')
            window.locatiom.href="http://localhost:8888/pernil181/index.php/gestionTablasProductos/productos"
        })
*/
        $('.delete-row_').click(function(){
            // console.log('delete row')
            $('.delete-confirmation').on('show.bs.modal', function() { 
                $('.modal-body >p').html('<?php echo $this->session->mensajeAuxiliar; ?>')
                $('button.delete-confirmation-button').addClass('hide')
                $('[data-dimiss="modal"]').addClass('hide')
                // console.log('desde modal')
            })
            return;
        })

        //eliminar producto
        //$('#gcrud-search-form > table > tbody > tr > td > div.only-desktops > div > ul > li:nth-child(4) > a')
   
   /*     $('#gcrud-search-form > table > tbody > tr > td:nth-child(1) > div.only-desktops > div > ul > li:nth-child(4) > a > span').click(function(){
            alert('paso')
        })
        $('#gcrud-search-form > table > tbody > tr > td > div.only-desktops > div > ul > li:nth-child(4) > a ').click(function(e){
            e.preventDefault()
            var dataTarget=$(this).parent().attr('data-target')
            var n=dataTarget.lastIndexOf("/");
            var id=dataTarget.substr(n+1);
            alert(id)
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/checkPosibilityToEliminate/"+id,
                data: {id: id},
                success: function (datos) {
                    var datos = $.parseJSON(datos)
                    alert(datos);
                    return;
                },
                error: function () {
                    alert('Error eliminar producto. Informar')
                }
            })
            
        })
*/
        //eliminar botón búsqueda
        $('input.searchable-input[name="precio_compra"]').addClass('hide')
        $('input.searchable-input[name="tarifa_venta"]').addClass('hide')
        $('input.searchable-input[name="margen_real_producto"]').addClass('hide')
        $('input.searchable-input[name="stock_total"]').addClass('hide')
        $('input.searchable-input[name="valoracion"]').addClass('hide')
        $('input.searchable-input[name="url_imagen_portada"]').addClass('hide')

        //enmarcar y colorear título columna
        $('th[data-order-by="precio_compra"]').css('color','blue')
        $('th[data-order-by="precio_compra"]').css('border','2px solid blue')
        $('th[data-order-by="tarifa_venta"]').css('color','red')
        $('th[data-order-by="tarifa_venta"]').css('border','2px solid red')
        $('th[data-order-by="margen_real_producto"]').css('color','green')
        $('th[data-order-by="margen_real_producto"]').css('border','2px solid green')

        $('#field-codigo_ean').parent().addClass('col-sm-3')
        $('#field-codigo_ean').parent().removeClass('col-sm-9')
        var img_producto=$('#field-url_imagen_portada').val()
        $('.codigo_ean_form_group').append('<div class="col-sm-4"><img id="img_producto" src="'+img_producto+'" alt=""></img></div>')
         
        $('#field-url_imagen_portada').change(function(){
            // console.log(' cambio imagen producto')
            img_producto=$('#field-url_imagen_portada').val()
            // console.log(img_producto)
            $('img#img_producto').attr('src',img_producto)
        })
        $('div#field-codigo_producto').parent().addClass('col-sm-2')
        $('div#field-codigo_producto').parent().removeClass('col-sm-9')
        $('div#field-codigo_producto').parent().parent().addClass('botonVolver')
        //boton volver superior de ver
        var botonVolver='<div class="col-sm-2"><button class="btn btn-default cancel-button volver" type="button"><i class="fa fa-arrow-left"></i> Volver a la lista</button></div>'
        $('.botonVolver').append(botonVolver)
        $('.volver').click(function(){
            window.location = '<?php echo base_url() ?>index.php/gestionTablasProductos/productos/'
        })


        
       
        $('<a class="btn btn-default t5 ir-descatalogados "><i class="fa fa-arrow-circle-right"></i><span class="hidden-xs floatR l5">Ir a Productos DESCATALOGADOS</span><div class="clear"></div></a>').insertBefore(".mi-clear-filtering");
        
        

        $(".ir-descatalogados").click(function(){
            var url="<?php echo base_url() ?>index.php/gestionTablasProductos/productosDescatalogados"
            window.location.href = url;
        })
       
       var pack=$('#field_id_grupo_chosen').children().children().html()==="Packs productos"
      
       if(pack){
           //se impide editar algunos campos.
           $('#field-precio_ultimo_unidad').hide()
           var valor=$('#field-precio_ultimo_unidad').val()
           var divValor=$('#field-precio_ultimo_unidad').parent()
           $(divValor).addClass('paddingTopTexto')
           var inputValor=$('#field-precio_ultimo_unidad').parent().children()
           $('#field-precio_ultimo_unidad').parent().html("")
           $(divValor).append(inputValor)
           $(divValor).append(valor+'  €/Unidad')
           
           $('#field-tarifa_venta_unidad').hide()
           valor=$('#field-tarifa_venta_unidad').val()
           divValor=$('#field-tarifa_venta_unidad').parent()
           $(divValor).addClass('paddingTopTexto')
           inputValor=$('#field-tarifa_venta_unidad').parent().children()
           $(divValor).children('span:first').text("")
           $(divValor).append(valor+' €/Unidad')
           
           $('#field-id_grupo').parent().hide()
           $('div.id_grupo_form_group').append('<div class="col-sm-9 paddingTopTexto">Packs productos</div>')
          
           $('#field-id_familia').parent().hide()
           $('div.id_familia_form_group').append('<div class="col-sm-9 paddingTopTexto">Packs productos</div>')
           
           $('.descuento_1_compra_form_group').hide()
           $('.precio_transformacion_unidad_form_group').hide()
           $('#field-margen_real_producto').attr('disabled','disabled')
       }
       
        //botones parte superior
        var botones='<button class="btn btn-default btn-success b10" type="submit" id="form-button-save2">\n\
                                <i class="fa fa-check"></i>\n\
                                Actualizar cambios\n\
                            </button>\n\
                            <button class="btn btn-info b10" type="button" id="save-and-go-back-button2">\n\
                                Actualizar y volver a la lista\n\
                            </button>\n\
                            <button class="rueda"><img class="img-responsive" src="<?php echo base_url('images/ajax-loader-2.gif') ?>"></button>\n\
                            <button class="btn btn-default cancel-button b10" type="button" id="cancel-button2">\n\
                                <i class="fa fa-warning"></i>\n\
                                Cancelar\n\
                            </button>'
       
     
    
           var rueda='<button class="rueda"><img class="img-responsive" src="<?php echo base_url('images/ajax-loader-2.gif') ?>"></img></button>'
     
    $(botones).insertAfter('input#field-codigo_producto')
    $(rueda).insertAfter('button#save-and-go-back-button')
    
    $('#form-button-save2').css('margin-left','50px')
    $('div.codigo_producto_form_group label').css('margin-top','10px')  


    
                                   
    /*
      $('#form-button-save2').click(function(){
          //$('.rueda').css('display','initial')
          $('#form-button-save').click()
      })
      $('#form-button-save').click(function(e){
          $('.rueda').css('display','initial')
      })
     */ 
      $('#form-button-save2, #form-button-save').css('display','none')
      $('#save-and-go-back-button2').css('margin-left','20px')
      
      
      $('#save-and-go-back-button2').click(function(){
          $('.rueda').css('display','initial')
          $('#save-and-go-back-button').click()
      })
      $('#cancel-button2').click(function(){
          $('#cancel-button').click()
      })
        
        // var selIdioma = '<div class="form-group idioma_form_group"><label class="col-sm-3 control-label">Seleccionar idioma catálogo</label><div class="col-sm-1">'
        // selIdioma += '<select id="idioma"><option value="0">Ninguno</option><option value="es">Castellano</option><option value="en">Inglés</option><option value="fr">Francés</option></select>'
        // selIdioma += '</div>'
        // selIdioma += '<div class="col-sm-3" ><img id="img_bandera" alt="No existe imagen" height="20"></div>'   
        // selIdioma += '</div>'
        
        // $(selIdioma).insertAfter(".notas_form_group");
        $('#img_bandera').addClass('hide')
        
        $('div[class*="cat_"]').addClass('hide')

        $('#idioma').change(function () {
            var idioma = $(this).val()
            var host='<?php echo base_url() ?>'
            switch (idioma) {
                case '0':
                    $('div[class*="cat_"]').addClass('hide')
                    $('#img_bandera').addClass('hide')
                    break;
                case 'es':
                    $('div[class*="cat_"]').removeClass('hide')
                    $('div[class*="_en_form_group"]').addClass('hide')
                    $('div[class*="_fr_form_group"]').addClass('hide')
                    $('#img_bandera').attr('src',host+'images/es_flag.jpg')
                    $('#img_bandera').removeClass('hide')
                    $('.img_bandera').attr('src',host+'images/es_flag.jpg')
                    break
                case 'en':
                    $('div[class*="cat_"]').addClass('hide')
                    $('div[class*="_en_form_group"]').removeClass('hide')
                    $('div[class*="_fr_form_group"]').addClass('hide')
                    $('#img_bandera').removeClass('hide')
                    $('#img_bandera').attr('src',host+'images/en_flag.png')
                    $('#img_bandera').removeClass('hide')
                    $('.img_bandera').attr('src',host+'images/en_flag.png')
                    break
                case 'fr':
                    $('div[class*="cat_"]').addClass('hide')
                    $('div[class*="_en_form_group"]').addClass('hide')
                    $('div[class*="_fr_form_group"]').removeClass('hide')
                    $('#img_bandera').removeClass('hide')
                    $('#img_bandera').attr('src',host+'images/fr_flag.png')
                    $('#img_bandera').removeClass('hide')
                    $('.img_bandera').attr('src',host+'images/fr_flag.png')
                    break
            }
        })

        $(document).keypress(function (e) {
            var tag = e.target.tagName.toLowerCase();
            if (e.keyCode === 13 && tag != 'input' && tag != 'textarea') {
                $('#save-and-go-back-button').trigger("click");
                $('button[class$="cancel-button"]').trigger("click");
                return false;
            }

        })


        
        


        //resaltar algunos valores de editar y ver
        $('#read_precio_compra').attr('style', 'padding:5px;color:blue;border:1px solid blue')
        $('#read_precio_compra').parent().parent().children('label').attr('style', 'color:blue;')

        $('#read_tarifa_peso, #read_tarifa_unidad').attr('style', 'padding:5px;color:red;border:1px solid red')
        $('#read_tarifa_peso, #read_tarifa_unidad').parent().parent().children('label').attr('style', 'color:red;')

        $('#read_margen_real_producto').attr('style', 'padding:5px;color:green;border:1px solid green')
        $('#read_margen_real_producto').parent().parent().children('label').attr('style', 'color:gre;')

        $('.precio_compra_form_group').attr('style', 'color:blue')
        // $('.precio_ultimo_peso_form_group').attr('style','color:blue')
        // $('.descuento_1_compra_form_group').attr('style','color:blue')

        $('.tarifa_venta_unidad_form_group').attr('style', 'color:red')
        $('.tarifa_venta_peso_form_group').attr('style', 'color:red')

        $('.margen_real_producto_form_group').attr('style', 'color:green')


        //comprueba si es añadir productos y pone beneficio_recomendado=35 Dto prof=10 y desc máximo 18
        var anadir = $('form').attr('action').lastIndexOf("/insert");
        if (anadir !== -1) {
            $('#field-beneficio_recomendado').val(35)
            $('#field-descuento_profesionales').val(10)
            $('#field-descuento_profesionales_vip').val(15)
            $('#field-unidades_precio').val(1)
        }

        $('#field-unidades_precio').keyup(function () {
            calculoPrecioCompra()
            var margen = calculoMargen()
            // console.log(margen)
            $('#field-margen_real_producto').val(margen)
        })
        
        //si se cambia el precio compra o descuento compra,
        //se ponen a cero la tarifa venta y margen real
        $('#field-precio_ultimo_unidad').keyup(function () {
            if ($('#field-precio_ultimo_unidad').val() == "") {
                $('#field-precio_ultimo_peso').val("");
                $('#field-precio_transformacion_peso').val("");
                $('#field-tarifa_venta_peso').val("");
                $('#field-precio_ultimo_peso').removeAttr('disabled');
                $('#field-precio_venta_peso').removeAttr('disabled');
                $('#field-precio_transformacion_peso').removeAttr('disabled');
                $('#field-tarifa_venta_peso').removeAttr('disabled');
                $('#field-tipo_unidad_mostrar').val('');
            } else {
                $('#field-precio_ultimo_peso').attr('disabled', 'disabled');
                $('#field-precio_transformacion_peso').attr('disabled', 'disabled');
                $('#field-tarifa_venta_peso').attr('disabled', 'disabled');
                $('#field-tipo_unidad_mostrar').val('Und');
            }
            // console.log('campo precio ultimo unidad cambiado'+$('#field-precio_ultimo_unidad').val())
            calculoPrecioCompra()
            //var tarifa=$('#field-tarifa_venta_unidad')
            var margen = calculoMargen()
            $('#field-margen_real_producto').val(margen);
            // console.log('cambio margen ' + margen)
            
            
            /*
             $('#field-tarifa_venta_unidad').val("")
             if($('#field-precio_compra').val()!=0)
             $('#field-tarifa_venta_unidad').val(tarifa)
                 
             var tarifa_profesionales=calculoTarifaProfesionales(tarifa)
             $('#tarifa_profesionales').html(tarifa_profesionales)
             */
            $('.anterior').removeClass('hidden')
        })

        $('#field-precio_ultimo_peso').keyup(function () {
            if ($('#field-precio_ultimo_peso').val() == "") {
                $('#field-precio_ultimo_unidad').val("");
                $('#field-precio_transformacion_unidad').val("");
                $('#field-tarifa_venta_unidad').val("");
                $('#field-precio_ultimo_unidad').removeAttr('disabled');
                $('#field-precio_transformacion_unidad').removeAttr('disabled');
                $('#field-tarifa_venta_unidad').removeAttr('disabled');
                $('#field-tipo_unidad_mostrar').val('');
            } else {
                $('#field-precio_ultimo_unidad').attr('disabled', 'disabled');
                $('#field-precio_transformacion_unidad').attr('disabled', 'disabled');
                $('#field-tarifa_venta_unidad').attr('disabled', 'disabled');
                $('#field-tipo_unidad_mostrar').val('Kg');
            }
            calculoPrecioCompra()
            //var tarifa=$('#field-tarifa_venta_unidad')
            var margen = calculoMargen()
            $('#field-margen_real_producto').val(margen);
            // console.log('cambio margen ' + margen)
            //var tarifa=calculoTarifa()

            /*
             $('#field-tarifa_venta_peso').val("")
             if($('#field-precio_compra').val()!=0)
             $('#field-tarifa_venta_peso').val(tarifa)
                 
             var tarifa_profesionales=calculoTarifaProfesionales(tarifa)
             $('#tarifa_profesionales').html(tarifa_profesionales)
             */
            $('.anterior').removeClass('hidden')
        })

        $('#field-descuento_1_compra').keyup(function () {
            calculoPrecioCompra()
            var margen = calculoMargen()
            $('#field-margen_real_producto').val(margen);
            // console.log('cambio margen ' + margen)
            //var tarifa=calculoTarifa()

        })
        $('#field-precio_transformacion_unidad').keyup(function () {
            calculoPrecioCompra()
            var margen = calculoMargen()
            // console.log(margen)
            $('#field-margen_real_producto').val(margen)
           
        })

        $('#field-precio_transformacion_peso').keyup(function () {
            calculoPrecioCompra()
            var margen = calculoMargen()
            // console.log(margen)
            $('#field-margen_real_producto').val(margen)
        })


        //si se cambia el margen se calcula la tarifa, 
        $('#field-margen_real_producto').keyup(function () {
            var tarifa = calculoTarifa()
            // console.log('cambio precio PVP ' + tarifa)
            if ($('#field-precio_ultimo_unidad').val() != 0)
                $('#field-tarifa_venta_unidad').val(tarifa)
            if ($('#field-precio_ultimo_peso').val() != 0)
                $('#field-tarifa_venta_peso').val(tarifa)

            var tarifa_profesionales = calculoTarifaProfesionales(tarifa)
            var tarifa_profesionales_vip = calculoTarifaProfesionalesVip(tarifa)
            $('#field-tarifa_profesionales').val(tarifa_profesionales)
            $('#field-tarifa_profesionales_vip').val(tarifa_profesionales_vip)

            $('.anterior').removeClass('hidden')
        })

        //si se cambia la tarifa venta, se pone calcula el margen, 
        $('#field-tarifa_venta_unidad').keyup(function () {
            if ($('#field-precio_ultimo_unidad').val() == 0) {
                $(this).val("")
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Entrada Producto')
                $('.modal-body>p').html('No ha indicado el Precio Neto Compra (€/unidad) <br>Debe introducirlo antes que la tarifa venta')
                $('#myModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                return
            }
            var margen = calculoMargen()
            // console.log('cambio margen ' + margen)
            $('#field-margen_real_producto').val(margen)
            var tarifa = $(this).val()
            var tarifa_profesionales = calculoTarifaProfesionales(tarifa)
            var tarifa_profesionales_vip = calculoTarifaProfesionalesVip(tarifa)
            $('#field-tarifa_profesionales').val(tarifa_profesionales)
            $('#field-tarifa_profesionales_vip').val(tarifa_profesionales_vip)

            $('.anterior').removeClass('hidden')
        })

        //si se cambia la tarifa venta, se pone calcula el margen, 
        $('#field-tarifa_venta_peso').keyup(function () {
            if ($('#field-precio_ultimo_peso').val() == 0) {
                $(this).val("")
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Entrada Producto')
                $('.modal-body>p').html('No ha indicado el <strong>Precio Neto Compra (€/Kg)</strong> <br>Debe introducirlo antes que la tarifa venta')
                $('#myModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                return
            }
            var margen = calculoMargen()
            // console.log('cambio margen ' + margen)
            $('#field-margen_real_producto').val(margen)
            var tarifa = $(this).val()
            var tarifa_profesionales = calculoTarifaProfesionales(tarifa)
            var tarifa_profesionales_vip = calculoTarifaProfesionalesVip(tarifa)
            $('#field-tarifa_profesionales').val(tarifa_profesionales)
            $('#field-tarifa_profesionales_vip').val(tarifa_profesionales_vip)
            $('.anterior').removeClass('hidden')
        })

        $('#field-descuento_profesionales,#field-descuento_profesionales_vip').keyup(function () {
            var tarifa = calculoTarifa()
            var tarifa_profesionales = calculoTarifaProfesionales(tarifa)
            var tarifa_profesionales_vip = calculoTarifaProfesionalesVip(tarifa)
            $('#field-tarifa_profesionales').val(tarifa_profesionales)
            $('#field-tarifa_profesionales_vip').val(tarifa_profesionales_vip)
        })
        
        

        function numero(valor) {
//        valor=valor.replace(',','.')
            valor = parseFloat(valor)
            if (isNaN(valor))
                valor = 0;
            return valor
        }

        function calculoPrecioCompra() {
            //si no existe ningún precio el precio=0 
            if ($('#field-precio_ultimo_unidad').val() == 0
                    && $('#field-precio_ultimo_peso').val() == 0
                    && $('#field-precio_transformacion_unidad').val() == 0
                    && $('#field-precio_transformacion_peso').val() == 0) {
                $('#field-precio_compra').val(0)
                // console.log('calculoPrecioCompra todos 0' + $('#field-precio_compra').val())
                $('#field-precio_compra').removeClass('hide')
                return 0
            }
            var precio = 0
            //selecciona un precio_trnaformacion si existe
            precio = $('#field-precio_transformacion_unidad').val() == 0 ? $('#field-precio_transformacion_peso').val() : $('#field-precio_transformacion_unidad').val()
            //si existe ese es el precio_compra
            if (precio) {
                // console.log('precio '+precio)
                // console.log("numeral(precio).format('0.000')"+numeral(precio).format('0.000'))
                $('#field-precio_compra').val(numeral(precio).format('0.000'))
                // console.log('calculoPrecioCompra transformacion ' + precio)
                $('#field-precio_compra').removeClass('hide')
                return precio
            }
            //si no existiera pecio_tranaformación examinamos precio ultimo compra
            precio = $('#field-precio_ultimo_unidad').val() == 0 ? $('#field-precio_ultimo_peso').val() : $('#field-precio_ultimo_unidad').val()
            var dto = $('#field-descuento_1_compra').val()
            var unidadesPrecio=$('#field-unidades_precio').val()
            precio/=unidadesPrecio
            $('#field-precio_compra').val(numeral(precio - precio * dto / 100).format('0.000'))
            // console.log('calculoPrecioCompra transformacion ' + $('#field-precio_compra').val())
            $('#field-precio_compra').removeClass('hide')
            return precio
        }
        function calculoTarifaProfesionales(tarifa) {
            var descuento_profesionales = $('#field-descuento_profesionales').val()
            // console.log('descuento_profesionales ' + descuento_profesionales)
            descuento_profesionales = numero(descuento_profesionales)
            if (!descuento_profesionales)
                descuento_profesionales = 10
            // console.log('descuento_profesionales ' + descuento_profesionales)
            var iva = $('#field-iva').val() / 1000
            // console.log('iva ' + iva)
            var tarifa_profesionales = (100 - descuento_profesionales) * (tarifa / (100 + iva));
            // console.log('tarifa_profesionales ' + tarifa_profesionales)
            return numeral(tarifa_profesionales).format('0.00');
        }

        function calculoTarifaProfesionalesVip(tarifa) {
            var descuento_profesionales = $('#field-descuento_profesionales_vip').val()
            // console.log('descuento_profesionales_vip ' + descuento_profesionales)
            descuento_profesionales = numero(descuento_profesionales)
            if (!descuento_profesionales)
                descuento_profesionales = 10
            // console.log('descuento_profesionales_vip ' + descuento_profesionales)
            var iva = $('#field-iva').val() / 1000
            // console.log('iva ' + iva)
            var tarifa_profesionales = (100 - descuento_profesionales) * (tarifa / (100 + iva));
            // console.log('tarifa_profesionales_vip ' + tarifa_profesionales)
            return numeral(tarifa_profesionales).format('0.00');
        }

        function calculoTarifa() {
            var iva = $('#field-iva').val() / 1000
            if (iva == 0)
                return 0;
            // console.log('iva ' + iva)
            var precio = calculoPrecioCompra()
            // console.log('calculoTarifa precio compra' + precio)
            /*
             var precio=$('#field-precio_ultimo_unidad').val()==0?$('#field-precio_ultimo_peso').val():$('#field-precio_ultimo_unidad').val()
             precio=numero(precio)
             console.log('precio '+precio)
             var descuento=$('#field-descuento_1_compra').val()
             descuento=numero(descuento)
             console.log('descuento '+descuento)
             precio=precio-precio*descuento/100;
             $('#field-precio_compra').val(numeral(precio).format('0.00'))
             console.log('precio '+precio)
             */
            var margen = $('#field-margen_real_producto').val()
            margen = numero(margen)
            if (!margen) {
                var beneficio_recomendado = $('#field-beneficio_recomendado').val()
                beneficio_recomendado = numero(beneficio_recomendado)
                if (!beneficio_recomendado)
                    beneficio_recomendado = 35000
                margen = beneficio_recomendado
            }
            // console.log('margen ' + margen)

            var tarifa = 0
            tarifa = (precio) * (100 + iva) / (100 - margen)
            tarifa = numeral(tarifa).format('0.00')
            // console.log('tarifa ' + tarifa)
            return tarifa
        }

        function calculoMargen() {
            var iva=$('#field-iva').val()
            var precio_compra = $('#field-precio_compra').val() *1000
            var tarifa_venta = $('#field-tarifa_venta_unidad').val()*1000
            // console.log('iva   '+iva)
            //  console.log('precio_compra   '+precio_compra)
            //  console.log('tarifa_venta   '+tarifa_venta)
             
            
            var baseTarifa=Math.round(tarifa_venta/(1+iva/100000))
            // console.log('baseTarifa   '+baseTarifa)
            
            var margen=0
            margen=Math.round((baseTarifa-precio_compra)/baseTarifa*100000)/1000
            // console.log('margen   '+margen)
            margen = numeral(margen).format('0.00')
            return margen
            
            var iva = $('#field-iva').val() / 1000
            // console.log('iva ' + iva)
           // var precio = $('#field-precio_ultimo_unidad').val() == 0 ? $('#field-precio_ultimo_peso').val() : $('#field-precio_ultimo_unidad').val()
            var precio = $('#field-precio_compra').val() 
            precio = numero(precio)
            // console.log('precio ' + precio)
            var tarifa = $('#field-tarifa_venta_unidad').val() == 0 ? $('#field-tarifa_venta_peso').val() : $('#field-tarifa_venta_unidad').val()
            tarifa = numero(tarifa)
            // console.log('tarifa ' + tarifa)
            //var descuento = $('#field-descuento_1_compra').val()
            //descuento = numero(descuento)
            //precio = precio - precio * descuento / 100;
            //console.log('descuento ' + descuento)


            var margen = 0
            margen = (100 * tarifa - precio * (100 + iva)) / tarifa;
            margen = numeral(margen).format('0.00')
            $('#field-margen_real_producto').removeClass('hide')
            return margen
        }

        var datosP = [];
        var nombre;
        var totalUnidades;
        var codigoBascula

        function drawChart() {
            var data = google.visualization.arrayToDataTable(datosP);
            var options = {
                legend: {position: 'none'},
                chart: {
                    title: 'Unidades vendidas últimos 12 meses: ' + totalUnidades + ' Unidades - Código Bascula: ' + codigoBascula,
                    subtitle: 'Ventas mensuales',
                }
            };
            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
            //chart.draw(data, options);
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        $('[data-dismiss]').click(function () {
            location.reload();
        })

        var grande = false;
        var last = {}
        $('body').delegate('img.imagenProducto', 'click', function (e) {
           
            var izda=$(this).position().left
          
            if ($(this).attr('src') == "")
                return false;

            if (!grande) {
                
                $(this).css({'height': 300 + 'px', 'width': 300 + 'px'});
                $(this).css({
                    position: 'absolute',

                });
                $(this).css('z-index', '1')
                $(this).css('left',izda-300 + 'px')
                last = $(this)
                grande = true;
            } else {
                if (last) {
                    last.css({'height': 30 + 'px', 'width': 30 + 'px'});
                    last.css('position', 'relative')
                    last.css('left', 0 + 'px')
                    $(this).css('z-index', '1')
                }
                $(this).css({'height': 30 + 'px', 'width': 30 + 'px'});
                $(this).css('position', 'relative')
                $(this).css('left', 0 + 'px')
                grande = false;
            }

        })
       // $('input[name="url_imagen_portada"]').addClass('hide')
        //var nuevoProducto=$('.crud-form div div div div div').html().trim()=="Añadir Productos";     
        var verProductos = $('.table-label').children().html().trim() == "Ver Productos";

        //pone fecha en caso de no existir (add new)      
        if ($('#field-fecha_alta').val() == "")
            $('#field-fecha_alta').val('<?php echo date('d/m/Y'); ?>')
        if ($('#field-unidades_precio').val() == "0")
            $('#field-unidades_precio').val('1')

        $('#ver_url_imagen_portada').attr('href', $('#field-url_imagen_portada').val())
        $('#ver_url_producto').attr('href', $('#field-url_producto').val())

        $('#img_producto').attr('src', $('#field-url_imagen_portada').val())

        $('#field-url_imagen_portada').blur(function () {
            $('#ver_url_imagen_portada').attr('href', $(this).val())
            $('#img_producto').attr('src', $('#field-url_imagen_portada').val())
        })
        $('#field-url_producto').blur(function () {
            $('#ver_url_producto').attr('href', $(this).val())

        })

        $('#field-id_producto').removeClass('form-control')
        $('#field-nombre').removeClass('form-control')
        $('#field-nombre_generico').removeClass('form-control')
        $('#field-codigo_ean').removeClass('form-control')
        $('#field-unidades_precio').removeClass('form-control')

        $('#field-fecha_alta').removeClass('form-control').parent().children('a').children().remove()
        $('#field-fecha_proveedor_2').removeClass('form-control').parent().children('a').children().remove()
        $('#field-fecha_proveedor_3').removeClass('form-control').parent().children('a').children().remove()



        $('#field-codigo_producto').parent().children('span').css('color', 'red')
        $('#field-codigo_producto').parent().children('span').css('font-weight', 'bold')
        $('#field-codigo_producto').parent().css('margin-top', '6px')
        $('#field-codigo_producto').css('color', 'red')
        $('#field-codigo_producto').css('font-weight', 'bold')

      


        selectFamilias()

        function selectFamilias() {
            var familia = $('select#field-id_familia').val()
            var grupo = $('#field-id_grupo').val()
            //alert('hola '+grupo)
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/getFamilias",
                data: {grupo: grupo},
                success: function (datos) {
                    //alert(datos);
                    var datos = $.parseJSON(datos)
                    $('select#field-id_familia').parent().attr('id', 'maba')
                    $('#maba').empty();
                    var seleccion = '<select id="field-id_familia" name="id_familia" class="chosen-select"  style="width: 300px;    ">'
                    seleccion += '<option value="' + 0 + '">' + 'Seleccionar Familia' + '</option>';
                    $(datos).each(function (index, value) {
                        var sel = ''
                        if (value[0] == familia)
                            sel = 'selected="selected"';
                        seleccion += '<option value="' + value[0] + '" ' + sel + '>' + value[1] + '</option>';
                    })
                    seleccion += '</select>';
                    $('#maba').append(seleccion)
                },
                error: function () {
                    alert('Error búsqueda familias. Informar')
                }
            })
        }

        $('#field-id_grupo').change(function () {
            $('select#field-id_familia').val(0)
            selectFamilias()

        })

      

       
        if( $('#categoria').val() == 2)  {
            
            //$('.gc-export').removeAttr('data-url')
            $('.gc-export').attr('class', 'btn btn-default t5 mi-excel hide')
        } else {
            //alert('hola')
            //$('.gc-export').removeAttr('data-url')
            //$('.gc-export').attr('class', 'btn btn-default t5 mi-excel ')
        }


        $('body').delegate('a.codigo_bascula', 'click', function (e) {
            e.preventDefault()
            var id_producto = $(this).html()
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/getInfoCodigoBascula/" + id_producto,
                data: {id_producto: id_producto},
                success: function (datos) {
                    // alert ('datos '+datos)
                    var datos = $.parseJSON(datos)
                    var info = "<table class='table-hover'><thead><tr><th width='120' style='text-align:left'>Códigos 13 </th>\n\
                    <th width='300' style='text-align:left'> Nombre </th>\n\
                    <th width='70' style='text-align:right'> Unidad </th>\n\
                    <th width='70' style='text-align:right'> Peso </th>\n\
                    <th width='200' style='text-align:left'> Proveedor </th>\n\
                    <th width='70' style='text-align:right'> Coste </th>\n\
                    <th width='70' style='text-align:right'> PVP </th>\n\
                    <th width='70' style='text-align:right'> Status </th>\n\
                    <th width='70' style='text-align:right'> Stock </th>\n\
                    <th width='90' style='text-align:right'> Valor Stock </th>\n\
                    <th width='70' style='text-align:right'> R.Coste </th>\n\
                    <th width='70' style='text-align:right'> R.PVP </th>\n\
                    </thead><tbody>"
                    var coste_peso = [];
                    var pvp_peso = [];
                    var totalCantidad=0
                    var totalValor=0
                    $.each(datos['codigos_producto'], function (index, value) {
                        totalCantidad+=datos['cantidad'][index]
                        totalValor+=datos['cantidad'][index]*datos['precio'][index]
                        info += '<tr><td> ' + value + ' </td>';
                        info += '<td style="text-align:left"> ' + datos['nombres'][index] + ' </td>';
                        info += '<td style="text-align:right"> ' + datos['tipoUnidad'][index] + ' </td>';
                        info += '<td style="text-align:right"> ' + datos['pesos'][index].toFixed(3) + ' </td>';
                        info += '<td style="text-align:left"> ' + datos['proveedor'][index] + ' </td>';
                        info += '<td style="text-align:right"> ' + datos['precio'][index].toFixed(3) + ' </td>';
                        info += '<td style="text-align:right"> ' + datos['pvp'][index].toFixed(2) + ' </td>';
                        info += '<td style="text-align:right"> ' + datos['status'][index] + ' </td>';
                        info += '<td style="text-align:right"> ' + datos['cantidad'][index] + ' </td>';
                        info += '<td style="text-align:right"> ' + (datos['cantidad'][index]*datos['precio'][index]).toFixed(2) + ' </td>';
                        if (datos['status'][index] == 1)
                            coste_peso[index] = datos['precio'][index] / datos['pesos'][index]
                        info += '<td style="text-align:right"> ' + coste_peso[index].toFixed(3) + ' </td>';
                        if (datos['status'][index] == 1)
                            pvp_peso[index] = datos['pvp'][index] / datos['pesos'][index]
                        info += '<td style="text-align:right"> ' + pvp_peso[index].toFixed(3) + ' </td>';
                        info += '</tr>';
                    })
                    //info += '</tbody>'
                    info += "<tr><td colspan='8'>   </td><td style='border-top: 1px solid blue'> </td><td style='border-top: 1px solid blue'>  </td></tr></tbody><tfoot><tr><th width='120' style='text-align:left'>Totales </th>\n\
                    <th width='300' style='text-align:left'> </th>\n\
                    <th width='70' style='text-align:right'>  </th>\n\
                    <th width='70' style='text-align:right'>  </th>\n\
                    <th width='200' style='text-align:left'>  </th>\n\
                    <th width='70' style='text-align:right'>  </th>\n\
                    <th width='70' style='text-align:right'>  </th>\n\
                    <th width='70' style='text-align:right'>  </th>\n\
                    <th width='70' style='text-align:right'>"+totalCantidad+"</th>\n\
                    <th width='90' style='text-align:right'>"+totalValor.toFixed(2)+"</th>\n\
                    <th width='70' style='text-align:right'> </th>\n\
                    <th width='70' style='text-align:right'> </th>\n\
                    </tfoot>"
                    info += '</table><br>';

                    var coste_peso_promedio = coste_peso.reduce(function (valorAnterior, valorActual, indice, vector) {
                        return valorAnterior + valorActual;
                    })
                    coste_peso_promedio = coste_peso_promedio / coste_peso.length;
                    var pvp_peso_promedio = pvp_peso.reduce(function (valorAnterior, valorActual, indice, vector) {
                        return valorAnterior + valorActual
                    })
                    pvp_peso_promedio = pvp_peso_promedio / pvp_peso.length;
                    if ($.isNumeric(coste_peso_promedio))
                        coste_peso_promedio = coste_peso_promedio.toFixed(3)
                    if ($.isNumeric(pvp_peso_promedio))
                        pvp_peso_promedio = pvp_peso_promedio.toFixed(3)
                    var observaciones = "Observaciones: <strong>Coste promedio </strong>= " + coste_peso_promedio + " - <strong>PVP promedio </strong>= " + pvp_peso_promedio

                    /*
                     datosP=[]
                     var pares=[]
                     pares.push('Mes')
                     pares.push('Total')
                     datosP.push(pares)
                     $.each(datos['datos']['und'], function(key, value){
                     var pares=[]
                     pares.push(value['fecha'])
                     pares.push(value['und']+value['undP']+value['undVD'])
                         
                     console.log(value['und']+value['undP']+value['undVD'])
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
                     */


                    $('#myModalProductos').css('color', 'blue')
                    $('.modal-title').html('Información Productos Código Báscula')
                    $('.modal-body>p').html('Códigos asociados al código báscula ' + '<strong>' + id_producto + '</strong><br><br>' + info + observaciones)
                    $('#myModalProductos').modal()

                    


                    return false
                },
                error: function () {
                    alertaError("Información importante", "Error en el proceso grabado lineas venta Directa. Informar");
                }
            })


        })



        $('a.mi-excel_').click(function (e) {
            e.preventDefault()
            //alert('mi-excel')
            var buscadores = []
            $('input.searchable-input').each(function (index) {
                buscadores.push($(this).val())
                //alert($(this).val())
            })
            // alert('hola')   

            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>"+"index.php/productos/bajarExcelProductos", 
                data: {buscadores: buscadores, },
                success: function (datos) {
                    //alert(datos);
                    var datos = $.parseJSON(datos)
                    // alert(datos);


                    var direccion = "<?php echo base_url() ?>" + datos
                    mywindow = window.open(direccion)
                    // window.location.reload();
                    window.close();

                },
                error: function () {
                }
            })





        })




        //______________________________________
        $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nuevo Producto')
        $(' a[href*="/add"]').attr('id', 'nuevo')

        $('#nuevo').after('<a class="btn btn-default" id="nuevoProductoRangos"><i class="fa fa-plus"></i><span class="hidden-xs floatR l5"> &nbsp;Nuevo Producto Rangos / Nuevo Vino Añada</span></a>')

        $('#nuevoProductoRangos').click(function(){
            var url="<?php echo base_url() ?>index.php/gestionTablasProductos/nuevoProductoRangos"
            window.location.href = url;
        })


        function getUnidadCodigoProducto(codigo_producto) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/getUnidadCodigoProducto/" + codigo_producto,
                data: {},
                success: function (datos) {
                   // alert(datos);
                    var datos = $.parseJSON(datos)
                    if (datos == 'Und') {
                        $('#read_precio_ultimo_peso').parent().parent().css('display', 'none')
                        $('#read_tarifa_peso').parent().parent().css('display', 'none')
                        $('#read_precio_transformacion_peso').parent().parent().css('display', 'none')

                        if ($('#read_stock_minimo').html() == 1)
                            $('#read_stock_minimo').html($('#read_stock_minimo').html() + ' Unidad')
                        else
                            $('#read_stock_minimo').html($('#read_stock_minimo').html() + ' Unidades')

                        if ($('#read_unidades_precio').html() == 1)
                            $('#read_unidades_precio').html($('#read_unidades_precio').html() + ' Unidad')
                        else
                            $('#read_unidades_precio').html($('#read_unidades_precio').html() + ' Unidades')
                        
                        
                        if ($('#read_unidades_caja').html() == 0)
                            $('#read_unidades_caja').html('No especificado')
                            else{
                        if ($('#read_unidades_caja').html() == 1)
                            $('#read_unidades_caja').html($('#read_unidades_caja').html() + ' Unidad')
                        else
                            $('#read_unidades_caja').html($('#read_unidades_caja').html() + ' Unidades')
                        }
                        
                        

                        $('.euro_tipo').html(' €/unidad')

                    } else {
                        $('#read_precio_ultimo_unidad').parent().parent().css('display', 'none')
                        $('#read_tarifa_unidad').parent().parent().css('display', 'none')
                        $('#read_precio_transformacion_unidad').parent().parent().css('display', 'none')

                        $('#read_stock_minimo').html($('#read_stock_minimo').html() + ' Kg')
                        $('#read_unidades_precio').html($('#read_unidades_precio').html() + ' Kg')
                        //$('#read_unidades_precio').html('Kg')
                        $('#read_unidades_caja').html($('#read_unidades_caja').html() + ' Kg')
                        //$('#read_unidades_caja').html('Kg')
                        $('.euro_tipo').html(' €/Kg')

                    }


                },
                error: function () {
                }
            })
        }




        $(window).load(function () {
            
            //caso de ver información productos
            $('div.container').addClass('container-fluid')
            $('div.container-fluid').removeClass('container')

            if (verProductos) {
                //si pantalla ver productos, se muestra la imagen
                $('#img_producto_read').attr('src', $('#read_url_imagen_portada').html())
                var codigo_producto = ($('#field-codigo_producto').html())
                //gestiona lo que se ve segun sea unidad o peso
                getUnidadCodigoProducto(codigo_producto)
            }


            //cuando se edita, evita cambiar el tipo de precio unidad/peso
            if ($('.table-label').children().html().trim() == 'Editar Productos') {
                if ($('input[name="precio_ultimo_unidad"]').val() != "") {
                    $('div.precio_ultimo_peso_form_group').addClass('hide')
                    $('div.precio_peso_2_form_group').addClass('hide')
                    $('div.precio_peso_3_form_group').addClass('hide')
                    $('div.tarifa_venta_peso_form_group').addClass('hide')
                    $('div.precio_transformacion_peso_form_group').addClass('hide')
                }
                if ($('input[name="precio_ultimo_peso"]').val() != "") {
                    $('div.precio_ultimo_unidad_form_group').addClass('hide')
                    $('div.precio_unidad_2_form_group').addClass('hide')
                    $('div.precio_unidad_3_form_group').addClass('hide')
                    $('div.tarifa_venta_unidad_form_group').addClass('hide')
                    $('div.precio_transformacion_unidad_form_group').addClass('hide')
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




