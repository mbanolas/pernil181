<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
         $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; 
$results=$resultsTickets['importes'];
$resultsFormaPago=$resultsTickets['formaPago'];
$resultsTotales=$resultsTicketsTotales;

?>

<h3>TICKETS VENDIDOS</h3>

<table id="listaTickets" class="display" cellspacing="0" width="70%">
                        <thead>
                        <tr>
                            <th data-halign="left"  style="text-align: left;width: 10px">Fecha</th>
                            <th data-halign="right" style="text-align: left;width: 20px">Ticket</th>
                            <th data-halign="right" style="text-align: left;width: 30px">Forma Pago</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Base</th>
                            <th data-halign="right" style="text-align: right;width: 20px">IVA</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Total</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                         <?php foreach($results as $k=>$v) {
                             
                             switch($resultsFormaPago[$k]->formaPago){
                                 case 1: $resultsFormaPago[$k]->formaPago='Metálico';break;
                                 case 2: $resultsFormaPago[$k]->formaPago='Cheque';break;
                                 case 3: $resultsFormaPago[$k]->formaPago='Vale';break;
                                 case 4: $resultsFormaPago[$k]->formaPago='Tarjeta C.';break;
                                 case 5: $resultsFormaPago[$k]->formaPago='Transferencia';break;
                                 case 6: $resultsFormaPago[$k]->formaPago='A cuenta';break;
                                 case 20: $resultsFormaPago[$k]->formaPago='Metálico';break;
                                default :$resultsFormaPago[$k]->formaPago='Sin definir';
                             }
                             
                             ?>
                        <tr>
                           
                            <td data-halign="left" style="text-align: left;width: 10px"><?php echo $v->fecha ?></td>
                            <td data-halign="right" style="text-align: left;"><a href='' class="aTicket" ><?php echo $v->ticket ?></a></td>
                            <td data-halign="right" style="text-align: left;"><?php echo $resultsFormaPago[$k]->formaPago ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->totales/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                      
                        <tr>
                            
                            <th data-halign="right"></th>
                            <th data-halign="right"></th>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->totales/100) ?></th>
                            
                        </tr>
                        
                        </tbody>
                    </table>

 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTickets').addClass('btn-primary');  
})
</script>

<script>
$(document).ready(function() {
   $('.aTicket').click(function(e){
       e.preventDefault()
       
       var ticket=$(this).html()
       var fecha=$(this).parent().parent().children().html()
       ticket=ticket.toString()+' '+fecha.toString()
       //alert(ticket)
       //alert(fecha)
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/getDatosTicket",
            data: {ticket:ticket},
            success: function(datos){
                //alert(datos)
                var valores=$.parseJSON(datos);
               
                $('#myModalTicket').css('color','black')
                $('.modal-title').html('Ticket Num. '+ticket)
                $('.modal-body>p').html(valores)
                $("#myModalTicket").modal()  

            },
            error: function(){
                alert('Error en el proceso Ticket. Informar al Administrador')
            }
        });
       
       // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });
        
   })
} );
</script>

