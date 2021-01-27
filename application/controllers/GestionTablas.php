<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class GestionTablas extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        /* Standard Libraries of codeigniter are required */
        $this->load->database();

        $this->load->helper('url');

        $this->load->model('productos_');

        //$this->productos_->crearTablaCodigosProductos();

        /* ------------------ */

        $this->load->library('grocery_CRUD');


        $this->grocery_crud->set_theme('bootstrap');
        $this->grocery_crud->unset_bootstrap();
        $this->grocery_crud->unset_jquery();
        $this->grocery_crud->set_language("spanish");



        $this->load->helper('cookie');
        $this->load->helper('maba');
    }

    public function index()
    {
    }

    public function estudiosMercado()
    {
        $this->grocery_crud->set_table('pe_productos_codigos_estudio');
        $this->grocery_crud->set_language("spanish");



        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['titulo'] = 'Códigos Productos Estudios Mercado';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output);
    }

    public function entradas()
    {
        $this->grocery_crud->set_table('pe_movimientosWeb');
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->order_by('fecha', 'desc');
        $this->grocery_crud->set_subject('Entradas Web');

        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_add();
        $this->grocery_crud->unset_read();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['titulo'] = 'Entradas web';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output);
    }

    public function clientes()
    {

        $this->grocery_crud->set_table('pe_clientes')
            ->set_subject('Clientes')
            ->columns('id_cliente', 'nombre', 'telefono1', 'correo1');

        $this->grocery_crud->where('status_cliente', '1');

        $display = array(
            'id_cliente' => 'Código',
            'nombre' => 'Nombre',
            'empresa',
            'tienda_web' => 'Tienda/Web',
            'fechaAlta' => 'Fecha Alta',
            'codigoContabilidad' => 'Cod. Contabilidad',
            'direccion' => 'Dirección',
            'codigoPostal' => 'Cod. Postal',
            'poblacion' => 'Población',
            'provincia' => 'Provincia',
            'pais' => 'País',
            'telefono1' => 'Teléfono',
            'nif' => 'NIF',
            'fechaModificacion' => 'Fecha Modificación',
            'dtoGeneral' => 'Descuento General',
            'grupoPrecio' => 'Grupo Precio',
            'web' => 'Web',
            'contacto1' => 'Contacto',
            'movil1' => 'Teléfono Móvil',
            'correo1' => 'Email',
            'varios1' => 'Varias',
            'notas' => 'Notas',
            'contacto2' => 'Contacto 2',
            'telefono2' => 'Teléfono 2',
            'movil2' => 'Teléfono Móvil 2',
            'correo2' => 'Email 2',
        );

        $this->grocery_crud->display_as($display);

        $this->grocery_crud->edit_fields(
            'nombre',
            'empresa',
            'tienda_web',
            'fechaAlta',
            'codigoContabilidad',
            'direccion',
            'codigoPostal',
            'poblacion',
            'provincia',
            'pais',
            'telefono1',
            'nif',

            'dtoGeneral',
            'grupoPrecio',
            'web',
            'contacto1',
            'movil1',
            'correo1',
            'varios1',
            'notas',
            'contacto2',
            'telefono2',
            'movil2',
            'correo2'
        );


        $this->grocery_crud->add_fields(
            'id_cliente',
            'nombre',
            'empresa',
            'tienda_web',
            'fechaAlta',
            'codigoContabilidad',
            'direccion',
            'codigoPostal',
            'poblacion',
            'provincia',
            'pais',
            'telefono1',
            'nif',

            'dtoGeneral',
            'grupoPrecio',
            'web',
            'contacto1',
            'movil1',
            'correo1',
            'varios1',
            'notas',
            'contacto2',
            'telefono2',
            'movil2',
            'correo2'
        );


        $this->grocery_crud->required_fields(
            'id_cliente',
            'nombre',
            'empresa',
            'tienda_web',
            'fechaAlta',
            'codigoContabilidad',
            'direccion',
            'codigoPostal',
            'poblacion',
            'provincia',
            'pais',
            'telefono1',
            'nif'
        );


        $this->grocery_crud->callback_column('nombre', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('correo1', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('telefono1', array($this, '_column_left_align_nombre'));


        $this->grocery_crud->set_rules('correo1', 'email NO válido', 'valid_email');
        $this->grocery_crud->set_rules('correo2', 'email NO válido', 'valid_email');

        $this->grocery_crud->set_rules('codigoContabilidad', 'El código de contabilidad NO es válido. Debe ser 430.0.0.xxx', 'regex_match[/430.0.0.[0-9]{3}/]');

        $this->grocery_crud->callback_add_field('pais', array($this, 'add_field_callback_pais'));
        $this->grocery_crud->callback_add_field('id_cliente', array($this, 'add_field_callback_id_cliente'));

        $this->grocery_crud->callback_delete(array($this, '_delete_clientes'));
        // $this->grocery_crud->callback_add_field('fechaAlta',array($this,'add_field_callback_fechaAlta')); 

        //$this->grocery_crud->callback_add_field('fechaAlta',array($this,'_fechaEuropea'));  

        $this->grocery_crud->callback_after_insert(array($this, 'fechaModificacion_after_update_cliente'));
        $this->grocery_crud->callback_after_update(array($this, 'fechaModificacion_after_update_cliente'));

        //campos únicos
        $this->grocery_crud->unique_fields('id', 'id_cliente', 'codigoContabilidad', 'nombre', 'empresa');


        $this->grocery_crud->callback_add_field('tienda_web', array($this, 'add_field_callback_tienda_web'));
        $this->grocery_crud->callback_edit_field('tienda_web', array($this, 'edit_field_callback_tienda_web_edit'));

        // $this->grocery_crud->unset_read_fields('tienda_web');

        $output = $this->grocery_crud->render();




        $output = (array)$output;
        //$output['titulo'] = 'Clientes';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_clientes($output, "Clientes");
    }

    public function formas_pagos()
    {
        $this->grocery_crud->set_table('pe_formas_pagos')
            ->set_subject('Formas pagos Proveedores');
        $this->grocery_crud->display_as('forma_pago', 'Forma de Pago');

        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['titulo'] = 'Formas de Pago';
        $output['col_bootstrap'] = 8;
        $output = (object)$output;
        $this->_table_output($output, "Formas de Pago");
    }

    public function tiendasWeb()
    {
        $this->grocery_crud->set_table('pe_shops_web')
            ->set_subject('Tiendas Web');

        $output = $this->grocery_crud->render();




        $output = (array)$output;
        $output['titulo'] = 'Tiendas Web';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_tiendas_web($output, "Tiendas Web");
    }

    public function proveedores()
    {
        $this->grocery_crud->set_table('pe_proveedores')
            ->set_subject('Proveedores')
            ->columns('id_proveedor', 'nombre_proveedor', 'telefono', 'email1');

        $this->grocery_crud->where('status_proveedor', '1');


        $display =  [
            'id_proveedor' => 'Código',
            'nombre_proveedor' => 'Nombre',
            'fechaAlta' => 'Fecha Alta',
            'id_contable' => 'Cod. Contabilidad',
            'id_forma_pago' => 'Forma de pago',
            'domicilio' => 'Dirección',
            'cp' => 'Cod. Postal',
            'poblacion' => 'Población',
            'provincia' => 'Provincia',
            'pais' => 'País',
            'telefono' => 'Teléfono',
            'fax' => 'Fax',
            'cif' => 'CIF',
            'fechaModificacion' => 'Fecha Modificación',
            'web' => 'Web',
            'contacto' => 'Contacto',
            'movil' => 'Teléfono Móvil',
            'email1' => 'Email',
            'otros' => 'Otros',
            'nota' => 'Nota',
            'email2' => 'Email 2',
        ];

        $this->grocery_crud->display_as($display);
        $this->grocery_crud->edit_fields(
            'nombre_proveedor',
            'fechaAlta',
            'id_contable',
            'id_forma_pago',
            'domicilio',
            'cp',
            'poblacion',
            'provincia',
            'pais',
            'telefono',
            'fax',
            'cif',
            'web',
            'contacto',
            'movil',
            'email1',
            'otros',
            'nota',
            'email2'
        );

        $this->grocery_crud->add_fields(
            'id_proveedor',
            'nombre_proveedor',
            'fechaAlta',
            'id_contable',
            'id_forma_pago',
            'domicilio',
            'cp',
            'poblacion',
            'provincia',
            'pais',
            'telefono',
            'fax',
            'cif',
            'web',
            'contacto',
            'movil',
            'email1',
            'otros',
            'nota',
            'email2'
        );

        $this->grocery_crud->required_fields(
            'id_proveedor',
            'nombre_proveedor',
            'fechaAlta',
            //  'id_contable',
            'domicilio',
            'cp',
            'poblacion',
            'provincia',
            'pais',
            'telefono',
            'cif'
        );

        $this->grocery_crud->callback_column('nombre_proveedor', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('telefono', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('email1', array($this, '_column_left_align_nombre'));


        $this->grocery_crud->callback_add_field('id_proveedor', function () {
            $sql = "SELECT MAX(id_proveedor) as id_proveedor FROM pe_proveedores WHERE id_proveedor<900";
            $siguiente = $this->db->query($sql)->row()->id_proveedor + 1;
            return '<input id="field-id_proveedor" name="id_proveedor" type="text" value="' . $siguiente . '" class="numeric form-control" maxlength="11">';
        });

        $this->grocery_crud->callback_delete(array($this, '_delete_proveedores'));

        $this->grocery_crud->set_relation('id_forma_pago', 'pe_formas_pagos', 'forma_pago');

        $this->grocery_crud->set_rules('email1', 'email NO válido', 'valid_email');
        $this->grocery_crud->set_rules('email2', 'email NO válido', 'valid_email');
        $this->grocery_crud->set_rules('cif', 'CIF', 'callback_validar_cif');

        $this->grocery_crud->unique_fields('id', 'id_contable', 'id_proveedor', 'nombre_proveedor');

        $this->grocery_crud->set_rules('id_contable', 'El código de contabilidad NO es válido. Debe ser 400.0.0.xxx', 'regex_match[/400.0.0.[0-9]{3}/]');

        $this->grocery_crud->callback_add_field('pais', array($this, 'add_field_callback_pais'));
        //$this->grocery_crud->callback_add_field('fechaAlta',array($this,'add_field_callback_fechaAlta')); 

        $this->grocery_crud->callback_after_insert(array($this, 'fechaModificacion_after_update_proveedor'));
        $this->grocery_crud->callback_after_update(array($this, 'fechaModificacion_after_update_proveedor'));


        $output = $this->grocery_crud->render();




        $output = (array)$output;
        $output['titulo'] = 'Proveedores';
        $output['col_bootstrap'] = 8;
        $output = (object)$output;
        $this->_table_output_clientes($output, "Proveedores");
    }

    function validar_cif($cif)
    {
        switch (valida_nif_cif_nie($cif)) {
            case 0:
                $this->form_validation->set_message('validar_cif', "EL CIF No es válido. Revíselo y vuelva a introducirlo)");
                return false;
                break;
            case -1:
                $this->form_validation->set_message('validar_cif', "EL CIF No es válido. Revíselo y vuelva a introducirlo)");
                return false;
                break;
            case -2:
                $this->form_validation->set_message('validar_cif', "EL CIF No es válido. Revíselo y vuelva a introducirlo)");
                return false;
                break;
            case -3:
                $this->form_validation->set_message('validar_cif', "EL CIF No es válido. Revíselo y vuelva a introducirlo)");
                return false;
                break;
            default:
                return true;
        }
    }



    public function acreedores()
    {




        $this->grocery_crud->set_table('pe_acreedores')
            ->set_subject('Acreedores')
            ->columns('id_proveedor', 'nombre_proveedor', 'telefono', 'email1');

        $this->grocery_crud->where('status_acreedor', '1');

        $display =  [
            'id_proveedor' => 'Código',
            'nombre_proveedor' => 'Nombre',
            'fechaAlta' => 'Fecha Alta',
            'id_contable' => 'Cod. Contabilidad',
            'id_forma_pago' => 'Forma de pago',
            'domicilio' => 'Dirección',
            'cp' => 'Cod. Postal',
            'poblacion' => 'Población',
            'provincia' => 'Provincia',
            'pais' => 'País',
            'telefono' => 'Teléfono',
            'fax' => 'Fax',
            'cif' => 'CIF',
            'fechaModificacion' => 'Fecha Modificación',
            'web' => 'Web',
            'contacto' => 'Contacto',
            'movil' => 'Teléfono Móvil',
            'email1' => 'Email',
            'otros' => 'Otros',
            'nota' => 'Nota',
            'email2' => 'Email 2',
        ];

        $this->grocery_crud->display_as($display);
        $this->grocery_crud->edit_fields(
            'nombre_proveedor',

            'fechaAlta',
            'id_contable',
            'id_forma_pago',
            'domicilio',
            'cp',
            'poblacion',
            'provincia',
            'pais',
            'telefono',
            'fax',
            'cif',
            'web',
            'contacto',
            'movil',
            'email1',
            'otros',
            'nota',
            'email2'
        );

        $this->grocery_crud->add_fields(
            'id_proveedor',
            'nombre_proveedor',

            'fechaAlta',
            'id_contable',
            'id_forma_pago',
            'domicilio',
            'cp',
            'poblacion',
            'provincia',
            'pais',
            'telefono',
            'fax',
            'cif',
            'web',
            'contacto',
            'movil',
            'email1',
            'otros',
            'nota',
            'email2'
        );

        $this->grocery_crud->required_fields(
            'id_proveedor',
            'nombre_proveedor',

            'fechaAlta',
            //'id_contable',
            //'id_forma_pago',
            'domicilio',
            'cp',
            'poblacion',
            'provincia',
            'pais',
            'telefono',
            'cif'
        );

        $this->grocery_crud->callback_column('nombre_proveedor', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('email1', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('telefono', array($this, '_column_left_align_nombre'));


        $this->grocery_crud->callback_add_field('id_proveedor', function () {
            $sql = "SELECT MAX(id_proveedor) as id_proveedor FROM pe_acreedores WHERE id_proveedor<900";
            $siguiente = $this->db->query($sql)->row()->id_proveedor + 1;
            return '<input id="field-id_proveedor" name="id_proveedor" type="text" value="' . $siguiente . '" class="numeric form-control" maxlength="11">';
        });

        $this->grocery_crud->set_relation('id_forma_pago', 'pe_formas_pagos', 'forma_pago');

        $this->grocery_crud->set_rules('email1', 'email NO válido', 'valid_email');
        $this->grocery_crud->set_rules('email2', 'email NO válido', 'valid_email');

        $this->grocery_crud->set_rules('id_contable', 'El código de contabilidad NO es válido. Debe ser 410.0.0.xxx', 'regex_match[/410.0.0.[0-9]{3}/]');

        $this->grocery_crud->callback_add_field('pais', array($this, 'add_field_callback_pais'));

        $this->grocery_crud->callback_delete(array($this, '_delete_acreedores'));

        // $this->grocery_crud->callback_after_update(array($this, 'fechaModificacion_after_update_acreedor'));
        $this->grocery_crud->callback_after_insert(array($this, 'fechaModificacion_after_update_acreedor'));
        $this->grocery_crud->callback_after_update(array($this, 'fechaModificacion_after_update_acreedor'));

        //campos únicos
        $this->grocery_crud->unique_fields('id', 'id_proveedor', 'id_contable', 'nombre_proveedor');



        $output = $this->grocery_crud->render();




        $output = (array)$output;
        $output['titulo'] = 'Acreedores';
        $output['col_bootstrap'] = 8;
        $output = (object)$output;
        $this->_table_output_clientes($output, "Acreedores");
    }

    public function facturas()
    {
       
        $this->grocery_crud->set_table('pe_registroFacturas');
        $this->grocery_crud->set_subject('Facturas Clientes Tickets');
        

        if (strtolower($this->session->username) != 'pernilall') {
            $this->grocery_crud->where('fecha_factura >=',strval(date('Y')-1));
        }
        $this->grocery_crud->order_by('ordenacion', 'desc');

        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->required_fields('id_factura', 'id_cliente', 'nombreArchivoFactura');
        $this->grocery_crud->display_as('nombreArchivoFactura', 'Nombre Documento');
        $this->grocery_crud->display_as('id_cliente', 'Nombre Empresa');
        $this->grocery_crud->display_as('id_factura', 'Núm. factura');
        $this->grocery_crud->set_field_upload('nombreArchivoFactura', 'facturas');
        $this->grocery_crud->unset_columns(array('ordenacion'));
        $this->grocery_crud->unset_edit_fields(array('ordenacion'));
        $this->grocery_crud->unset_read_fields(array('ordenacion'));

        $this->grocery_crud->callback_column('nombreArchivoFactura', array($this, '_callback_nombreArchivoFactura'));
        //  $this->grocery_crud->callback_edit_field('nombreArchivoFactura',array($this,'edit_field_callback_nombreArchivoFactura'));

        $this->grocery_crud->set_relation('id_cliente', 'pe_clientes', 'empresa');

        $this->grocery_crud->callback_before_upload(array($this, 'example_callback_before_upload'));
        $this->grocery_crud->callback_after_upload(array($this, 'example_callback_after_upload'));

        $this->grocery_crud->callback_after_insert(array($this, 'facturas_callback_after'));
        $this->grocery_crud->callback_after_update(array($this, 'facturas_callback_after'));

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Facturas';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output_facturas($output, "Facturas Clientes");
    }

    public function pedidos()
    {
        //  $this->movimiento('pedidos');
        $this->grocery_crud->set_table('pe_pedidos_proveedores');
        $this->grocery_crud->set_subject('Pedidos a proveedores');
        $this->grocery_crud->where('status_pedido', '1');
        if (strtolower($this->session->username) != 'pernilall') {
            $this->grocery_crud->where('fecha >=',strval(date('Y')-1));
        }

        $this->grocery_crud->order_by('id', 'desc');
        $this->grocery_crud->set_language("spanish");
        // $this->grocery_crud->required_fields('id_factura', 'id_cliente', 'nombreArchivoFactura');
        $this->grocery_crud->display_as('numPedido', 'Núm Pedido');
        $this->grocery_crud->display_as('nombreArchivoPedido', 'Nombre Documento');
        $this->grocery_crud->display_as('id_proveedor', 'Proveedor');
        $this->grocery_crud->display_as('importe', 'Importe Total Pedido');
        $this->grocery_crud->display_as('otrosCostes', 'Otros Costes Pedido');
        $this->grocery_crud->display_as('status', 'Estado');
        $mostrarTabla = array('id_proveedor', 'numPedido', 'fecha', 'otrosCostes', 'importe', 'nombreArchivoPedido', 'fecha_recibido');
        $this->grocery_crud->columns($mostrarTabla);

        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_delete();
        //   $this->grocery_crud->unset_read();

        $this->grocery_crud->callback_delete(array($this, '_delete_pedidos'));

        $this->grocery_crud->callback_column('importe', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_column('otrosCostes', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_column('numPedido', array($this, '_column_right_align_numPedido'));
        $this->grocery_crud->callback_column('fecha', array($this, '_column_center_align'));
        $this->grocery_crud->callback_column('fecha_recibido', array($this, '_column_center_align'));

        $this->grocery_crud->set_field_upload('nombreArchivoPedido', 'pedidos');
        // $this->grocery_crud->callback_column('cambio_euro_to_rupias', array($this, '_column_right_align'));
        $this->grocery_crud->set_relation('id_proveedor', 'pe_proveedores_acreedores', 'nombre');

        $this->grocery_crud->callback_before_upload(array($this, 'example_callback_before_upload'));

        $this->grocery_crud->callback_after_upload(array($this, 'example_callback_after_upload'));

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Pedidos';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output_pedidos($output, "Pedidos");
    }



    public function ventasDirectas()
    {
        $this->movimiento('ventasDirectas');
        $this->grocery_crud->set_table('pe_ventas_directas');
        $this->grocery_crud->set_subject('Ventas Directas');

        $this->grocery_crud->order_by('fecha', 'desc');
        if($_SESSION['username']!='pernilall'){
            // mensaje($_SESSION['username']);
            $inicio=date('Y')-1;
            $this->grocery_crud->where('fecha >=',"$inicio");
        }


        $this->grocery_crud->callback_read_field('importe_total', array($this, '_column_left_number'));
        $this->grocery_crud->callback_column('importe_total', array($this, '_column_right_number'));
        $this->grocery_crud->callback_column('coste_total', array($this, '_column_right_number'));
        $this->grocery_crud->callback_column('pvp_total', array($this, '_column_right_number'));

        $this->grocery_crud->set_relation('id_cliente', 'pe_clientes', 'nombre');
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Ventas Directas';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_ventas_directas($output, "Ventas Directas");
    }

    public function albaranes()
    {
        $this->movimiento('albaranes');
        $this->grocery_crud->set_table('pe_albaranes');
        $this->grocery_crud->set_subject('Albaranes entradas');
        $this->grocery_crud->order_by('fecha,id_pedido', 'DESC');
        if (strtolower($this->session->username) != 'pernilall') {
            $this->grocery_crud->where('pe_albaranes.fecha >=',strval(date('Y')-1));
        }


        $this->grocery_crud->display_as('id_proveedor', 'Proveedor');
        $this->grocery_crud->display_as('id_pedido', 'Basado en pedido');


        // $this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));
        $this->grocery_crud->callback_column('total', array($this, '_column_right_number'));

        $this->grocery_crud->set_relation('id_proveedor', 'pe_proveedores_acreedores', 'nombre');
        $this->grocery_crud->set_relation('id_pedido', 'pe_pedidos_proveedores', '{nombreArchivoPedido}');
        $this->grocery_crud->callback_column($this->unique_field_name('id_pedido'), array($this, '_column_id_pedido'));
        //$this->grocery_crud->set_primary_key('id','pe_pedidos_proveedores');

        //$this->grocery_crud->set_field_upload('id_pedido', 'pedidos');

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Albaranes';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_albaranes($output, "Albaranes");
    }

    public function facturasAcreedores($tipo = "", $inicio = "", $final = "")
    {
        // $results=$this->db->query("SELECT * FROM pe_facturas_acreedores ")->result();
        // foreach($results as $k=>$v){
        //     if($this->db->query("SELECT * FROM pe_acreedores WHERE id_proveedor='".$v->id_acreedor."'")->num_rows()>0){
        //     $row=$this->db->query("SELECT * FROM pe_acreedores WHERE id_proveedor='".$v->id_acreedor."'")->row();
        //     $this->db->query("UPDATE pe_facturas_acreedores SET nombre_acreedor='".$row->nombre_proveedor."' WHERE id='".$v->id."'");
        //     }
        //     if($this->db->query("SELECT * FROM pe_formas_pagos_tickets WHERE id_forma_pago_ticket='".$v->id_forma_pago."'")->num_rows()>0){
        //     $row=$this->db->query("SELECT * FROM pe_formas_pagos_tickets WHERE id_forma_pago_ticket='".$v->id_forma_pago."'")->row();
        //     $this->db->query("UPDATE pe_facturas_acreedores SET nombre_forma_pago='".$row->forma_pago."' WHERE id='".$v->id."'");
        //     }
        //     if($this->db->query("SELECT * FROM pe_conceptos_acreedores WHERE id='".$v->id_concepto."'")->num_rows()>0){
        //     $row=$this->db->query("SELECT * FROM pe_conceptos_acreedores WHERE id='".$v->id_concepto."'")->row();
        //     $this->db->query("UPDATE pe_facturas_acreedores SET nombre_concepto='".$row->concepto."' WHERE id='".$v->id."'");
        //     }
        // }  

        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;
        $_SESSION['tipo'] = $tipo;

        $this->movimiento('facturasAcreedores');
        $this->grocery_crud->set_table('pe_facturas_acreedores');

        $where = "";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        }
        $whereTipo = "";
        switch ($tipo) {
            case 'todas':
                $whereTipo = "";
                break;
            case 'pagadas':
                $whereTipo = "fecha_pago!='0000-00-00'";
                break;
            case 'sinPagar':
                $whereTipo = "fecha_pago='0000-00-00'";
                break;
        }
        if ($whereTipo) {
            if ($where) $where .= " AND $whereTipo ";
            else $where = " $whereTipo ";
        }
        if ($where) $this->grocery_crud->where($where);

        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id', 'Identificador');
        $this->grocery_crud->display_as('fecha', 'Fecha factura');
        $this->grocery_crud->display_as('num_factura', 'Núm. factura');
        $this->grocery_crud->display_as('id_acreedor', 'Acreedor');
        $this->grocery_crud->display_as('nombre_acreedor', 'Acreedor');
        $this->grocery_crud->display_as('id_concepto', 'Concepto');
        $this->grocery_crud->display_as('nombre_concepto', 'Concepto');
        $this->grocery_crud->display_as('importe_total', 'Importe total');
        $this->grocery_crud->display_as('importe_iva', 'Importe iva');
        $this->grocery_crud->display_as('fecha_pago', 'Fecha pago');
        $this->grocery_crud->display_as('id_forma_pago', 'Forma pago');
        $this->grocery_crud->display_as('nombre_forma_pago', 'Forma pago');
        $this->grocery_crud->display_as('referencia_pago', 'Referencia pago');

        $this->grocery_crud->columns('fecha', 'num_factura', 'id_acreedor', 'id_concepto', 'importe_total',  'importe_iva', 'fecha_pago', 'id_forma_pago');


        //$this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        // $this->grocery_crud->unset_delete();

        $this->grocery_crud->set_relation('id_forma_pago', 'pe_formas_pagos_acreedores', 'forma_pago');
        $this->grocery_crud->set_relation('id_acreedor', 'pe_acreedores', 'nombre_proveedor');
        $this->grocery_crud->set_relation('id_concepto', 'pe_conceptos_acreedores', 'concepto');

        $this->grocery_crud->add_fields(array('fecha', 'num_factura', 'id_acreedor', 'id_concepto', 'importe_total', 'importe_iva', 'fecha_pago', 'id_forma_pago', 'referencia_pago'));

        $this->grocery_crud->required_fields('fecha', 'num_factura', 'id_acreedor', 'id_concepto', 'importe_total');

        $this->grocery_crud->callback_before_insert(array($this, 'formateo_facturas_acreedores'));
        $this->grocery_crud->callback_before_update(array($this, 'formateo_facturas_acreedores'));



        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['tituloRango'] = 'Facturas Acreedor';
        $textoTipo = "";
        if ($tipo == "" || $tipo == "todas") $textoTipo = "(todas)";
        if ($tipo == "pagadas") $textoTipo = "(pagadas)";
        if ($tipo == "sinPagar") $textoTipo = "(sin pagar)";

        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Facturas Acreedores ' . $textoTipo . ' entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);


        $output['titulo'] = 'Facturas Acreedores';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_facturas_acreedores($output, "Facturas Acreedores");
    }


    public function facturasProveedores($tipo = "", $inicio = "", $final = "")
    {
        // poner nombres de proveedor

        // $results=$this->db->query("SELECT * FROM pe_facturas_proveedores ")->result();
        // foreach($results as $k=>$v){
        //     if($this->db->query("SELECT * FROM pe_proveedores WHERE id_proveedor='".$v->id_proveedor."'")->num_rows()>0){
        //     $row=$this->db->query("SELECT * FROM pe_proveedores WHERE id_proveedor='".$v->id_proveedor."'")->row();
        //     $this->db->query("UPDATE pe_facturas_proveedores SET nombre_proveedor='".$row->nombre_proveedor."' WHERE id='".$v->id."'");
        //     }
        // }    



        // para actualizar total_iva y base cambios realizados el 20200101
        // $results=$this->db->query("SELECT * FROM pe_facturas_proveedores ")->result();
        // foreach($results as $k=>$v){
        //     $resultLineas=$this->db->query("SELECT * FROM pe_lineas_facturas_proveedores WHERE id_factura='".$v->id."'")->result();
        //     $bases=array();
        //     foreach($resultLineas as $k1=>$v1){
        // mensaje('total linea '.$v1->total);
        // mensaje('total linea tipo iva'.$v1->tipoIva);
        //         if(isset($bases[$v1->tipoIva])) $bases[$v1->tipoIva]+=$v1->total;
        //         else $bases[$v1->tipoIva]=$v1->total;
        // mensaje('bases '.$v1->tipoIva.' - '.$bases[$v1->tipoIva]);
        //     }
        //     $totalIva=0;
        //     foreach($bases as $k2=>$v2){
        // mensaje($v2);
        // mensaje($k2);
        //         $totalIva+=$v2*$k2/100/100;
        //     }
        // mensaje('$totalIva '.$totalIva);
        //     $this->db->query("UPDATE pe_facturas_proveedores SET total_iva='$totalIva' WHERE id='".$v->id."'");
        //     $this->db->query("UPDATE pe_facturas_proveedores SET base=importe-total_iva WHERE id='".$v->id."'");
        // }

        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;
        $_SESSION['tipo'] = $tipo;

        $this->movimiento('facturaProveedores');
        $this->grocery_crud->set_table('pe_facturas_proveedores');

        $where = "";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        }
        $whereTipo = "";
        switch ($tipo) {
            case 'todas':
                $whereTipo = "";
                break;
            case 'pagadas':
                $whereTipo = "fecha_pago!='0000-00-00'";
                break;
            case 'sinPagar':
                $whereTipo = "fecha_pago='0000-00-00'";
                break;
        }
        if ($whereTipo) {
            if ($where) $where .= " AND $whereTipo ";
            else $where = " $whereTipo ";
        }
        if ($where) $this->grocery_crud->where($where);

        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id_proveedor', 'Proveedor');
        $this->grocery_crud->display_as('nombre_proveedor', 'Proveedor');
        $this->grocery_crud->display_as('numFactura', 'Núm. Factura');
        $this->grocery_crud->display_as('otrosCostes', 'Otros costes');
        $this->grocery_crud->display_as('numPedido', 'Basado en pedido núm');
        $this->grocery_crud->display_as('num_albaran', 'Núm. albarán/es');
        $this->grocery_crud->display_as('fecha_pago', 'Fecha de pago');
        $this->grocery_crud->set_lang_string('list_edit', 'Pagar');

        $this->grocery_crud->unset_columns(array('tipoIva', 'otrosCostes'));
        $this->grocery_crud->callback_read_field('importe', array($this, '_column_left_number'));
        $this->grocery_crud->callback_column('importe', array($this, '_column_right_number'));
        $this->grocery_crud->callback_column('total_iva', array($this, '_column_right_number'));
        $this->grocery_crud->callback_column('base', array($this, '_column_right_number'));
        $this->grocery_crud->callback_column('otrosCostes', array($this, '_column_right_number'));
        $this->grocery_crud->callback_column('fecha', array($this, '_column_center_align'));
        $this->grocery_crud->callback_column('nombre_proveedor', array($this, '_column_nombre_proveedor'));

        $this->grocery_crud->set_relation('id_proveedor', 'pe_proveedores_acreedores', 'nombre');

        $this->grocery_crud->edit_fields('id_proveedor', 'numFactura', 'fecha', 'importe', 'fecha_pago');
        $this->grocery_crud->columns('id_proveedor', 'numFactura', 'num_albaran', 'fecha', 'base',  'total_iva', 'importe', 'fecha_pago');


        //$this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        // $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['tituloRango'] = 'Facturas Proveedores';
        $textoTipo = "";
        if ($tipo == "" || $tipo == "todas") $textoTipo = "(todas)";
        if ($tipo == "pagadas") $textoTipo = "(pagadas)";
        if ($tipo == "sinPagar") $textoTipo = "(sin pagar)";

        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Facturas Proveedor ' . $textoTipo . ' entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);


        $output['titulo'] = 'Facturas Proveedores';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_facturas_proveedores($output, "Facturas Proveedor");
    }



    function formateo_concepto($post_array)
    {
        $post_array['concepto'] = ucfirst($post_array['concepto']);
        return $post_array;
    }
    function formateo_facturas_acreedores($post_array)
    {
        $post_array['importe_total'] = str_replace(',', '.', $post_array['importe_total']);
        $post_array['importe_iva'] = str_replace(',', '.', $post_array['importe_iva']);
        return $post_array;
    }

    public function conceptosAcreedores()
    {
        $this->movimiento('conceptosAcreedores');
        $this->grocery_crud->set_table('pe_conceptos_acreedores');

        $this->grocery_crud->order_by('concepto');
        $this->grocery_crud->display_as('id', 'Identificador');
        $this->grocery_crud->display_as('concepto', 'Concepto Acreedor');

        $this->grocery_crud->required_fields('concepto');

        //$this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $this->grocery_crud->callback_before_insert(array($this, 'formateo_concepto'));
        $this->grocery_crud->callback_before_update(array($this, 'formateo_concepto'));


        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Conceptos Acreedores';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_conceptos_acreedores($output, "Conceptos Acreedores");
    }

    public function formasPagosAcreedores()
    {
        $this->movimiento('formaPagoAcreedores');
        $this->grocery_crud->set_table('pe_formas_pagos_acreedores');

        $this->grocery_crud->order_by('forma_pago');
        $this->grocery_crud->display_as('id', 'Identificador');
        $this->grocery_crud->display_as('forma_pago', 'Forma pago acreedor');

        $this->grocery_crud->required_fields('forma_pago');

        //$this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $this->grocery_crud->callback_before_insert(array($this, 'formateo_concepto'));
        $this->grocery_crud->callback_before_update(array($this, 'formateo_concepto'));


        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Formas Pago Acreedores';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_formas_pagos_acreedores($output, "Formas Pago Acreedores");
    }


    public function transformaciones()
    {
        $this->movimiento('transformaciones');
        $this->grocery_crud->set_table('pe_transformaciones');

        $this->grocery_crud->order_by('id_transformacion', 'desc');

        if (strtolower($this->session->username) != 'pernilall') {
            $this->grocery_crud->where('fecha >=',strval(date('Y')-1));
        }



        $this->grocery_crud->add_action('Deshacer', '', '', 'fa fa-sign-in', array($this, '_deshacer_transformacion'));

        $this->grocery_crud->display_as('id_transformacion', 'Transf.');
        //  $this->grocery_crud->display_as('id_pedido', 'Basado en pedido');


        //    $this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));
        //    $this->grocery_crud->callback_column('total', array($this, '_column_right_number'));

        //    $this->grocery_crud->set_relation('id_proveedor', 'pe_proveedores','nombre_proveedor');
        //    $this->grocery_crud->set_relation('id_pedido', 'pe_pedidos_proveedores','{nombreArchivoPedido}');
        //    $this->grocery_crud->callback_column($this->unique_field_name('id_pedido'), array($this, '_column_id_pedido'));
        //$this->grocery_crud->set_primary_key('id','pe_pedidos_proveedores');

        //$this->grocery_crud->set_field_upload('id_pedido', 'pedidos');
        // mensaje('$this->session->categoria '.$this->session->categoria);

        /* pendiente de confirmar si Sergi ouede ver la transformación

        if ($this->session->categoria == 2){
            $this->grocery_crud->unset_read();
        }
*/

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();




        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Transformaciones';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_transformaciones($output, "Transformaciones");
    }

    public function grupos()
    {
        $this->grocery_crud->set_table('pe_grupos');
        $this->grocery_crud->set_subject('Grupos');

        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->required_fields('nombre_grupo',  'id_iva');
        $this->grocery_crud->display_as('id_grupo', 'Grupo');
        $this->grocery_crud->display_as('nombre_grupo', 'Nombre del Grupo');
        $this->grocery_crud->display_as('id_iva', '% IVA de este grupo');

        $this->grocery_crud->set_relation('id_iva', 'pe_ivas', 'valor_iva');

        $this->grocery_crud->fields('nombre_grupo', 'id_iva');

        $this->grocery_crud->unique_fields('nombre_grupo');


        $this->grocery_crud->callback_after_insert(array($this, 'after_insert_grupo'));


        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Grupos';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output, "Grupos");
    }

    public function trackingPrestashop()
    {
        $this->grocery_crud->set_table('pe_email_tracking');
        $this->grocery_crud->set_subject('Tracking PrestaShop');
        $this->grocery_crud->order_by('num_pedido', 'desc');
        if (strtolower($this->session->username) != 'pernilall') {
            $this->grocery_crud->where('fecha_envio >=',strval(date('Y')-1));
        }

        $this->grocery_crud->display_as('num_pedido', 'Núm. pedido');
        $this->grocery_crud->display_as('fecha_envio', 'Fecha Envío');
        $this->grocery_crud->display_as('delivery_firstname', 'Nombre');
        $this->grocery_crud->display_as('delivery_lastname', 'Apellido');
        $this->grocery_crud->display_as('delivery_country', 'País');
        $this->grocery_crud->display_as('customer_email', 'Email');
        $this->grocery_crud->display_as('reference', 'Referencia');
        $this->grocery_crud->display_as('shop_name', 'Tienda online');
        $this->grocery_crud->columns('num_pedido', 'fecha_envio', 'delivery_firstname', 'delivery_lastname', 'delivery_country');

        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Tracking PrestaShop';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_tracking_prestashop($output, "Tracking Prestashop");
    }



    public function productosPrestashop($inicio = "", $final = "")
    {
        /*
        $sql="DELETE FROM  pe_tabla_lineas_orders_prestashop WHERE 1";
        $this->db->query($sql);

        $sql="SELECT * FROM pe_lineas_orders_prestashop";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $id_order=$v->id_order;
            $cantidad=$v->cantidad;
            $precio=$v->precio/100;
            $importe=$v->importe/100;
            $valid=$v->valid;
            $importe_con_descuento=$v->importe_con_descuento/100;
            $iva_con_descuento=$v->iva_con_descuento/100;
            $es_pack=$v->es_pack;
            //$fecha=$v->fecha;

            $this->db->query("INSERT INTO pe_tabla_lineas_orders_prestashop SET 
                    id='$id', 
                    id_order='$id_order', 
                    cantidad='$cantidad', 
                    precio='$precio', 
                    importe='$importe', 
                    valid='$valid', 
                    importe_con_descuento='$importe_con_descuento', 
                    iva_con_descuento='$iva_con_descuento', 
                    es_pack='$es_pack'");

        }


        $sql="SELECT id,id_pe_producto,id_order FROM pe_lineas_orders_prestashop";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $id_pe_producto=$v->id_pe_producto;
            $id_order=$v->id_order;
            $sql="SELECT codigo_producto, nombre FROM pe_productos WHERE id='$id_pe_producto'";
            $row=$this->db->query($sql)->row();
            $codigo_producto=$row->codigo_producto;
            $nombre_producto=$row->nombre;
            $sql="SELECT customer_id as id_cliente,fecha FROM pe_orders_prestashop WHERE id='$id_order'";
            $row=$this->db->query($sql)->row();
            $id_cliente=$row->id_cliente;
            $fecha=$row->fecha;
            $sql="SELECT firstname, lastname FROM pe_clientes_jamonarium WHERE id='$id_cliente'";
            $row=$this->db->query($sql)->row();
            $nombre_cliente=$row->firstname.' '.$row->lastname;
            $this->db->query("UPDATE pe_tabla_lineas_orders_prestashop SET codigo_producto='$codigo_producto',nombre_producto='$nombre_producto',id_cliente='$id_cliente', fecha='$fecha', nombre_cliente='$nombre_cliente' WHERE id='$id'");
        }
*/


        $this->grocery_crud->set_table('pe_tabla_lineas_orders_prestashop');

        $this->grocery_crud->set_subject('Productos Ventas PrestaShop');

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        //$this->grocery_crud->set_relation('id_pe_producto','pe_productos','nombre');

        $output = $this->grocery_crud->render();

        $output = (array)$output;
        //$output['titulo'] = 'Pedidos Prestashop entre Entre '.$inicio." y ".$final;
        $output['tituloRango'] = 'Productos Ventas Prestashop TODOS';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_productos_prestashop($output, "Productos Vendidos Prestashop");
    }




    public function pedidosPrestashopEntreFechas()
    {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $dato['anys'] = array();
        $dato['anys'] = array("Seleccionar año");
        if (strtolower($this->session->username) == 'pernilall') {
            for ($x = date('Y'); $x >= 2009; $x--) {
                $dato['anys'][] = $x;
            }
        }
        else{
            for ($x = date('Y'); $x >= date('Y')-1; $x--) {
                $dato['anys'][] = $x;
            }
        }
        $dato['meses'] = array("Seleccionar mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('pedidosPrestashopEntreFechas', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
    }

    public function ticketsTiendaEntreFechas()
    {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $dato['anys'] = array("Seleccionar año");
        // mensaje('username ' . $this->session->username);
        if (strtolower($this->session->username) == 'pernilall') {
            for ($x = date('Y'); $x >= 2014; $x--) {
                $dato['anys'][] = $x;
            }
        }
        else{
            for ($x = date('Y'); $x >= date('Y')-1; $x--) {
                $dato['anys'][] = $x;
            }
        }
        $dato['meses'] = array("Seleccionar mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('ticketsTiendaEntreFechas', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
    }

    public function tickets_TiendaEntreFechas()
    {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $dato['anys'] = array("Seleccionar año");

        if (strtolower($this->session->username) == 'pernilall') {
            for ($x = date('Y'); $x >= 2009; $x--) {
                $dato['anys'][] = $x;
            }
        }
        else{
            for ($x = date('Y'); $x >= date('Y')-1; $x--) {
                $dato['anys'][] = $x;
            }
        }
        $dato['meses'] = array("Seleccionar mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('tickets_TiendaEntreFechas', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
    }


    public function facturasProveedoresTotales()
    {
        $sql = $_POST['sql'];
        // mensaje($sql);
        $row = $this->db->query($sql)->row_array();
        $row['totalImporte'] = number_format($row['totalImporte'] / 100, 2, ",", ".");
        $row['totalIva'] = number_format($row['totalIva'] / 100, 2, ",", ".");
        $row['totalBase'] = number_format($row['totalBase'] / 100, 2, ",", ".");

        echo  json_encode($row);
    }
    public function facturasAcreedoresTotales()
    {
        $sql = $_POST['sql'];
        // mensaje($sql);
        $row = $this->db->query($sql)->row_array();
        $row['importeTotal'] = number_format($row['importeTotal'], 2, ",", ".");
        $row['importeIva'] = number_format($row['importeIva'], 2, ",", ".");

        echo  json_encode($row);
    }

    public function pedidosPrestashopTotales()
    {
        $sql = $_POST['sql'];
        // mensaje($sql);
        $row = $this->db->query($sql)->row_array();
        $row['total'] = number_format($row['total'] / 100, 2, ",", ".");
        $row['num_orders'] = number_format($row['num_orders'], 0, ",", ".");
        $row['descuento'] = number_format($row['descuento'] / 100, 2, ",", ".");
        $row['total_producto'] = number_format($row['total_producto'] / 100, 2, ",", ".");
        $row['total_base'] = number_format($row['total_base'] / 100, 2, ",", ".");
        $row['total_iva'] = number_format($row['total_iva'] / 100, 2, ",", ".");
        $row['transporte'] = number_format($row['transporte'] / 1000, 2, ",", ".");
        $row['base_transporte'] = number_format($row['base_transporte'] / 1000, 2, ",", ".");
        $row['iva_transporte'] = number_format($row['iva_transporte'] / 1000, 2, ",", ".");
        $row['base_factura'] = number_format($row['base_factura'] / 100, 2, ",", ".");
        $row['total_pedido'] = number_format($row['total_pedido'] / 1000, 2, ",", ".");

        if (!isset($row['peso_transporte'])) $row['peso_transporte'] = 0;
        $row['peso_transporte'] = number_format($row['peso_transporte'] / 100, 2, ",", ".");

        if (!isset($row['bultos_transporte'])) $row['bultos_transporte'] = 0;
        $row['bultos_transporte'] = number_format($row['bultos_transporte'] / 100, 2, ",", ".");

        if (!isset($row['base_tarifa'])) $row['base_tarifa'] = 0;
        $row['base_tarifa'] = number_format($row['base_tarifa'] / 100, 2, ",", ".");

        if (!isset($row['base_suplementos'])) $row['base_suplementos'] = 0;
        $row['base_suplementos'] = number_format($row['base_suplementos'] / 100, 2, ",", ".");

        if (!isset($row['diferencia_base_transporte_base_factura'])) $row['diferencia_base_transporte_base_factura'] = 0;
        $row['diferencia_base_transporte_base_factura'] = number_format($row['diferencia_base_transporte_base_factura'] / 100, 2, ",", ".");

        if (!isset($row['suple_dif'])) $row['suple_dif'] = 0;
        $row['suple_dif'] = number_format($row['suple_dif'] / 100, 2, ",", ".");




        echo  json_encode($row);
    }

    public function ticketsTiendaTotales()
    {
        $sql = $_POST['sql'];
        $row = $this->db->query($sql)->row_array();
        $row['total'] = number_format($row['total'] / 100, 2, ",", ".");
        echo  json_encode($row);
    }
    public function ticketsAlternativosTiendaTotales()
    {
        // $this->db = $this->load->database('pernil181bcn',true);

        $sql = $_POST['sql'];
        $row = $this->load->database('pernil181bcn', true)->query($sql)->row_array();
        $row['total'] = number_format($row['total'] / 100, 2, ",", ".");
        echo  json_encode($row);
    }

    public function pedidosPrestashopTransportes($inicio = "", $final = "")
    {
        //id (num pedido) no se ve en tabla ver -> ponemos campo pedido
        $this->db->query("UPDATE pe_orders_prestashop SET pedido=id");
        if (!$inicio) $inicio = '2018-02-23';
        if (!$final) $final = date('Y-m-d');
        if ($inicio < '2018-02-23') $inicio = '2018-02-23';
        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;
        $this->movimiento('pedidosPrestashop');
        $this->grocery_crud->set_table('pe_orders_prestashop');

        $where = "";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        }
        if ($where) $this->grocery_crud->where($where);

        $this->grocery_crud->set_subject('Transportes Pedidos PrestaShop');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id', 'Núm. pedido');
        $this->grocery_crud->display_as('delivery_country', 'País');
        $this->grocery_crud->display_as('delivery_country_transporte', 'País destino');


        $this->grocery_crud->display_as('fecha', 'Fecha pedido');

        $this->grocery_crud->display_as('peso_envio', 'Peso envío (Kg)');
        $this->grocery_crud->display_as('bultos', 'Núm butos');
        $this->grocery_crud->display_as('base_factura', 'Base Transporte factura');
        $this->grocery_crud->display_as('base_tarifa', 'Base Transporte tarifa');
        $this->grocery_crud->display_as('base_transporte', 'Base Transporte pagada por cliente');
        $this->grocery_crud->display_as('diferencia_base_factura_base_tarifa', 'Diferencia Base factura - Base tarifa');
        $this->grocery_crud->display_as('diferencia_base_transporte_base_factura', 'Diferencia Base pagado cliente - Base factura');
        $this->grocery_crud->display_as('base_suplementos', 'Base suplementos');
        $this->grocery_crud->display_as('suplementos', 'Concepto suplementos');
        $this->grocery_crud->display_as('suple_fsf', 'Suplementos Combustible');
        $this->grocery_crud->display_as('suple_it1', 'Suplementos Responsabilidad');
        $this->grocery_crud->display_as('suple_res', 'Suplementos Residencia');
        $this->grocery_crud->display_as('suple_ess', 'Suplementos Seguridad');
        $this->grocery_crud->display_as('suple_rwa', 'Suplementos Corrección Dirección');
        $this->grocery_crud->display_as('suple_oa', 'Suplementos Fuera Area');
        $this->grocery_crud->display_as('suple_cl2', 'Suplementos Gestión Exportación');
        $this->grocery_crud->display_as('suple_sns', 'Suplementos Manipulación Especial');
        $this->grocery_crud->display_as('suple_rpf', 'Suplementos Gestión Importación');
        $this->grocery_crud->display_as('suple_fie', 'Suplementos Combustible Plus');
        $this->grocery_crud->display_as('suple_nec', 'Suplementos Alb No Electrónico');
        $this->grocery_crud->display_as('suple_e28', 'Suplementos Recoger Otra Provincia');
        $this->grocery_crud->display_as('suple_rpd', 'Suplementos Domestic Receveir Pays');
        $this->grocery_crud->display_as('suple_cl9', 'Suplementos Servicio Islas');
        $this->grocery_crud->display_as('suple_mam', 'Suplementos Manipulación Montaje');
        $this->grocery_crud->display_as('suple_aduana', 'Suplementos Aduana');
        $this->grocery_crud->display_as('pedido', 'Núm. pedido');
        $this->grocery_crud->display_as('post_code', 'Código postal');
        $this->grocery_crud->display_as('suple_dif', 'Base suplenentos diferencias');



        $this->grocery_crud->callback_column('base_transporte_segun_precios', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_column('diferencia_base_factura_base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('diferencia_base_transporte_base_factura', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_column('peso_transporte', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('bultos_transporte', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_column('base_factura', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_suplementos', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('suple_dif', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('pedido', array($this, '_callback_num_pedido_prestashop'));


        $this->grocery_crud->callback_read_field('base_transporte_segun_precios', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_read_field('diferencia_base_factura_base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('diferencia_base_transporte_base_factura', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('peso_transporte', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('bultos_transporte', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('base_factura', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_read_field('base_suplementos', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_read_field('suple_fsf', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_it1', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_res', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_ess', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_rwa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_oa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_cl2', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_sns', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_rpf', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_fie', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_nec', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_e28', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_rpd', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_cl9', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_mam', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_aduana', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_dif', array($this, '_column_total_prestashop'));



        $this->grocery_crud->columns(
            'fecha',
            'pedido',
            'post_code',
            'delivery_country_transporte',
            'peso_transporte',
            'bultos_transporte',
            'base_transporte_segun_precios',

            'base_tarifa',
            'base_suplementos',
            'suple_dif',
            'base_factura',
            'base_transporte',
            //'diferencia_base_factura_base_tarifa',
            'diferencia_base_transporte_base_factura',

            'suplementos',
            'nombre_transportista'
        );

        /*
        $this->grocery_crud->fields(
            'fecha',
            'id',
            'delivery_country',
            'peso_envio',
            'bultos',
            'base_factura',  //base_factura
            'base_tarifa',
            'base_transporte',
            'diferencia_base_factura_base_tarifa',
            'diferencia_base_transporte_base_factura',
            'base_suplementos',
            'suplementos',
            'nombre_transportista'
        );
*/
        $this->grocery_crud->unset_read_fields(array('delivery_country', 'coste_transporte', 'descuento', 'customer_id', 'customer_id_group', 'tipo_iva_transporte', 'total_base', 'total_iva', 'total', 'total_producto', 'iva_transporte', 'transporte', 'transporte_original_igual_descuento', 'total_pedido', 'customer_email', 'customer_id_language', 'reference', 'delivery_firstname', 'delivery_lastname', 'shop_name'));


        //para exportar a Excel TODA la tabla, columnas seleccionadas
        if ($this->uri->segment(3) == 'export') {
            $query = $this->db->query("select * from pe_orders_prestashop");
            $string = [];
            foreach ($query->result_array() as $row) {
                $string = array_keys($row);
            }
            $this->grocery_crud->columns($string);
        }

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();



        $output = (array)$output;
        //$output['titulo'] = 'Pedidos Prestashop entre Entre '.$inicio." y ".$final;
        $output['tituloRango'] = 'Transportes Pedidos Prestashop TODOS';
        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Transporte Pedidos Prestashop entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_pedidos_prestashop_transportes($output, "Transporte Pedidos Prestashop entre fechas");
    }

    public function pedidosPrestashopTransportesNum($pedido = "")
    {
        //id (num pedido) no se ve en tabla ver -> ponemos campo pedido
        $this->db->query("UPDATE pe_orders_prestashop SET pedido=id");
        // if (!$inicio) $inicio = '2018-02-23';
        // if (!$final) $final = date('Y-m-d');
        // if ($inicio < '2018-02-23') $inicio = '2018-02-23';
        // $_SESSION['inicio'] = $inicio;
        // $_SESSION['final'] = $final;
        $this->movimiento('pedidosPrestashopTransportesNum');
        $this->grocery_crud->set_table('pe_orders_prestashop');

        $where = "";
        // if ($inicio && $final) {
        //     $finalDia = $final . ' 23:59';
        //     $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        // }
        $where .= " pedido='$pedido'";
        if(strtolower($this->session->username)!='pernilall'){
            $fecha_min=date('Y')-1;
            $where .=" AND fecha>='$fecha_min'";
        }
        if ($where) $this->grocery_crud->where($where);



        $this->grocery_crud->set_subject('Transportes Pedidos PrestaShop');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id', 'Núm. pedido');
        $this->grocery_crud->display_as('delivery_country', 'País');
        $this->grocery_crud->display_as('delivery_country_transporte', 'País destino');


        $this->grocery_crud->display_as('fecha', 'Fecha pedido');

        $this->grocery_crud->display_as('peso_envio', 'Peso envío (Kg)');
        $this->grocery_crud->display_as('bultos', 'Núm butos');
        $this->grocery_crud->display_as('base_factura', 'Base Transporte factura');
        $this->grocery_crud->display_as('base_tarifa', 'Base Transporte tarifa');
        $this->grocery_crud->display_as('base_transporte', 'Base Transporte pagada por cliente');
        $this->grocery_crud->display_as('diferencia_base_factura_base_tarifa', 'Diferencia Base factura - Base tarifa');
        $this->grocery_crud->display_as('diferencia_base_transporte_base_factura', 'Diferencia Base pagado cliente - Base factura');
        $this->grocery_crud->display_as('base_suplementos', 'Base suplementos');
        $this->grocery_crud->display_as('suplementos', 'Concepto suplementos');
        $this->grocery_crud->display_as('suple_fsf', 'Suplementos Combustible');
        $this->grocery_crud->display_as('suple_it1', 'Suplementos Responsabilidad');
        $this->grocery_crud->display_as('suple_res', 'Suplementos Residencia');
        $this->grocery_crud->display_as('suple_ess', 'Suplementos Seguridad');
        $this->grocery_crud->display_as('suple_rwa', 'Suplementos Corrección Dirección');
        $this->grocery_crud->display_as('suple_oa', 'Suplementos Fuera Area');
        $this->grocery_crud->display_as('suple_cl2', 'Suplementos Gestión Exportación');
        $this->grocery_crud->display_as('suple_sns', 'Suplementos Manipulación Especial');
        $this->grocery_crud->display_as('suple_rpf', 'Suplementos Gestión Importación');
        $this->grocery_crud->display_as('suple_fie', 'Suplementos Combustible Plus');
        $this->grocery_crud->display_as('suple_nec', 'Suplementos Alb No Electrónico');
        $this->grocery_crud->display_as('suple_e28', 'Suplementos Recoger Otra Provincia');
        $this->grocery_crud->display_as('suple_rpd', 'Suplementos Domestic Receveir Pays');
        $this->grocery_crud->display_as('suple_cl9', 'Suplementos Servicio Islas');
        $this->grocery_crud->display_as('suple_mam', 'Suplementos Manipulación Montaje');
        $this->grocery_crud->display_as('suple_aduana', 'Suplementos Aduana');
        $this->grocery_crud->display_as('pedido', 'Núm. pedido');
        $this->grocery_crud->display_as('post_code', 'Código postal');
        $this->grocery_crud->display_as('suple_dif', 'Base suplenentos diferencias');



        $this->grocery_crud->callback_column('base_transporte_segun_precios', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_column('diferencia_base_factura_base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('diferencia_base_transporte_base_factura', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_column('peso_transporte', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('bultos_transporte', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_column('base_factura', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_suplementos', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('suple_dif', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('pedido', array($this, '_callback_num_pedido_prestashop'));


        $this->grocery_crud->callback_read_field('base_transporte_segun_precios', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_read_field('diferencia_base_factura_base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('diferencia_base_transporte_base_factura', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('peso_transporte', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('bultos_transporte', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('base_factura', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('base_tarifa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_read_field('base_suplementos', array($this, '_column_total_prestashop'));

        $this->grocery_crud->callback_read_field('suple_fsf', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_it1', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_res', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_ess', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_rwa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_oa', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_cl2', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_sns', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_rpf', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_fie', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_nec', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_e28', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_rpd', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_cl9', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_mam', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_aduana', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_read_field('suple_dif', array($this, '_column_total_prestashop'));



        $this->grocery_crud->columns(
            'fecha',
            'pedido',
            'post_code',
            'delivery_country_transporte',
            'peso_transporte',
            'bultos_transporte',
            'base_transporte_segun_precios',

            'base_tarifa',
            'base_suplementos',
            'suple_dif',
            'base_factura',
            'base_transporte',
            //'diferencia_base_factura_base_tarifa',
            'diferencia_base_transporte_base_factura',

            'suplementos',
            'nombre_transportista'
        );

        /*
        $this->grocery_crud->fields(
            'fecha',
            'id',
            'delivery_country',
            'peso_envio',
            'bultos',
            'base_factura',  //base_factura
            'base_tarifa',
            'base_transporte',
            'diferencia_base_factura_base_tarifa',
            'diferencia_base_transporte_base_factura',
            'base_suplementos',
            'suplementos',
            'nombre_transportista'
        );
*/
        $this->grocery_crud->unset_read_fields(array('delivery_country', 'coste_transporte', 'descuento', 'customer_id', 'customer_id_group', 'tipo_iva_transporte', 'total_base', 'total_iva', 'total', 'total_producto', 'iva_transporte', 'transporte', 'transporte_original_igual_descuento', 'total_pedido', 'customer_email', 'customer_id_language', 'reference', 'delivery_firstname', 'delivery_lastname', 'shop_name'));


        //para exportar a Excel TODA la tabla, columnas seleccionadas
        if ($this->uri->segment(3) == 'export') {
            $query = $this->db->query("select * from pe_orders_prestashop");
            $string = [];
            foreach ($query->result_array() as $row) {
                $string = array_keys($row);
            }
            $this->grocery_crud->columns($string);
        }

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();



        $output = (array)$output;
        //$output['titulo'] = 'Pedidos Prestashop entre Entre '.$inicio." y ".$final;
        $output['tituloRango'] = 'Transportes Pedidos Prestashop pedido ' . $pedido;
        // if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Transporte Pedidos Prestashop entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_pedidos_prestashop_transportes($output, "Transporte Pedidos Prestashop entre fechas");
    }

    public function pedidosPrestashop($inicio = "", $final = "")
    {
        if (!$inicio) $inicio = '2018-02-23';
        if (!$final) $final = date('Y-m-d');
        if ($inicio < '2018-02-23') $inicio = '2018-02-23';
        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;
        $this->movimiento('pedidosPrestashop');
        $this->grocery_crud->set_table('pe_orders_prestashop');

        $where = "";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        }
        if ($where) $this->grocery_crud->where($where);
        mensaje($where);

        $this->grocery_crud->set_subject('Pedidos PrestaShop');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id', 'Núm. pedido');
        $this->grocery_crud->display_as('customer_id_group', 'Tipo cliente');
        $this->grocery_crud->display_as('customer_id', 'Cliente');
        $this->grocery_crud->display_as('base_factura', 'Base coste transporte');
        $this->grocery_crud->display_as('delivery_country', 'País');

        //$this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));
        $this->grocery_crud->callback_column('total', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_base', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_iva', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('descuento', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_producto', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('iva_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_factura', array($this, '_column_right_base_factura'));

        $this->grocery_crud->callback_column('total_pedido', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('customer_id', array($this, '_callback_num_cliente_prestashop'));

        $this->grocery_crud->columns(
            'fecha',
            'id',
            'customer_id_group',
            'customer_id',
            'total',
            'descuento',
            'total_producto',
            'total_base',
            'total_iva',
            'transporte',
            'base_transporte',
            'iva_transporte',
            'base_factura',
            'total_pedido',
            'delivery_country'
        );

        //para exportar a Excel TODA la tabla, columnas seleccionadas
        if ($this->uri->segment(3) == 'export') {
            $query = $this->db->query("select * from pe_orders_prestashop");
            $string = [];
            foreach ($query->result_array() as $row) {
                $string = array_keys($row);
            }
            $this->grocery_crud->columns($string);
        }

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();



        $output = (array)$output;
        //$output['titulo'] = 'Pedidos Prestashop entre Entre '.$inicio." y ".$final;
        $output['tituloRango'] = 'Pedidos Prestashop TODOS';
        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Pedidos Prestashop entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_pedidos_prestashop($output, "Pedidos Prestashop entre fechas");
    }

    public function pedidosPrestashopNum($pedido)
    {
        $inicio = "";
        $final = "";
        $_SESSION['pedido'] = $pedido;
        $this->movimiento('pedidosPrestashopNum');
        $this->grocery_crud->set_table('pe_orders_prestashop');

        $where = "pedido='$pedido '";
        if(strtolower($this->session->username)!='pernilall'){
            $fecha_min=date('Y')-1;
            $where .=" AND fecha>='$fecha_min'";
        }
        if ($where) $this->grocery_crud->where($where);

        $this->grocery_crud->set_subject('Pedidos PrestaShop');
        // $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id', 'Núm. pedido');
        $this->grocery_crud->display_as('customer_id_group', 'Tipo cliente');
        $this->grocery_crud->display_as('customer_id', 'Cliente');
        $this->grocery_crud->display_as('base_factura', 'Base coste transporte');
        $this->grocery_crud->display_as('delivery_country', 'País');

        //$this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));
        $this->grocery_crud->callback_column('total', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_base', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_iva', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('descuento', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_producto', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('iva_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_factura', array($this, '_column_right_base_factura'));

        $this->grocery_crud->callback_column('total_pedido', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('customer_id', array($this, '_callback_num_cliente_prestashop'));

        $this->grocery_crud->columns(
            'fecha',
            'id',
            'customer_id_group',
            'customer_id',
            'total',
            'descuento',
            'total_producto',
            'total_base',
            'total_iva',
            'transporte',
            'base_transporte',
            'iva_transporte',
            'base_factura',
            'total_pedido',
            'delivery_country'
        );

        //para exportar a Excel TODA la tabla, columnas seleccionadas
        if ($this->uri->segment(3) == 'export') {
            $query = $this->db->query("select * from pe_orders_prestashop");
            $string = [];
            foreach ($query->result_array() as $row) {
                $string = array_keys($row);
            }
            $this->grocery_crud->columns($string);
        }

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();



        $output = (array)$output;
        //$output['titulo'] = 'Pedidos Prestashop entre Entre '.$inicio." y ".$final;
        $output['tituloRango'] = "Pedido Prestashop núm $pedido";
        // if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Pedidos Prestashop entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_pedidos_prestashop($output, "Pedido Prestashop núm $pedido");
    }

    public function _callback_num_cliente_prestashop($value, $row)
    {
        //pendiente de hacer crear acción sobre click en el núm del cliente
        return '<a href="#" class="cliente_prestashop">' . $value . '</a>';

        return $value;
    }
    public function _callback_num_pedido_prestashop($value, $row)
    {
        //pendiente de hacer crear acción sobre click en el núm del cliente
        return '<a href="#" class="pedido_prestashop">' . $value . '</a>';

        return $value;
    }




    public function pedidosPrestashopTabla($inicio = "", $final = "")
    {
        $this->movimiento('pedidosPrestashop');
        $this->grocery_crud->set_table('pe_tabla_orders_prestashop');

        $where = "";
        if ($inicio && $final) $where = "fecha >= '$inicio' AND fecha <='$final'";
        if ($where) $this->grocery_crud->where($where);

        $this->grocery_crud->set_subject('Pedidos PrestaShop');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('id', 'Núm. pedido');
        $this->grocery_crud->display_as('customer_id_group', 'Tipo cliente');
        $this->grocery_crud->display_as('customer_id', 'Cliente');
        //$this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));
        /*
        $this->grocery_crud->callback_column('total', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_base', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_iva', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('descuento', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('total_producto', array($this, '_column_total_prestashop'));
        $this->grocery_crud->callback_column('transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('base_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('iva_transporte', array($this, '_column_right_transporte'));
        $this->grocery_crud->callback_column('total_pedido', array($this, '_column_right_transporte'));
        */
        $this->grocery_crud->columns(
            'fecha',
            'id',
            'customer_id_group',
            'customer_id',
            'total',
            'descuento',
            'total_producto',
            'total_base',
            'total_iva',
            'transporte',
            'base_transporte',
            'iva_transporte',
            'total_pedido'
        );


        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        //$output['titulo'] = 'Pedidos Prestashop entre Entre '.$inicio." y ".$final;
        $output['tituloRango'] = 'Pedidos Prestashop TODOS';
        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Pedidos Prestashop entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_pedidos_prestashop_tabla($output, "Pedidos Prestashop entre fechas");
    }


    public function estudioMercadoProductos()
    {
        //antes de cargar página se actualiza tabla con codigos de producto precion pvp.compra e ivas, nombre productos
        $this->load->model('productos_');
        $this->productos_->actualizarProductosMercado();
        $this->grocery_crud->set_table('pe_productos_mercado');
        $columnas = array(
            'codigo_producto', 'nombre', 'pvp0', 'precio_compra0', 'beneficio0',
            'url_web1', 'pvp1', 'precio_compra1',
            'url_web2', 'pvp2', 'precio_compra2',
            'url_web3', 'pvp3', 'precio_compra3'
        );
        $columnasMostrar = array(
            'codigo_producto',
            'nombre',
            'iva',
            'tipo_precio',
            'peso_real',
            'fecha_1',
            'url_web1',
            'tarifa_venta_unidad1',
            'tarifa_venta_peso1',
            'fecha_2',
            'url_web2',
            'tarifa_venta_unidad2',
            'tarifa_venta_peso2',
            'fecha_3',
            'url_web3',
            'tarifa_venta_unidad3',
            'tarifa_venta_peso3',
        );
        $this->grocery_crud->columns($columnas);
        $this->grocery_crud->fields($columnasMostrar);


        $this->grocery_crud->unset_fields(
            'beneficio1',
            'beneficio2',
            'beneficio3',
            'tarifa_venta_peso0',
            'tarifa_venta_unidad0',
            'precio_ultimo_peso0',
            'precio_ultimo_unidad0',

            'precio_ultimo_peso1',
            'precio_ultimo_unidad1',

            'precio_ultimo_peso2',
            'precio_ultimo_unidad2',

            'precio_ultimo_peso3',
            'precio_ultimo_unidad3'
        );


        //validaciones
        $this->grocery_crud->set_rules('tarifa_venta_unidad1', 'tarifa PVP unidad', 'callback_compararTarifa_unidad_vs_Tipo_precio[' . $this->input->post('tipo_precio') . ']');
        $this->grocery_crud->set_rules('tarifa_venta_unidad2', 'tarifa PVP unidad', 'callback_compararTarifa_unidad_vs_Tipo_precio[' . $this->input->post('tipo_precio') . ']');
        $this->grocery_crud->set_rules('tarifa_venta_unidad3', 'tarifa PVP unidad', 'callback_compararTarifa_unidad_vs_Tipo_precio[' . $this->input->post('tipo_precio') . ']');

        $this->grocery_crud->set_rules('tarifa_venta_peso1', 'tarifa PVP peso', 'callback_compararTarifa_peso_vs_Tipo_precio[' . $this->input->post('tipo_precio') . ']');
        $this->grocery_crud->set_rules('tarifa_venta_peso2', 'tarifa PVP peso', 'callback_compararTarifa_peso_vs_Tipo_precio[' . $this->input->post('tipo_precio') . ']');
        $this->grocery_crud->set_rules('tarifa_venta_peso3', 'tarifa PVP peso', 'callback_compararTarifa_peso_vs_Tipo_precio[' . $this->input->post('tipo_precio') . ']');


        $this->grocery_crud->unset_add();
        //$this->grocery_crud->unset_delete();
        $this->grocery_crud->unset_edit();

        $this->grocery_crud->display_as('codigo_producto', 'Código Producto');
        $this->grocery_crud->display_as('pvp0', 'PVP Actual');
        $this->grocery_crud->display_as('precio_compra0', 'Precio Compra Actual');
        $this->grocery_crud->display_as('beneficio0', 'Beneficio % Actual');

        $this->grocery_crud->display_as('iva', 'Tipo iva %');

        $this->grocery_crud->display_as('tarifa_venta_peso0', 'PVP actual €/Kg');
        $this->grocery_crud->display_as('tarifa_venta_unidad0', 'PVP actual €/unidad');

        $this->grocery_crud->display_as('precio_ultimo_peso0', 'Precio Compra Actual €/Kg');
        $this->grocery_crud->display_as('precio_ultimo_unidad0', 'Precio Compra Actual €/unidad');

        $this->grocery_crud->display_as('pvp1', 'PVP Web 1');
        $this->grocery_crud->display_as('pvp2', 'PVP Web 2');
        $this->grocery_crud->display_as('pvp3', 'PVP Web 3');

        $this->grocery_crud->display_as('precio_compra1', 'Precio Compra Web 1 (30 %)');
        $this->grocery_crud->display_as('precio_compra2', 'Precio Compra Web 2 (30 %)');
        $this->grocery_crud->display_as('precio_compra3', 'Precio Compra Web 3 (30 %)');

        $this->grocery_crud->display_as('fecha_1', 'Fecha consulta 1');
        $this->grocery_crud->display_as('fecha_2', 'Fecha consulta 2');
        $this->grocery_crud->display_as('fecha_3', 'Fecha consulta 3');

        $this->grocery_crud->display_as('url_web1', 'Web 1');
        $this->grocery_crud->display_as('url_web2', 'Web 2');
        $this->grocery_crud->display_as('url_web3', 'Web 3');

        $this->grocery_crud->display_as('tarifa_venta_peso1', 'PVP 1 €/Kg');
        $this->grocery_crud->display_as('tarifa_venta_peso2', 'PVP 2 €/Kg');
        $this->grocery_crud->display_as('tarifa_venta_peso3', 'PVP 3 €/Kg');

        $this->grocery_crud->display_as('tarifa_venta_unidad1', 'PVP 1 €/unidad');
        $this->grocery_crud->display_as('tarifa_venta_unidad2', 'PVP 2 €/unidad');
        $this->grocery_crud->display_as('tarifa_venta_unidad3', 'PVP 3 €/unidad');

        $this->grocery_crud->display_as('precio_ultimo_peso1', 'Precio Compra Ben 30% €/Kg 1');
        $this->grocery_crud->display_as('precio_ultimo_unidad1', 'Precio Compra Ben 30% €/Un 1');
        $this->grocery_crud->display_as('precio_ultimo_peso2', 'Precio Compra Ben 30% €/Kg 2');
        $this->grocery_crud->display_as('precio_ultimo_unidad2', 'Precio Compra Ben 30% €/Un 2');
        $this->grocery_crud->display_as('precio_ultimo_peso3', 'Precio Compra Ben 30% €/Kg 3');
        $this->grocery_crud->display_as('precio_ultimo_unidad3', 'Precio Compra Ben 30% €/Un 3');

        $this->grocery_crud->display_as('peso1', 'Peso 1 Kg');
        $this->grocery_crud->display_as('peso2', 'Peso 2 Kg');
        $this->grocery_crud->display_as('peso3', 'Peso 3 Kg');


        $this->grocery_crud->callback_column('nombre', array($this, '_column_left_align_nombre_grid'));
        $this->grocery_crud->callback_column('tipo_precio', array($this, '_column_right_align_tipo_precio'));
        $this->grocery_crud->callback_column('pvp0', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_column('precio_compra0', array($this, '_column_right_align_precio_compra'));
        $this->grocery_crud->callback_column('beneficio0', array($this, '_column_right_align_precio'));

        $this->grocery_crud->callback_column('url_web1', array($this, '_column_url_web_grid'));
        $this->grocery_crud->callback_column('pvp1', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_column('precio_compra1', array($this, '_column_right_align_precio_compra'));

        $this->grocery_crud->callback_column('url_web2', array($this, '_column_url_web_grid'));
        $this->grocery_crud->callback_column('pvp2', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_column('precio_compra2', array($this, '_column_right_align_precio_compra'));

        $this->grocery_crud->callback_column('url_web3', array($this, '_column_url_web_grid'));
        $this->grocery_crud->callback_column('pvp3', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_column('precio_compra3', array($this, '_column_right_align_precio_compra'));


        //precalculo campos edit y add
        $this->grocery_crud->callback_field('codigo_producto', array($this, '_field_right_align_codigo_producto'));
        $this->grocery_crud->callback_field('peso_real', array($this, '_field_right_align_peso_real'));
        $this->grocery_crud->callback_field('nombre', array($this, '_field_right_align_nombre'));
        $this->grocery_crud->callback_field('iva', array($this, '_field_right_align_iva'));
        $this->grocery_crud->callback_field('tipo_precio', array($this, '_field_right_align_tipo_precio'));

        $this->grocery_crud->callback_field('url_web1', array($this, '_field_right_align_url_web1'));
        $this->grocery_crud->callback_field('url_web2', array($this, '_field_right_align_url_web2'));
        $this->grocery_crud->callback_field('url_web3', array($this, '_field_right_align_url_web3'));

        $this->grocery_crud->callback_field('tarifa_venta_unidad1', array($this, '_field_right_align_tarifa_venta_unidad1'));
        $this->grocery_crud->callback_field('tarifa_venta_peso1', array($this, '_field_right_align_tarifa_venta_peso1'));
        $this->grocery_crud->callback_field('tarifa_venta_unidad2', array($this, '_field_right_align_tarifa_venta_unidad2'));
        $this->grocery_crud->callback_field('tarifa_venta_peso2', array($this, '_field_right_align_tarifa_venta_peso2'));
        $this->grocery_crud->callback_field('tarifa_venta_unidad3', array($this, '_field_right_align_tarifa_venta_unidad3'));
        $this->grocery_crud->callback_field('tarifa_venta_peso3', array($this, '_field_right_align_tarifa_venta_peso3'));


        //precalculo tabla view 
        $this->grocery_crud->callback_read_field('tipo_precio', array($this, '_column_right_align_tipo_precio_view'));

        $this->grocery_crud->callback_read_field('pvp0', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_read_field('pvp1', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_read_field('pvp2', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_read_field('pvp3', array($this, '_column_right_align_precio_pvp'));
        $this->grocery_crud->callback_read_field('precio_compra1', array($this, '_column_right_align_precio_compra'));
        $this->grocery_crud->callback_read_field('precio_compra2', array($this, '_column_right_align_precio_compra'));
        $this->grocery_crud->callback_read_field('precio_compra3', array($this, '_column_right_align_precio_compra'));
        $this->grocery_crud->callback_read_field('fecha_1', array($this, '_column_right_align_fecha'));
        $this->grocery_crud->callback_read_field('fecha_2', array($this, '_column_right_align_fecha'));
        $this->grocery_crud->callback_read_field('fecha_3', array($this, '_column_right_align_fecha'));
        $this->grocery_crud->callback_read_field('peso1', array($this, '_column_right_align_peso'));
        $this->grocery_crud->callback_read_field('peso2', array($this, '_column_right_align_peso'));
        $this->grocery_crud->callback_read_field('peso3', array($this, '_column_right_align_peso'));


        $this->grocery_crud->callback_read_field('precio_compra0', array($this, '_column_right_align_precio_compra'));
        $this->grocery_crud->callback_read_field('beneficio0', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_read_field('tarifa_venta_unidad1', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_read_field('tarifa_venta_peso1', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_read_field('tarifa_venta_unidad2', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_read_field('tarifa_venta_peso2', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_read_field('tarifa_venta_unidad3', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_read_field('tarifa_venta_peso3', array($this, '_column_right_align_precio'));






        $this->grocery_crud->callback_read_field('url_web1', array($this, '_column_url_web'));
        $this->grocery_crud->callback_read_field('url_web2', array($this, '_column_url_web'));
        $this->grocery_crud->callback_read_field('url_web3', array($this, '_column_url_web'));

        $this->grocery_crud->callback_before_insert(array($this, '_calculos_formateos_productos_mercado_db'));
        $this->grocery_crud->callback_before_update(array($this, '_calculos_formateos_productos_mercado_db'));



        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Productos - Estudio Mercado';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output($output);
    }

    public function stocks()
    {

        $this->db->query(
            "UPDATE pe_stocks s"
                . " LEFT JOIN pe_productos p ON p.id=s.id_pe_producto "
                . " SET s.control_stock=p.control_stock"
        );

        $this->movimiento('stocks');
        $this->grocery_crud->set_table('pe_stocks');
        $this->grocery_crud->set_subject('Stocks - Fecha caducidad');
        $this->grocery_crud->order_by('codigo_producto', 'fecha_caducidad_stock');
        $this->grocery_crud->where(array('activo' => '1', 'pe_stocks.control_stock' => 'Sí'));

        $this->grocery_crud->columns('id_pe_producto', 'codigo_bascula', 'codigo_producto', 'cantidad', 'fecha_caducidad_stock', 'proveedor');

        $this->grocery_crud->unset_read_fields('id_pe_producto');


        $this->grocery_crud->display_as('codigo_producto', 'Producto');
        $this->grocery_crud->display_as('id_pe_producto', 'Código 13');
        $this->grocery_crud->display_as('codigo_bascula', 'Código Báscula');
        $this->grocery_crud->display_as('proveedor', 'Proveedor última compra');
        $this->grocery_crud->display_as('fecha_caducidad_stock', 'Fecha Caducidad');


        $this->grocery_crud->callback_column('cantidad', array($this, '_callback_column_cantidad'));
        $this->grocery_crud->callback_column('fecha_entrada', array($this, '_read_fecha_centrado'));
        $this->grocery_crud->callback_column('fecha_caducidad_stock', array($this, '_read_fecha_centrado'));
        $this->grocery_crud->callback_column('fecha_modificacion_stock', array($this, '_read_fecha_centrado'));

        $this->grocery_crud->callback_read_field('cantidad', array($this, '_read_cantidad_stocks'));
        $this->grocery_crud->callback_read_field('codigo_producto', array($this, '_callback_column_codigo_producto'));
        $this->grocery_crud->callback_read_field('fecha_entrada', array($this, '_read_fecha'));
        $this->grocery_crud->callback_read_field('fecha_caducidad_stock', array($this, '_read_fecha'));
        $this->grocery_crud->callback_read_field('fecha_modificacion_stock', array($this, '_read_fecha'));


        $this->grocery_crud->set_relation('codigo_producto', 'pe_productos', 'nombre');
        $this->grocery_crud->set_relation('id_pe_producto', 'pe_productos', 'codigo_producto');
        $this->grocery_crud->set_relation('proveedor', 'pe_proveedores_acreedores', 'nombre');
        $this->grocery_crud->set_relation('codigo_bascula', 'pe_productos', 'id_producto');

        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['titulo'] = 'Stocks - Fecha caducidad';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_stocks($output, "Stocks - fecha caducidad");
        // $this->_table_output_albaranes($output,"Albaranes");


    }

    public function stocks_totales()
    {
        // se actualiza pe_stocks_totales con los datos de pe_productos (proveedor, control_stock, status_producto)
        $this->db->query(
            "UPDATE pe_stocks_totales s"
                . " LEFT JOIN pe_productos p ON p.id=s.id_pe_producto "
                . " SET s.proveedor=p.id_proveedor_web, s.control_stock=p.control_stock, s.activo=p.status_producto"
        );

        $this->load->model('stocks_model');
        $this->movimiento('stocks_totales');
        $this->grocery_crud->set_table('pe_stocks_totales');
        $this->grocery_crud->set_subject('Stocks - totales (productos activos con control stock)');
        $this->grocery_crud->order_by('codigo_producto');
        $this->grocery_crud->where('activo', '1');
        $this->grocery_crud->where('pe_stocks_totales.control_stock', 'Sí');

        if ($this->session->categoria != 2) {
            $this->grocery_crud->columns('id_pe_producto', 'codigo_bascula', 'codigo_producto', 'cantidad', 'fecha_modificacion_stock', 'proveedor', 'valoracion');
        } else {
            $this->grocery_crud->columns('id_pe_producto', 'codigo_bascula', 'codigo_producto', 'cantidad', 'fecha_modificacion_stock', 'proveedor', 'codigo_producto_excel');
        }

        $this->grocery_crud->unset_read_fields('nombre');

        $this->grocery_crud->display_as('id_pe_producto', 'Codigo 13');
        $this->grocery_crud->display_as('codigo_producto', 'Producto');
        $this->grocery_crud->display_as('codigo_bascula', 'Código Báscula');
        $this->grocery_crud->display_as('proveedor', 'Proveedor última compra');
        $this->grocery_crud->display_as('fecha_modificacion_stock', 'Fecha modificación');
        $this->grocery_crud->display_as('valoracion', 'Valor Compra Stock');
        $this->grocery_crud->display_as('cantidad', 'Stock total actual');

        $this->grocery_crud->callback_column('fecha_modificacion_stock', array($this, '_read_fecha_centrado'));
        $this->grocery_crud->callback_column('valoracion', array($this, '_callback_valoracion_stock'));
        $this->grocery_crud->callback_column('cantidad', array($this, '_callback_column_cantidad'));
        $this->grocery_crud->callback_column('codigo_bascula', array($this, '_callback_column_codigo_bascula'));

        $this->grocery_crud->callback_column('proveedor', array($this, '_callback_column_proveedor'));



        $this->grocery_crud->callback_read_field('cantidad', array($this, '_read_cantidad_stocks_totales'));
        $this->grocery_crud->callback_read_field('id_pe_producto', array($this, '_callback_column_id_pe_producto'));
        $this->grocery_crud->callback_read_field('fecha_modificacion_stock', array($this, '_read_fecha'));
        $this->grocery_crud->callback_read_field('valoracion', array($this, '_callback_read_valoracion_stock'));

        $this->grocery_crud->set_relation('id_pe_producto', 'pe_productos', 'codigo_producto');
        //$this->grocery_crud->set_relation('codigo_bascula_excel','pe_productos','codigo_producto');
        $this->grocery_crud->set_relation('codigo_producto', 'pe_productos', 'nombre');
        $this->grocery_crud->set_relation('codigo_bascula', 'pe_productos', 'id_producto');
        $this->grocery_crud->callback_column('proveedor', array($this, '_read_proveedor'));
        $this->grocery_crud->set_relation('proveedor', 'pe_proveedores_acreedores', 'nombre');
        $this->grocery_crud->set_relation('activo', 'pe_productos', 'status_producto');

        // $this->grocery_crud->unset_add();
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_delete();

        if ($this->uri->segment(3) == 'export') {
            // mensaje('paso export');
            /*
            $query = $this->db->query("select * from pe_productos");
			$string = [];
            foreach ($query->result_array() as $row)
                {
                        $string=array_keys($row);
                }
                */
            //$this->db->query("UPDATE pe_productos SET precio_compra_excel=precio_compra, tarifa_venta_excel=tarifa_venta,peso_real_excel=peso_real, valoracion_excel=valoracion, margen_real_producto_excel=margen_real_producto,url_imagen_portada_excel=url_imagen_portada");
            //$string=array('id','codigo_producto','id_producto','nombre','peso_real_excel','tipo_unidad','id_grupo','id_familia','precio_compra_excel','id_proveedor_web','tarifa_venta_excel','stock_total','valoracion_excel','margen_real_producto_excel','url_imagen_portada_excel');
            //$this->grocery_crud->columns( array('id_pe_producto','codigo_producto'));
        }

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Stocks - totales';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        // mensaje('Gestion tablas stocks totales -----------------------');

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_stocks_totales.php', $output);
        $this->load->view('templates/footer.html');


        //$this->_table_output_stocks_totales($output,"Stocks - totales");


    }


    function edit_field_callback_nombreArchivoFactura($value, $primary_key)
    {
        // echo $value.'<br>';
    }

    function _callback_nombreArchivoFactura($value, $row)
    {

        if (!$value) return false;
        $mostrar = ucwords('F' . substr($value, strpos($value, 'actura')));
        $mostrar = str_replace("-b", " B", $mostrar);
        $id = $row->id;
        $sql = "SELECT nombreArchivoFactura FROM pe_registroFacturas WHERE id='$id'";
        $nombreArchivoFactura = $this->db->query($sql)->row()->nombreArchivoFactura;
        //$baseUrl=base_url();
        return '<a href="' . base_url() . 'facturas/' . $nombreArchivoFactura . '" target="_blank">' . $mostrar . '</a>';
    }

    public function pedidos_()
    {
        $this->grocery_crud->set_table('pe_pedidos_proveedores');
        $this->grocery_crud->set_subject('Pedidos a proveedores');
        //$this->grocery_crud->where('status','1');

        $this->grocery_crud->order_by('fecha', 'desc');
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->display_as('numPedido', 'Núm Pedido');
        $this->grocery_crud->display_as('nombreArchivoPedido', 'Nombre Documento');
        $this->grocery_crud->display_as('id_proveedor', 'Proveedor');
        $this->grocery_crud->display_as('importe', 'Importe Total Pedido');
        $this->grocery_crud->display_as('otrosCostes', 'Otros Costes Pedido');

        //$this->grocery_crud->unset_add();
        $this->grocery_crud->unset_edit();
        //   $this->grocery_crud->unset_read();

        $this->grocery_crud->callback_column('importe', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_column('otrosCostes', array($this, '_column_right_align_precio'));

        $this->grocery_crud->set_field_upload('nombreArchivoPedido', 'pedidos');
        // $this->grocery_crud->callback_column('cambio_euro_to_rupias', array($this, '_column_right_align'));
        $this->grocery_crud->set_relation('id_proveedor', 'pe_proveedores', 'nombre_proveedor');

        $this->grocery_crud->callback_before_upload(array($this, 'example_callback_before_upload'));

        $this->grocery_crud->callback_after_upload(array($this, 'example_callback_after_upload'));

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Pedidos';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output, "Pedidos");
    }

    function facturas_callback_after($post_array, $primary_key)
    {
        //se adecua el campo ordencion par poder ordenar por fecha y id_factura
        $this->db->query("UPDATE pe_registroFacturas SET ordenacion=concat(fecha_factura, id_factura) WHERE id='$primary_key'");
        return true;
    }

    function after_insert_grupo($post_array, $primary_key)
    {
        $sql = "SELECT id,id_grupo FROM pe_grupos ";
        $result = $this->db->query($sql);
        foreach ($result->result() as $k => $v) {
            $id = $v->id;
            $sql = "UPDATE pe_grupos SET id_grupo='$id' WHERE id='$id'";
            $this->db->query($sql);
        }
        return true;
    }

    public function   _column_left_number($value, $row)
    {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span  style='text-align:left;width:20%;display:block;'>$value</span>";
    }
    public function   _column_right_number($value, $row)
    {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        // return "<span  class='text-right' style='text-align:right;width:20%;display:block;'>$value</span>";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }
    public function   _column_nombre_proveedor($value, $row)
    {

        // return "<span  class='text-right' style='text-align:right;width:20%;display:block;'>$value</span>";
        return "<span style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    public function   _column_right_number_importe($value, $row)
    {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span  style='display:block;'>$value</span>";
    }


    public function _column_total_prestashop($value, $row)
    {
        //$descuento=$this->db->query("SELECT descuento FROM pe_orders_prestashop WHERE id='".$row->id."'")->row()->descuento;
        //$value-=$descuento;
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return $value;
        return "<span  class='text-right' style='width:50%;display:block;'>$value</span>";
    }


    public function   _column_right_number50($value, $row)
    {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span  class='text-right' style='width:50%;display:block;'>$value</span>";
    }
    public function   _column_right_transporte($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return $value;
        return "<span  class='text-right' style='width:50%;display:block;'>$value</span>";
    }
    public function   _column_right_base_factura($value, $row)
    {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return $value;
        return "<span  class='text-right' style='width:50%;display:block;'>$value</span>";
    }

    function unique_field_name($field_name)
    {
        return 's' . substr(md5($field_name), 0, 8); //This s is because is better for a string to begin with a letter and not with a number
    }
    public function _callback_column_cantidad($value, $row)
    {

        $tipoUnidad = $this->productos_->getUnidad($row->id_pe_producto);
        $value /= 1000;
        if ($tipoUnidad == 'Kg') $value =  number_format($value, 3);
        if ($value == 0) $value = "";
        else $value = $value . ' ' . $tipoUnidad;
        return "<span class='derecha' style='text-align:right;padding-right:10px;width:100%;display:block;' >$value</span>"; //$value.' '.$tipoUnidad;//"<span class='derecha' >$value</span>";
    }

    public function _callback_valoracion_stock($value, $row)
    {
        //echo $row->id_pe_producto;
        $id_pe_producto = $row->id_pe_producto;
        $datosProducto = $this->productos_->getDatosCompraProducto($id_pe_producto);
        $cantidad = $this->stocks_model->getCantidadTotal($id_pe_producto);
        /*
        $precio=$datosProducto['precio']-$datosProducto['precio']*$datosProducto['descuento']/100;
        if($datosProducto['unidades_precio']) $precio=$precio/$datosProducto['unidades_precio'];
        $value=$precio*$cantidad;
        $this->stocks_model->putValoracion($id_pe_producto,$value);
        $value/=1000;
        $value=  number_format ($value,2);
        */
        //cambiado para considerar el precio_compra del producto que incluye descuentos y transformaciones
        $value = $datosProducto['precio_compra'] / 1000 * $cantidad;
        $value =  number_format($value, 2);
        return "<span class='derecha' style='text-align:right;padding-right:10px;width:100%;display:block;'>" . $value . "</span>";
    }

    public function _callback_read_valoracion_stock($value, $row)
    {

        $value /= 1000;
        $value =  number_format($value, 2);
        return "<span class='' style='text-align:left;padding-right:10px;width:100%;display:block;'>" . $value . "</span>";
    }

    public function _read_cantidad_stocks($value, $primaryKey)
    {

        $row = $this->db->query("SELECT id_pe_producto FROM pe_stocks WHERE id='$primaryKey'")->row();
        $tipoUnidad = $this->productos_->getUnidad($row->id_pe_producto);
        $value /= 1000;
        if ($tipoUnidad == 'Kg') $value =  number_format($value, 3);
        if ($value == 0) $value = "";
        else $value = $value . ' ' . $tipoUnidad;

        //$value/=1000;
        //if(intval($value)!=$value) $value=  number_format ($value,3);
        return "<span class='derecha' style='text-align:left;width:100%;display:block;'>$value</span>";
    }

    public function _read_cantidad_stocks_totales($value, $primaryKey)
    {

        $row = $this->db->query("SELECT id_pe_producto FROM pe_stocks_totales WHERE id='$primaryKey'")->row();
        $tipoUnidad = $this->productos_->getUnidad($row->id_pe_producto);
        $value /= 1000;
        if ($tipoUnidad == 'Kg') $value =  number_format($value, 3);
        if ($value == 0) $value = "";
        else $value = $value . ' ' . $tipoUnidad;

        //$value/=1000;
        //if(intval($value)!=$value) $value=  number_format ($value,3);
        return "<span class='derecha' style='text-align:left;width:100%;display:block;'>$value</span>";
    }


    public function _callback_column_codigo_producto($value, $row)
    {
        $sql = "SELECT nombre FROM pe_productos WHERE id='$value'";
        $value = $this->db->query($sql)->row()->nombre;
        return "<span class='numero'>$value</span>";
    }



    public function _callback_column_codigo_bascula($value, $row)
    {
        $id = $row->id_pe_producto;
        $sql = "SELECT id_producto FROM pe_productos WHERE id='$id'";
        $value = $this->db->query($sql)->row()->id_producto;
        return "<span class='numero derecha' style='text-align:right;padding-right:30px;width:100%;display:block;'>$value</span>";
    }


    public function _callback_column_proveedor($value, $row)
    {
        $id_pe_producto = $row->id_pe_producto;
        $id = $row->id;
        $sql = "SELECT id_proveedor_web FROM pe_productos WHERE id='$id_pe_producto'";
        $id_proveedor_web = $this->db->query($sql)->row()->id_proveedor_web;
        $sql = "UPDATE pe_stocks SET proveedor='$id_proveedor_web' WHERE id='$id'";
        $this->db->query($sql);
        return;
        return "<span class='numero' style='text-align:left;width:100%;display:block;'>$id_pe_producto</span>";
    }

    public function _callback_column_id_pe_producto($value, $row)
    {
        $sql = "SELECT codigo_producto FROM pe_productos WHERE id='$value'";
        $value = $this->db->query($sql)->row()->codigo_producto;
        return "<span class=''>'$value</span>";
    }
    public function _callback_column_codigo_producto_excel($value, $row)
    {
        $sql = "SELECT codigo_producto FROM pe_productos WHERE id='$value'";
        $value = $this->db->query($sql)->row()->codigo_producto;
        return "<span class=''>$value</span>";
    }



    function _read_fecha_centrado($value, $row)
    {
        if ($value == "0000-00-00") return "";
        $originalDate = $value;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return "<span class=''  style='text-align:center;width:100%;display:block;'>$newDate</span>";
    }

    function _read_proveedor($value, $row)
    {
        $proveedor = $this->productos_->getNombreProveedorWeb($value);
        return "<span class=''  style='text-align:left;width:100%;display:block;'>$proveedor</span>";
    }



    public function get_client_ip_env()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }


    function movimiento($accion)
    {
        $nombre = "no session";
        if (isset($_SESSION['nombre']))
            $nombre = $_SESSION['nombre'];
        $fecha = date('Y-m-d H:i:s');
        $ip = $this->get_client_ip_env();

        $fechaDia = substr($fecha, 0, 13);
        $sql = "SELECT * FROM pe_movimientosWeb WHERE usuario='$nombre' AND left(fecha,13)='$fechaDia' AND accion='$accion' AND IP='$ip' ";
        if ($this->db->query($sql)->num_rows() == 0) {
            $sql = "INSERT INTO pe_movimientosWeb SET usuario='$nombre',fecha='$fecha',accion='$accion',IP='$ip' ";
            $this->db->query($sql);
        }
    }




    public function _delete_pedidos($primary_key)
    {
        return $this->db->update('pe_pedidos_proveedores', array('status' => '0'), array('id' => $primary_key));
    }





    public function _delete_proveedores($primary_key)
    {
        return $this->db->update('pe_proveedores', array('status_proveedor' => '0'), array('id_proveedor' => $primary_key));
    }

    // No se utiliza - function para eliminar 
    public function _delete_productos__($primary_key)
    {
        $sql = "DELETE FROM pe_stocks WHERE codigo_producto='$primary_key'";
        $this->db->query($sql);
        $sql = "DELETE FROM pe_stocks_totales WHERE codigo_producto='$primary_key'";
        $this->db->query($sql);
        return $this->db->update('pe_productos', array('status_producto' => '0', "fecha_modificacion" => date('Y-m-d')), array('id' => $primary_key));
    }

    public function _activar_productos($primary_key, $row)
    {
        return site_url('productos/activarProducto/') . '/' . $row->id;
        /*
        return site_url('gestionTablas/productosDescatalogados');
        return $row->id;
        update('pe_productos',array('status_producto' => '1',"fecha_modificacion" => date('Y-m-d')),array('id' => $primary_key));
        return false;
         */
    }
    public function _deshacer_transformacion($primary_key, $row)
    {

        return site_url('stocks/deshacerTransformacion') . '/' . $row->id;
        /*
        return site_url('gestionTablas/productosDescatalogados');
        return $row->id;
        update('pe_productos',array('status_producto' => '1',"fecha_modificacion" => date('Y-m-d')),array('id' => $primary_key));
        return false;
         */
    }

    public function _desactivar_productos($primary_key, $row)
    {

        return site_url('productos/desactivarProducto/') . '/' . $row->id;
        return site_url('gestionTablas/productos');
        return $row->id;
        return false;
    }

    public function _delete_acreedores($primary_key)
    {
        return $this->db->update('pe_acreedores', array('status_acreedor' => '0'), array('id_proveedor' => $primary_key));
    }
    public function _delete_clientes($primary_key)
    {
        return $this->db->update('pe_clientes', array('status_cliente' => '0'), array('id_cliente' => $primary_key));
    }


    public function familias_()
    {
        $this->grocery_crud->set_table('pe_familias');
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->required_fields('id_familia', 'nombre');
        $this->grocery_crud->display_as('id_familia', 'Familia');
        $this->grocery_crud->display_as('nombre_familia', 'Nombre de la Familia');
        // $this->grocery_crud->callback_column('cambio_euro_to_rupias', array($this, '_column_right_align'));


        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Familias';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output, "Familias");
    }

    public function familias()
    {
        $this->grocery_crud->set_table('pe_familias');
        $this->grocery_crud->set_subject('Familias');
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->required_fields('nombre_familia');
        $this->grocery_crud->display_as('id_familia', 'Familia');
        $this->grocery_crud->display_as('nombre_familia', 'Nombre de la Familia');




        $this->grocery_crud->fields('nombre_familia');

        $this->grocery_crud->unique_fields('nombre_familia');


        $this->grocery_crud->callback_after_insert(array($this, 'after_insert_familia'));


        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Familias';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output);
    }

    function after_insert_familia($post_array, $primary_key)
    {
        $sql = "SELECT id,id_familia FROM pe_familias ";
        $result = $this->db->query($sql);
        foreach ($result->result() as $k => $v) {
            $id = $v->id;
            $sql = "UPDATE pe_familias SET id_familia='$id' WHERE id='$id'";
            $this->db->query($sql);
        }
        return true;
    }


    public function ivas()
    {
        $this->grocery_crud->set_table('pe_ivas');
        $this->grocery_crud->set_subject('Tipos IVAs');
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->required_fields('valor_iva');
        $this->grocery_crud->display_as('valor_iva', '% IVA');
        $this->grocery_crud->fields('id', 'valor_iva');
        $this->grocery_crud->add_fields('valor_iva');
        $this->grocery_crud->edit_fields('valor_iva');

        $this->grocery_crud->callback_column('valor_iva', array($this, '_column_right_porcentaje_iva'));
        // $this->grocery_crud->callback_read_field('valor_iva', array($this, '_column_right_align_porcentaje_iva'));
        // $this->grocery_crud->callback_field('valor_iva', array($this, '_field_right_align_valor_iva'));
        // $this->grocery_crud->callback_before_insert(array($this,'_calculos_formateos_db_ivas'));
        // $this->grocery_crud->callback_before_update(array($this,'_calculos_formateos_db_ivas'));




        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['titulo'] = 'Tipos de iva';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;

        $this->_table_output_ivas($output, "IVAs");
    }

    public function grupos_familias()
    {
        $this->grocery_crud->set_table('pe_grupos_familias');
        $this->grocery_crud->set_subject('Grupos - familias');
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->required_fields('id_grupo', 'id_familia');
        $this->grocery_crud->display_as('id_grupo', 'Grupo');
        $this->grocery_crud->display_as('id_familia', 'Familia');
        // $this->grocery_crud->callback_column('cambio_euro_to_rupias', array($this, '_column_right_align'));
        $this->grocery_crud->set_relation('id_grupo', 'pe_grupos', 'nombre_grupo');
        $this->grocery_crud->set_relation('id_familia', 'pe_familias', 'nombre_familia');


        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['titulo'] = 'Grupos';
        $output['col_bootstrap'] = 10;
        $output = (object)$output;
        $this->_table_output($output, "Relacionar Grupos y sus Familias");
    }

    public function conversiones()
    {

        $this->productos_->ponerNombresGenericosTablaConversiones();


        $this->grocery_crud->set_table('pe_conversiones');
        $this->grocery_crud->set_subject('Conversiones');

        $this->grocery_crud->order_by('id_codigo_inicio');
        $this->grocery_crud->order_by('id_codigo_final');
        ///campos obligatorios
        $this->grocery_crud->required_fields('id_codigo_inicio', 'id_codigo_final', 'activa');

        //el codigo inicial debe ser unico, pero le puede corresponder uno o vrioa codigos finales
        $this->grocery_crud->unique_fields(array('id_codigo_inicio'));

        $this->grocery_crud->display_as(array(
            'codigo_producto_inicio' => 'Producto Inicial',
            'codigo_producto_final' => 'Producto Convertido',
            'id_codigo_inicio' => 'Código Báscula Inicio',
            'id_codigo_final' => 'Código Báscula Convertido',
            'peso' => 'Peso (Kg)',
            'activa' => 'Opción Activa(1)/desactiva(0)'
        ));
        // $this->grocery_crud->field_type('activa','true_false');
        $this->grocery_crud->columns('id_codigo_inicio', 'id_codigo_final', 'activa');


        $this->grocery_crud->callback_field('peso', array($this, '_field_right_align_peso_conversion'));

        //$this->grocery_crud->callback_field('id_codigo_inicio', array($this, '_codigos_bascula'));
        // $this->grocery_crud->callback_field('codigo_producto_final', array($this, '_field_left_align_codigo_producto_final'));
        $this->grocery_crud->callback_read_field('peso', array($this, '_column_right_align_peso'));
        $this->grocery_crud->callback_read_field('activa', array($this, '_column_right_align_activa'));
        $this->grocery_crud->callback_column('peso', array($this, '_column_right_align_peso'));
        $this->grocery_crud->callback_column('activa', array($this, '_column_right_align_activa'));
        //campos relacionados    
        // $this->grocery_crud->set_relation('codigo_producto_inicio', 'pe_productos', 'nombre');
        // $this->grocery_crud->set_relation('codigo_producto_final', 'pe_productos', 'nombre');

        //validaciones
        $this->grocery_crud->set_rules('id_codigo_inicio', 'códigos', 'callback_comparar_conversiones[' . $this->input->post('id_codigo_final') . ']');




        $this->grocery_crud->callback_before_insert(array($this, '_calculos_formateos_db_conversion'));
        $this->grocery_crud->callback_before_update(array($this, '_calculos_formateos_db_conversion'));

        $this->grocery_crud->fields(array('id_codigo_inicio', 'id_codigo_final', 'activa'));
        $this->grocery_crud->edit_fields(array('id_codigo_inicio', 'id_codigo_final', 'activa'));


        $output = $this->grocery_crud->render();
        $output = (array)$output;
        $output['titulo'] = 'Conversiones';
        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output($output, "Conversiones");
    }



    function fechaModificacion_after_update_cliente($post_array, $primary_key)
    {
        $fechaModificacion = array(
            "fechaModificacion" => date('Y-m-d')
        );
        $this->db->update('pe_clientes', $fechaModificacion, array('id' => $primary_key));
        return true;
    }

    function fechaModificacion_after_update_proveedor($post_array, $primary_key)
    {
        $fechaModificacion = array(
            "fechaModificacion" => date('Y-m-d')
        );
        $this->db->update('pe_proveedores', $fechaModificacion, array('id_proveedor' => $primary_key));

        $sql = "DELETE FROM pe_proveedores_acreedores WHERE 1";
        $this->db->query($sql);
        $sql = "SELECT id_proveedor,nombre_proveedor as nombre FROM pe_proveedores";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_proveedor = $v->id_proveedor;
            $nombre = $v->nombre;
            $sql = "INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
            $this->db->query($sql);
        }
        $sql = "SELECT id_proveedor,nombre_proveedor as nombre FROM pe_acreedores";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_proveedor = $v->id_proveedor * 1000;
            $nombre = $v->nombre;
            $sql = "INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
            $this->db->query($sql);
        }

        return true;
    }

    function fechaModificacion_after_update_acreedor($post_array, $primary_key)
    {
        $fechaModificacion = array(
            "fechaModificacion" => date('Y-m-d')
        );
        $this->db->update('pe_acreedores', $fechaModificacion, array('id_proveedor' => $primary_key));

        $sql = "DELETE FROM pe_proveedores_acreedores WHERE 1";
        $this->db->query($sql);
        $sql = "SELECT id_proveedor,nombre_proveedor as nombre FROM pe_proveedores";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_proveedor = $v->id_proveedor;
            $nombre = $v->nombre;
            $sql = "INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
            $this->db->query($sql);
        }
        $sql = "SELECT id_proveedor,nombre_proveedor as nombre FROM pe_acreedores";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_proveedor = $v->id_proveedor * 1000;
            $nombre = $v->nombre;
            $sql = "INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
            $this->db->query($sql);
        }
        return true;
    }

    function add_field_callback_pais()
    {

        return "<input type='text' maxlength='50' value='España' name='pais' >";
    }

    function add_field_callback_id_cliente()
    {
        $this->load->model('clientes_');
        $siguienteCliente = $this->clientes_->id_clienteUltimoCliente() + 1;

        return "<input type='text' maxlength='50' value='$siguienteCliente' name='id_cliente' >";
    }

    function add_field_callback_fechaAlta()
    {
        $fechaActual = date('Y-m-d');
        return '<input type="date" maxlength="50" value="' . $fechaActual . '" name="fechaActual" >';
    }

    function add_field_callback_tienda_web()
    {
        $checked1 = 'checked';
        $checked2 = '';


        return "<input type='radio' name='tienda_web' value='1' $checked1/> Tienda
            <input type='radio' name='tienda_web' value='0' $checked2/> Web";
    }

    function edit_field_callback_tienda_web_edit($value, $primary_key)
    {
        $checked1 = '';
        $checked2 = '';
        if ($value) {
            $checked1 = 'checked';
        } else {
            $checked2 = 'checked';
        }

        return "<input type='radio' name='tienda_web' value='1' $checked1/> Tienda
            <input type='radio' name='tienda_web' value='0' $checked2/> Web";
    }

    function _codigos_bascula($value = '', $primary_key = null)
    {
        $this->load->model('conversion_model');
        $codigosBascula = $this->conversion_model->getCodigosBascula();

        return form_dropdown('id_codigo_inicio', $codigosBascula, '', 'id="field-id_codigo_inicio"');
    }


    function field_callback_fecha($value, $primary_key)
    {
        $originalDate = $value;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return $newDate;
    }

    function _read_fecha($value, $row)
    {
        if ($value == "0000-00-00") return "";
        $originalDate = $value;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return $newDate;
    }

    function example_callback_before_upload($files_to_upload, $field_info)
    {
        foreach ($files_to_upload as $value) {
            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
            $nombre = pathinfo($value['name'], PATHINFO_BASENAME);
        }
        $nombreCheck = strtolower($nombre);
        $valor = strpos($nombreCheck, 'factura');
        if (strpos($nombreCheck, 'factura') === false)
            return "El nombre del archivo (" . $valor . ') debe contener la palabra Factura';

        $allowed_formats = array("xls");
        if (in_array($ext, $allowed_formats)) {
            return true;
        } else {
            return 'Tipo de archivo NO permitido. Debe ser Excel xls' . $ext . $nombre;
        }
    }


    function example_callback_after_upload($files_to_upload, $field_info)
    {
        //  $this->load->library('image_moo');

        //Is only one file uploaded so it ok to use it with $uploader_response[0].
        //$file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name; 

        // $this->image_moo->load($file_uploaded)->resize(800,600)->save($file_uploaded,true);

        return true;
    }


    function _table_output($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $dato['activeMenu'] = 'Listados';
        $dato['activeSubmenu'] = $table;
        $this->load->view('templates/top.php', $dato);
        $this->load->view('table_template.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_ivas($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_ivas.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_productos($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_productos.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_productos_descatalogados($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_productos_descatalogados.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }


    function _table_output_stocks($output = null, $table = "")
    {
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_stocks.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_stocks_totales($output = null, $table = "")
    {
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_stocks_totales.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_ventas_directas($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $dato['activeMenu'] = 'Listados';
        $dato['activeSubmenu'] = $table;
        $this->load->view('templates/top.php', $dato);
        $this->load->view('table_template_ventas_directas.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_tiendas_web($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $dato['activeMenu'] = 'Listados';
        $dato['activeSubmenu'] = $table;
        $this->load->view('templates/top.php', $dato);
        $this->load->view('table_template_tiendas_web.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_albaranes($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $dato['activeMenu'] = 'Listados';
        $dato['activeSubmenu'] = $table;
        $this->load->view('templates/top.php', $dato);
        //var_dump($output);
        $this->load->view('table_template_albaranes.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_facturas_proveedores($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_facturas_proveedores.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }


    function _table_output_formas_pagos_acreedores($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_formas_pagos_acreedores.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_conceptos_acreedores($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_conceptos_acreedores.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }
    function _table_output_facturas_acreedores($output = null, $table = "")
    {
        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_facturas_acreedores.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_transformaciones($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $dato['activeMenu'] = 'Listados';
        $dato['activeSubmenu'] = $table;
        $this->load->view('templates/top.php', $dato);
        $this->load->view('table_template_transformaciones.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalConfirmDeshacer');
    }


    function _table_output_pedidos_prestashop($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_pedidos_prestashop.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalPrestashop');
    }

    function _table_output_pedidos_prestashop_transportes($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_pedidos_prestashop_transportes.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalPrestashop');
    }



    function _table_output_pedidos_prestashop_tabla($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_pedidos_prestashop_tabla.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_productos_prestashop($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_productos_prestashop.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_tracking_prestashop($output = null, $table = "")
    {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_tracking_prestashop.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_tickets($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_tickets.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModalTicket');
    }
    function _table_output_tickets_alternativos($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_tickets_alternativos.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModalTicket');
    }


    function _table_output_clientes($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_clientes.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_acreedores($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_acreedores.php', $output);
        $this->load->view('templates/footer.html');
    }


    function _table_output_download($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_download.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_facturas($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_facturas.php', $output);
        $this->load->view('templates/footer.html');
    }

    function _table_output_pedidos($output = null, $table = "")
    {
        //$this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_pedidos.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _column_right_align($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _read_cantidad_stock_minimo($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $unidad = " Unidades o Kg";
        $decimales = 0;

        $value = $value != 0 ? number_format($value, $decimales, ".", ",") . $unidad : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value </span>";
    }

    function _read_stock_minimo($value, $row)
    {
        $value /= 1000;

        $value = $value != 0 ? number_format($value, 0, ".", ",") : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;' id='read_stock_minimo' >$value</span>";
    }
    function _read_unidades_precio($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0, ".", ",") : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value <span id='read_unidades_precio' ></span></span>";
    }
    function _read_unidades_caja($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0, ".", ",") : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value <span id='read_unidades_caja' ></span></span>";
    }
    function _read_cat_unidades_caja($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0, ".", ",") : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value <span id='read_unidades_caja' ></span></span>";
    }

    function _read_cantidad($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " €" : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _read_cantidad_peso($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", ",") . " €/Kg" : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_precio_ultimo_unidad($value, $primary_key)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", ",") . " €/unidad compra" : "";
        return "<span id='read_precio_ultimo_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }
    function _read_codigo_ean($value, $primary_key)
    {

        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>" . ' <img id="img_producto_read" src="" alt="No existe imagen" >';
    }
    function _read_precio_ultimo_peso($value, $primary_key)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", ",") . " €/Kg compra" : "";
        return "<span id='read_precio_ultimo_peso' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_cantidad_unidad($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", ",") . " €/und." : "";

        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_cantidad_porcentaje($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " %" : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_iva($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " %" : "";
        $value = 88888; //$row->id_grupo;
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _read_tarifa_unidad($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " €/unidad" : "";
        return "<span id='read_tarifa_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }
    function _read_tarifa_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " €/Kg" : "";
        return "<span id='read_tarifa_peso' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }
    function _read_cat_tarifa($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " €/unidad" : "";
        return "<span id='read_tarifa_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_porcentaje($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ".", ",") . " %" : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_align_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_id_producto($value, $row)
    {
        if ($this->session->categoria < 2  || $this->session->categoria == 4) {
            return "<a   class='derecha codigo_bascula' style='text-align:right;width:100%;display:block;'>$value</a>";
        } else {
            return "<span   class='derecha codigo_bascula' style='text-align:right;width:100%;display:block;'>$value</span>";
        }
    }

    function _column_left_align_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='izquierda' style='text-align:left;width:100%;display:block;'>$value</span>";
    }


    function _column_right_align_nombre($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=1000;
        //$value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_right_align_NumPedido($value, $row)
    {

        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_right_align_precio($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_url_web($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=100;
        //$value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'><a target='_blank' href='$value'>$value</a></span>";
    }
    function _column_url_web_grid($value, $row)
    {
        $w = "W";
        if ($value == "") $w = "";
        return "<span class='derecha' style='text-align:right;width:20px;display:block;'><a target='_blank' href='$value'>$w</a></span>";
    }

    function _column_right_align_precio_pvp($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='text-align:right;width:20%;display:block;'>$value</span>";
    }

    function _column_right_align_precio_compra($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='text-align:right;width:20%;display:block;'>$value</span>";
    }

    function _column_left_align_nombre($value, $row)
    {
        if (strlen($value) > 70) $value = substr($value, 0, 70);

        return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _column_left_align_nombre_grid($value, $row)
    {

        return "<span  style='text-align:left;display:block;'>$value</span>";
    }

    function _column_right_align_fecha($value, $row)
    {
        $value = substr($value, 8, 2) . '/' . substr($value, 5, 2) . '/' . substr($value, 0, 4);
        return "<span  style='text-align:left;display:block;'>$value</span>";
    }
    function _column_center_align($value, $row)
    {
        $value = substr($value, 8, 2) . '/' . substr($value, 5, 2) . '/' . substr($value, 0, 4);
        if ($value == '//') $value = "";
        if ($value == '00/00/0000') $value = "";
        return "<span  style='text-align:center;display:block;'>$value</span>";
    }

    function _column_right_align_tipo_precio($value, $row)
    {
        if ($value) $valueTexto = "€/und";
        else $valueTexto = "€/Kg";
        return "<span  style='width:30px;text-align:left;display:block;'>$valueTexto</span>";
    }
    function _column_right_align_tipo_precio_view($value, $row)
    {


        if (substr($row, 0, 2) == "EM") $valueTexto = "";
        else {
            if ($value) $valueTexto = "Precio por Unidad";
            else $valueTexto = "Precio por Kg";
        }
        return "<span  style='width:100px;text-align:left;display:block;'>$valueTexto</span>";
    }



    function _column_left_align_nombre2__($value, $row)
    {

        return "<span  style='width:400px;text-align:left;display:block;'>$value</span>";
    }
    function _column_left_align_proveedor_web($value, $row)
    {

        return "<span  style='width:200px;text-align:left;display:block;'>$value</span>";
    }
    function _column_id_pedido($value, $row)
    {
        $id_pedido = $row->id_pedido;
        $sql = "SELECT nombreArchivoPedido FROM pe_pedidos_proveedores WHERE id='$id_pedido'";
        $nombreArchivoPedido = $this->db->query($sql)->row_array()['nombreArchivoPedido'];
        $base_url = base_url();
        return "<a href='$base_url/pedidos/$nombreArchivoPedido' target='_blank'>$nombreArchivoPedido</a>";
    }


    function _column_right_align_activa($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        if ($value == 1) $value = "Activa";
        else $value = "NO activa";

        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_align_id_producto($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=1000;
        //$value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='derecha' style='width:10%;text-align:right;display:block;'>$value&nbsp;&nbsp;&nbsp;</span> ";
    }

    function _column_right_align_codigo_web($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=1000;
        //$value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='derecha' style='width:10%;text-align:right;display:block;'>$value&nbsp;&nbsp;&nbsp;</span> ";
    }
    function _column_right_align_porcentaje($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_precio_ultimo_unidad($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }
    function _column_right_precio_ultimo_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_descuento_1_compra($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_porcentaje($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }
    function _column_right_porcentaje_iva($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:20%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_tarifa_venta($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }


    function _getIva($value, $row)
    {
        $sql = "SELECT precio_ultimo_unidad as unidad, "
            . "precio_ultimo_peso as kg, "
            . "descuento_1_compra as dto, "
            . "peso_real/1000 as peso, "
            . "tarifa_venta_unidad as bruto_unidad, "
            . "tarifa_venta_peso as bruto_peso, "
            . "valor_iva as iva "
            . "FROM pe_productos p "
            . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
            . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
            . " WHERE codigo_producto='$row->codigo_producto'";
        $query = $this->db->query($sql);
        $unidad = $query->row()->unidad;
        $kg = $query->row()->kg;
        $dto = $query->row()->dto;
        $iva = $query->row()->iva;
        $peso = $query->row()->peso;
        if ($unidad) $precio = $unidad;
        else $precio = $kg;

        $precio = $precio - $precio * $dto / 10000;

        $bruto_unidad = $query->row()->bruto_unidad;
        $bruto_peso = $query->row()->bruto_peso;

        if ($unidad) $bruto = $bruto_unidad;
        else $bruto = $bruto_peso;
        $bruto *= 1000;
        $tarifaNeta = $bruto / (100 + $iva) * 1000;


        if ($precio != 0) $value = ($tarifaNeta - $precio) / $precio * 100;
        else $value = 0;


        return $iva;
    }




    function _field_right_align_valor_iva2($value, $row)
    {
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>Hola</span>";
    }

    function _column_right_iva($value, $row)
    {
        $sql = "SELECT i.valor_iva as iva"
            . " FROM pe_productos p "
            . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
            . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
            . " WHERE codigo_producto='$row->codigo_producto'";
        $query = $this->db->query($sql);
        $value = $query->row()->iva;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }


    function _field_add_codigo_producto($value, $prymary_key)
    {
        return '<input placeholder="Código producto (13)" id="field-codigo_producto" type="text" maxlength="20" value="' . $value . '" name="codigo_producto" class="numeric">';
    }
    function _field_edit_codigo_producto($value, $primary_key)
    {
        $base_url = base_url();
        return "<span>$value</span>" . '<input   id="field-codigo_producto" type="hidden"  maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100%;">'
            . '<input id="base_url" type="hidden" value="' . $base_url . '">';
    }
    function _field_nombre($value, $primary_key)
    {
        return '<input id="field-nombre" placeholder="Nombre producto"  type="text"  value="' . $value . '" name="nombre" >';
    }
    function _field_anada($value, $primary_key)
    {
        return '<input id="field-anada" placeholder="Añada"  type="text"  class="numeric" value="' . $value . '" name="anada" >';
    }
    function _field_id_producto($value, $prymary_key)
    {
        return '<input placeholder="Cód. báscula" id="field-id_producto" type="text" maxlength="20" value="' . $value . '" name="id_producto" class="numeric">';
    }
    function _field_unidades_caja($value, $prymary_key)
    {
        $value /= 1000;
        return '<input placeholder="Und./Caja" id="field-unidades_caja" type="text" maxlength="20" value="' . $value . '" name="unidades_caja" class="numeric">&nbsp; Unidades/Caja Compra';
    }

    function _field_cat_unidades_caja($value, $prymary_key)
    {
        $value /= 1000;
        return '<input placeholder="Und./Caja" id="field-cat_unidades_caja" type="text" maxlength="20" value="' . $value . '" name="cat_unidades_caja" class="numeric">&nbsp; Unidades/Caja Compra';
    }

    function _field_unidades_precio($value, $prymary_key)
    {
        $tipoUnidad = $this->productos_->getUnidad($prymary_key);
        $texto = "";
        if ($tipoUnidad == 'Kg') {
            $value = $value != 0 ? number_format($value, 3, ",", ".") . '' : "";
            $texto = "Kg'/Precio Neto Compra'";
        }
        if ($tipoUnidad == "Und") $texto = "Unidades'/Precio Neto Compra'";

        $value /= 1000;
        return '<input placeholder="Und./Precio" id="field-unidades_precio" type="text" maxlength="20" value="' . $value . '" name="unidades_precio" class="numeric">&nbsp; ' . $texto;
    }
    function _field_codigo_ean($value, $prymary_key)
    {
        return '<input placeholder="Código EAN" id="field-codigo_ean" type="text" maxlength="20" value="' . $value . '" name="codigo_ean" class="numeric">'
            . ' <img id="img_producto" src="" alt="No existe imagen" >';
    }
    function _field_peso_real($value, $prymary_key)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";

        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input placeholder="Peso (Kg)" id="field-peso_real" type="text"  value="' . $value . '" name="peso_real" class="numeric">&nbsp; Kg';
    }
    function _field_stock_minimo($value, $prymary_key)
    {
        $tipoUnidad = $this->productos_->getUnidad($prymary_key);
        $value /= 1000;
        if ($tipoUnidad == 'Kg') $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        if ($tipoUnidad == "Und") $tipoUnidad = "Unidades";
        return '<input placeholder="Stock mínimo" id="field-stock_minimo" type="text" maxlength="20" value="' . $value . '" name="stock_minimo" class="numeric">&nbsp; ' . $tipoUnidad;
    }
    function _field_precio_ultimo_peso($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input placeholder="Precio €/Kg" id="field-precio_ultimo_peso" type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_peso" style="width:100px">&nbsp; €/kg.';
    }
    function _field_tarifa_venta_peso($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input placeholder="PVP €/Kg" id="field-tarifa_venta_peso" type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso" style="width:100px">&nbsp; €/kg';
    }
    function _field_precio_ultimo_unidad($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input placeholder="Precio €/Und" id="field-precio_ultimo_unidad" type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_unidad" style="width:100px">&nbsp; €/Unidad';
    }
    function _field_tarifa_venta_unidad($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input placeholder="PVP €/Und" id="field-tarifa_venta_unidad" type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad" style="width:100px">&nbsp; €/Unidad';
    }
    function _field_cat_tarifa($value, $row)
    {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input placeholder="PVP €/Und" id="field-cat_tarifa" type="text" maxlength="20" value="' . $value . '" name="cat_tarifa" style="width:100px">&nbsp; €/Unidad';
    }
    function _field_descuento_1_compra($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input placeholder="Dto compra %" id="field-descuento_1_compra" type="text" maxlength="20" value="' . $value . '" name="descuento_1_compra" style="width:100px">&nbsp; %';
    }
    function _field_beneficio_recomendado($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input placeholder="% Beneficio" id="field-beneficio_recomendado" type="text" maxlength="20" value="' . $value . '" name="beneficio_recomendado" style="width:100px"> %';
    }
    function _field_url_producto($value, $primary_key)
    {
        return '<input id="field-url_producto" placeholder="Url producto Tienda Online"  type="text"  value="' . $value . '" name="url_producto" >&nbsp; <a id="ver_url_producto" href="" target="_blank">Ver</a>';
    }
    function _field_url_imagen_portada($value, $primary_key)
    {

        return '<input id="field-url_imagen_portada" placeholder="Url imagen portada Tienda Online"  type="text"  value="' . $value . '" name="url_imagen_portada" >&nbsp; <a id="ver_url_imagen_portada" href="" target="_blank">Ver</a>';
    }
    function _field_notas($value, $primary_key)
    {
        return '<input id="field-notas" placeholder="Observaciones"  type="text"  value="' . $value . '" name="notas" >';
    }




    function _field_right_align_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input id="field-peso_real" type="text" maxlength="20" value="' . $value . '" name="peso_real" >&nbsp; Kg';
    }
    function _field_right_align_id_producto($value, $primary_key)
    {
        return '<input id="field-id_producto" type="text" maxlength="20" value="' . $value . '" name="id_producto" style="width:100px">';
    }
    function _field_right_align_codigo_ean($value, $primary_key)
    {
        return '<input id="field-codigo_ean" type="text" maxlength="20" value="' . $value . '" name="codigo_ean" style="width:100px">';
    }

    function _field_right_align_anada($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=1000;
        //$value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input id="field-anada" type="text" maxlength="20" value="' . $value . '" name="anada" style="width:100px">';
    }

    function _field_fecha_alta_hoy($value, $row)
    {
        $value = date("Y-m-d");
        return '<input id="field-fecha_alta" name="fecha_alta" type="text" value="' . $value . '" maxlength="10" class="datepicker-input hasDatepicker">';
    }

    function _field_right_align_stock_minimo($value, $row)
    {
        $value /= 1000;
        return '<input type="text" maxlength="20" value="' . $value . '" name="stock_minimo" style="width:100px"> Unidades o Kg';
    }
    function _field_right_align_unidades_caja($value, $row)
    {
        $value /= 1000;
        return '<input type="text" maxlength="20" value="' . $value . '" name="unidades_caja" style="width:100px"> Unidades';
    }
    function _field_right_align_iva($value, $row)
    {
        $value /= 1000;
        return '<input type="text" maxlength="20" value="' . $value . '" name="iva" style="width:100px"> %';
    }


    function _field_right_align_peso_conversion($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="peso" style="width:100px"> Kg';
    }

    function _field_left_align_codigo_producto_inicio($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=1000;
        // $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="codigo_producto_inicio" style="">';
    }
    function _field_left_align_codigo_producto_final($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        //$value/=1000;
        // $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="codigo_producto_final" style="">';
    }

    function _field_right_align_valor_iva($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="valor_iva" style="width:100px"> %';
    }




    function _field_right_align_precio_ultimo_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_peso" style="width:100px"> €/kg';
    }



    function _field_right_align_precio_ultimo_unidad($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_unidad" style="width:100px"> €/unidad';
    }

    function _field_right_align_precio_peso_2($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_peso_2" style="width:100px"> €/kg';
    }

    function _field_right_align_beneficio_recomendado($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="beneficio_recomendado" style="width:100px"> %';
    }

    function _field_right_align_unidades_stock($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="unidades_stock" style="width:100px"> unidades';
    }


    function _field_right_align_precio_peso_3($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_peso_3" style="width:100px"> €/kg';
    }
    function _field_right_align_precio_unidad_2($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_unidad_2" style="width:100px"> €/unidad';
    }
    function _field_right_align_precio_unidad_3($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_unidad_3" style="width:100px"> €/unidad';
    }

    function _field_right_align_tarifa_venta_unidad($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_unidad1($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad1" style="width:100px"> €';
    }
    function _field_right_align_tarifa_venta_unidad2($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad2" style="width:100px"> €';
    }
    function _field_right_align_tarifa_venta_unidad3($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad3" style="width:100px"> €';
    }

    function _field_right_align_descuento_1_compra($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="descuento_1_compra" style="width:100px"> %';
    }

    function _field_right_align_tarifa_venta_peso($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input id="field-tarifa_venta_peso" type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso" style="width:100px"> €';
    }
    function _field_right_align_tarifa_venta_peso1($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso1" style="width:100px"> €';
    }
    function _field_right_align_tarifa_venta_peso2($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso2" style="width:100px"> €';
    }
    function _field_right_align_tarifa_venta_peso3($value, $row)
    {
        //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso3" style="width:100px"> €';
    }
    function _field_right_align_codigo_producto($value, $row)
    {
        return '<input disabled type="text" maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100px;background-color:yellow;">';
    }
    function _field_right_align_nombre($value, $row)
    {
        return '<input disabled type="text" maxlength="20" value="' . $value . '" name="nombre" style="width:500px;background-color:yellow;">';
    }

    function _field_right_align_id_grupo($value, $row)
    {
        return '<input disabled type="text" maxlength="20" value="' . $value . '" name="id_grupo" style="width:500px;background-color:yellow;">';
    }

    function _field_right_align_peso_real($value, $row)
    {
        return '<input id="field-peso_real" disabled type="text" maxlength="20" value="' . $value . '" name="peso_real" style="width:100px;background-color:yellow;"> Kg';
    }

    function _edit_codigo_producto($value, $row)
    {
        $base_url = base_url();
        return "<span>$value</span>" . '<input   id="field-codigo_producto" type="hidden"  maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100%;">'
            . '<input id="base_url" type="hidden" value="' . $base_url . '">';
    }

    function _edit_id_producto($value, $row)
    {
        $base_url = base_url();
        return "<span>$value</span>" . '<input   id="field-id_producto" type="hidden"  maxlength="20" value="' . $value . '" name="id_producto" style="width:100%;">';
    }

    function _add_codigo_producto($value, $row)
    {
        $base_url = base_url();
        return '<input   id="field-codigo_producto" type="text" onblur="codigo13()" maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100%;">'
            . '<input id="base_url" type="hidden" value="' . $base_url . '">';
    }

    function _add_id_producto($value, $row)
    {
        $base_url = base_url();
        return '<input   id="field-id_producto" type="text"  maxlength="20" value="' . $value . '" name="id_producto" style="width:100%;">';
    }


    function _field_right_align_tipo_precio($value, $row)
    {
        if ($value) $valueTexto = 'Precio por Unidad';
        else $valueTexto = 'Precio por Kg';
        return '<input  type="hidden"  value="' . $value . '" name="tipo_precio" > <input disabled type="text" maxlength="20" value="' . $valueTexto . '" name="tipo_precio" style="width:120px;background-color:yellow;"> ';
    }
    function _field_right_align_url_web1($value, $row)
    {
        return '<input  type="text"  value="' . $value . '" name="url_web1" style="width:700px;">';
    }
    function _field_right_align_url_web2($value, $row)
    {
        return '<input  type="text"  value="' . $value . '" name="url_web2" style="width:700px;">';
    }
    function _field_right_align_url_web3($value, $row)
    {
        return '<input  type="text"  value="' . $value . '" name="url_web3" style="width:700px;">';
    }


    function _calculos_formateos_db_peso_real($post_array)
    {
        $peso_real = $post_array['peso_real'];
        $peso_real =  str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = $peso_real * 1000;
        return $post_array;
    }


    function _calculos_formateos_db_2($post_array)
    {
        $peso_real = $post_array['peso_real'];
        $peso_real =  str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = $peso_real * 1000;

        $tarifa_venta_unidad = $post_array['tarifa_venta_unidad'];
        $tarifa_venta_unidad =  str_replace(",", ".", $tarifa_venta_unidad);
        $post_array['tarifa_venta_unidad'] = $tarifa_venta_unidad * 1000;

        $tarifa_venta_peso = $post_array['tarifa_venta_peso'];
        $tarifa_venta_peso =  str_replace(",", ".", $tarifa_venta_peso);
        $post_array['tarifa_venta_peso'] = $tarifa_venta_peso * 1000;

        $unidades_caja = $post_array['unidades_caja'];
        $unidades_caja =  str_replace(",", ".", $unidades_caja);
        $post_array['unidades_caja'] = $unidades_caja * 1000;

        return $post_array;
    }

    function _calculos_formateos_db_3($post_array)
    {
        $peso_real = $post_array['peso_real'];
        $peso_real =  str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = $peso_real * 1000;



        return $post_array;
    }

    function _calculos_formateos_db_tarifa_venta_peso($post_array)
    {
        $tarifa_venta_peso = $post_array['tarifa_venta_peso'];
        $tarifa_venta_peso =  str_replace(",", ".", $tarifa_venta_peso);
        $post_array['tarifa_venta_peso'] = $tarifa_venta_peso * 100;
        return $post_array;
    }


    function _calculos_formateos_db($post_array)
    {

        if ($this->session->categoria == 2) {
            $peso_real = $post_array['peso_real'];
            $peso_real =  str_replace(",", ".", $peso_real);
            $post_array['peso_real'] = $peso_real * 1000;

            $unidades_caja = $post_array['unidades_caja'];
            $unidades_caja =  str_replace(",", ".", $unidades_caja);
            $post_array['unidades_caja'] = $unidades_caja * 1000;

            return $post_array;
        }
        if ($this->session->categoria == 3) {
            $peso_real = $post_array['peso_real'];
            $peso_real =  str_replace(",", ".", $peso_real);
            $post_array['peso_real'] = $peso_real * 1000;

            return $post_array;
        }
        if (isset($post_array['cat_unidades_caja'])) {
            $unidades_caja = $post_array['cat_unidades_caja'];
            $unidades_caja = str_replace(",", ".", $unidades_caja);
            $post_array['cat_unidades_caja'] = $unidades_caja * 1000;
        }

        if ($this->session->categoria < 2) {
            $beneficio_recomendado = $post_array['beneficio_recomendado'];
            $beneficio_recomendado =  str_replace(",", ".", $beneficio_recomendado);
            $post_array['beneficio_recomendado'] = $beneficio_recomendado * 1000;
        }

        $peso_real = $post_array['peso_real'];
        $peso_real =  str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = $peso_real * 1000;

        $precio_ultimo_peso = $post_array['precio_ultimo_peso'];
        $precio_ultimo_peso =  str_replace(",", ".", $precio_ultimo_peso);
        $post_array['precio_ultimo_peso'] = $precio_ultimo_peso * 1000;

        $precio_ultimo_unidad = $post_array['precio_ultimo_unidad'];
        $precio_ultimo_unidad =  str_replace(",", ".", $precio_ultimo_unidad);
        $post_array['precio_ultimo_unidad'] = $precio_ultimo_unidad * 1000;


        $tarifa_venta_unidad = $post_array['tarifa_venta_unidad'];
        $tarifa_venta_unidad =  str_replace(",", ".", $tarifa_venta_unidad);
        $post_array['tarifa_venta_unidad'] = $tarifa_venta_unidad * 1000;

        $tarifa = $post_array['cat_tarifa'];
        $tarifa =  str_replace(",", ".", $tarifa);
        $post_array['cat_tarifa'] = $tarifa * 1000;

        $tarifa_venta_peso = $post_array['tarifa_venta_peso'];
        $tarifa_venta_peso =  str_replace(",", ".", $tarifa_venta_peso);
        $post_array['tarifa_venta_peso'] = $tarifa_venta_peso * 1000;


        $descuento_1_compra = $post_array['descuento_1_compra'];
        $descuento_1_compra =  str_replace(",", ".", $descuento_1_compra);
        $post_array['descuento_1_compra'] = $descuento_1_compra * 1000;

        $stock_minimo = $post_array['stock_minimo'];
        $stock_minimo =  str_replace(",", ".", $stock_minimo);
        $post_array['stock_minimo'] = $stock_minimo * 1000;

        $unidades_caja = $post_array['unidades_caja'];
        $unidades_caja =  str_replace(",", ".", $unidades_caja);
        $post_array['unidades_caja'] = $unidades_caja * 1000;

        $unidades_precio = $post_array['unidades_precio'];
        $unidades_precio =  str_replace(",", ".", $unidades_precio);
        $post_array['unidades_precio'] = $unidades_precio * 1000;

        return $post_array;
    }

    function _producto_before_delete($primary_key)
    {
        $this->db->db_debug = false;

        return false;
    }

    function _calculos_formateos_productos_mercado_db($post_array)
    {

        $fields = array(
            'tarifa_venta_peso1', 'tarifa_venta_peso2', 'tarifa_venta_peso3',
            'tarifa_venta_unidad1',
            'tarifa_venta_unidad2',
            'tarifa_venta_unidad3'
        );

        foreach ($fields as $k => $v) {
            if (isset($post_array[$v])) {
                $post = $post_array[$v];
                $post =  str_replace(",", ".", $post);
                $post_array[$v] = $post * 100;
            }
        }

        if ($post_array['tipo_precio'] == 'Precio por Unidad')  $post_array['tipo_precio'] = 1;
        else $post_array['tipo_precio'] = 0;

        return $post_array;
    }

    function _calculos_formateos_db_ivas($post_array)
    {
        $valor_iva = $post_array['valor_iva'];
        $valor_iva =  str_replace(",", ".", $valor_iva);
        $post_array['valor_iva'] = $valor_iva * 100;

        return $post_array;
    }

    function _calculos_formateos_db_conversion($post_array)
    {
        /*
        $peso=$post_array['peso'];
        $peso=  str_replace(",", ".", $peso);
        $post_array['peso']=floatval($peso)*1000;
        */
        return $post_array;
    }


    function _calculo_margen($value, $row)
    {
        // $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $precio_ultimo = (str_replace(".", "", $row->precio_ultimo));
        $peso_real = 100;
        $descuento_1_compra = $row->descuento_1_compra;
        $margen = $precio_ultimo;
        //  $margen = $margen != 0 ? number_format($margen, 2, ",", ".") : "";
        //return $margen;
        $value = $row->precio_ultimo * $row->peso_real;
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }


    function comparar_conversiones($id_codigo_inicial, $id_codigo_final)
    {
        //código inicial y final deben ser iguales
        if ($id_codigo_inicial == $id_codigo_final) {
            $this->form_validation->set_message('comparar_conversiones', 'El Código inicial y el Código final NO pueden ser iguales');
            return false;
        }
        //debe existir tarifa en productos finales

        $this->load->model('conversion_model');
        $numProductosBascula = $this->conversion_model->numProductosBascula($id_codigo_final);

        if ($numProductosBascula > 1) {
            $this->form_validation->set_message('comparar_conversiones', 'El Código final es ambiguo: solo debe corresponder a un producto de código báscula único');
            return false;
        }

        //tarifa final final debe ser inferior a cualquiera de las tarifas iniciales
        $precio = $this->conversion_model->getUltimasTarifasVenta($id_codigo_final);
        if (!$precio) {
            $this->form_validation->set_message('comparar_conversiones', 'El Código Báscula Convertido ' . $id_codigo_final . ' NO existe o no tiene tarifa venta');
            return false;
        }

        $precio = $this->conversion_model->getUltimasTarifasVenta($id_codigo_inicial);
        if (!$precio) {
            $this->form_validation->set_message('comparar_conversiones', 'El Código Báscula Inicio ' . $id_codigo_inicial . ' NO existe o no tiene tarifa venta');
            return false;
        }


        return true;




        //return true;
    }

    function codigo_producto($codigo_producto)
    {
        if (strlen(($codigo_producto)) != 13) {
            $this->form_validation->set_message('codigo_producto', 'El código de producto debe tener exactamente 13 cifras. Revisar espacios.');
            return false;
        }
    }

    function compararTarifa($unitario, $peso)
    {
        $unitario =  str_replace(",", ".", $unitario);
        $peso =  str_replace(",", ".", $peso);

        if ($unitario > 0 && $peso > 0) {
            $this->form_validation->set_message('compararTarifa', 'El producto DEBE tener una tarifa PVP unidad o una tarifa PVP Kg, pero NO ambos a la vez');
            return false;
        }
        if (($unitario == 0 && $peso == 0)) {
            $this->form_validation->set_message('compararTarifa', 'Obligatorio indicar tarifa PVP unidad o tarifa PVP Kg');
            return false;
        }
        return true;
    }


    function compararTarifa_unidad_vs_Tipo_precio($unitario, $tipoPrecio)
    {
        if ($unitario != 0 && $tipoPrecio == 0) {
            $this->form_validation->set_message('compararTarifa_unidad_vs_Tipo_precio', 'El producto NO PUEDE tener una tarifa PVP por Unidad, debe ser SOLO por Peso');
            return false;
        }
    }
    function compararTarifa_peso_vs_Tipo_precio($peso, $tipoPrecio)
    {
        if ($peso != 0 && $tipoPrecio == 1) {
            $this->form_validation->set_message('compararTarifa_peso_vs_Tipo_precio', 'El producto NO PUEDE tener una tarifa PVP por Peso, debe ser SOLO por Unidad');
            return false;
        }
    }




    function comparar($unitario, $peso)
    {
        $unitario =  str_replace(",", ".", $unitario);
        $peso =  str_replace(",", ".", $peso);
        if ($unitario > 0 && $peso > 0) {
            $this->form_validation->set_message('comparar', 'El producto DEBE tener un precio unidad o un precio Kg, pero NO ambos a la vez');
            return false;
        }
        if (($unitario == 0 && $peso == 0)) {
            $this->form_validation->set_message('comparar', 'Obligatorio indicar precio unitario o precio Kg');
            return false;
        }
        return true;
    }

    function comparar_precios($pvp, $precio)
    {
        $pvp =  str_replace(",", ".", $pvp);
        $precio =  str_replace(",", ".", $precio);

        if ($pvp > 0 && !$precio) {
            $this->form_validation->set_message('comparar_precios', 'Si se indica PVP unidad o peso, se DEBE indicar el Precio Compra correspondiente');
            return false;
        }
        if ($precio > 0 && !$pvp) {
            $this->form_validation->set_message('comparar_precios', 'Si se indica Precio Compra unidad o peso, se DEBE indicar el PVP correspondiente');
            return false;
        }

        return true;
    }


    function field_callback_url($value, $row)
    {
        return "<span class='' style='text-align:left;display:block;'><a target='_blank' href='$value'>$value</a></span>";
    }

    function _read_url_producto($value, $row)
    {
        return "<span id='read_url_producto' style='text-align:left;display:block;'>$value</span>";
    }
    function _read_url_imagen_portada($value, $row)
    {
        if ($value != "" && $this->url_exists($value))
            return "<span id='read_url_imagen_portada' style='text-align:left;display:block;'>$value</span>";
        else {
            $imagen = base_url() . "images/pernil1812.png";
            return "<span id='read_url_imagen_portada' style='text-align:left;display:block;'>$imagen</span>";
        }
    }

    function url_exists($url)
    {
        if (!$fp = curl_init($url)) return false;
        return true;
    }

    function _imagen_producto($value, $row)
    {
        if ($value != "" && $this->url_exists($value))
            return "<span ><img  class='imagenProducto' id='column' src='$value' alt='No existe imagen'></span>";
        else {
            $imagen = base_url() . "images/pernil1812.png";
            return "<span ><img  class='imagenProducto' id='column' src='$imagen' alt='No existe imagen'></span>";
        }
    }

    // function url_exists_($url)
    // {
    //     $ch = curl_init($url);
    //     //cURL set options
    //     $options = array(
    //         CURLOPT_URL => $url,              #set URL address
    //         CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',  #set UserAgent to get right content like a browser
    //         CURLOPT_RETURNTRANSFER => true,         #redirection result from output to string as curl_exec() result
    //         CURLOPT_COOKIEFILE => 'cookies.txt',    #set cookie to skip site ads
    //         CURLOPT_COOKIEJAR => 'cookiesjar.txt',  #set cookie to skip site ads
    //         CURLOPT_FOLLOWLOCATION => true,         #follow by header location
    //         CURLOPT_HEADER => true,                 #get header (not head) of site
    //         CURLOPT_FORBID_REUSE => true,           #close connection, connection is not pooled to reuse
    //         CURLOPT_FRESH_CONNECT => true,          #force the use of a new connection instead of a cached one
    //         CURLOPT_SSL_VERIFYPEER => false         #can get protected content SSL
    //     );
    //     //set array options to object
    //     curl_setopt_array($ch, $options);
    //     curl_exec($ch);
    //     $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);
    //     if (empty($retcode) || $retcode > 400) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }


    public function tickets($inicio = "", $final = "")
    {

        // $this->load->database('pernil181',true);

        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;
        $this->movimiento('tickets');
        $this->grocery_crud->set_table('pe_tickets');

        $where = "";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        }
        if ($where) $this->grocery_crud->where($where);


        $this->grocery_crud->set_subject('Tickets');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('num_ticket', 'Núm. ticket');
        $this->grocery_crud->display_as('fecha', 'Fecha y hora ticket');
        $this->grocery_crud->display_as('id_forma_pago_ticket', 'Forma pago');
        $this->grocery_crud->display_as('id_cliente', 'Núm. cliente');
        $this->grocery_crud->display_as('total', 'Importe Total Ticket');

        $this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));

        $this->grocery_crud->callback_column('total', array($this, '_column_right_number_importe'));

        $this->grocery_crud->columns('fecha', 'num_ticket', 'id_cliente', 'id_forma_pago_ticket', 'total');

        $this->grocery_crud->set_relation('id_forma_pago_ticket', 'pe_formas_pagos_tickets', 'forma_pago');
        $this->grocery_crud->add_action('Editar cliente', '', 'tickets/editar_cliente', '');
        $this->grocery_crud->add_action('Editar forma_pago', '', 'tickets/editar_forma_pago', '');

        $this->grocery_crud->unset_edit();
        // $this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['tituloRango'] = 'Tickets tienda TODOS';
        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Tickets tienda entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);

        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_tickets($output, "Tickets");
    }

    public function tickets_num($num_ticket)
    {


        $this->movimiento('tickets_num');
        $this->grocery_crud->set_table('pe_tickets');

        $where = " num_ticket='$num_ticket'";
        if(strtolower($this->session->username)!='pernilall'){
            $fecha_min=date('Y')-1;
            $where .=" AND fecha>='$fecha_min'";
        }

        if ($where) $this->grocery_crud->where($where);


        $this->grocery_crud->set_subject('Tickets');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('num_ticket', 'Núm. ticket');
        $this->grocery_crud->display_as('fecha', 'Fecha y hora ticket');
        $this->grocery_crud->display_as('id_forma_pago_ticket', 'Forma pago');
        $this->grocery_crud->display_as('id_cliente', 'Núm. cliente');
        $this->grocery_crud->display_as('total', 'Importe Total Ticket');

        $this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));

        $this->grocery_crud->callback_column('total', array($this, '_column_right_number_importe'));

        $this->grocery_crud->columns('fecha', 'num_ticket', 'id_cliente', 'id_forma_pago_ticket', 'total');

        $this->grocery_crud->set_relation('id_forma_pago_ticket', 'pe_formas_pagos_tickets', 'forma_pago');
        $this->grocery_crud->add_action('Editar cliente', '', 'tickets/editar_cliente', '');
        $this->grocery_crud->add_action('Editar forma_pago', '', 'tickets/editar_forma_pago', '');

        $this->grocery_crud->unset_edit();
        // $this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['tituloRango'] = 'Tickets seleccionados por número';
        // if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Tickets tienda entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);

        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_tickets($output, "Tickets");
    }

    public function tickets_($inicio = "", $final = "")
    {
        $this->db = $this->load->database('pernil181bcn', true);


        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;
        $this->movimiento('tickets');
        $this->grocery_crud->set_table('pe_tickets');

        $where = "";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= " fecha >= '$inicio' AND fecha <='$finalDia'";
        }
        if ($where) $this->grocery_crud->where($where);


        $this->grocery_crud->set_subject('Tickets');
        $this->grocery_crud->order_by('fecha', 'desc');

        $this->grocery_crud->display_as('Num_ticket', 'núm. ticket');
        $this->grocery_crud->display_as('fecha', 'Fecha y hora ticket');
        $this->grocery_crud->display_as('id_forma_pago_ticket', 'Forma pago');
        $this->grocery_crud->display_as('total', 'Importe Total Ticket Alternativa');

        $this->grocery_crud->callback_read_field('total', array($this, '_column_left_number'));

        $this->grocery_crud->callback_column('total', array($this, '_column_right_number_importe'));

        $this->grocery_crud->columns('fecha', 'num_ticket', 'id_forma_pago_ticket', 'total');

        $this->grocery_crud->set_relation('id_forma_pago_ticket', 'pe_formas_pagos_tickets', 'forma_pago');

        $this->grocery_crud->unset_edit();
        // $this->grocery_crud->unset_add();
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();


        $output = (array)$output;
        $output['tituloRango'] = 'Tickets tienda TODOS';
        if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Tickets tienda alternativa entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);

        $output['col_bootstrap'] = 12;
        $output = (object)$output;
        $this->_table_output_tickets_alternativos($output, "Tickets alternativa");
    }
}
