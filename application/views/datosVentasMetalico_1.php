<h3>VENTAS EN METALICO</h3>

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
                        $totalBase2=0;
                        $totalIVA2=0;
                        $totalTotal2=0;
                        foreach ($results['tiposM'] as $t => $vt) {
                            $totalBase+=$results['basesM'][$t];
                            $totalIVA+=$results['ivasM'][$t];
                            $totalTotal+=$results['totalesM'][$t];
                            
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
                            <td data-halign="right"><?php echo formato2decimales($results['basesM'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['ivasM'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['totalesM'][$t]) ?></td>
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



