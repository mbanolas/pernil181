<?php
$generarCatalogo="Generar Catálogo PDF ".ucwords($modelo);
?>
<div class="container"><br />
<h3>Marcas del Catálogo <?php echo ucwords($modelo) ?></h3>

<?php echo form_open('catalogo/generarCatalogoDownLoad/'.$modelo, array('class'=>"form-horizontal", 'role'=>"form")); ?>
<div class="row">
<div col-md-sm-offset-3 class="col-sm-12">
            Idioma: 
            <input type="radio" name="idioma[]"  value="es" checked="checked" style="margin:10px" /><img class="img_bandera" src="<?php echo base_url() ?>images/es_flag.jpg" alt="No existe imagen" height="20"> Castellano
            <input type="radio" name="idioma[]"  value="en"  style="margin:10px" /><img class="img_bandera" src="<?php echo base_url() ?>images/en_flag.png" alt="No existe imagen" height="20"> Inglés
            <input type="radio" name="idioma[]"  value="fr"  style="margin:10px" /><img class="img_bandera" src="<?php echo base_url() ?>images/fr_flag.png" alt="No existe imagen" height="20"> Francés
    </div> 
</div>
<br>
<div class="row">
    <div class="col-sm-3" >
              <button  type="submit" class="btn btn-success prepararCatalogo" name="prepararCatálogo">
                    <?php echo $generarCatalogo ?>
              </button>
    </div>
    <?php if ($modelo=="profesionales"){ ?>
        <div class="col-sm-9">
                Productos en marca ordenador por: 
                <input type="radio" name="orden[]"  value="cat_nombre" checked="checked" style="margin:10px" />Nombre
                <input type="radio" name="orden[]"  value="cat_referencia"  style="margin:10px" />Referencia
                <input type="radio" name="orden[]"  value="cat_orden"  style="margin:10px" />Orden en marca
        </div>
    <?php } ?>
            
        <?php if ($modelo=="tienda"){ ?>
    <div class="col-sm-9">
            Productos en marca ordenador por: 
            <input type="radio" name="orden[]"  value="cat_nombre" checked="checked" style="margin:10px" />Nombre
            <input type="radio" name="orden[]"  value="cat_referencia"  style="margin:10px" />Referencia
    </div>
    <div col-md-sm-offset-3 class="col-sm-9">
            Precio tarifa: 
            <input type="radio" name="tarifa[]"  value="pvp" checked="checked" style="margin:10px" />PVP (con IVA)
            <input type="radio" name="tarifa[]"  value="base"  style="margin:10px" />Base (sin IVA)
    </div> 
    
       <?php } ?>    
</div>
<div class="col-sm-2_" >    
    <img style='display:inlinen;' class="img-responsive ajax-loader2 hide"   src="<?php echo base_url('images/ajax-loader.gif') ?>">      	
</div>

<br>
<div class="row">
    <div class="col-sm-6 ">
    <?php 
    $data = array(
        'name'          => 'todos',
        'id'            => 'todos',
        'value'         => 'accept',
        //'checked'       => TRUE,
        'style'         => 'margin-right:5px'
);
    echo form_checkbox($data); echo 'Todos'; 
    ?>
    </div>
</div>
<br>
<div class='row'>
    <div class="form-group_">
        <div class="col-sm-6 ">
        <?php 
        $numOrden=0;
        $numOrdenUltimo=0;
        for($k=0;$k<=count($marcas)/2;$k++){
            $disabled="";
            $marcado=false;
            if($n[$k]==0) {
                $disabled='disabled';
                $marcado=false;
                $numOrden="";
            }
            else{
                $numOrdenUltimo++;
                $numOrden=$numOrdenUltimo;
            }
            $intK=($k);
            //echo form_checkbox($ids[$k], 'marca', $marcado, $disabled.' style="margin-right:5px"');  echo $marcas[$k].': '.$n[$k].' productos<br>'; 
            echo form_checkbox('marcas[]', $ids[$k], $marcado, $disabled.' style="margin-right:5px"'); 
            echo form_input("ordenMarcas[]",$numOrden,$disabled.' style="width:26px"');
            echo " Dto: ";
            echo form_input('dto_actual[]',$descuentos[$intK],$disabled.' k="'.$intK.'"  style="width:40px"'); 
            echo " % - ";
            echo $marcas[$k].': '.$n[$k].' productos<br>'; 
            
            echo '<input type="hidden" name="dto_es[]" k="'.$intK.'" value="'.$descuentos[$intK].'" >';
            echo '<input type="hidden" name="dto_en[]" k="'.$intK.'" value="'.$descuentos_en[$intK].'" >';
            echo '<input type="hidden" name="dto_fr[]" k="'.$intK.'" value="'.$descuentos_fr[$intK].'" >';
            
        }
        ?>
        </div>
        <div class="col-sm-6 ">
        <?php 
        for($k=count($marcas)/2+1;$k<count($marcas);$k++){
            $disabled="";
            $marcado=true;
            if($n[$k]==0) {
                $disabled='disabled';
                $marcado=false;
                $numOrden="";
            }
            else{
                $numOrdenUltimo++;
                $numOrden=$numOrdenUltimo;
            }
           $intK=($k);
            //echo form_checkbox($ids[$k], 'marca', $marcado, $disabled.' style="margin-right:5px"');  echo $marcas[$k].': '.$n[$k].' productos<br>'; 
            echo form_checkbox('marcas[]', $ids[$k], $marcado, $disabled.' style="margin-right:5px"'); 
            echo form_input('ordenMarcas[]',$numOrden,$disabled.' posicion="'.$intK.'"   style="width:26px"'); 
            echo " Dto: ";
            echo form_input('dto_actual[]',$descuentos[$intK],$disabled.' k="'.$intK.'" style="width:40px"'); 
            echo " % - ";
            echo $marcas[$k].': '.$n[$k].' productos<br>';
            
            echo '<input type="hidden" name="dto_es[]" k="'.$intK.'" value="'.$descuentos[$intK].'" >';
            echo '<input type="hidden" name="dto_en[]" k="'.$intK.'" value="'.$descuentos_en[$intK].'" >';
            echo '<input type="hidden" name="dto_fr[]" k="'.$intK.'" value="'.$descuentos_fr[$intK].'" >';
            
        }
        
        ?>
        <?php foreach($marcas as $k=>$v){ ?>
            <input type="text" class="hide" id="<?php echo $ids[$k] ?>" value="<?php echo $n[$k] ?>">
        <?php } ?>   
        </div>
    </div>
</div>
<br>
<div class='row'>
    <div class="col-sm-3">
              <button type="submit" class="btn btn-success prepararCatalogo " name="prepararCatálogo">
              <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> 
              <?php echo $generarCatalogo ?></button>
    </div>
 <div class="col-sm-2" >    
    <img style='display:inlinen;' class="img-responsive ajax-loader2_ hide"   src="<?php echo base_url('images/ajax-loader.gif') ?>">      	
</div>
</div>
<?php echo form_close(); ?>
</div>
<script>

$(document).ready(function(){
    
  var numOrdenUltimo=<?php  echo $numOrdenUltimo; ?>  
  
  $('input#todos').removeAttr('checked');
  $('input[name="marcas[]"]').removeAttr('checked');
  
  $('input[name="idioma[]"]').click(function(){
      var idioma=$(this).val();
      //alert('idioma '+idioma );
        $('input[name="dto_actual[]"]').each(function(index) {
            //console.log($(this).attr('k'))
            var k=$(this).attr('k')
        if(idioma=="es"){
            var k=$(this).attr('k');
            $(this).val($('input[name="dto_es[]"][k="'+k+'"]').val())
        }
        if(idioma=="en"){
            var k=$(this).attr('k');
            $(this).val($('input[name="dto_en[]"][k="'+k+'"]').val())
        }
        if(idioma=="fr"){
            var k=$(this).attr('k');
            $(this).val($('input[name="dto_fr[]"][k="'+k+'"]').val())
        }
        //console.log($(this).val())
    })
    })
  
  $('button[type="submit"]').click(function(e){
      
      var totalProductos=0
     $($('input[name="marcas[]"]')).each(function() {
         if($(this).prop('checked')==true) {
            var marca=$(this).val()
            totalProductos+=Number($('#'+marca).val())
           // console.log(totalProductos)
            }
        })
     if(totalProductos==0) {
            e.preventDefault()
            //alert("Se han seleccionado "+totalProductos+" productos.\nDebe seleccionar un máximo de 80.")
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html("No se ha seleccionado<strong>ninguna marca</strong>.<br><br>Señale, al menos una marca");
            $("#myModal").modal() 
            return;
        }   
     if(totalProductos>=60) {
            e.preventDefault()
            //alert("Se han seleccionado "+totalProductos+" productos.\nDebe seleccionar un máximo de 80.")
            $('#myModal').css('color','red')
            $('.modal-title').html('Información')
            $('.modal-body>p').html("Se han seleccionado <strong>"+totalProductos+"</strong> productos.<br><br>Preparar pdf con un máximo de 60 productos o menos si diera error (pantalla en blanco).<br>Despuén unir los pdf con un programa externo. Por ejemplo:<br><br><a target='_blank' href='http://www.ilovepdf.com/es/unir_pdf'>http://www.ilovepdf.com/es/unir_pdf</a>")
            $("#myModal").modal() 
            return;
        }
        var idioma=""
      $($('input[name="idioma[]"]')).each(function() {
          
          if($(this).prop('checked')==true){
              idioma=$(this).val()
              //console.log(idioma)
        }
      })
      $($('input[name="marcas[]"]')).each(function() {
          if($(this).prop('checked')==true) {
              //console.log($(this).val());
              var id=$(this).val();
              var dto=$(this).next().next().val()
              //console.log(dto);
              
              $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/catalogo/grabarDescuento", 
            data: {id:id, dto:dto,idioma:idioma },
            success: function(datos){
                //alert(datos);
                 },
            error: function(){
                alert("Error en el proceso grabar descuentos en catálogo. Informar");
            }
        })
          }
      })
  })
      
  
  
  $($('input[name="marcas[]"]')).each(function() {
      
     if($(this).prop('checked')==false) {
          $(this).next().attr("value","") 
          $(this).next().attr("disabled","disabled") 
        }
    })
  
  $('input[name="todos"]').click(function(){
      if($(this).prop('checked')==true){
          totalProductos=0
          $($('input[name="marcas[]"]')).each(function() {
               if(!$(this).prop('disabled')) {
                   $( this ).attr('checked','checked');
                    numOrdenUltimo++
                    $(this).next().attr("value",numOrdenUltimo)
                    $(this).next().removeAttr("disabled")
                    
                }
                else{
                    $(this).next().attr("value","") 
                    $(this).next().attr("disabled","disabled")
                    
                }
          });
           //$('input[value="marca"]').attr('checked','checked');
      }
      else {
         $('input[name="marcas[]"]').removeAttr('checked');
         $('input[name="marcas[]"]').next().attr("value","")
         $('input[name="marcas[]"]').next().attr("disabled","disabled") 
      }
      
  })  
  
  $('input[type="checkbox"]').click(function(){
      if($(this).prop('checked')==true){
          
          numOrdenUltimo++
          $(this).next().attr("value",numOrdenUltimo)
          $(this).next().removeAttr("disabled")
      }
      else{
         //alert('NO checked') 
         $(this).next().attr("value","")
         $(this).next().attr("disabled","disabled") 
      }
  })
    
  $('.prepararCatalogo_').click(function(e){
    // e.preventDefault()
    // alert($('form').serialize())
    $('img.ajax-loader2').removeClass('hide')
    
    /*
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/catalogo/generarCatalogoDownLoad",
            data: $('form').serialize(),
            success: function(datos){
               alert(datos)
               //window.open(datos)
               //var datos=$.parseJSON(datos)
               $('img.ajax-loader2').addClass('hide')
                
            },
            error: function(){
                alertaError("Información importante","Error en el proceso grabado lineas albarán. Informar");
            }
        })   
        */
    
  }) 
})
</script>