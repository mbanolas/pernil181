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
    
    

</style>


<?php //para incluir título en cabecera tabla
   /* $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  */?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
         
    <div class="container">
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
 $(document).ready(function(){
  
  $(' a[href*="/add"]').addClass('hide')
  $('.container').addClass('container-fluid');
  $('.container').removeClass('container');  
   
  
    })   
    
    
    </script>