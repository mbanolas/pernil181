<style>
  h2{
    color:red;
  }
  tr td{
    text-align:left;
    padding-left:4px;
  }
  tr th{
    width: 30px;
    padding-right:4px;
  }
  table{
    border: solid 1px blue;
    background-color:lightblue;
    
  }
</style>
<h3>Bienvenido a la aplicación</h3>
<h3>Versión 4.2</h3>
<h5><?php echo 'Versión PHP: '.phpversion().' - Versión CI: '.CI_VERSION; ?> - Pantalla: <span id='pantalla'> </span> x <span id='pantalla_alto'></span></h5>
<!-- <?php if( $this->session->categoria ==1 ){ ?>
  <h3 id="categoria"><?php echo $password; ?></h3>
<?php } ?> -->
<h3 id="nombre"><?php echo $this->session->nombre;; ?></h3>


<h3><?php echo $this->session->tipoUsuario;?></h3>
<!--
<h2>Programa con versión php 7.2 en periodo de PRUEBAS (producción)</h2>
<h2>En caso de detectar algún error informar a mbanolas@gmail.com indicando la acción realizada y un pantallazo del reporde del error</h2>

<hr>
-->

<div class='hide' style="color: red">
<h3>Atención:</h3><h3>Esta aplicación está en fase de desarrollo, por lo que se pueden producir errores debido a apartados aún no implementados y/o no previstos.</h3>
<h3>Gracias por vuestra consideración.</h3>
</div>

<script>





$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuInicio').addClass('btn-primary');

  $(window).on('resize', function(){
      var win = $(this); //this = window
      $('#pantalla').html(win.width())
      $('#pantalla_alto').html(win.height())
      var ancho=$(window).width()
      var alto=$(window).height()
      var alto=$(window).height()
      setTimeout(
      $.ajax({
              type: "POST",
              url: "<?php echo base_url()?>"+"index.php/"+"inicio/sizePantallaCambiada",
              data: {ancho:ancho,alto:alto},
              success: function(datos){
                  //alert(datos);
                  var datos=$.parseJSON(datos);
                  //alert(datos);

          },
              error: function(){
                    alert('Error proceso sizePantallaCambiada. Informar ');
              }
          }),
          30000);
        
      //informarAnchoPantalla()
      // setTimeout(informarAnchoPantalla(), 30000);
  });
  informarAnchoPantalla()

  function informarAnchoPantalla(){
    var ancho=$(window).width()
    var alto=$(window).height()
      //ajax para enviar información con ancho pantalla
    $.ajax({
              type: "POST",
              url: "<?php echo base_url()?>"+"index.php/"+"inicio/sizePantallaEmail",
              data: {ancho:ancho,alto:alto},
              success: function(datos){
                  // alert(datos);
                  var datos=$.parseJSON(datos);
                  // alert(datos);
          },
              error: function(){
                    alert('Error proceso inicio. Informar ');
              }
          });
        }
})  
</script>

<script>
$(document).ready(function(){
 var url="<?php echo base_url() ?>"
/* 
  navigator.serviceWorker.register('service-worker.js').then(registration => {
  registration.pushManager.subscribe({userVisibleOnly: true}).then(subscription => {
    registration.showNotification('Hola Mundo');
  })
})
*/
// navigator.serviceWorker.register('<?php echo base_url() ?>service-worker.js').then(registration => {
//   registration.pushManager.subscribe({userVisibleOnly: true}).then(subscription => {
//     console.log(subscription.endpoint);
//   })
// })
<?php if( $this->session->categoria !=6 ){ ?>
    $('#myModal').css('color','blue')
    $('.modal-title').html('Consejo')
    $('.modal-body>p').html('El mantener los stocks actualizados proporciona valiosa información.<br><br> '
        + 'Actualmente existen el siguiente número de <strong>códigos Báscula activos y con control de stocks</strong>, que en total tienen:<br>'
        + '<table><tr><td>Stocks 0: </td><th><?php echo $numCero; ?></th></tr><br>'
        + '<tr><td>Stocks negativos: </td><th><?php echo $numMenorCero; ?></th></tr></table>'
        +' <a href="<?php echo base_url() ?>index.php/stocks/productosStock0oMenor">Bajar información</a><br><br>'
        + 'Se <strong>excluyen los productos</strong>:<br>'
        + '- con código de Báscula 0,<br>'
        + '- cuyo nombre contiene "COMPRA",'
        + '<br>- cuyo nombre contiene "plato",'
        + '<br>- cuyo nombre contiene "bandeja",'
        + '<br>'

        + '<br>Revisar el stock y/o considerar si algún producto se pueden descatalogar o dejar de controlar el stock.'
        +' <br><br>Para examinar los stocks: <br>'
        +' <a href="<?php echo base_url() ?>index.php/gestionTablas/stocks_totales">Ir a tabla Stocks</a>'
        +' <br><br>Para obtener información de dichos productos: <br>'
        +' <a href="<?php echo base_url() ?>index.php/stocks/productosStock0oMenor">Bajar información</a>'
        +' <?php if(!$fechaActualizacionStocksMinimos) { ?>'
        +' <hr>'
        +' <p style="font-weight:bold;border-radius:10px;border:5px solid red;padding-top:5px;padding-bottom:5px;text-align: center;background-color:lightgreen;color:red;"><a style="color:red;" href="<?php echo base_url() ?>index.php/stocks/stocksMinimos">Los stocks mínimos no están actualizados'
        +' <br>Pulsar para actualización'
        +' </a></p><?php } ?>'
        				
							
        +' <?php if(!$fechaActualizacionStocksMinimos) { ?>'
        +' <hr>'
        +' <p style="font-weight:bold;border-radius:10px;border:5px solid red;padding-top:5px;padding-bottom:5px;text-align: center;background-color:lightgreen;color:red;"><a style="color:red;" href="<?php echo base_url() ?>index.php/stocks/stocksMinimos">Los stocks mínimos no están actualizados'
        +' <br>Pulsar para actualización'
        +' </a></p><?php } ?>'
        )
    $("#myModal").modal()  

    $('#pantalla').html(window.innerWidth)    
    $('#pantalla_alto').html(window.innerHeight)    
      <?php  } ?>


var nombre=$('#nombre').html()
   
  $.ajax({
      type: 'POST',
      url: "<?php echo base_url() ?>"+"index.php/inicio/fechaMovimientoWeb", 
      data: {nombre:nombre,ancho_pantalla:window.innerWidth},
      success: function(datos){
      //var d=$.parseJSON(datos)
      //alert(datos)
        },
       error: function(){
                alert("Error en el proceso. Informar");
       }
  })
  
})
</script>
