<style>
    #grafico {
        margin-top: 18px;
    }
</style>

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(document).ready(function() {

        $('#fechaHasta').val(hoy())

        var datosP = [];


        $('input.searchable-input').keyup(function() {
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
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function(e) {
            $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function(ev) {
            nombreId = $(this).attr('id')
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border', '1px solid #ccc')
            if (nombreId == 'buscarProveedores')
                filtroProveedores(" ", 'proveedor')
            if (nombreId == 'buscarProductos')
                filtroProductos(" ", 'producto')

        });

        $('#buscar').click(function(e) {
            e.preventDefault()
            var filtro = $('#buscarProductos').val()
            filtroProductos(filtro, 'producto')
            $('#buscarProductos').css('color', 'black')
        })

        //filtrado productosFinales 
        $('#buscarProductos').click(function() {
            var filtro = $('#buscarProductos').val()
            filtroProductos(filtro, 'producto')
            $('#buscarProductos').css('color', 'black')
            //filtroProductos("",'producto')
        })

        function filtroProductos(filtro, id) {
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/stocks/getProductosFiltro",
                data: {
                    filtro: filtro,
                    id: id
                },
                success: function(datos) {
                    //alert(datos)
                    var datos = $.parseJSON(datos);
                    $('select#' + id + ' option').remove();
                    $('#' + id).append('<option value="0">' + 'Seleccionar un producto' + '</option>')
                    $.each(datos['productos'], function(index, value) {
                        var id_pe_producto = value['id']
                        var nombre = value['nombre']
                        var codigo_producto = value['codigo_producto']
                        var option = '<option value="' + id_pe_producto + '">' + nombre + ' (' + codigo_producto + ')' + '</option>'
                        $('#' + id).append(option)
                    })
                },
                error: function() {
                    alert("Error en el proceso. Informar");
                }
            })
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

            return yyyy + '-' + mm + '-' + dd
        }

        $('#grafico').click(function() {
            var id_pe_producto = $('#producto').val()
            // var num=$('#numUltimos').val()
            var fechaDesde = $('#fechaDesde').val()
            var fechaHasta = $('#fechaHasta').val()
            if (!fechaDesde) fechaDesde = '2015-05-05'
            if (!fechaHasta) fechaHasta = hoy()

            if ('<?php echo strtolower($this->session->username) != 'pernilall'; ?>') {
                $("#myModalError").css('color','blue')
            if(fechaDesde < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Desde debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
            if(fechaHasta < '<?php echo date('Y')-1 ?>'){
              $('.modal-title').html('Información')
              $('.modal-body>p').html("<h3>La fecha Hasta debe ser igual o posterior a <strong> 01/01/<?php echo date('Y')-1 ?></strong></h3>")
              $("#myModalError").modal({
              })
              return false
            }
            }





            if (id_pe_producto == 0) {
                $('#myModal').css('color', 'red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Debe seleccionar un producto')
                $("#myModal").modal({
                    backdrop: "static",
                    keyboard: "false"
                })
                return false
            }
            /*    
            if(num==0){
                    $('#myModal').css('color','red')
                    $('.modal-title').html('Información')
                    $('.modal-body>p').html('Debe indicar número últimas ventas')
                    $("#myModal").modal({backdrop:"static",keyboard:"false"})
                    return false
                } 
                */
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/estadisticas/getDatosVentas",
                data: {
                    id_pe_producto: id_pe_producto,
                    fechaDesde: fechaDesde,
                    fechaHasta: fechaHasta
                },
                success: function(datos) {
                    // alert (datos)


                    var datosN = $.parseJSON(datos)
                    //  var datosP=[];

                    //   var datosN = JSON.parse(datos);
                    datosP = []
                    $.each(datosN['pvp'], function(key, value) {
                        var pares = []
                        pares.push(value['fecha'])
                        pares.push(value['pvp'])
                        datosP.push(pares)
                    })
                    var nombre = datosN['nombre']
                    // alert(nombre)
                    // alert(datosP)
                    options = {
                        'title': nombre,
                        'chartArea': {
                            'width': '76%',
                            'height': '90%'
                        },
                    };
                    options2 = {
                        'title': nombre,
                        'chartArea': {
                            left: 64
                        },
                    };

                    // Load the Visualization API and the corechart package.
                    google.charts.load('current', {
                        'packages': ['corechart']
                    });

                    // Set a callback to run when the Google Visualization API is loaded.
                    google.charts.setOnLoadCallback(drawChart);

                    // Load the Visualization API and the corechart package.
                    google.charts.load('current', {
                        'packages': ['corechart']
                    });

                    // Set a callback to run when the Google Visualization API is loaded.
                    google.charts.setOnLoadCallback(drawChart2);

                },
                error: function() {
                    alertaError("Información importante", "Error en la obtención de datos. Informar");
                }
            })
        })

        var options = {};
        var options2 = {};


        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Fecha');
            data.addColumn('number', 'PVP €');
            data.addRows(datosP);

            // Set chart options

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            //  var chart2 = new google.visualization.BarChart(document.getElementById('chart_div2'));
            chart.draw(data, options);
            //  chart2.draw(data, options);
        }

        function drawChart2() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Fecha');
            data.addColumn('number', 'PVP €');
            data.addRows(datosP);

            // Set chart options

            // Instantiate and draw our chart, passing in some options.
            //  var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            var chart2 = new google.visualization.LineChart(document.getElementById('chart_div2'));
            //   chart.draw(data, options);
            chart2.draw(data, options2);
        }


    })
</script>

<div class="container">
    <h3>Evolución PVP (ventas en tienda)</h3>
    <div class="row">
        <div class="col-sm-2">
            <label class="col-sm-12 form-control-label">Filtro productos </label>
            <div class="input-group">
                <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term">
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscar"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <label for="producto" class="col-sm-12 form-control-label">Producto</label>
            <?php echo form_dropdown('producto', $productosArray, '', array('width' => '100%', 'id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>

        <?php if (strtolower($this->session->username) != 'pernilall') { ?>
            <div class="col-sm-2">
                <label for="fechaDesde" class="col-sm-12 form-control-label">Desde</label>
                <input type="date" id="fechaDesde" min="<?php echo date('Y') - 1 ?>-01-01" max="<?php echo date('Y-m-d') ?>" class="form-control input-sm" placeholder="Desde" name="fechaDesde" value="<?php echo date('Y') - 1 ?>-01-01">
            </div>
            <div class="col-sm-2">
                <label for="fechaHasta" class="col-sm-12 form-control-label">Hasta</label>
                <input type="date" id="fechaHasta" min="<?php echo date('Y') - 1 ?>-01-01" max="<?php echo date('Y-m-d') ?>" class="form-control input-sm" placeholder="Hasta" name="fechaHasta" value="">
            </div>
        <?php } else { ?>
            <div class="col-sm-2">
                <label for="fechaDesde" class="col-sm-12 form-control-label">Desde</label>
                <input type="date" id="fechaDesde" min="2014-01-01" max="<?php echo date('Y-m-d') ?>" class="form-control input-sm" placeholder="Desde" name="fechaDesde" value="2014-02-20">
            </div>
            <div class="col-sm-2">
                <label for="fechaHasta" class="col-sm-12 form-control-label">Hasta</label>
                <input type="date" id="fechaHasta" min="2014-01-01" max="<?php echo date('Y-m-d') ?>" class="form-control input-sm" placeholder="Hasta" name="fechaHasta" value="">
            </div>
        <?php }  ?>




        <div class="col-sm-2">
            <label for="numUltimos" class="col-sm-12 form-control-label"></label>

            <button class="btn btn-default btn-sm" id="grafico">Mostrar Gráfico</button>
        </div>
    </div>
    <!--Div that will hold the pie chart-->
    <div id="chart_div" style="width: 900px; height: 1500px; margin-top:0px; margin-bottom: 0px;padding-top: 0px; padding-top:-100px;"></div>
    <hr>
    <div id="chart_div2" style="width: 1300px; height: 500px;margin-top: 0px; margin-bottom: 0px;padding-top: 0px; padding-top: 0px;"></div>
</div>