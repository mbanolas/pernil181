<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No está permitido el acceso directo a esta URL</h2>");

    

class GestionTablasProductos extends CI_Controller {

    

    function __construct() {
        parent::__construct();
        /* Standard Libraries of codeigniter are required */
        $this->load->database();
        $this->load->helper('url');

        $this->load->model('productos_');
        $this->load->model('stocks_model');
        $this->mensajeAuxiliar="hola";

        //$this->productos_->crearTablaCodigosProductos();

        /* ------------------ */

        $this->load->library('grocery_CRUD');


        $this->grocery_crud->set_theme('bootstrap');
        $this->grocery_crud->unset_bootstrap();
        $this->grocery_crud->unset_jquery();
        //$this->grocery_crud->set_language("spanish"); 



        $this->load->helper('cookie');
        $this->load->helper('maba');
    }

     public function packs() {
        $this->grocery_crud->set_table('pe_packs');
        $this->grocery_crud->set_subject('Packs Productos');
        $this->grocery_crud->where('activo', '1');
        
        $this->grocery_crud->display_as('id_pe_producto_pack', 'Código Pack');
        $this->grocery_crud->display_as('precio_pack', 'Coste Pack');
        $this->grocery_crud->display_as('pvp_tienda', 'PVP tienda');
        $this->grocery_crud->display_as('pvp_pack', 'PVP Pack');

        $this->grocery_crud->set_relation('id_pe_producto_pack', 'pe_productos', 'codigo_producto');
        //$this->grocery_crud->set_relation('codigo_bascula', 'pe_productos', 'id_producto');
         $this->grocery_crud->set_relation('nombre', 'pe_productos', 'nombre');
       // $this->grocery_crud->set_relation('precio_pack', 'pe_productos', 'precio_compra');
       // $this->grocery_crud->set_relation('pvp_pack', 'pe_productos', 'tarifa_venta_unidad');

        $this->grocery_crud->columns('id_pe_producto_pack','nombre','precio_pack','pvp_tienda','margen_tienda','pvp_pack','margen_pack');

                
        $this->grocery_crud->callback_column('precio_pack', array($this, '_column_precio_pack'));
        $this->grocery_crud->callback_column('pvp_pack', array($this, '_column_precio_pack'));
        $this->grocery_crud->callback_column('pvp_tienda', array($this, '_column_precio_pack'));
        $this->grocery_crud->callback_column('margen_pack', array($this, '_column_margen_tienda'));
        $this->grocery_crud->callback_column('margen_tienda', array($this, '_column_margen_tienda'));
        //$this->grocery_crud->callback_column('margen_online', array($this, '_column_margen_online'));
        $this->grocery_crud->callback_column('id_pe_producto_pack', array($this, '_column_nombre'));

        $this->grocery_crud->unset_read_fields('activo');
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();
        $output = (array) $output;
        $output['titulo'] = 'Nuevo Packs Productos';
        $output['col_bootstrap'] = 12;

        $output = (object) $output;
        $this->_table_output_packs($output, "Packs Productos");
        return;
    }
    
    public function embalajes() {
        $this->grocery_crud->set_table('pe_embalajes');
        $this->grocery_crud->set_subject('Envases y embalajes Productos');
        $this->grocery_crud->where('activo', '1');

        $this->grocery_crud->set_relation('codigo_producto', 'pe_productos', 'codigo_producto');
        $this->grocery_crud->set_relation('codigo_bascula', 'pe_productos', 'id_producto');
        $this->grocery_crud->set_relation('nombre', 'pe_productos', 'nombre');

        $this->grocery_crud->callback_column('precio_embalaje_tienda', array($this, '_column_precio_embalaje_tienda'));
        $this->grocery_crud->callback_column('precio_embalaje_online', array($this, '_column_precio_embalaje_online'));
        $this->grocery_crud->callback_column('margen_tienda', array($this, '_column_margen_tienda'));
        $this->grocery_crud->callback_column('margen_online', array($this, '_column_margen_online'));
        $this->grocery_crud->callback_column('codigo_producto', array($this, '_column_nombre'));

        $this->grocery_crud->unset_read_fields('activo');
        $this->grocery_crud->columns('codigo_producto','codigo_bascula','precio_embalaje_tienda','precio_embalaje_online','margen_tienda','margen_online','nombre');
        $this->grocery_crud->unset_delete();

        $output = $this->grocery_crud->render();
        $output = (array) $output;
        $output['titulo'] = 'Nuevo Envases y Embalajes Productos';
        $output['col_bootstrap'] = 12;
        $output = (object) $output;
        $this->_table_output_embalajes_productos($output, "Envases y embalajes Productos");
        return;
    }

    

    public function productos() {
        
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->set_table('pe_productos');
        $this->grocery_crud->set_subject('Productos');
        $this->grocery_crud->where('status_producto', '1');

        $this->grocery_crud->add_action('Descatalogar', '', '', 'fa fa-sign-out', array($this, '_desactivar_productos'));
        $this->grocery_crud->add_action('Eliminar', '', '', 'fa fa-trash', array($this, '_eliminar_producto'));


        $this->grocery_crud->unset_delete();
                if (true) {
            $this->grocery_crud->display_as('id_producto', 'Cód. Básc.');
            $this->grocery_crud->display_as('codigo_producto', 'Código Producto (13)');

            $this->grocery_crud->display_as('descuento_1_compra', 'Dto. (%)');
            $this->grocery_crud->display_as('codigo_ean', 'Código EAN');
            $this->grocery_crud->display_as('id_familia', 'Familia');
            $this->grocery_crud->display_as('peso_real', 'Peso Real (Kg)');
            $this->grocery_crud->display_as('peso_real_excel', 'Peso Real (Kg)');

            $this->grocery_crud->display_as('id_grupo', 'Grupo');
            $this->grocery_crud->display_as('anada', 'Añada');
            $this->grocery_crud->display_as('iva', 'IVA (%)');
            $this->grocery_crud->display_as('nombre', 'Nombre');
            $this->grocery_crud->display_as('nombre_generico', 'Nombre genérico');

            $this->grocery_crud->display_as('nombre_web', 'Nombre Producto Básculas');
            $this->grocery_crud->display_as('precio_ultimo_unidad', 'Precio Compra (€/unidad compra)');
            $this->grocery_crud->display_as('precio_ultimo_peso', 'Precio Compra (€/Kg compra)');
            $this->grocery_crud->display_as('precio_compra', 'Precio Compra Final en Tienda');
            $this->grocery_crud->display_as('precio_compra_excel', 'Precio Compra Final en Tienda');
            $this->grocery_crud->display_as('tarifa_venta_excel', 'Tarifa venta');

            $this->grocery_crud->display_as('tarifa_venta_unidad', 'Precio venta PVP (€/unidad)');
            $this->grocery_crud->display_as('tarifa_venta_peso', 'Precio venta PVP (€/Kg)');
            $this->grocery_crud->display_as('beneficio_recomendado', 'Beneficio recomendado (%)');
            $this->grocery_crud->display_as('margen_real_producto', 'Margen (%)');
            $this->grocery_crud->display_as('margen_real_producto_excel', 'Margen (%)');

            $this->grocery_crud->display_as('id_proveedor_web', 'Proveedor');
            $this->grocery_crud->display_as('id_proveedor_2', 'Proveedor 2');
            $this->grocery_crud->display_as('id_proveedor_3', 'Proveedor 3');
            $this->grocery_crud->display_as('precio_unidad_2', 'Precio Unidad Proveedor 2 (€/und)');
            $this->grocery_crud->display_as('precio_peso_2', 'Precio Peso Proveedor 2 (€/Kg)');
            $this->grocery_crud->display_as('precio_unidad_3', 'Precio Unidad Proveedor 3 (€/und)');
            $this->grocery_crud->display_as('precio_peso_3', 'Precio Peso Proveedor 3 (€/Kg)');
            $this->grocery_crud->display_as('fecha_proveedor_2', 'Fecha Proveedor 2');
            $this->grocery_crud->display_as('fecha_proveedor_3', 'Fecha Proveedor 3');
            $this->grocery_crud->display_as('unidades_precio', 'Unidades Precio');
            $this->grocery_crud->display_as('url_imagen_portada', 'Imagen Producto');
            $this->grocery_crud->display_as('stock_total', 'Total unidades stock');
            $this->grocery_crud->display_as('valoracion', 'Valor stock precio compra actual');
            $this->grocery_crud->display_as('valoracion', 'Valor stock precio compra actual');
            $this->grocery_crud->display_as('valoracion_excel', 'Valor stock precio compra actual');

            $this->grocery_crud->display_as('tipo_unidad', 'Tipo Unidad');
            $this->grocery_crud->display_as('tipo_unidad_mostrar', 'Tipo Unidad');
            $this->grocery_crud->display_as('precio_transformacion', 'Precio Transformación');
            $this->grocery_crud->display_as('precio_transformacion_unidad', 'Precio Transformación (€/unidad)');
            $this->grocery_crud->display_as('precio_transformacion_peso', 'Precio Transformación (€/peso)');

            $this->grocery_crud->display_as('tarifa_venta', 'Tarifa PVP');
            $this->grocery_crud->display_as('descuento_profesionales', 'Dto. Prof. (%)');
            $this->grocery_crud->display_as('descuento_profesionales_vip', 'Dto. Max. Prof. (%)');
            $this->grocery_crud->display_as('tarifa_profesionales', 'Tarifa Venta Prof. (€/tipo unidad)');
            $this->grocery_crud->display_as('tarifa_profesionales_vip', 'Tarifa Venta Mínima Prof. (€/tipo unidad)');
            $this->grocery_crud->display_as('margen_venta_profesionales', 'Margen Venta Prof. (%)');
            $this->grocery_crud->display_as('margen_venta_profesionales_vip', 'Margen Mínimo Venta Prof. (%)');
        }
        
        //columnas sin ordenación
        //basado en https://github.com/scoumbourdis/grocery-crud/pull/210/commits/39ce8f0fc3e4cb1639ec34678ccb0a010e81d638
        $this->grocery_crud->field_without_sorter(array('tarifa_venta','precio_compra','margen_real_producto','stock_total','valoracion','url_imagen_portada'));
        //fin columnas sin ordenación

        //definiciones relaciones, únicos, obligatorios
        if (true) {
            //campos relacionados
            $this->grocery_crud->set_relation('id_grupo', 'pe_grupos', 'nombre_grupo');
            $this->grocery_crud->set_relation('id_familia', 'pe_familias', 'nombre_familia');
            $this->grocery_crud->set_relation('id_proveedor_web', 'pe_proveedores_acreedores', 'nombre');
            $this->grocery_crud->set_relation('modificado_por', 'pe_users', 'nombre');

            // campos únicos
            $this->grocery_crud->unique_fields('codigo_producto', 'nombre');

            ///campos obligatorios
            $this->grocery_crud->required_fields('control_stock','id_proveedor_web','beneficio_recomendado', 'unidades_precio', 'codigo_producto', 'id_producto', 'nombre', 'id_grupo', 'id_familia');

            //validaciones
            $this->grocery_crud->set_rules('precio_ultimo_unidad', 'precio unidad', 'callback_comparar[' . $this->input->post('precio_ultimo_peso') . ']');
            $this->grocery_crud->set_rules('tarifa_venta_unidad', 'tarifa PVP unidad', 'callback_compararTarifa[' . $this->input->post('tarifa_venta_peso') . ']');
            $this->grocery_crud->set_rules('tarifa_venta_unidad', 'tarifa PVP unidad', 'callback_comparar_precios[' . $this->input->post('precio_ultimo_unidad') . ']');
            $this->grocery_crud->set_rules('tarifa_venta_peso', 'tarifa PVP unidad', 'callback_comparar_precios[' . $this->input->post('precio_ultimo_peso') . ']');
            $this->grocery_crud->set_rules('codigo_producto', 'Código de producto', 'callback_codigo_producto');
            $this->grocery_crud->set_rules('id_familia', 'Familia', 'greater_than[0]');
            $this->grocery_crud->set_rules('nombre', 'Nombre producto', 'callback_nombre');
            $this->grocery_crud->set_rules('id_producto', 'Código Boka', 'callback_id_producto[' . $this->input->post('tipo_unidad') . ']');
        }

        
        //$this->grocery_crud->field_without_sorter('codigo_producto');

        //precalculo tabla view  
        if (true) {
            $this->grocery_crud->callback_read_field('peso_real', array($this, '_column_left_align_peso'));
            $this->grocery_crud->callback_read_field('codigo_ean', array($this, '_read_codigo_ean'));
            $this->grocery_crud->callback_read_field('precio_ultimo_peso', array($this, '_read_precio_ultimo_peso'));
            $this->grocery_crud->callback_read_field('precio_ultimo_unidad', array($this, '_read_precio_ultimo_unidad'));
            $this->grocery_crud->callback_read_field('precio_compra', array($this, '_read_precio_compra'));
            $this->grocery_crud->callback_read_field('tarifa_venta_unidad', array($this, '_read_tarifa_unidad'));
            $this->grocery_crud->callback_read_field('tarifa_venta_peso', array($this, '_read_tarifa_peso'));
            $this->grocery_crud->callback_read_field('tarifa_venta', array($this, '_read_tarifa_venta'));
            $this->grocery_crud->callback_read_field('tipo_unidad_mostrar', array($this, '_read_tipo_unidad_mostrar'));
            $this->grocery_crud->callback_read_field('descuento_1_compra', array($this, '_read_cantidad_porcentaje'));
            $this->grocery_crud->callback_read_field('beneficio_recomendado', array($this, '_read_cantidad_porcentaje'));
            $this->grocery_crud->callback_read_field('iva', array($this, '_read_cantidad_porcentaje'));
            $this->grocery_crud->callback_read_field('stock_minimo', array($this, '_read_stock_minimo'));
            $this->grocery_crud->callback_read_field('unidades_caja', array($this, '_read_unidades_caja'));
            $this->grocery_crud->callback_read_field('unidades_precio', array($this, '_read_unidades_precio'));
            $this->grocery_crud->callback_read_field('margen_real_producto', array($this, '_read_margen_real_producto' ));
            $this->grocery_crud->callback_read_field('fecha_alta', array($this, '_read_fecha'));
            $this->grocery_crud->callback_read_field('fecha_modificacion', array($this, '_read_fecha'));
            $this->grocery_crud->callback_read_field('url_producto', array($this, '_read_url_producto'));
            $this->grocery_crud->callback_read_field('url_imagen_portada', array($this, '_read_url_imagen_portada'));
            $this->grocery_crud->callback_read_field('id_producto', array($this, '_read_id_producto'));
            $this->grocery_crud->callback_read_field('nombre', array($this, '_read_nombre'));
            $this->grocery_crud->callback_read_field('precio_transformacion', array($this, '_read_precio_transformacion'));
            $this->grocery_crud->callback_read_field('precio_transformacion_unidad', array($this, '_read_precio_transformacion_unidad'));
            $this->grocery_crud->callback_read_field('precio_transformacion_peso', array($this, '_read_precio_transformacion_peso'));
        }

        //precalculo campos edit y/o add
        if (true) {
            $this->grocery_crud->callback_edit_field('codigo_producto', array($this, '_field_edit_codigo_producto'));
            $this->grocery_crud->callback_add_field('codigo_producto', array($this, '_field_add_codigo_producto'));
            $this->grocery_crud->callback_field('id_producto', array($this, '_field_id_producto'));
            $this->grocery_crud->callback_field('nombre', array($this, '_field_nombre'));
            $this->grocery_crud->callback_field('nombre_generico', array($this, '_field_nombre_generico'));
            $this->grocery_crud->callback_field('anada', array($this, '_field_anada'));
            $this->grocery_crud->callback_field('peso_real', array($this, '_field_peso_real'));
            $this->grocery_crud->callback_field('unidades_caja', array($this, '_field_unidades_caja'));
            $this->grocery_crud->callback_field('codigo_ean', array($this, '_field_codigo_ean'));
            $this->grocery_crud->callback_field('stock_minimo', array($this, '_field_stock_minimo'));
            $this->grocery_crud->callback_field('tipo_unidad_mostrar', array($this, '_field_tipo_unidad_mostrar'));
            $this->grocery_crud->callback_field('precio_ultimo_peso', array($this, '_field_precio_ultimo_peso'));
            $this->grocery_crud->callback_field('precio_compra', array($this, '_field_precio_compra'));
            $this->grocery_crud->callback_field('precio_ultimo_unidad', array($this, '_field_precio_ultimo_unidad'));
            $this->grocery_crud->callback_field('precio_transformacion_unidad', array($this, '_field_precio_transformacion_unidad'));
            $this->grocery_crud->callback_field('precio_transformacion_peso', array($this, '_field_precio_transformacion_peso'));
            $this->grocery_crud->callback_field('unidades_precio', array($this, '_field_unidades_precio'));
            $this->grocery_crud->callback_field('tarifa_venta_peso', array($this, '_field_tarifa_venta_peso'));
            $this->grocery_crud->callback_field('tarifa_venta_unidad', array($this, '_field_tarifa_venta_unidad'));
            $this->grocery_crud->callback_field('descuento_1_compra', array($this, '_field_descuento_1_compra'));
            $this->grocery_crud->callback_field('beneficio_recomendado', array($this, '_field_beneficio_recomendado'));
            $this->grocery_crud->callback_field('margen_real_producto', array($this, '_field_margen_real_producto'));
            $this->grocery_crud->callback_field('descuento_profesionales', array($this, '_field_descuento_profesionales'));
            $this->grocery_crud->callback_field('descuento_profesionales_vip', array($this, '_field_descuento_profesionales_vip'));
            $this->grocery_crud->callback_field('tarifa_profesionales', array($this, '_field_tarifa_profesionales'));
            $this->grocery_crud->callback_field('tarifa_profesionales_vip', array($this, '_field_tarifa_profesionales_vip'));
            $this->grocery_crud->callback_field('url_producto', array($this, '_field_url_producto'));
            $this->grocery_crud->callback_field('url_imagen_portada', array($this, '_field_url_imagen_portada'));
            $this->grocery_crud->callback_field('notas', array($this, '_field_notas'));
            
            $this->grocery_crud->field_type('iva', 'hidden');
            // $this->grocery_crud->field_type('tarifa_profesionales', 'hidden');
            $this->grocery_crud->field_type('tipo_unidad', 'hidden');
        }

        //precálculo columnas grid
        if (true) {
            $this->grocery_crud->callback_column('id_producto', array($this, '_column_id_producto'));
            $this->grocery_crud->callback_column('nombre', array($this, '_column_nombre'));
            $this->grocery_crud->callback_column('peso_real', array($this, '_column_peso'));
            $this->grocery_crud->callback_column('peso_real_excel', array($this, '_column_peso_excel'));

            $this->grocery_crud->callback_column('precio_ultimo_unidad', array($this, '_column_precio_ultimo_unidad'));
            $this->grocery_crud->callback_column('precio_ultimo_peso', array($this, '_column_precio_ultimo_peso'));
            $this->grocery_crud->callback_column('precio_compra', array($this, '_column_precio_compra'));
            $this->grocery_crud->callback_column('precio_compra_excel', array($this, '_column_precio_compra_excel'));
            $this->grocery_crud->callback_column('tarifa_venta_excel', array($this, '_column_tarifa_venta_excel'));

            $this->grocery_crud->callback_column('precio_transformacion', array($this, '_column_precio_transformacion'));

            $this->grocery_crud->callback_column('descuento_1_compra', array($this, '_column_descuento_1_compra'));
            $this->grocery_crud->callback_column('tarifa_venta_unidad', array($this, '_column_right_tarifa_venta'));
            $this->grocery_crud->callback_column('tarifa_venta_peso', array($this, '_column_right_tarifa_venta'));
            $this->grocery_crud->callback_column('margen_real_producto', array($this, '_column_right_margen_real_producto'));
            $this->grocery_crud->callback_column('margen_real_producto_excel', array($this, '_column_right_margen_real_producto_excel'));

            $this->grocery_crud->callback_column('beneficio_recomendado', array($this, '_column_beneficio_recomendado'));
            $this->grocery_crud->callback_column('iva', array($this, '_column_right_iva'));
            $this->grocery_crud->callback_column('unidades_caja', array($this, '_column_peso'));
            $this->grocery_crud->callback_column('id_producto', array($this, '_column_id_producto'));
            $this->grocery_crud->callback_column('url_imagen_portada', array($this, '_imagen_producto'));
            // $this->grocery_crud->callback_column('tipo_unidad', array($this, '_column_tipos_unidades'));
            $this->grocery_crud->callback_column('tarifa_venta', array($this, '_column_tarifa_venta'));
            $this->grocery_crud->callback_column('stock_total', array($this, '_column_stock_total'));
           
            $this->grocery_crud->callback_column('valoracion', array($this, '_column_valoracion'));
            $this->grocery_crud->callback_column('valoracion_excel', array($this, '_column_valoracion_excel'));

                   }



        // para usuarios 0, 1, 3, 4, 5  (NO 2 )
        if ($this->session->categoria != 2 ) {

            $campos = array(
                'codigo_producto',
                'id_producto',
                'nombre',
                'nombre_generico',
                'codigo_ean',
                'id_grupo',
                'id_familia',
                'peso_real',
                'anada',
                'stock_minimo',
                'control_stock',
                'fecha_alta',
                'unidades_caja',
                'id_proveedor_web',
                'precio_ultimo_unidad',
                'precio_ultimo_peso',
                'descuento_1_compra',
                'precio_transformacion_unidad',
                'precio_transformacion_peso',
                'precio_compra',
                'unidades_precio',
                'tipo_unidad',
                'tipo_unidad_mostrar',
                'tarifa_venta_unidad',
                'tarifa_venta_peso',
                'iva',
                'margen_real_producto',
                'beneficio_recomendado',
              
                'tipo_unidad',
                'url_producto',
                'url_imagen_portada',
                'notas',
            
            );

            if ($this->session->categoria < 2) {
                //campos para mostrar en add/edit
                $this->grocery_crud->fields($campos);
                $campos_no_read = array('precio_transformacion', 'tarifa_venta_unidad', 'tarifa_venta_peso', 'fecha_caducidad', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'unidades_stock', 'status_producto', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'fecha_caducidad', 'codigo_web', 'nombre_web', 'id_proveedor', 'precio', 'tarifa_venta_kg');
                $this->grocery_crud->unset_read_fields($campos_no_read);
                //$this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto', 'cat_marca', 'descuento_profesionales', 'tarifa_profesionales', 'margen_venta_profesionales', 'descuento_profesionales_vip', 'tarifa_profesionales_vip', 'margen_venta_profesionales_vip', 'url_imagen_portada');
                $this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto',  'stock_total','valoracion','url_imagen_portada');
            }
            if ($this->session->categoria == 4 || $this->session->categoria == 5) {

                $campos = array('codigo_producto',
                    'id_producto',
                    'nombre',
                    'nombre_generico',
                    'codigo_ean',
                    'id_grupo',
                    'id_familia',
                    'peso_real',
                    'anada',
                    'stock_minimo',
                    'control_stock',
                    'fecha_alta',
                    'unidades_caja',
                    'id_proveedor_web',
                    'precio_ultimo_unidad',
                    'precio_ultimo_peso',
                    'descuento_1_compra',
                    'precio_transformacion',
                    'precio_transformacion_unidad',
                    'precio_transformacion_peso',
                    'precio_compra',
                    'unidades_precio',
                    'tipo_unidad',
                    'tipo_unidad_mostrar',
                    'tarifa_venta_unidad',
                    'tarifa_venta_peso',
                    'iva',
                    'margen_real_producto',
                    'beneficio_recomendado',
              
                    'tipo_unidad',
                    'url_producto',
                    'url_imagen_portada',
                    'notas',
                   
                );

                //campos para mostrar en add/edit
                $this->grocery_crud->field_type('descuento_profesionales_vip', 'hidden');
                $this->grocery_crud->field_type('tarifa_profesionales_vip', 'hidden');
                $this->grocery_crud->fields($campos);

                $this->grocery_crud->unset_read_fields('precio_transformacion', 'tarifa_venta_unidad', 'tarifa_venta_peso', 'margen_venta_profesionales_vip', 'tarifa_profesionales_vip', 'descuento_profesionales_vip', 'fecha_caducidad', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'unidades_stock', 'status_producto', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'fecha_caducidad', 'codigo_web', 'nombre_web', 'codigo_web', 'nombre_web', 'id_proveedor', 'precio', 'tarifa_venta_kg');
                //$this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto', 'beneficio_recomendado', 'url_imagen_portada');
                $this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto', 'beneficio_recomendado','stock_total' ,'url_imagen_portada');

                
            }
        }

        //User 2 - Sergi
        if ($this->session->categoria == 2) {
            $campos = array('codigo_producto',
                'id_producto',
                'nombre',
                'nombre_generico',
                'codigo_ean',
                'id_grupo',
                'id_familia',
                'peso_real',
                'anada',
                'stock_minimo',
                'unidades_caja',
                'id_proveedor_web',
                'tarifa_venta_unidad',
                'tarifa_venta_peso',
                'stock_total',
                'url_producto', 'url_imagen_portada');
            unset($campos[array_search('tarifa_venta_unidad', $campos)]);
            unset($campos[array_search('tarifa_venta_peso', $campos)]);
            $this->grocery_crud->fields($campos);
            $this->grocery_crud->unset_read_fields('tarifa_profesionales', 'tarifa_profesionales_vip', 'descuento_1_compra', 'descuento_profesionales', 'descuento_profesionales_vip', 'margen_venta_profesionales', 'margen_venta_profesionales_vip', 'grupo', 'familia', 'proveedor', 'tarifa_venta_unidad', 'tarifa_venta_peso', 'precio_compra', 'precio_transformacion', 'precio_transformacion_unidad', 'precio_transformacion_peso', 'fecha_caducidad',  'fecha_alta', 'fecha_modificacion', 'precio_ultimo_unidad', 'precio_ultimo_peso', 'descuento_1_compra', 'unidades_precio', 'beneficio_recomendado', 'margen_real_producto', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'unidades_stock', 'id_proveedor', 'precio', 'tarifa_venta_kg', 'notas', 'codigo_web', 'nombre_web', 'status_producto');
            $this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tarifa_venta_unidad', 'stock_total','url_imagen_portada');
        }

        //usuario Noelia ??
        if ($this->session->categoria == 3) {
            $campos = array('codigo_producto', 'codigo_web', 'nombre', 'peso_real', 'url_producto', 'url_imagen_portada');

            //columnas mostradas en grid
            $this->grocery_crud->columns('codigo_producto', 'codigo_web', 'nombre', 'peso_real', 'url_imagen_portada');

            //campos para mostrar en add
            $this->grocery_crud->fields($campos);
            //campos para mostrar en edit
            $this->grocery_crud->edit_fields($campos);
            $this->grocery_crud->unset_read_fields('tarifa_venta_unidad', 'anada', 'id_producto', 'unidades_caja', 'tarifa_venta_peso', 'precio_ultimo_unidad', 'precio_ultimo_peso', 'id_proveedor_web', 'id_familia', 'id_grupo', 'codigo_ean', 'fecha_caducidad', 'stock_minimo', 'fecha_alta', 'fecha_modificacion', 'precio_ultimo_unidad', 'precio_ultimo_peso', 'descuento_1_compra', 'unidades_precio', 'beneficio_recomendado', 'margen_real_producto', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'unidades_stock', 'id_proveedor', 'precio', 'tarifa_venta_kg', 'notas', 'nombre_web', 'status_producto');
        }

        $this->grocery_crud->callback_before_insert(array($this, '_calculos_formateos_db'));
        $this->grocery_crud->callback_before_update(array($this, '_calculos_formateos_db'));

        $this->grocery_crud->callback_after_update(array($this, '_registros_after_productos'));
        $this->grocery_crud->callback_after_insert(array($this, '_registros_after_productos'));

        
        //para exportar a Excel TODA la tabla, columnas seleccionadas
        //productos()
        if($this->uri->segment(3)=='export'){
            
                $this->db->query("UPDATE pe_productos SET precio_compra_excel=precio_compra, tarifa_venta_excel=tarifa_venta,peso_real_excel=peso_real, valoracion_excel=valoracion, margen_real_producto_excel=margen_real_producto,url_imagen_portada_excel=url_imagen_portada");
                $string=array('id','codigo_producto','id_producto','nombre','peso_real_excel','tipo_unidad','id_grupo','id_familia','precio_compra_excel','id_proveedor_web','tarifa_venta_excel','stock_total','valoracion_excel','margen_real_producto_excel','url_imagen_portada_excel');
			    $this->grocery_crud->columns( $string);
        } 

        $output = $this->grocery_crud->render();
        $output = (array) $output;
        $output['titulo'] = 'Productos';
        $output['col_bootstrap'] = 12;
        $output = (object) $output;
        $this->_table_output_productos($output, "Productos");
        return;
    }

   

   
    public function productosDescatalogados() {
        
       
        $this->grocery_crud->set_language("spanish");
        $this->grocery_crud->set_table('pe_productos');
        $this->grocery_crud->set_subject('Productos Descatalogados');
        $this->grocery_crud->where('status_producto', '0');

        $this->grocery_crud->unset_edit();
        //$this->grocery_crud->unset_add();

        $this->grocery_crud->unset_delete();
        //$this->grocery_crud->callback_before_delete(array($this,'check_before_delete'));

        $this->grocery_crud->add_action('Catalogar', '', '', 'fa fa-sign-in', array($this, '_activar_productos'));
        $this->grocery_crud->add_action('Eliminar', '', '', 'fa fa-trash', array($this, '_eliminar_producto'));

        
        
         
        
        if (true) {
            $this->grocery_crud->display_as('id_producto', 'Cód. Básc.');
            $this->grocery_crud->display_as('codigo_producto', 'Código Producto (13)');

            $this->grocery_crud->display_as('descuento_1_compra', 'Dto. (%)');
            $this->grocery_crud->display_as('codigo_ean', 'Código EAN');
            $this->grocery_crud->display_as('id_familia', 'Familia');
            $this->grocery_crud->display_as('peso_real', 'Peso Real (Kg)');
            $this->grocery_crud->display_as('id_grupo', 'Grupo');
            $this->grocery_crud->display_as('anada', 'Añada');
            $this->grocery_crud->display_as('iva', 'IVA (%)');
            $this->grocery_crud->display_as('nombre', 'Nombre');
            $this->grocery_crud->display_as('nombre_generico', 'Nombre genérico');

            $this->grocery_crud->display_as('nombre_web', 'Nombre Producto Básculas');
            $this->grocery_crud->display_as('precio_ultimo_unidad', 'Precio Compra (€/unidad compra)');
            $this->grocery_crud->display_as('precio_ultimo_peso', 'Precio Compra (€/Kg compra)');
            $this->grocery_crud->display_as('precio_compra', 'Precio Compra Final en Tienda');
            $this->grocery_crud->display_as('tarifa_venta_unidad', 'Precio venta PVP (€/unidad)');
            $this->grocery_crud->display_as('tarifa_venta_peso', 'Precio venta PVP (€/Kg)');
            $this->grocery_crud->display_as('beneficio_recomendado', 'Beneficio recomendado (%)');
            $this->grocery_crud->display_as('margen_real_producto', 'Margen (%)');
            $this->grocery_crud->display_as('margen_real_producto_excel', 'Margen (%)');

            $this->grocery_crud->display_as('id_proveedor_web', 'Proveedor');
            $this->grocery_crud->display_as('id_proveedor_2', 'Proveedor 2');
            $this->grocery_crud->display_as('id_proveedor_3', 'Proveedor 3');
            $this->grocery_crud->display_as('precio_unidad_2', 'Precio Unidad Proveedor 2 (€/und)');
            $this->grocery_crud->display_as('precio_peso_2', 'Precio Peso Proveedor 2 (€/Kg)');
            $this->grocery_crud->display_as('precio_unidad_3', 'Precio Unidad Proveedor 3 (€/und)');
            $this->grocery_crud->display_as('precio_peso_3', 'Precio Peso Proveedor 3 (€/Kg)');
            $this->grocery_crud->display_as('fecha_proveedor_2', 'Fecha Proveedor 2');
            $this->grocery_crud->display_as('fecha_proveedor_3', 'Fecha Proveedor 3');
            $this->grocery_crud->display_as('unidades_precio', 'Unidades Precio');
            $this->grocery_crud->display_as('url_imagen_portada', 'Imagen Producto');
            $this->grocery_crud->display_as('stock_total', 'Total unidades stock');
            $this->grocery_crud->display_as('valoracion', 'Valor stock precio compra actual');

            // $this->grocery_crud->display_as('cat_nombre', 'Catálogo Nombre producto cast ');
            // $this->grocery_crud->display_as('cat_marca', 'Catálogo Marca  ');
            // $this->grocery_crud->display_as('cat_marca_text', 'Catálogo Marca  ');
            // $this->grocery_crud->display_as('cat_referencia', 'Catálogo Referencia cast ');
            // $this->grocery_crud->display_as('cat_orden', 'Catálogo Orden en marca cast');
            // $this->grocery_crud->display_as('cat_url_producto', 'Catálogo Url imagen producto cast ');
            // $this->grocery_crud->display_as('cat_origen', 'Catálogo Origen cast ');
            // $this->grocery_crud->display_as('cat_raza', 'Catálogo Raza cast ');
            // $this->grocery_crud->display_as('cat_curado', 'Catálogo Curación cast ');
            // $this->grocery_crud->display_as('cat_pesos', 'Catálogo Pesos cast ');
            // $this->grocery_crud->display_as('cat_anada', 'Catálogo Añada cast ');
            // $this->grocery_crud->display_as('cat_formato', 'Catálogo Formato cast ');
            // $this->grocery_crud->display_as('cat_unidades_caja', 'Catálogo Unidades Caja cast ');
            // $this->grocery_crud->display_as('cat_ecologica', 'Catálogo Ecológica cast ');
            // $this->grocery_crud->display_as('cat_tipo_de_uva', 'Catálogo Tipo de uva cast ');
            // $this->grocery_crud->display_as('cat_volumen', 'Catálogo Volumen cast ');
            // $this->grocery_crud->display_as('cat_variedades', 'Catálogo Variedades cast ');
            // $this->grocery_crud->display_as('cat_descripcion', 'Catálogo Descripción cast ');
            // $this->grocery_crud->display_as('cat_tarifa', 'Catálogo Tarifa cast ');
            // $this->grocery_crud->display_as('cat_unidad', 'Catálogo Unidad cast ');

            // $this->grocery_crud->display_as('cat_nombre_en', 'Catálogo Nombre producto inglés ');
            // $this->grocery_crud->display_as('cat_marca_en', 'Catálogo Marca ');
            // $this->grocery_crud->display_as('cat_marca_text_en', 'Catálogo Marca ');
            // $this->grocery_crud->display_as('cat_referencia_en', 'Catálogo Referencia inglés ');
            // $this->grocery_crud->display_as('cat_orden_en', 'Catálogo Orden en marca inglés ');
            // $this->grocery_crud->display_as('cat_url_producto_en', 'Catálogo Url imagen producto inglés ');
            // $this->grocery_crud->display_as('cat_origen_en', 'Catálogo Origen inglés ');
            // $this->grocery_crud->display_as('cat_raza_en', 'Catálogo Raza inglés ');
            // $this->grocery_crud->display_as('cat_curado_en', 'Catálogo Curación inglés ');
            // $this->grocery_crud->display_as('cat_pesos_en', 'Catálogo Pesos inglés ');
            // $this->grocery_crud->display_as('cat_anada_en', 'Catálogo Añada inglés ');
            // $this->grocery_crud->display_as('cat_formato_en', 'Catálogo Formato inglés ');
            // $this->grocery_crud->display_as('cat_unidades_caja_en', 'Catálogo Unidades Caja inglés ');
            // $this->grocery_crud->display_as('cat_ecologica_en', 'Catálogo Ecológica inglés ');
            // $this->grocery_crud->display_as('cat_tipo_de_uva_en', 'Catálogo Tipo de uva inglés ');
            // $this->grocery_crud->display_as('cat_volumen_en', 'Catálogo Volumen inglés ');
            // $this->grocery_crud->display_as('cat_variedades_en', 'Catálogo Variedades inglés ');
            // $this->grocery_crud->display_as('cat_descripcion_en', 'Catálogo Descripción inglés ');
            // $this->grocery_crud->display_as('cat_tarifa_en', 'Catálogo Tarifa inglés ');
            // $this->grocery_crud->display_as('cat_unidad_en', 'Catálogo Unidad inglés ');

            // $this->grocery_crud->display_as('cat_nombre_fr', 'Catálogo Nombre producto francés ');
            // $this->grocery_crud->display_as('cat_marca_fr', 'Catálogo Marca ');
            // $this->grocery_crud->display_as('cat_marca_text_fr', 'Catálogo Marca ');
            // $this->grocery_crud->display_as('cat_referencia_fr', 'Catálogo Referencia francés ');
            // $this->grocery_crud->display_as('cat_orden_fr', 'Catálogo Orden en marca francés ');
            // $this->grocery_crud->display_as('cat_url_producto_fr', 'Catálogo Url imagen producto francés ');
            // $this->grocery_crud->display_as('cat_origen_fr', 'Catálogo Origen francés ');
            // $this->grocery_crud->display_as('cat_raza_fr', 'Catálogo Raza francés ');
            // $this->grocery_crud->display_as('cat_curado_fr', 'Catálogo Curación francés ');
            // $this->grocery_crud->display_as('cat_pesos_fr', 'Catálogo Pesos francés ');
            // $this->grocery_crud->display_as('cat_anada_fr', 'Catálogo Añada francés ');
            // $this->grocery_crud->display_as('cat_formato_fr', 'Catálogo Formato francés ');
            // $this->grocery_crud->display_as('cat_unidades_caja_fr', 'Catálogo Unidades Caja francés ');
            // $this->grocery_crud->display_as('cat_ecologica_fr', 'Catálogo Ecológica francés ');
            // $this->grocery_crud->display_as('cat_tipo_de_uva_fr', 'Catálogo Tipo de uva francés ');
            // $this->grocery_crud->display_as('cat_volumen_fr', 'Catálogo Volumen francés ');
            // $this->grocery_crud->display_as('cat_variedades_fr', 'Catálogo Variedades francés ');
            // $this->grocery_crud->display_as('cat_descripcion_fr', 'Catálogo Descripción francés ');
            // $this->grocery_crud->display_as('cat_tarifa_fr', 'Catálogo Tarifa francés ');
            // $this->grocery_crud->display_as('cat_unidad_fr', 'Catálogo Unidad francés ');


            $this->grocery_crud->display_as('tipo_unidad', 'Tipo Unidad');
            $this->grocery_crud->display_as('tipo_unidad_mostrar', 'Tipo Unidad');
            $this->grocery_crud->display_as('precio_transformacion', 'Precio Transformación');
            $this->grocery_crud->display_as('precio_transformacion_unidad', 'Precio Transformación (€/unidad)');
            $this->grocery_crud->display_as('precio_transformacion_peso', 'Precio Transformación (€/peso)');

            $this->grocery_crud->display_as('tarifa_venta', 'Tarifa PVP');
            $this->grocery_crud->display_as('descuento_profesionales', 'Dto. Prof. (%)');
            $this->grocery_crud->display_as('descuento_profesionales_vip', 'Dto. Max. Prof. (%)');
            $this->grocery_crud->display_as('tarifa_profesionales', 'Tarifa Venta Prof. (€/tipo unidad)');
            $this->grocery_crud->display_as('tarifa_profesionales_vip', 'Tarifa Venta Mínima Prof. (€/tipo unidad)');
            $this->grocery_crud->display_as('margen_venta_profesionales', 'Margen Venta Prof. (%)');
            $this->grocery_crud->display_as('margen_venta_profesionales_vip', 'Margen Mínimo Venta Prof. (%)');
        }
        
        //columnas sin ordenación
        //basado en https://github.com/scoumbourdis/grocery-crud/pull/210/commits/39ce8f0fc3e4cb1639ec34678ccb0a010e81d638
        $this->grocery_crud->field_without_sorter(array('tarifa_venta','precio_compra','margen_real_producto','stock_total','valoracion','url_imagen_portada'));
        //fin columnas sin ordenación

        //definiciones relaciones, únicos, obligatorios
        if (true) {
            //campos relacionados
            $this->grocery_crud->set_relation('id_grupo', 'pe_grupos', 'nombre_grupo');
            $this->grocery_crud->set_relation('id_familia', 'pe_familias', 'nombre_familia');
            $this->grocery_crud->set_relation('id_proveedor_web', 'pe_proveedores_acreedores', 'nombre');
            $this->grocery_crud->set_relation('id_proveedor_2', 'pe_proveedores_acreedores', 'nombre');
            $this->grocery_crud->set_relation('id_proveedor_3', 'pe_proveedores_acreedores', 'nombre');
            // $this->grocery_crud->set_relation('cat_marca', 'pe_marcas', 'marca');
            // $this->grocery_crud->set_relation('cat_marca_en', 'pe_marcas', 'marca');
            // $this->grocery_crud->set_relation('cat_marca_fr', 'pe_marcas', 'marca');
            $this->grocery_crud->set_relation('modificado_por', 'pe_users', 'nombre');

            // campos únicos
            $this->grocery_crud->unique_fields('codigo_producto', 'nombre');

            ///campos obligatorios
            $this->grocery_crud->required_fields('control_stock','id_proveedor_web','beneficio_recomendado', 'unidades_precio', 'codigo_producto', 'id_producto', 'nombre', 'id_grupo', 'id_familia');

            //validaciones
            $this->grocery_crud->set_rules('precio_ultimo_unidad', 'precio unidad', 'callback_comparar[' . $this->input->post('precio_ultimo_peso') . ']');
            $this->grocery_crud->set_rules('tarifa_venta_unidad', 'tarifa PVP unidad', 'callback_compararTarifa[' . $this->input->post('tarifa_venta_peso') . ']');
            $this->grocery_crud->set_rules('tarifa_venta_unidad', 'tarifa PVP unidad', 'callback_comparar_precios[' . $this->input->post('precio_ultimo_unidad') . ']');
            $this->grocery_crud->set_rules('tarifa_venta_peso', 'tarifa PVP unidad', 'callback_comparar_precios[' . $this->input->post('precio_ultimo_peso') . ']');
            $this->grocery_crud->set_rules('codigo_producto', 'Código de producto', 'callback_codigo_producto');
            $this->grocery_crud->set_rules('id_familia', 'Familia', 'greater_than[0]');
            $this->grocery_crud->set_rules('nombre', 'Nombre producto', 'callback_nombre');
            $this->grocery_crud->set_rules('id_producto', 'Código Boka', 'callback_id_producto[' . $this->input->post('tipo_unidad') . ']');
        }

        
        //$this->grocery_crud->field_without_sorter('codigo_producto');

        //precalculo tabla view  
        if (true) {
            $this->grocery_crud->callback_read_field('peso_real', array($this, '_column_left_align_peso'));
            $this->grocery_crud->callback_read_field('codigo_ean', array($this, '_read_codigo_ean'));
            $this->grocery_crud->callback_read_field('precio_ultimo_peso', array($this, '_read_precio_ultimo_peso'));
            $this->grocery_crud->callback_read_field('precio_ultimo_unidad', array($this, '_read_precio_ultimo_unidad'));
            $this->grocery_crud->callback_read_field('precio_compra', array($this, '_read_precio_compra'));
            $this->grocery_crud->callback_read_field('tarifa_venta_unidad', array($this, '_read_tarifa_unidad'));
            $this->grocery_crud->callback_read_field('tarifa_venta_peso', array($this, '_read_tarifa_peso'));
            $this->grocery_crud->callback_read_field('tarifa_venta', array($this, '_read_tarifa_venta'));
            $this->grocery_crud->callback_read_field('tipo_unidad_mostrar', array($this, '_read_tipo_unidad_mostrar'));
            $this->grocery_crud->callback_read_field('descuento_1_compra', array($this, '_read_cantidad_porcentaje'));
            $this->grocery_crud->callback_read_field('beneficio_recomendado', array($this, '_read_cantidad_porcentaje'));
            $this->grocery_crud->callback_read_field('iva', array($this, '_read_cantidad_porcentaje'));
            $this->grocery_crud->callback_read_field('stock_minimo', array($this, '_read_stock_minimo'));
            $this->grocery_crud->callback_read_field('unidades_caja', array($this, '_read_unidades_caja'));
            $this->grocery_crud->callback_read_field('unidades_precio', array($this, '_read_unidades_precio'));
            $this->grocery_crud->callback_read_field('margen_real_producto', array($this, '_read_margen_real_producto' ));
            $this->grocery_crud->callback_read_field('fecha_alta', array($this, '_read_fecha'));
            $this->grocery_crud->callback_read_field('fecha_modificacion', array($this, '_read_fecha'));
            $this->grocery_crud->callback_read_field('url_producto', array($this, '_read_url_producto'));
            $this->grocery_crud->callback_read_field('url_imagen_portada', array($this, '_read_url_imagen_portada'));
            // $this->grocery_crud->callback_read_field('cat_unidades_caja', array($this, '_read_cat_unidades_caja'));
            // $this->grocery_crud->callback_read_field('cat_unidades_caja_en', array($this, '_read_cat_unidades_caja'));
            // $this->grocery_crud->callback_read_field('cat_unidades_caja_fr', array($this, '_read_cat_unidades_caja'));
            // $this->grocery_crud->callback_read_field('cat_tarifa', array($this, '_read_cat_tarifa'));
            // $this->grocery_crud->callback_read_field('cat_tarifa_en', array($this, '_read_cat_tarifa'));
            // $this->grocery_crud->callback_read_field('cat_tarifa_fr', array($this, '_read_cat_tarifa'));
            $this->grocery_crud->callback_read_field('descuento_profesionales', array($this, '_read_descuento_profesionales'));
            $this->grocery_crud->callback_read_field('descuento_profesionales_vip', array($this, '_read_descuento_profesionales_vip'));
            $this->grocery_crud->callback_read_field('tarifa_profesionales', array($this, '_read_tarifa_profesionales'));
            $this->grocery_crud->callback_read_field('tarifa_profesionales_vip', array($this, '_read_tarifa_profesionales_vip'));
            $this->grocery_crud->callback_read_field('margen_venta_profesionales', array($this, '_read_margen_venta_profesionales'));
            $this->grocery_crud->callback_read_field('margen_venta_profesionales_vip', array($this, '_read_margen_venta_profesionales_vip'));
            $this->grocery_crud->callback_read_field('id_producto', array($this, '_read_id_producto'));
            $this->grocery_crud->callback_read_field('nombre', array($this, '_read_nombre'));
            $this->grocery_crud->callback_read_field('precio_transformacion', array($this, '_read_precio_transformacion'));
            $this->grocery_crud->callback_read_field('precio_transformacion_unidad', array($this, '_read_precio_transformacion_unidad'));
            $this->grocery_crud->callback_read_field('precio_transformacion_peso', array($this, '_read_precio_transformacion_peso'));
        }

        //precalculo campos edit y/o add
        if (true) {
            $this->grocery_crud->callback_edit_field('codigo_producto', array($this, '_field_edit_codigo_producto'));
            $this->grocery_crud->callback_add_field('codigo_producto', array($this, '_field_add_codigo_producto'));
            $this->grocery_crud->callback_field('id_producto', array($this, '_field_id_producto'));
            $this->grocery_crud->callback_field('nombre', array($this, '_field_nombre'));
            $this->grocery_crud->callback_field('nombre_generico', array($this, '_field_nombre_generico'));
            $this->grocery_crud->callback_field('anada', array($this, '_field_anada'));
            $this->grocery_crud->callback_field('peso_real', array($this, '_field_peso_real'));
            $this->grocery_crud->callback_field('unidades_caja', array($this, '_field_unidades_caja'));
            $this->grocery_crud->callback_field('codigo_ean', array($this, '_field_codigo_ean'));
            $this->grocery_crud->callback_field('stock_minimo', array($this, '_field_stock_minimo'));
            $this->grocery_crud->callback_field('tipo_unidad_mostrar', array($this, '_field_tipo_unidad_mostrar'));
            $this->grocery_crud->callback_field('precio_ultimo_peso', array($this, '_field_precio_ultimo_peso'));
            $this->grocery_crud->callback_field('precio_compra', array($this, '_field_precio_compra'));
            $this->grocery_crud->callback_field('precio_ultimo_unidad', array($this, '_field_precio_ultimo_unidad'));
            $this->grocery_crud->callback_field('precio_transformacion_unidad', array($this, '_field_precio_transformacion_unidad'));
            $this->grocery_crud->callback_field('precio_transformacion_peso', array($this, '_field_precio_transformacion_peso'));
            $this->grocery_crud->callback_field('unidades_precio', array($this, '_field_unidades_precio'));
            $this->grocery_crud->callback_field('tarifa_venta_peso', array($this, '_field_tarifa_venta_peso'));
            $this->grocery_crud->callback_field('tarifa_venta_unidad', array($this, '_field_tarifa_venta_unidad'));
            $this->grocery_crud->callback_field('descuento_1_compra', array($this, '_field_descuento_1_compra'));
            $this->grocery_crud->callback_field('beneficio_recomendado', array($this, '_field_beneficio_recomendado'));
            $this->grocery_crud->callback_field('margen_real_producto', array($this, '_field_margen_real_producto'));
            $this->grocery_crud->callback_field('descuento_profesionales', array($this, '_field_descuento_profesionales'));
            $this->grocery_crud->callback_field('descuento_profesionales_vip', array($this, '_field_descuento_profesionales_vip'));
            $this->grocery_crud->callback_field('tarifa_profesionales', array($this, '_field_tarifa_profesionales'));
            $this->grocery_crud->callback_field('tarifa_profesionales_vip', array($this, '_field_tarifa_profesionales_vip'));
            $this->grocery_crud->callback_field('url_producto', array($this, '_field_url_producto'));
            $this->grocery_crud->callback_field('url_imagen_portada', array($this, '_field_url_imagen_portada'));
            $this->grocery_crud->callback_field('notas', array($this, '_field_notas'));
            // $this->grocery_crud->callback_field('cat_unidades_caja', array($this, '_field_cat_unidades_caja'));
            // $this->grocery_crud->callback_field('cat_unidades_caja_en', array($this, '_field_cat_unidades_caja_en'));
            // $this->grocery_crud->callback_field('cat_unidades_caja_fr', array($this, '_field_cat_unidades_caja_fr'));
            // $this->grocery_crud->callback_field('cat_tarifa', array($this, '_field_cat_tarifa'));
            // $this->grocery_crud->callback_field('cat_tarifa_en', array($this, '_field_cat_tarifa_en'));
            // $this->grocery_crud->callback_field('cat_tarifa_fr', array($this, '_field_cat_tarifa_fr'));
            // $this->grocery_crud->callback_field('cat_orden', array($this, '_field_cat_orden'));
            // $this->grocery_crud->callback_field('cat_orden_en', array($this, '_field_cat_orden_en'));
            // $this->grocery_crud->callback_field('cat_orden_fr', array($this, '_field_cat_orden_fr'));

            $this->grocery_crud->field_type('iva', 'hidden');
            // $this->grocery_crud->field_type('tarifa_profesionales', 'hidden');
            $this->grocery_crud->field_type('tipo_unidad', 'hidden');
        }

        //precálculo columnas grid
        if (true) {
            $this->grocery_crud->callback_column('id_producto', array($this, '_column_id_producto'));
            $this->grocery_crud->callback_column('nombre', array($this, '_column_nombre'));
            $this->grocery_crud->callback_column('peso_real', array($this, '_column_peso'));
            $this->grocery_crud->callback_column('precio_ultimo_unidad', array($this, '_column_precio_ultimo_unidad'));
            $this->grocery_crud->callback_column('precio_ultimo_peso', array($this, '_column_precio_ultimo_peso'));
            $this->grocery_crud->callback_column('precio_compra', array($this, '_column_precio_compra'));
            $this->grocery_crud->callback_column('precio_transformacion', array($this, '_column_precio_transformacion'));

            $this->grocery_crud->callback_column('descuento_1_compra', array($this, '_column_descuento_1_compra'));
            $this->grocery_crud->callback_column('tarifa_venta_unidad', array($this, '_column_right_tarifa_venta'));
            $this->grocery_crud->callback_column('tarifa_venta_peso', array($this, '_column_right_tarifa_venta'));
            $this->grocery_crud->callback_column('margen_real_producto', array($this, '_column_right_margen_real_producto'));
            $this->grocery_crud->callback_column('margen_real_producto_excel', array($this, '_column_right_margen_real_producto_excel'));

            $this->grocery_crud->callback_column('beneficio_recomendado', array($this, '_column_beneficio_recomendado'));
            $this->grocery_crud->callback_column('iva', array($this, '_column_right_iva'));
            $this->grocery_crud->callback_column('unidades_caja', array($this, '_column_peso'));
            $this->grocery_crud->callback_column('id_producto', array($this, '_column_id_producto'));
            $this->grocery_crud->callback_column('url_imagen_portada', array($this, '_imagen_producto'));
            // $this->grocery_crud->callback_column('tipo_unidad', array($this, '_column_tipos_unidades'));
            $this->grocery_crud->callback_column('tarifa_venta', array($this, '_column_tarifa_venta'));
            $this->grocery_crud->callback_column('stock_total', array($this, '_column_stock_total'));
            $this->grocery_crud->callback_column('valoracion', array($this, '_column_valoracion'));

            $this->grocery_crud->callback_column('descuento_profesionales', array($this, '_column_descuento_profesionales'));
            $this->grocery_crud->callback_column('descuento_profesionales_vip', array($this, '_column_descuento_profesionales_vip'));
            $this->grocery_crud->callback_column('tarifa_profesionales', array($this, '_column_tarifa_profesionales'));
            $this->grocery_crud->callback_column('tarifa_profesionales_vip', array($this, '_column_tarifa_profesionales_vip'));
            $this->grocery_crud->callback_column('margen_venta_profesionales', array($this, '_column_margen_venta_profesionales'));
            $this->grocery_crud->callback_column('margen_venta_profesionales_vip', array($this, '_column_margen_venta_profesionales_vip'));
        }



        // para usuarios 0, 1, 3, 4, 5  (NO 2 )
        if ($this->session->categoria != 2 ) {

            $campos = array(
                'codigo_producto',
                'id_producto',
                'nombre',
                'nombre_generico',
                'codigo_ean',
                'id_grupo',
                'id_familia',
                'peso_real',
                'anada',
                'stock_minimo',
                'control_stock',
                'fecha_alta',
                'unidades_caja',
                'id_proveedor_web',
                'precio_ultimo_unidad',
                'precio_ultimo_peso',
                'descuento_1_compra',
                //  'precio_transformacion',
                'precio_transformacion_unidad',
                'precio_transformacion_peso',
                'precio_compra',
                'unidades_precio',
                'tipo_unidad',
                'tipo_unidad_mostrar',
                'tarifa_venta_unidad',
                'tarifa_venta_peso',
                'iva',
                'margen_real_producto',
                'beneficio_recomendado',
               // 'descuento_profesionales',
               // 'tarifa_profesionales',
               // 'descuento_profesionales_vip',
               // 'tarifa_profesionales_vip',
                'tipo_unidad',
                'url_producto',
                'url_imagen_portada',
                'notas',
                // 'cat_marca',
                // 'cat_nombre',
                // 'cat_referencia',
                // 'cat_orden',
                // 'cat_url_producto',
                // 'cat_origen',
                // 'cat_raza',
                // 'cat_curado',
                // 'cat_pesos',
                // 'cat_anada',
                // 'cat_formato',
                // 'cat_unidades_caja',
                // 'cat_ecologica',
                // 'cat_tipo_de_uva',
                // 'cat_volumen',
                // 'cat_variedades',
                // 'cat_descripcion',
               // 'cat_tarifa',
                // 'cat_unidad',
                // 'cat_marca_en',
                // 'cat_nombre_en',
                // 'cat_referencia_en',
                // 'cat_orden_en',
                // 'cat_url_producto_en',
                // 'cat_origen_en',
                // 'cat_raza_en',
                // 'cat_curado_en',
                // 'cat_pesos_en',
                // 'cat_anada_en',
                // 'cat_formato_en',
                // 'cat_unidades_caja_en',
                // 'cat_ecologica_en',
                // 'cat_tipo_de_uva_en',
                // 'cat_volumen_en',
                // 'cat_variedades_en',
                // 'cat_descripcion_en',
               // 'cat_tarifa_en',
                // 'cat_unidad_en',
                // 'cat_marca_fr',
                // 'cat_nombre_fr',
                // 'cat_referencia_fr',
                // 'cat_orden_fr',
                // 'cat_url_producto_fr',
                // 'cat_origen_fr',
                // 'cat_raza_fr',
                // 'cat_curado_fr',
                // 'cat_pesos_fr',
                // 'cat_anada_fr',
                // 'cat_formato_fr',
                // 'cat_unidades_caja_fr',
                // 'cat_ecologica_fr',
                // 'cat_tipo_de_uva_fr',
                // 'cat_volumen_fr',
                // 'cat_variedades_fr',
                // 'cat_descripcion_fr',
                //'cat_tarifa_fr',
                // 'cat_unidad_fr'
            );

            if ($this->session->categoria < 2) {
                //campos para mostrar en add/edit
                $this->grocery_crud->fields($campos);
                $campos_no_read = array('precio_transformacion', 'tarifa_venta_unidad', 'tarifa_venta_peso', 'fecha_caducidad', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'unidades_stock', 'status_producto', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'fecha_caducidad', 'codigo_web', 'nombre_web', 'id_proveedor', 'precio', 'tarifa_venta_kg');
                $this->grocery_crud->unset_read_fields($campos_no_read);
                //$this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto', 'cat_marca', 'descuento_profesionales', 'tarifa_profesionales', 'margen_venta_profesionales', 'descuento_profesionales_vip', 'tarifa_profesionales_vip', 'margen_venta_profesionales_vip', 'url_imagen_portada');
                $this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto',  'stock_total','valoracion','url_imagen_portada');
            }
            if ($this->session->categoria == 4 || $this->session->categoria == 5) {

                $campos = array('codigo_producto',
                    'id_producto',
                    'nombre',
                    'nombre_generico',
                    'codigo_ean',
                    'id_grupo',
                    'id_familia',
                    'peso_real',
                    'anada',
                    'stock_minimo',
                    'control_stock',
                    'fecha_alta',
                    'unidades_caja',
                    'id_proveedor_web',
                    'precio_ultimo_unidad',
                    'precio_ultimo_peso',
                    'descuento_1_compra',
                    'precio_transformacion',
                    'precio_transformacion_unidad',
                    'precio_transformacion_peso',
                    'precio_compra',
                    'unidades_precio',
                    'tipo_unidad',
                    'tipo_unidad_mostrar',
                    'tarifa_venta_unidad',
                    'tarifa_venta_peso',
                    'iva',
                    'margen_real_producto',
                    'beneficio_recomendado',
                  //  'descuento_profesionales',
                  //  'tarifa_profesionales',
                    // 'descuento_profesionales_vip',
                    // 'tarifa_profesionales_vip',
                    'tipo_unidad',
                    'url_producto',
                    'url_imagen_portada',
                    'notas',
                    // 'cat_nombre',
                    // 'cat_marca',
                    // 'cat_referencia',
                    // 'cat_url_producto',
                    // 'cat_origen',
                    // 'cat_raza',
                    // 'cat_curado',
                    // 'cat_pesos',
                    // 'cat_anada',
                    // 'cat_formato',
                    // 'cat_unidades_caja',
                    // 'cat_ecologica',
                    // 'cat_tipo_de_uva',
                    // 'cat_volumen',
                    // 'cat_variedades',
                    // 'cat_descripcion',
                    //'cat_tarifa',
                    // 'cat_unidad',
                    // 'cat_nombre_en',
                    // 'cat_marca_en',
                    // 'cat_referencia_en',
                    // 'cat_url_producto_en',
                    // 'cat_origen_en',
                    // 'cat_raza_en',
                    // 'cat_curado_en',
                    // 'cat_pesos_en',
                    // 'cat_anada_en',
                    // 'cat_formato_en',
                    // 'cat_unidades_caja_en',
                    // 'cat_ecologica_en',
                    // 'cat_tipo_de_uva_en',
                    // 'cat_volumen_en',
                    // 'cat_variedades_en',
                    // 'cat_descripcion_en',
                    // 'cat_tarifa_en',
                    //'cat_unidad_en',
                    // 'cat_nombre_fr',
                    // 'cat_marca_fr',
                    // 'cat_referencia_fr',
                    // 'cat_url_producto_fr',
                    // 'cat_origen_fr',
                    // 'cat_raza_fr',
                    // 'cat_curado_fr',
                    // 'cat_pesos_fr',
                    // 'cat_anada_fr',
                    // 'cat_formato_fr',
                    // 'cat_unidades_caja_fr',
                    // 'cat_ecologica_fr',
                    // 'cat_tipo_de_uva_fr',
                    // 'cat_volumen_fr',
                    // 'cat_variedades_fr',
                    // 'cat_descripcion_fr',
                    //'cat_tarifa_fr',
                    // 'cat_unidad_fr'
                );

                //campos para mostrar en add/edit
                $this->grocery_crud->field_type('descuento_profesionales_vip', 'hidden');
                $this->grocery_crud->field_type('tarifa_profesionales_vip', 'hidden');
                $this->grocery_crud->fields($campos);

                $this->grocery_crud->unset_read_fields('precio_transformacion', 'tarifa_venta_unidad', 'tarifa_venta_peso', 'margen_venta_profesionales_vip', 'tarifa_profesionales_vip', 'descuento_profesionales_vip', 'fecha_caducidad', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'unidades_stock', 'status_producto', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'fecha_caducidad', 'codigo_web', 'nombre_web', 'codigo_web', 'nombre_web', 'id_proveedor', 'precio', 'tarifa_venta_kg');
                //$this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto', 'beneficio_recomendado', 'url_imagen_portada');
                $this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tipo_unidad', 'precio_compra', 'id_proveedor_web', 'tarifa_venta', 'margen_real_producto', 'beneficio_recomendado','stock_total' ,'url_imagen_portada');

                
            }
        }

        //User 2 - Sergi
        if ($this->session->categoria == 2) {
            $campos = array('codigo_producto',
                'id_producto',
                'nombre',
                'nombre_generico',
                'codigo_ean',
                'id_grupo',
                'id_familia',
                'peso_real',
                'anada',
                'stock_minimo',
                'unidades_caja',
                'id_proveedor_web',
                'tarifa_venta_unidad',
                'tarifa_venta_peso',
                'stock_total',
                'url_producto', 'url_imagen_portada');
            //columnas mostradas en grid
            //$this->grocery_crud->columns($campos);

            //campos para mostrar en add/edit
            //User 2 (Sergi) puede ver PVP, pero no la puede editar porque no puede introducir precios compra
            unset($campos[array_search('tarifa_venta_unidad', $campos)]);
            unset($campos[array_search('tarifa_venta_peso', $campos)]);
            $this->grocery_crud->fields($campos);
            $this->grocery_crud->unset_read_fields(
            //     'cat_nombre', 
            //     'cat_marca', 
            //     'cat_referencia',
            //      'cat_orden',
            //       'cat_url_producto', 
            //       'cat_origen',
            //        'cat_raza',
            //         'cat_curado',
            //          'cat_pesos',
            //           'cat_anada',
            //            'cat_formato',
            //             'cat_unidades_caja',
            //              'cat_ecologica',
            //               'cat_tipo_de_uva',
            //                'cat_volumen',
            //                 'cat_variedades',
            //                  'cat_descripcion',
            //                   'cat_tarifa',
            //                    'cat_unidad',
            //                     'cat_nombre_en',
            //                      'cat_marca_en',
            //                       'cat_referencia_en',
            //                        'cat_orden_en',
            //                         'cat_url_producto_en',
            //                          'cat_origen_en',
            //                           'cat_raza_en',
            //                            'cat_curado_en',
            //                             'cat_pesos_en',
            //                              'cat_anada_en',
            //                               'cat_formato_en',
            //                                'cat_unidades_caja_en',
            //                                 'cat_ecologica_en',
            //                                  'cat_tipo_de_uva_en',
            //                                   'cat_volumen_en',
            //                                    'cat_variedades_en',
            //                                     'cat_descripcion_en',
            //                                      'cat_tarifa_en',
            //                                       'cat_unidad_en',
            //                                        'cat_nombre_fr',
            //                                         'cat_marca_fr',
            //                                          'cat_referencia_fr',
            //                                           'cat_orden_fr',
            //                                            'cat_url_producto_fr',
            //                                             'cat_origen_fr', 
            //                                             'cat_raza_fr', 
            //                                             'cat_curado_fr', 
            //                                             'cat_pesos_fr', 
            //                                             'cat_anada_fr', 
            //                                             'cat_formato_fr', 
            //                                             'cat_unidades_caja_fr', 
            //                                             'cat_ecologica_fr', 
            //                                             'cat_tipo_de_uva_fr', 
            //                                             'cat_volumen_fr', 
            //                                             'cat_variedades_fr', 
            //                                             'cat_descripcion_fr', 
            //                                             'cat_tarifa_fr', 
            //                                             'cat_unidad_fr', 
                                                        'tarifa_profesionales', 
                                                        'tarifa_profesionales_vip', 'descuento_1_compra', 'descuento_profesionales', 'descuento_profesionales_vip', 'margen_venta_profesionales', 'margen_venta_profesionales_vip', 'grupo', 'familia', 'proveedor', 'tarifa_venta_unidad', 'tarifa_venta_peso', 'precio_compra', 'precio_transformacion', 'precio_transformacion_unidad', 'precio_transformacion_peso', 'fecha_caducidad',  'fecha_alta', 'fecha_modificacion', 'precio_ultimo_unidad', 'precio_ultimo_peso', 'descuento_1_compra', 'unidades_precio', 'beneficio_recomendado', 'margen_real_producto', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'unidades_stock', 'id_proveedor', 'precio', 'tarifa_venta_kg', 'notas', 'codigo_web', 'nombre_web', 'status_producto');
            //$this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tarifa_venta_unidad', 'url_imagen_portada');
            $this->grocery_crud->columns('codigo_producto', 'id_producto', 'nombre', 'peso_real', 'tarifa_venta_unidad', 'stock_total','url_imagen_portada');
        }

        //usuario Noelia ??
        if ($this->session->categoria == 3) {
            $campos = array('codigo_producto', 'codigo_web', 'nombre', 'peso_real', 'url_producto', 'url_imagen_portada');

            //columnas mostradas en grid
            $this->grocery_crud->columns('codigo_producto', 'codigo_web', 'nombre', 'peso_real', 'url_imagen_portada');

            //campos para mostrar en add
            $this->grocery_crud->fields($campos);
            //campos para mostrar en edit
            $this->grocery_crud->edit_fields($campos);
            $this->grocery_crud->unset_read_fields('tarifa_venta_unidad', 'anada', 'id_producto', 'unidades_caja', 'tarifa_venta_peso', 'precio_ultimo_unidad', 'precio_ultimo_peso', 'id_proveedor_web', 'id_familia', 'id_grupo', 'codigo_ean', 'fecha_caducidad', 'stock_minimo', 'fecha_alta', 'fecha_modificacion', 'precio_ultimo_unidad', 'precio_ultimo_peso', 'descuento_1_compra', 'unidades_precio', 'beneficio_recomendado', 'margen_real_producto', 'id_proveedor_2', 'precio_unidad_2', 'precio_peso_2', 'fecha_proveedor_2', 'id_proveedor_3', 'precio_unidad_3', 'precio_peso_3', 'fecha_proveedor_3', 'unidades_stock', 'id_proveedor', 'precio', 'tarifa_venta_kg', 'notas', 'nombre_web', 'status_producto');
        }

        $this->grocery_crud->callback_before_insert(array($this, '_calculos_formateos_db'));
        $this->grocery_crud->callback_before_update(array($this, '_calculos_formateos_db'));

        $this->grocery_crud->callback_after_update(array($this, '_registros_after_productos'));
        $this->grocery_crud->callback_after_insert(array($this, '_registros_after_productos'));
        
        //para exportar a Excel TODA la tabla, columnas seleccionadas
        //productosDescatalogados()
        if($this->uri->segment(3)=='export'){
            $query = $this->db->query("select * from pe_productos");
			$string = [];
            foreach ($query->result_array() as $row)
                {
                        $string=array_keys($row);
                }
                $string=array('nombre');
			    $this->grocery_crud->columns( $string);
        } 

        $output = $this->grocery_crud->render();
        $output = (array) $output;
        $output['titulo'] = 'Productos Descatalogados';
        $output['col_bootstrap'] = 12;
        $output = (object) $output;
        $this->_table_output_productos_descatalogados($output, "Productos Descatalogados.");
        return;
    }

    
    function _registros_after_productos($post_array, $primary_key) {
        //log_message('INFO','PASO POR _registros_after_productos '.$primary_key);
       //poner datos edicion/nuevo producto
       //$this->db->update('pe_productos', array("fecha_modificacion" => date('Y-m-d'), "modificado_por" => $_SESSION['id']), array('id' => $primary_key));
       $fecha_modificacion=date('Y-m-d');
       $modificado_por=$this->session->id;
       $id=$primary_key;
       $this->db->query("UPDATE pe_productos SET "
               . " fecha_modificacion='$fecha_modificacion',"
               . " modificado_por ='$modificado_por' "
               . " WHERE id='$id'");

        //actualizacion iva
        $sql="UPDATE pe_productos p
              LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo
              LEFT JOIN pe_ivas i ON g.id_iva=i.id_iva
              SET p.iva=i.valor_iva*1000 
              WHERE p.id='$id'";
        $this->db->query($sql);

        //actualización margen
        $sql="UPDATE pe_productos p
                SET margen_real_producto=round( (round(tarifa_venta/(1+iva/100000),0)-precio_compra )/
                round(tarifa_venta/(1+iva/100000),0)*100000,0)
                WHERE p.id='$id'";
        $this->db->query($sql);    


        if ($this->session->categoria == 2)
            return true;
        if ($this->session->categoria == 3)
            return true;

        //se mantiene un histórico de precios/proveedor PVP
        if (!isset($post_array['precio_ultimo_peso']))
            $post_array['precio_ultimo_peso'] = 0;
        if (!isset($post_array['precio_ultimo_unidad']))
            $post_array['precio_ultimo_unidad'] = 0;
        if (!isset($post_array['tarifa_venta_unidad']))
            $post_array['tarifa_venta_unidad'] = 0;
        if (!isset($post_array['tarifa_venta_peso']))
            $post_array['tarifa_venta_peso'] = 0;

        // //copiamos datos en castellano del catálogo en los demas idiomas si NO esixtes
        // $sql = "UPDATE pe_productos "
        //         . " SET cat_marca_en=IF(cat_marca_en='',cat_marca,cat_marca_en), "
        //         . "  cat_marca_fr=IF(cat_marca_fr='',cat_marca,cat_marca_fr), "
        //         . "  cat_nombre_en=IF(cat_nombre_en='',cat_nombre,cat_nombre_en), "
        //         . "  cat_nombre_fr=IF(cat_nombre_fr='',cat_nombre,cat_nombre_fr), "
        //         . "  cat_referencia_en=IF(cat_referencia_en='',cat_referencia,cat_referencia_en), "
        //         . "  cat_referencia_fr=IF(cat_referencia_fr='',cat_referencia,cat_referencia_fr), "
        //         . "  cat_orden_en=IF(cat_orden_en='',cat_orden,cat_orden_en), "
        //         . "  cat_orden_fr=IF(cat_orden_fr='',cat_orden,cat_orden_fr), "
        //         . "  cat_url_producto_en=IF(cat_url_producto_en='',cat_url_producto,cat_url_producto_en), "
        //         . "  cat_url_producto_fr=IF(cat_url_producto_fr='',cat_url_producto,cat_url_producto_fr), "
        //         . "  cat_origen_en=IF(cat_origen_en='',cat_origen,cat_origen_en), "
        //         . "  cat_origen_fr=IF(cat_origen_fr='',cat_origen,cat_origen_fr), "
        //         . "  cat_raza_en=IF(cat_raza_en='',cat_raza,cat_raza_en), "
        //         . "  cat_raza_fr=IF(cat_raza_fr='',cat_raza,cat_raza_fr), "
        //         . "  cat_curado_en=IF(cat_curado_en='',cat_curado,cat_curado_en), "
        //         . "  cat_curado_fr=IF(cat_curado_fr='',cat_curado,cat_curado_fr), "
        //         . "  cat_pesos_en=IF(cat_pesos_en='',cat_pesos,cat_pesos_en), "
        //         . "  cat_pesos_fr=IF(cat_pesos_fr='',cat_pesos,cat_pesos_fr), "
        //         . "  cat_anada_en=IF(cat_anada_en='',cat_anada,cat_anada_en), "
        //         . "  cat_anada_fr=IF(cat_anada_fr='',cat_anada,cat_anada_fr), "
        //         . "  cat_formato_en=IF(cat_formato_en='',cat_formato,cat_formato_en), "
        //         . "  cat_formato_fr=IF(cat_formato_fr='',cat_formato,cat_formato_fr), "
        //         . "  cat_unidades_caja_en=IF(cat_unidades_caja_en='',cat_unidades_caja,cat_unidades_caja_en), "
        //         . "  cat_unidades_caja_fr=IF(cat_unidades_caja_fr='',cat_unidades_caja,cat_unidades_caja_fr), "
        //         . "  cat_ecologica_en=IF(cat_ecologica_en='',cat_ecologica,cat_ecologica_en), "
        //         . "  cat_ecologica_fr=IF(cat_ecologica_fr='',cat_ecologica,cat_ecologica_fr), "
        //         . "  cat_tipo_de_uva_en=IF(cat_tipo_de_uva_en='',cat_tipo_de_uva,cat_tipo_de_uva_en), "
        //         . "  cat_tipo_de_uva_fr=IF(cat_tipo_de_uva_fr='',cat_tipo_de_uva,cat_tipo_de_uva_fr), "
        //         . "  cat_volumen_en=IF(cat_volumen_en='',cat_volumen,cat_volumen_en), "
        //         . "  cat_volumen_fr=IF(cat_volumen_fr='',cat_volumen,cat_volumen_fr), "
        //         . "  cat_variedades_en=IF(cat_variedades_en='',cat_variedades,cat_variedades_en), "
        //         . "  cat_variedades_fr=IF(cat_variedades_fr='',cat_variedades,cat_variedades_fr), "
        //         . "  cat_descripcion_en=IF(cat_descripcion_en='',cat_descripcion,cat_descripcion_en), "
        //         . "  cat_descripcion_fr=IF(cat_descripcion_fr='',cat_descripcion,cat_descripcion_fr), "
        //         . "  cat_tarifa_en=IF(cat_tarifa_en='',cat_tarifa,cat_tarifa_en), "
        //         . "  cat_tarifa_fr=IF(cat_tarifa_fr='',cat_tarifa,cat_tarifa_fr), "
        //         . "  cat_unidad_en=IF(cat_unidad_en='',cat_unidad,cat_unidad_en), "
        //         . "  cat_unidad_fr=IF(cat_unidad_fr='',cat_unidad,cat_unidad_fr) "
        //         . " WHERE id='$primary_key' ";
        // $this->db->query($sql);
        
        //regulariza datos 
        $this->productos_->regularizarDatosProducto($primary_key);
        
        $codigo_embalaje=$this->productos_->isEmbalaje($primary_key);
        //log_message('INFO','PASO POR _registros_after_productos isEmbaalaje '.$codigo_embalaje);
        if($codigo_embalaje){
           //'PASO POR _registros_after_productos isEmbaalaje '.$codigo_embalaje);
           $sql="SELECT p.id as pe_id_producto, p.id_producto as codigo_bascula,id_embalajes FROM pe_lineas_embalajes le 
                 LEFT JOIN pe_embalajes e ON le.id_embalajes=e.id
                 LEFT JOIN pe_productos p ON e.codigo_producto=p.id
                 WHERE codigo_embalaje='$codigo_embalaje'
                 GROUP BY p.id_producto";
           if($this->db->query($sql)->num_rows()>0){
               $result=$this->db->query($sql)->result();
               foreach($result as $k=>$v){
                   //log_message('INFO','PASO POR _registros_after_productos id_embalajes '.$v->id_embalajes);
                   //log_message('INFO','PASO POR _registros_after_productos pe_id_producto '.$v->pe_id_producto);
                   //log_message('INFO','PASO POR _registros_after_productos codigo_bascula '.$v->codigo_bascula);
                   $embalajesTienda=$this->productos_->calculoPrecioEmbalajeTienda($v->pe_id_producto);
                   $embalajesOnline=$this->productos_->calculoPrecioEmbalajeOnline($v->pe_id_producto);
                   //log_message('INFO','PASO POR _registros_after_productos embalajesTienda '.$embalajesTienda);
                   //log_message('INFO','PASO POR _registros_after_productos embalajesOnline '.$embalajesOnline);
                   $this->productos_->ponerCostesEmbalajes($v->codigo_bascula,$embalajesTienda,$embalajesOnline);
                   
               }
           }
           
        }
        

        /*
        //verificar si se ha modificado un embalaje
        $codigo_embalaje=$this->productos_->isEmbalaje($primary_key);
        $sql="SELECT * FROM pe_lineas_embalajes WHERE codigo_embalaje='$codigo_embalaje'";
        if($codigo_embalaje  && $this->db->query($sql)->num_rows()>0){
            //actualizar precios embajales y margeners
            {
                $result=$this->db->query($sql)->result();
                foreach($result as $k=>$v){
                    $id_embalajes=$v->id_embalajes;
                    $result2=$this->db->query("SELECT * FROM pe_lineas_embalajes WHERE id_embalajes='$id_embalajes'")->result();
                    $precioEmbalajeTienda=0;
                    $precioEmbalajeOnline=0;
                    foreach($result2 as $k2=>$v2){
                        $precioCompra=$this->productos_->getPrecioCompra($v2->codigo_embalaje);
                        if($v2->tienda){
                           $precioEmbalajeTienda+=$v2->cantidad/1000*$precioCompra;
                        }
                        if($v2->online){
                           $precioEmbalajeOnline+=$v2->cantidad/1000*$precioCompra;
                        }
                    }
                    log_message('INFO', "SELECT codigo_producto FROM pe_embalajes WHERE id='$id_embalajes'");
                   $this->db->query("UPDATE pe_embalajes SET precio_embalaje_tienda='$precioEmbalajeTienda', precio_embalaje_online='$precioEmbalajeOnline' WHERE id='$id_embalajes'");
                   $codigo_producto=$this->db->query("SELECT codigo_producto FROM pe_embalajes WHERE id='$id_embalajes'")->row()->codigo_producto;    
                   
                  $margenTienda=$this->productos_->calculoMargenTienda($primary_key);
                  $margenOnline=$this->productos_->calculoMargenOnline($primary_key);
         
                  $this->db->query("UPDATE pe_embalajes SET margen_tienda='$margenTienda', margen_online='$margenOnline' WHERE id='$id_embalajes'");

                  
                }
            }
        }
        */

         
                   
        $this->productos_->grabarCambiosPrecios($post_array, $primary_key);

        $this->productos_->regularizarPrecios($primary_key);

        $this->productos_->regularizarStocks($primary_key);
        
        $this->productos_->regularizarPacks($primary_key);

        return true;
    }

    function after_insert_grupo($post_array, $primary_key) {
        $sql = "SELECT id,id_grupo FROM pe_grupos ";
        $result = $this->db->query($sql);
        foreach ($result->result() as $k => $v) {
            $id = $v->id;
            $sql = "UPDATE pe_grupos SET id_grupo='$id' WHERE id='$id'";
            $this->db->query($sql);
        }
        return true;
    }

    public function _column_left_number($value, $row) {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",") : "";
        return "<span  style='text-align:left;width:20%;display:block;'>$value</span>";
    }

    public function _column_right_number($value, $row) {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",") : "";
        // return "<span  class='text-right' style='text-align:right;width:20%;display:block;'>$value</span>";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    public function _column_right_number30($value, $row) {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",") : "";
        return "<span  class='text-right' style='text-align:right;width:30%;display:block;'>$value</span>";
    }

    public function _column_right_number50($value, $row) {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ",") : "";
        return "<span  class='text-right' style='width:50%;display:block;'>$value</span>";
    }

    function unique_field_name($field_name) {
        return 's' . substr(md5($field_name), 0, 8); //This s is because is better for a string to begin with a letter and not with a number
    }

    public function _callback_column_cantidad($value, $row) {

        $tipoUnidad = $this->productos_->getUnidad($row->id_pe_producto);
        $value /= 1000;
        if ($tipoUnidad == 'Kg')
            $value = number_format($value, 3);
        if ($value == 0)
            $value = "";
        else
            $value = $value . ' ' . $tipoUnidad;
        return "<span class='derecha' style='text-align:right;padding-right:10px;width:100%;display:block;' >$value</span>"; //$value.' '.$tipoUnidad;//"<span class='derecha' >$value</span>";
    }

    public function _callback_valoracion_stock($value, $row) {
        $id_pe_producto = $row->id_pe_producto;
        $datosProducto = $this->productos_->getDatosCompraProducto($id_pe_producto);
        $cantidad = $this->stocks_model->getCantidadTotal($id_pe_producto);
        $precio = $datosProducto['precio'] - $datosProducto['precio'] * $datosProducto['descuento'] / 100;
        $value = $precio * $cantidad;
        $this->stocks_model->putValoracion($id_pe_producto, $value);
        $value /= 1000;
        $value = number_format($value, 2);
        return "<span class='derecha' style='text-align:right;padding-right:10px;width:100%;display:block;'>" . $value . "</span>";
    }

    public function _callback_read_valoracion_stock($value, $row) {

        $value /= 1000;
        $value = number_format($value, 2);
        return "<span class='' style='text-align:left;padding-right:10px;width:100%;display:block;'>" . $value . "</span>";
    }

    public function _read_cantidad_stocks($value, $primaryKey) {

        $row = $this->db->query("SELECT id_pe_producto FROM pe_stocks WHERE id='$primaryKey'")->row();
        $tipoUnidad = $this->productos_->getUnidad($row->id_pe_producto);
        $value /= 1000;
        if ($tipoUnidad == 'Kg')
            $value = number_format($value, 3);
        if ($value == 0)
            $value = "";
        else
            $value = $value . ' ' . $tipoUnidad;

        //$value/=1000;
        //if(intval($value)!=$value) $value=  number_format ($value,3);
        return "<span class='derecha' style='text-align:left;width:100%;display:block;'>$value</span>";
    }

    public function _read_cantidad_stocks_totales($value, $primaryKey) {

        $row = $this->db->query("SELECT id_pe_producto FROM pe_stocks_totales WHERE id='$primaryKey'")->row();
        $tipoUnidad = $this->productos_->getUnidad($row->id_pe_producto);
        $value /= 1000;
        if ($tipoUnidad == 'Kg')
            $value = number_format($value, 3);
        if ($value == 0)
            $value = "";
        else
            $value = $value . ' ' . $tipoUnidad;

        //$value/=1000;
        //if(intval($value)!=$value) $value=  number_format ($value,3);
        return "<span class='derecha' style='text-align:left;width:100%;display:block;'>$value</span>";
    }

    public function _callback_column_codigo_producto($value, $row) {
        $sql = "SELECT nombre FROM pe_productos WHERE id='$value'";
        $value = $this->db->query($sql)->row()->nombre;
        return "<span class='numero'>$value</span>";
    }

    public function _callback_column_codigo_bascula($value, $row) {
        $id = $row->id_pe_producto;
        $sql = "SELECT id_producto FROM pe_productos WHERE id='$id'";
        $value = $this->db->query($sql)->row()->id_producto;
        return "<span class='numero derecha' style='text-align:right;padding-right:30px;width:100%;display:block;'>$value</span>";
    }

    public function _callback_column_proveedor($value, $row) {
        $id_pe_producto = $row->id_pe_producto;
        $id = $row->id;
        $sql = "SELECT id_proveedor_web FROM pe_productos WHERE id='$id_pe_producto'";
        $id_proveedor_web = $this->db->query($sql)->row()->id_proveedor_web;
        $sql = "UPDATE pe_stocks SET proveedor='$id_proveedor_web' WHERE id='$id'";
        $this->db->query($sql);
        return;
        return "<span class='numero' style='text-align:left;width:100%;display:block;'>$id_pe_producto</span>";
    }

    public function _callback_column_id_pe_producto($value, $row) {
        $sql = "SELECT codigo_producto FROM pe_productos WHERE id='$value'";
        $value = $this->db->query($sql)->row()->codigo_producto;
        return "<span class=''>$value</span>";
    }

    function _read_fecha_centrado($value, $row) {
        if ($value == "0000-00-00")
            return "";
        $originalDate = $value;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return "<span class=''  style='text-align:center;width:100%;display:block;'>$newDate</span>";
    }

    function _read_proveedor($value, $row) {
        $proveedor = $this->productos_->getNombreProveedorWeb($value);
        return "<span class=''  style='text-align:left;width:100%;display:block;'>$proveedor</span>";
    }

    // No se utiliza - function para eliminar 
    public function _delete_productos__($primary_key) {
        $sql = "DELETE FROM pe_stocks WHERE codigo_producto='$primary_key'";
        $this->db->query($sql);
        $sql = "DELETE FROM pe_stocks_totales WHERE codigo_producto='$primary_key'";
        $this->db->query($sql);
        return $this->db->update('pe_productos', array('status_producto' => '0', "fecha_modificacion" => date('Y-m-d')), array('id' => $primary_key));
    }

    public function _activar_productos($primary_key, $row) {
        return site_url('productos/activarProducto/') . '/' . $row->id;
    }

    public function _eliminar_producto($primary_key, $row){
        $resultado= $this->productos_->checkPosibilityToEliminate($primary_key);
        if($resultado['eliminar']){
            // mensaje("javascript:eliminarProducto('".$resultado['id']."', '".$resultado['eliminar']."', '".$resultado['texto']."')");
            return "javascript:eliminarProducto('".$resultado['id']."', '".$resultado['eliminar']."', '".$resultado['texto']."')";
        }
        else{
            return "javascript:eliminarProducto('".$resultado['id']."', '".$resultado['eliminar']."', '".$resultado['texto']."')";
        }
        
    }
    public function _desactivar_productos($primary_key, $row) {
        //echo '<script>alert("hola")</script>';
        return site_url('productos/desactivarProducto/') . '/' . $row->id;
    }

    public function _editar_productos($primary_key, $row) {
        return site_url('gestionTablasProductos/productos/edit/') . '/' . $row->id;
    }

    function field_callback_fecha($value, $primary_key) {
        $originalDate = $value;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return $newDate;
    }

    function _read_fecha($value, $row) {
        if ($value == "0000-00-00")
            return "";
        $originalDate = $value;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return $newDate;
    }

    function q_table_output($output = null, $table = "") {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);
        $dato['activeMenu'] = 'Listados';
        $dato['activeSubmenu'] = $table;
        $this->load->view('templates/top.php', $dato);
        $this->load->view('table_template.php', $output);
        $this->load->view('templates/footer.html');
    }

    function nuevoProductoRangos(){
        $datos['productos']=array();
        $result=$this->db->query("SELECT * FROM pe_productos WHERE id_producto=0 AND status_producto=1 AND tipo_unidad='Kg' ORDER BY nombre")->result();
        $datos['productosCompra'][0]="Seleccionar producto compra";
        foreach($result as $k=>$v){
            $datos['productosCompra'][$v->codigo_producto]=$v->nombre.' ('.$v->codigo_producto.')';
        }

        $result2=$this->db->query("SELECT id_producto FROM pe_productos WHERE id_grupo=8 AND status_producto=1 AND tipo_unidad='Und' GROUP BY id_producto ")->result();
        $datos['productosBodega'][0]="Seleccionar producto bodega";
        foreach($result2 as $k=>$v){
            $row=$this->db->query("SELECT codigo_producto,nombre FROM pe_productos WHERE id_producto='".$v->id_producto."' ORDER BY anada DESC LIMIT 1" )->row();
            $datos['productosBodega'][$row->codigo_producto]=$row->nombre.' ('.$row->codigo_producto.')';
        }
        asort($datos['productosBodega']);
        array_unshift($datos['productosBodega'],"Seleccionar producto bodega" );

        $this->load->view('templates/header.html');
        $this->load->view('templates/top.php');
        $this->load->view('nuevoProductoRangos.php',$datos);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalProductos');
        $this->load->view('myModalPregunta');
    }

    function _table_output_productos($output = null, $table = "") {
        
        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_productos_separada.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalProductos');
        $this->load->view('myModalPregunta');
    }
    function _table_output_productos_venta($output = null, $table = "") {
        
        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_productos_separada_venta.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalProductos');
        $this->load->view('myModalPregunta');
    }

    function _table_output_embalajes_productos($output = null, $table = "") {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_embalajes_productos.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }
    
    function _table_output_packs($output = null, $table = "") {

        // $this->load->view('templates/header.php', $output);
        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_packs.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }

    function _table_output_productos_descatalogados($output = null, $table = "") {

        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');
        $this->load->view('table_template_productos_descatalogados_separada.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
        $this->load->view('myModalPregunta');
    }

    function _column_right_align($value, $row) {
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _read_cantidad_stock_minimo($value, $row) {
        $value /= 1000;
        $unidad = " Unidades o Kg";
        $decimales = 0;

        $value = $value != 0 ? number_format($value, $decimales) . $unidad : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value </span>";
    }

    function _read_stock_minimo($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0) : "--";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;' id='read_stock_minimo' >$value</span>";
    }

    function _read_unidades_precio($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0) : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;' id='read_unidades_precio'>$value";
    }

    function _read_unidades_caja($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0) : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;' id='read_unidades_caja'>$value</span>";
    }

    function _read_cat_unidades_caja($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0) : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value <span id='read_unidades_caja' ></span></span>";
    }

    function _read_cantidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " €" : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _read_cantidad_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) . " €/Kg" : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_precio_ultimo_unidad($value, $primary_key) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) . " €/unidad" : "";
        return "<span id='read_precio_ultimo_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_codigo_ean($value, $primary_key) {

        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>" . ' <img id="img_producto_read" src="" alt="No existe imagen" >';
    }

    function _read_precio_ultimo_peso($value, $primary_key) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) . " €/Kg" : "";
        return "<span id='read_precio_ultimo_peso' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_cantidad_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) . " €/und." : "";

        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_cantidad_porcentaje($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_margen_real_producto($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span id='read_margen_real_producto' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_id_producto($value, $row) {
        return "<span class='derecha' style='width:100%;text-align:left;display:block;padding-left:5px;'>$value</span>";
    }

    function _read_nombre($value, $row) {
        return "<span class='derecha' style='width:100%;text-align:left;display:block;padding-left:5px;'>$value</span>";
    }

    function _read_iva($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        $value = 88888; //$row->id_grupo;
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _read_descuento_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span  class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_descuento_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span  class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_margen_venta_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span  class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_margen_venta_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span  class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_tarifa_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " €/unidad" : "";
        return "<span id='read_tarifa_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_tarifa_venta($value, $primary_key) {
        $tipo_unidad = $this->productos_->getUnidad($primary_key);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . ' €/ ' . $tipo_unidad : "";
        return "<span id='read_tarifa_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_tarifa_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span id='read_tarifa_profesionales' class='izquierda' style='width:100%;text-align:left;display:block;'>$value<span class='euro_tipo'></span></span>";
    }

    function _read_tarifa_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span id='read_tarifa_profesionales_vip' class='izquierda' style='width:100%;text-align:left;display:block;'>$value<span class='euro_tipo'></span></span>";
    }

    function _read_precio_compra($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span id='read_precio_compra' class='izquierda' style='width:100%;text-align:left;display:block;'>$value<span class='euro_tipo'></span></span>";
    }

    function _read_precio_transformacion($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span id='read_tarifa_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value<span class='euro_tipo'></span></span>";
    }

    function _read_tipo_unidad_mostrar($value, $primary_key) {
        $value = $this->productos_->getUnidad($primary_key);
        return "<span id='read_tipo_unidad_mostrar'  style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_tarifa_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) . " €/Kg" : "";
        return "<span id='read_tarifa_peso' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_precio_transformacion_unidad($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) . " €/unidad" : "";
        return "<span id='read_precio_transformacion_unidad' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_precio_transformacion_peso($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) . " €/Kg" : "";
        return "<span id='read_precio_transformacion_peso' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_cat_tarifa($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span id='read_cat_tarifa' class='izquierda' style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _read_porcentaje($value, $row) {
        $value=floatval($value);
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2) . " %" : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_peso($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_peso_excel($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3,",","") : "";
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_id_producto($value, $row) {
        if ($this->session->categoria < 2 || $this->session->categoria == 4 || $this->session->categoria == 5) {
            return "<a   class='derecha codigo_bascula' style='text-align:right;width:100%;display:block;'>$value</a>";
        } else {
            return "<span   class='derecha codigo_bascula' style='text-align:right;width:100%;display:block;'>$value</span>";
        }
    }

    function _column_left_align_peso($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span class='izquierda' style='text-align:left;width:100%;display:block;'>$value</span>";
    }

    function _column_right_align_nombre($value, $row) {
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_right_align_NumPedido($value, $row) {

        return "<span class='derecha' style='text-align:right;width:100%;display:block;'>$value</span>";
    }

    function _column_right_align_precio($value, $row) {
        $value=floatval($value);
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_url_web($value, $row) {
        return "<span class='derecha' style='text-align:right;width:100%;display:block;'><a target='_blank' href='$value'>$value</a></span>";
    }

    function _column_url_web_grid($value, $row) {
        $w = "W";
        if ($value == "")
            $w = "";
        return "<span class='derecha' style='text-align:right;width:20px;display:block;'><a target='_blank' href='$value'>$w</a></span>";
    }

    function _column_right_align_precio_pvp($value, $row) {
        $value=floatval($value);
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='text-align:right;width:20%;display:block;'>$value</span>";
    }

    function _column_right_align_precio_compra($value, $row) {
        $value=floatval($value);
        $value /= 1000000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='text-align:right;width:20%;display:block;'>$value</span>";
    }

    function _column_codigo_producto($value, $row) {

        return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _column_codigo_bascula($value, $row) {

        return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _column_nombre($value, $row) {
        if (strlen($value) > 70)
            $value = substr($value, 0, 70);
        return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
    }

    function _column_codigos__($value, $row) {
        $codigo_producto = $row->codigo_producto;
        $codigo_bascula = $row->id_producto;
        if ($this->session->categoria < 2 || $this->session->categoria == 4 || $this->session->categoria == 5) {
            $htmlCodigo_bascula = "<a   class='izquierda codigo_bascula' style='text-align:left;width:100%;display:block;'>$codigo_bascula</a>";
        } else {
            $htmlCodigo_bascula = "<span   class='izquierda codigo_bascula' style='text-align:left;width:100%;display:block;'>$codigo_bascula</span>";
        }

        return $htmlCodigo_bascula
                . "<span  style='width:100%;text-align:left;display:block;'>$codigo_producto</span>";
    }

    function _column_tipos_unidades($value, $row) {
        $tipo_unidad = $this->productos_->getUnidad($row->id);
        return "<span  style='width:100%;text-align:right;display:block;'>$row->tipo_unidad</span>";
    }

    function _column_precio_transformacion($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return "<span class='derecha compra' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_precio_compra($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return $value;
        return "<span class='derecha compra' style='text-align:right;display:block;'>$value</span>";
    }
    function _column_precio_compra_excel($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ",", "") : "";
        return $value;
        return "<span class='derecha compra' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_tarifa_venta($value, $row) {
        $value=floatval($value);
        if ($row->tarifa_venta_unidad && !$row->tarifa_venta_peso)
            $value = $row->tarifa_venta_unidad;
        if (!$row->tarifa_venta_unidad && $row->tarifa_venta_peso)
            $value = $row->tarifa_venta_peso;
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return "<span class='derecha tienda' style='text-align:right;display:block;'>$value</span>";
    }
    function _column_tarifa_venta_excel($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ",", "") : "";
        return "<span class='derecha tienda' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_stock_total($value,$row){    
        $value= $this->stocks_model->getCantidad($row->id)/1000;
        return $value;
    }

    function _column_valoracion($value,$row){
        // mensaje('stock_total '.$row->stock_total);
        // mensaje('precio_compra '.$row->precio_compra);
        $value=floatval($row->stock_total)*floatval($row->precio_compra);
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return $value;
    }
    function _column_valoracion_excel($value,$row){
        // mensaje('stock_total '.$row->stock_total);
        // mensaje('precio_compra '.$row->precio_compra);
        $value=floatval($row->stock_total)*floatval($row->precio_compra)/1000;
        $value = $value != 0 ? number_format($value, 2, ",", "") : "";
        return $value;
    }


    function _column_tarifa_profesionales($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return "<span class='derecha profesionales' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_tarifa_profesionales_vip($value, $row) {
        $value=floatval($value);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return "<span class='derecha profesionales_vip' style='text-align:right;display:block;'>$value</span>";
    }

    function _column_right_align_fecha($value, $row) {
        $value = substr($value, 8, 2) . '/' . substr($value, 5, 2) . '/' . substr($value, 0, 4);
        return "<span  style='text-align:left;display:block;'>$value</span>";
    }

    function _column_center_align($value, $row) {
        $value = substr($value, 8, 2) . '/' . substr($value, 5, 2) . '/' . substr($value, 0, 4);
        if ($value == '//')
            $value = "";
        if ($value == '00/00/0000')
            $value = "";
        return "<span  style='text-align:center;display:block;'>$value</span>";
    }

    function _column_right_align_tipo_precio($value, $row) {
        if ($value)
            $valueTexto = "€/und";
        else
            $valueTexto = "€/Kg";
        return "<span  style='width:30px;text-align:left;display:block;'>$valueTexto</span>";
    }

    function _column_right_align_tipo_precio_view($value, $row) {


        if (substr($row, 0, 2) == "EM")
            $valueTexto = "";
        else {
            if ($value)
                $valueTexto = "Precio por Unidad";
            else
                $valueTexto = "Precio por Kg";
        }
        return "<span  style='width:100px;text-align:left;display:block;'>$valueTexto</span>";
    }

    function _column_left_align_nombre2__($value, $row) {

        return "<span  style='width:400px;text-align:left;display:block;'>$value</span>";
    }

    function _column_left_align_proveedor_web($value, $row) {

        return "<span  style='width:200px;text-align:left;display:block;'>$value</span>";
    }

    function _column_id_pedido($value, $row) {
        $id_pedido = $row->id_pedido;
        $sql = "SELECT nombreArchivoPedido FROM pe_pedidos_proveedores WHERE id='$id_pedido'";
        $nombreArchivoPedido = $this->db->query($sql)->row_array()['nombreArchivoPedido'];
        $base_url = base_url();
        return "<a href='$base_url/pedidos/$nombreArchivoPedido' target='_blank'>$nombreArchivoPedido</a>";
    }

    function _column_cat_marca_text($value, $row) {
        $id = $row->cat_marca;

        $sql = "SELECT marca FROM pe_marcas WHERE id='$id'";
        $marca = 'Sin marca';
        if ($this->db->query($sql)->num_rows() != 0) {
            $marca = $this->db->query($sql)->row()->marca;
        }
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$marca</span>";
    }

    function _column_right_align_activa($value, $row) {
        if ($value == 1)
            $value = "Activa";
        else
            $value = "NO activa";

        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_align_codigo_web($value, $row) {
        return "<span class='derecha' style='width:10%;text-align:right;display:block;'>$value&nbsp;&nbsp;&nbsp;</span> ";
    }

    function _column_right_align_porcentaje($value, $row) {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_precio_ultimo_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_precio_ultimo_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_descuento_1_compra($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha compra' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_descuento_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha profesionales' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_descuento_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha profesionales_vip' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_porcentaje($value, $row) {
        //$value/=100;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_porcentaje_iva($value, $row) {
        //$value/=100;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:20%;text-align:right;display:block;'>$value</span>";
    }

    function _column_right_tarifa_venta($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _getIva($value, $row) {
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
        if ($unidad)
            $precio = $unidad;
        else
            $precio = $kg;

        $precio = $precio - $precio * $dto / 10000;

        $bruto_unidad = $query->row()->bruto_unidad;
        $bruto_peso = $query->row()->bruto_peso;

        if ($unidad)
            $bruto = $bruto_unidad;
        else
            $bruto = $bruto_peso;
        $bruto *= 1000;
        $tarifaNeta = $bruto / (100 + $iva) * 1000;


        if ($precio != 0)
            $value = ($tarifaNeta - $precio) / $precio * 100;
        else
            $value = 0;


        return $iva;
    }

    function _margen_real_producto($value, $row) {

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
        if ($unidad)
            $precio = $unidad;
        else
            $precio = $kg;

        $precio = $precio - $precio * $dto / 100000;

        $bruto_unidad = $query->row()->bruto_unidad;
        $bruto_peso = $query->row()->bruto_peso;

        if ($unidad)
            $pvp = $bruto_unidad;
        else
            $pvp = $bruto_peso;
        log_message('INFO', $pvp.' '.$precio.' '.$iva);
        if ($pvp)
            $margen_real_producto = (100 * $pvp - $precio * (100 + $iva)) / $pvp;
        else
            $margen_real_producto = "---";


        return $margen_real_producto;
    }

    function _column_beneficio_recomendado($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        if ($value < $row->beneficio_recomendado / 1000)
            return "<span class='derecha' style='width:100%;text-align:right;display:block;color:red'>$value</span>";
        else
            return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _column_right_margen_real_producto($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        if (floatval($value) < floatval($row->beneficio_recomendado) / 1000)
            return "<span class='derecha' style='width:100%;text-align:right;display:block;color:red'>$value</span>";
        else
            return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }
    function _column_right_margen_real_producto_excel($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2,",","") : "";
        if (floatval($value) < floatval($row->beneficio_recomendado) / 1000)
            return "<span class='derecha' style='width:100%;text-align:right;display:block;color:red'>$value</span>";
        else
            return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }


    function _column_margen_venta_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _column_margen_venta_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _column_precio_embalaje_tienda($value, $row) {
        //$coste=$this->coste_embalaje_tienda($row->id);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _column_precio_pack($value, $row) {
        //$coste=$this->coste_embalaje_tienda($row->id);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    
    function _column_precio_embalaje_online($value, $row) {
        //$coste=$this->coste_embalaje_online($row->id);
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _column_margen_tienda($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _column_margen_online($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$value</span>";
    }

    function _field_right_align_valor_iva2($value, $row) {
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>Hola</span>";
    }

    function _column_right_iva($value, $row) {
        $sql = "SELECT i.valor_iva as iva"
                . " FROM pe_productos p "
                . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
                . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
                . " WHERE codigo_producto='$row->codigo_producto'";
        $query = $this->db->query($sql);
        $value = $query->row()->iva;
        $value = $value != 0 ? number_format($value, 2) : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function _field_add_codigo_producto($value, $prymary_key) {
        return '<input placeholder="Código producto (13)" id="field-codigo_producto" type="text" maxlength="20" value="' . $value . '" name="codigo_producto" class="numeric">';
    }

    function _field_edit_codigo_producto($value, $primary_key) {
        $base_url = base_url();
        return "<span>$value</span>" . '<input   id="field-codigo_producto" type="hidden"  maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100%;">'
                . '<input id="base_url" type="hidden" value="' . $base_url . '">';
    }

    function _field_nombre($value, $primary_key) {
        return '<input id="field-nombre" placeholder="Nombre producto"  type="text"  value="' . $value . '" name="nombre" >';
    }
    function _field_nombre_generico($value, $primary_key) {
        return '<input id="field-nombre_generico" placeholder="Nombre genérico del producto (aparce en tickets y facturas cliente)"  type="text"  value="' . $value . '" name="nombre_generico" >';
    }

    function _field_anada($value, $primary_key) {
        return '<input id="field-anada" placeholder="Añada"  type="text"  class="numeric" value="' . $value . '" name="anada" >';
    }

    function _field_id_producto($value, $prymary_key) {
        return '<input placeholder="Cód. báscula" id="field-id_producto" type="text" maxlength="20" value="' . $value . '" name="id_producto" class="numeric">';
    }

    function _field_unidades_caja($value, $prymary_key) {
        $value=floatval($value);
        $value /= 1000;
        return '<input placeholder="Und./Caja" id="field-unidades_caja" type="text" maxlength="20" value="' . $value . '" name="unidades_caja" class="numeric">&nbsp; Unidades/Caja Compra';
    }

    function _field_cat_unidades_caja($value, $prymary_key) {
        $value=floatval($value);
        $value /= 1000;
        return '<input placeholder="Und./Caja" id="field-cat_unidades_caja" type="text" maxlength="20" value="' . $value . '" name="cat_unidades_caja" class="numeric">&nbsp; Unidades/Caja Compra';
    }

    function _field_cat_unidades_caja_en($value, $prymary_key) {
        $value=floatval($value);
        $value /= 1000;
        return '<input placeholder="Und./Caja" id="field-cat_unidades_caja_en" type="text" maxlength="20" value="' . $value . '" name="cat_unidades_caja" class="numeric">&nbsp; Unidades/Caja Compra';
    }

    function _field_cat_unidades_caja_fr($value, $prymary_key) {
        $value=floatval($value);
        $value /= 1000;
        return '<input placeholder="Und./Caja" id="field-cat_unidades_caja_fr" type="text" maxlength="20" value="' . $value . '" name="cat_unidades_caja" class="numeric">&nbsp; Unidades/Caja Compra';
    }

    function _field_unidades_precio($value, $prymary_key) {
        $tipoUnidad = $this->productos_->getUnidad($prymary_key);
        $texto = "";

        if ($tipoUnidad == 'Kg') {
            $texto = "Kg/Precio Compra";
        }
        if ($tipoUnidad == "Und")
            $texto = "Unidades/Precio Compra";

        $value /= 1000;
        if ($tipoUnidad == "Kg")
            $value = $value != 0 ? number_format($value, 3, ".", "") . '' : "";
        if ($tipoUnidad = "Und")
            $value = $value != 0 ? number_format($value, 0, ".", "") . '' : "";

        return '<input placeholder="' . $tipoUnidad . '"./Precio" id="field-unidades_precio" type="text" maxlength="20" value="' . $value . '" name="unidades_precio" class="numeric">&nbsp; ' . $texto;
    }

    function _field_codigo_ean($value, $prymary_key) {
        return '<input placeholder="Código EAN" id="field-codigo_ean" type="text" maxlength="25" value="' . $value . '" name="codigo_ean" class="numeric">';
    }

    function _field_peso_real($value, $prymary_key) {

        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return '<input placeholder="Peso (Kg)" id="field-peso_real" type="text"  value="' . $value . '" name="peso_real" class="numeric">&nbsp; Kg';
    }

    function _field_stock_minimo($value, $prymary_key) {
        $tipoUnidad = $this->productos_->getUnidad($prymary_key);
        $value /= 1000;
        if ($tipoUnidad == 'Kg')
            $value = $value != 0 ? number_format($value, 3) : "";
        if ($tipoUnidad == "Und")
            $tipoUnidad = "Unidades";
        return '<input placeholder="Stock mínimo" id="field-stock_minimo" type="text" maxlength="20" value="' . $value . '" name="stock_minimo" class="numeric">&nbsp; ' . $tipoUnidad;
    }

    function _field_precio_ultimo_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input placeholder="Precio €/Kg" id="field-precio_ultimo_peso" type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_peso" style="width:100px">&nbsp; €/kg.';
    }

    function _field_tarifa_venta_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="PVP €/Kg" id="field-tarifa_venta_peso" type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso" style="width:100px">&nbsp; €/kg <span class="anterior hidden">&nbsp;&nbsp;(Anterior: ' . $value . '&nbsp; €/Kg)</span>';
    }

    function _field_precio_ultimo_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input placeholder="Precio €/Und" id="field-precio_ultimo_unidad" type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_unidad" >&nbsp;  €/Unidad';
    }

    function _field_precio_transformacion_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input placeholder="Precio €/Und" id="field-precio_transformacion_unidad" type="text" maxlength="20" value="' . $value . '" name="precio_transformacion_unidad" style="width:100px">&nbsp; €/Unidad';
    }

    function _field_precio_transformacion_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input placeholder="Precio €/Kg" id="field-precio_transformacion_peso" type="text" maxlength="20" value="' . $value . '" name="precio_transformacion_peso" style="width:100px">&nbsp; €/Kg';
    }

    function _field_tarifa_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input  id="field-tarifa_profesionales" type="text" maxlength="20" value="' . $value . '" name="tarifa_profesionales" style="width:100px" disabled>&nbsp; €/Unidad';
    }

    function _field_tipo_unidad($value, $row) {

        return '<input  id="field-tipo_unidad" type="text" maxlength="20" value="' . $value . '" name="tipo_unidad" style="width:100px" disabled>';
    }

    function _field_tipo_unidad_mostrar($value, $primary_key) {
        $value = $this->productos_->getUnidad($primary_key);
        return '<input  id="field-tipo_unidad_mostrar" type="text" maxlength="20" value="' . $value . '" name="tipo_unidad_mostrar" style="text-align:right;width:100px" disabled>';
    }

    function _field_tarifa_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input  id="field-tarifa_profesionales_vip" type="text" maxlength="20" value="' . $value . '" name="tarifa_profesionales_vip" style="width:100px" disabled>&nbsp; €/Unidad';
    }

    function _field_precio_compra($value, $primary_key) {
        $tipoUnidad = "Kg";
        $tipo = $this->productos_->getUnidad($primary_key);
        if ($tipo == "Und")
            $tipoUnidad = "Unidad";

        $tipoUnidad = " €/" . $tipoUnidad;
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input id="field-precio_compra" type="text" maxlength="20" value="' . $value . '" name="precio_compra" style="padding:5px;color:blue;border:1px solid blue;" disabled>&nbsp;<span style="color:blue" >&nbsp;' . $tipoUnidad . '</span>';
    }

    function _field_tarifa_venta_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="PVP €/Und" id="field-tarifa_venta_unidad" type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad" style="padding:5px;color:red;border:1px solid red">&nbsp;<span style="color:red"> €/Unidad</span>'
                . ' <span class="anterior hidden">&nbsp;&nbsp;(Anterior: ' . $value . '&nbsp; €/Unidad)</span>';
    }

    function _field_cat_tarifa($value, $primary_key) {
        //var_dump($value);
        //var_dump($primary_key);
        $tipo_unidad = $this->productos_->getUnidad($primary_key);
        $tarifa_profesionales = $this->productos_->getTarifaProfesionales($primary_key);
        $tarifa_profesionales = number_format($tarifa_profesionales / 1000, 2, ".", "");
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="PVP Catálogo" id="field-cat_tarifa" type="text" maxlength="20" value="' . $value . '" name="cat_tarifa" style="width:100px">&nbsp; €/' . $tipo_unidad
                . ' <span class="anterior ">&nbsp;&nbsp;(Calculada: <span id="tarifa_profesionales">' . $tarifa_profesionales . '</span>&nbsp; €/' . $tipo_unidad . ')</span>';
    }

    function _field_cat_tarifa_en($value, $primary_key) {
        //var_dump($value);
        //var_dump($primary_key);
        $tipo_unidad = $this->productos_->getUnidad($primary_key);
        $tarifa_profesionales = $this->productos_->getTarifaProfesionales($primary_key);
        $tarifa_profesionales = number_format($tarifa_profesionales / 1000, 2, ".", "");
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="PVP Catálogo" id="field-cat_tarifa_en" type="text" maxlength="20" value="' . $value . '" name="cat_tarifa" style="width:100px">&nbsp; €/' . $tipo_unidad
                . ' <span class="anterior ">&nbsp;&nbsp;(Calculada: <span id="tarifa_profesionales">' . $tarifa_profesionales . '</span>&nbsp; €/' . $tipo_unidad . ')</span>';
    }

    function _field_cat_tarifa_fr($value, $primary_key) {
        //var_dump($value);
        //var_dump($primary_key);
        $tipo_unidad = $this->productos_->getUnidad($primary_key);
        $tarifa_profesionales = $this->productos_->getTarifaProfesionales($primary_key);
        $tarifa_profesionales = number_format($tarifa_profesionales / 1000, 2, ".", "");
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="PVP Catálogo" id="field-cat_tarifa_fr" type="text" maxlength="20" value="' . $value . '" name="cat_tarifa" style="width:100px">&nbsp; €/' . $tipo_unidad
                . ' <span class="anterior ">&nbsp;&nbsp;(Calculada: <span id="tarifa_profesionales">' . $tarifa_profesionales . '</span>&nbsp; €/' . $tipo_unidad . ')</span>';
    }

    function _field_cat_orden($value, $primary_key) {
        return '<input placeholder="Núm orden" id="field-cat_orden" type="text" maxlength="20" value="' . $value . '" name="cat_orden" >';
    }

    function _field_cat_orden_en($value, $primary_key) {
        return '<input placeholder="Núm orden" id="field-cat_orden_en" type="text" maxlength="20" value="' . $value . '" name="cat_orden_en" >';
    }

    function _field_cat_orden_fr($value, $primary_key) {
        return '<input placeholder="Núm orden" id="field-cat_orden_fr" type="text" maxlength="20" value="' . $value . '" name="cat_orden_fr" >';
    }

    function _field_descuento_1_compra($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="Dto compra %" id="field-descuento_1_compra" type="text" maxlength="20" value="' . $value . '" name="descuento_1_compra" >&nbsp;<span > %</span>';
    }

    function _field_beneficio_recomendado($value, $row) {
        $oculto = '';
        //if($this->session->categoria == 4 ) $oculto='class="hidden"';
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<span ' . $oculto . '><input placeholder="% Beneficio" id="field-beneficio_recomendado" type="text" maxlength="20" value="' . $value . '" name="beneficio_recomendado" style="width:100px">&nbsp; %</span>';
    }

    function _field_margen_real_producto($value, $row) {
        $oculto = '';
        //if($this->session->categoria == 4 ) $oculto='class="hidden"';
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<span ' . $oculto . '><input placeholder="% Margen real producto" id="field-margen_real_producto" type="text" maxlength="20" value="' . $value . '" name="margen_real_producto" style="padding:5px;color:green;border:1px solid green">&nbsp;<span style="color:green"> %</span>'
                . ' <span class="anterior hidden">&nbsp;&nbsp;(Anterior: ' . $value . '&nbsp; %)</span></span>';
    }

    function _field_descuento_profesionales($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="% Descuento ventas a profesionales" id="field-descuento_profesionales" type="text" maxlength="20" value="' . $value . '" name="descuento_profesionales" style="width:100px">&nbsp; %';
    }

    function _field_descuento_profesionales_vip($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input placeholder="% Descuento máximo vetas a profesionales" id="field-descuento_profesionales_vip" type="text" maxlength="20" value="' . $value . '" name="descuento_profesionales_vip" style="width:100px">&nbsp; %';
    }

    function _field_url_producto($value, $primary_key) {
     
        return '<input id="field-url_producto" placeholder="Url producto Tienda Online"  type="text"  value="' . $value . '" name="url_producto" >&nbsp; <a id="ver_url_producto" href="" target="_blank">Ver</a>';
    }

    function _field_url_imagen_portada($value, $primary_key) {

        return '<input id="field-url_imagen_portada" placeholder="Url imagen portada Tienda Online"  type="text"  value="' . $value . '" name="url_imagen_portada" >&nbsp; <a id="ver_url_imagen_portada" href="" target="_blank">Ver </a>';
    }

    function _field_notas($value, $primary_key) {
        return '<input id="field-notas" placeholder="Observaciones"  type="text"  value="' . $value . '" name="notas" >';
    }

    function _field_right_align_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input id="field-peso_real" type="text" maxlength="20" value="' . $value . '" name="peso_real" >&nbsp; Kg';
    }

    function _field_right_align_id_producto($value, $primary_key) {
        return '<input id="field-id_producto" type="text" maxlength="20" value="' . $value . '" name="id_producto" style="width:100px">';
    }

    function _field_right_align_codigo_ean($value, $primary_key) {
        return '<input id="field-codigo_ean" type="text" maxlength="20" value="' . $value . '" name="codigo_ean" style="width:100px">';
    }

    function _field_right_align_anada($value, $row) {
        return '<input id="field-anada" type="text" maxlength="20" value="' . $value . '" name="anada" style="width:100px">';
    }

    function _field_fecha_alta_hoy($value, $row) {
        $value = date("Y-m-d");
        return '<input id="field-fecha_alta" name="fecha_alta" type="text" value="' . $value . '" maxlength="10" class="datepicker-input hasDatepicker">';
    }

    function _field_right_align_stock_minimo($value, $row) {
        $value /= 1000;
        return '<input type="text" maxlength="20" value="' . $value . '" name="stock_minimo" style="width:100px"> Unidades o Kg';
    }

    function _field_right_align_unidades_caja($value, $row) {
        $value /= 1000;
        return '<input type="text" maxlength="20" value="' . $value . '" name="unidades_caja" style="width:100px"> Unidades';
    }

    function _field_right_align_iva($value, $row) {
        $value /= 1000;
        return '<input type="text" maxlength="20" value="' . $value . '" name="iva" style="width:100px"> %';
    }

    function _field_right_align_peso_conversion($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="peso" style="width:100px"> Kg';
    }

    function _field_left_align_codigo_producto_inicio($value, $row) {
        return '<input type="text" maxlength="20" value="' . $value . '" name="codigo_producto_inicio" style="">';
    }

    function _field_left_align_codigo_producto_final($value, $row) {
        return '<input type="text" maxlength="20" value="' . $value . '" name="codigo_producto_final" style="">';
    }

    function _field_right_align_valor_iva($value, $row) {
        $value /= 100;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="valor_iva" style="width:100px"> %';
    }

    function _field_right_align_precio_ultimo_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_peso" style="width:100px"> €/kg';
    }

    function _field_right_align_precio_ultimo_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_ultimo_unidad" style="width:100px"> €/unidad';
    }

    function _field_right_align_precio_peso_2($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_peso_2" style="width:100px"> €/kg';
    }

    function _field_right_align_beneficio_recomendado($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="beneficio_recomendado" style="width:100px"> %';
    }

    function _field_right_align_unidades_stock($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 0, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="unidades_stock" style="width:100px"> unidades';
    }

    function _field_right_align_precio_peso_3($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_peso_3" style="width:100px"> €/kg';
    }

    function _field_right_align_precio_unidad_2($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_unidad_2" style="width:100px"> €/unidad';
    }

    function _field_right_align_precio_unidad_3($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="precio_unidad_3" style="width:100px"> €/unidad';
    }

    function _field_right_align_tarifa_venta_unidad($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_unidad1($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad1" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_unidad2($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad2" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_unidad3($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_unidad3" style="width:100px"> €';
    }

    function _field_right_align_descuento_1_compra($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="descuento_1_compra" style="width:100px"> %';
    }

    function _field_right_align_tarifa_venta_peso($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input id="field-tarifa_venta_peso" type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_peso1($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso1" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_peso2($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso2" style="width:100px"> €';
    }

    function _field_right_align_tarifa_venta_peso3($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 2, ".", "") : "";
        return '<input type="text" maxlength="20" value="' . $value . '" name="tarifa_venta_peso3" style="width:100px"> €';
    }

    function _field_right_align_codigo_producto($value, $row) {
        return '<input disabled type="text" maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100px;background-color:yellow;">';
    }

    function _field_right_align_nombre($value, $row) {
        return '<input disabled type="text" maxlength="20" value="' . $value . '" name="nombre" style="width:500px;background-color:yellow;">';
    }

    function _field_right_align_id_grupo($value, $row) {
        return '<input disabled type="text" maxlength="20" value="' . $value . '" name="id_grupo" style="width:500px;background-color:yellow;">';
    }

    function _field_right_align_peso_real($value, $row) {
        return '<input id="field-peso_real" disabled type="text" maxlength="20" value="' . $value . '" name="peso_real" style="width:100px;background-color:yellow;"> Kg';
    }

    function _edit_codigo_producto($value, $row) {
        $base_url = base_url();
        return "<span>$value</span>" . '<input   id="field-codigo_producto" type="hidden"  maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100%;">'
                . '<input id="base_url" type="hidden" value="' . $base_url . '">';
    }

    function _edit_id_producto($value, $row) {
        $base_url = base_url();
        return "<span>$value</span>" . '<input   id="field-id_producto" type="hidden"  maxlength="20" value="' . $value . '" name="id_producto" style="width:100%;">';
    }

    function _add_codigo_producto($value, $row) {
        $base_url = base_url();
        return '<input   id="field-codigo_producto" type="text" onblur="codigo13()" maxlength="20" value="' . $value . '" name="codigo_producto" style="width:100%;">'
                . '<input id="base_url" type="hidden" value="' . $base_url . '">';
    }

    function _add_id_producto($value, $row) {
        $base_url = base_url();
        return '<input   id="field-id_producto" type="text"  maxlength="20" value="' . $value . '" name="id_producto" style="width:100%;">';
    }

    function _field_right_align_tipo_precio($value, $row) {
        if ($value)
            $valueTexto = 'Precio por Unidad';
        else
            $valueTexto = 'Precio por Kg';
        return '<input  type="hidden"  value="' . $value . '" name="tipo_precio" > <input disabled type="text" maxlength="20" value="' . $valueTexto . '" name="tipo_precio" style="width:120px;background-color:yellow;"> ';
    }

    function _field_right_align_url_web1($value, $row) {
        return '<input  type="text"  value="' . $value . '" name="url_web1" style="width:700px;">';
    }

    function _field_right_align_url_web2($value, $row) {
        return '<input  type="text"  value="' . $value . '" name="url_web2" style="width:700px;">';
    }

    function _field_right_align_url_web3($value, $row) {
        return '<input  type="text"  value="' . $value . '" name="url_web3" style="width:700px;">';
    }

    function _calculos_formateos_db_peso_real($post_array) {
        $peso_real = $post_array['peso_real'];
        $peso_real = str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = floatval($peso_real) * 1000;
        return $post_array;
    }

    function _calculos_formateos_db_2($post_array) {
        $peso_real = $post_array['peso_real'];
        $peso_real = str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = floatval($peso_real) * 1000;

        $tarifa_venta_unidad = $post_array['tarifa_venta_unidad'];
        $tarifa_venta_unidad = str_replace(",", ".", $tarifa_venta_unidad);
        $post_array['tarifa_venta_unidad'] = floatval($tarifa_venta_unidad) * 1000;

        $tarifa_venta_peso = $post_array['tarifa_venta_peso'];
        $tarifa_venta_peso = str_replace(",", ".", $tarifa_venta_peso);
        $post_array['tarifa_venta_peso'] = floatval($tarifa_venta_peso) * 1000;

        $unidades_caja = $post_array['unidades_caja'];
        $unidades_caja = str_replace(",", ".", $unidades_caja);
        $post_array['unidades_caja'] = floatval($unidades_caja) * 1000;

        return $post_array;
    }

    function _calculos_formateos_db_3($post_array) {
        $peso_real = $post_array['peso_real'];
        $peso_real = str_replace(",", ".", $peso_real);
        $post_array['peso_real'] = floatval($peso_real) * 1000;



        return $post_array;
    }

    function _calculos_formateos_db_tarifa_venta_peso($post_array) {
        $tarifa_venta_peso = $post_array['tarifa_venta_peso'];
        $tarifa_venta_peso = str_replace(",", ".", $tarifa_venta_peso);
        $post_array['tarifa_venta_peso'] = floatval($tarifa_venta_peso) * 100;
        return $post_array;
    }

    function _calculos_formateos_db($post_array) {
        //var_dump($post_array['precio_compra']);
        if (isset($post_array['nombre'])) {
            $nombre = $post_array['nombre'];
            $post_array['nombre'] = trim($nombre);
        }

        if (isset($post_array['nombre_generico'])) {
            $nombre_generico = $post_array['nombre_generico'];
            $post_array['nombre_generico'] = trim($nombre_generico);
        }

        if (isset($post_array['peso_real'])) {
            $peso_real = $post_array['peso_real'];
            $peso_real = str_replace(",", ".", $peso_real);
            $post_array['peso_real'] = floatval($peso_real) * 1000;
        }

        if (isset($post_array['cat_unidades_caja'])) {
            $unidades_caja = $post_array['cat_unidades_caja'];
            $unidades_caja = str_replace(",", ".", $unidades_caja);
            $post_array['cat_unidades_caja'] = $unidades_caja * 1000;
        }

        if (isset($post_array['beneficio_recomendado'])) {
            $beneficio_recomendado = $post_array['beneficio_recomendado'];
            $beneficio_recomendado = str_replace(",", ".", $beneficio_recomendado);
            $post_array['beneficio_recomendado'] = $beneficio_recomendado * 1000;
        }

        if (isset($post_array['descuento_profesionales'])) {
            $descuento_profesionales = $post_array['descuento_profesionales'];
            $descuento_profesionales = str_replace(",", ".", $descuento_profesionales);
            $post_array['descuento_profesionales'] = floatval($descuento_profesionales) * 1000;
        }

        if (isset($post_array['descuento_profesionales_vip'])) {
            $descuento_profesionales_vip = $post_array['descuento_profesionales_vip'];
            $descuento_profesionales_vip = str_replace(",", ".", $descuento_profesionales_vip);
            $post_array['descuento_profesionales_vip'] = floatval($descuento_profesionales_vip) * 1000;
        }

        /* nunca se pasa por estar enabled
        if (isset($post_array['precio_compra'])) {
            $precio_compra = $post_array['precio_compra'];
            $precio_compra = str_replace(",", ".", $precio_compra);
            $post_array['precio_compra'] = $precio_compra * 1000;
        }
        */
        
        if (isset($post_array['precio_ultimo_peso'])) {
            $precio_ultimo_peso = $post_array['precio_ultimo_peso'];
            $precio_ultimo_peso = str_replace(",", ".", $precio_ultimo_peso);
            $post_array['precio_ultimo_peso'] = floatval($precio_ultimo_peso) * 1000;
            $post_array['tipo_unidad'] = "Kg";
        }

        if (isset($post_array['precio_ultimo_unidad'])) {
            $precio_ultimo_unidad = $post_array['precio_ultimo_unidad'];
            $precio_ultimo_unidad = str_replace(",", ".", $precio_ultimo_unidad);
            $post_array['precio_ultimo_unidad'] = floatval($precio_ultimo_unidad) * 1000;
            $post_array['tipo_unidad'] = "Und";
        }

        if (isset($post_array['precio_transformacion_unidad'])) {
            $precio_ultimo_unidad = $post_array['precio_transformacion_unidad'];
            $precio_ultimo_unidad = str_replace(",", ".", $precio_ultimo_unidad);
            $post_array['precio_transformacion_unidad'] = floatval($precio_ultimo_unidad) * 1000;
            $post_array['tipo_unidad'] = "Und";
        }

        if (isset($post_array['precio_transformacion_peso'])) {
            $precio_ultimo_unidad = $post_array['precio_transformacion_peso'];
            $precio_ultimo_unidad = str_replace(",", ".", $precio_ultimo_unidad);
            $post_array['precio_transformacion_peso'] = floatval($precio_ultimo_unidad) * 1000;
            $post_array['tipo_unidad'] = "Kg";
        }

        if (isset($post_array['precio_transformacion'])) {
            $precio_transformacion = $post_array['precio_transformacion'];
            $precio_transformacion = str_replace(",", ".", $precio_transformacion);
            $post_array['precio_transformacion'] = floatval($precio_transformacion) * 1000;
        }


        if (isset($post_array['tarifa_venta_unidad'])) {
            $tarifa_venta_unidad = $post_array['tarifa_venta_unidad'];
            $tarifa_venta_unidad = str_replace(",", ".", $tarifa_venta_unidad);
            $post_array['tarifa_venta_unidad'] = floatval($tarifa_venta_unidad) * 1000;
        }

        if (isset($post_array['cat_tarifa'])) {
            $tarifa = $post_array['cat_tarifa'];
            $tarifa = str_replace(",", ".", $tarifa);
            $post_array['cat_tarifa'] = floatval($tarifa) * 1000;
        }

        if (isset($post_array['tarifa_venta_peso'])) {
            $tarifa_venta_peso = $post_array['tarifa_venta_peso'];
            $tarifa_venta_peso = str_replace(",", ".", $tarifa_venta_peso);
            $post_array['tarifa_venta_peso'] = floatval($tarifa_venta_peso) * 1000;
        }

        if (isset($post_array['descuento_1_compra'])) {
            $descuento_1_compra = $post_array['descuento_1_compra'];
            $descuento_1_compra = str_replace(",", ".", $descuento_1_compra);
            $post_array['descuento_1_compra'] = floatval($descuento_1_compra) * 1000;
        }

        if (isset($post_array['stock_minimo'])) {
            $stock_minimo = $post_array['stock_minimo'];
            $stock_minimo = str_replace(",", ".", $stock_minimo);
            $post_array['stock_minimo'] = floatval($stock_minimo) * 1000;
        }

        if (isset($post_array['unidades_caja'])) {
            $unidades_caja = $post_array['unidades_caja'];
            $unidades_caja = str_replace(",", ".", $unidades_caja);
            $post_array['unidades_caja'] = floatval($unidades_caja) * 1000;
        }
        if (isset($post_array['unidades_precio'])) {
            $unidades_precio = $post_array['unidades_precio'];
            $unidades_precio = str_replace(",", ".", $unidades_precio);
            $post_array['unidades_precio'] = floatval($unidades_precio) * 1000;
        }
        
        if (isset($post_array['margen_real_producto'])) {
            $margen_real_producto = $post_array['margen_real_producto'];
            $margen_real_producto = str_replace(",", ".", $margen_real_producto);
            $post_array['margen_real_producto'] = floatval($margen_real_producto) * 1000;
        }

        return $post_array;
    }

    function _producto_before_delete($primary_key) {
        $this->db->db_debug = false;

        return false;
    }

    function _calculos_formateos_productos_mercado_db($post_array) {

        $fields = array('tarifa_venta_peso1', 'tarifa_venta_peso2', 'tarifa_venta_peso3',
            'tarifa_venta_unidad1',
            'tarifa_venta_unidad2',
            'tarifa_venta_unidad3');

        foreach ($fields as $k => $v) {
            if (isset($post_array[$v])) {
                $post = $post_array[$v];
                $post = str_replace(",", ".", $post);
                $post_array[$v] = floatval($post) * 100;
            }
        }

        if ($post_array['tipo_precio'] == 'Precio por Unidad')
            $post_array['tipo_precio'] = 1;
        else
            $post_array['tipo_precio'] = 0;

        return $post_array;
    }

    function _calculos_formateos_db_ivas($post_array) {
        $valor_iva = $post_array['valor_iva'];
        $valor_iva = str_replace(",", ".", $valor_iva);
        $post_array['valor_iva'] = floatval($valor_iva) * 100;

        return $post_array;
    }

    function _calculos_formateos_db_conversion($post_array) {

        $peso = $post_array['peso'];
        $peso = str_replace(",", ".", $peso);
        $post_array['peso'] = floatval($peso) * 1000;

        return $post_array;
    }

    function _calculo_margen($value, $row) {
        $precio_ultimo = (str_replace(".", "", $row->precio_ultimo));
        $peso_real = 100;
        $descuento_1_compra = $row->descuento_1_compra;
        $margen = $precio_ultimo;
        $value = $row->precio_ultimo * $row->peso_real;
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }

    function comparar_conversiones($id_codigo_inicial, $id_codigo_final) {
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

    function codigo_producto($codigo_producto) {
        /*
        $nombre_familia=$this->productos_->getNombreFamilia($codigo_producto);
        $nombre_grupo=$this->productos_->getNombreGrupo($codigo_producto);
        if($nombre_grupo==="Packs productos"){
        $this->form_validation->set_message('codigo_producto', 'Este producto corresponde a un Pack Productos. No se puede modificar. Comunicar al Administrador la modificación. ');
            return false;
        }
        */
        if (strlen(($codigo_producto)) != 13) {
            $this->form_validation->set_message('codigo_producto', 'El código de producto debe tener exactamente 13 cifras. Revisar espacios.');
            return false;
        }
    }

    function nombre($nombre) {
        if (strpos($nombre, '%') !== false) {
            $this->form_validation->set_message('nombre', 'El nombre del producto NO puede contener el símbolo %');
            return false;
        }
    }

    function compararTarifa($unitario, $peso) {
        $unitario = str_replace(",", ".", $unitario);
        $peso = str_replace(",", ".", $peso);

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

    function id_producto($id_producto,$tipo_unidad){
        if($id_producto!=0 && $tipo_unidad=='Kg'){
            $this->form_validation->set_message('id_producto', 'El producto, con tipo de unidad = Kg, DEBE TENER el código Boka = 0');
            return false;
        }
       
    }

    function compararTarifa_unidad_vs_Tipo_precio($unitario, $tipoPrecio) {
        if ($unitario != 0 && $tipoPrecio == 0) {
            $this->form_validation->set_message('compararTarifa_unidad_vs_Tipo_precio', 'El producto NO PUEDE tener una tarifa PVP por Unidad, debe ser SOLO por Peso');
            return false;
        }
    }

    function compararTarifa_peso_vs_Tipo_precio($peso, $tipoPrecio) {
        if ($peso != 0 && $tipoPrecio == 1) {
            $this->form_validation->set_message('compararTarifa_peso_vs_Tipo_precio', 'El producto NO PUEDE tener una tarifa PVP por Peso, debe ser SOLO por Unidad');
            return false;
        }
    }

    function comparar($unitario, $peso) {
        $unitario = str_replace(",", ".", $unitario);
        $peso = str_replace(",", ".", $peso);
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

    function comparar_precios($pvp, $precio) {
        $pvp = str_replace(",", ".", $pvp);
        $precio = str_replace(",", ".", $precio);

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

    function field_callback_url($value, $row) {
        return "<span class='' style='text-align:left;display:block;'><a target='_blank' href='$value'>$value</a></span>";
    }

    function _read_url_producto($value, $row) {
        return "<span id='read_url_producto' style='text-align:left;display:block;'>$value</span>";
    }

    function _read_url_imagen_portada($value, $row) {
        if ($value != "" && $this->url_exists($value))
            return "<span id='read_url_imagen_portada' style='text-align:left;display:block;'>$value</span>";
        else {
            $imagen = base_url() . "images/pernil1812.png";
            return "<span id='read_url_imagen_portada' style='text-align:left;display:block;'>$imagen</span>";
        }
    }

    function url_exists($url) {
        if (!$fp = curl_init($url))
            return false;
        return true;
    }

    function _imagen_producto($value, $row) {
        return "<a href='$value'  target='_blank'>Imagen</a>";
        if ($value != "" && $this->url_exists($value))
            return "<span ><img  class='imagenProducto'  src='$value' height='30' width='30' alt=''></span>";
        else {
            $imagen = base_url() . "images/pernil1812.png";
            return "<span ><img  class='imagenProducto'  src='$imagen' height='30' width='30' alt=''></span>";
        }
    }

    // function url_exists_($url) {
    //     $ch = curl_init($url);
    //     //cURL set options
    //     $options = array(
    //         CURLOPT_URL => $url, #set URL address
    //         CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13', #set UserAgent to get right content like a browser
    //         CURLOPT_RETURNTRANSFER => true, #redirection result from output to string as curl_exec() result
    //         CURLOPT_COOKIEFILE => 'cookies.txt', #set cookie to skip site ads
    //         CURLOPT_COOKIEJAR => 'cookiesjar.txt', #set cookie to skip site ads
    //         CURLOPT_FOLLOWLOCATION => true, #follow by header location
    //         CURLOPT_HEADER => true, #get header (not head) of site
    //         CURLOPT_FORBID_REUSE => true, #close connection, connection is not pooled to reuse
    //         CURLOPT_FRESH_CONNECT => true, #force the use of a new connection instead of a cached one
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

}
