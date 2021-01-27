
<?php //var_dump($results2) ?>
<!--
<input type="hidden" value="<?php echo $inicio ?>" id="inicio">
<input type="hidden" value="<?php echo $final ?>" id="final">
<input type="hidden" id="agrupar" value="<?php //echo $agrupar ?>">
<input type="hidden" id="listado" value="<?php echo 'tienda' ?>">
-->
<input type="hidden" id="listado" value="<?php echo 'tienda' ?>">
<?php
$inicio=date('d/m/Y',  strtotime($inicio));
$final=date('d/m/Y',  strtotime($final));
echo "<h3 id='cabecera'>RESUMEN PRODUCTOS VENTAS TIENDA del $inicio al $final</h3>";
//var_dump($results);

?>
<a href="#" class="btn btn-default inline pull-right" id="bajarExcel">
        <span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> 
        Exportar Excel</a>
<div class="container">
    

    

</div>

<?php echo '<span class="titulo">'.$periodoBalanzaTodas.'</span><br />'.
        '<span class="titulo">'.$periodoBalanza1.'</span><br />'.
        '<span class="titulo">'.$periodoBalanza2.'</span><br />'.
        '<span class="titulo">'.$periodoBalanza3.'</span><br />'.
        '<span class="titulo">'.$periodoManuales.'</span><br />'
        ; ?>


<table class="table table-striped" id="tablaProductos">
                        <thead>
                        <tr id="encabezado">
                            <th data-halign="right">Código 13</th>
                            <th class="izquierda" data-halign="left">Nombre</th>
                            <th data-halign="right">Partidas</th>
                            <th data-halign="right">Peso (Kg)</th>
                            <th data-halign="right">Importe (€)</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalUnidades=0;
                        $totalPesos=0;
                        $totalImportes=0;
                        foreach ($productos['lineasProducto'] as $k => $v) {
                            $importe=formato2decimales(($v['importe'])/100);
                            //$iva=formato2decimales($v['iva']/100);
                           
                            //$base=formato2decimales(($v['base'])/100);
                            //$ivaPorcentaje=($v['ivaPorcentaje'])/100;
                            //if($base!=0){
                            //     $tipo=formato2decimales($ivaPorcentaje);
                            // }else $tipo='---';
                            $peso=formato3decimales($v['peso']/1000);  
                            $peso=$peso==0?" ":$peso; 
                            $totalPesos+=$v['peso']/1000;
                            $totalUnidades+=$v['unidades'];
                            $totalImportes+=$v['importe']/100;
                            ?>
                        <tr class="linea">
                            <td data-halign="right"><?php echo $v['codigo_producto'] ?></td>
                            <td class="izquierda" data-halign="left"><?php echo $v['nombre'] ?></td>
                            <td data-halign="right"><?php echo $v['unidades'] ?></td>
                            
                            <td data-halign="right"><?php echo $peso ?></td>
                            <td data-halign="right"><?php echo formato2decimales(($v['importe'])/100); ?></td>
                           
                        </tr>
                        
                        <?php } ?>
                        </tbody>
                        
                        <tfoot>
                            
                        <tr id="pie">
                            <th class="sumas" data-halign="right"><?php //echo count($productos) ?></th>
                            <th class="izquierda" data-halign="left"></th>
                            <th class="sumas" data-halign="right"><?php echo ($productosTotales['unidades']) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato3decimales($productosTotales['peso']/1000) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato2decimales($productosTotales['importe']/100) ?></th>
                           
                           
                        </tfoot>
                    </table>
<br/>
<?php echo form_open('listados/seleccionVentasProductos',array('role'=>'form')) ?>
   <input style="display: inline;" type="submit" class="btn btn-primary btn-mini" value="Otra Selección" >
    



<br />
<br />

<style>
   
     
     h3,button { display:block; /* Pre-existing code */ }
h3.inline {
    display:inline;
}
button {
    text-align:center;
    color:red;
    display:inline;
}

#imagen{
    border:1px solid black;
    border-radius: 10px;
}
a{
    color: black;
}

 </style>
 
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 
<script>

$(document).ready(function(){
    
    var datosP=[];
   
    var totalUnidades;
    var codigoBascula;
    
    $('[data-dismiss]').click(function(){
        location.reload();
    })
    
    $('.datosCodigo').click(function(){
        return;
        var codigo13=$(this).html().toString()
       
        $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/stocks/datosCodigo13/"+codigo13, 
        data: {
               },
        success:function(datos){
           //alert(datos)
          var datos=$.parseJSON(datos)  
                    
                    google.charts.load('current', {'packages':['bar']});
                    datosP=[]
                    var pares=[]
                       pares.push('Mes')
                       pares.push('Total')
                       datosP.push(pares)
                   $.each(datos['datos']['und'], function(key, value){
                      var pares=[]
                      pares.push(value['fecha'])
                      pares.push(value['und']+value['undP']+value['undVD'])
                       datosP.push(pares)
                   })
                   //nombre=datos['datos']['nombre']
                   totalUnidades=0
                   totalUnidades+=datos['datos']['totalUnidadesT']
                   totalUnidades+=datos['datos']['totalUnidadesP']
                   totalUnidades+=datos['datos']['totalUnidadesVD']
                   codigoBascula=datos['codigo_bascula']
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);  
                
                    $('#columnchart_material').removeClass('hide');
          
          //alert(datos['imagen'])
          $('#myModal').css('color','black')
          $('.modal-title').html('Información Stocks')
          var informacion=codigo13+' '+datos['nombre']
          
          informacion+='<br><img  id="imagen" height="150" width="150" src="'+datos['imagen']+'" alt="No existe imagen">'
          informacion+= '<br>Stock total actual ('+hoyEuropeo()+') <br>'+'<strong>'+ datos['cantidad']+' '+datos['tipoUnidad']+'</strong>'
          //informacion+='<br>Código báscula tienda: '+datos['codigo_bascula']
          $('.modal-body>p').html(informacion)
          $('#myModal').modal({
                    backdrop: 'static',
                    keyboard: false
              })
         },
        error: function(){

     }
    })
    }) 
    
    function drawChart() {
        var data = google.visualization.arrayToDataTable(datosP);
        var options = {
            legend: {position: 'none'},
          chart: {
            title: 'Unidades vendidas últimos 12 meses: '+totalUnidades+' Unidades - Código Bascula: '+codigoBascula,
            subtitle: 'Ventas mensuales',
          }
        };
        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        //chart.draw(data, options);
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    
    function hoyEuropeo(){
        var today=new Date();
         var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        if(dd<10) {
            dd='0'+dd;
        } 
        if(mm<10) {
            mm='0'+mm;
        } 
        today = dd+'-'+mm+'-'+yyyy;
        return today;
        
    }
    
  $('#bajarExcel').click(function(){
      
    //copiamos información página y creamos excel  
    var encabezados=[]
    var pies=[]
    
    $('tr#encabezado').children('th').each(function(){
        encabezados.push($(this).html())
    })
    $('tr#pie').children('th').each(function(){
        pies.push($(this).html())
    })
     
    var titulos=[]
   $( ".titulo" ).each(function( index ) {
        titulos.push($(this).html())
        });
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/listados/bajarExcelProductosTickets", 
        data: {cabecera:$('#cabecera').html(), 
                titulos:titulos,
                encabezados:encabezados,
                pies:pies,
                desde:$('#desde').val(),
                hasta:$('#hasta').val(),
                inicio:$('#inicio').val(),
                final:$('#final').val(),
                agrupar:$('#agrupar').val(),
                listado:$('#listado').val(),
                //no se pueden pasar directamente las líneas porque max de php
                //por ello pasamos la info para recosntruir
                //lineas:lineas
               },
        success:function(datos){
            //alert(datos);
           var datos=$.parseJSON(datos)  
           var direccion="<?php echo base_url() ?>"+datos
                        window.open(direccion )
                        //window.location.reload();
        },
        error: function(){

     }

                   })
  
    
    
})
})
</script>

