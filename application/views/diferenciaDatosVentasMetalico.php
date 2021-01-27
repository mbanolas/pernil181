<?php  
$results=$resultsNoMetalico;
$resultsTotales=$resultsNoMetalicoTotales;

$results2=$resultsNoMetalico2;
$resultsTotales2=$resultsNoMetalicoTotales2;

?>



<h3>VENTAS EN METALICO</h3>



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
                        <?php foreach($resultsTodas as $k=>$v) {
                                 $bases[$k]=$v->bases;
                                 $ivas[$k]=$v->ivas;
                                 $totales[$k]=$v->totales;
                                 $bases2[$k]=$resultsTodas2[$k]->bases;
                                 $ivas2[$k]=$resultsTodas2[$k]->ivas;
                                 $totales2[$k]=$resultsTodas2[$k]->totales;
                                 
                             }
                            foreach($results as $k=>$v) {
                             $v->bases=$bases[$k]-$v->bases;
                             $v->ivas=$ivas[$k]-$v->ivas;
                             $v->totales=$totales[$k]-$v->totales;
                             
                             $results2[$k]->bases=$bases2[$k]-$results2[$k]->bases;
                             $results2[$k]->ivas=$ivas2[$k]-$results2[$k]->ivas;
                             $results2[$k]->totales=$totales2[$k]-$results2[$k]->totales;
                             ?>
                        <tr>   
                            <td data-halign="right"><?php echo formato2decimales($results[$k]->tipos/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results[$k]->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results[$k]->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results[$k]->totales/100) ?></td>
                            <td></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->bases/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->ivas/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->totales/100) ?></td>
                            
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->totales/100-$results[$k]->totales/100) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results2[$k]->ivas/100-$results[$k]->ivas/100) ?></td>                        </tr>
                        
                        <?php } ?>
                         <?php 
                                 $bases=$resultsTodasTotales->bases;
                                 $ivas=$resultsTodasTotales->ivas;
                                 $totales=$resultsTodasTotales->totales;
                             $resultsTotales->bases=$bases-$resultsTotales->bases;
                             $resultsTotales->ivas=$ivas-$resultsTotales->ivas;
                             $resultsTotales->totales=$totales-$resultsTotales->totales;
                             
                                 $bases2=$resultsTodasTotales2->bases;
                                 $ivas2=$resultsTodasTotales2->ivas;
                                 $totales2=$resultsTodasTotales2->totales;
                             $resultsTotales2->bases=$bases2-$resultsTotales2->bases;
                             $resultsTotales2->ivas=$ivas2-$resultsTotales2->ivas;
                             $resultsTotales2->totales=$totales2-$resultsTotales2->totales;

                             ?>
                        
                                               <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales->totales/100) ?></th>
                            <th></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->bases/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->ivas/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales($resultsTotales2->totales/100) ?></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales2->totales/100-$resultsTotales->totales/100)) ?></th>
                            <th data-halign="right"><?php echo formato2decimales(($resultsTotales2->ivas/100-$resultsTotales->ivas/100)) ?></th>
                        </tr>

                        </tbody>
                    </table>



