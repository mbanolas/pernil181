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

.table tbody tr td:nth-child(3){
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

.table tbody tr .col-md-3:nth-child(3){
    text-align: right;
}

.table.ticket thead tr th:nth-child(1){
    text-align: right;
}

.table.ticket thead tr th:nth-child(2){
    text-align: right;
}
.table.ticket thead tr th:nth-child(3){
    text-align: right;
}
.table.ticket thead tr th:nth-child(4){
    text-align: right;
}
.table.ticket thead tr th:nth-child(5){
    text-align: right;
}
#totalTickets{
    text-align: left;
}


/*
.modal-content{
    width:800px;
    left:-52px;
    
}
*/
.forma_pago{
    color:red;
}

/* para cambiar el ancho ventana modal */
.modal-dialog {
        width: 40%;
        margin: 0 auto;
        margin-top: 15px;
    }

.fila {
  display: flex;
}

.columna {
  flex: 50%;
}

#rangoFechas{
    margin-right:5px;
    margin-top:6px;
}
body > div > div.tablaGC > div > div.row > div.table-section > div.table-label{
    border: 5px solid blue;
    background-color: lightblue;
}
#gcrud-search-form > table > tfoot > tr:nth-child(2){
    border: 5px solid blue;
    background-color: lightblue;
}
#gcrud-search-form > table > tbody > tr > td:nth-child(5){
    text-align: right;
}






</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $tituloRango=isset($tituloRango)?$tituloRango:'Tickets tienda';
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
    <input type="hidden" id="tituloRango" value="<?php echo $tituloRango ?>">
         



         
   <div class="tablaGC">
                    <?php echo $output; ?>
                </div>
    
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                
            </div>
        </div>
    
    

    <script>
        
$(document).ready(function(){

    //elimina boton Eliminar filtros del pie
    $('#gcrud-search-form > div.footer-tools > div.floatR.r5 > div > button').addClass('hide')

    //introducir Buscar total SIN , o . decimal (que es como está el número internamente en BD)
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(5) > input').attr('data-toggle','tooltip')
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(5) > input').attr('data-placement','top')

    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(5) > input').attr('title','Introducir número sin , o .')

    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(5) > input').keyup(function(e){
        if(e.keyCode==188 || e.keyCode==190){
            alert("Entrar el número sin ',' o '.'")
            var valor=$(this).val()
            valor=valor.replace(new RegExp(',', 'g'), '');
            $(this).val(valor)
        }
    })

    $('[data-toggle="tooltip"]').tooltip(); 

    function totalesPie(){
            var inicio=$('.gc-export').attr('data-url').slice(-28,-18)
            var final=$('.gc-export').attr('data-url').slice(-17,-7)
            var sql="SELECT sum(t.total) as total,count(t.num_ticket) as num_tickets FROM pe_tickets t"; 
            var where=" WHERE fecha>='"+inicio+"' AND fecha<='"+final+' 23:59:59'+"' "

            $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').each(function(){
                var valor=buscar($(this).val());
                if(valor){
                    var campo=$(this).attr('placeholder')
                    switch(campo){
                            case 'Buscar Forma pago':
                                sql+=" LEFT JOIN pe_formas_pagos_tickets f ON f.id_forma_pago_ticket=t.id_forma_pago_ticket "
                                where+=" AND f.forma_pago LIKE '%"+valor+"%'"
                            break
                            case 'Buscar Fecha y hora ticket':
                                where+=" AND t.fecha LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Núm. ticket':
                                where+=" AND t.num_ticket LIKE '%"+valor+"%'"
                            break 
                            case 'Buscar Importe Total Ticket':
                                console.log(valor)
                                //valor=valor.replace(new RegExp(',', 'g'), '');
                                where+=" AND t.total LIKE '%"+valor+"%'"
                            break 
                    }
                }
            })
            sql+=where
            //console.log(sql)
            
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/gestionTablas/ticketsAlternativosTiendaTotales", 
            data:{sql:sql},
            success:function(datos){
                //alert(datos)          
                var datos=$.parseJSON(datos);
                //alert(datos['total']) 
                $('#total').text(datos['total'])
                $('#totalTickets').text(datos['num_tickets'])
                
            },
            error: function(){
                    alert("Error en el proceso cálculo totalesPie. Informar");
            }
            })
        }

        function totalesPieSinFiltros(){
            var inicio=$('.gc-export').attr('data-url').slice(-28,-18)
            var final=$('.gc-export').attr('data-url').slice(-17,-7)
            var sql="SELECT count(num_ticket) as num_tickets, sum(total) as total FROM pe_tickets WHERE fecha>='"+inicio+"' AND fecha<='"+final+' 23:59:59'+"' "
            
            $.ajax({
            type:'POST',
            url: "<?php echo base_url() ?>"+"index.php/gestionTablas/ticketsAlternativosTiendaTotales", 
            data:{sql:sql},
            success:function(datos){
                //alert(datos)          
                var datos=$.parseJSON(datos);
                //alert(datos['total']) 
                $('#total').text(datos['total'])
                $('#totalTickets').text(datos['num_tickets'])
                
            },
            error: function(){
                    alert("Error en el proceso totalesPieSinFiltros. Informar");
            }
            })
        }    

        //al poner nueva búsqueda, calcula el nuevo pie  
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').keyup(function(){
        totalesPie()
    })

    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td > input').change(function(){
        
    })
   
    

    totalesPie() 

    //anchura mínima fecha
    $('#gcrud-search-form > table > thead > tr:nth-child(1) > th:nth-child(2)').css('min-width','160px')
    
    //poner en título el rango
    var tituloRango=$('#tituloRango').val()
    $('body > div > div.tablaGC > div > div.row > div.table-section > div.table-label > div.floatL.l5').text(tituloRango)

    //boton para seleccionar nuevo rango fechas
    $('<button type="button" id="rangoFechas" class="btn btn-default">Seleccionar rango fechas</button>').insertBefore( ".mi-clear-filtering" );
   
    //selección nuevo rango fechas
    $('#rangoFechas').click(function(){
        var url="<?php echo base_url() ?>index.php/gestionTablas/ticketsTiendaEntreFechas";
            window.location.href = url;
    })

    //nuevo textp botón add
    $('#gcrud-search-form > div.header-tools > div.floatL.t5 > a').html('<i class="fa fa-plus"></i> &nbsp; Subir archivo Boka')   
    //acción boton add 
    $('#gcrud-search-form > div.header-tools > div.floatL.t5 > a').click(function(e){
        e.preventDefault()
        $(location).attr("href", "<?php echo base_url() ?>"+"index.php/upload/do_upload");
     }) 
     
     //pie con totales rango seleccionado
    $('<tfoot ><tr><th></th></tr><tr style="border:5px solid blue;"><th style="text-align: left; " colspan="2">Totales selección</th><th id="totalTickets">T</th><th i></th><th id="total"></th></tr></tfoot>').insertAfter('tbody')
  

    <?php if($this->session->username=="maba" || $this->session->username=="carlos" || $this->session->username=="carlos2"){ ?>
        $('.modal-dialog').css('width','80%')
    <?php } ?>
    
      

    //cambiar texto y acción boton add
    $(' a[href*="tickets/add"]').html('<i class="fa fa-plus"></i> &nbsp; Subir Boka')
    $('body').delegate(' a[href*="tickets/add"]','click',function(e)  
        {  
            e.preventDefault()
            window.location.assign("<?php echo base_url() ?>"+"index.php/upload/do_upload");
        })
        
    
    $('body').delegate('tr td> div> a[href*="tickets"]','click',function(e)  
        {  
            e.preventDefault()
            var id=$(this).attr('href').substr($(this).attr('href').lastIndexOf("/")+1)
            $( this ).append( " <img class='loader' src='<?php echo base_url() ?>images/ajax-loader-2.gif' height='20' width='20'>" );
          $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/tickets/getDatosTicketIdTickets", 
        data:{id:id},
        success: function(datos){
               // alert(datos)
               $( ".loader" ).remove();
                var valores=$.parseJSON(datos);
                //alert(valores['no_existe'])
                if(valores['no_existe']){
                    
                    //alert(valores['no_existe'])
                    $('#myModalTicket').css('color','red')
                    $('.modal-title').html('Ticket Num. inexistente')
                    $('.modal-body>p').html("El ticket <strong>"+valores['no_existe']+"</strong> NO existe en el sistema. <br>Informar al administrador.")
                    $("#myModalTicket").modal()  
                    
                }
                else{
                    
                
                    $('#myModalTicket').css('color','black')
                    $('.modal-title').html('Ticket Num. '+valores[0])
                    $('.modal-body').html(valores[1])
                    $("#myModalTicket").modal()  
                }
            },
        error: function(){
                alert("Error en el proceso a[href*='tickets']. Informar");
         }
    }) 

    
    
    
    
    // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
    
        });

        function buscar(dato){
            console.log(dato)
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
        totalesPieSinFiltros() 
    })  


     })
    
    
    
    </script>
    
      