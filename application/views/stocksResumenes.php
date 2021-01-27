

<br />
<!-- Seleccion producto -->
<div class="box box-primary col-lg-12 " id="seleccionar">  
        <div class="container">
            <h4>Resúmenes Stocks </h4>
            
            
            <?php //echo $resumen['sql'] ?>
            </div>
        </div>

<div class="container">
          
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="izda">Grupo</th>
        <th class="izda">Familia</th>
        
        <th class="drcha">Cantidad</th>
        <th class="drcha">Unidad</th>
        <th class="drcha">Importe Stock</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($resumen['result'] as $k=>$v) { ?>
      <tr>
        <td class="izda"><?php echo $v->grupo ?></td>
        <td class="izda"><?php echo $v->familia ?></td>
        <?php 
            if($v->unidad==="Und") {
                $cantidad= number_format ($v->cantidad/1000,0);
               }
               else{
                 $cantidad= number_format ($v->cantidad/1000,3);  
               }
               ?>
        <td class="drcha"><?php echo $cantidad ?></td>
        <td class="drcha"><?php echo $v->unidad ?></td>
        <td class="drcha"><?php echo number_format ($v->importe,2) ?></td>
      </tr>
        <?php } ?>
    <tfoot>
        <td class="izda"><?php //echo $v->grupo ?></td>
        <td class="izda"><?php //echo $v->familia ?></td>
        
        <td class="drcha"><?php //echo $cantidad ?></td>
        <td class="drcha"><?php //echo $v->unidad ?></td>
        <td class="drcha"><?php echo number_format ($resumen['totalRow']->importe,2) ?></td>
    </tfoot>
      
      
    </tbody>
  </table>
</div>

        





<style>
    .izda {text-align: left;}
    .drcha {text-align: right;}
</style>

<script>
        
$(document).ready(function () {
    
   
    
})
</script>