
<!-- Boton Otro periodo -->
<?php echo form_open('tickets/seleccionaTicket', array('role' => 'form')); ?>
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
    <span class="" aria-hidden="true"></span> Seleccionar otro ticket
</button>
<?php echo \form_close(); ?> 


<!-- Boton Excel -->
<?php echo form_open('tickets/excelFactura2/pe_boka', array('role' => 'form')); ?>
<input type="hidden" name="ticket" value="<?php echo $ticket['numero'].' '.$ticket['fecha'] ?>">
<button style="display: inline;" type="submit" class="btn btn-default btn-mini" >
    <span class="glyphicon glyphicon-file" aria-hidden="true"></span> Excel
</button>
<?php echo \form_close(); ?> 

<!-- Boton Otro periodo -->
<?php echo form_open('tickets/seleccionaTicketEliminar', array('role' => 'form')); ?>
<button style="display: inline;" type="submit" class="btn btn-danger btn-mini " id="eliminarTicket">
    <span class="" aria-hidden="true"></span> Eliminar este ticket
</button>
 <?php echo \form_close(); ?> 

<script>

</script>

















