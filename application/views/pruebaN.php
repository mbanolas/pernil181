<?php
//var_dump($salida);
//echo 'hola';

foreach($salida1 as $k=>$v){
    
            echo  $v['id'].' - '.$v['fecha_caducidad_stock'].' '.$v['cantidad'].' '.'<br>';
        }
        
echo '--------------------------<br>'  ;      

foreach($salida2 as $k=>$v){
    
            echo  $v['id'].' - '.$v['fecha_caducidad_stock'].' '.$v['cantidad'].' '.'<br>';
        }