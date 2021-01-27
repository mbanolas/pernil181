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
    font-weight: bold;
    
}
   
.modal-content{
    width:1100px;
    left:-250px;
    
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
#rangoFechas{
    margin-right:5px;
    margin-top:6px;
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

    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container')
    $('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child(2)').css('min-width','160px')
    
    function calculoBase(total,iva){
        iva=iva/100
        return total/(100+iva)
    }
    var tituloRango=$('#tituloRango').val()
    $('body > div > div.row > div > div > div > div.row > div.table-section > div.table-label > div.floatL.l5').text(tituloRango)
    $('<button type="button" id="rangoFechas" class="btn btn-default">Seleccionar rango fechas</button>').insertBefore( ".mi-clear-filtering" );
    
    $('<tfoot><tr style="border:5px solid blue;"><th style="text-align: left; " colspan="5">Totales selección</th><th id="total"></th><th id="descuento"></th><th id="total_producto">T</th><th id="total_base">T</th><th id="total_iva">T</th><th id="transporte">T</th><th id="base_transporte">T</th><th id="iva_transporte">T</th><th id="total_pedido">T</th></tr></tfoot>').insertAfter('tbody')

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
    function totalesPie(){
        var inicio=$('.gc-export').attr('data-url').slice(-28,-18)
        var final=$('.gc-export').attr('data-url').slice(-17,-7)
        var sql="SELECT sum(total) as total, sum(descuento) as descuento, sum(total_producto) as total_producto, sum(total_base) as total_base, sum(total_iva) as total_iva, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte, sum(total_pedido) as total_pedido FROM pe_orders_prestashop WHERE fecha>='"+inicio+"' AND fecha<='"+final+"' "
        $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').each(function(){
                var valor=buscar($(this).val());
                if($(this).val()) sql=sql+" AND "+$(this).attr('name')+" LIKE '%"+valor+"%'"
        })
        
        console.log(sql) 
        $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/gestionTablas/pedidosPrestashopTotales", 
        data:{sql:sql},
        success:function(datos){
            //alert(datos)          
            var datos=$.parseJSON(datos);
            //alert(datos['total']) 
            $('#total').text(datos['total'])
            $('#descuento').text(datos['descuento'])
            $('#total_producto').text(datos['total_producto'])
            $('#total_base').text(datos['total_base'])
            $('#total_iva').text(datos['total_iva'])
            $('#transporte').text(datos['transporte'])
            $('#base_transporte').text(datos['base_transporte'])
            $('#iva_transporte').text(datos['iva_transporte'])
            $('#total_pedido').text(datos['total_pedido'])
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
        })
        

    }
    


    totalesPie() 
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').keyup(function(){
        totalesPie()
    })
    

    $(' a[href*="pedidosPrestashop/add"]').html('<i class="fa fa-plus"></i> &nbsp; Subir archivo PrestaShop')
    $('body').delegate(' a[href*="pedidosPrestashop/add"]','click',function(e)  
        {  
            e.preventDefault()
            window.location.replace("<?php echo base_url() ?>"+"index.php/upload/do_upload_prestashop");
        })
        
        
    $('body').delegate('tr td> div> a[href*="pedidosPrestashop"]','click',function(e)  
        {   
           
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getPedidoPrestashop", 
        data:{id:id},
        success:function(datos){
          //alert(datos)
          
            var datos=$.parseJSON(datos);
            var total=((datos['total']/100)-(datos['descuento']/100)).toFixed(2);
            var totalBase=((datos['total_base']/100)).toFixed(2);
            var totalIva=((datos['total_iva']/100)).toFixed(2);
              
             var fecha=datos['fecha'].substr(8,2)+'/'+datos['fecha'].substr(5,2)+'/'+datos['fecha'].substr(0,4)
             var cabecera="<div class='factura' ><table class='tcab'>"
             cabecera+="<tr><th class='izda w2'>Pedido</th><td class='izda'>"+datos['pedido']+"</td></tr>"
             cabecera+="<tr><th class='izda w2'>Tipo cliente</th><td class='izda'>"+datos['tipoCliente']+"</td></tr>" 
             cabecera+="<tr><th class='izda w2'>Cliente</th><td class='izda'>"+datos['num_cliente']+" "+datos['firstname']+" "+datos['lastname']+" "+datos['country']+"</td></tr>" 

             cabecera+="<tr><th class='izda w2'>Fecha</th><td izda='dcha w1'>"+fecha+"</td></tr>" 
             cabecera+="</table><p>"
             
             var productos="<table class='tlineas'>"
             if(datos['tipoCliente']!=9)
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
                    if(datos['tipoCliente']!=9){
                        productos+="<td class='dcha'>"+baseTotal.toFixed(2)+"</td>"
                        productos+="<td class='dcha'>"+tipoIva.toFixed(2)+"%</td>"
                        productos+="<td class='dcha'>"+varAmount.toFixed(2)+" €</td>"
                     }
                    productos+="<td class='dcha'>"+precio.toFixed(2)+" €</td>"
                    
                    totalPrecio+=precio
                }
                else 
                    if(datos['tipoCliente']!=9){
                        productos+="<td></td><td></td><td></td><td></td><td></td><td></td>"
                    }
                    else
                        productos+="<td></td><td></td><td></td>"

                productos+="</tr>"
             }
             var descuento=Number(datos['descuento'])/1
             var transporte=Number(datos['transporte'])/10
             
            // alert(descuento)
            // alert(transporte)
             /*
             if(descuento>transporte){
                descuento=(descuento-transporte)
                transporte=0
                }
              */  
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
             if(datos['tipoCliente']==9)
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
                 if(isNaN(value)) value=0
                 if(isNaN(base)) base=0
                 if(isNaN(amount)) amount=0
                 
                 if(datos['tipoCliente']!=9){
                    ivas+="<td class='dcha'>"+value.toFixed(3)+"</td>"
                    ivas+="<td class='dcha'>"+base.toFixed(2)+" €</td>"
                    ivas+="<td class='dcha'>"+amount.toFixed(2)+" €</td>"
                 }
                 else{
                    ivas+="<td class='dcha'></td>" 
                 }
                 
                 if(isNaN(factor)) factor=0
                 ivas+="<td class='dcha'>"+(precioIvas[index]*factor).toFixed(2)+" €</td></tr>"
                 totalSinIva+=base
                 totalAmount+=amount
                 totalIvaIncluido+=precioIvas[index]*factor
             })
             if(transporte!=0){
                 transporte=transporte/100
                 var index=21
                 if(datos['tipoCliente']==9) index=0; //es un cliente intercomunitario que no paga iva.
                 var base=transporte/(1+index/100)
                 var amount=transporte-base
                 ivas+="<tr ><td class='dcha'>Transportista</td>"
                 if(datos['tipoCliente']!=9){
                    ivas+="<td class='dcha'>"+index.toFixed(3)+"</td>"
                    ivas+="<td class='dcha'>"+base.toFixed(2)+" €</td>"
                    ivas+="<td class='dcha'>"+amount.toFixed(2)+" €</td>"
                }
                else{
                    ivas+="<td class='dcha'></td>" 
                 }
                 ivas+="<td class='dcha'>"+(transporte).toFixed(2)+" €</td></tr>"
                 totalSinIva+=base
                 totalAmount+=amount
                 totalIvaIncluido+=transporte
                 
                 }
             if(datos['tipoCliente']!=9){    
                ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td><td></td><td></td></tr>"
                ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+totalSinIva.toFixed(2)+" €</td><td class='dcha'>"+totalAmount.toFixed(2)+" €</td><td class='dcha'>"+totalIvaIncluido.toFixed(2)+" €</td></tr>"
             }  
             else{
                ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td></tr>"
                ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+totalIvaIncluido.toFixed(2)+" €</td></tr>"
             }
             ivas+="</table>"
             

             var totalFinal="<table class='totalFinal'><tr><td >Total: "+totalIvaIncluido.toFixed(2)+" €</td></tr></div>"
            $('.modal-title').html('Pedido Prestashop')
           // $('.modal-body').html(tabla+tabla2)
           $('.modal-body').html(cabecera+productos+ ivas+totalFinal)
            $("#myModal").modal()
            //$("#myModalPedidosPrestashop").modal()
            
            
            
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
        });
    
     $('.gc-export').click(function(){
         console.log('pulsado gc.export')
         console.log($('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(2) > input').val())
         //$('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(2) > input').val('02-01')
     })
    
    
     })
    
    
    
    </script>
    
      