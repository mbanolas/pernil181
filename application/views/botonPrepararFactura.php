<?php echo form_open('tickets/excelFactura2/pe_boka'); ?>
<input type="hidden" name="ticket" value="<?php echo $ticket['numero'] ?>">
<div class="row">
    <div class="col-md-6" style="display: inline;text-align: center;">
        <button id="generarFactura" style="display: inline;text-align: center;" type="submit" class="btn btn-primary btn-mini" >
            <span class="glyphicon glyphicon-print" aria-hidden="true">&nbsp;&nbsp;</span> Generar Factura
        </button>
    </div>
</div>

<?php echo form_close('<br /><br />'); ?>

