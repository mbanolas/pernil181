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
         
    <div class="container-fluid">
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

        $(window).load(function () {
            //caso de ver información productos
            $('div.container').addClass('container-fluid')
            $('div.container-fluid').removeClass('container')
        })
        
   var resultado=''
   
  $(' a[href*="/add"]').html('<i class="fa fa-plus"></i> &nbsp; Generar nueva factura')
  
  
   //para mostrar en edición en nombre 'bonito' de la factura en lugar del internno
  $(document).ajaxComplete(function(){
      console.log('hola')
      var nombreArchivoFactura=$('.open-file').html()
   if (nombreArchivoFactura != null){
       nombreArchivoFactura=nombreArchivoFactura.toLowerCase();
        var n=nombreArchivoFactura.lastIndexOf("actura");
        nombreArchivoFactura= "F"+nombreArchivoFactura.substr(n);
        nombreArchivoFactura=nombreArchivoFactura.replace("-b", " B");
        
        $('.open-file').html(nombreArchivoFactura)
    }
  })
   
   //para mostrar en edición en nombre 'bonito' de la factura en lugar del internno
   var nombreArchivoFactura=$('.open-file').html()
   if (nombreArchivoFactura != null){
       nombreArchivoFactura=nombreArchivoFactura.toLowerCase();
        var n=nombreArchivoFactura.lastIndexOf("actura");
        nombreArchivoFactura= "F"+nombreArchivoFactura.substr(n);
        nombreArchivoFactura=nombreArchivoFactura.replace("-b", " B");
        $('.open-file').html(nombreArchivoFactura)
    }
    
    
   
   $('body').delegate(' a[href*="/add"]','click',function(e)  
        {  
            e.preventDefault()
           
            window.location.replace("<?php echo base_url() ?>"+"index.php/facturas");
        })    
      
    })   
    
    
    </script>