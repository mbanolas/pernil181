<h3>Archivos Boka</h3>

<hr>
<?php 
if (sizeof($infoFile)>0)
foreach($infoFile as $k=>$v){
     foreach($v as $k1=>$v1){
         echo "[$k1] => <strong>$v1</strong><br />";
     }
     echo "<br />";
    }
    else echo "<h3>No existen archivos</h3>";
    ?>




