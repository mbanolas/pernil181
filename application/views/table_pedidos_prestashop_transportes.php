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
    /* font-weight: bold;*/
}
.table tbody tr td:nth-child(8){
    text-align: right;
  /*  font-weight: bold;*/
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

    
    //calcula totales pie al cargar página
    totalesPie() 
    
    //carga el script para preparar ventana modal detalle pedido prestashop
    $.ajax({
        url: "<?php echo base_url() ?>js/pedidoPrestashop.js",
        dataType: "script"
    });

    $('body').delegate('a.pedido_prestashop','click',function(e)  
        {  
            //e.preventDefault()
            var id=$(this).html()
            var numTicket=id
           var tipoTienda=2
            //console.log(numTicket)
            
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
            
        }) 

    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container')

    //anchura mínima fecha
    $('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child(2)').css('min-width','160px')
    
    //elimina boton Eliminar filtros del pie
    $('#gcrud-search-form > div.footer-tools > div.floatR.r5 > div > button').addClass('hide')

    

    //poner en título el rango
    var tituloRango=$('#tituloRango').val()
    $('body > div > div.row > div > div > div > div.row > div.table-section > div.table-label > div.floatL.l5').text(tituloRango)

    <?php if(strpos($tituloRango, "Transportes Pedidos Prestashop") ===0) { ?>
        $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row').addClass('hide')
    <?php } ?>

    //boton para seleccionar nuevo rango fechas
    $('<button type="button" id="rangoFechas" class="btn btn-default">Seleccionar rango fechas</button>').insertBefore( ".mi-clear-filtering" );
    
    //pie con totales rango seleccionado
    <?php if(strpos($tituloRango, "Transportes Pedidos Prestashop") !==0) { ?>
        //pie con totales rango seleccionado
        console.log('pie con totales rango seleccionado')
        $('<tfoot ><tr><th></th></tr><tr ><th style="text-align: left; " colspan="2">Totales selección</th><th id="numOrders"></th><th ></th><th ></th><th id="peso_transporte">Peso</th><th id="bultos_transporte">Bultos</th><th ></th><th id="base_tarifa">BT</th><th id="base_suplementos">BS</th><th id="suple_dif">SD</th><th id="base_factura">T</th><th id="base_transporte"></th><th id="diferencia_base_transporte_base_factura">DIF</th><th ></th><th ></th><th ></th></tr></tfoot>').insertAfter('tbody')
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
            //console.log(inicio);
            //console.log(final);

            var sql="SELECT count(id) as num_orders, sum(total) as total, sum(descuento) as descuento, sum(total_producto) as total_producto, sum(suple_dif) as suple_dif, sum(total_base) as total_base, sum(total_iva) as total_iva, sum(transporte) as transporte, sum(peso_transporte) as peso_transporte,sum(bultos_transporte) as bultos_transporte, sum(base_transporte_segun_precios) as base_transporte_segun_precios,sum(base_transporte) as base_transporte,sum(diferencia_base_transporte_base_factura) as diferencia_base_transporte_base_factura,sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte,sum(base_factura) as base_factura, sum(total_pedido) as total_pedido,sum(base_tarifa) as base_tarifa,sum(base_suplementos) as base_suplementos FROM pe_orders_prestashop WHERE fecha>='"+inicio+"' AND fecha<='"+final+" 23:59' "
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
                $('#peso_transporte').text(datos['peso_transporte'])
                $('#bultos_transporte').text(datos['bultos_transporte'])
                $('#base_tarifa').text(datos['base_tarifa'])
                $('#base_suplementos').text(datos['base_suplementos'])
                $('#diferencia_base_transporte_base_factura').text(datos['diferencia_base_transporte_base_factura'])
                $('#suple_dif').text(datos['suple_dif'])

                
            },
            error: function(){
                    alert("Error en el proceso cálculo totales. Informar");
            }
            })
        }
    
        function totalesPieSinFiltros(){
            var inicio=$('.gc-export').attr('data-url').slice(-28,-18)
            var final=$('.gc-export').attr('data-url').slice(-17,-7)
            var sql="SELECT count(id) as num_orders, sum(total) as total, sum(descuento) as descuento, sum(total_producto) as total_producto, sum(total_base) as total_base,  sum(suple_dif) as suple_dif,sum(total_iva) as total_iva, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte, sum(base_factura) as base_factura,sum(total_pedido) as total_pedido,sum(peso_transporte) as peso_transporte,sum(bultos_transporte) as bultos_transporte, sum(base_transporte_segun_precios) as base_transporte_segun_precios,sum(base_transporte) as base_transporte,sum(diferencia_base_transporte_base_factura) as diferencia_base_transporte_base_factura,sum(base_tarifa) as base_tarifa,sum(base_suplementos) as base_suplementos FROM pe_orders_prestashop WHERE fecha>='"+inicio+"' AND fecha<='"+final+" 23:59' "
        
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/gestionTablas/pedidosPrestashopTotales", 
            data:{sql:sql},
            success:function(datos){
                alert('totalesPieSinFiltros ' + datos)          
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
                $('#peso_transporte').text(datos['peso_transporte'])
                $('#bultos_transporte').text(datos['bultos_transporte'])
                $('#base_tarifa').text(datos['base_tarifa'])
                $('#base_suplementos').text(datos['base_suplementos'])
               $('#diferencia_base_factura_base_tarifa').text(datos['diferencia_base_factura_base_tarifa'])
                $('#suple_dif').text(datos['suple_dif'])


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
    
    //muestra ventana con dados pedido    
    $('body').delegate('tr td> div> a[href*="pedidosPrestashop_"]','click',function(e)  
        {    
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            var numTicket=id
           var tipoTienda=2
           //console.log('numTicket '+numTicket)
           //console.log('tipoTienda '+tipoTienda)
            $( this ).append( " <img class='loader' src='<?php echo base_url() ?>images/ajax-loader-2.gif' height='20' width='20'>" );
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/compras/getPedidoPrestashop", 
            data:{id:id},
            success:function(datos){
                $(".loader").remove();
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
                var factor=0
                if(totalPrecio!=0) {   
                    factor=1+descuento/totalPrecio
                }
                //console.log('descuento '+descuento)
                //console.log('totalPrecio '+totalPrecio)
                //console.log('factor '+factor)

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
                    //console.log('precioIvas[index] '+precioIvas[index])
                    //console.log('factor '+factor)
                    totalIvaIncluido+=precioIvas[index]*factor
                    //console.log('totalIvaIncluido '+totalIvaIncluido)
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
                //console.log('totalIvaIncluido '+totalIvaIncluido)
                var totalFinal="<table class='totalFinal'><tr><td >Total: "+totalIvaIncluido.toFixed(2)+" €</td></tr></div>"

            $('.modal-title').html('Pedido Prestashop')
            // $('.modal-body').html(tabla+tabla2)
                $('.modal-body').html(cabecera+productos+ ivas+totalFinal)
                //$("#myModal").modal()
            //$("#myModalPedidosPrestashop").modal()
            $("#myModalPrestashop").modal()
            
            
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
    
    
    
    </script>
    
      