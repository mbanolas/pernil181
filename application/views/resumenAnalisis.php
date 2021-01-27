<h3>Resumen Análisis</h3>
<h3>Indicar periodo a analizar</h3>
<div class="container2">
    <div class="col-4">
    <span>Desde: <input type="date" id="desde"></span>
    </div>   
     <div class="col-4"><span> Hasta: <input type="date" id="hasta"></span>
     </div>

<button id="botonAnalizar" style="display: inline;text-align: center;"  class="btn btn-primary btn-mini">
            <span ></span> Calcular análisis</button>
</div>
<?php

?>
<style>
    
</style>

 <script>
 $(document).ready(function(){
 
    $('#botonAnalizar').click(function(){
        var desde=$('#desde').val();
        var hasta=$('#hasta').val();
        if(!desde || !hasta) {
            alert('Falta indicar alguna de las fechas')
            return
        }
        $.ajax({
            url: "<?php echo base_url() ?>"+"index.php/ventas/getAnalisisVentas",
            success: function(datos){
               var datos=$.parseJSON(datos)
               
            },
            error: function(){
                alert("Error en el proceso Análisis Ventas. Informar");
            }
        })    
        
        
    })
    
  
  
})
 
 
 </script>

    

