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
    .table tbody tr td:nth-child(3){
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

</style>


<?php
//para incluir título en cabecera tabla
$titulo = isset($titulo) ? $titulo : 'Sin Título';
$col_bootstrap = isset($col_bootstrap) ? $col_bootstrap : 10;
?>
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

    $(document).ready(function () {

        // $('.table tbody tr td:nth-child(3)').css('color','red')
        //$('#field-fechaAlta').val(hoy());

        // $('.compra').parent().css('background-color','lightyellow') 
        var selIdioma = '<div class="form-group idioma_form_group"><label class="col-sm-3 control-label">Seleccionar idioma catálogo</label><div class="col-sm-1">'
        selIdioma += '<select id="idioma"><option value="0">Ninguno</option><option value="es">Castellano</option><option value="en">Inglés</option><option value="fr">Francés</option></select>'
        selIdioma += '</div>'
        selIdioma += '<div class="col-sm-3" ><img id="img_bandera" alt="No existe imagen" height="20"></div>'
        selIdioma += '</div>'


        $("#crudForm").prepend(selIdioma);
        $('#img_bandera').addClass('hide')

        $('div[class*="marca_form_group"]').addClass('hide')
        $('div[class*="sub_titulo_form_group"]').addClass('hide')
        $('div[class*="mapa_form_group"]').addClass('hide')
        $('div[class*="imagen_form_group"]').addClass('hide')
        $('div[class*="descripcion_marca_form_group"]').addClass('hide')
        $('div[class*="descuento_form_group"]').addClass('hide')
        $('div[class*="_en_form_group"]').addClass('hide')
        $('div[class*="_fr_form_group"]').addClass('hide')
        

        $('#idioma').change(function () {
            var idioma = $(this).val()

            var host = '<?php echo base_url() ?>'
            switch (idioma) {
                case '0':
                    $('div[class*="marca_form_group"]').addClass('hide')
                    $('div[class*="sub_titulo_form_group"]').addClass('hide')
                    $('div[class*="mapa_form_group"]').addClass('hide')
                    $('div[class*="imagen_form_group"]').addClass('hide')
                    $('div[class*="descripcion_marca_form_group"]').addClass('hide')
                    $('div[class*="descuento_form_group"]').addClass('hide')
                    $('div[class*="_en_form_group"]').addClass('hide')
                    $('div[class*="_fr_form_group"]').addClass('hide')

                    $('#img_bandera').addClass('hide')
                    break;
                case 'es':
                    $('div[class*="marca_form_group"]').removeClass('hide')
                    $('div[class*="sub_titulo_form_group"]').removeClass('hide')
                    $('div[class*="mapa_form_group"]').removeClass('hide')
                    $('div[class*="imagen_form_group"]').removeClass('hide')
                    $('div[class*="descripcion_marca_form_group"]').removeClass('hide')
                    $('div[class*="descuento_form_group"]').removeClass('hide')
                    $('div[class*="_en_form_group"]').addClass('hide')
                    $('div[class*="_fr_form_group"]').addClass('hide')

                    $('#img_bandera').attr('src', host + 'images/es_flag.jpg')
                    $('#img_bandera').removeClass('hide')
                    $('.img_bandera').attr('src',host+'images/es_flag.jpg')

                    break
                case 'en':
                    $('div[class*="marca_form_group"]').addClass('hide')
                    $('div[class*="sub_titulo_form_group"]').addClass('hide')
                    $('div[class*="mapa_form_group"]').addClass('hide')
                    $('div[class*="imagen_form_group"]').addClass('hide')
                   // $('div[class*="descripcion_marca_form_group"]').addClass('hide')
                    $('div[class*="_fr_form_group"]').addClass('hide')
                    $('div[class*="_en_form_group"]').removeClass('hide')
                    $('#img_bandera').removeClass('hide')
                    $('#img_bandera').attr('src', host + 'images/en_flag.png')
                    $('.img_bandera').attr('src',host+'images/en_flag.png')


                    break
                case 'fr':
                    $('div[class*="marca_form_group"]').addClass('hide')
                    $('div[class*="sub_titulo_form_group"]').addClass('hide')
                    $('div[class*="mapa_form_group"]').addClass('hide')
                    $('div[class*="imagen_form_group"]').addClass('hide')
                   // $('div[class*="descripcion_marca_form_group"]').addClass('hide')
                    $('div[class*="_en_form_group"]').addClass('hide')
                    $('div[class*="_fr_form_group"]').removeClass('hide')
                    $('#img_bandera').removeClass('hide')
                    $('#img_bandera').attr('src', host + 'images/fr_flag.png')
                    $('.img_bandera').attr('src',host+'images/fr_flag.png')


                    break
                default :
                    break
            }
        })





        if ($('#field-fechaAlta').val() == "") {
            $('#field-fechaAlta').val(hoy());
        }



        function hoy() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd
            }

            if (mm < 10) {
                mm = '0' + mm
            }
            return  dd + '/' + mm + '/' + yyyy;
        }



        /*
         var fechaAlta=$('#field-fechaAlta.readonly_label').html()
         fechaAlta=fechaAlta.substr(8,2)+"/"+fechaAlta.substr(5,2)+"/"+fechaAlta.substr(0,4)
         $('#field-fechaAlta.readonly_label').html(fechaAlta)
         
         var fechaModificacion=$('#field-fechaModificacion.readonly_label').html()
         fechaModificacion=fechaModificacion.substr(8,2)+"/"+fechaModificacion.substr(5,2)+"/"+fechaModificacion.substr(0,4)
         $('#field-fechaModificacion.readonly_label').html(fechaModificacion)
         */

        var valor = $('#field-tienda_web.readonly_label').html()
        if (valor == 1) {
            valor = "Tienda"
        } else {
            valor = "Web"
        }
        $('#field-tienda_web.readonly_label').html(valor)





        //$('#tienda_web_input_box').children().html('<label><div class="radio" id="uniform-field-tienda_web-true"><span><input id="field-tienda_web-true" class="radio-uniform" type="radio" name="tienda_web" value="1"></span></div> actigdggvo</label> <label><div class="radio" id="uniform-field-tienda_web-false"><span><input id="field-tienda_web-false" class="radio-uniform" type="radio" name="tienda_web" value="0"></span></div> inactivo</label>')
    })
</script>