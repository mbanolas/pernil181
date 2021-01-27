<div class="container">
<br>
<h3>Bienvenido a la aplicación</h3>
<h3><?php echo 'Versión PHP: '.phpversion().' - Versión CI: '.CI_VERSION; ?> (<span id='pantalla'></span>)</h3>

<h3 id="nombre"><?php echo $this->session->nombre;; ?></h3>


<h3><?php echo $this->session->tipoUsuario;?></h3>
<br><br>
<h3>Esta aplicación se ha transferido a otro servidor</h3>
<h3>Utilizar mismo nombre de usuario y contraseña</h3>
<h3><a href="https://olivaret.com/pernil181" >https://olivaret.com/pernil181 </a></h3>
<!--
<h2>Programa con versión php 7.2 en periodo de PRUEBAS (producción)</h2>
<h2>En caso de detectar algún error informar a mbanolas@gmail.com indicando la acción realizada y un pantallazo del reporde del error</h2>

<hr>
-->
<div class='hide' style="color: red">
<h3>Atención:</h3><h3>Esta aplicación está en fase de desarrollo, por lo que se pueden producir errores debido a apartados aún no implementados y/o no previstos.</h3>
<h3>Gracias por vuestra consideración.</h3>
</div>
