<style>
    #grafico{
        margin-top:18px;
        
    }
    text {
        color:black;
        font-weight:bold;
    }
</style>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        
       $(document).ready(function () {
          
       $('#fechaHasta').val(hoy())
       
       var datosP=[];
       var datosPI=[];
       var nombre;
     
     
        
    $('input.searchable-input').keyup(function(){
    if($(this).val()){
        $(this).css('border-color','#444')
        $(this).css('border-style','dashed')
        $(this).css('color','red')
    }
    else{
        $(this).css('border','1px solid #ccc')  
        $(this).css('color','black')
    }
    }) 
    
     // CLEARABLE INPUT
        function tog(v){return v?'addClass':'removeClass';} 
        var nombreId
        
        $(document).on('input', '.clearable', function(){
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){
            nombreId=$(this).attr('id')
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc')  
            if(nombreId=='buscarProveedores')
                filtroProveedores(" ",'proveedor')
            if(nombreId=='buscarProductos')
                filtroProductos(" ",'producto')
            
        });
        
         $('#buscar').click(function(e){
        e.preventDefault()
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
        $('#buscarProductos').css('color','black')
    })
    
    //filtrado productosFinales 
    $('#buscarProductos').click(function(){
        var filtro=$('#buscarProductos').val()
        filtroProductos(filtro,'producto')
        $('#buscarProductos').css('color','black')
    //filtroProductos("",'producto')
    })
    
    function filtroProductos(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/stocks/getProductosFiltro", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
             $('select#'+id+' option').remove();
             $('#'+id).append('<option value="0">'+'Seleccionar un producto'+'</option>')
             $.each(datos['productos'], function(index, value){
                 var id_pe_producto=value['id']
                 var nombre =value['nombre']
                 var codigo_producto=value['codigo_producto']
                 var option='<option value="'+id_pe_producto+'">'+nombre+' ('+codigo_producto+')'+'</option>'
                 $('#'+id).append(option)
             })
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
   
    function hoy(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd='0'+dd
        } 

        if(mm<10) {
            mm='0'+mm
        } 

        return yyyy+'-'+mm+'-'+dd
    }
    
    $('#producto').change(function(){
        $('#columnchart_material').addClass('hide')
        $('#columnchart_materialI').addClass('hide')
        $('img.ajax-loader2').addClass('hide')
    })
     
    $('#grafico').click(function(){
        var id_pe_producto=$('#producto').val()
       // var num=$('#numUltimos').val()
       var fechaDesde=$('#fechaDesde').val()
       var fechaHasta=$('#fechaHasta').val()
       if(!fechaDesde) fechaDesde='2016-12-01'
      // if(fechaDesde<'2016-12-01') fechaDesde='2016-12-01'
       if(!fechaHasta) fechaHasta=hoy()
       if(fechaHasta>hoy()) fechaHasta=hoy()
       $('#fechaDesde').val(fechaDesde)
       $('#fechaHasta').val(fechaHasta)
       
        if(id_pe_producto==0){
                $('#myModal').css('color','red')
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Debe seleccionar un producto')
                $("#myModal").modal({backdrop:"static",keyboard:"false"})
                return false
            }  
            
           
        $('img.ajax-loader2').removeClass('hide')
        $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>"+"index.php/estadisticas/getCantidadesStocks",
                data: {id_pe_producto:id_pe_producto,fechaDesde:fechaDesde,fechaHasta:fechaHasta},
                success: function(datos){
                   // alert (datos)
                   $('#columnchart_material').removeClass('hide')
                   $('#columnchart_materialI').removeClass('hide')
                   $('img.ajax-loader2').addClass('hide')
                   
                    var datosN=$.parseJSON(datos)
                    nombre=datosN['nombre']
                    var stock_minimo=datosN['stock_minimo']/1000
                    datosP=[]
                    datosPI=[]
                    var pares=[]
                       pares.push('Dia')
                       pares.push('Stock')
                       pares.push('Stock mínimo')
                       //pares.push('pointsVisible')
                       datosP.push(pares)
                       
                       pares=[]
                       pares.push('Dia')
                       pares.push('Valor Stock €')
                       datosPI.push(pares)
                            
                   $.each(datosN['und'], function(key, value){
                        var pares=[]
                       pares.push(value['fecha'])
                       pares.push(value['und'])
                       pares.push(stock_minimo)
                       //pares.push(false)
                       datosP.push(pares)
                   })
                       
                       
                   $.each(datosN['importe'], function(key, value){
                        var pares=[]
                       pares.push(value['fecha'])
                       pares.push(value['importe'])
                       datosPI.push(pares)
                   })
                  // alert(datosP)
                   
                    
                   // $('#chart_div').removeClass('hide')
                 google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);  
                
                google.charts.setOnLoadCallback(drawChartI);  
                
                },
                error: function(){
                    alertaError("Información importante","Error en la obtención de datos. Informar");
                }
            }) 
    }) 
        
  var options = {}; 
  var options2 = {}; 


  function drawChart() {
        var data = google.visualization.arrayToDataTable(datosP);
        var options = {
           
            title: 'Producto: '+nombre,
            subtitle: 'Existenias, cantidades',
            //curveType: 'function',
            legend: { position: 'bottom' },
            pointsVisible: 'true',
       
       vAxis:{format:'decimal'} 
          
        };
        var chart = new google.visualization.LineChart(document.getElementById('columnchart_material'));
        chart.draw(data, options);
      }
      
  function drawChartI() {
        var data = google.visualization.arrayToDataTable(datosPI);
        var options2 = {
          
            title: 'Producto: '+nombre,
            subtitle: 'Existenias, valor compra actual',
            //curveType: 'function',
            legend: { position: 'bottom' },
        vAxis:{format:'decimal'} ,
        pointsVisible: 'true',
        
          
          
        };
        var chart = new google.visualization.LineChart(document.getElementById('columnchart_materialI'));
        chart.draw(data, options2);
        //chart.draw(data, options);
       // chart.draw(data, google.charts.Bar.convertOptions(options));
        
        
      }
       
      
     
        })
    </script>
        
    
    <div class="container">
        <h3>Evolución Stocks</h3>
        
        <div class="row">
          <div class="col-sm-2">
            <label  class="col-sm-12 form-control-label">Filtro productos </label>
            <div class="input-group">
                <input type="text" id="buscarProductos" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="buscar" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
         
        <div class="col-sm-4">
            <label for="producto" class="col-sm-12 form-control-label">Producto</label>
              <?php echo form_dropdown('producto', $productosArray, '', array('width'=>'100%','id' => 'producto', 'class' => ' form-control input-sm ')); ?>
        </div>
            
       <div class="col-sm-2">
            <label for="fechaDesde" class="col-sm-12 form-control-label">Desde</label>
            <input type="date" id="fechaDesde" class="form-control input-sm" placeholder="Desde" name="fechaDesde" value="2016-12-01" > 
       </div>  
       <div class="col-sm-2">
            <label for="fechaHasta" class="col-sm-12 form-control-label">Hasta</label>
            <input type="date" id="fechaHasta" class="form-control input-sm" placeholder="Hasta" name="fechaHasta" value=""> 
       </div>  
  
        <div class="col-sm-2">
                        <label for="numUltimos" class="col-sm-12 form-control-label"></label>

         <button class="btn btn-default btn-sm"  id="grafico" >Mostrar Gráfico</button>
    </div>
        </div>
       
        <div >
            <img class="img-responsive ajax-loader2 hide"   src="<?php echo base_url('images/ajax-loader.gif') ?>">      	

        </div>        
        <hr>
        
    <div  class="hide" id="columnchart_material" style="width: 1200px; height: 450px;"></div>
    
    <hr>
    <div  class="hide" id="columnchart_materialI" style="width: 1200px; height: 450px;"></div>
    
    <!--
    <div id="chart_div" style="width: 1300px; height: 500px; margin-top:0px; margin-bottom: 0px;padding-top: 0px; padding-top:-100px;"></div>
    <hr>
    
    <div class='hide' id="chart_div2" style="width: 1300px; height: 500px;margin-top: 0px; margin-bottom: 0px;padding-top: 0px; padding-top: 0px;"></div>
   -->
    </div>