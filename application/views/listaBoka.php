<?php echo $periodoBalanzaTodas.'<br />'.
        $periodoBalanza1.'<br />'.
        $periodoBalanza2.'<br />'.
        $periodoBalanza3.'<br />'.
        $periodoManuales.'<br />'
        ; ?>


<h3>LISTADO DATOS BOKA</h3>



<table id="tablaListadoBoka" class="display" cellspacing="0" width="100%">
    <thead>
        <?php $cabeceras=$campos;
        foreach($cabeceras as $k=> $v){ ?>
        <th class="listadoBoka">
            <?php echo $v ?>
        </th>
        
        <?php
        }
        ?>
</thead>  
<tbody>
    <?php
   
    
    foreach ($results as $k => $row) { ?>
    
    <tr style="font-size: 12px" >
        <?php foreach ($campos as $k1 => $v1) { ?>
        <td  class="boka listadoCliente id_cliente">  
            
            <?php 
                $valor=substr($row->$v1,11,8)=="00:00:00"?"":$row->$v1;
                echo $valor
                        
           ?>
        </td>
        <?php
    }
      
    ?>
        
    </tr>
       
        <?php
    }
      
    ?>
</tbody>
</table>
<br />
<br />

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuListadoBoka').addClass('btn-primary');  
})
</script>

