<style>
#beneficio{
    font-weight: bold;
}
#precioCompra,#tarifaVenta{
   width:100px;
}
</style>

<br>
<h5>Procedimiento para <strong style="color:blue">crear nuevos productos con un peso determinado</strong>, copiando los datos desde el producto de COMPRA seleccionado (Cód. Boka = 0 y tipo unidad = Kg). Los precios de compra, tarifa de venta y nombre se calculan y definen a partir del productos de compra. Esos valores se pueden variar en caso de ser necesario.</h5>
<div class="form-horizontal">
    <div class="row">
        <div class="col-sm-3">
            <label class="col-sm-12 form-control-label">Filtro productos compra </label>
            <div class="input-group">
                <input type="text" id="buscarProductosCompra" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term">
                <div class="input-group-btn ">
                    <button class="btn btn-default btn-sm" id="buscarProducto"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <label for="productoCompra" class="col-sm-12 form-control-label">Producto Compra </label>
            <?php echo form_dropdown('productoCompra', $productosCompra, '', array('width' => '100%', 'id' => 'productoCompra', 'class' => ' form-control input-sm ')); ?>
        </div>
    </div>

    <br>
    <h5>Procedimiento para <strong style="color:blue">crear nuevos productos bodega con otra añada</strong>, copiando los datos desde el producto de mayor añada catalogado. Los precios de compra, tarifa de venta y nombre se calculan y definen a partir del productos de mayor añada. Esos valores se pueden variar en caso de ser necesario.</h5>

    <div class="row">
        <div class="col-sm-3">
            <label class="col-sm-12 form-control-label">Filtro productos bodega </label>
            <div class="input-group">
                <input type="text" id="buscarProductosBodega" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term">
                <div class="input-group-btn ">
                    <button class="btn btn-default btn-sm" id="buscarVino"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <label for="productoCompra" class="col-sm-12 form-control-label">Producto Bodega </label>
            <?php echo form_dropdown('productoCompra', $productosBodega, '', array('width' => '100%', 'id' => 'productoBodega', 'class' => ' form-control input-sm ')); ?>
        </div>
    </div>

    <div id="infoProductos">

    </div>

    <div id="anadirProductos" class="hide">
        <h5>Datos nuevo producto</h5>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">Boka: <span id="boka"></span></div>
                <div class="col-sm-9">Nombre genérico: <input style="width:400px;" id="nombreGenerico" type="text" name="nombreGenerico"></div>
            </div>
            <div class="row">
                <div class="col-sm-3">Peso (Kg): <input id="peso" style="width:100px;background-color:yellow" type="number" name="peso"></div>
                <div class="col-sm-3">Precio compra (€/und): <input  type="number" name="precioCompra" id="precioCompra"></div>
                <div class="col-sm-3">Tarifa venta (€/und): <input  type="number" name="tarifaVenta" id="tarifaVenta"></div>
                <div class="col-sm-3">Iva (%): <input style="width:100px;" type="number" name="iva" id="iva"></div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">Beneficio (%): <span id="beneficio"></span></div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-3">Código 13: <input id="codigoProducto" style="width:150px;" type="text" name="codigoProducto"></div>
                <div class="col-sm-9">Nombre: <input style="width:400px;" id="nombre" type="text" name="nombre"></div>
            </div>
            <br>
            <div class="row ">
                <button id="insertar" type="button" class="btn btn-default">Insertar nuevo producto copiado de <strong><span id="codigoProductoOriginal"></span></strong></button>
            </div>
        </div>
    </div>

    <div id="anadirVinos" class="hide">
        <h5>Datos nuevo producto vino</h5>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">Boka: <span id="bokaVino"></span></div>
                <div class="col-sm-9">Nombre genérico: <input style="width:400px;" id="nombreGenericoVino" type="text" name="nombreGenerico"></div>
            </div>
            <div class="row">
                <div class="col-sm-3">Añada (año yyyy): <input id="anada" style="width:100px;background-color:yellow" type="number" name="anada"></div>
                <div class="col-sm-3">Precio compra (€/und): <input  type="number" name="precioCompra" id="precioCompraVino"></div>
                <div class="col-sm-3">Tarifa venta (€/und): <input  type="number" name="tarifaVenta" id="tarifaVentaVino"></div>
                <div class="col-sm-3">Iva (%): <input style="width:100px;" type="number" name="iva" id="ivaVino"></div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">Beneficio (%): <span id="beneficioVino"></span></div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-3">Código 13: <input id="codigoProductoVino" style="width:150px;" type="text" name="codigoProducto"></div>
                <div class="col-sm-9">Nombre: <input style="width:400px;" id="nombreVino" type="text" name="nombreVino"></div>
            </div>
            <br>
            <div class="row ">
                <button id="insertarVino" type="button" class="btn btn-default">Insertar nuevo vino copiado de <strong><span id="codigoProductoOriginalVino"></span></strong></button>
            </div>
        </div>
    </div>

    <!-- <div class="form-group"> 
        <div class="col-sm-offset-0 col-sm-10" id="botones">
            <button type="submit" class="btn btn-default" id="prepararRangosProducto">Preparar rangos producto</button>
            <button type="submit" class="btn btn-default" id="cancelar">Cancelar</button>
        </div>
    </div> -->
</div>

<script>
    $(document).ready(function() {
        $('#buscarProductos').focus()

        $('input.searchable-input').keyup(function(ev) {
            if (ev.which == 13 || ev.which == 9) {
                return;
            }
            if ($(this).val()) {
                $(this).css('border-color', '#444')
                $(this).css('border-style', 'dashed')
                $(this).css('color', 'red')
            } else {
                $(this).css('border', '1px solid #ccc')
                $(this).css('color', 'black')
            }
        })

        

        // CLEARABLE INPUT
        function tog(v) {
            return v ? 'addClass' : 'removeClass';
        }
        var nombreId
        $(document).on('input', '.clearable', function() {
            nombreId=$(this).attr('id')
            $(this)[tog(this.value)]('x');
            // console.log(nombreId)
        }).on('mousemove', '.x', function(e) {
            $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function(ev) {
            nombreId=$(this).attr('id')
            
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border', '3px solid #ccc')
            if(nombreId=='buscarProductosCompra')
                filtroProductosCompra(" ")
            if(nombreId=='buscarProductosBodega')
                filtroProductosBodega(" ")
        });

        function filtroProductosBodega(filtro) {
            // alert(filtro)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/stocks/getProductosBodegaFiltro",
                data: {
                    filtro: filtro
                },
                success: function(datos) {
                    
                    var datos = $.parseJSON(datos);
                    $('select#productoBodega option').remove();
                    //  $('select#productoCompra').append('<option value="0">'+'Seleccionar producto compra ajax'+'</option>')

                    $.each(datos, function(index, value) {
                        // console.log(value)
                        var nombre = value
                        var codigo_producto = index
                        var option = '<option value="' + codigo_producto + '">' + value + '</option>'
                        $('#productoBodega').append(option)
                    })
                },
                error: function() {
                    alert("Error en el proceso. Informar");
                }
            })
        }

        function filtroProductosCompra(filtro) {
            // alert(filtro)
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/stocks/getProductosCompraFiltro",
                data: {
                    filtro: filtro
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    $('select#productoCompra option').remove();
                    //  $('select#productoCompra').append('<option value="0">'+'Seleccionar producto compra ajax'+'</option>')

                    $.each(datos, function(index, value) {
                        //  alert(value)
                        var nombre = value
                        var codigo_producto = index
                        var option = '<option value="' + codigo_producto + '">' + value + '</option>'
                        $('#productoCompra').append(option)
                    })
                },
                error: function() {
                    alert("Error en el proceso. Informar");
                }
            })
        }

        $('#buscarProducto').click(function(e) {
            var filtro = $('#buscarProductosCompra').val()
            filtroProductosCompra(filtro)
            $('select#productoCompra').focus()
        })

        


        $('#buscarProductosCompra').click(function(ev) {
            $('#infoProductos').html('')
            $('#anadirProductos').addClass('hide')
            $('#anadirVinos').addClass('hide')
            $('#buscarProductosBodega').val('')
            $('select#productoBodega').val(0)
            // $('#buscarProductosBodega').trigger('click')

            $(this).val('')
            filtroProductosCompra("")
        }).keydown(function(ev) {
            if (ev.which == 13 || ev.which == 9) {
                ev.preventDefault();
                var filtro = $(this).val()
                filtroProductosCompra(filtro)
                $(this).css('border', '1px solid #ccc')
                $(this).css('color', 'black')
                $('select#productoCompra').focus()
            }
        })

        $('#buscarVino').click(function(e) {
            var filtro = $('#buscarProductosBodega').val()
            filtroProductosBodega(filtro)
            $('select#productoBodega').focus()
        })

        $('#buscarProductosBodega').click(function(ev) {
            $('#infoProductos').html('')
            $('#anadirProductos').addClass('hide')
            $('#anadirVinos').addClass('hide')
            $('#buscarProductosCompra').val('')
            $('select#productoCompra').val(0)
            // $('#buscarProductosCompra').trigger('click')
            $(this).val('')
            filtroProductosBodega("")
        }).keydown(function(ev) {
            if (ev.which == 13 || ev.which == 9) {
                ev.preventDefault();
                var filtro = $(this).val()
                filtroProductosBodega(filtro)
                $(this).css('border', '1px solid #ccc')
                $(this).css('color', 'black')
                $('select#productoBodega').focus()
            }
        })

        function listadoProductos(codigoProducto) {
            var codigoBoka = codigoProducto.substr(5, 3)
            var preCodigo = codigoProducto.substr(0, 8)
            //    alert($(this).val()+' '+preCodigo)
            $('#infoProductos').html('')
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/stocks/getProductosCodigoPre",
                data: {
                    preCodigo: preCodigo
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    //  $('select#productoCompra').append('<option value="0">'+'Seleccionar producto compra ajax'+'</option>')
                    $('#insertar').addClass('hide')
                    var salida = "";
                    if (datos) {
                        salida += "<h5>Productos catalogados actuales</h5>"
                        salida += "<table class='table table-hover '>"
                        salida += "<thead>"
                        salida += "<tr><th class = 'text-left'>Código 13</th>"
                        salida += "<th class = 'text-left'>Boka</th>"
                        // salida += "<th class = 'text-left'>Nombre genérico</th>"
                        salida += "<th class = 'text-left'>Nombre</th>"
                        salida += "<th>Peso</th>"
                        salida += "<th>Unidad</th>"
                        salida += "<th>Precio Compra (€/und)</th>"
                        salida += "<th>Tarifa Venta (€/und)</th>"
                        salida += "</tr>"
                        salida += "</thead>"
                        salida += "<tbody>"
                    }
                    $.each(datos, function(index, value) {
                        var sub=""
                        var fsub=""
                        if(value['statusProducto']==1) {
                            salida += "<tr>"   
                        }
                        else {
                            salida += "<tr style='color:lightgrey'>" 
                            sub="<del>"
                            fsub="</del>"
                        }
                        salida += "<td class = 'text-left'>"+sub + value['codigoBoka']+fsub + "</td>"
                        salida += "<td class = 'text-left'>"+sub + value['codigoProducto']+fsub + "</del></td>"
                        salida += "<td class = 'text-left'>"+sub + value['nombre']+fsub + "</td>"
                        salida += "<td>"+sub + value['pesoReal']+fsub + "</td>"
                        salida += "<td>"+sub + value['tipoUnidad']+fsub + "</td>"
                        salida += "<td>"+sub + value['precioCompra']+fsub + "</td>"
                        salida += "<td>"+sub + value['tarifaVenta']+fsub + "</td>"
                        salida += "</tr>"   
                    })
                    salida += "</tbody>"
                    salida += "</table>"
                    $('#infoProductos').append(salida)
                    $('#anadirProductos').removeClass('hide')
                    $('#boka').html(datos[0]['codigoProducto'].substr(5, 3))
                    var nombreGenerico = datos[0]['nombreGenerico'] ? datos[0]['nombreGenerico'] : datos[0]['nombre'].replace('COMPRA', '').replace('Compra', '')
                    var iva=(datos[0]['iva']/1000).toFixed(2)

                    $('#iva').val(iva)
                    $('#nombreGenerico').val(nombreGenerico)
                    $('input#peso').val('')
                    $('#precioCompra').val('')
                    $('#tarifaVenta').val('')
                    $('#nombre').val('')
                    $('#codigoProducto').val('')
                    $('input#peso').focus()

                    $('input#peso').keyup(function() {
                        var peso = $(this).val()
                        var precioCompra = (datos[0]['precioCompra'] * peso).toFixed(3)
                        var tarifaVenta = (Number((datos[0]['tarifaVenta'] * peso).toFixed(2))).toFixed(3)
                        var pesoEnCodigo = (peso * 1000).toFixed(0)
                        while (pesoEnCodigo.length < 5) pesoEnCodigo = '0' + pesoEnCodigo
                        var codigoProductoOriginal = datos[0]['codigoProducto']
                        var codigoProducto = datos[0]['codigoProducto'].substr(0, 8) + pesoEnCodigo
                        $('#precioCompra').val(precioCompra)
                        $('#tarifaVenta').val(tarifaVenta)
                        $('#beneficio').html(beneficioProducto(tarifaVenta,precioCompra,iva))
                        $('#codigoProducto').val(codigoProducto)
                        $('#codigoProductoOriginal').html(codigoProductoOriginal)
                        $('#nombre').val(nombreGenerico.trim() + ' (' + Number(peso).toFixed(3) + ' Kg)')
                        $('#insertar').removeClass('hide')
                    })
                },
                error: function() {
                    alert("Error en el proceso. Informar");
                }
            })
        }

        function listadoVinos(codigoProducto) {
            var codigoBoka = codigoProducto.substr(5, 3)
            var preCodigo = codigoProducto.substr(0, 10)
            // console.log(preCodigo)
            $('#infoProductos').html('')
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/stocks/getProductosCodigoBod",
                data: {
                    preCodigo: preCodigo
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos);
                    //  $('select#productoCompra').append('<option value="0">'+'Seleccionar producto compra ajax'+'</option>')
                    $('#insertar').addClass('hide')
                    var salida = "";
                    if (datos) {
                        salida += "<h5>Productos catalogados actuales</h5>"
                        salida += "<table class='table table-hover '>"
                        salida += "<thead>"
                        salida += "<tr><th class = 'text-left'>Código 13</th>"
                        salida += "<th class = 'text-left'>Boka</th>"
                        // salida += "<th class = 'text-left'>Nombre genérico</th>"
                        salida += "<th class = 'text-left'>Nombre</th>"
                        salida += "<th>Añada</th>"
                        salida += "<th>Unidad</th>"
                        salida += "<th>Precio Compra (€/und)</th>"
                        salida += "<th>Tarifa Venta (€/und)</th>"
                        salida += "</tr>"
                        salida += "</thead>"
                        salida += "<tbody>"
                    }
                    $.each(datos, function(index, value) {
                        var sub=""
                        var fsub=""
                        if(value['statusProducto']==1) {
                            salida += "<tr>"   
                        }
                        else {
                            salida += "<tr style='color:lightgrey'>" 
                            sub="<del>"
                            fsub="</del>"
                        }
                        salida += "<td class = 'text-left'>"+sub + value['codigoProducto']+fsub + "</td>"
                        salida += "<td class = 'text-left'>"+sub + value['codigoBoka']+fsub + "</td>"
                        salida += "<td class = 'text-left'>"+sub + value['nombre']+fsub + "</td>"
                        salida += "<td>"+sub + value['anada']+fsub + "</td>"
                        salida += "<td>"+sub + value['tipoUnidad']+fsub + "</td>"
                        salida += "<td>"+sub + value['precioCompra']+fsub + "</td>"
                        salida += "<td>"+sub + value['tarifaVenta']+fsub + "</td>"
                        salida += "</tr>"
                    })
                    salida += "</tbody>"
                    salida += "</table>"
                    $('#infoProductos').append(salida)
                    // alert(datos[0]['codigoProducto'])
                    $('#anadirVinos').removeClass('hide')
                    $('#bokaVino').html(datos[0]['codigoProducto'].substr(5, 3))
                    $('#precioCompraVino').val(datos[0]['precioCompra'])
                    $('#tarifaVentaVino').val(datos[0]['tarifaVenta'])
                    var precioCompra=$('#precioCompraVino').val()
                    var tarifaVenta=$('#tarifaVentaVino').val()
                    var nombreGenerico = datos[0]['nombreGenerico'] ? datos[0]['nombreGenerico'] : datos[0]['nombre'].replace('COMPRA', '').replace('Compra', '')
                    var iva=(datos[0]['iva']/1000).toFixed(2)
                    $('#beneficioVino').html(beneficioProducto(tarifaVenta,precioCompra,iva))


                    $('#ivaVino').val(iva)
                    $('#nombreGenericoVino').val(nombreGenerico)
                    $('input#anada').val('')
                    // $('#precioCompraVino').val('')
                    // $('#tarifaVentaVino').val('')
                    $('#nombreVino').val('')
                    $('#codigoProductoVino').val('')
                    $('input#anada').focus()

                    $('input#anada').keyup(function() {
                        var anada = $(this).val()
                        // var precioCompra = (datos[0]['precioCompra']).toFixed(3)
                        // var tarifaVenta = (Number((datos[0]['tarifaVenta']).toFixed(2))).toFixed(3)
                        // var pesoEnCodigo = (peso * 1000).toFixed(0)
                        // while (pesoEnCodigo.length < 5) pesoEnCodigo = '0' + pesoEnCodigo
                        var codigoProductoOriginal = datos[0]['codigoProducto']
                        var codigoProducto = datos[0]['codigoProducto'].substr(0, 11) + anada.substr(2,2)
                        // $('#precioCompraVino').val()
                        // $('#tarifaVentaVino').val(tarifaVenta)
                        // $('#beneficioVino').html(beneficioProducto(tarifaVenta,precioCompra,iva))
                        $('#codigoProductoVino').val(codigoProducto)
                        $('#codigoProductoOriginalVino').html(codigoProductoOriginal)
                        $('#nombreVino').val(nombreGenerico.trim() + ' (' + anada + ')')
                        $('#insertarVino').removeClass('hide')
                    })
                },
                error: function() {
                    alert("Error en el proceso. Informar");
                }
            })
        }

        function beneficioProducto(tarifaVenta,precioCompra,iva){
            var tarifaVenta=Number(tarifaVenta)
            var precioCompra=Number(precioCompra)
            var iva=Number(iva)
            // alert(tarifaVenta)
            // alert(precioCompra)
            // alert(iva)
            if (!precioCompra) return 0;
            var beneficio=(100*tarifaVenta-precioCompra*(100+iva))/tarifaVenta;
            return beneficio.toFixed(2);
        }

        $('#precioCompra, #tarifaVenta').keyup(function(){
            var tarifaVenta=$('#tarifaVenta').val()
            var precioCompra=$('#precioCompra').val()
            var iva=$('#iva').val()
            $('#beneficio').html(beneficioProducto(tarifaVenta,precioCompra,iva))
        })

        $('#productoCompra').change(function() {
            if ($(this).val() == 0) return;
            var codigoProducto = $(this).val()
            listadoProductos(codigoProducto)
        })

        $('#productoBodega').change(function() {
            if ($(this).val() == 0) return;
            var codigoProducto = $(this).val()
            listadoVinos(codigoProducto)
        })

        $('#insertar').click(function() {
            var codigoProductoOriginal = $('#codigoProductoOriginal').html()
            var codigoProducto = $('#codigoProducto').val()
            var idProducto = $('#boka').html()
            var nombre = $('#nombre').val()
            var nombreGenerico = $('#nombreGenerico').val()
            var pesoReal = (Number($('#peso').val()) * 1000).toFixed(0)
            var precioCompra = (Number($('#precioCompra').val()) * 1000).toFixed(0)
            var tarifaVenta = (Number($('#tarifaVenta').val()) * 1000).toFixed(0)
            var beneficioProducto = (Number($('#beneficio').val()) * 1000).toFixed(0)
            var iva = (Number($('#iva').val()) * 1000).toFixed(0)

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/insertProductosPeso",
                data: {
                    codigoProductoOriginal: codigoProductoOriginal,
                    codigoProducto: codigoProducto,
                    idProducto: idProducto,
                    nombre: nombre,
                    nombreGenerico: nombreGenerico,
                    pesoReal: pesoReal,
                    precioCompra: precioCompra,
                    tarifaVenta: tarifaVenta,
                    beneficioProducto:beneficioProducto,
                    iva:iva
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos)
                    // alert('sql: '+datos.sql)
                    if (datos.error) {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html(datos.titulo)
                        $('.modal-body>p').html(datos.textoError)
                        $("#myModal").modal()
                    } else {
                        $('#myModal').css('color', 'black')
                        $('.modal-title').html(datos.titulo)
                        $('.modal-body>p').html("Se ha creado el producto correctamente<br>")
                        $("#myModal").modal()
                        listadoProductos(codigoProductoOriginal)
                    }

                },
                error: function() {
                    alert("Error en el proceso de insertar. Informar");
                }
            })

        })

        $('#insertarVino').click(function() {
            var codigoProductoOriginal = $('#codigoProductoOriginalVino').html()
            var codigoProducto = $('#codigoProductoVino').val()
            var idProducto = $('#bokaVino').html()
            var nombre = $('#nombreVino').val()
            var nombreGenerico = $('#nombreGenericoVino').val()
            var anada=$('#anada').val()
            if(anada.length!=4){
                $('#myModal').css('color','red')    
                $('.modal-title').html('Información')
                $('.modal-body>p').html('La añada debe tener cuatro cifras (yyyy)')
                $("#myModal").modal()
                return  
            }
            // var pesoReal = (Number($('#peso').val()) * 1000).toFixed(0)
            var precioCompra = (Number($('#precioCompraVino').val()) * 1000).toFixed(0)
            var tarifaVenta = (Number($('#tarifaVentaVino').val()) * 1000).toFixed(0)
            var beneficioProducto = (Number($('#beneficioVino').val()) * 1000).toFixed(0)
            var iva = (Number($('#iva').val()) * 1000).toFixed(0)

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/insertProductosPeso",
                data: {
                    codigoProductoOriginal: codigoProductoOriginal,
                    codigoProducto: codigoProducto,
                    idProducto: idProducto,
                    nombre: nombre,
                    nombreGenerico: nombreGenerico,
                    anada: anada,
                    precioCompra: precioCompra,
                    tarifaVenta: tarifaVenta,
                    beneficioProducto:beneficioProducto,
                    iva:iva
                },
                success: function(datos) {
                    // alert(datos)
                    var datos = $.parseJSON(datos)
                    // alert('sql: '+datos.sql)
                    if (datos.error) {
                        $('#myModal').css('color', 'red')
                        $('.modal-title').html(datos.titulo)
                        $('.modal-body>p').html(datos.textoError)
                        $("#myModal").modal()
                    } else {
                        $('#myModal').css('color', 'black')
                        $('.modal-title').html(datos.titulo)
                        $('.modal-body>p').html("Se ha creado el producto correctamente<br>")
                        $("#myModal").modal()
                        listadoVinos(codigoProductoOriginal)
                    }

                },
                error: function() {
                    alert("Error en el proceso de insertar. Informar");
                }
            })

        })

    })
</script>