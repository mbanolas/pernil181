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
    
}

/*alineación campos tabla a la izda */
.table tbody tr td{
    text-align: left;
}

/*alineación titulos tabla a la izda */
.table thead tr th{
    text-align: left;
}
.table thead tr th:nth-child(1){
    text-align: center;
}

</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
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
   
 // $('.table tbody tr td:nth-child(3)').css('color','red')
  //$('#field-fechaAlta').val(hoy());
  
   
    
         
      
    
    
    if($('#field-fechaAlta').val()==""){
            $('#field-fechaAlta').val(hoy());
     }
    
    
    
    function hoy(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd='0'+dd
        } 

        if(mm<10) {
            mm='0'+mm
        } 
        return  dd+'/'+mm+'/'+yyyy;
    }
    
    
    
    /*
    var fechaAlta=$('#field-fechaAlta.readonly_label').html()
    fechaAlta=fechaAlta.substr(8,2)+"/"+fechaAlta.substr(5,2)+"/"+fechaAlta.substr(0,4)
    $('#field-fechaAlta.readonly_label').html(fechaAlta)
    
    var fechaModificacion=$('#field-fechaModificacion.readonly_label').html()
    fechaModificacion=fechaModificacion.substr(8,2)+"/"+fechaModificacion.substr(5,2)+"/"+fechaModificacion.substr(0,4)
    $('#field-fechaModificacion.readonly_label').html(fechaModificacion)
    */
   
    var valor=$('#field-tienda_web.readonly_label').html()
    if (valor==1) {valor="Tienda"} else {valor="Web"}
    $('#field-tienda_web.readonly_label').html(valor)
    
   
    
    
   
   //$('#tienda_web_input_box').children().html('<label><div class="radio" id="uniform-field-tienda_web-true"><span><input id="field-tienda_web-true" class="radio-uniform" type="radio" name="tienda_web" value="1"></span></div> actigdggvo</label> <label><div class="radio" id="uniform-field-tienda_web-false"><span><input id="field-tienda_web-false" class="radio-uniform" type="radio" name="tienda_web" value="0"></span></div> inactivo</label>')
    })
    </script>