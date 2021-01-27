
<h3>TICKETS PAGADOS EN METALICO</h3>

<h4>Total tickets: <?php echo $resumenMetalico['tickets'] ?></h4>
<h4>Tickets registrados: <?php echo $resumenMetalico['codigosSubidos'] ?></h4>

<table class="table">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalBase=0;
                        $totalIVA=0;
                        $totalTotal=0;
                        foreach ($resumenMetalico['base'] as $t => $vt) {
                            $totalBase+=$vt/100;
                            $totalIVA+=$resumenMetalico['iva'][$t]/100;
                            $totalTotal+=$resumenMetalico['total'][$t]/100;
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
                            <td data-halign="right"><?php echo formato2decimales($resumenMetalico['iva'][$t]/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($resumenMetalico['total'][$t]/100) ?></td>
                        </tr>
                        
                        <?php
                        }
                        ?>
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($totalBase) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalIVA) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalTotal) ?></th>
                        </tr>
                        </tbody>
                    </table>





