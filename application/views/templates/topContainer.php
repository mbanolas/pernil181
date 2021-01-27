<div class="container">  

<div class="row">
    <div class="   col-xs-12 col-md-6">
        <h1 ><img class="img-responsive"  id="logoNuevo" src="<?php echo base_url('images/pernil181.png') ?>"></h1>
    </div>
    <div class="   col-xs-12  col-md-6">
        <h1 >Gestión Tickets Balanzas</h1>
        
    </div>  
</div>
  
<!-- Barra navegación -->


<div class="bs-example" style="border-bottom: 1px solid #CCCCCC; padding: 5px">

        <div class="btn-group">
            <?php echo anchor('inicio','Inicio',array('class'=>"menu btn btn-default  ",'id'=>"menuInicio")) ?>
        </div>

        <div class="btn-group">
            <?php echo anchor('upload/do_upload','Boka1',array('class'=>"menu btn btn-default ",'id'=>"menuBoka")) ?>
        </div>
        <div class="btn-group">
                <button type="button" data-toggle="dropdown" class="menu btn btn-default dropdown-toggle " id="menuCaja">Caja <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><?php echo anchor('caja/cierreCaja','Cierre Caja',array('class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuCierreCaja")) ?></li>
                <li><?php echo anchor('caja/informacionCierresCaja','Información Cierres Caja',array( 'class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuInformacionCierresCaja")) ?></li>
               <li class="divider"></li>
                <li><?php echo anchor('caja/inicializarCaja','Inicializar Caja',array(  'class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuInicializarCaja")) ?></li>
            </ul>        </div>
        <?php if ($this->session->categoria != 2) { ?>
        <div class="btn-group">
            <button type="button" data-toggle="dropdown" class="menu btn btn-default dropdown-toggle " id="menuListados">Listados <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><?php echo anchor('listados/listaProductos','Productos',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuListaProductos")) ?></li>
                <li><?php echo anchor('listados/listaFamilias','Familias',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuListaFamilias")) ?></li>
                <li><?php echo anchor('listados/listaProveedores','Proveedores',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuListaProveedores")) ?></li>
                <li><?php echo anchor('listados/listaAcreedores','Acreedores',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuListaAcreedores")) ?></li>
                <li><?php echo anchor('listados/listaClientes','Clientes',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuListaClientes")) ?></li>
            <!--    <li class="divider"></li>
                <li><a href="#">Separated link</a></li> -->
            </ul>
        </div>

        <div class="btn-group">
            <button type="button" data-toggle="dropdown" class="menu btn btn-default dropdown-toggle " id="menuTickets">Tickets <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><?php echo anchor('tickets/seleccionarTicketsMostrar','Mostrar Tickets',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuMostrarTickets")) ?></li>
                <li><?php echo anchor('tickets/seleccionaTicket','Mostrar UN Ticket',array('class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuCopiaTicket")) ?></li>
                <?php if ($this->session->categoria != 2) { ?>
                <li><?php echo anchor('tickets/seleccionarTicketsModificar','Procesado Tickets',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuModificarTickets")) ?></li>
                <li><?php echo anchor('tickets/seleccionaTicketProcesado','Mostrar UN Ticket Procesado',array('class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuCopiaTicketProsesado")) ?></li>
                 <?php } ?>
                 <li><?php echo anchor('tickets/seleccionarTicketsCambioPago','Cambio Forma Pago - Cliente Tickets Periodo',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuCambioPagoTickets")) ?></li>
                <li><?php echo anchor('tickets/seleccionarTicketCambioPago','Cambio Forma Pago - Cliente UN Ticket',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuCambioPagoTickets")) ?></li>

                <li><?php echo anchor('facturas','Generar Factura',array('class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuFacturaTickets")) ?></li>
                <!--
                <?php if ($this->session->categoria != 2) { ?>
                <li class="divider"></li>
                <li><?php echo anchor('tickets/seleccionarTicketsDiferencias','Diferencias Tickets',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuDiferenciaTickets")) ?></li>
                <?php } ?>
                -->
                </ul>
        </div>
       
        <div class="btn-group">
            <button type="button" data-toggle="dropdown" class="menu btn btn-default dropdown-toggle " id="menuTienda">Ventas Tienda <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><?php echo anchor('listados/seleccionBoka','Listado Datos Boka',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuListadoBoka")) ?></li>
                <li><?php echo anchor('listados/seleccionVentasImportes','Ventas: Importes e IVAs',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuVentasTienda")) ?></li>
                <li><?php echo anchor('listados/seleccionVentasTickets','Listado Tickets Ventas',array('class'=>"menu btn btn-default  dropdown-toggle nuevo",'id'=>"menuVentasTickets")) ?></li>
                <li><?php echo anchor('listados/seleccionTicketsProcesados','Listado Tickets Procesados',array('class'=>"menu btn btn-default  dropdown-toggle nuevo",'id'=>"menuTicketsProcesados")) ?></li>
                <li><?php echo anchor('listados/seleccionVentasProductos','Ventas: Productos',array('class'=>"menu btn btn-default  dropdown-toggle",'id'=>"menuProductosTienda")) ?></li>
                <?php if ($this->session->categoria != 2) { ?>
                <li class="divider"></li>
                <li><?php echo anchor('listados/seleccionVentasDiferenciasImportes','Diferencias Ventas',array('class'=>"nuevo menu btn btn-default  dropdown-toggle",'id'=>"menuDiferenciasTienda")) ?></li>
                <?php } ?>
            </ul>
        </div>
<?php } ?>

    <?php if ($this->session->categoria == 1) { ?>
      <div class="btn-group pull-right">
            <button type="button" data-toggle="dropdown" class="menu btn btn-default dropdown-toggle " >Archivos Boka <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><?php echo anchor('archivosBoka/write_test','Write Test',array('class'=>"menu btn btn-default  dropdown-toggle",)) ?></li>
                <li><?php echo anchor('archivosBoka/read_test','Read Test',array('class'=>"menu btn btn-default  dropdown-toggle",)) ?></li>
                <li><?php echo anchor('archivosBoka/filenames_test','Nombres Archivos',array('class'=>"menu btn btn-default  dropdown-toggle",)) ?></li>
                <li><?php echo anchor('archivosBoka/delete_test','Borrar Archivos',array('class'=>"menu btn btn-default  dropdown-toggle",)) ?></li>

                <li class="divider"></li>
                <li><?php echo anchor('archivosBoka/write_test','Write Test',array('class'=>"menu btn btn-default  dropdown-toggle",)) ?></li>
            </ul>
        </div>
    <?php } ?>
    
        <div class="btn-group pull-right">
            <?php echo anchor('inicio/logout','<span class="glyphicon glyphicon-log-out"> </span>  Salir',array('class'=>"menu btn btn-default ",'id'=>"menuSalir")) ?>
        </div>
        
</div>
 <br />
</div>


