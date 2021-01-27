<style>
    .container-fluid {
        margin: 5px;
        /* margin-right:5px; */
    }

    #titulo {
        background-color: #DDD;
        font-family: Arial;
        font-weight: bold;
        padding: 5px;
    }

    thead input {
        width: 100%;
    }

    #reset {
        margin-left: 10px;
    }

    .eliminar {
        color: red;
        cursor: grab;
    }

    .descatalogar {
        color: pink;
        cursor: grab;
    }

    .editar {
        color: black;
        cursor: grab;
    }

    .ver {
        color: blue;
        cursor: grab;
    }


    #productos>tbody>tr:nth-child(1)>td.sorting_1 {
        min-width: 100px;
    }

    .eliminar:hover,
    .descatalogar:hover,
    .editar:hover,
    .ver:hover {
        font-size: 30px;
    }

    /* #productos>thead>tr:nth-child(1)>th.sorting_asc, */
    #productos>thead>tr:nth-child(2)>th:nth-child(1)>input[type=text]

    /* #productos > thead > tr:nth-child(2) > th:nth-child(1){ */
        {
        /* visibility: hidden; */
        /* display:none; */
        border: 0px !important;
        color: red !important;
    }

    .modal-dialog {
        width: 900px;
        /* New width for default modal */
    }

    .form-control-plaintext {
        border: 0px;
    }

    .form-group.row {
        margin-bottom: 5px !important;
    }

    .form-group.row>label {
        text-align: right !important;
        font-weight: normal !important;

    }

    .form-group.row>div>input {
        text-align: left !important;
        font-weight: bold !important;
    }

    .col-form-label {
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0px;
    }

    .errorVerificacion>p {
        background-color: red;
        color: white;
        padding-left: 10px;
    }

    .conError {
        border-color: red;
    }

    .sinError {
        border-color: #CCCCCC;
    }

    #catalogados,
    #nuevoProductoRangos,
    #exportar,
    #reset {
        margin-left: 5px;
    }

    /* precio final */
    /* #myModalProducto>div>div>div.modal-body>form>div:nth-child(23) {
        color: blue;
        border: 2px solid;
        padding: 3px;
        background-color: lightblue;
    }

    #myModalProducto>div>div>div.modal-body>form>div:nth-child(23)>div>input {
        margin: 0px;
        color: blue;
    }

    #myModalProducto>div>div>div.modal-body>form>div:nth-child(23)>label {
        font-weight: bold !important;
    } */

    /* #productos>tbody>tr>td:nth-child(7),
    #productos>thead>tr:nth-child(1)>th:nth-child(7),
    #productos>tfoot>tr>th:nth-child(7),
    #productos>thead>tr:nth-child(2)>th:nth-child(7)>input[type=text] {
        color: blue;
        font-weight: bold !important;
    } */

    /* PVP */
    /* #myModalProducto>div>div>div.modal-body>form>div:nth-child(25) {
        color: crimson;
        border: 2px solid;
        padding: 3px;
        background-color: lightpink;
    } */

    #myModalProducto>div>div>div.modal-body>form>div:nth-child(25)>div>input {
        margin: 0px;
        color: crimson;
    }

    #myModalProducto>div>div>div.modal-body>form>div:nth-child(25)>label {
        font-weight: bold !important;
    }

    /* #productos>tbody>tr>td:nth-child(9),
    #productos>thead>tr:nth-child(1)>th:nth-child(9),
    #productos>tfoot>tr>th:nth-child(9),
    #productos>thead>tr:nth-child(2)>th:nth-child(9)>input[type=text] {
        color: crimson;
        font-weight: bold !important;
    } */

    /* margen  */
    /* #myModalProducto>div>div>div.modal-body>form>div:nth-child(27) {
        color: seagreen;
        border: 2px solid;
        padding: 3px;
        background-color: lightgreen;
    } */

    #myModalProducto>div>div>div.modal-body>form>div:nth-child(27)>div>input {
        margin: 0px;
        color: seagreen;
    }

    #myModalProducto>div>div>div.modal-body>form>div:nth-child(27)>label {
        font-weight: bold !important;
    }

    /* #productos>tbody>tr>td:nth-child(10),
    #productos>thead>tr:nth-child(1)>th:nth-child(10),
    #productos>tfoot>tr>th:nth-child(10),
    #productos>thead>tr:nth-child(2)>th:nth-child(10)>input[type=text] {
        color: seagreen;
        font-weight: bold !important;
    } */

    #titulo {
        margin-top: 3px;
        margin-bottom: 3px;
    }

    #productos>thead>tr:nth-child(1)>th {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
        padding-left: 0px !important;

    }

    #productos>thead>tr:nth-child(2)>th {
        padding: 2px !important;
        margin-top: 2px;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container-fluid">
    <?php $tipoProducto = $status_producto ? "Catalogados" : "Descatalogados" ?>
    <h2 id="titulo">Cargando... Productos <?php echo $tipoProducto ?></h2>

    <table id="productos" class="display" style="width:100%; display:none">
        <thead>
            <tr>
                <th></th>
                <th>Código Producto</th>
                <th>Cód. Bascula</th>
                <th class="izquierda">Nombre</th>
                <th>Peso Real (Kg)</th>
                <th>Tipo Unidad</th>
                <!-- <th>Precio Compra Final en Tienda</th> -->
                <th class="izquierda">Proveedor</th>
                <th>Tarifa PVP</th>
                <!-- <th>Margen (%)</th> -->
                <th>Undidades Stock</th>
                <!-- <th>Valor Stock precio compra actual</th> -->
                <th>Imagen Producto</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($estructura as $k => $v) {
                // echo $v['requerido'].'<br>';  
            } ?>
            <?php foreach ($productos as $k => $v) { ?>

                <tr>
                    <!-- botones navegacion -->
                    <td producto="<?php echo $v->id ?>" style="font-size:18px">
                        <span class="trash">
                            <a class="eliminar "> <i class="fa fa-trash"></i> </a>
                        </span>

                        <span class="">
                            <a class="descatalogar"><i class="fa fa-window-close-o"></i> </a>
                        </span>

                        <span class="">
                            <a class="editar2"><i class="fa fa-edit"></i> </a>
                        </span>

                        <span class="">
                            <a class="ver2"><i class="fa fa-eye"></i> </a>
                        </span>
                    </td>

                    <!-- datos producto -->
                    <td><?php echo $v->codigo_producto ?></td>
                    <td><?php echo $v->id_producto ?></td>
                    <td class="izquierda"><?php echo $v->nombre ?></td>
                    <td><?php echo number_format($v->peso_real / 1000, 3, ",", ".") ?></td>
                    <td><?php echo $v->tipo_unidad ?></td>
                    <!-- <td><?php echo number_format($v->precio_compra / 1000, 3, ",", ".") ?></td> -->
                    <td class="izquierda"><?php echo $v->proveedor ?></td>
                    <td><?php echo number_format($v->tarifa_venta / 1000, 3, ",", ".") ?></td>
                    <!-- <td><?php echo number_format($v->margen_real_producto / 1000, 3, ",", ".") ?></td> -->
                    <td><?php echo $v->stock_total  ?></td>
                    <!-- <td><?php echo number_format($v->valoracion, 2, ",", ".") ?></td> -->
                    <!-- <td><a  href="<?php echo $v->url_imagen_portada ?>" target="_blank">Img</a></td> -->
                    <td><button class="img" img="<?php echo $v->url_imagen_portada ?>">Img</button></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th>Código Producto</th>
                <th>Cód. Bascula</th>
                <th class="izquierda">Nombre</th>
                <th>Peso Real (Kg)</th>
                <th>Tipo Unidad</th>
                <!-- <th>Precio Compra Final en Tienda</th> -->
                <th class="izquierda">Proveedor</th>
                <th>Tarifa PVP</th>
                <!-- <th>Margen (%)</th> -->
                <th>Undidades Stock</th>
                <!-- <th>Valor Stock precio compra actual</th> -->
                <th>Imagen Producto</th>

            </tr>
        </tfoot>
    </table>




</div>


<script>
    jQuery.fn.dataTableExt.oApi.fnSortNeutral = function(oSettings) {
        /* Remove any current sorting */
        oSettings.aaSorting = [];

        /* Sort display arrays so we get them in numerical order */
        oSettings.aiDisplay.sort(function(x, y) {
            return x - y;
        });
        oSettings.aiDisplayMaster.sort(function(x, y) {
            return x - y;
        });

        /* Redraw */
        oSettings.oApi._fnReDraw(oSettings);
    };


    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container')

    $(document).ready(function() {


        // Setup - add a text input to each footer cell
        $('#productos thead tr').clone(true).appendTo('#productos thead');
        $('#productos thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            if (i == 0) {
                $(this).html('<input type="text" placeholder="" disabled style="border:0px;" />');
            } else {
                $(this).html('<input type="text" placeholder="Buscar ' + title + '" />');
            }
            // if (i == 6) {
            //     $(this).html('<input type="text"  placeholder="Buscar ' + title + '" style="color: blue;border-color:blue;" />');
            // }
            // if (i == 8) {
            //     $(this).html('<input type="text"  placeholder="Buscar ' + title + '" style="color: blue;border-color:crimson;" />');
            // }
            // if (i == 9) {
            //     $(this).html('<input type="text"  placeholder="Buscar ' + title + '" style="color: seagreen;;border-color:seagreen;;" />');
            // }
            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });


        var table = $('#productos').DataTable({
            "dom": 'ft<lp>i',
            orderCellsTop: true,
            fixedHeader: true,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            }

        });


        // $( '<button type="button" id="reset" class="">Reset filtros</button>' ).appendTo( "#productos_length" );
        // $( '<button type="button" id="reset" class="">Reset filtros</button>' ).insertBefore( "#productos" );
        $('<a class="btn btn-default" href="#" id="nuevo"><i class="fa fa-plus"></i> &nbsp; Nuevo Producto</a>').insertBefore("#productos");
        <?php if ($status_producto == 1) { ?>
            $('<a class="btn btn-default" href="#" id="catalogados"><i class="fa fa-inbox"></i> &nbsp; Ir a descatalogados</a>').insertBefore("#productos");
            // $('<a class="btn btn-default" href="#" id="catalogados"><i class="fa fa-inbox"></i> &nbsp; Ir a descatalogados</a>').insertBefore("#productos");
            $('<a class="btn btn-default" id="nuevoProductoRangos"><i class="fa fa-plus"></i><span class=""> &nbsp;Nuevo Producto Rangos / Nuevo Vino Añada</span></a>').insertBefore("#productos");
            // $('<a  href="<?= base_url() ?>index.php/productos/exportExcel" class="btn btn-default t5 botonSuperior mi-nuevo-excel" id="exportar"><i class="fa fa-cloud-download"></i> &nbsp;Exportar selección</a>').insertBefore("#productos");
            // $('#gcrud-search-form > div.header-tools > div.floatR > a.btn.btn-default.t5.mi-clear-filtering').before('<a  href="<?= base_url() ?>index.php/productos/exportExcel" class="btn btn-default t5 botonSuperior mi-nuevo-excel"><i class="fa fa-cloud-download"></i><span class="hidden-xs floatR l5">Exportar selección</span><div class="clear"></div></a>')

        <?php } else { ?>
            $('<a class="btn btn-default" href="#" id="catalogados"><i class="fa fa-inbox"></i> &nbsp; Ir a catalogados</a>').insertBefore("#productos");
        <?php } ?>



        $('#nuevoProductoRangos').click(function() {
            var url = "<?php echo base_url() ?>index.php/gestionTablasProductos/nuevoProductoRangos"
            window.location.href = url;
        })



        $('<a class="btn btn-default" id="reset"><i class="fa fa-eraser"> </i> <span class="">Eliminar filtros</span></a>').insertBefore("#productos");
        $('#reset').click(function() {
            $('#productos thead input').val('').change();
            $("#productos").DataTable().search("").draw()
            var table = $('#productos').dataTable();
            // Sort in the order that was originally in the HTML
            table.fnSortNeutral();
        })

        $('#nuevo').click(function() {
            id = 0
            $('#cerrar').addClass('hide')
            $('#grabar').removeClass('hide')
            $('#cancelar').removeClass('hide')

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getEditarProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    // alert(datos['datos']['codigo_producto'])
                    $('#myModalProducto').css('color', 'black')
                    $('.modal-title').html('Modificar datos producto')
                    $('.modal-body').html(datos)
                    $("#myModalProducto").modal()

                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').prop('disabled', false)
                    if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == '---') {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').attr('disabled', 'disabled')
                    } else if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == 'Und') {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').attr('disabled', 'disabled')
                    } else if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == 'Kg') {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').attr('disabled', 'disabled')
                    }

                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })
        })

        $('.modal-body').delegate('.form-control', 'click', function() {
            console.log('paso')
            var errores = ""
            $(".errorVerificacion").html(errores);
        })

        // $('.eliminar').click(function() {
        $('table#productos').delegate('.eliminar', 'click', function() {
            id = $(this).parent().parent().attr('producto')
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/checkPosibilityToEliminate/" + id,
                data: {
                    id: id
                },
                success: function(datos) {
                    console.log(datos)
                    var datos = $.parseJSON(datos)
                    id_pe_producto=id
                    // alert(datos['texto'])
                    if (datos['eliminar']) {
                        $('#pregunta').css('color', 'blue')
                        $('.modal-title').html('Información')
                        $('.modal-body').html(datos['texto'])
                        $("#pregunta").modal()
                    }else {
                        console.log('eliminar ' + id)
                        // console.log('eliminar ' + $(this).parent().parent().attr('producto'))
                        $('#myModal').css('color', 'blue')
                        $('.modal-title').html('Información')
                        $('.modal-body').html(datos['texto'])
                        $("#myModal").modal()
                    }
                },
                error: function() {
                    alert("Error en el proceso checkPosibilityToEliminate. Informar");
                }
            })

        })

        // $('.descatalogar').click(function() {
        $('table#productos').delegate('.descatalogar', 'click', function() {
            id = $(this).parent().parent().attr('producto')
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/cambiar_status_producto",
                data: {
                    id: id
                },
                success: function(datos) {
                    // alert (datos)
                    var datos = $.parseJSON(datos)
                    var url = "<?php echo base_url() ?>index.php/productos/productosSpeedy/<?php echo $status_producto ?>"
                    window.location.href = url;
                },
                error: function() {
                    alert("Error en el proceso cambio status producto. Informar");
                }
            })
            console.log('descatalogar ' + id)
        })

        var id = "";

        $('#catalogados').click(function() {
            <?php if ($status_producto == 1) { ?>
                var url = "<?php echo base_url() ?>index.php/productos/productosSpeedy/0"
            <?php } else { ?>
                var url = "<?php echo base_url() ?>index.php/productos/productosSpeedy/1"
            <?php } ?>
            window.location.href = url;

        })

        // $('.editar').click(function() {
        $('table#productos').delegate('.editar', 'click', function() {
            id = $(this).parent().parent().attr('producto')
            $('#cerrar').addClass('hide')
            $('#grabar').removeClass('hide')
            $('#cancelar').removeClass('hide')
            // console.log('editar ' + id)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getEditarProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    // alert(datos['datos']['codigo_producto'])
                    $('#myModalProducto').css('color', 'black')
                    $('.modal-title').html('Modificar datos producto')
                    $('.modal-body').html(datos)
                    $("#myModalProducto").modal()

                    if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == 'Und') {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').attr('disabled', 'disabled')
                    } else {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').attr('disabled', 'disabled')
                    }
                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })
        })
        // $('.editar2').click(function() {
        $('table#productos').delegate('.editar2', 'click', function() {
            id = $(this).parent().parent().attr('producto')
            $('#cerrar').addClass('hide')
            $('#grabar').removeClass('hide')
            $('#cancelar').removeClass('hide')
            // console.log('editar ' + id)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getEditar2Producto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    // alert(datos['datos']['codigo_producto'])
                    $('#myModalProducto').css('color', 'black')
                    $('.modal-title').html('Modificar datos producto')
                    $('.modal-body').html(datos)
                    $("#myModalProducto").modal()

                    if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == 'Und') {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').attr('disabled', 'disabled')
                    } else {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').attr('disabled', 'disabled')
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').attr('disabled', 'disabled')
                    }
                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })
        })


        // motral modal imagen del producto
        $('table#productos').delegate('.img', 'click', function() {
            var img = $(this).attr('img');
            var producto = $(this).parent().parent().children('td:eq(3)').html()
            var codigo13 = $(this).parent().parent().children('td:eq(1)').html()
            var codigoBoka = $(this).parent().parent().children('td:eq(2)').html()
            $('#myModal').css('color', 'black')
            $('.modal-title').html('<h4>Imagen del producto</h4><h4>' + producto + '</h4><h4>Código: ' + codigo13 + '</h4><h4>Tienda: ' + codigoBoka + '</h4>')
            $('.modal-body').html('<img src="' + img + '"  ></img>')
            $("#myModal").modal()
        })

        // $('.<i class="fas fa-magic mr-1">').click(function() {
        $('table#productos').delegate('.ver', 'click', function() {
            console.log('hola ver')
            id = $(this).parent().parent().attr('producto')
            console.log(id)
            $('#grabar').addClass('hide')
            $('#cancelar').addClass('hide')
            $('#cerrar').removeClass('hide')

            // console.log('ver ' + id)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getVerProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    // alert(datos['datos']['codigo_producto'])
                    $('#myModalProducto').css('color', 'black')
                    $('.modal-title').html('Datos producto')
                    $('.modal-body').html(datos)
                    $("#myModalProducto").modal()

                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })

        })
        $('table#productos').delegate('.ver2', 'click', function() {
            console.log('hola ver')
            id = $(this).parent().parent().attr('producto')
            console.log(id)
            $('#grabar').addClass('hide')
            $('#cancelar').addClass('hide')
            $('#cerrar').removeClass('hide')

            // console.log('ver ' + id)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getVer2Producto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    // alert(datos['datos']['codigo_producto'])
                    $('#myModalProducto').css('color', 'black')
                    $('.modal-title').html('Datos producto')
                    $('.modal-body').html(datos)
                    $("#myModalProducto").modal()

                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })

        })

        var errorCodigo13 = "OK"

        verificadoCodigo = false;

        function validarCodigo13(campo) {
            if (verificadoCodigo) return;
            var campo = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(1) > div > input')
            var codigo13 = campo.val()
            if (!codigo13) return
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/codigo13Valido",
                data: {
                    id: id,
                    codigo13: codigo13,
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);

                    verificadoCodigo = true;
                    campo.removeClass('conError')
                    campo.removeClass('sinError')

                    if (datos) {
                        campo.addClass('conError')
                        alert('El codigo 13 ' + codigo13 + ' YA existe, cambiarlo por otro NO existente')
                        errorCodigo13 = 'El codigo 13 ' + codigo13 + ' YA existe, cambiarlo por otro NO existente';
                        return errorCodigo13
                    }
                },
                error: function() {
                    alert("Error en el proceso. grabarProducto");
                }
            })
            return ""

        }

        // verificar codigo 13
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(1) > div > input', function() {
                $(this).removeClass('conError')
                $(this).removeClass('sinError')
                // console.log($(this).val().length)
                if ($(this).val().length != 13) {
                    $(this).addClass('conError')
                    return
                } else $(this).addClass('sinError')
                verificadoCodigo = false
                validarCodigo13($(this))
            })
        });

        // verificar anada
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(9) > div > input', function() {
                $(this).removeClass('conError')
                $(this).removeClass('sinError')
                // console.log($(this).val().length)
                if ($(this).val() < 1960 || $(this).val() > (new Date().getFullYear())) $(this).addClass('conError')
                else $(this).addClass('sinError')
            })
        });

        // eventos si se cambia tipo unidad
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select', function() {
                console.log('cambiada tipo unidad')
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').prop("disabled", false);
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').prop("disabled", false);
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').prop("disabled", false);
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').prop("disabled", false);
                if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == '---') {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').attr('disabled', 'disabled')
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').attr('disabled', 'disabled')
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').attr('disabled', 'disabled')
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').attr('disabled', 'disabled')

                } else if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == 'Und') {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').attr('disabled', 'disabled')
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').attr('disabled', 'disabled')
                } else if ($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(17) > div > select').val() == 'Kg') {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').attr('disabled', 'disabled')
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').attr('disabled', 'disabled')
                }
            })
        });



        // grupo cambiado -> get IVA  
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(6) > div > select', function() {
                var grupo = $(this).val()
                console.log('grupo cambiado del producto ' + id + ' grupo ' + grupo)
                $.ajax({
                    async: false,
                    type: 'POST',
                    url: "<?php echo base_url() ?>" + "index.php/productos/getIva",
                    data: {
                        grupo: grupo
                    },
                    success: function(datos) {
                        // alert(datos)
                        var datos = $.parseJSON(datos);
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(30) > div > input').val(datos)
                        // alert('cambiado')
                        var margen = calculoMargen()
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
                    },
                    error: function() {
                        alert("Error en el proceso. get IVA. Informar");
                    }
                })

            })
        });

        // precio unidad cambiado 
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val())
                // Si precio transformación != 0 no se hace nada
                if (parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val()) != 0) return;
                var stock_total = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(28) > div > input').val()
                var precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').val()
                var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                if (unidades_precio == 0) {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                    unidades_precio = 1
                }
                var descuento = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input').val()
                var precio_final = precio / unidades_precio * (100 - descuento) / 100
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val(precio_final)
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(29) > div > input').val(precio_final * stock_total)
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        });

        // precio peso cambiado
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val())
                // Si precio transformación != 0 no se hace nada
                if (parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val()) != 0) return;
                var stock_total = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(28) > div > input').val()
                var precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(19) > div > input').val()
                var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                if (unidades_precio == 0) {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                    unidades_precio = 1
                }
                var descuento = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input').val()
                var precio_final = precio / unidades_precio * (100 - descuento) / 100
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val(precio_final)
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(29) > div > input').val(precio_final * stock_total)
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        });



        // descuento cambiado
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val())
                // Si precio transformación != 0 no se hace nada
                if (parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val()) != 0) return;
                var stock_total = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(28) > div > input').val()
                var precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').val()
                var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                if (unidades_precio == 0) {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                    unidades_precio = 1
                }
                var descuento = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input').val()
                var precio_final = precio / unidades_precio * (100 - descuento) / 100
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val(precio_final)
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(29) > div > input').val(precio_final * stock_total)
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        });

        // unidades_precio cambiado
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val())
                // Si precio transformación != 0 no se hace nada
                if (parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val()) != 0) return;
                var stock_total = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(28) > div > input').val()
                var precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').val()
                var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                if (unidades_precio == 0) {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                    unidades_precio = 1
                }
                var descuento = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input').val()
                var precio_final = precio / unidades_precio * (100 - descuento) / 100
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val(precio_final)
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(29) > div > input').val(precio_final * stock_total)
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        });

        // Si se cambia el precio de transformación unidad se toma como precio final y se recalcula todo
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val())
                if (parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(21) > div > input').val()) == 0) {
                    var precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').val()
                    var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                    if (unidades_precio == 0) {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                        unidades_precio = 1
                    }
                    var descuento = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input').val()
                    var precio_final = precio / unidades_precio * (100 - descuento) / 100
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val(precio_final)
                } else {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val($(this).val())
                }
                var stock_total = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(28) > div > input').val()
                var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                if (unidades_precio == 0) {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                    unidades_precio = 1
                }
                var precio_final = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(29) > div > input').val(precio_final * stock_total)
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        });

        // Si se cambia el precio de transformación peso se toma como precio final y se recalcula todo
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').val())
                if (parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').val()) == 0) {
                    var precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(18) > div > input').val()
                    var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                    if (unidades_precio == 0) {
                        $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                        unidades_precio = 1
                    }
                    var descuento = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(20) > div > input').val()
                    var precio_final = precio / unidades_precio * (100 - descuento) / 100
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val(precio_final)
                } else {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val($(this).val())
                }
                var stock_total = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(28) > div > input').val()
                var unidades_precio = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val()
                if (unidades_precio == 0) {
                    $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(24) > div > input').val(1)
                    unidades_precio = 1
                }
                var precio_final = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(29) > div > input').val(precio_final * stock_total)
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        });
        // Si se cambia tarifa venta
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(25) > div > input', function() {
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').val())
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        })

        // si se cambia IVA
        $(function() {
            $(document).on('change', '#myModalProducto > div > div > div.modal-body > form > div:nth-child(30) > div > input', function() {
                alert('cambiado iva')
                // console.log($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(22) > div > input').val())
                var margen = calculoMargen()
                $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen)
            })
        })






        $('#titulo').html("Productos <?php echo $tipoProducto ?>")
        <?php if ($status_producto == 1) { ?>
            $('#titulo').css('color', 'black')
        <?php } else { ?>
            $('#titulo').css('color', 'red')
        <?php } ?>


        // renderiza cuando esta completa la carga
        $('#productos').show()




        function verificaciones() {
            var errores = ""
            $(".errorVerificacion").html(errores);

            // verificacion capos con requerido no estan en blanco
            $.each($('#myModalProducto > div > div > div.modal-body > form > div > div > input'), function(index, value) {
                // se eliminan errores marcados 
                if ($(this).css('background-color') == "rgb(255, 255, 0)") {
                    $(this).css('background-color', 'white')
                }
                var campo = $(this).attr('name')
                var requerido = $(this).attr('requerido')
                var texto = campo
                if (requerido == 1) {
                    if ($(this).val() == "") {
                        <?php foreach ($estructura as $k => $v) { ?>
                            if (campo == "<?php echo $v['campo']; ?>") {
                                texto = "<?php echo $v['texto']; ?>"
                            }
                        <?php } ?>
                        errores += "<p> El " + texto + " debe tener un valor</p>"
                        $(this).css('background-color', 'yellow')
                    }
                }
                // verificamos campos numericos
                <?php foreach ($estructura as $k => $v) { ?>
                    if (campo == "<?php echo $v['campo']; ?>") {
                        texto = "<?php echo $v['texto']; ?>"
                        if ('<?php echo $v['tipo']; ?>' == 'number') {
                            if (!$.isNumeric($(this).val().replace(",", ""))) {
                                errores += "<p> El " + texto + " debe tener un valor numerico</p>"
                                $(this).css('background-color', 'yellow')
                            }
                        }
                    }
                <?php } ?>



                // console.log(campo+requerido)
            });


            // verificar codigo 13
            var codigo13 = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(1) > div > input')
            if (codigo13.val().length != 13) {
                errores += "<p> El código de producto debe tener 13 cifras</p>"
                codigo13.css('background-color', 'yellow')
            }
            verificadoCodigo = false
            validarCodigo13(codigo13)
            if (errorCodigo13) {
                errores += '<p>' + errorCodigo13 + '</p>'
            }
            // si es un vino debe tener una añada
            var anada = $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(9) > div > input')
            if (codigo13.val().substr(0, 2) == '08' && anada.val() != "") {
                if (anada.val() < 1959 || anada.val() > (new Date().getFullYear())) {
                    errores += "<p> La añada del producto debe ser entre los años 1960 y el añoa actual</p>"
                    anada.css('background-color', 'yellow')
                }
            }



            <?php foreach ($estructura as $k => $v) { ?>
                // console.log(<?php echo $v['requerido']; ?>)
            <?php } ?>

            return errores;
        }

        $('#grabar').click(function() {
            $('#grabar > i ').removeClass('hide')
            // codigo_producto 13 cifras
            errorCodigo13 = ""
            var errores = verificaciones()
            console.log('errores ' + errores)
            $(".errorVerificacion").append(errores);
            if (errores) {
                $('#grabar > i ').addClass('hide')
                return
            }
            // var datosForm=$('form').serializeArray()

            var datosForm = '{"id" :' + id + ', ';
            $.each($('#myModalProducto > div > div > div.modal-body > form > div > div > input'), function(index, value) {
                datosForm += '"' + $(this).attr('name') + '" : "' + $(this).val() + '",';
            })
            $.each($('#myModalProducto > div > div > div.modal-body > form > div > div > select'), function(index, value) {
                datosForm += '"' + $(this).attr('name') + '" : "' + $(this).val() + '",';
            })

            datosForm = datosForm.slice(0, -1)
            datosForm += '}'
            console.log(datosForm)
            // datosForm='{"codigo_producto":"1234","id_producto":"5"}'

            // alert(datosForm)
            var tmpData = JSON.parse(datosForm);
            // alert(tmpData)
            // var formattedJson = JSON.stringify(tmpData, null, '\t');


            console.log(tmpData)
            $.ajax({
                async: true,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/grabarProducto",
                // data: tmpData,
                // data: {codigo_producto:'1234'},
                data: tmpData,
                success: function(datos) {
                    $("#myModalProducto").modal('hide')
                    // alert(datos)
                    var datos = $.parseJSON(datos);

                    if (datos['resultado']) {
                        
                        notify()
                        $('#myModal').css('color', 'blue')
                        $('.modal-title').html('Información')
                        $('.modal-body').html("Producto <br> <strong>" + datos['codigo_producto'] + "<br>" + datos['nombre'] + "</strong><br>se ha modificado con éxito")
                        $("#myModal").modal()
                        setTimeout(
                            function() {
                                location.reload();
                            }, 5000);

                    } else {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html('Información')
                        $('.modal-body').html("Producto <br> <strong>" + datos['codigo_producto'] + "<br>" + datos['nombre'] + "</strong><br>NO se ha podido modificar. Avisar")
                        $("#myModal").modal()
                    }

                    // setTimeout(
                    // function() 
                    // {
                    //     location.reload();
                    // }, 1000);
                },
                error: function() {
                    alert("Error en el proceso. grabarProducto");
                }
            })
            $('#grabar > i ').addClass('hide')
        })


        //ventanas modal informando el eliminar un producto
        var id_pe_producto = ""

        function eliminarProducto(id, eliminar, texto) {
            if (eliminar) {
                //alert ("SE ELIMINA " + texto)
                id_pe_producto = id
                $('#pregunta').css('color', 'black')
                $('.modal-title').html('Eliminar producto')
                $('.modal-body>p').html(texto)
                $('#pregunta').modal()

            } else {
                //alert ("NO SE ELIMINA " + texto)
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Eliminar producto')
                $('.modal-body>p').html(texto)
                $('#myModal').modal()
            }

        }

        //si se confirma eliminar un producto
        $('#continuar').click(function() {
            //alert('hola se borrar '+id_pe_producto)
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/eliminarProducto/" + id_pe_producto,
                data: {
                    id_pe_producto: id_pe_producto
                },
                success: function(datos) {
                    //alert(datos);
                    //var datos = $.parseJSON(datos)
                    //alert(datos);
                    location.reload(true);
                },
                error: function() {
                    alert('Error al eliminar producto. Informar')
                }
            })
        })

        function calculoMargen() {
            var tarifa_venta = parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(25) > div > input').val())
            var precio_compra = parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(23) > div > input').val())
            var iva = parseFloat($('#myModalProducto > div > div > div.modal-body > form > div:nth-child(30) > div > input').val())
            if (tarifa_venta == 0) return 0;
            var margen = (100 * tarifa_venta - precio_compra * (100 + iva)) / (tarifa_venta);

            $('#myModalProducto > div > div > div.modal-body > form > div:nth-child(27) > div > input').val(margen.toFixed(2))
            return margen.toFixed(2);
        }



    });
</script>