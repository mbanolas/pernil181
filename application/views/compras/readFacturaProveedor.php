<h3>Facturas proveedor registradas</h3>
<?php 
$optionsFacturas=$facturas['options'];
  ?>      
<form action="" method="POST">  
    <div class="box box-primary col-lg-12">  
        <br /><br />
        <div class="row">
            <div class="box-body_ col-lg-6">  
                <?php echo form_dropdown('factura', $optionsFacturas, '', array('id' => 'factura', 'class' => 'form-control')); ?>
            </div>    
            <div class="box-body_ col-lg-1">  
                <button type="button" class="btn btn-success" id="readFactura" >Ver factura</button>
            </div> 
            <div class="box-body_ col-lg-2">  
                <button type="button" class="btn btn-danger" id="deleteFactura" >Eliminar factura</button>
            </div> 
        </div>
    </div>
        
        <div id="datosFactura" class="hide">
            <div class="col-lg-4">
                <br />  
            <table class="table table-bordered_ table-hover_ ">
                <tbody>
                    <tr >
                        <th class="izda">Factura núm:</th>
                        <td class="izda" id="numFactura">fggfgggg
                    </tr>
                    <tr>
                        <th class="izda">Proveedor:</th>
                        <td class="izda" id="nombre">fggfgggg
                    </tr>
                    <tr>
                        <th class="izda">Fecha:</th>
                        <td class="izda" id="fecha">fggfgggg
                    </tr>
                </tbody>
            </table>
                </div>
           
            
            <table class="table table-bordered_ table-hover table-striped">
                        
                        <thead>  
                            <th>No</th>  
                            <th class="izda">Producto</th>  
                            <th>Cantidad</th>  
                            <th>Precio</th>  
                            <th>Descuento</th>  
                            <th>Total</th>  
                        </thead>  
                        <tbody class="detail">  
                      
</tbody>  

  
</table>  
            <div class="col-lg-4">
                <br />  
            <table class="table table-bordered_ table-hover ">
                <tbody>
                    <tr >
                        <th class="izda">Otros costes:</th>
                        <td class="izda_" id="otrosCostes"></td>
                    </tr>
                    <tr>
                        <th class="izda">Total Factura:</th>
                        <td class="izda_" ><span ><h4 id="totalFactura"></h4></span></td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
        </div>
        
        <br />
</form>  

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuCompras').addClass('btn-primary');
  $('#menuFacturaProveedorVer').addClass('btn-primary');  
})
</script>


<script>
$(document).ready(function () {
       
    $('.volver_').click(function(e){
        document.location.reload(true);
    })
    
    $('select#factura').click(function(){
        $('#datosFactura').addClass('hide')
    })   
    
    $('#deleteFactura').click(function(){
        var id_factura=$('#factura').val()
        
        if(id_factura==0) { alerta('Información','Seleccione una factura') }
        else{
             $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
                
                $('#confirm-delete').css('color','black')
                $('.modal-title').html('Eliminar Factura')
                $('.modal-body>p').html('¿Desea eliminar la factura '+datos['numFactura']+' de<br />'+datos['nombre']+' de <br />Importe: '+datos['totalFactura']+'?')
                $("#confirm-delete").modal('show')  
                
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
            })
            
        }
    })
    
    //borrando factura
    $('.btn-ok_').click(function(){
        var id_factura=$('#factura').val()
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/borrarFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos);
               // alert('Factura borrada correctamente.')
           // document.location.reload(true);
           
           $('#myModalVolver').css('color','blue')
              $('.modal-title').html('Información')
            $('.modal-body>p').html('Factura borrada correctamente.')
            $("#myModalVolver").modal() 
                
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
            })
    })
    
    $('#readFactura').click(function(){
        var id_factura=$('#factura').val()
        if(id_factura==0) { alerta('Información','Seleccione una factura') }
        else{
           //alerta('Información','Factura seleccionada '+id_factura) 
           $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosFactura", 
            data: {id_factura:id_factura },
            success: function(datos){
                //alert(datos)
                
                var datos=$.parseJSON(datos);
                $('#numFactura').html(datos['numFactura'])
                $('#nombre').html(datos['nombre'])
                $('#fecha').html(datos['fecha'])
                $('#otrosCostes').html(datos['otrosCostes'])
                $('#totalFactura').html(datos['totalFactura'])
                var body=""
                var lineas=datos['lineas']
                var sumaTotales=0
                for(var i=0; i<lineas.length;i++){
                    sumaTotales+=parseFloat(lineas[i]['total'])
                body+="<tr><td>"+(i+1)+"</td>"+
                           "<td class='izda'>"+lineas[i]['nombre']+"</td>"+
                           "<td class=''>"+lineas[i]['cantidad']+"</td>"+
                           "<td class=''>"+lineas[i]['precio']+"</td>"+
                           "<td class=''>"+lineas[i]['descuento']+"</td>"+
                           "<td class=''>"+lineas[i]['total']+"</td>"+
                           "</tr>"
                }
                body+="<tr><td></td>"+
                           "<td class='izda'></td>"+
                           "<td class=''></td>"+
                           "<td class=''></td>"+
                           "<td class=''></td>"+
                           "<td class=''><strong>"+sumaTotales.toFixed(2)+"</strong></td>"+
                           "</tr>"
                $('.detail').html(body)
                $('#datosFactura').removeClass('hide')
                },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        }
    })
    
   
})





</script>

<style type="text/css">
    .table-bordered > tbody > tr > td.separacion{
        border-left: 1px solid white;
        border-right: 1px solid white;
        color:white;
    }
    .separacionTitulo{
        border: 1px solid white;
        text-align: left;
        
    }
    .izda{
       text-align: left; 
    }
    
    .table > tbody > tr > td{
        vertical-align:middle;
        border-top:0px;
    }  
    .table > tbody > tr > th{
        border-top:0px;
    }
    
</style>

 

