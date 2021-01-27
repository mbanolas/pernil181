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

        font-weight: normal;
        margin: 15px 0 14px 0;



        color:red;
    }

    div.title_2{
        color: #444;
        background-color: transparent;
        border-bottom: 2px solid #D0D0D0;

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


    nav#mainNav ul li a {
        padding: 0 1.5em;
    }



    div.flexigrid{
        font-size: 14px;
    }
    .ftitle{
        font-size: 20px;

    }

    td{
        text-align: left;
    }

    h4{
        font-weight: bold;
    }

    .modal-content{
        width:1200px;
        margin-left: -300px;
    }

    .gc-container{
        margin-top: 20px;
    }



</style>

<!-- <script src="<?php echo base_url() ?>/bower_components/webcomponentsjs/webcomponents-loader.js"></script> -->

<!-- <link rel="import" href="<?php echo base_url() ?>/src/dialogo-app/dialogo-app.html"> -->
<!-- <link rel="import" href="<?php echo base_url() ?>/bower_components/paper-dialog/paper-dialog.html"> -->

<!-- <link rel="import" href="<?php echo base_url() ?>/bower_components/paper-dialog-scrollable/paper-dialog-scrollable.html"> -->
<!-- <link rel="import" href="<?php echo base_url() ?>/bower_components/drag-resize/drag-resize.html"> -->


<?php
//para incluir título en cabecera tabla
/*
  $titulo=isset($titulo)?$titulo:'Sin Título' ;
  $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10; */
?>

<input type="hidden" id="titulo" value="<?php echo $titulo ?>">



        <paper-dialog id="animated" with-backdrop>
           
            <paper-dialog-scrollable>
                 <drag-resize>
                    <div class="content" >
                    </div>
                </drag-resize>
            </paper-dialog-scrollable>

        </paper-dialog>

    

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
        //align cabeceras tabla
        $('th:nth-child(1)').css('text-align', 'left')
        $('th:nth-child(2)').css('text-align', 'left')
        // $('th:nth-child(3)').css('text-align', 'left')
        // $('th:nth-child(4)').css('text-align', 'left')
        // $('th:nth-child(5)').css('text-align', 'center')
        // $('th:nth-child(6)').css('text-align', 'right')
        // $('th:nth-child(7)').css('text-align', 'right')


        $('.table-label div:first-child').html('<h4><?php echo $titulo ?></h4>')
        $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Nueva Forma Pago Acreedor')


        // $('body').delegate(' a[href*="/add"]', 'click', function (e)
        // {
        //     e.preventDefault()
        //     window.location.replace("<?php echo base_url() ?>" + "index.php/compras/facturaProveedor");
        // })


        // $('input#field-num_albaran').attr('disabled', 'disabled')
        // $('input#field-numFactura').attr('disabled', 'disabled')
        // $('input#field-numPedido').attr('disabled', 'disabled')
        // $('input#field-fecha').attr('disabled', 'disabled')
        // $('input#field-importe').attr('disabled', 'disabled')

        // //cambiar el selector id_proveedor por etiqueta
        // var id = $('select#field-id_proveedor').val()
        // var nombre = $('option[value="' + id + '"]').html()
        // $('select#field-id_proveedor').parent().addClass('nombreProveedor')
        // $('select#field-id_proveedor').parent().html('')
        // $('.nombreProveedor').append('<label class="control-label">' + nombre + '</label>')

        // //cmbiar input por label en edit (pagar)
        // var nombreNumFactura = $('input#field-numFactura').val()
        // $('input#field-numFactura').parent().addClass('nombreNumFactura')
        // $('input#field-numFactura').parent().html('')
        // $('.nombreNumFactura').append('<label class="control-label">' + nombreNumFactura + '</label>')

        // //cmbiar input por label en edit (pagar)
        // var nombreNumPedido = $('input#field-numPedido').val()
        // $('input#field-numPedido').parent().addClass('nombreNumPedido')
        // $('input#field-numPedido').parent().html('')
        // $('.nombreNumPedido').append('<label class="control-label">' + nombreNumPedido + '</label>')

        // //cmbiar input por label en edit (pagar)
        // var nombreFecha = $('input#field-fecha').val()
        // $('input#field-fecha').parent().addClass('nombreFecha')
        // $('input#field-fecha').parent().html('')
        // $('.nombreFecha').append('<label class="control-label">' + nombreFecha + '</label>')

        // //cmbiar input por label en edit (pagar)
        // var nombreImporte = $('input#field-importe').val()
        // $('input#field-importe').parent().addClass('nombreImporte')
        // $('input#field-importe').parent().html('')
        // $('.nombreImporte').append('<label class="control-label">' + nombreImporte / 100 + '</label>')

        // $('body').delegate('tr td> div>  div> ul> li> a[href*="facturasProveedores/read"]', 'click', function (e)
        // {
        //     e.preventDefault()
        //     $('dialogo-app').removeClass('hide')

        //     var id = $(this).attr('href').substr($(this).attr('href').lastIndexOf("/") + 1)
        //     $.ajax({
        //         type: 'POST',
        //         url: "<?php echo base_url() ?>" + "index.php/compras/getFacturaProveedor",
        //         data: {id: id},
        //         success: function (datos) {
        //             //alert('getFacturaProveedor '+datos)
        //             var datos = $.parseJSON(datos);

        //             var tabla = "<table class='table'>"
        //             tabla += "<tr><td>Proveedor</td>"
        //             tabla += '<td>' + datos['proveedor'] + '</td></tr>'
        //             tabla += "<tr><td>Factura Núm.</td>"
        //             tabla += '<td>' + datos['numFactura'] + '</td></tr>'
        //             tabla += "<tr><td>Basada en albaranes</td>"

        //             tabla += '<td>' + datos['albaran'] + '</td></tr>'


        //             tabla += '<tr ><th class="text-left">Importe Total Factura</th>'
        //             tabla += '<th class="text-left">' + (datos['importe'] / 100).toFixed(2) + '</th></tr>'

        //             tabla += "<tr><td>Fecha</td>"
        //             tabla += '<td>' + datos['fecha'].substr(8, 2) + '/' + datos['fecha'].substr(5, 2) + '/' + datos['fecha'].substr(0, 4) + '</td></tr>'
        //             tabla += "</tr>"

        //             tabla += "<tr><td>Pagado el </td>"
        //             if (datos['fecha_pago'] == '0000-00-00') {
        //                 tabla += '<td>Pendiente pago</td>'
        //             } else {
        //                 tabla += '<td>' + datos['fecha_pago'].substr(8, 2) + '/' + datos['fecha_pago'].substr(5, 2) + '/' + datos['fecha_pago'].substr(0, 4) + '</td></tr>'
        //             }
        //             tabla += "</tr>"

        //             tabla += '</table>'
        //             tabla += 'Detalles'

        //             var tabla2 = "<table class='table'>"
        //             tabla2 += "<tr><th class='text-left'>Código 13</th>"
        //             tabla2 += "<th class='text-left'>Producto</th>"
        //             tabla2 += '<th class="text-right">Cantidad</th>'
        //             tabla2 += '<th class="text-right">Und</th>'
        //             tabla2 += '<th class="text-right">Fecha caducidad</th>'
        //             tabla2 += '<th class="text-right">Precio</th>'
        //             tabla2 += '<th class="text-right">Dto.</th>'
        //             tabla2 += '<th class="text-right">Base</th>'
        //             tabla2 += '<th class="text-right">% Iva</th>'
        //             tabla2 += '<th class="text-right">Iva</th>'
        //             tabla2 += '<th class="text-right">Total</th>'


        //             var tbase = 0
        //             var tiva = 0
        //             var ttotal = 0
        //             for (var i = 0; i < datos['lineas'].length; i++) {
        //                 var cantidad = datos['lineas'][i]['cantidad'] / 1000
        //                 if (datos['lineas'][i]['tipoUnidad'] === 'Und')
        //                     cantidad == cantidad.toFixed(0)
        //                 else
        //                     cantidad = cantidad.toFixed(3)
        //                 // if(cantidad==cantidad.toFixed(0)) cantidad=cantidad.toFixed(0); else cantidad=cantidad.toFixed(3)
        //                 tabla2 += "<tr><td class='text-left'>" + datos['lineas'][i]['codigo_producto'] + "</td>"
        //                 tabla2 += "<td class='text-left'>" + datos['lineas'][i]['nombre'] + "</td>"
        //                 tabla2 += "<td class='text-right'>" + cantidad + "</td>"
        //                 tabla2 += "<td class='text-right'>" + datos['lineas'][i]['tipoUnidad'] + "</td>"
        //                 if (datos['lineas'][i]['fechaCaducidad'] === '0000-00-00')
        //                     datos['lineas'][i]['fechaCaducidad'] = ''
        //                 else
        //                     datos['lineas'][i]['fechaCaducidad'] = datos['lineas'][i]['fechaCaducidad'].substr(8, 2) + "/" + datos['lineas'][i]['fechaCaducidad'].substr(5, 2) + "/" + datos['lineas'][i]['fechaCaducidad'].substr(0, 4)
        //                 tabla2 += "<td class='text-center'>" + datos['lineas'][i]['fechaCaducidad'] + "</td>"
        //                 tabla2 += "<td class='text-right'>" + (datos['lineas'][i]['precio'] / 100).toFixed(2) + "</td>"
        //                 tabla2 += "<td class='text-right'>" + (datos['lineas'][i]['descuento'] / 1000).toFixed(2) + "</td>"
        //                 var base=Number((datos['lineas'][i]['total'] / 100).toFixed(2))
        //                 tabla2 += "<td class='text-right'>" + (datos['lineas'][i]['total'] / 100).toFixed(2) + "</td>"
        //                 tabla2 += "<td class='text-right'>" + (datos['lineas'][i]['tipoIva'] / 100).toFixed(2) + "</td>"
        //                 var iva=Number((datos['lineas'][i]['total'] * datos['lineas'][i]['tipoIva'] / 100 / 100 / 100).toFixed(2))
        //                 tabla2 += "<td class='text-right'>" + (datos['lineas'][i]['total'] * datos['lineas'][i]['tipoIva'] / 100 / 100 / 100).toFixed(2) + "</td>"
        //                 var total=Number((datos['lineas'][i]['total'] * datos['lineas'][i]['tipoIva'] / 100 / 100 / 100 + datos['lineas'][i]['total'] / 100).toFixed(2))
        //                 tabla2 += "<td class='text-right'>" + (datos['lineas'][i]['total'] * datos['lineas'][i]['tipoIva'] / 100 / 100 / 100 + datos['lineas'][i]['total'] / 100).toFixed(2) + "</td>"
        //                 tabla2 += "</tr>"

        //                 tbase += base //parseFloat(datos['lineas'][i]['total'] / 100)
        //                 tiva += iva //parseFloat(datos['lineas'][i]['total'] * datos['lineas'][i]['tipoIva'] / 100 / 100 / 100)
        //                 ttotal += total //parseFloat(datos['lineas'][i]['total'] * datos['lineas'][i]['tipoIva'] / 100 / 100 / 100 + datos['lineas'][i]['total'] / 100)
        //             }
        //             tbase += parseFloat(datos['otrosCostes'] / 100)
        //             tiva += parseFloat(datos['tipoIva'] / 100)

        //             tabla2 += "<tr>"

        //             tabla2 += "<td>Otros costes</td>"
        //             tabla2 += "<td> </td>"
        //             tabla2 += "<td> </td>"
        //             tabla2 += "<td> </td>"
        //             tabla2 += "<td> </td>"
        //             tabla2 += "<td> </td>"
        //             tabla2 += "<td> </td>"
        //             tabla2 += "<td class='text-right'>" + (datos['otrosCostes'] / 100).toFixed(2) + "</td>"
        //             tabla2 += "<td class='text-right'>" + (datos['tipoIva'] / 100).toFixed(2) + "</td>"
        //             tabla2 += "<td class='text-right'>" + ((datos['otrosCostes'] / 100) * datos['tipoIva'] / 100 / 100).toFixed(2) + "</td>"
        //             var totrosCostes = parseFloat(datos['otrosCostes'] / 100)
        //             totrosCostes += parseFloat((datos['otrosCostes'] / 100) * datos['tipoIva'] / 100 / 100)
        //             ttotal += totrosCostes
        //             tabla2 += "<td class='text-right'>" + (totrosCostes.toFixed(2)) + "</td>"
        //             tabla2 += "</tr>"
        //             tabla2 += "<tfooter><tr>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th>" + tbase.toFixed(2) + "</th>"
        //             tabla2 += "<th> </th>"
        //             tabla2 += "<th>" + tiva.toFixed(2) + "</th>"
        //             tabla2 += "<th>" + ttotal.toFixed(2) + "</th>"
        //             tabla2 += "</tr></tfooter>"

        //             tabla2 += '</table>'



        //             $('.modal-title').html('Factura')
        //             $('.modal-body').html(tabla + tabla2)
        //             $("#myModal").modal()
        //             //$('#animated').html(tabla + tabla2)
        //           //  $('.content').html('<h3>Factura Proveedor</h3>'+tabla + tabla2)
        //           //  animated.open()


        //         },
        //         error: function () {
        //             alert("Error en el proceso ver factura proveedor. Informar");
        //         }
        //     })
        // });


        // // $('#titulo').after('<h3 class="titulo"><?php echo $titulo; ?></h3>') 


        // $('[rel="peso_real"]').removeClass('text-left')
        // $('[rel="peso_real"]').addClass('text-right')
        // $('[rel="tarifa_venta"]').removeClass('text-left')
        // $('[rel="tarifa_venta"]').addClass('text-right')
        // $('[rel="precio_ultimo"]').removeClass('text-left')
        // $('[rel="precio_ultimo"]').addClass('text-right')
        // $('[rel="descuento_1_compra"]').addClass('text-right')
        // $('[rel="margen_real_producto"]').addClass('text-right')

        function load() {
            var titulo = $('#titulo').val()
            // $('div.ftitle').html(titulo)
        }
        ;

        // window.onload = load;


        // $('select#field-id_pe_producto').attr('style', 'width:auto;');
        // $('input#field-cantidad').attr('style', 'width:auto;');



        // $('#field-id_pe_producto_buscar').change(function () {
        //     var filtro = $(this).val()
        //     buscarProductos(filtro)
        // })

        // buscarProductos("")

        // $('.readonly_label option').remove();


        // function buscarProductos(filtro) {

        //     //var filtro=$(this).val()
        //     //alert('Hola blur '+$(this).val())
        //     $.ajax({
        //         type: 'POST',
        //         url: "<?php echo base_url() ?>" + "index.php/compras/getIdProductosFiltrados",
        //         data: {filtro: filtro},
        //         success: function (datos) {
        //             //alert(datos)
        //             var datos = $.parseJSON(datos);
        //             // alert(datos['nombres'])
        //             $("select#field-id_pe_producto option").remove();
        //             var option = '<option value="0">Seleccionar un producto</option>'
        //             $('#field-id_pe_producto').append(option)
        //             $.each(datos['nombres'], function (index, value) {
        //                 var option = '<option value="' + datos['id_pe_producto'][index] + '">' + value + ' (' + datos['ids'][index] + ')</option>'
        //                 $('#field-id_pe_producto').append(option)
        //             })


        //         },
        //         error: function () {
        //             alert("Error en el proceso. Informar");
        //         }
        //     })

        // }





    })



</script>

