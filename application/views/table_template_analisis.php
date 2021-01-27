

<style>
    
    .ftitle{
    font-size: 20px;
   
}

.grocery-crud-table tbody tr td{
    text-align: left;
}

h4.modal-title{
    font-weight: bold;
}

.row {
    margin-right: -10px;
    margin-left: -10px;
}

.table-label div{
    font-weight: bold;
    font-size:24px;
}

.container{
    padding-left: 0px;
    padding-right: 0px;
}





.gc-container {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: auto;
    margin-left: auto;
}

td{
        text-align: left;
    }



.table thead tr th:nth-child(1){
    text-align: center;
}

.table thead tr th:nth-child(2){
    text-align: left;
}
.table thead tr th:nth-child(3){
    text-align: left;
} 
.table thead tr th:nth-child(4){
    text-align: left;
} 
.table thead tr th:nth-child(5){
    text-align: left;
} 
.table thead tr th:nth-child(6){
    text-align: left;
} 
.table thead tr th:nth-child(7){
    text-align: left;
} 
.table thead tr th:nth-child(8){
    text-align: left;
} 

.izda{
   text-align: left; 
}
.dcha{
   text-align: right;
}
.w1{
    padding-left: 30px;
}
.w2{
    min-width:100px; 
}
.cab th{
    
    padding: 10px 10px;
    border: 1px solid black;
    background-color: lightgray;
    text-align: center;
}
.linea td{
     border: 1px solid black;
     padding: 5px 10px 5px 10px;
}
.descuento{
    font-weight: bold;
}
.ivas th{
    padding-top:12px;
    padding-left:20px;  
}

.factura{
    
    
}

.tcab{
    width: auto;
    margin-right: auto;
    margin-left: 30px;
    
}

.tlineas{
   width: auto;
    margin-right: 30px;
    margin-left: auto;
}
.tivas{
   width: auto;
    margin-right: 30px;
    margin-left: auto;
}

.totalFinal{
    width: auto;
    margin-right: 30px;
    margin-left: auto;
    
    margin-top:10px;
    padding-top: 5px;
    padding-bottom: 5px;
    border: 1px solid black;
    
    font-size: 14px;
    font-weight: bold;
    
    height:30px;
}

.totalFinal td{
   
    width: 200px;
    text-align: center;
}
.table tbody tr td:nth-child(2){
    text-align: left;
} 
.table tbody tr td:nth-child(3){
    text-align: right;
    
} 
.table tbody tr td:nth-child(4){
    text-align: center;
} 
.table tbody tr td:nth-child(6){
    text-align: left;
} 
.table tbody tr td:nth-child(7){
    text-align: left;
    
} 
.table tbody tr td:nth-child(8){
    text-align: left;
} 
.table tbody tr td:nth-child(9){
    text-align: right;
    
} 
.table tbody tr td:nth-child(10){
    text-align: right;
} 
.table tbody tr td:nth-child(11){
    text-align: right;
} 
.table tbody tr td:nth-child(12){
    text-align: right;
} 
.table tbody tr td:nth-child(13){
    text-align: right;
} 
.table tbody tr td:nth-child(14){
    text-align: right;
} 
.table tbody tr td:nth-child(15){
    text-align: right;
   
} 
.table tbody tr td:nth-child(16){
    text-align: right;
    
   
} 
.table tbody tr td:nth-child(17){
    text-align: right;
} 
.table tbody tr td:nth-child(18){
    text-align: right;
    font-weight: bold;
}
.table tbody tr td:nth-child(19){
    text-align: right;
    font-weight: bold;
}
.table tbody tr td:nth-child(20){
    text-align: right;
    font-weight: bold;
}

#rangoFechas,#exportarExcel{
    margin-right:5px;
    margin-top:6px;
}
#gcrud-search-form > table > tfoot > tr:nth-child(1) > th{
    padding:0px;
}
.table>tbody>tr>td {
    padding-top: 0px;
    padding-bottom: 0px;
}
body > div > div.container-fluid > div > div > div > div > div.row > div.table-section > div.table-label{
    border: 5px solid blue;
    background-color: lightblue;
}
#gcrud-search-form > table > tfoot > tr:nth-child(2){
    border: 5px solid blue;
    background-color: lightblue;
}
#ingresado{
    color:blue;
}
#total_transporte{
    text-decoration: underline;
}
</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    if($tienda==2){
        $tituloRango=isset($tituloRango)?$tituloRango:'Pedidos Prestashop' ;
    }
    if($tienda==1){
        $tituloRango=isset($tituloRango)?$tituloRango:'Tickets tienda' ;
    }
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
    <input type="hidden" id="tituloRango" value="<?php echo $tituloRango ?>">
    <input type="hidden" id="tienda" value="<?php echo $tienda ?>">
    <input type="hidden" id="inicio" value="<?php echo $inicio ?>">
    <input type="hidden" id="finalDia" value="<?php echo $finalDia ?>">
         
   
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
        
$(document).ready(function(){

    // $('.gc-export').attr('data-url','')
    // $('.gc-export').removeClass('gc-export')
    // $('.gc-export').addClass('hide')

     //anchura mínima fecha
    $('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child(2)').css('min-width','150px')
    
    //num ticket anchura min
    $('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child(2)').css('min-width','80px')

    //elimina boton Eliminar filtros del pie
    $('#gcrud-search-form > div.footer-tools > div.floatR.r5 > div > button').addClass('hide')


    //oculta boton add (sin desconfigurar resto)
    $(".header-tools .floatL.t5").addClass('hide')

    //total ancho container
    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container')

    //enmarcado título
    //$('body > div > div.container-fluid > div > div > div > div > div.row > div.table-section > div.table-label').css('border','5px solid blue')
   
    <?php if(strpos($tituloRango, "Productos Ventas Prestashop Pedido") ===0) { ?>
        $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row').addClass('hide')
    <?php } ?>

    //pie totalizador
    if($('#tienda').val()==1)
        $('<tfoot style="margin-top:5px"><tr><th> </th></tr><tr ><th id="total_seleccion" style="text-align: left; " colspan="11">Totales selección</th><th id="cantidad"><th id="peso"></th><th id="pvp_neto"></th><th></th><th id="beneficio_producto"></th><th id="beneficio_producto_embalaje"></th><th id="ingresado"></th><th id="beneficio_absoluto"></th></tr></tfoot>').insertAfter('tbody')
    if($('#tienda').val()==2){
        <?php if(strpos($tituloRango, "Productos Ventas Prestashop Pedido") !==0) { ?>

        $('<tfoot style="margin-top:5px"><tr><th> </th></tr><tr ><th id="total_seleccion" style="text-align: left; " colspan="11">Totales selección</th><th id="cantidad"><th id="pvp_neto"></th><th></th><th id="transporte"></th><th id="beneficio_producto"></th><th id="beneficio_producto_embalaje"></th><th id="total_transporte"></th><th id="ingresado"></th><th id="beneficio_absoluto"></th></tr></tfoot>').insertAfter('tbody')
        <?php } ?>    
    }
    if($('#tienda').val()=="")
        $('<tfoot style="margin-top:5px"><tr><th> </th></tr><tr ><th id="total_seleccion" style="text-align: left; " colspan="9">Totales selección</th><th id="cantidad"><th id="peso"></th><th id="pvp_neto"></th><th></th><th id="transporte">T</th><th id="beneficio_producto">T</th><th id="beneficio_producto_embalaje">T</th><th id="beneficio_absoluto">T</th></tr></tfoot>').insertAfter('tbody')

    totalesPie()
    
    var tituloRango=$('#tituloRango').val()
    $('body > div > div.container-fluid > div > div > div > div > div.row > div.table-section > div.table-label > div.floatL.l5').text(tituloRango)
    
    //poner boton Seleccionar rango fechas
    $('<button type="button" id="rangoFechas" class="btn btn-default">Seleccionar rango fechas</button>').insertBefore( ".mi-clear-filtering" );
    // $('<button type="button" id="exportarExcel" class="btn btn-default"> <i class="fa fa-cloud-download floatL t3"></i><span class="hidden-xs floatR l5">Exportar Excel</span></button>').insertBefore( ".gc-print" );
    // $('<a href="<?= base_url() ?>index.php/ventas/exportarExcelTienda" type="button" id="exportarExcel" class="btn btn-default"> <i class="fa fa-cloud-download floatL t3"></i><span class="hidden-xs floatR l5">Exportar Excel</span></a>').insertBefore( ".gc-print" );
    
    //ir a seleccionar nuevo rango fechas
    $('#rangoFechas').click(function(){
        if(<?php echo $tienda; ?>==1){
            var url="<?php echo base_url() ?>index.php/gestionTablas/ticketsTiendaEntreFechas";
        }
        else {
            var url="<?php echo base_url() ?>index.php/gestionTablas/pedidosPrestashopEntreFechas";
        }
        window.location.href = url;
    })
    
    //oculta botones Buscar no procedentes
    $('input[name="precio_compra"]').addClass('hide')
    $('input[name="tarifa_venta"]').addClass('hide')
    $('input[name="precio_embalaje"]').addClass('hide')
    $('input[name="pvp_neto"]').addClass('hide')
    $('input[name="transporte"]').addClass('hide')
    $('input[name="beneficio_producto"]').addClass('hide')
    $('input[name="beneficio_producto_embalaje"]').addClass('hide')
    $('input[name="beneficio_producto_embalaje_transporte"]').addClass('hide')
    $('input[name="beneficio_absoluto"]').addClass('hide')
    $('input[name="cantidad"]').addClass('hide')
    //$('input[name="tipo_iva"]').addClass('hide')
    $('input[name="ingresado"]').addClass('hide')
    $('input[name="peso"]').addClass('hide')
    $('input[name="total_transporte"]').addClass('hide')
    
    //poniendo nombres campos selectores
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(2) > input').attr('campo','fecha_venta')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(3) > input').attr('campo','num_ticket')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(4) > input').attr('campo','num_cliente')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(5) > input').attr('campo','codigo_producto')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(6) > input').attr('campo','grupo')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(7) > input').attr('campo','familia')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(8) > input').attr('campo','producto')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(15) > input').attr('campo','tipo_iva')

    // $.ajax({
    //             type: "POST",
    //             url: "<?php echo base_url() ?>" + "index.php/ventas/leerVariablesTablaTienda",
                
    //             success: function(datos) {
    //                 var datos = $.parseJSON(datos)
    //                 console.log(datos)
    //                 $('input[campo="fecha_venta"]').val(datos['fecha_venta'])
    //                 $('input[campo="num_ticket"]').val(datos['num_ticket'])
    //                 $('input[campo="num_cliente"]').val(datos['num_cliente'])
    //                 $('input[campo="codigo_producto"]').val(datos['codigo_producto'])
    //                 $('input[campo="grupo"]').val(datos['grupo'])
    //                 $('input[campo="familia"]').val(datos['familia'])
    //                 $('input[campo="producto"]').val(datos['producto'])
    //                 $('input[campo="tipo_iva"]').val(datos['tipo_iva'])
    //             },
    //             error: function() {
    //                 alert("Información importante", "Error en el proceso grabado variablesTablaTienda . Informar");
    //             }
    //     })


    $('#exportarExcel_').click(function(){

            //titulares
            var titulares=[];
            for (let i = 2; i < 20; i++) {
            titulares.push($('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child('+i+')').html())
            }
            var jsonTitulares=JSON.stringify(titulares)
           

            var fecha_venta=$('input[campo="fecha_venta"]').val()
            var num_ticket=$('input[campo="num_ticket"]').val()
            var num_cliente=$('input[campo="num_cliente"]').val()
            var codigo_producto=$('input[campo="codigo_producto"]').val()
            var grupo=$('input[campo="grupo"]').val()
            var familia=$('input[campo="familia"]').val()
            var producto=$('input[campo="producto"]').val()
            var tipo_iva=$('input[campo="tipo_iva"]').val()
            var inicio=$('input#inicio').val()
            var tienda=$('input#tienda').val()
            
            var finalDia=$('input#finalDia').val()
            var tituloCabecera=$('body > div > div.container-fluid > div > div > div > div > div.row > div.table-section > div.table-label > div.floatL.l5').html()
            var tituloPie=$('#total_seleccion').html()

            console.log('num_ticket '+num_ticket)
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/ventasCI/exportarExcelTienda",
                data: {
                    'fecha_venta': fecha_venta,
                    'num_ticket': num_ticket,
                    'num_cliente': num_cliente,
                    'codigo_producto': codigo_producto,
                    'grupo': grupo,
                    'familia': familia,
                    'producto': producto,
                    'tipo_iva': tipo_iva,
                    'inicio':inicio,
                    'finalDia':finalDia,
                    'tituloCabecera':tituloCabecera,
                    'tituloPie':tituloPie,
                    'tienda':tienda,
                    'titulares':jsonTitulares
                },
                success: function(datos) {
                    // console.log(datos)
                    // var datos = $.parseJSON(datos)
                },
                error: function() {
                    alert("Información importante: Error en el proceso exportarExcelTienda . Informar");
                }
        })



    })

    $('.searchable-input').change(function(e) {
            console.log($(this).val())
            var fecha_venta=$('input[campo="fecha_venta"]').val()
            var num_ticket=$('input[campo="num_ticket"]').val()
            var num_cliente=$('input[campo="num_cliente"]').val()
            var codigo_producto=$('input[campo="codigo_producto"]').val()
            var grupo=$('input[campo="grupo"]').val()
            var familia=$('input[campo="familia"]').val()
            var producto=$('input[campo="producto"]').val()
            var tipo_iva=$('input[campo="tipo_iva"]').val()
            // console.log(fecha_venta)
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/ventas/variablesTablaTienda",
                data: {
                    'fecha_venta': fecha_venta,
                    'num_ticket': num_ticket,
                    'num_cliente': num_cliente,
                    'codigo_producto': codigo_producto,
                    'grupo': grupo,
                    'familia': familia,
                    'producto': producto,
                    'tipo_iva': tipo_iva
                },
                success: function(datos) {
                    // console.log(datos)
                    // var datos = $.parseJSON(datos)
                },
                error: function() {
                    alert("Información importante", "Error en el proceso grabado variablesTablaTienda . Informar");
                }
        })
    })


    function calculoBase(total,iva){
        iva=iva/100
        return total/(100+iva)
    }
    
    

    $('body').delegate('a.cliente_tienda','click',function(e)  
        {  
            //e.preventDefault()
            var numCliente=$(this).html()
            $.ajax({
                type:'POST',
                url: "<?php echo base_url() ?>"+"index.php/clientes/getDatosCliente/"+numCliente, 
                data:{},
                success: function(datos){
                      //alert(datos)
                        var valores=$.parseJSON(datos);
                        //alert(valores['empresa']) 
                        $('.loader').remove()
                        $('#myModal').css('color','black')
                        $('.modal-title').html('Cliente núm '+numCliente)
                        var salidaBody="Empresa: <strong>"+valores['empresa']+'</strong><br>'
                        salidaBody+="Nombre: <strong>"+valores['nombre']+'</strong><br>'
                        salidaBody+="Direccion: <strong>"+valores['direccion']+'</strong><br>'
                        salidaBody+="Poblacion: <strong>"+valores['codigoPostal']+' '+valores['poblacion']+'</strong><br>'
                        salidaBody+="Provincia: <strong>"+valores['provincia']+'</strong><br>'
                        salidaBody+="Email: <strong>"+valores['correo1']+'</strong><br>'
                        $('.modal-body').html(salidaBody)
                        $("#myModal").modal() 
                        //$("#myModalPrestashop").hide()
                        return
                    },
                error: function(){
                        alert("Error en el proceso Ver ticket. Informar");
                }
            }) 
        })    
        
        $('#myModal').on('show.bs.modal', function () {
            console.log('show.bs.modal')
            $(this).find('#myModal').css({
                color:'red',
              width:'200%', //probably not needed
              height:'auto', //probably not needed 
              'max-height':'100%'
       });
});


        $('body').delegate('a.cliente_prestashop','click',function(e)  
        {  
            //e.preventDefault()
            var numCliente=$(this).html()
            console.log(numCliente)
            $.ajax({
                type:'POST',
                url: "<?php echo base_url() ?>"+"index.php/clientes/getDatosClientePrestashop/"+numCliente, 
                data:{},
                success: function(datos){
                      //alert(datos)
                        var valores=$.parseJSON(datos);
                        //alert(valores['empresa']) 
                        $('.loader').remove()
                        $('#myModal').css('color','black')
                        $('.modal-title').html('Cliente núm '+numCliente)
                        var salidaBody="<table>"
                        salidaBody="<tr><td>Company: </td><td style='padding-left:10px'><strong>"+valores['company']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Firstname: </td><td style='padding-left:10px'><strong>"+valores['firstname']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Lastname: </td><td style='padding-left:10px'><strong>"+valores['lastname']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Email: </td><td style='padding-left:10px'><strong>"+valores['email']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Address_postcode: </td><td style='padding-left:10px'><strong>"+valores['address_postcode']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Address_city: </td><td style='padding-left:10px'><strong>"+valores['address_city']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Address_phone: </td><td style='padding-left:10px'><strong>"+valores['address_phone']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Address_phone_mobile: </td><td style='padding-left:10px'><strong>"+valores['address_phone_mobile']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Language: </td><td style='padding-left:10px'><strong>"+valores['id_lang']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Shop_name: </td><td style='padding-left:10px'><strong>"+valores['shop_name']+'</strong></td></tr>'
                        salidaBody+="<tr><td>Country: </td><td style='padding-left:10px'><strong>"+valores['country']+'</strong></td></tr>'
                        salidaBody+="</table>"
                        $('.modal-body').html(salidaBody)
                        $("#myModal").modal() 
                        //$("#myModalPrestashop").hide()
                        return
                    },
                error: function(){
                        alert("Error en el proceso Ver ticket. Informar");
                }
            }) 
        }) 
        
    
    
        $('body').delegate('tr td> div> a[href*="2/read"]','click',function(e)  
        {  
            //alert($(this).attr('href'))
            e.preventDefault()
           
           // var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
           var numTicket=$(this).parent().parent().next().next().html().trim();//.parent().parent().$('td:nth-child(2)').html()
           var tipoTienda=$(this).parent().parent().next().next().next().html().trim();//.parent().parent().$('td:nth-child(2)').html()
           //console.log('numTicket '+numTicket)
           //console.log('tipoTienda '+tipoTienda)
           $( this ).append( " <img class='loader' src='<?php echo base_url() ?>images/ajax-loader-2.gif' height='20' width='20'>" );

        if(true){
            
                $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/compras/getPedidoPrestashop", 
            data:{id:numTicket},
            success:function(datos){
                $('.loader').remove()
                var datos=$.parseJSON(datos);
                var total=((datos['total']/100)-(datos['descuento']/100)).toFixed(2);
                var totalBase=((datos['total_base']/100)).toFixed(2);
                var totalIva=((datos['total_iva']/100)).toFixed(2);
                var intercomunitario=datos['tipoIvaTransporte']==0?true:false; //ver de forma indirecta si es intercomunitario
                var fecha=datos['fecha'].substr(8,2)+'/'+datos['fecha'].substr(5,2)+'/'+datos['fecha'].substr(0,4)
               
                var cabecera="<div class='factura' ><table class='tcab'>"
                cabecera+="<tr><th class='izda w2'>Pedido</th><td class='izda'>"+datos['pedido']+"</td></tr>"
                cabecera+="<tr><th class='izda w2'>Tipo cliente</th><td class='izda'>"+datos['tipoCliente']+"</td></tr>" 
                cabecera+="<tr><th class='izda w2'>Cliente</th><td class='izda'>"+datos['num_cliente']+" "+datos['firstname']+" "+datos['lastname']+" "+datos['country']+"</td></tr>" 

                cabecera+="<tr><th class='izda w2'>Fecha</th><td izda='dcha w1'>"+fecha+"</td></tr>" 
                cabecera+="</table><p>"
                
                var productos="<table class='tlineas'>"
                if(!intercomunitario)
                    productos+="<tr class='cab' ><th>Referencia</th><th>Descripción</th><th>V</th><th>Base price</th><th>Qty</th><th>Total</th><th>VAT Type</th><th>VAT Amount</th><th>Total</th></tr>"
                else 
                    productos+="<tr class='cab' ><th>Referencia</th><th>Descripción</th><th>V</th><th>Base price</th><th>Qty</th><th>Total</th></tr>"
                var infoIvas=[];
            
                var precioIvas=[];
                var totalPrecio=0;
                var anulado=false
                
                for(var i=0;i<datos['lineas'].length;i++){
                    if(datos['lineas'][i]['valid']==-1) 
                            anulado=true 
                    if(datos['lineas'][i]['esPack']==1)
                            productos+="<tr style='color:blue' class='linea'>"
                        else
                            productos+="<tr class='linea'>"    
                    productos+="<td>"+datos['lineas'][i]['codigo_producto']+"</td>" 
                    
                    var nombre=datos['lineas'][i]['nombre'];
                    if(datos['lineas'][i]['esPack']==1)
                            nombre=nombre+" ("+datos['lineas'][i]['cantidad']+" Packs)"
                    if(datos['lineas'][i]['esComponentePack']==1) 
                            nombre="---- "+nombre    
                    
                    productos+="<td>"+nombre+"</td><td>"+datos['lineas'][i]['valid']+"</td>" 
                    //var base=(datos['lineas'][i]['importe']/100).toFixed(2)-(datos['lineas'][i]['iva']/100).toFixed(2);
                    var precio=datos['lineas'][i]['precio']*datos['lineas'][i]['cantidad']/100
                    //alert(datos['lineas'][i]['precio'])
                    //alert(datos['lineas'][i]['tipoIva'])
                    var basePrecio=calculoBase(datos['lineas'][i]['precio'],datos['lineas'][i]['tipoIva']);
                    basePrecio=Math.round(basePrecio*100)/100
                    var baseTotal=basePrecio*datos['lineas'][i]['cantidad']
                    
                    var tipoIva=datos['lineas'][i]['tipoIva']/100
                    infoIvas[tipoIva]=tipoIva
                    
                    var varAmount=precio-baseTotal
                    varAmount=Math.round(varAmount*100,2)/100
                    
                    if(isNaN(precioIvas[tipoIva])) precioIvas[tipoIva]=0;
                    precioIvas[tipoIva]+=precio
                    if(datos['lineas'][i]['esPack']==0){
                        productos+="<td class='dcha'>"+basePrecio+" €</td>"
                        productos+="<td class='dcha'>"+datos['lineas'][i]['cantidad']+"</td>"
                        if(!intercomunitario){
                            productos+="<td class='dcha'>"+baseTotal.toFixed(2)+"</td>"
                            productos+="<td class='dcha'>"+tipoIva.toFixed(2)+"%</td>"
                            productos+="<td class='dcha'>"+varAmount.toFixed(2)+" €</td>"
                        }
                        productos+="<td class='dcha'>"+precio.toFixed(2)+" €</td>"
                        
                        totalPrecio+=precio
                    }
                    else 
                    if(!intercomunitario){
                            productos+="<td></td><td></td><td></td><td></td><td></td><td></td>"
                        }
                        else
                            productos+="<td></td><td></td><td></td>"

                    productos+="</tr>"
                }
                var descuento=Number(datos['descuento'])/1
                var transporte=Number(datos['transporte'])/10
                
            
                if(descuento==transporte){
                    descuento=0
                    transporte=0
                    }   
                if(descuento!=0){
                    descuento=-descuento/100
                    if(datos['tipoCliente']!=9)
                            productos+="<tr class='linea'><td></td><td class='izda descuento'>Descuento</td><td></td><td class='dcha'>1</td><td></td><td></td><td></td><td></td><td class='dcha'>"+descuento.toFixed(2)+" €</td></tr>"
                    else
                        productos+="<tr class='linea'><td></td><td class='izda descuento'>Descuento</td><td></td><td ></td><td class='dcha'>1</td><td class='dcha'>"+descuento.toFixed(2)+" €</td></tr>"
                    }
                productos+="</table>"
                
                var factor=1+descuento/totalPrecio
                var ivas="<table class='tivas table-responsive'>"
                if(intercomunitario)
                    ivas+="<tr class='ivas'><th class='dcha'>Detalle</th><th width='8'></th><th class='dcha'>Total</th><tr>"
                else
                    ivas+="<tr class='ivas'><th class='dcha'>IVA</th><th class='dcha'>% IVA</th><th class='dcha'>Total sin IVA</th><th class='dcha'>Total IVA</th><th class='dcha'>Total IVA incluido</th><tr>"

                var totalSinIva=0
                var totalAmount=0
                var totalIvaIncluido=0

                infoIvas.forEach(function(value,index){
                    //if (value==0) return
                    //if (precioIvas[index]==0) return
                    var base=precioIvas[index]*factor/(1+index/100)
                    var amount=precioIvas[index]*factor-base
                    ivas+="<tr ><td class='dcha'>Productos</td>"
                    if(!intercomunitario){
                        ivas+="<td class='dcha'>"+value.toFixed(3)+"</td>"
                        ivas+="<td class='dcha'>"+base.toFixed(2)+" €</td>"
                        ivas+="<td class='dcha'>"+amount.toFixed(2)+" €</td>"
                    }
                    else{
                        ivas+="<td class='dcha'></td>" 
                    }
                    ivas+="<td class='dcha'>"+(precioIvas[index]*factor).toFixed(2)+" €</td></tr>"
                    totalSinIva+=base
                    totalAmount+=amount
                    totalIvaIncluido+=precioIvas[index]*factor
                })
                if(transporte!=0){
                    transporte=transporte/100
                    var index=Number(datos['tipoIvaTransporte']/1000)
                    //if(datos['tipoCliente']==9) index=0; //es un cliente intercomunitario que no paga iva.
                    var base=Number(datos['baseTransporte']/1000)
                    var amount=transporte-base
                    //alert(transporte)
                    //alert(base)
                    ivas+="<tr ><td class='dcha'>Transportista</td>"
                    if(!intercomunitario){
                        ivas+="<td class='dcha'>"+index.toFixed(3)+"</td>"
                        ivas+="<td class='dcha'>"+base.toFixed(2)+" €</td>"
                        ivas+="<td class='dcha'>"+amount.toFixed(2)+" €</td>"
                    }
                    else{
                        //ivas+="<tr ><td class='dcha'>Transportista</td>"
                        
                        ivas+="<td class='dcha'></td>"
                        
                    }
                    //alert(index)
                    /*
                    if(index){
                        ivas+="<td class='dcha'>"+index.toFixed(3)+"</td>"
                        ivas+="<td class='dcha'>"+base.toFixed(2)+" €</td>"
                        ivas+="<td class='dcha'>"+amount.toFixed(2)+" €</td>"
                    }
                    else{
                        alert(ivas)
                        ivas+="<td class='dcha'></td>" 
                    }
                    */
                    ivas+="<td class='dcha'>"+(transporte).toFixed(2)+" €</td></tr>"
                    totalSinIva+=base
                    totalAmount+=amount
                    totalIvaIncluido+=transporte
                    
                    }
                if(!intercomunitario){    
                    ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td><td></td><td></td></tr>"
                    ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+totalSinIva.toFixed(2)+" €</td><td class='dcha'>"+totalAmount.toFixed(2)+" €</td><td class='dcha'>"+totalIvaIncluido.toFixed(2)+" €</td></tr>"
                }  
                else{
                    ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td></tr>"
                    ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+totalIvaIncluido.toFixed(2)+" €</td></tr>"
                }
                ivas+="</table>"
                console.log(totalIvaIncluido)
                var totalFinal="<table class='totalFinal'><tr><td >Total: "+totalIvaIncluido.toFixed(2)+" €</td></tr></div>"

                $('.modal-title').html('Pedido Prestashop')
                // $('.modal-body').html(tabla+tabla2)
                $('.modal-body').html("")
                $('.modal-body').html(cabecera+productos+ ivas+totalFinal)
                $("#myModalPrestashop").modal()
                $("#myModalTicket").hide()  
                //$("#myModalPedidosPrestashop").modal()
                return
                
                
            },
            error: function(){
                    alert("Error en el proceso. Informar");
            }
        })
            
        }
    });

    $('body').delegate('tr td> div> a[href*="1/read"]','click',function(e)
    {
        e.preventDefault();
        var numTicket=$(this).parent().parent().next().next().html().trim();//.parent().parent().$('td:nth-child(2)').html()
        //alert(tipoTienda)
            var fecha=$(this).parent().parent().next().html().trim();//.parent().parent().$('td:nth-child(2)').html()
            fecha=fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2)
            $( this ).append( " <img class='loader' src='<?php echo base_url() ?>images/ajax-loader-2.gif' height='20' width='20'>" );

             //alert(fecha)
            //alert(numTicket)
            $.ajax({
                type:'POST',
                url: "<?php echo base_url() ?>"+"index.php/tickets/getDatosTicketNumFechaTickets", 
                data:{fecha:fecha, numTicket:numTicket},
                success: function(datos){
                    //  alert(datos)
                        var valores=$.parseJSON(datos);
                        $('.loader').remove()
                        $('#myModalTicket').css('color','black')
                        $('.modal-title').html('Ticket Num. '+valores[0])
                        $('.modal-body').html("")
                        $('.modal-body').html(valores[1])
                        $("#myModalTicket").modal() 
                        $("#myModalPrestashop").hide()
                        return
                    },
                error: function(){
                        alert("Error en el proceso Ver ticket. Informar");
                }
            }) 
        })      
    
        function buscar(dato){
            
            if(!isNaN(dato) || dato.indexOf('/')==-1) return dato
            var d=dato.trim()
            //console.log(d)
            var partes=[]
            while(d.indexOf('/')>0){
                var p=d.indexOf('/')
                partes.push(d.substring(0, p))
                d=d.substring(p+1)
            }
            partes.push(d)
            var fecha="";
            for(var i=partes.length-1;i>=0;i--){
                fecha=fecha+partes[i]+'-'
            }
            fecha=fecha.substring(0,fecha.length-1)
            //console.log(fecha)
            return fecha
        } 

        $('.mi-clear-filtering').click(function(){
            console.log('mi-clear-filtering')
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/ventas/variablesTablaTienda",
                data: {
                    'fecha_venta': "",
                    'num_ticket': "",
                    'num_cliente': "",
                    'codigo_producto': "",
                    'grupo': "",
                    'familia': "",
                    'producto': "",
                    'tipo_iva': ""
                },
                success: function(datos) {
                    // console.log(datos)
                    // var datos = $.parseJSON(datos)
                },
                error: function() {
                    alert("Información importante", "Error en el proceso grabado variablesTablaTienda . Informar");
                }
        })
        })

        $('a.clear-filtering').click(function(){
            console.log('clear filtering nuevo  ')
            //$('a.mi-clear-filtering').trigger('click')
            $('.fa-times').trigger('click')
            totalesPieSinFiltros()
            
        })

        function totalesPieSinFiltros(){
            var busqueda=false
            var inicio=$('.gc-export').attr('data-url').slice(-30,-20)
            
            var final=$('.gc-export').attr('data-url').slice(-19,-9)
            var finalDia=final+' 23:59';
            var tienda=$('#tienda').val()
            //console.log($('.gc-export').attr('data-url'))
            //console.log('tienda '+tienda)
            var sql="SELECT count(r.id) as lineas, sum(pvp_neto) as pvp_neto, sum(cantidad) as cantidad, sum(peso) as peso,sum(r.transporte) as transporte,sum(total_transporte) as total_transporte, avg(beneficio_producto) as beneficio_producto, avg(beneficio_producto_embalaje) as beneficio_producto_embalaje, avg(beneficio_producto_embalaje_transporte) as beneficio_producto_embalaje_transporte, sum(beneficio_absoluto) as beneficio_absoluto, sum(ingresado) as ingresado FROM pe_registro_ventas r ";
            var where=" WHERE tipo_tienda='"+tienda+"' AND fecha_venta>='"+inicio+"' AND fecha_venta<='"+finalDia+"' "
            
            
            sql+=where
            console.log(sql)
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/ventas/productosTotales", 
            data:{sql:sql},
            success:function(datos){
                //alert(datos)          
                var datos=$.parseJSON(datos);
                //alert(datos['total']) 
                //$('#pvp_neto').text(datos['pvp_neto'])
                $('#cantidad').text(datos['cantidad'])

                $('#total_seleccion').text('Total selección ('+datos['lineas']+(datos['lineas']>1?' líneas)':' línea)'))
                $('#peso').text(datos['peso'])
                //$('#transporte').text(datos['transporte'])
                $('#ingresado').text(datos['ingresado'])
                $('#total_transporte').text(datos['total_transporte'])
                //$('#beneficio_producto').text(datos['beneficio_producto'])
                //$('#beneficio_producto_embalaje').text(datos['beneficio_producto_embalaje'])
                //$('#beneficio_producto_embalaje_transporte').text(datos['beneficio_producto_embalaje_transporte'])
                $('#beneficio_absoluto').text(datos['beneficio_absoluto'])
                $('#total_seleccion').parent().css('background-color','lightblue')
                if(busqueda) $('#total_seleccion').parent().css('background-color','lightpink')
            },
            error: function(){
                    alert("Error en el proceso Total pie. Informar");
            }
            })
    }
        //función que calcula 
        function totalesPie(){
            var busqueda=false
            var inicio=$('.gc-export').attr('data-url').slice(-30,-20)
            
            var final=$('.gc-export').attr('data-url').slice(-19,-9)
            var finalDia=final+' 23:59';
            var tienda=$('#tienda').val()
            //console.log($('.gc-export').attr('data-url'))
            //console.log('tienda '+tienda)
            var sql="SELECT count(r.id) as lineas, sum(pvp_neto) as pvp_neto, sum(cantidad) as cantidad, sum(peso) as peso,sum(r.transporte) as transporte,sum(total_transporte) as total_transporte, avg(beneficio_producto) as beneficio_producto, avg(beneficio_producto_embalaje) as beneficio_producto_embalaje, avg(beneficio_producto_embalaje_transporte) as beneficio_producto_embalaje_transporte, sum(beneficio_absoluto) as beneficio_absoluto, sum(ingresado) as ingresado FROM pe_registro_ventas r ";
            var where=" WHERE tipo_tienda='"+tienda+"' AND fecha_venta>='"+inicio+"' AND fecha_venta<='"+finalDia+"' "
            
            $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').each(function(){
                    var valor=buscar($(this).val());
                    if(valor){
                        // console.log('busqueda '+valor)
                        busqueda=true
                        var campo=$(this).attr('placeholder')
                        //console.log(campo)
                        switch(campo){
                            case 'Buscar Código 13':
                                sql+=" LEFT JOIN pe_productos p ON p.id=r.id_pe_producto "
                                where+=" AND p.codigo_producto LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Producto':
                                sql+=" LEFT JOIN pe_productos p3 ON p3.id=r.id_pe_producto "
                                where+=" AND p3.nombre LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Grupo':
                                sql+=" LEFT JOIN pe_productos p1 ON p1.id=r.id_pe_producto "
                                sql+=" LEFT JOIN pe_grupos g ON g.id_grupo=p1.id_grupo "
                                where+=" AND g.nombre_grupo LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Familia':
                                sql+=" LEFT JOIN pe_productos p2 ON p2.id=r.id_pe_producto "
                                sql+=" LEFT JOIN pe_familias f ON f.id_familia=p2.id_familia "
                                where+=" AND f.nombre_familia LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Núm cliente':
                                if($('#tienda').val()==1)
                                    where+=" AND r.num_cliente LIKE '%"+valor+"%'"
                                if($('#tienda').val()==2){
                                    sql+=" LEFT JOIN pe_orders_prestashop op ON op.id=r.num_cliente "
                                    where+=" AND op.customer_id LIKE '%"+valor+"%'"
                                }
                            break 
                            case 'Buscar Núm pedido':
                                where+=" AND r.num_ticket LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Núm ticket':
                                where+=" AND r.num_ticket LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Fecha venta':
                                where+=" AND r.fecha_venta LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Und':
                                where+=" AND r.cantidad LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Tipo IVA (%)':
                                where+=" AND r.tipo_iva LIKE '%"+valor+"%'"
                            break 
                        }
                        
                    }
            })
            sql+=where
            // console.log(sql)
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/ventas/productosTotales", 
            data:{sql:sql},
            success:function(datos){
                //alert(datos)          
                var datos=$.parseJSON(datos);
                //alert(datos['total']) 
                //$('#pvp_neto').text(datos['pvp_neto'])
                $('#cantidad').text(datos['cantidad'])

                $('#total_seleccion').text('Total selección ('+datos['lineas']+(datos['lineas']>1?' líneas)':' línea)'))
                $('#peso').text(datos['peso'])
                //$('#transporte').text(datos['transporte'])
                $('#ingresado').text(datos['ingresado'])
                $('#total_transporte').text(datos['total_transporte'])
                //$('#beneficio_producto').text(datos['beneficio_producto'])
                //$('#beneficio_producto_embalaje').text(datos['beneficio_producto_embalaje'])
                //$('#beneficio_producto_embalaje_transporte').text(datos['beneficio_producto_embalaje_transporte'])
                $('#beneficio_absoluto').text(datos['beneficio_absoluto'])
                $('#total_seleccion').parent().css('background-color','lightblue')
                if(busqueda) $('#total_seleccion').parent().css('background-color','lightpink')
            },
            error: function(){
                    alert("Error en el proceso Total pie. Informar");
            }
            })
    }

    
    
    //al poner nueva búsqueda, calcula el nuevo pie
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').keyup(function(){
        totalesPie()
    })
    
    
     })
    
    
    
    </script>