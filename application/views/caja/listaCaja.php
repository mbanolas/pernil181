<div class="container">
<h3>Lista Caja </h3>

</div>
<div class="container listado">
<div class="row-fluid">
<table id="listaCaja" class="display" cellspacing="0" width="70%">
                        <thead>
                        <tr>
                            <th data-halign="left"  style="text-align: right;width: 10px">Fecha</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Cambio Mañana</th>
                            <th data-halign="right" style="text-align: right;width: 30px">Ventas Día</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Retiro Metálico</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Retiro Tarjetas</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Retiro Otros</th>
                           
                            <th data-halign="right" style="text-align: right;width: 20px">NO Cobradas</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Cotros Atrasos Metálico</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Cotros Atrasos Tarjeta</th>
                            
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencia Metálico</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencia Tarjetas</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencia Otros</th> 
                           
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencia Caja</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Pendientes Cobro</th>
                            <th data-halign="right" style="text-align: right;width: 20px">Diferencias Caja Acumulada</th>
                        </tr>
                        </thead>
                        <tbody>
                          
                         <?php foreach($cierresCaja as $k=>$v) { 
                             $retiroOtros=$v->retiroVale+$v->retiroTransferencia+$v->retiroCheque;
                             $diferenciaOtras=$v->diferenciaVale+$v->diferenciaTransferencia+$v->diferenciaCheque;
                             
                             ?>
                            <tr>
                            
                            <td data-halign="left"  style="text-align: right;width: 10px"><?php echo substr(fechaEuropea($v->fecha),0,10) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->cambioManana/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 30px"><?php echo formato2decimales($v->ventaDia/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 30px"><?php echo formato2decimales($v->retiroMetalico/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->retiroTarjeta/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($retiroOtros/100) ?></td>
                            
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->ventaNoCobrada/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->cobroAtrasosMetalico/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->cobroAtrasosTarjeta/100) ?></td>

                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaMetalico/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaTarjeta/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($diferenciaOtras/100) ?></td>
                           
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaCaja/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->saldoBanco/100) ?></td>
                            <td data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaCajaAcumulada/100) ?></td>
                            
                        </tr>
                        <?php } ?>
                      
                      
                        
                        </tbody>
                        <footer>
                           <?php foreach($sumaCierresCaja as $k=>$v) { 
                             $retiroOtros=$v->retiroVale+$v->retiroTransferencia+$v->retiroCheque;
                             $diferenciaOtras=$v->diferenciaVale+$v->diferenciaTransferencia+$v->diferenciaCheque;
                             ?>
                            <tr>
                            <th data-halign="left"  style="text-align: right;width: 10px">Totales</th>
                            <th data-halign="right" style="text-align: right;width: 20px"></th>
                            <th data-halign="right" style="text-align: right;width: 30px"><?php echo formato2decimales($v->ventaDia/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 30px"><?php echo formato2decimales($v->retiroMetalico/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->retiroTarjeta/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($retiroOtros/100) ?></th>
                            
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->ventaNoCobrada/100) ?></th>
                            
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->cobroAtrasosMetalico/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->cobroAtrasosTarjeta/100) ?></th>

                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaMetalico/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaTarjeta/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($diferenciaOtras/100) ?></th>
                            
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->diferenciaCaja/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"><?php echo formato2decimales($v->ventaNoCobrada/100-$v->cobroAtrasosMetalico/100-$v->cobroAtrasosTarjeta/100) ?></th>
                            <th data-halign="right" style="text-align: right;width: 20px"></th>
                      
                        </tr>
                        <?php } ?> 
                            
                            
                        </footer>
                    </table>
</div>
</div>




<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuCaja').addClass('btn-primary');
  $('#menuInformacionCierresCaja').addClass('btn-primary');  
})
</script>
