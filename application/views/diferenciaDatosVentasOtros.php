


<h3>VENTAS OTROS</h3>

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
                        foreach ($results['tiposO'] as $t => $vt) {
                            $totalBase+=$results['basesO'][$t];
                            $totalIVA+=$results['ivasO'][$t];
                            $totalTotal+=$results['totalesO'][$t];
                            $totalBase2+=$results2['basesO'][$t];
                            $totalIVA2+=$results2['ivasO'][$t];
                            $totalTotal2+=$results2['totalesO'][$t];
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
                            <td data-halign="right"><?php echo formato2decimales($results['tipos'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['basesO'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['ivasO'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['totalesO'][$t]) ?></td>
                            <td></td>
                            <td data-halign="right"><?php echo formato2decimales($results2['basesO'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2['ivasO'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2['totalesO'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2['totalesO'][$t])-formato2decimales($results['totalesO'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2['ivasO'][$t])-formato2decimales($results['ivasO'][$t]) ?></td>
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
                            <th data-halign="right"><?php echo formato2decimales($totalTotal2-$totalTotal) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($totalIVA2-$totalIVA) ?></th>
                        </tr>
                        </tbody>
                    </table>



