

<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
        $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; ?>

<h3>TODAS LAS VENTAS</h3>

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
                        foreach ($results['tipos'] as $t => $vt) {
                            $totalBase+=$results['bases'][$t];
                            $totalIVA+=$results['ivas'][$t];
                            $totalTotal+=$results['totales'][$t];
                            
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
                            <td data-halign="right"><?php echo formato2decimales($results['bases'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['ivas'][$t]) ?></td>
                            <td data-halign="right"><?php echo formato2decimales($results['totales'][$t]) ?></td>
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

 <script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTienda').addClass('btn-primary');  
})
</script>

