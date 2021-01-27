
<?php if(isset($fallosEnvios) && count($fallosEnvios)>0){ 
        echo "<h4>Los siguientes pedidos han fallado en el envío del email</h4>";
        foreach($fallosEnvios as $k=>$v){
            echo "<h5>".$v."</h5>";
        }
    }   
    else {
        echo "<br><br><h4>Emails preparados para ser enviados</h4>";
        //echo "<a class='btn btn-primary' target='_blank' href='localhost:8888/envioemails' id='enviar' >Enviar emails tracking</a>";
    }
?>
<script>
$(document).ready(function () {
    
})
</script>



