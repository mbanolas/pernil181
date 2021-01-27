

<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
        $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; 
$results=$resultsTickets['importes'];
$resultsFormaPago=$resultsTickets['formaPago'];
$resultsTotales=$resultsTicketsTotales;

$results2=$resultsTickets2['importes'];
$resultsFormaPago2=$resultsTickets2['formaPago'];
$resultsTotales2=$resultsTicketsTotales2;

$resultsTotales->bases=0;
$resultsTotales->ivas=0;
 $resultsTotales->totales=0;
 $resultsTotales2->bases=0;
$resultsTotales2->ivas=0;
 $resultsTotales2->totales=0;

?>

<h3>TICKETS PROCESADOS</h3>

<table id="listadoTicketsProcesados" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th data-halign="left"  style="text-align: left;width: 10px">Fecha</th>
                            <th data-halign="right" style="text-align: left;width: 20px">Ticket</th>
                            <th data-halign="right" style="text-align: left;width: 30px">Forma Pago</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Base</th>
                            <th data-halign="right" style="text-align: right;width: 20px">IVA</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Total</th>
                            <th></th>
                            <th data-halign="right" style="text-align: right;width: 20px">Base</th>
                            <th data-halign="right" style="text-align: right;width: 20px">IVA</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Total</th>
                            <th></th>
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencia Importes</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencias IVAs</th>
                        </tr>
                        </thead>
                        <tbody>
                         <?php foreach($results as $k=>$v) {
                           //  echo $v->ticket.'  - '.$v->totales.' - '.$results2[$k]->totales.'<br />';
                         if($v->totales!=$results2[$k]->totales) {
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
                            <td data-halign="right" style="text-align: left;"><?php echo $v->ticket ?></td>
                            <td data-halign="right" style="text-align: left;"><?php echo $resultsFormaPago[$k]->formaPago ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($v->totales/100) ?></td>
                            <td></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->totales/100) ?></td>
                            <td></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($results2[$k]->totales/100-$results[$k]->totales/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($results2[$k]->ivas/100-$results[$k]->ivas/100) ?></td>
                        </tr>
                        <?php 
                            $resultsTotales->bases+=$v->bases;
                            $resultsTotales->ivas+=$v->ivas;
                             $resultsTotales->totales+=$v->totales;
                             $resultsTotales2->bases+=$results2[$k]->bases;
                            $resultsTotales2->ivas+=$results2[$k]->ivas;
                             $resultsTotales2->totales+=$results2[$k]->totales;
                            
                         }
                             } ?>
                       
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"></th>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->totales/100) ?></th>
                            <th></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->totales/100) ?></th>
                            <th></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->totales/100-$resultsTotales->totales/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->ivas/100-$resultsTotales->ivas/100) ?></th>
                        </tr>
                         
                        </tbody>
                    </table>

 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuTicketsProcesados').addClass('btn-primary');  
})
</script>

<script>
$(document).ready(function() {
   
} );
</script>