<div style='height:15px;'></div>  

<div class="container">
   
   
   
       
		<?php echo $output; ?>
        
</div>

<style>
    .derecha{
        text-align: right;
    }
    
</style>

<script>
$(document).ready(function () {
  $(window).load(function(){
      $('span.derecha').parent().addClass('derecha') 
      $('td.derecha').parent().parent().parent().children().children().children('th:nth-child(4)').addClass('derecha') 
     // $('thead').children('tr').children('th:nth-child(3)').addClass('derecha') 
  }) 
  $('td span.derecha').addClass('derecha')  
  
  
  
  
  /*
  var fecha=$('#field-fecha_nacimiento').html()
  if (typeof(fecha) != "undefined"){
  var fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
  if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_nacimiento').html(fechaEuropea)
  }
  
  
 fecha=$('#field-fecha_alta').html()
 if (typeof(fecha) != "undefined"){
 fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
 if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_alta').html(fechaEuropea)
 }
 
 fecha=$('#field-fecha_modificacion').html()
 if (typeof(fecha) != "undefined"){
 fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
 if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_modificacion').html(fechaEuropea)
 }
 
 
 
  fecha=$('#field-fecha_baja').html()
  if (typeof(fecha) != "undefined"){
 fechaEuropea=fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)
 if(fechaEuropea=='00/00/0000') fechaEuropea=""
  $('#field-fecha_baja').html(fechaEuropea)
  }
  
  var telefono=$('#field-telefono_1').html()
  if (typeof(fecha) != "undefined"){
  telefono=telefono.replace(/ /g,"")
  if(telefono.length==9)
      telefono=telefono.substr(0,3)+' '+telefono.substr(3,3)+' '+telefono.substr(6,3);
  $('#field-telefono_1').html(telefono)
  }
  
  telefono=$('#field-telefono_2').html()
  if (typeof(fecha) != "undefined"){
  telefono=telefono.replace(/ /g,"")
  if(telefono.length==9)
      telefono=telefono.substr(0,3)+' '+telefono.substr(3,3)+' '+telefono.substr(6,3);
  $('#field-telefono_2').html(telefono)
  }
  */
  $("#field-inicio_1, #field-inicio_2,#field-final_1,#field-final_2").attr("placeholder", "hh:mm").blur();
  $("#field-precio_trimestre").attr("placeholder", "Importe en €").blur();
  $("#field-precio_curso").attr("placeholder", "Importe en €").blur();
  
  $('#field-fecha_modificacion').attr('disabled','disabled')
  
})
</script>