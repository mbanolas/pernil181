<input type="hidden" id="listado" value="<?php echo 'totales' ?>">
<input type="hidden" value="<?php echo $inicio ?>" id="inicio">
<input type="hidden" value="<?php echo $final ?>" id="final">
<input type="hidden" value="<?php echo $desde ?>" id="desde">
<input type="hidden" value="<?php echo $hasta ?>" id="hasta">
<input type="hidden" id="agrupar" value="<?php echo $agrupar ?>">

<div class="container">
    <h3 class="inline titulo">PRODUCTOS VENTAS TOTALES EN EL PERIODO</h3>
    <a href="#" class="btn btn-default inline pull-right" id="bajarExcel">
        <span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> 
        Exportar</a>
</div>
<hr>
<table class="table" id="tablaProductos">
                        <thead>
                        <tr id="encabezado">
                            <th data-halign="right">Código</th>
                            <th class="izquierda" data-halign="left">Nombre</th>
                            <th data-halign="right">Partidas</th>
                            <th data-halign="right">Peso (Kg)</th>
                            <th data-halign="right">Importe (€)</th>
                            <!--
                            <th data-halign="right">Base (€)</th>
                            <th data-halign="right">IVA (€)</th>
                            <th data-halign="right">Tipo IVA (%)</th>
                            -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        foreach ($productos as $k => $v) {
                            /*
                            $importe=formato2decimales(($v['importe'])/100);
                            $iva=formato2decimales($v['iva']/100);
                           
                            $base=formato2decimales(($v['base'])/100);
                            $ivaPorcentaje=($v['ivaPorcentaje'])/100;
                            if($base!=0){
                                 $tipo=formato2decimales($ivaPorcentaje);
                             }else $tipo='---';
                             * 
                             */
                            $peso=0;
                            if(isset($v['peso']))
                                $peso=formato3decimales($v['peso']/1000);  
                            $peso=$peso==0?" ":$peso; 
                            
                            ?>
                        <tr id="linea">
                            <td data-halign="right"><?php echo $v['codigo'] ?></td>
                            <td class="izquierda" data-halign="left"><?php echo $v['nombre'] ?></td>
                            <td data-halign="right"><?php echo $v['unidades'] ?></td>
                            
                            <td data-halign="right"><?php echo $peso ?></td>
                            <td data-halign="right"><?php echo formato2decimales(($v['importe'])/100); ?></td>
                           
                        </tr>
                        
                        <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr id="pie">
                            <th class="sumas" data-halign="right"><?php echo count($productos) ?></th>
                            <th class="izquierda" data-halign="left"></th>
                            <th class="sumas" data-halign="right"><?php echo ($productosTotales['unidades']) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato3decimales($productosTotales['peso']/1000) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato2decimales($productosTotales['importe']/100) ?></th>
                           
                           </tr>
                        </tfoot>
                    </table>



<br/>
   <?php echo form_open('listados/seleccionVentasProductosTotales',array('role'=>'form')) ?>
   <input style="display: inline;" type="submit" class="btn btn-primary btn-mini" value="Otra Selección" >
 <br/><br/> 


<script>
// control menú navegación    
$(document).ready(function(){
    
    
  $('#bajarExcel').click(function(){
      
     
    //copiamos información página y creamos excel  
    var encabezados=[]
    var pies=[]
    var lineas=[]
   
    $('tr#encabezado').children('th').each(function(){
        encabezados.push($(this).html())
    })
    $('tr#pie').children('th').each(function(){
        pies.push($(this).html())
    })
    $('tr#linea').children('td').each(function(){
        lineas.push($(this).html())
    })
     if (lineas.length==0) lineas.push('')
    
     var titulos=[]
    $( ".titulo" ).each(function( index ) {
        titulos.push($(this).html())
        });
    
     var listado= $('#listado').val()  
      
      
    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/listados/bajarExcelProductosTickets", 
        data: { listado:listado,
                cabecera:$('#cabecera').html(), 
                titulos:titulos,
                encabezados:encabezados,
                pies:pies,
                desde:$('#desde').val(),
                hasta:$('#hasta').val(),
                inicio:$('#inicio').val(),
                final:$('#final').val(),
                agrupar:$('#agrupar').val(),
                //lineas:lineas,
               
              
               },
        success:function(datos){
           // alert(datos);
           var datos=$.parseJSON(datos)  
           var direccion="<?php echo base_url() ?>"+datos
                        window.open(direccion )
                        window.location.reload();
        },
        error: function(){

     }

                   })
  
    
    
})
})
</script>

