

<h3>Seleccionar periodo Ventas</h3>

<p>
    

    <?php echo form_open('listados/listaBoka',array('role'=>'form')) ; ?>
<ul style="list-style:none;" >
    <li>
        <?php echo form_radio('hoy','hoy',true,'id="hoy"').' Hoy'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','ayer',false,'id="ayer"').' Ayer'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Semana actual',false,'id="semanaActual"').' Semana actual'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Semana_anterior',false,'id="semanaAnterior"').' Semana anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Mes actual',false,'id="mesActual"').' Mes actual'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Mes anterior',false,'id="mesAnterior"').' Mes anterior'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Año actual',false,'id="añoActual"').' Año actual'; ?>
    </li>
    <li>
        <?php echo form_radio('hoy','Año anterior',false,'id="añoAnterior"').' Año anterior'; ?>
    </li>
    
</ul>
    
    <?php
    
    
    $data = array(
        'name'          => 'inicio',
        'id'            => 'inicio',
        'value'         => '',
        'type'=>'date',
       
        );

    echo  "Desde fecha: ".form_input($data);
   
?>
</p>
<p>
<?php
    $data = array(
        'name'          => 'final',
        'id'            => 'final',
        'value'         => '',
        'type'=>'date'
        );

    echo   "Hasta fecha: ".form_input($data);
    ?>
    
</p>
  
    
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
  <span class="" aria-hidden="true"></span> Listar datos Boka
</button>

<?php
    echo \form_close();
?>
    
 <?php echo form_open('listados/resumenBoka',array('role'=>'form')) ; ?>
 <input type="hidden" name="inicio" value="" class="inicioR">
 <input type="hidden" name="final" value="" class="finalR">

  
 
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
  <span class="" aria-hidden="true"></span> Resumen Ventas Tienda
</button>

<?php
    echo \form_close();
?>
 
 <?php echo form_open('listados/resumenProductos',array('role'=>'form')) ; ?>
 <input type="hidden" name="inicio" value="" class="inicioR">
 <input type="hidden" name="final" value="" class="finalR">

  
 
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
  <span class="" aria-hidden="true"></span> Resumen Productos Vendidos
</button>

<?php
    echo \form_close();
?>
 
 
 <?php echo form_open('inicio',array('role'=>'form')) ; ?>
 
<button style="display: inline;" type="submit" class="btn btn-default btn-mini" >
  <span class="" aria-hidden="true"></span> Menú Principal
</button>
 
  <?php
    echo \form_close();
?>  
 
 <script>
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTienda').addClass('btn-primary');
  $('#menuVentasTienda').addClass('btn-primary');  
})
</script>

