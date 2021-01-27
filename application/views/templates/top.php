<style>
.blink {
      /* animation: blinker 0.9s linear infinite; */
      /* color: #1c87c9; */
      color: red !important;
      /* font-size: 30px; */
      font-weight: bold;
      /* font-family: sans-serif; */
      /* } */
      /* @keyframes blinker {  
      50% { opacity: 0; } */
      }
</style>
<?php if($this->session->categoria=="") {
    header("Location: ".base_url());
  exit();
} ?>
<div class="container">
<!-- entradas sólo para Sara -->
<?php if ($this->session->categoria ==6) { ?> 
<!-- inicio -->
<div class="btn-group">
    <?php echo anchor('inicio','Inicio',array('class'=>'btn btn-default')) ?>
</div> 

<div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Subir Archivos <span class="caret"></span></button>
    <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
      <li><?php echo anchor('upload/do_upload_prestashop','Prestashop') ?></li>

      <li class="divider"></li>
      <li><?php echo anchor('upload/do_upload_tracking','Tracking') ?></li>
                    <li><?php echo anchor('envioTrackingPrestashop/listaPendientes', 'Envío Tracking Prestashop') ?></li>
		<li class="divider"></li>
					        <li><?php echo anchor('prestashop/documentosSuiza', 'Documentos Prestashop Suiza') ?></li>

                    <li class="divider"></li>
      <li><a href="<?= base_url() ?>index.php/stocks/exportCSV" class="btn btn-default t5 botonSuperior " id="maba"><i class="fa fa-download"></i><span class="hidden-xs floatR l5">Stocks para presta</span><div class="clear"></div></a></li>        
                 
    </ul>
</div>
<?php echo anchor('inicio/logout','Salir.',array('class'=>'btn btn-default pull-right')) ?>

<?php } else {?>

<!-- inicio -->
<div class="btn-group">
    <?php echo anchor('inicio','Inicio',array('class'=>'btn btn-default')) ?>
</div> 
<!-- subir archivos -->    
<div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Subir Archivos <span class="caret"></span></button>
    <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
        <?php if ($this->session->categoria < 3) { ?>    
              <li><?php echo anchor('upload/do_upload','Boka') ?></li>
         <!--     <li><?php echo anchor('upload/registroVentas','Finalizar Boka último') ?></li> -->
      
              
      
              <li><?php echo anchor('upload/do_upload_prestashop','Prestashop') ?></li>
              <?php } ?>
              <?php if ($this->session->categoria !=2) { ?>  
              <li class="divider"></li>
              <li><?php echo anchor('upload/do_upload_tracking','Tracking') ?></li>
              <li class="divider"></li>
              <li><?php echo anchor('upload/do_upload_costes_transportes','Costes transportes') ?></li>
       <!--       <li class="divider"></li>
              <li><?php echo anchor('upload/do_upload_costes_transportes/1','Costes transportes adicionales') ?></li>
       -->
              <?php } ?>       
    </ul>
</div>
 
<!-- listados -->     
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        Listados <span class="caret"></span></button>
    <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
        
        <?php if ($this->session->categoria <2) { ?>
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Varios</a>
            <ul class="dropdown-menu">
                <?php if ($this->session->categoria ==1){ ?> 
               <li><?php echo anchor('gestionTablas/entradas', 'Movimiento Web') ?></li>
                <?php } ?>
                <li><?php echo anchor('gestionBoka/seleccionarBoka', 'Listado Datos Boka') ?></li>
      <!--         <li><?php echo anchor('listados/seleccionBoka', 'Listado Datos Boka') ?></li> -->
            </ul>
        </li>
        <?php } ?>
        
        <?php if ($this->session->categoria != 2) { ?>
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Directorios</a>
            <ul class="dropdown-menu">
                  <li><?php echo anchor('gestionTablas/proveedores', 'Proveedores') ?></li>
                  <li><?php echo anchor('gestionTablas/acreedores', 'Acreedores') ?></li>
                  <li><?php echo anchor('gestionTablas/clientes', 'Clientes') ?></li>
            </ul>
        </li>
        <?php } ?>
        
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Gestión Productos</a>
            <ul class="dropdown-menu">
                <li><?php echo anchor('gestionTablasProductos/productos', 'Productos','style="font-weight:bold;color:blue"') ?></li>
                <li><?php echo anchor('gestionTablasProductos/productosVenta', 'Sólo Productos Venta','style="font-weight:bold;color:blue"') ?></li>
                <li><?php echo anchor('gestionTablasProductos/packs', 'Packs productos') ?></li>
                <li><?php echo anchor('gestionTablasProductos/embalajes', 'Envases y embalajes Productos') ?></li>
                <li><?php echo anchor('gestionTablasProductos/productosDescatalogados', 'Productos Descatalogados') ?></li>
                <?php if ($this->session->categoria ==1){ ?> 
                  <li><?php echo anchor('gestionTablasProductos/index', 'Actualizar márgenes y precios_compra') ?></li>
                <?php } ?>  
                <?php if ($this->session->categoria != 2) { ?>
                    <li><?php echo anchor('gestionTablas/ivas', 'IVAs') ?></li>
                    <li><?php echo anchor('gestionTablas/grupos', 'Grupos') ?></li>
                    <li><?php echo anchor('gestionTablas/familias', 'Familias') ?></a></li>
                    <li><?php echo anchor('gestionTablas/grupos_familias', 'Relacionar Grupos y sus Familias') ?></li>
                  <?php } ?>
            </ul>
        </li>
        
        <?php if ($this->session->categoria != 2) { ?>
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Facturas a Clientes</a>
            <ul class="dropdown-menu">
                  <li><?php echo anchor('gestionTablas/facturas', 'Facturas Clientes') ?></li>
                  <li><?php echo anchor('gestionTablas/formas_pagos', 'Formas de Pago') ?></li>
            </ul>
        </li>
        <?php } ?>
        
        <?php if ($this->session->categoria != 5) { ?>
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Ventas</a>
            <ul class="dropdown-menu">
                <?php if ($this->session->categoria != 5) { ?>
                  <li><?php echo anchor('gestionTablas/ticketsTiendaEntreFechas', 'Ventas Tickets') ?></li>
                <?php } ?>    
                  <?php if ($this->session->categoria != 2) { ?>
                 <!--   <li><?php echo anchor('gestionTablas/pedidosPrestashop', 'Ventas Pedidos Prestashop') ?></li> -->
              <!--     <li><?php echo anchor('gestionTablas/productosPrestashop', 'Ventas Productos Prestashop') ?></li> -->
                    <li><?php echo anchor('gestionTablas/pedidosPrestashopEntreFechas', 'Ventas Prestashop') ?></li>
           <!--         <li><?php echo anchor('gestionTablas/pedidosPrestashopTabla', 'Ventas Pedidos Prestashop Tabla') ?></li> -->
                    
                    <?php if ($this->session->categoria < 2) { ?>  
                     <li><?php echo anchor('gestionTablas/ventasDirectas', 'Ventas Directas') ?></li>
                     <li class="divider"></li>
                     <li ><?php echo anchor('ventas/entradaTransporte', 'Entrada Manual Transporte Pagado', array('class'=>'blink')) ?></li>

                  <?php } ?>  
                    <li class="divider"></li>
                    <li><?php echo anchor('envioTrackingPrestashop/listaPendientes', 'Envío Tracking Prestashop') ?></li>
				    <li><?php echo anchor('prestashop/documentosSuiza', 'Documentos Prestashop Suiza') ?></li>

                    <li class="divider"></li>
                    <li><?php echo anchor('gestionTablas/tiendasWeb', 'Tiendas Web datos') ?></li>
                    <li><?php echo anchor('gestionTablas/trackingPrestashop', 'Tracking Prestashop') ?></li>
                    
                  
                  <?php } ?>
            </ul>
        </li>
        <?php } ?>
        
        <?php if ($this->session->categoria == 2) { ?>
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Compras</a>
            <ul class="dropdown-menu">
                
                  <li><?php echo anchor('gestionTablas/pedidos', 'Pedidos') ?></li>
                
                  <li><?php echo anchor('gestionTablas/albaranes', 'Albaranes') ?></li>
               <!--     <li><?php echo anchor('gestionTablas/facturasProveedores', 'Facturas Proveedores') ?></li>
                        <li><?php echo anchor('gestionTablas/facturasAcreedores', 'Facturas Acreedores') ?></li> -->
                  <li><?php echo anchor('compras/pagoFacturasProveedores', 'Registrar Pago Facturas Proveedores') ?></li>
                
                <li><?php echo anchor('gestionTablas/transformaciones', 'Transformaciones') ?></li>
            </ul>
        </li>
        <?php } ?>
        <?php if ($this->session->categoria != 2) { ?>
          <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Compras</a>
            <ul class="dropdown-menu">
              
              <li><?php echo anchor('gestionTablas/pedidos', 'Pedidos') ?></li>
              <li><?php echo anchor('gestionTablas/albaranes', 'Albaranes') ?></li>
              <!-- <li><?php echo anchor('gestionTablas/facturasProveedores', 'Facturas Proveedores') ?></li> -->
              <li><?php echo anchor('compras/facturasProveedoresEntreFechas', 'Facturas Proveedores entre fechas') ?></li>
              
              <?php if ($this->session->categoria != 5) { ?>
                <!-- <li ><?php echo anchor('gestionTablas/facturasAcreedores', 'Facturas Acreedores') ?></li> -->
                <li><?php echo anchor('compras/facturasAcreedoresEntreFechas', 'Facturas Acreedores entre fechas') ?></li>
                <?php } ?>
                <li><?php echo anchor('compras/pagoFacturasProveedores', 'Registrar Pago Facturas Proveedores') ?></li>
                <li><?php echo anchor('gestionTablas/transformaciones', 'Transformaciones') ?></li>
                <?php if ($this->session->categoria != 5) { ?>
                  <li><?php echo anchor('gestionTablas/conceptosAcreedores', 'Conceptos Acreedores') ?></li>
                  <li><?php echo anchor('gestionTablas/formasPagosAcreedores', 'Formas Pagos Acreedores') ?></li>
                  <?php } ?>
            </ul>
        </li>
        <?php } ?>
        <?php if ($this->session->categoria != 2 && $this->session->categoria != 5) { ?>
        <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Stocks</a>
            <ul class="dropdown-menu">
                  <li><?php echo anchor('gestionTablas/stocks', 'Stocks - fecha caducidad') ?></li>
                  <li><?php echo anchor('gestionTablas/stocks_totales', 'Stocks totales') ?></li>
                  <li><?php echo anchor('stocks/stocksResumenes', 'Resúmenes Stocks') ?></li>
            </ul>
        </li>
        <?php } ?>
        <?php if ($this->session->categoria < 2) { ?>   
            <li><?php echo anchor('gestionTablas/conversiones', 'Conversiones') ?></li>
        <?php } ?>
        <?php if ($this->session->categoria == 5) { ?> 
        <li class="divider"></li>
                    <li><?php echo anchor('envioTrackingPrestashop/listaPendientes', 'Envío Tracking Prestashop') ?></li>
					<li><?php echo anchor('prestashop/documentosSuiza', 'Documentos Prestashop Suiza') ?></li>
                    <li><?php echo anchor('gestionTablas/trackingPrestashop', 'Tracking Prestashop') ?></li>
	                

        <?php } ?>
        
</ul>
   
</div>    
<!-- inicio -->
<!-- <div class="btn-group">
    <?php echo anchor('gestionTablasProductos/productos', 'Productos',array('class'=>'btn btn-default','style'=>"font-weight:bold;color:blue")) ?>
</div>      -->
<?php if ($this->session->categoria < 2) { ?>
<div class="btn-group">
    <?php echo anchor('productos/productosSpeedy', 'Productos',array('class'=>'btn btn-default','style'=>"font-weight:bold;color:blue")) ?>
</div> 
<?php } ?>   
<?php if ($this->session->categoria == 2) { ?>
<div class="btn-group">
    <?php echo anchor('productos/productos_Speedy', 'Productos',array('class'=>'btn btn-default','style'=>"font-weight:bold;color:blue")) ?>
</div> 
<?php } ?>   
<!-- ventas tienda -->   
<?php if ($this->session->categoria ==2) { ?>
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">

    Ventas Tienda- <span class="caret"></span></button>

    <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
    <li><?php echo anchor('caja/cierreCaja','Cierre Caja') ?></li>
    <li><?php echo anchor('gestionTablas/ticketsTiendaEntreFechas', 'Ventas Tickets') ?></li>
    <li><?php echo anchor('facturas','Generar Factura') ?></li>

             </ul>
</div>
  <?php } ?>   

<?php if ($this->session->categoria !=2) { ?>
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Ventas Tienda- <span class="caret"></span></button>
    <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
            <?php if ($this->session->categoria < 3) { ?>
              <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Caja</a>
                <ul class="dropdown-menu">
                    <li><?php echo anchor('caja/cierreCaja','Cierre Caja') ?></li>
                <?php if ($this->session->categoria != 2) { ?>    
                    <li class="divider"></li>
                    <li><?php echo anchor('caja/informacionCierresCaja','Información Cierres Caja') ?></li>
                    <li><?php echo anchor('caja/inicializarCaja','Inicialización Caja') ?></li>
                <?php } ?>     
                </ul>
              </li>
            <?php } ?> 
             <?php if ($this->session->categoria == 2  || $this->session->categoria < 2 || $this->session->categoria==4 || $this->session->categoria==5) { ?>  
        <!--      <li><?php echo anchor('listados/seleccionVentasTickets','Listado Tickets Ventas') ?></li> -->
              <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Tickets</a>
                
                <ul class="dropdown-menu">
                  <?php if ($this->session->categoria < 2  || $this->session->categoria ==4 || $this->session->categoria ==5) { ?>
                  <!--
                    <li class="dropdown-submenu">

                    <a href="#">Mostrar...</a>
                      <ul class="dropdown-menu">
                          <?php if ($this->session->categoria ==2  || $this->session->categoria < 2 || $this->session->categoria ==4 || $this->session->categoria ==5) { ?>
                          <li><?php echo anchor('tickets/seleccionarTicketsMostrar','Mostrar Tickets Periodo') ?></li>
                        <?php } ?>
                          <li><?php echo anchor('tickets/seleccionaTicket','Mostrar UN Ticket') ?></li>
                      </ul>
                  </li>
                          -->              
                  <?php } ?>
                  
                  <!-- <li class="dropdown-submenu">
                    <a href="#">Cambiar forma de pago...</a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('tickets/seleccionarTicketsCambiar','Periodo') ?></li>
                    	<li><?php echo anchor('tickets/seleccionarTicketCambioPago','Un ticket') ?></li>
                    </ul>
                  </li> -->
                  <!--
                  <li><a tabindex="-1" href="#">Entrada manual datos resumen día</a></li>
                  -->
                  <?php if ($this->session->categoria !=2 && $this->session->categoria !=5){ ?>
                  <li><?php echo anchor('tickets/entradaTicketManual','Entrada Manual Datos Tira') ?></li>
                  <?php } ?>
                  <li><?php echo anchor('facturas','Generar Factura') ?></li>
                  
                </ul>
              </li>
              
               <?php if ($this->session->categoria < 2 ) { ?>
              <!--
              <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Ventas</a>
                <ul class="dropdown-menu">
                  <li><?php echo anchor('listados/seleccionVentasImportes','Ventas: Importes e IVAs') ?></li>
                  <li><?php echo anchor('listados/seleccionVentasProductos','Ventas: Productos') ?></li>
                </ul>
              </li>
              -->
              <li><?php echo anchor('conversion/conversionPeriodo','Conversiones') ?></li>
             
              <?php } ?>
              
        <?php } ?>    
            </ul>
</div>
<?php } ?>    

<?php if ($this->session->categoria < 2 || $this->session->categoria ==5) { ?> 
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Resumen Ventas <span class="caret"></span></button>

            
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
        <?php if ($this->session->categoria != 2 && $this->session->categoria !=5) { ?>
                  <li><?php echo anchor('gestionTablas/ticketsTiendaEntreFechas', 'Ventas Tickets') ?></li>
                  <?php if ($this->session->categoria < 2 && strtolower($this->session->username)=='pernilall') { ?>   
                    <li><?php echo anchor('gestionTablas/tickets_TiendaEntreFechas', 'Ventas Tickets alternativa') ?></li>
                <?php } ?>    
                <?php } ?>    
                  <?php if ($this->session->categoria != 2 || $this->session->categoria == 5) { ?>
                 <!--   <li><?php echo anchor('gestionTablas/pedidosPrestashop', 'Ventas Pedidos Prestashop') ?></li> -->
              <!--     <li><?php echo anchor('gestionTablas/productosPrestashop', 'Ventas Productos Prestashop') ?></li> -->
                    <li><?php echo anchor('gestionTablas/pedidosPrestashopEntreFechas', 'Ventas Prestashop') ?></li>
           <!--         <li><?php echo anchor('gestionTablas/pedidosPrestashopTabla', 'Ventas Pedidos Prestashop Tabla') ?></li> -->
                    
                    <?php if ($this->session->categoria < 2) { ?>  
                     <li><?php echo anchor('gestionTablas/ventasDirectas', 'Ventas Directas') ?></li>
                  <?php } ?> 
                  <?php } ?>   

    </ul>

</div>  
<?php } ?>

<?php if ($this->session->categoria < 2 || $this->session->categoria ==4 || $this->session->categoria ==5){ ?>
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Compras <span class="caret"></span></button>
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                  <li><?php echo anchor('stocks/albaran','Albaranes (Entrada producto)') ?></li>
                  <li><?php echo anchor('compras/facturaProveedor','Facturas Proveedores') ?></li>
             <!--     <li><?php echo anchor('compras/pedidoProveedor','Pedidos a Proveedores') ?></li> -->
                  <li><?php echo anchor('compras/pedidoProveedorNuevo','Pedidos a Proveedores') ?></li>          
        </ul>
  </div>
<?php } ?>

<?php if ($this->session->categoria  ==2){ ?>
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Transformaciones <span class="caret"></span></button>
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                
                  <li><?php echo anchor('stocks/transformaciones','Transformaciones productos') ?></li>
                  
        </ul>
</div>
<?php } ?>
<?php if ($this->session->categoria < 3 || $this->session->categoria ==4 || $this->session->categoria ==5){ ?>
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Stocks <span class="caret"></span></button>
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                  <li><?php echo anchor('gestionTablas/stocks','Stocks - fecha caducidad') ?></li>
                  <li><?php echo anchor('gestionTablas/stocks_totales','Stocks totales') ?></li>
                  <?php if ($this->session->categoria !=2){ ?>
                  <?php if ($this->session->categoria !=5){ ?>
                  <li class="divider"></li>
                  <li><?php echo anchor('stocks/stocksResumenes', 'Resúmenes Stocks') ?></li>
                   <?php } ?>
                  <li class="divider"></li>
                  <li><?php echo anchor('stocks/stocksMinimos', 'Actualizar stocks mínimos') ?></li>
                   <?php } ?>
                  <?php if ($this->session->categoria ==1){ ?> 
                     <li class="divider"></li>
                     <li><?php echo anchor('stocks/verificacion','Verificación integridad stocks') ?></li>
                     <li><?php echo anchor('stocks/depuracion','Depuración stocks (stocks totales)') ?></li>
                  <?php } ?>
                     
                 <?php if ($this->session->categoria < 2 || $this->session->categoria ==4 || $this->session->categoria ==5){ ?>
                  <li class="divider"></li>
          <!--    <li><?php echo anchor('stocks/entradas','Entrada producto (Compras)') ?></li> -->
                  
                  <li><?php echo anchor('stocks/albaran','Entrada producto - albaranes (Compras)') ?></li>
                  
           <!--       <li><?php echo anchor('stocks/salidas','Salida producto (Ventas)') ?></li> -->
                 <?php if ($this->session->categoria < 2  ){ ?> 
                  <li><?php echo anchor('stocks/ventaDirecta','Salidas producto - Ventas directas') ?></li>
                  <?php } ?>
                  <li><?php echo anchor('stocks/transformaciones','Transformaciones productos') ?></li>
                  <?php } ?>
                  <li class="divider"></li>
                  
                  <?php if ($this->session->categoria < 2 || $this->session->categoria == 2 || $this->session->categoria ==4 || $this->session->categoria ==5){ ?>
                  <li><?php echo anchor('stocks/inventarios','Entrada inventario') ?></li>
                   <?php } ?>
        </ul>
</div>
<?php } ?>

 <?php if ($this->session->categoria ==5){ ?>
 <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
  Otros <span class="caret"></span></button>
       <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                 <li><?php echo anchor('tickets/seleccionarTicketCambioPago','Cambiar cliente en ticket') ?></li>               
       </ul>
</div>
<?php } ?>
 
<?php if ($this->session->categoria < 2 ){ ?>  <!--|| $this->session->categoria ==4){ ?> -->
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Estadísticas <span class="caret"></span></button>
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                  <li><?php echo anchor('estadisticas/evolucionPVP','Evolución PVP productos') ?></li>
                  <!--<li><?php echo anchor('estadisticas/evolucionStocks','Evolución Stocks') ?></li>-->
                  <li><?php echo anchor('estadisticas/evolucionVentas','Evolución Ventas Mensuales producto') ?></li>
                  <li><?php echo anchor('estadisticas/ventasUltimoDia','Ventas último día') ?></li>
                  <li class="divider"></li>
        </ul>
  </div>
<?php } ?>

<?php if ($this->session->categoria < 2 ||  $this->session->categoria == 4){ ?>  <!--|| $this->session->categoria ==4){ ?> -->
  <!-- <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Catálogo <span class="caret"></span></button>
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
            
                  <li><?php echo anchor('catalogo/marcas','Datos marcas') ?></li>
                  <?php if ($this->session->categoria < 2 ){ ?>
                    <li><?php echo anchor('catalogo/generarCatalogo','Generar Catálogo Profesionales') ?></li>
                    <li><?php echo anchor('catalogo/generarCatalogo/tienda','Generar Catálogo Tienda') ?></li>
                  <?php } ?>
        </ul>
  </div> -->
<?php } ?> 
              
  
 <?php if ($this->session->categoria ==1){ ?> 
  <div class="btn-group">
   <button type="button" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown">
    Pruebas <span class="caret"></span></button>
        <ul class="dropdown-menu multi-level " role="menu" aria-labelledby="dropdownMenu">
                 

                 <li><?php echo anchor('pruebas/crearRegistrosVentas2','Crear Registros Ventas pernil181bcn') ?></li>
                 <li><?php echo anchor('pruebas/pruebaUsoBD','Probar uso base de datos') ?></li>
                 <li><?php echo anchor('pruebas/pruebasA','Para pruebas A') ?></li>
                 <li><?php echo anchor('pruebas/pruebasC','Para pruebas C') ?></li>
                 <!--
                 <li><?php echo anchor('chartcontroller','Chart') ?></li>
                  <li><?php echo anchor('pruebas/pruebasA','Para pruebas A') ?></li>
                  <li><?php echo anchor('pruebas/pruebasB','Para pruebas B') ?></li>
                  <li><?php echo anchor('pruebas/pruebasC','Para pruebas C') ?></li>
                  <li><?php echo anchor('pruebas/pruebasD','Para pruebas D') ?></li>
                  <li><?php echo anchor('pruebas/pruebasE','Para pruebas E') ?></li>
                  <li><?php echo anchor('pruebas/pruebasF','Pruebas F - Crear pe_tickets a partir de datos BOKA') ?></li>
                  <li><?php echo anchor('pruebas/pruebasG','Pruebas G - Cantidades Stocks x 1000') ?></li>
                  <li><?php echo anchor('pruebas/pruebasH','Pruebas H - Consistencia stocks con productos') ?></li>
                  <li><?php echo anchor('pruebas/pruebasI','Pruebas I - Preparar PS sarcar archivo 19/09/2016') ?></li>
                  <li><?php echo anchor('pruebas/pruebasJ','Pruebas J - Actualizar pe_proveedores_acreedores con id_proveedor de pe_pedidos_proveedores') ?></li>
                  <li><?php echo anchor('pruebas/pruebasK','Pruebas K - Corregir IVA y tipo iva a los clientes internacionales de prestashop cliente=9') ?></li>
                  <li><?php echo anchor('pruebas/pruebasL','Pruebas L - Para incluir en boka el id_pe_producto') ?></li>
                 <li><?php echo anchor('pruebas/pruebasM','Pruebas M - Contar num basculas iguales en productos') ?></li>
                 <li><?php echo anchor('pruebas/pruebasN','Pruebas N - Pendiente implementar') ?></li>
                 <li><?php echo anchor('pruebas/pruebasO','Prueba Catálogo') ?></li>
                 <li><?php echo anchor('pruebas/pruebasP','Pruebas P - Pendiente implementar') ?></li>
 -->

        </ul>
</div>
  <?php } ?>
  <?php echo anchor('inicio/logout','Salir.',array('class'=>'btn btn-default pull-right')) ?>
<br>
<?php } ?>




<style>
    ul.dropdown-menu.multi-level >li >a{
        margin-top: 0px;
        margin-bottom: 0px;
    } 
    ul.dropdown-menu.multi-level >li.dropdown-submenu >a{
        margin-top: 0px;
        margin-bottom: 0px;
    } 
    ul.dropdown-menu.multi-level >li.dropdown-submenu >ul.dropdown-menu >li >a{
        margin-top: 0px;
        margin-bottom: 0px;
    }
    ul.dropdown-menu.multi-level >li.dropdown-submenu >ul.dropdown-menu >li >ul >li >a{
        margin-top: 0px;
        margin-bottom: 0px;
    }
    
    
   
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
    
    
    
    
</style>

<script>
$(document).ready(function () {
  
  $('body > div > div > button, body > div > div > a,body > div > a').mouseenter(function(){
    $(this).css('border','1px solid blue')
  })
  $('body > div > div > button, body > div > div > a,body > div > a').mouseleave(function(){
    $(this).css('border','1px solid #CCCCCC')
  })

  
})

</script>