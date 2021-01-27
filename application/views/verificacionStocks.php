
<h3>Productos NO existentes en pe_stocks_totales</h3>
<?php 
    if(count($productosNoExistentesEnStocksTotales)==0) echo '<h4>Verificación correcta: no se han encontrado ninguno.</h4>';
    else{
        echo '<h4>Productos NO existentes en pe_stocks_totales:</h4>';
    
    foreach($productosNoExistentesEnStocksTotales as $k=>$v){
            echo $v.'<br>';
        }
    }
    ?>

<h3>Productos de pe_stocks_totales NO existente en pe_productos</h3>
<?php 
    if(count($productosNoExistentesEnProductos)==0) echo '<h4>Verificación correcta: no se han encontrado ninguno.</h4>';
    else{
        echo '<h4>Productos NO existentes en pe_stocks_totales:</h4>';
    
    foreach($productosNoExistentesEnProductos as $k=>$v){
            echo $v.'<br>';
        }
    }
    ?>
<h3>Productos de pe_stocks NO existente en pe_productos</h3>
<?php 
    if(count($productosNoExistentesEnProductos2)==0) echo '<h4>Verificación correcta: no se han encontrado ninguno.</h4>';
    else{
        echo '<h4>Productos NO existentes en pe_stocks_totales:</h4>';
    
    foreach($productosNoExistentesEnProductos2 as $k=>$v){
            echo $v.'<br>';
        }
    }
    ?>



<h3>Integridad cantidades pe_stocks y pe_stocks_totales</h3>
<h4>Suma cantidades pe_stocks: <?php echo $sumaStocks ?></h4>
<h4>Suma cantidades pe_stocks_totales: <?php echo $sumaStocksTotales ?></h4>
<?php if($sumaStocks!=$sumaStocksTotales) {echo '<h4><span style="color:red"> Error en sumas</span></h4>';
    foreach($productosConDiferencias as $k=>$v){
        echo $v.'<br>';
    }
}
      else echo '<h4><span > Correcto</span></h4>';


