<!--
<h3>Relación Imagenes  Producto 23 Enero 2017</h3>
<br>



<?php
foreach($codigo_productos as $k=>$v){
   // echo $v.' '.$nombres[$k]. '  '.$imagenes[$k].'<br>';
}
$salida="<table>";
foreach($imagenes as $k=>$v){
   //if($k>5)        break;
    $salida.='<tr>';
    $salida.='<td>'.$codigo_productos[$k].'</td>';
    $salida.='<td class="nombre">'.$nombres[$k].'</td>';
    $resultado=url_exists($v);
    if ($v!='') $salida.='<td class="url">'.$resultado.'</td>'; else $salida.='<td class="url">'.'---'.'</td>';
    $salida.='<td class="url"><a href="'.$v.'" target="_blanc">'.$v.'</a></td>';
    if($resultado=="OK")
          $salida.='<td><img src="'.$v.'" alt="No existe" height="42" width="42"> </td>';  

    $salida.='</tr>';
}
$salida.='</table>';
echo $salida;
?>
-->

<h3>Relación Páginas Producto Tienda Online  23 Enero 2017</h3>
<br>
<?php
$salida="<table>";
foreach($paginas as $k=>$v){
   //if($k>5)        break;
    $salida.='<tr>';
    $salida.='<td>'.$codigo_productos[$k].'</td>';
    $salida.='<td class="nombre">'.$nombres[$k].'</td>';

    if ($v!='') $salida.='<td class="url">'.url_exists($v).'</td>'; else $salida.='<td class="url">'.'---'.'</td>';
    $salida.='<td class="url"><a href="'.$v.'" target="_blanc">'.$v.'</a></td>';
        
    $salida.='</tr>';
}
$salida.='</table>';
echo $salida;

?>





<?php
function url_exists($url) {
    $ch = curl_init($url); 
//cURL set options
$options = array(
    CURLOPT_URL => $url,              #set URL address
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',  #set UserAgent to get right content like a browser
    CURLOPT_RETURNTRANSFER => true,         #redirection result from output to string as curl_exec() result
    CURLOPT_COOKIEFILE => 'cookies.txt',    #set cookie to skip site ads
    CURLOPT_COOKIEJAR => 'cookiesjar.txt',  #set cookie to skip site ads
    CURLOPT_FOLLOWLOCATION => true,         #follow by header location
    CURLOPT_HEADER => true,                 #get header (not head) of site
    CURLOPT_FORBID_REUSE => true,           #close connection, connection is not pooled to reuse
    CURLOPT_FRESH_CONNECT => true,          #force the use of a new connection instead of a cached one
    CURLOPT_SSL_VERIFYPEER => false         #can get protected content SSL
);
//set array options to object
curl_setopt_array($ch, $options);
curl_exec($ch); 
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
curl_close($ch);  if(empty($retcode) || $retcode > 400) { return 'ERROR'; } 
else { return 'OK'; } 
}


?>

<style>
    .nombre{
        text-align: left;
        padding-left: 15px;
        font-weight: bold;
    }
    .url{
        text-align: left;
        padding-left: 15px;
       /*font-weight: bold;*/
    }
    
</style>