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
    
    

    
<style>
    .numero{
        text-align: right;
    }
    .centrado{
        text-align:center;
    }
    
</style>


    <script>
        
$(document).ready(function(){
    
    $('.gc-export').removeAttr('data-url')
    $('.gc-export').attr('class','btn btn-default t5 mi-excel ')
    
    $('a.mi-excel').click(function(e){
          var buscadores=[]
          $('input.searchable-input').each(function( index ) {
              buscadores.push($(this).val())
              //alert($(this).val())
          })
          
          $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/bajarExcelStocks", 
            data: {
                
                buscadores:buscadores,
              
               },
            success:function(datos){
                //alert(datos)
            var datos=$.parseJSON(datos)  
            var direccion="<?php echo base_url() ?>"+datos
            window.location.href = direccion;
                     // window.open(direccion,'_blank');
                     // window.close()

            },
            error: function(){
            }  
          })
    
    })
    
    $(' a[href*="/add"]').addClass('hide')
    
    $('th:nth-child(1)').css('text-align','center')
    $('th:nth-child(2)').css('text-align','center')
    $('th:nth-child(3)').css('text-align','right')
    $('th:nth-child(3)').css('padding-right','20px')
    $('th:nth-child(4)').css('text-align','left')
    $('th:nth-child(5)').css('text-align','left')
    $('th:nth-child(6)').css('text-align','center')
    $('th:nth-child(7)').css('text-align','center')
   
   })
    
    
    
    </script>
    
      