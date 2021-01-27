<style>
   

</style>
<?php echo $fecha; 
/*
$fecha="11/02/2019";
$d=$fecha;
echo $d.'<br>';
if(substr_count($d,"/"))
    for ($i = 0; $i <= substr_count($d,"/"); $i++) {
        $l=strpos($d,"/");
        $partes[]=substr($d,0,$l);
        $d=substr($d,$l+1);
    }
$partes[]=$d;
krsort($partes);
$fecha=implode("-",$partes);
var_dump($partes);
echo '<br>'.$fecha;
*/
?>


<script>
    $(document).ready(function () {

        //convierte string busqueda en fecha yyyy-mm-dd si
        //identifica la entrada con "/"
        function buscar(dato){
            if(!isNaN(dato) || dato.indexOf('/')==-1) return dato
            d=dato.trim()
            //console.log(d)
            var partes=[]
            while(d.indexOf('/')>0){
                var p=d.indexOf('/')
                partes.push(d.substring(0, p))
                d=d.substring(p+1)
            }
            partes.push(d)
            var fecha="";
            for(var i=partes.length-1;i>=0;i--){
                fecha=fecha+partes[i]+'-'
            }
            fecha=fecha.substring(0,fecha.length-1)
            //console.log(fecha)
            return fecha
        }

        console.log(buscar("1234"))
        console.log(buscar("10/02/2019"))
        console.log(buscar(4))

    })
</script>


    