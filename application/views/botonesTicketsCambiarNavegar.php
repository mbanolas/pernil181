<?php 
//var_dump($ticket);
//var_dump($ticket2);
?>
<input type="hidden" id="base_url" value="<?php echo base_url() ?>">
<input type="hidden" id="ajax" value="mostrar/mostrarTicketCambiarAjax">
<input type="hidden" id="posicion" value="<?php echo $posicion ?>">
<input type="hidden" id="ultimo" value="<?php echo $totalNumTickets ?>">
<!-- Boton Otro periodo -->
<?php echo form_open('tickets/seleccionarTicketsMostrar', array('role' => 'form')); ?>
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini " >
    <span class="" aria-hidden="true"></span> Seleccionar otro periodo
</button>
<?php echo \form_close(); ?> 

<button style="display: inline;" type="button" class="btn btn-default btn-mini " id="primero">
    <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span> Ticket Primero
</button>

<button style="display: inline;" type="button" class="btn btn-default btn-mini" id="anterior">
    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>  Ticket anterior
</button>

<button style="display: inline;" type="button" class="btn btn-default btn-mini" id="siguiente_Ticket">
    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span> Ticket siguiente
</button>

<button style="display: inline;" type="button" class="btn btn-default btn-mini" id="ultimo_Ticket" >
    <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>  Ticket último
</button>

<?php echo form_dropdown('tickets', $ticketsPeriodo, 0,
      array('style'=>"display: inline;", 'class' =>  'btn btn-default btn-mini dropdown-toggle botonNavegador', 'id' => 'ticketsNavegar')); ?>
                

<script>




</script>







