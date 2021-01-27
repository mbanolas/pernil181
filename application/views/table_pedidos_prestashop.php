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
    text-align: center;
}
.table thead tr th:nth-child(3){
    text-align: center;
} 
.table thead tr th:nth-child(4){
    text-align: center;
} 
.table thead tr th:nth-child(5){
    text-align: right;
} 
.table thead tr th:nth-child(6){
    text-align: right;
} 
.table thead tr th:nth-child(7){
    text-align: right;
} 
.table thead tr th:nth-child(8){
    text-align: right;
} 
.table thead tr th:nth-child(16){
    text-align: left;
} 

.table tbody tr td:nth-child(3){
    text-align: center;
}
.table tbody tr td:nth-child(4){
    text-align: center;
}
.table tbody tr td:nth-child(5){
    text-align: right;
}
.table tbody tr td:nth-child(6){
    text-align: right;
}
.table tbody tr td:nth-child(7){
    text-align: right;
     font-weight: bold;
}
.table tbody tr td:nth-child(8){
    text-align: right;
    font-weight: bold;
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
    font-weight: bold;
}
/*   
.modal-content_{
    width:1100px;
    left:-250px;
    
}
*/
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

.btn{
    padding: 5px 12px;
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
#rangoFechas{
    margin-right:5px;
    margin-top:6px;
}
#gcrud-search-form > table > tfoot > tr:nth-child(1) > th{
    padding:0px;
}
body > div > div.row > div > div > div > div.row > div.table-section > div.table-label{
    border: 5px solid blue;
    background-color: lightblue;
}
#gcrud-search-form > table > tfoot > tr:nth-child(2){
    border: 5px solid blue;
    background-color: lightblue;
}
#numOrders{
    text-align: center;
}
#total_producto{
    color:blue;
}
#transporte{
    text-decoration: underline;
}


    
</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $tituloRango=isset($tituloRango)?$tituloRango:'Pedidos Prestashop' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
    <input type="hidden" id="tituloRango" value="<?php echo $tituloRango ?>">
         
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    

    
 


    <script>
        
$(document).ready(function(){

    <?php if(strpos($tituloRango, "Pedido Prestashop núm") ===0) { ?>
        $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row').addClass('hide')
    <?php } ?>

    //calcula totales pie al cargar página
    totalesPie() 

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

    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container')

    //anchura mínima fecha
    $('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child(2)').css('min-width','160px')
    
    //elimina boton Eliminar filtros del pie
    $('#gcrud-search-form > div.footer-tools > div.floatR.r5 > div > button').addClass('hide')

    function calculoBase(total,iva){
        iva=iva/100
        return total/(100+iva)
    }

    //poner en título el rango
    var tituloRango=$('#tituloRango').val()
    $('body > div > div.row > div > div > div > div.row > div.table-section > div.table-label > div.floatL.l5').text(tituloRango)


    //boton para seleccionar nuevo rango fechas
    $('<button type="button" id="rangoFechas" class="btn btn-default">Seleccionar rango fechas</button>').insertBefore( ".mi-clear-filtering" );
    

    
    <?php if(strpos($tituloRango, "Pedido Prestashop núm") !==0) { ?>
        //pie con totales rango seleccionado
        console.log('pie con totales rango seleccionado')
        $('<tfoot ><tr><th></th></tr><tr ><th style="text-align: left; " colspan="2">Totales selección</th><th id="numOrders"></th><th ></th><th ></th><th id="total"></th><th id="descuento"></th><th id="total_producto">T</th><th id="total_base">T</th><th id="total_iva">T</th><th id="transporte">T</th><th id="base_transporte">T</th><th id="iva_transporte">T</th><th id="base_factura">T</th><th id="total_pedido">T</th><th></th></tr></tfoot>').insertAfter('tbody')
    <?php } ?>

    //selección nuevo rango fechas
    $('#rangoFechas').click(function(){
        var url="<?php echo base_url() ?>index.php/gestionTablas/pedidosPrestashopEntreFechas";
            window.location.href = url;
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

    $('.clear-filtering').click(function(){
        //console.log('paso por clear filteriong' )
        totalesPieSinFiltros() 
    })  

    function esFecha(fecha){
        var patt=/((?:19|20)\d{2})\-(1[012]|0?[1-9])\-(3[01]|[12][0-9]|0?[1-9])/i
        var result = fecha.match(patt);
        return result
    }
    //función que calcula 
    function totalesPie(){
           
            var inicio=$('.gc-export').attr('data-url').slice(-28,-18)
            var final=$('.gc-export').attr('data-url').slice(-17,-7)
            if(!esFecha(inicio)) inicio='2018-02-23';
            if(!esFecha(final)) final='3000-01-01';
            console.log(inicio);
            console.log(final);

            var sql="SELECT count(id) as num_orders, sum(total) as total, sum(descuento) as descuento, sum(total_producto) as total_producto, sum(total_base) as total_base, sum(total_iva) as total_iva, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte,sum(base_factura) as base_factura, sum(total_pedido) as total_pedido FROM pe_orders_prestashop WHERE fecha>='"+inicio+"' AND fecha<='"+final+" 23:59' "
            $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').each(function(){
                    var valor=buscar($(this).val());
                    if($(this).val()) sql=sql+" AND "+$(this).attr('name')+" LIKE '%"+valor+"%'"
            })
            //console.log(sql)
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/gestionTablas/pedidosPrestashopTotales", 
            data:{sql:sql},
            success:function(datos){
                //alert(datos)          
                var datos=$.parseJSON(datos);
                //alert(datos['total']) 
                $('#total').text(datos['total'])
                $('#numOrders').text(datos['num_orders'])
                $('#descuento').text(datos['descuento'])
                $('#total_producto').text(datos['total_producto'])
                $('#total_base').text(datos['total_base'])
                $('#total_iva').text(datos['total_iva'])
                $('#transporte').text(datos['transporte'])
                $('#base_transporte').text(datos['base_transporte'])
                $('#iva_transporte').text(datos['iva_transporte'])
                $('#base_factura').text(datos['base_factura'])
                $('#total_pedido').text(datos['total_pedido'])
            },
            error: function(){
                    alert("Error en el proceso cálculo totales. Informar");
            }
            })
        }
    
        function totalesPieSinFiltros(){
            var inicio=$('.gc-export').attr('data-url').slice(-28,-18)
            var final=$('.gc-export').attr('data-url').slice(-17,-7)
            var sql="SELECT count(id) as num_orders, sum(total) as total, sum(descuento) as descuento, sum(total_producto) as total_producto, sum(total_base) as total_base, sum(total_iva) as total_iva, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte, sum(base_factura) as base_factura,sum(total_pedido) as total_pedido FROM pe_orders_prestashop WHERE fecha>='"+inicio+"' AND fecha<='"+final+" 23:59' "
        
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/gestionTablas/pedidosPrestashopTotales", 
            data:{sql:sql},
            success:function(datos){
                //alert(datos)          
                var datos=$.parseJSON(datos);
                //alert(datos['total']) 
                $('#total').text(datos['total'])
                $('#numOrders').text(datos['num_orders'])
                $('#descuento').text(datos['descuento'])
                $('#total_producto').text(datos['total_producto'])
                $('#total_base').text(datos['total_base'])
                $('#total_iva').text(datos['total_iva'])
                $('#transporte').text(datos['transporte'])
                $('#base_transporte').text(datos['base_transporte'])
                $('#iva_transporte').text(datos['iva_transporte'])
                $('#base_factura').text(datos['base_factura'])
                $('#total_pedido').text(datos['total_pedido'])
            },
            error: function(){
                    alert("Error en el proceso. Informar");
            }
            })
        }


    //al poner nueva búsqueda, calcula el nuevo pie
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').keyup(function(){
        totalesPie()
    })
    
    //nuevo textp botón add
    $('#gcrud-search-form > div.header-tools > div.floatL.t5 > a').html('<i class="fa fa-plus"></i> &nbsp; Subir archivo PrestaShop')   
    //acción boton add 
    $('#gcrud-search-form > div.header-tools > div.floatL.t5 > a').click(function(e){
        e.preventDefault()
        $(location).attr("href", "<?php echo base_url() ?>"+"index.php/upload/do_upload_prestashop");
     })   
    
     //carga el script para preparar ventana modal detalle pedido prestashop
    $.ajax({
        url: "<?php echo base_url() ?>js/pedidoPrestashop.js",
        dataType: "script"
    });

    //muestra ventana con dados pedido    
    $('body').delegate('tr td> div> a[href*="pedidosPrestashop"]','click',function(e)  
        {    
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            var numTicket=id
           var tipoTienda=2
           //console.log('numTicket '+numTicket)
            $( this ).append( " <img class='loader' src='<?php echo base_url() ?>images/ajax-loader-2.gif' height='20' width='20'>" );
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/compras/getPedidoPrestashop", 
            data:{id:id},
            success:function(datos){
                detallePedido(datos)
            },
            error: function(){
                    alert("Error en el proceso. Informar");
            }
            })
        });
    

     $('.gc-export').click(function(){
         //console.log('pulsado gc.export')
         //console.log($('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(2) > input').val())
         //$('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(2) > input').val('02-01')
     })
    
    
     })
     <?php if(strpos($tituloRango, "Pedido Prestashop núm") !==false) { ?>
        console.log('Núm pedido')
        $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row').addClass('hide')
        $('#gcrud-search-form > table > tfoot > tr').addClass('hide')
    <?php } ?>
    
    
    </script>
    
      