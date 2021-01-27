<style>
    
    .ftitle{
    font-size: 20px;
   
}

.grocery-crud-table tbody tr td{
    text-align: left;
}

h4.modal-title{
    font-weight: bold;
}

.row {
    margin-right: -10px;
    margin-left: -10px;
}

.table-label div{
    font-weight: bold;
    font-size:24px;
}

.container{
    padding-left: 0px;
    padding-right: 0px;
}





.gc-container {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: auto;
    margin-left: auto;
}

td{
        text-align: left;
    }



.table thead tr th:nth-child(1){
    text-align: center;
}

.table thead tr th:nth-child(2){
    text-align: right;
}
.table thead tr th:nth-child(3){
    text-align: center;
} 
.table thead tr th:nth-child(4){
    text-align: left;
} 
.table thead tr th:nth-child(5){
    text-align: left;
} 
.table thead tr th:nth-child(6){
    text-align: left;
} 

.table tbody tr td:nth-child(1){
    text-align: center;
}
.table tbody tr td:nth-child(2){
    text-align: right;
}
.table tbody tr td:nth-child(3){
    text-align: center;
}
.table tbody tr td:nth-child(4){
    text-align: left;
}
.table tbody tr td:nth-child(5){
    text-align: left;
}
.table tbody tr td:nth-child(6){
    text-align: left;
}

   
.modal-content{
    width:1100px;
    left:-250px;
    
}

.izda{
   text-align: left; 
}
.dcha{
   text-align: right;
}
.w1{
    padding-left: 30px;
}
.w2{
    min-width:100px; 
}
.cab th{
    
    padding: 10px 10px;
    border: 1px solid black;
    background-color: lightgray;
    text-align: center;
}
.linea td{
     border: 1px solid black;
     padding: 5px 10px 5px 10px;
}
.descuento{
    font-weight: bold;
}
.ivas th{
    padding-top:12px;
    padding-left:20px;  
}

.factura{
    
    
}

.tcab{
    width: auto;
    margin-right: auto;
    margin-left: 30px;
    
}

.tlineas{
   width: auto;
    margin-right: 30px;
    margin-left: auto;
}
.tivas{
   width: auto;
    margin-right: 30px;
    margin-left: auto;
}

.totalFinal{
    width: auto;
    margin-right: 30px;
    margin-left: auto;
    
    margin-top:10px;
    padding-top: 5px;
    padding-bottom: 5px;
    border: 1px solid black;
    
    font-size: 14px;
    font-weight: bold;
    
    height:30px;
}

.totalFinal td{
   
    width: 200px;
    text-align: center;
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
    
      