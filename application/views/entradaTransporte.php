<style>
    #buscarPedido,
    #buscarTicket {
        margin-left: 5px;
        margin-top: 25px;
    }
    #cambiarDatosCliente {
        margin-left: 5px;
    }

    .form-horizontal {
        margin-left: 12px;
    }

    .input_label {

        border: 0px;
        background-color: white;
        box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0);
    }

    .div_input_label {
        margin-left: 50px;
    }

    .table>tbody>tr>td {
        padding: 8px 0px !important;
    }

    .datosPedido>div>table>tbody>tr>th {
        text-align: left;
    }

    .cliente {
        color: blue;
    }

    #grupo2 {
        margin-left: 20px;
    }

    .nada {
        color: white;
    }
    .separacion{
        padding: 0px;
    }
    /* .datosPedido.separacion{
        padding: 0 0 0 20px;
    } */
</style>
<br />
<h3>Entrada transportes pagados por cliente</h3>
<br>

<div class="form-horizontal">
    <input type="hidden" id="id"  value="">
    <div class="row">
        <div class="form-group col-sm-2">
            <label for="exampleInputEmail1">Núm Pedido Prestashop</label>
            <input type="text" class="form-control" id="pedido" aria-describedby="emailHelp" placeholder="Pedido Prestashop" value="">
        </div>
        <div class="form-group col-sm-2">
            <button type="text" class="btn btn-default" id="buscarPedido">Buscar datos pedido <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
        </div>

        <div class="form-group col-sm-1">
        </div>

        <div class="form-group col-sm-2" id="grupo2">
            <label >Núm Ticket</label>
            <input type="text" class="form-control" id="ticket" placeholder="Ticket tienda" value="">
        </div>
        <div class="form-group col-sm-2">
            <button type="text" class="btn btn-default" id="buscarTicket">Buscar datos ticket <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
        </div>

    </div>
    <div class="row hide datosPedido">
        <div class="form-group col-sm-4">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">Fecha</th>
                        <td id="fecha"></td>
                    </tr>
                    <tr>
                        <th scope="row">Base transporte Facturado</th>
                        <td id="base_factura"></td>
                    </tr>
                    <tr>
                        <th scope="row">IVA %</th>
                        <td id="tipo_iva_transporte"></td>
                    </tr>
                    <tr>
                        <th scope="row">Total transporte facturado</th>
                        <td id="total_factura"></td>
                    </tr>
                    <tr>
                        <th class="cliente" scope="row">Base transporte cliente</th>
                        <td class="cliente" id="base_transporte"></td>
                    </tr>
                    <tr>
                        <th class="cliente" scope="row">IVA % transporte cliente</th>
                        <td class="cliente" id="ivaCliente"></td>
                    </tr>
                    <tr>
                        <th class="cliente" scope="row">Total transporte cliente</th>
                        <td class="cliente" id="transporte"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-group col-sm-1">
        </div>

        <div class="form-group col-sm-4 " id="tablaTicket">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">Fecha</th>
                        <td id="fecha_ticket"></td>
                    </tr>
                    <tr>
                        <th class="nada" scope="row">nada </th>
                        <td class="nada">nada </td>
                    </tr>
                    <tr>
                        <th class="nada" scope="row">nada </th>
                        <td class="nada">nada </td>
                    </tr>
                    <tr>
                        <th class="nada" scope="row">nada </th>
                        <td class="nada">nada </td>
                    </tr>
                    <tr>
                        <th class="cliente" scope="row">Base transporte cliente</th>
                        <td class="cliente" id="base_transporte_ticket"></td>
                    </tr>
                    <tr>
                        <th class="cliente" scope="row">IVA %</th>
                        <td class="cliente" id="tipo_iva_transporte_ticket"></td>
                    </tr>
                    
                    
                    <tr>
                        <th class="cliente" scope="row">Total transporte cliente</th>
                        <td class="cliente" id="total_factura_ticket"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row hide datosPedido separacion">
        <div class="form-group col-sm-2 separacion">
            <label for="baseTransporteCliente">Base Transporte cliente</label>
            <input type="text" class="form-control" id="baseTransporteCliente" aria-describedby="emailHelp" placeholder="Base trans cliente" value="">
        </div>
        <div class="form-group col-sm-1">
        </div>
    
        <div class="form-group col-sm-2 separacion">
            <label for="exampleInputEmail1">IVA % </label>
            <input type="text" class="form-control" id="ivaTransporteCliente" placeholder="IVA % trans cliente" value="">
        </div>
        <div class="form-group col-sm-1">
        </div>
    
        <div class="form-group col-sm-2 separacion">
            <label for="exampleInputEmail1">Total Transporte cliente</label>
            <input type="text" class="form-control" id="totalTransporteCliente"  placeholder="Total trans cliente" value="">
        </div>
        <div class="form-group col-sm-1">
        </div>
        <div class="form-group col-sm-3 separacion">
            <label for="exampleInputEmail1">Observaciones/comentarios</label>
            <input type="text" class="form-control" id="observaciones"  placeholder="Observaciones" value="">
        </div>
    
    </div>
    <div class="row hide datosPedido separacion">
        <div class="form-group col-sm-3 separacion">
            <button type="text" class="btn btn-default" id="cambiarDatosCliente">Registrar datos en prestashop <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
        </div>
        <div class="form-group col-sm-2 separacion">
            <button type="text" class="btn btn-default" id="cancelar">Cancelar <img class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
        </div>
    </div>


</div>

<script>
    $(document).ready(function() {

        var cambios = false

        $('#cancelar').click(function(){
            cambios=false
            window.location = "<?php echo base_url()?>index.php/ventas/entradaTransporte" 
        })

        function fechaEuropea(fecha) {
            return fecha.substr(8, 2) + "/" + fecha.substr(5, 2) + "/" + fecha.substr(0, 4) + fecha.substr(10, 10)
        }

        $('#buscarProveedores').focus()

        $('#buscarPedido').click(function() {
            var pedido = $('#pedido').val();
            if (pedido == 0) {
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('No se ha indicado ningún número de pedido prestashop.')
                $("#myModal").modal()
                return false
            }
            $('#buscarPedido > img').removeClass('hide')
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/ventas/getPedido",
                data: {
                    pedido: pedido
                },
                success: function(datos) {
                    cambios=true
                    $('#buscarPedido > img').addClass('hide')
                    if (datos == 0) {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html('Información')
                        $('.modal-body>p').html('<strong>El pedido ' + pedido + ' NO existe.</strong>')
                        $("#myModal").modal()
                        return
                    }
                    var datos = $.parseJSON(datos)
                    if($('#ticket').val()==""){
                        $('#tablaTicket').addClass('hide')
                    } 
                    $('.datosPedido').removeClass('hide')
                    $('#fecha').html(fechaEuropea(datos.fecha))
                    $('#id').val(datos.id)
                    $('#base_factura').html((datos.base_factura / 100).toFixed(2))
                    $('#tipo_iva_transporte').html((datos.tipo_iva_transporte / 1000).toFixed(2))
                    $('#total_factura').html((datos.base_factura / 100 * (1 + datos.tipo_iva_transporte / 100000)).toFixed(2))
                    $('#base_transporte').html((datos.base_transporte / 1000).toFixed(2))
                    if(datos.base_transporte!=0)
                        $('#ivaCliente').html((((datos.transporte/datos.base_transporte-1)).toFixed(2)*100).toFixed(2))
                    else
                        $('#ivaCliente').html((0).toFixed(2))

                    $('#transporte').html((datos.transporte / 1000).toFixed(2))

                    $('#baseTransporteCliente').val($('#base_transporte').html())
                    $('#ivaTransporteCliente').val($('#ivaCliente').html())
                    $('#totalTransporteCliente').val($('#transporte').html())

                },
                error: function() {
                    $('#buscarPedido > img').addClass('hide')
                    alert("Información importante - Error en el proceso buscar pedido");
                }
            })

        })

        $('#pedido').click(function() {
            $('.datosPedido').addClass('hide')
        })

        $('#ticket').click(function() {
            $('#tablaTicket').addClass('hide')
        })

        $('#buscarTicket').click(function() {
            var ticket = $('#ticket').val();
            var pedido = $('#pedido').val();
            if (ticket == 0) {
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('No se ha indicado ningún ticket.')
                $("#myModal").modal()
                return false
            }
            if (pedido == 0) {
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Se debe indicar un núm pedido prestashop')
                $("#myModal").modal()
                return false
            }
            $('#buscarTicket > img').removeClass('hide')
            cambios=true
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/ventas/getTicketPedido",
                data: {
                    ticket: ticket,
                    pedido: pedido
                },
                success: function(datos) {
                    $('#buscarTicket > img').addClass('hide')

                    var datos = $.parseJSON(datos)
                    if (datos['resultado'] == 0) {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html('Información')
                        $('.modal-body>p').html('El ticket ' + ticket + ' NO existe.')
                        $("#myModal").modal()
                        return
                    }
                    if (datos['resultado'] == 1) {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html('Información')
                        $('.modal-body>p').html('El pedido ' + pedido + ' NO existe.')
                        $("#myModal").modal()
                        return
                    }
                    if (datos['resultado'] == 2) {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html('Información')
                        $('.modal-body>p').html('<strong>El ticket ' + ticket + ' NO contiene transporte cliente</strong><br>Tickes cercanos a la fecha pedido prestashop con transporte cliente: <br>'+ datos['ticketsCercanos'])
                        $("#myModal").modal()
                        return
                    }

                    // var datos = $.parseJSON(datos)
                    $('.datosPedido').removeClass('hide')
                    $('#tablaTicket').removeClass('hide')
                    $('#fecha').html(fechaEuropea(datos['rowPedido'].fecha))
                    $('#id').val(datos['rowPedido'].id)
                    $('#base_factura').html((datos['rowPedido'].base_factura / 100).toFixed(2))
                    $('#tipo_iva_transporte').html((datos['rowPedido'].tipo_iva_transporte / 1000).toFixed(2))
                    $('#total_factura').html((datos['rowPedido'].base_factura / 100 * (1 + datos['rowPedido'].tipo_iva_transporte / 100000)).toFixed(2))
                    $('#base_transporte').html((datos['rowPedido'].base_transporte / 1000).toFixed(2))
                    $('#transporte').html((datos['rowPedido'].transporte / 1000).toFixed(2))


                    $('#fecha_ticket').html(fechaEuropea(datos['rowTicket'].ZEIS))
                    $('#base_transporte_ticket').html(((datos['rowTicket'].BT20 - datos['rowTicket'].BT40) / 100).toFixed(2))
                    if(datos['rowPedido'].base_transporte!=0)
                        $('#ivaCliente').html((((datos['rowPedido'].transporte/datos['rowPedido'].base_transporte-1)).toFixed(2)*100).toFixed(2))
                    else
                        $('#ivaCliente').html((0).toFixed(2))

                    $('#tipo_iva_transporte_ticket').html((datos['rowTicket'].MWSA/ 100).toFixed(2))    
                    $('#total_factura_ticket').html((datos['rowTicket'].BT20 / 100).toFixed(2))

                    $('#baseTransporteCliente').val($('#base_transporte_ticket').html())
                    $('#ivaTransporteCliente').val($('#tipo_iva_transporte_ticket').html())
                    $('#totalTransporteCliente').val($('#total_factura_ticket').html())
                    $('#observaciones').val("Transporte cliente Ticket "+$('#ticket').val())


                },
                error: function() {
                    $('#buscarTicket > img').removeClass('hide')
                    alert("Información importante - Error en el proceso buscar ticket");
                }
            })

        })

        function round(value, decimals) {
            return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
        }

        $('#cambiarDatosCliente').click(function(){

            var id=$('#id').val()
            var baseTransporteCliente=round($('#baseTransporteCliente').val(),2)
            var ivaTransporteCliente=round($('#ivaTransporteCliente').val(),2)
            var totalTransporteCliente=round($('#totalTransporteCliente').val(),2)
            var observaciones=$('#observaciones').val()

            var iva=round((baseTransporteCliente*(ivaTransporteCliente/100)),2)
            var total=round((baseTransporteCliente)+(iva),2)
            
            var diferencia=(total)-(totalTransporteCliente)

            if(diferencia!=0){
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('En los datos propuestos EXISTEN diferencia de cálculo. Los valores considerados son: '
                +'<br>base = '+baseTransporteCliente
                +'<br>% IVA = '+ivaTransporteCliente
                +'<br>iva = '+iva
                +'<br>total = '+total
                +'<br>los registros se realizarán con los decimales indicados'
                +'<br>En caso de desear cambiarlos, introducir los nuevos y vuelva a puldar Registrar datos en prestashop'
                )
                $("#myModal").modal()
                return
            }
            else{
                $('#cambiarDatosCliente > img').removeClass('hide')
                cambios=false

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>" + "index.php/ventas/grabarDatosTransporteClientes",
                    data: {
                        baseTransporteCliente: baseTransporteCliente,
                        iva:iva,
                        total: total,
                        id:id,
                        observaciones:observaciones
                    },
                    success: function(datos) {
                        $('#cambiarDatosCliente > img').addClass('hide')

                        if(datos){
                        $('#myModal').css('color', 'blue')
                        $('.modal-title').html('Información')
                        $('.modal-body>p').html('Valores registrados correctamente: '
                                +'<br>base = '+baseTransporteCliente.toFixed(2)
                                +'<br>% IVA = '+ivaTransporteCliente.toFixed(2)
                                +'<br>iva = '+iva.toFixed(2)
                                +'<br>total = '+total.toFixed(2)
                                +'<br>Observaciones: '+observaciones
                                )
                        $("#myModal").modal()
                        setTimeout(function(){
                            window.location = "<?php echo base_url()?>index.php/ventas/entradaTransporte" 
                            }, 3000);
                        return
                        }
                        else{

                        }
                    },
                    error: function() {
                        $('#cambiarDatosCliente > img').addClass('hide')
                        alert("Información importante - Error en el proceso buscar ticket");
                    }
                })

            }

        })


        window.onbeforeunload = confirmExit

        function confirmExit() {
            if (cambios) {
                return 'Ha introducido datos que no se han registrado.'

            }
        }



    })
</script>