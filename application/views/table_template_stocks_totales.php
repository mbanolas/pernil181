<style>

    .ftitle{
    font-size: 20px;
   
}

._grocery-crud-table tbody tr td{
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
    
    #gcrud-search-form > div.header-tools > div.floatR > a:nth-child(2){
        margin-left: 5px;
    }
</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
         
     <input type="hidden" id="usuario" value="<?php echo $this->session->categoria ?>">
    
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
    .botonSuperior{
        border: 2px solid red;
        margin-right: 3px;
        margin-left: 0px !important;
    }
   
    
</style>


    <script>
        
$(document).ready(function(){
    
    var valoracion=$('input.searchable-input[name="valoracion"').addClass('hide')
    
    $('#gcrud-search-form > div.header-tools > div.floatR > a.btn.btn-default.t5.mi-clear-filtering').before('<a href="<?= base_url() ?>index.php/stocks/exportCSV" class="btn btn-default t5 botonSuperior " id="maba"><i class="fa fa-download"></i><span class="hidden-xs floatR l5">Stocks para presta</span><div class="clear"></div></a>')
    $('#gcrud-search-form > div.header-tools > div.floatR > a.btn.btn-default.t5.mi-clear-filtering').before('<a  href="<?= base_url() ?>index.php/stocks/exportExcel" class="btn btn-default t5 botonSuperior mi-nuevo-excel"><i class="fa fa-cloud-download"></i><span class="hidden-xs floatR l5">Exportar selección</span><div class="clear"></div></a>')

    $('.gc-export').removeAttr('data-url')
    $('.gc-export').attr('class','btn btn-default t5 mi-excel hide')


    var buscadores=[]
        var href_inicial="<?= base_url() ?>index.php/stocks/exportExcel"
        var href=href_inicial
        $('input.searchable-input').each(function( index ) {
            buscadores.push($(this).val())
            
            if($(this).val()=="") {
                href+="/"+"_"
            } else {
                href+="/"+$(this).val().replace("/","-").replace("/","-").replace("/","-").replace("%20"," ")
            }
            // console.log(index+' '+$(this).val())
        })
        $('a.mi-nuevo-excel').attr('href',href)



    $('input.searchable-input').keyup(function(){
        var buscadores=[]
        var href_inicial="<?= base_url() ?>index.php/stocks/exportExcel"
        var href=href_inicial
        $('input.searchable-input').each(function( index ) {
            buscadores.push($(this).val())
            if($(this).val()=="") {
                href+="/"+"_"
            } else {
                href+="/"+$(this).val().replace("/","-").replace("/","-").replace("/","-").replace("%20"," ")
            }
            // console.log(index+' '+$(this).val())
        })
        $('a.mi-nuevo-excel').attr('href',href)
    })


    // $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row > td:nth-child(7) > input').change(function(){
    //     console.log('cambiado')
    //     var href=$('a.mi-nuevo-excel').attr('href')
    //     $('a.mi-nuevo-excel').attr('href',href+"/"+$(this).val())
    //     console.log($('a.mi-nuevo-excel').attr('href'))

    // })


    $('a.mi-excel').click(function(e){
          alert('hola')
          var usuario=$('#usuario').val()
         
          var buscadores=[]
          $('input.searchable-input').each(function( index ) {
              buscadores.push($(this).val())
              //alert($(this).val())
          })
          
          $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/bajarExcelStocksTotales", 
            data: {
                buscadores:buscadores, usuario:usuario
               },
            success:function(datos){
            alert(datos)
            // var datos=$.parseJSON(datos)  
            // var direccion="<?php echo base_url() ?>"+datos
            // window.location.href = direccion;
            // window.close()
            },
            error: function(){
            }  
          })
    
    })
    
    $(' a[href*="/add"]').addClass('hide')
    
    $('th:nth-child(1)').css('text-align','center')
    $('th:nth-child(2)').css('text-align','center')
    $('th:nth-child(3)').css('text-align','center')
   
    $('th:nth-child(4)').css('text-align','left')
    $('th:nth-child(5)').css('text-align','center')
    $('th:nth-child(6)').css('text-align','center')
    $('th:nth-child(7)').css('text-align','center')
     $('th:nth-child(8)').css('text-align','center')
    
    
   
   })
    
   
   
    
    </script>
    
      