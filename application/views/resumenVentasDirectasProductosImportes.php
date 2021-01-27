<input type="hidden" value="<?php echo $desde ?>" id="desde">
<input type="hidden" value="<?php echo $hasta ?>" id="hasta">
<input type="hidden" id="agrupar" value="<?php echo $agrupar ?>">

<div class="container">
    <h3 class="inline titulo">PRODUCTOS VENTAS DIRECTAS EN EL PERIODO</h3>
    <a href="#" class="btn btn-default inline pull-right" id="bajarExcel">
        <span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> 
        Exportar</a>
</div>

<?php echo  '<span class="titulo">'. 'Desde Venta Directa núm: '.$desde.'</span><br />';
        echo '<span class="titulo">'. 'Hasta Venta Directa núm: '.$hasta.'</span><br />';
         ?>
<hr>
<table class="table table-striped" >
    <thead>
        <tr id="encabezado">
            <th style="text-align:center">Código</th>
            <th class="izquierda" data-halign="left">Nombre</th>
            <th data-halign="right">Pedidos</th>
            <th data-halign="right">Unidades</th>
            <th data-halign="right">Importe (€)</th>

        </tr>
    </thead>
    
    <tbody>
        <?php 
                        foreach($productos as $k=>$v){ ?>
                        <tr id="linea">
                            <td data-halign="right"><a class="datosCodigo"><?php echo $v['codigo'] ?></a></td>
                            <td class="izquierda" data-halign="left"><?php echo $v['nombre'] ?></td>
                            <td data-halign="right"><?php echo $v['pedidos'] ?></td>
                            <td data-halign="right"><?php echo $v['unidades'] ?></td>
                            
                            <td data-halign="right"><?php echo formato2decimales(($v['importe'])/100); ?></td>
                           
                        </tr>
                        <?php } ?>
    </tbody> 
    <tfoot>
                        <tr id="pie">
                            <th class="sumas" data-halign="right"><?php echo count($productos) ?></th>
                            <th class="izquierda" data-halign="left"></th>
                            <th class="sumas" data-halign="right"><?php echo ($productosTotales['pedidos']) ?></th>
                            <th class="sumas" data-halign="right"><?php echo ($productosTotales['unidades']) ?></th>
                            <th class="sumas" data-halign="right"><?php echo formato2decimales($productosTotales['importe']/100) ?></th>
                           
                           </tr>
                        </tfoot>
</table>



<br/>
   <?php echo form_open('listados/seleccionVentasProductosVD',array('role'=>'form')) ?>
   <input style="display: inline;" type="submit" class="btn btn-primary btn-mini" value="Otra Selección" >
 <br/><br/> 

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

 </style>
<script>
// control menú navegación    
$(document).ready(function(){
   $('.datosCodigo').click(function(){
        var codigo13=$(this).html().toString()
       
        $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/stocks/datosCodigo13/"+codigo13, 
        data: {
               },
        success:function(datos){
           //alert(datos)
          var datos=$.parseJSON(datos)  
          //alert(datos['imagen'])
          $('#myModal').css('color','black')
          $('.modal-title').html('Información Stocks')
          var informacion=codigo13+' '+datos['nombre']
          informacion+='<br><img  id="imagen" height="150" width="150" src="'+datos['imagen']+'" alt="No existe imagen">'
          informacion+= '<br>Stock total actual ('+hoyEuropeo()+') <br>'+'<strong>'+ datos['cantidad']+' '+datos['tipoUnidad']+'</strong>'
          $('.modal-body>p').html(informacion)
          $("#myModal").modal()  
         },
        error: function(){

     }
    })
    }) 
    
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
    
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>"+"index.php/listados/bajarExcelProductosTickets", 
        data: {cabecera:$('#cabecera').html(),
                titulos:titulos,
                encabezados:encabezados,
                pies:pies,
                lineas:lineas,
                listado:'ventaDirecta',
                desde:$('#desde').val(),
                hasta:$('#hasta').val(),
                agrupar:$('#agrupar').val(),
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

