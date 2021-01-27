<br />

<h3>Registrar Pagos Facturas Proveedores </h3>
<?php $pedidos[0]='Seleccionar un pedido'; 
$facturas[0]='Seleccionar una factura';
?>
<input type="hidden" value="" id="num_albaranes">             
  <div class="form-horizontal">
      <div class="row">
          <div class="col-sm-3">
            <label  class="col-sm-12 form-control-label">Filtro proveedores/acreedores </label>
            <div class="input-group">
                <input type="text" id="buscarProveedores" class="form-control clearable searchable-input input-sm" placeholder="Buscar" name="srch-term" >
                <div class="input-group-btn " >
                    <button class="btn btn-default btn-sm" id="buscarProveedor" ><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
       
          <div class="col-sm-3">
            <label for="proveedor" class="col-sm-12 form-control-label">Proveedor/Acreedor: </label>
            <?php echo form_dropdown('proveedor', $proveedoresAcreedores, '', array('width'=>'100%','id' => 'proveedor', 'class' => ' form-control input-sm ')); ?>
        </div>
          
          <div class="col-sm-3" id='pedidosIniciales' >
            <label for="factura" class="col-sm-12 form-control-label">Facturas proveedor/acreedor: </label>
            
            <?php echo form_dropdown('factura', $facturas, '', array('disabled'=>'disabled','width'=>'100%','id' => 'factura', 'class' => ' form-control input-sm ')); ?>
        </div>
      </div>
   
      
     
  </div>
<hr>     
  <div class="hide" id="datosFactura">  
<form class="form-horizontal">
  <div class="form-group">
    <label class="control-label col-sm-2" >Proveedor</label>
    <div class="col-sm-10">
      <label class="control-label col-sm-10" id="infoProveedor"></label>
    </div>
  </div>
   
    <div class="form-group">
    <label class="control-label col-sm-2" >Núm Factura</label>
    <div class="col-sm-5">
      <label class="control-label col-sm-5 text-left" id="infoNumFactura"></label>
    </div>
  </div>
    
    <div class="form-group">
    <label class="control-label col-sm-2" >Fecha factura</label>
    <div class="col-sm-3">
      <label class="control-label col-sm-5 text-left" id="infoFechaFactura"></label>
    </div>
  </div>
    
    <div class="form-group">
    <label class="control-label col-sm-2" >Importe total</label>
    <div class="col-sm-3">
      <label class="control-label col-sm-5 text-left" id="infoImporteTotal"></label>
    </div>
  </div>
    
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">Pagada en fecha</label>
    <div class="col-sm-3"> 
      <input type="date" class="form-control" id="infoFechaPago" placeholder="Entrar fecha en que se ha pagado">
    </div>
  </div>
  
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <a  class="btn btn-default" id="registrarPago">Registrar Fecha Pago</a>
      <a  class="btn btn-default" id="cancelar">Cancelar</a>
    </div>
  </div>
</form>
</div>



   

<br>

<!-- style -->
<style>
    
    .form-horizontal .control-label {
    text-align: left;
}
    label.derecha,div.derecha {
        text-align: right;
    }
    
    label.derecha.aj {
        padding-right: 0px;;
    }
    
    .form-control-label span{
        font-weight: normal;
        font-size: 16px;
    }
    
    select.input-sm{
        height: 25px;
        
    }
    select#producto{
        width:100%;
    }
    .input-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
    input#buscarProductos{
        width:100%;
    }
    .reset{
       padding-left: 28px;
       padding-top:  4px;
    }
    
    .anadir{
       padding-left: 28px;
       padding-top:  20px;
    }
    
    .eliminar{
       padding-left: 28px;
       padding-top:  20px;
    }
    
    input.input-sm{
        border: 1px solid #cccccc;
    }
    hr{
        margin-top:5px;
    }
    .titulo{
        font-weight: bold;
    }
    .componentesCantidadVer,cantidadComponente{
        text-align: right;
    }
    
    #addLinea{
        padding-top:20px;
    }
     
    #totalCantidad,#importeTotal{
        font-weight: bold;
    }
    .col-sm-1b{
        position:relative;
        min-height:1px;
        paddign-right:0px;
        padding-left:0px;
    }
    @media (min-width:760px){
        .col-sm-1b{
            float:left;
        }   
        .col-sm-1b{
           width:2%; 
        }
        .col-sm-1c{
            float:left;
        }   
        .col-sm-1c{
           width:5%; 
        }
    }
    
    a#buscar{
        padding-left:5px;
    }
    
    p.cantidad{
        text-align: right;
    }
    .und{
        padding-right: 30px;
    }
    
    
    
    
    
     .btn-sm {
    height: 25px;
    width:100%;
    padding: 0px 10px;
    }
    
    #fechaFactura,#otrosCostes,#numFactura{
        border: 1px solid #cccccc;
        margin-left:5px;
        margin-right:20px;
        height: 25px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    #totalFactura{
        margin-left:10px;
    }
   
    #nuevaFactura{
        border-bottom:  2px solid lightgray;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    
    input.sinPedido{
        
        background-color:lightpink;
    }
    input.sinAlbaran{
        
        background-color:lightcoral;
    }
</style>

<script>
        
$(document).ready(function () {
    
    $('#buscarProveedores').focus()
    
    $('#cancelar').click(function(){
      window.location.href = "<?php echo base_url() ?>" + "index.php/inicioMenu";

    })
    
   $('#registrarPago').click(function(){
       var id_factura=$('#factura').val()
       var fecha_pago=$('#infoFechaPago').val()
       if(!fecha_pago){
      $("#myModal").css('color','red')
        $('.modal-title').html('Información') 
        $('.modal-body>p').html("No ha indicado la fecha de pago")
        $("#myModal").modal() 
                    return false;
                }
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/registrarPagoFactura",
            data: {id_factura:id_factura,fecha_pago:fecha_pago},
            success: function(datos){
               
               
                
               var datos=$.parseJSON(datos)
                
                $('#infoProveedor').html('')
                $('#infoNumFactura').html('')
                $('#infoFechaFactura').html('')
                $('#infoImporteTotal').html('')
                $('#infoFechaPago').val('')
                $('#factura').val(0)
                
                
                $('#datosFactura').addClass('hide')
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso registro Pago Factura. Informar");
            }
        })    
   })
   

   $('#factura').change(function(){
         var id_factura=$(this).val()
         $('#infoProveedor').html('')
        $('#infoNumFactura').html('')
        $('#infoFechaFactura').html('')
        $('#infoImporteTotal').html('')
        $('#infoFechaPago').html('')
        
         $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getDatosFactura",
            data: {id_factura:id_factura},
            success: function(datos){
               
               
              
               var datos=$.parseJSON(datos)
              
                $('#infoProveedor').html(datos['nombre'])
                $('#infoNumFactura').html(datos['numFactura'])
                $('#infoFechaFactura').html(datos['fecha'])
                $('#infoImporteTotal').html(datos['totalFactura']+' €')
                var fecha=datos['fechaPago']
                 var fecha_pago
                if(fecha==='01/01/1970') fecha_pago='0000-00-00'
                else fecha_pago=fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2);
                
                $('#infoFechaPago').val(fecha_pago)
                $('#datosFactura').removeClass('hide')
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso preparación Factura proveedor. Informar");
            }
        })    
       
   })
   
    
  
   
   var previous
   $("select#proveedor").on('focus', function () {
        // Store the current value on focus and on change
        previous = this.value;
    }).change(function() {
        // Do something with the previous value after the change
        if(cambios) {
           $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html('Datos factura NO registrados.<br>Cancelar factura para cambiar a otra.')
            $("#myModal").modal() 
            $(this).val(previous)
           return false
       }
       cambioProveedor()
    });
   
   
   function cambioProveedor(){
       $('#datosFactura').addClass('hide')
       $('#otrosDatosFacura').addClass('hide')
       $('#nuevaFactura').addClass('hide')
       var proveedor=$('#proveedor').val()
       
       if(proveedor>0) {
           //leer datos de los pedidos del proveedor seleccionado
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/compras/getFactura",
            data: {proveedor:proveedor, tipo:true},
            success: function(datos){
               // alert (datos)
               var datos=$.parseJSON(datos)
                $('select#factura').children('option').remove()
                
                
                var option0='No existen facturas'
               if(Object.keys(datos['options']).length>0) option0='Seleccionar una factura'
                $('select#factura').append('<option value="0">'+option0+'</option>')
                $.each(datos['options'], function(index, value){
                 var option='<option  value="'+index+'">'+value+' € </option>'
                 $('select#factura').append(option)
                  
             })
            $('#factura').removeAttr('disabled')
                  
            },
            error: function(){
                alertaError("Información importante","Error en el proceso consultar facturas. Informar");
            }
        })        
       }
       if(proveedor==0){
           // $('#pedidosIniciales').html(pedidosIniciales)
           $('#albaran').attr('disabled','disabled')
       
       }
   }
   
 
   
   
   $('input.searchable-input').keyup(function(){
    if($(this).val()){
        $(this).css('border-color','#444')
        $(this).css('border-style','dashed')
        $(this).css('color','red')
    }
    else{
        $(this).css('border','1px solid #ccc')  
        $(this).css('color','black')
    }
    }) 
    
     // CLEARABLE INPUT
        function tog(v){return v?'addClass':'removeClass';} 
        var nombreId
        
        $(document).on('input', '.clearable', function(){
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
            $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function( ev ){
            nombreId=$(this).attr('id')
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
            $(this).css('border','1px solid #ccc')  
            if(nombreId=='buscarProveedores')
                filtroProveedores(" ",'proveedor')
            if(nombreId=='buscarProductos')
                filtroProductos(" ",'producto')
            
        });

    //filtrado proveedores
    $('#buscarProveedores').click(function(ev){
        $(this).val('')
        filtroProveedores("",'proveedor')
    }).keydown(function(ev){
        if ( ev.which == 13 || ev.which == 9) {
            ev.preventDefault();
            var filtro=$(this).val()
            filtroProveedores(filtro,'proveedor')
            $(this).css('border','1px solid #ccc')  
            $(this).css('color','black')
            $('select#proveedor').focus()
        }
    })
    
    
    $('#buscarProveedor').click(function(e){
        var filtro=$('#buscarProveedores').val()
        filtroProveedores(filtro,'proveedor')
        $('select#proveedor').focus()
    })
    
    function filtroProveedores(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/compras/getProveedoresAcreedoresFiltrados", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            //alert(datos)
            var datos=$.parseJSON(datos);
            
             $('select#'+id+' option').remove();
             $('#'+id).append('<option value="0">Seleccionar un proveedor / acreedor</option>')
             $.each(datos['proveedores'], function(index, value){
                 $('#'+id).append('<option value="'+value['id_proveedor']+'">'+value['nombre']+'</option>')
             })
             $('#buscarProveedores').css('color','black')
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
   
   
   
   
  
   
  
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    
     
   
    
    
    
     
    
    
   
   
    
  
    
  
    
    
    
    
      
    
    
   
   
   
   window.onbeforeunload=confirmExit
     
     function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han registrado.'
           
        }
    }
    
    
   
    
})
</script>