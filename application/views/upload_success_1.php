<html>
<head>
<title>Pernil181</title>
</head>
<body>
    
<?php 
$rawName=$upload_data['raw_name'].$upload_data['file_ext'];
$original=$upload_data['orig_name'];

if (strtolower($rawName)==  strtolower($original)) { ?>

<h3>El archivo se está subiendo</h3>

<ul>
<?php 
$itemCast=array('file_name'=>'Nombre Archivo',
            'file_type' =>'Tipo Archivo',
            'file_path'=> 'Directorio Archivo',
            'full_path'=> 'Nombre completo Archivo',
    'raw_name'=> 'Nombre Archivo',
    'orig_name' => 'Nombre Archivo Original',
    'client_name'=> 'Nombre Cliente',
    'file_ext'=>'Extensión Archivo',
    'file_size'=>'Tamaño Archivo',
    );
foreach ($upload_data as $item => $value) {
if(isset($itemCast[$item])) { echo '<li>'.$itemCast[$item].$value.'</li>';}
}


?>
</ul>




$file = fopen(base_url() . "uploads/".$upload_data['raw_name'].$upload_data['file_ext'], "r") or exit("No se puede abrir el archivo");
                    $lineas = array();
                    while (!feof($file)) {
                        $lineas[] = fgets($file);
                    }
                    
                    $tickets=0;
                    $base[1]=0;
                    $base[2]=0;
                    $base[3]=0;
                    $iva[1]=0;
                    $iva[2]=0;
                    $iva[3]=0;
                    $total[1]=0;
                    $total[2]=0;
                    $total[3]=0;
                    
                    foreach ($lineas as $k => $v) {
                        if (substr($v, 9, 5)==1)
                        {
                            $tickets++;
                            
                            echo  substr($v, 0, 7) . '  ';
                        }
                        if (substr($v, 9, 5)==6)
                        {
                            $tipo=intval(substr($v, 115, 5));
                            $base[$tipo]+=intval(substr($v, 55, 10));
                            $iva[$tipo]+=intval(substr($v, 65, 10));
                            $total[$tipo]+=intval(substr($v, 75, 10));
                            echo  substr($v, 0, 7) . '  ';
                        }
                    }
                    echo '<br /><br />'.'TOTAL '.$tickets.' Tickets'.'<br /><br />'; 
                    ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th data-halign="right">Tipo IVA</th>
                            <th data-halign="right">Base</th>
                            <th data-halign="right">IVA</th>
                            <th data-halign="right">Total</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <?php
                        $totalBase=0;
                        $totalIVA=0;
                        $totalTotal=0;
                        foreach ($base as $t => $vt) {
                            $totalBase+=$vt/100;
                            $totalIVA+=$iva[$t]/100;
                            $totalTotal+=$total[$t]/100;
                            ?>
                        <tr>
                            <td data-halign="right"><?php echo $t ?></td>
                            <td data-halign="right"><?php echo $vt/100 ?></td>
                            <td data-halign="right"><?php echo $iva[$t]/100 ?></td>
                            <td data-halign="right"><?php echo $total[$t]/100 ?></td>
                        </tr>
                        
                        <?php
                        }
                        ?>
                        <tr>
                            <th data-halign="right"></th>
                            <th data-halign="right"><?php echo $totalBase ?></th>
                            <th data-halign="right"><?php echo $totalIVA ?></th>
                            <th data-halign="right"><?php echo $totalTotal ?></th>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                    /*
                    foreach($base as $t=>$vt){
                        $totalBoka=$total[$t]/100;
                        $txt=sprintf("Tipo IVA %u  Base %8.2f  IVA %8.2f   Total %8.2f <BR />", $t,$vt/100,$iva[$t]/100,$totalBoka);
                        echo $txt;
                        //echo 'Tipo IVA '.$t.' Base '. ($vt/100)."  IVA ".($iva[$t]/100)."  Total ".($total[$t]/100).'<br />';
                    }
                    */
                    fclose($file);
                    
                    //echo $this->upload_->setBoka($lineas);
                    
                    $inicios=array(0, 7, 9, 14, 19, 21, 26, 31, 40, 45, 50, 55, 65, 75, 85, 90, 95, 105, 115, 120, 125, 130, 135, 140, 145, 150, 155, 160, 165, 170, 175, 185, 190, 195, 205, 215, 220, 230, 239, 248, 253, 258, 263, 272, 277, 282, 287, 307, 327, 347);
                    $finales=array(7, 2, 5,  5,  2,  5,  5,  9,  5,  5,  5, 10, 10, 10,  5,  5, 10,  10,   5,   5,   5,   5,   5,   5, 	 5,   5,   5, 	5,   5,   5,  10,   5, 	 5,  10,  10,   5,  10,   9,   9,   5,   5,   5,   9,   5,   5,   5,  20,  20,  20,  20);
                    
                    $valores=array();
                    foreach ($lineas as $k => $v) {
                        foreach($inicios as $k2=>$v2){
                        $valores[$k][]=substr($v,$v2,$finales[$k2]);
                        }
                        
                        $valores[$k][46]= date_format(new DateTime($valores[$k][46]), 'Y-m-d H:i:s'); 
                        $valores[$k][47]= date_format(new DateTime($valores[$k][47]), 'Y-m-d H:i:s'); 
                        
                       $this->upload_->setBoka($valores[$k]);
                    }
                    
                    
}   
else{
    $archivo=$upload_data['orig_name'];
    
    echo "<br /><h3>Este archivo BOKA ($archivo) YA se ha procesado</h3><br /><br />";
}
                    ?>
<!--<p><?php echo anchor('upload', 'Upload Another File!');  ?></p>-->

<?php echo form_open('pernil181/index',array('role'=>'form')) ?>
   <input type="submit" class="btn btn-primary btn-lg" value="Continuar" >
    
</form>



</body>
</html>