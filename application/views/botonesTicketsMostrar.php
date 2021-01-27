<?php $ticketsPeriodo=$this->session->ticketsPeriodo ;
        $numTicket=$this->session->tickets;
        ?>
<!-- Boton Otro periodo -->
<?php echo form_open('tickets/seleccionarTicketsMostrar', array('role' => 'form')); ?>
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini" >
    <span class="" aria-hidden="true"></span> Seleccionar otro periodo
</button>
<?php echo \form_close(); ?> 


<!-- Boton Excel -->
<?php echo form_open('tickets/excelFactura2/pe_boka', array('role' => 'form')); ?>
<input type="hidden" name="ticket" value="<?php echo $ticket['numero'].' '.$ticket['fecha'] ?>">
<button style="display: inline;" type="submit" class="btn btn-default btn-mini" >
    <span class="glyphicon glyphicon-file" aria-hidden="true"></span> Excel
</button>
<?php echo \form_close(); ?> 


<!-- <?php echo 'posicion: '.$posicion.' sizeof($ticketsPeriodo) '.sizeof($ticketsPeriodo)?> -->
<!-- Boton Primer ticket -->
<?php echo form_open('tickets/mostrarTicket', array('role' => 'form')); ?>
<?php $i=0; //array_search($numTicket,$ticketsPeriodo);
if (sizeof($ticketsPeriodo)>0 && $posicion>0)
    {
    $primero=$i; //$ticketsPeriodo[$i];
    $disabled="";
}else {
    $primero=0;
    $disabled="disabled='disabled'";
}
?>
<input type="hidden" value="<?php echo $ticketsPeriodo[$primero] ?>" name="tickets">
<button style="display: inline;" type="submit" class="btn btn-default btn-mini" <?php echo $disabled ?>>
    <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span> Ticket Primero
</button>
<?php echo \form_close(); ?> 




    
<!-- Boton Anterior ticket -->
<?php echo form_open('tickets/mostrarTicket', array('role' => 'form')); ?>
<?php $i=$posicion; //array_search($numTicket,$ticketsPeriodo);
$i--;
if ($i>=0){
    $anterior=$i;
    $disabled="";
}else {
    $anterior="0";
    $disabled="disabled='disabled'";
}
?>
<input type="hidden" value="<?php echo $ticketsPeriodo[$anterior]  ?>" name="tickets">
<button style="display: inline;" type="submit" class="btn btn-default btn-mini" <?php echo $disabled ?>>
    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>  Ticket anterior
</button>
<?php echo \form_close(); ?>     

<!-- Boton Siguiente ticket -->
<?php echo form_open('tickets/mostrarTicket', array('role' => 'form')); ?>
<?php $i=$posicion; //array_search($numTicket,$ticketsPeriodo);
$i++;
if ($i<sizeof($ticketsPeriodo))
    {
    $siguiente=$i; //$ticketsPeriodo[$i];
    $disabled="";
}else {
    $i=sizeof($ticketsPeriodo)-1;
    $siguiente=$i;
    $disabled="disabled='disabled'";
}
?>
<input type="hidden" value="<?php echo $ticketsPeriodo[$siguiente] ?>" name="tickets">
<button style="display: inline;" type="submit" class="btn btn-default btn-mini" <?php echo $disabled ?>>
    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span> Ticket siguiente
</button>
<?php echo \form_close(); ?> 

    
<!-- Boton Último  ticket -->
<?php echo form_open('tickets/mostrarTicket', array('role' => 'form')); ?>
<?php if(sizeof($ticketsPeriodo)>0  && $posicion<sizeof($ticketsPeriodo)-1){
$i=sizeof($ticketsPeriodo)-1;

    $ultimo=$i;
    $disabled="";
}else {
    $i=sizeof($ticketsPeriodo)-1;
    $ultimo=$i;
    $disabled="disabled='disabled'";
}
?>
<input type="hidden" value="<?php echo $ticketsPeriodo[$ultimo] ?>" name="tickets">

<button style="display: inline;" type="submit" class="btn btn-default btn-mini" <?php echo $disabled ?>>
    <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>  Ticket último
</button>
<?php echo \form_close(); ?>  














