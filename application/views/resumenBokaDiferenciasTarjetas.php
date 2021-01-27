
<h3>TICKETS PAGADOS CON TARJETA CREDIDO</h3>

<h4>Total tickets: <?php echo $resumenTarjetas['tickets'] ?></h4>
<h4>Tickets registrados: <?php echo $resumenTarjetas['codigosSubidos'] ?></h4>


<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            <th></th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                            <th data-halign="right">Diferencias<br />Importes</th>
                            <th data-halign="right">Diferencias<br />IVAs</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalBase=0;
                        $totalIVA=0;
                        $totalTotal=0;
                        $totalBase2=0;
                        $totalIVA2=0;
                        $totalTotal2=0;
                        foreach ($resumenTarjetas['base'] as $t => $vt) {
                            $totalBase+=$vt/100;
                            $totalIVA+=$resumenTarjetas['iva'][$t]/100;
                            $totalTotal+=$resumenTarjetas['total'][$t]/100;
                            $totalBase2+=$resumenTarjetas2['base'][$t]/100;
                            $totalIVA2+=$resumenTarjetas2['iva'][$t]/100;
                            $totalTotal2+=$resumenTarjetas2['total'][$t]/100;
                        ?>
                        <tr>
                            <?php 
                            $tipo="";
                            switch ($t){
                                case 1: $tipo="10.0"; break;
                                case 2: $tipo="21.0"; break;
                                case 3: $tipo="4.0"; break;
                            }
                            ?>
                            <td data-halign="right"><?php echo $tipo ?></td>
                            <td data-halign="right"><?php echo formato2decimales($vt/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas['iva'][$t]/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas['total'][$t]/100) ?></td>
                            <td></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas2['base'][$t]/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas2['iva'][$t]/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas2['total'][$t]/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas['total'][$t]/100-$resumenTarjetas2['total'][$t]/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenTarjetas['iva'][$t]/100-$resumenTarjetas2['iva'][$t]/100) ?></td>
                        </tr>
                        
                        <?php
                        }
                        ?>
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($totalBase) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalIVA) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalTotal) ?></th>
                            <th></th>
                            <th data-halign="right"><?php echo formato2decimales($totalBase2) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalIVA2) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalTotal2) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalTotal-$totalTotal2) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalIVA-$totalIVA2) ?></th>
                        </tr>
                        </tbody>
                    </table>



