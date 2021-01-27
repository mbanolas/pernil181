<?php
echo '<br />';
foreach($productos as $k=>$v){
    $num=$v[0];
    $id_producto=$v[1];
    $snr1=$v[2];
    if(is_null($id_producto)){
        
        echo "Encontrado en $num tickets - $snr1 - NO ESTA en Base datos productos<br />";
    }
}

echo '<br />';
foreach($productos2 as $k=>$v){
    $num=$v[0];
    $snr1=$v[1];
    $numbascula=$v[2];
   
        
        echo "Encontrado en $num tickets - $snr1 - Tiene varios códigos báscula - $numbascula";
    echo '<br />';
}