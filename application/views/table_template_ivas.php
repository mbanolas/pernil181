<style>
    

.ftitle{
    font-size: 20px;
   
}

.table-label div{
    font-weight: bold;
    font-size:24px;
}

td{
        text-align: left;
    }

.table thead tr th:nth-child(1){
    text-align: center;
}

.table thead tr th:nth-child(2){
    text-align: left;
}
.table thead tr th:nth-child(3){
    text-align: left;
}    
    

</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">

        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
   
    <script>
        
$(document).ready(function(){
        
   
   
   
   
    
    
     })
    
    
    
    </script>
    
      