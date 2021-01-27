<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class GestionDirectorios extends CI_Controller {

    function __construct() {
        parent::__construct();
        /* Standard Libraries of codeigniter are required */
        $this->load->database();
        $this->load->helper('url');
        
        $this->load->library('grocery_CRUD');
      
        $this->grocery_crud->set_theme('bootstrap');
        $this->grocery_crud->unset_bootstrap();
        $this->grocery_crud->unset_jquery(); 
        $this->grocery_crud->set_language("spanish"); 
   
        
        $this->load->helper('cookie');
        $this->load->helper('maba');     
    }


    public function proveedores(){

        $sql="SELECT id,telefono,telefono2,movil FROM pe_proveedores";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $telefono=trim($v->telefono);
            $telefono=str_replace(' ', '', $telefono);
            $telefono=str_replace('.', '', $telefono);

            $telefono2=trim($v->telefono2);
            $telefono2=str_replace(' ', '', $telefono2);
            $telefono2=str_replace('.', '', $telefono2);

            $movil=trim($v->movil);
            $movil=str_replace(' ', '', $movil);
            $movil=str_replace('.', '', $movil);

            $this->db->query("UPDATE pe_proveedores SET telefono='$telefono', telefono2='$telefono2', movil='$movil' WHERE id='$id'");
        }

        $this->grocery_crud->set_table('pe_proveedores')
                    ->set_subject('Proveedores')
                    ->columns('id_proveedor','nombre_proveedor', 'telefono','telefono2','movil','email1');
        
        $this->grocery_crud->where('status_proveedor','1');
        $this->grocery_crud->order_by('nombre_proveedor');
        
        $display=  ['id_proveedor'=>'Código', 
           'nombre_proveedor'=>'Nombre', 
           'fechaAlta'=>'Fecha Alta', 
           'id_contable'=>'Cod. Contabilidad', 
           'id_forma_pago'=>'Forma de pago',
           'domicilio'=>'Dirección', 
           'cp'=>'Cod. Postal', 
           'poblacion'=>'Población', 
           'provincia'=>'Provincia', 
           'pais'=>'País', 
           'telefono'=>'Teléfono', 
           'fax'=>'Fax', 
           'cif'=>'CIF', 
           'fechaModificacion'=>'Fecha Modificación', 
           'web'=>'Web', 
           'contacto'=>'Contacto', 
           'movil'=>'Móvil', 
           'email1'=>'Email', 
           'otros'=>'Otros', 
           'nota'=>'Nota',  
           'email2'=>'Email 2', 
           ]; 
        $this->grocery_crud->display_as($display);

        $this->grocery_crud->set_relation('id_forma_pago','pe_formas_pagos','forma_pago');
        
        //obtenemos todos los campos
        $sql="SELECT `COLUMN_NAME`  
                        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                        WHERE `TABLE_SCHEMA`='".$this->db->database."' 
                        AND `TABLE_NAME`='pe_proveedores'";
        $result=$this->db->query($sql)->result_array();
        $campos=array_column($result, 'COLUMN_NAME');
        

        $camposEdit=array_diff($campos, array('id', 'id_proveedor','fechaAlta','fechaModificacion','status_proveedor'));
        $this->grocery_crud->edit_fields($camposEdit);

        $camposAdd=array_diff($campos, array('id', 'id_proveedor','fechaModificacion','status_proveedor')); 
        $this->grocery_crud->add_fields($camposAdd);
        
        $camposRequeridos=array('id_proveedor','nombre_proveedor','fechaAlta','domicilio','cp','poblacion','provincia','pais','telefono','cif');
        $this->grocery_crud->required_fields($camposRequeridos);

        $this->grocery_crud->callback_column('nombre_proveedor', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('telefono', array($this, '_formatearTelefono'));
        $this->grocery_crud->callback_column('telefono2', array($this, '_formatearTelefono'));
        $this->grocery_crud->callback_column('movil', array($this, '_formatearTelefono'));

        $this->grocery_crud->set_rules('email1','email NO válido','valid_email');   
        $this->grocery_crud->set_rules('email2','email NO válido','valid_email');  
        $this->grocery_crud->set_rules('id_contable','El código de contabilidad NO es válido. Debe ser 400.0.0.xxx','regex_match[/400.0.0.[0-9]{3}/]');  
        $this->grocery_crud->set_rules('cif','CIF','callback_validar_cif'); 

        $this->grocery_crud->unique_fields('id','id_contable', 'id_proveedor','nombre_proveedor');
       
        //valores establecidos para add
        $this->grocery_crud->callback_add_field('id_proveedor', function () {
            $sql = "SELECT MAX(id_proveedor) as id_proveedor FROM pe_proveedores WHERE id_proveedor<900";
            $siguiente = $this->db->query($sql)->row()->id_proveedor +1;
            log_message('INFO','hhhhhhhhhhhhhhhhh '.$this->db->query($sql)->row()->id_proveedor);
            log_message('INFO','sssssssssssssssss '.$siguiente);
            return '<input id="field-id_proveedor" name="id_proveedor" type="text" value="' . $siguiente . '" class="numeric form-control" maxlength="11">';
        });  
        $this->grocery_crud->callback_add_field('pais',array($this,'_add_field_callback_pais')); 
        
        //para borrar
        $this->grocery_crud->callback_delete(array($this, '_delete_proveedores'));
        
        //para exportar a Excel TODA la tabla
        if($this->uri->segment(3)=='export'){
            $this->grocery_crud->columns( $campos);
        } 
        
        $this->grocery_crud->callback_before_insert(array($this, '_before_insert_update_proveedor'));
        $this->grocery_crud->callback_before_update(array($this, '_before_insert_update_proveedor'));
       
       
        $this->grocery_crud->callback_after_insert(array($this, '_after_insert_proveedor'));
        $this->grocery_crud->callback_after_update(array($this, '_after_update_proveedor'));
       
       
        $output = $this->grocery_crud->render();
        
        $output = (array) $output;
        $output['titulo'] = 'Proveedores';
        $output['col_bootstrap'] = 8;
        $output = (object) $output;
        $this->_table_output_directorios($output,"Proveedores");
    }
    
    public function acreedores(){

        $sql="SELECT id,telefono,telefono2,movil FROM pe_acreedores";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $telefono=trim($v->telefono);
            $telefono=str_replace(' ', '', $telefono);
            $telefono=str_replace('.', '', $telefono);

            $telefono2=trim($v->telefono2);
            $telefono2=str_replace(' ', '', $telefono2);
            $telefono2=str_replace('.', '', $telefono2);

            $movil=trim($v->movil);
            $movil=str_replace(' ', '', $movil);
            $movil=str_replace('.', '', $movil);

            $this->db->query("UPDATE pe_acreedores SET telefono='$telefono', telefono2='$telefono2', movil='$movil' WHERE id='$id'");
        }


        $this->grocery_crud->set_table('pe_acreedores')
                    ->set_subject('Acreedores')
                    ->columns('id_proveedor','nombre_proveedor', 'telefono','telefono2','movil','email1');
        
        $this->grocery_crud->where('status_acreedor','1');
        $this->grocery_crud->order_by('nombre_proveedor');
        
        $display=  ['id_proveedor'=>'Código', 
           'nombre_proveedor'=>'Nombre', 
           'fechaAlta'=>'Fecha Alta', 
           'id_contable'=>'Cod. Contabilidad', 
           'id_forma_pago'=>'Forma de pago',
           'domicilio'=>'Dirección', 
           'cp'=>'Cod. Postal', 
           'poblacion'=>'Población', 
           'provincia'=>'Provincia', 
           'pais'=>'País', 
           'telefono'=>'Teléfono', 
           'fax'=>'Fax', 
           'cif'=>'CIF', 
           'fechaModificacion'=>'Fecha Modificación', 
           'web'=>'Web', 
           'contacto'=>'Contacto', 
           'movil'=>'Móvil', 
           'email1'=>'Email', 
           'otros'=>'Otros', 
           'nota'=>'Nota',  
           'email2'=>'Email 2', 
           ]; 
        $this->grocery_crud->display_as($display);

        $this->grocery_crud->set_relation('id_forma_pago','pe_formas_pagos','forma_pago');
        
        //obtenemos todos los campos
        $sql="SELECT `COLUMN_NAME`  
                        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                        WHERE `TABLE_SCHEMA`='".$this->db->database."' 
                        AND `TABLE_NAME`='pe_acreedores'";
        $result=$this->db->query($sql)->result_array();
        $campos=array_column($result, 'COLUMN_NAME');

        $camposEdit=array_diff($campos, array('id', 'id_proveedor','fechaAlta','fechaModificacion','status_proveedor'));
        $this->grocery_crud->edit_fields($camposEdit);

        $camposAdd=array_diff($campos, array('id', 'id_proveedor','fechaModificacion','status_proveedor')); 
        $this->grocery_crud->add_fields($camposAdd);
        
        $camposRequeridos=array('id_proveedor','nombre_proveedor','fechaAlta','domicilio','cp','poblacion','provincia','pais','telefono','cif');
        $this->grocery_crud->required_fields($camposRequeridos);

        $this->grocery_crud->callback_column('nombre_proveedor', array($this, '_column_left_align_nombre'));
        $this->grocery_crud->callback_column('telefono', array($this, '_formatearTelefono'));
        $this->grocery_crud->callback_column('telefono2', array($this, '_formatearTelefono'));
        $this->grocery_crud->callback_column('movil', array($this, '_formatearTelefono'));

        $this->grocery_crud->set_rules('email1','email NO válido','valid_email');   
        $this->grocery_crud->set_rules('email2','email NO válido','valid_email');  
        $this->grocery_crud->set_rules('id_contable','El código de contabilidad NO es válido. Debe ser 410.0.0.xxx','regex_match[/410.0.0.[0-9]{3}/]');  
        $this->grocery_crud->set_rules('cif','CIF','callback_validar_cif');   


        $this->grocery_crud->unique_fields('id','id_contable', 'id_proveedor','nombre_proveedor');
       
        //valores establecidos para add
        $this->grocery_crud->callback_add_field('id_proveedor', function () {
            $sql = "SELECT MAX(id_proveedor) as id_proveedor FROM pe_acreedores WHERE id_proveedor<900";
            $siguiente = $this->db->query($sql)->row()->id_proveedor + 1;
            return '<input id="field-id_proveedor" name="id_proveedor" type="text" value="' . $siguiente . '" class="numeric form-control" maxlength="11">';
        });  
        $this->grocery_crud->callback_add_field('pais',array($this,'_add_field_callback_pais')); 
        
        //para borrar
        $this->grocery_crud->callback_delete(array($this, '_delete_acreedores'));
        
        //para exportar a Excel TODA la tabla
        if($this->uri->segment(3)=='export'){
            $this->grocery_crud->columns( $campos);
        } 
        
        $this->grocery_crud->callback_before_insert(array($this, '_before_insert_update_proveedor'));
        $this->grocery_crud->callback_before_update(array($this, '_before_insert_update_proveedor'));
       
       
        $this->grocery_crud->callback_after_insert(array($this, '_after_insert_acreedor'));
        $this->grocery_crud->callback_after_update(array($this, '_after_update_acreedor'));
       
       
        $output = $this->grocery_crud->render();
        
        $output = (array) $output;
        $output['titulo'] = 'Proveedores';
        $output['col_bootstrap'] = 8;
        $output = (object) $output;
        $this->_table_output_directorios($output,"Proveedores");
    }

    public function clientes(){
        $sql="SELECT id,telefono1,telefono2,movil1,movil2 FROM pe_clientes";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $telefono1=trim($v->telefono1);
            $telefono1=str_replace(' ', '', $telefono1);
            $telefono1=str_replace('.', '', $telefono1);

            $telefono2=trim($v->telefono2);
            $telefono2=str_replace(' ', '', $telefono2);
            $telefono2=str_replace('.', '', $telefono2);

            $movil1=trim($v->movil1);
            $movil1=str_replace(' ', '', $movil1);
            $movil1=str_replace('.', '', $movil1);

            $movil2=trim($v->movil2);
            $movil2=str_replace(' ', '', $movil2);
            $movil2=str_replace('.', '', $movil2);

            $this->db->query("UPDATE pe_clientes SET telefono1='$telefono1', telefono2='$telefono2', movil1='$movil1', movil2='$movil2' WHERE id='$id'");
        }
        $this->grocery_crud->set_table('pe_clientes')
                    ->set_subject('Clientes')
                    ->columns('id_cliente','nombre', 'telefono1','correo1');
        
        $this->grocery_crud->where('status_cliente','1');

        //obtenemos todos los campos
        $sql="SELECT `COLUMN_NAME`  
                        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                        WHERE `TABLE_SCHEMA`='".$this->db->database."' 
                        AND `TABLE_NAME`='pe_clientes'";
        $result=$this->db->query($sql)->result_array();
        $campos=array_column($result, 'COLUMN_NAME');
       
        
       $display=array('id_cliente'=>'Código', 
           'nombre'=>'Nombre', 
           'empresa', 
           'tienda_web'=>'Tienda/Web', 
           'fechaAlta'=>'Fecha Alta', 
           'codigoContabilidad'=>'Cod. Contabilidad', 
           'direccion'=>'Dirección', 
           'codigoPostal'=>'Cod. Postal', 
           'poblacion'=>'Población', 
           'provincia'=>'Provincia', 
           'pais'=>'País', 
           'telefono1'=>'Teléfono', 
           'nif'=>'NIF', 
           'fechaModificacion'=>'Fecha Modificación', 
           'dtoGeneral'=>'Descuento General', 
           'grupoPrecio'=>'Grupo Precio', 
           'web'=>'Web', 
           'contacto1'=>'Contacto', 
           'movil1'=>'Teléfono Móvil', 
           'correo1'=>'Email', 
           'varios1'=>'Varias', 
           'notas'=>'Notas', 
           'contacto2'=>'Contacto 2', 
           'telefono2'=>'Teléfono 2', 
           'movil2'=>'Teléfono Móvil 2', 
           'correo2'=>'Email 2', 
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
           'correo2' );
        
        
         $this->grocery_crud->add_fields('id_cliente',
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
           'correo2' );
       
        
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
       $this->grocery_crud->callback_column('telefono1', array($this, '_formatearTelefono'));
       $this->grocery_crud->callback_column('telefono2', array($this, '_formatearTelefono'));
       $this->grocery_crud->callback_column('movil1', array($this, '_formatearTelefono'));
       $this->grocery_crud->callback_column('movil2', array($this, '_formatearTelefono'));        

       $this->grocery_crud->set_rules('correo1','email NO válido','valid_email');   
       $this->grocery_crud->set_rules('correo2','email NO válido','valid_email');  
       $this->grocery_crud->set_rules('nif','NIF','callback_validar_cif'); 
       
       $this->grocery_crud->set_rules('codigoContabilidad','El código de contabilidad NO es válido. Debe ser 430.0.0.xxx','regex_match[/430.0.0.[0-9]{3}/]');  
       
       //para exportar a Excel TODA la tabla
       if($this->uri->segment(3)=='export'){
            $this->grocery_crud->columns( $campos);
        } 
       $this->grocery_crud->callback_add_field('pais',array($this,'add_field_callback_pais')); 
       $this->grocery_crud->callback_add_field('id_cliente',array($this,'add_field_callback_id_cliente')); 
      
       $this->grocery_crud->callback_delete(array($this, '_delete_clientes'));
        // $this->grocery_crud->callback_add_field('fechaAlta',array($this,'add_field_callback_fechaAlta')); 

       //$this->grocery_crud->callback_add_field('fechaAlta',array($this,'_fechaEuropea'));  
       
       $this->grocery_crud->callback_before_insert(array($this, '_before_insert_update_cliente'));
       $this->grocery_crud->callback_before_update(array($this, '_before_insert_update_cliente'));
      

        $this->grocery_crud->callback_after_insert(array($this, 'fechaModificacion_after_update_cliente'));
        $this->grocery_crud->callback_after_update(array($this, 'fechaModificacion_after_update_cliente'));
       
        //campos únicos
        $this->grocery_crud->unique_fields('id', 'id_cliente','codigoContabilidad','nombre','empresa');


       $this->grocery_crud->callback_add_field('tienda_web',array($this,'add_field_callback_tienda_web'));
       $this->grocery_crud->callback_edit_field('tienda_web',array($this,'edit_field_callback_tienda_web_edit')); 
       
     // $this->grocery_crud->unset_read_fields('tienda_web');
       
        $output = $this->grocery_crud->render();
        
        
       
        
        $output = (array) $output;
        //$output['titulo'] = 'Clientes';
        $output['col_bootstrap'] = 12;
        $output = (object) $output;
        $this->_table_output_directorios($output,"Clientes");
    }
    
    /*
    public function acreedores(){
        
        $this->grocery_crud->set_table('pe_acreedores')
                    ->set_subject('Acreedores')
                    ->columns('id_proveedor','nombre_proveedor', 'telefono','email1');
        
        $this->grocery_crud->where('status_acreedor','1');
        
       $display=  ['id_proveedor'=>'Código', 
           'nombre_proveedor'=>'Nombre', 
           'fechaAlta'=>'Fecha Alta', 
           'id_contable'=>'Cod. Contabilidad', 
           'id_forma_pago'=>'Forma de pago',
           'domicilio'=>'Dirección', 
           'cp'=>'Cod. Postal', 
           'poblacion'=>'Población', 
           'provincia'=>'Provincia', 
           'pais'=>'País', 
           'telefono'=>'Teléfono', 
           'fax'=>'Fax', 
           'cif'=>'CIF', 
           'fechaModificacion'=>'Fecha Modificación', 
           'web'=>'Web', 
           'contacto'=>'Contacto', 
           'movil'=>'Teléfono Móvil', 
           'email1'=>'Email', 
           'otros'=>'Otros', 
           'nota'=>'Nota',  
           'email2'=>'Email 2', 
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
           'email2' );
        
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
       $this->grocery_crud->callback_column('telefono', array($this, '_formatearTelefono'));
       $this->grocery_crud->callback_column('telefono2', array($this, '_formatearTelefono'));
       $this->grocery_crud->callback_column('movil', array($this, '_formatearTelefono'));
       $this->grocery_crud->callback_column('email1', array($this, '_column_left_align_nombre'));

       
       $this->grocery_crud->callback_add_field('id_proveedor', function () {
           $sql="SELECT MAX(id_proveedor) as id_proveedor FROM pe_acreedores WHERE id_proveedor<900";
        $siguiente=$this->db->query($sql)->row()->id_proveedor+1;
        return '<input id="field-id_proveedor" name="id_proveedor" type="text" value="'.$siguiente.'" class="numeric form-control" maxlength="11">';
    });
    */





function validar_cif($cif){
    switch(valida_nif_cif_nie($cif)){
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


function actualizarProveedoresAcreedores(){
    $sql="DELETE FROM pe_proveedores_acreedores WHERE 1";
    $this->db->query($sql);
    $sql="SELECT id_proveedor,nombre_proveedor as nombre FROM pe_proveedores";
    $result=$this->db->query($sql)->result();
    foreach($result as $k=>$v){
        $id_proveedor=$v->id_proveedor;
        $nombre=$v->nombre;
        $sql="INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
        $this->db->query($sql);
    }
    $sql="SELECT id_proveedor,nombre_proveedor as nombre FROM pe_acreedores";
    $result=$this->db->query($sql)->result();
    foreach($result as $k=>$v){
        $id_proveedor=$v->id_proveedor*1000;
        $nombre=$v->nombre;
        $sql="INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
        $this->db->query($sql);
    }
    
    return true;
}

function _before_insert_update_proveedor($post_array){
    $post_array['telefono']=trim($post_array['telefono']);
    $post_array['telefono']=str_replace(' ', '', $post_array['telefono']);
    $post_array['telefono']=str_replace('.', '', $post_array['telefono']);

    $post_array['telefono2']=trim($post_array['telefono2']);
    $post_array['telefono2']=str_replace(' ', '', $post_array['telefono2']);
    $post_array['telefono2']=str_replace('.', '', $post_array['telefono2']);

    $post_array['movil']=trim($post_array['movil']);
    $post_array['movil']=str_replace(' ', '', $post_array['movil']);
    $post_array['movil']=str_replace('.', '', $post_array['movil']);
    return $post_array;
}

function _before_insert_update_cliente($post_array){
    $post_array['telefono1']=trim($post_array['telefono1']);
    $post_array['telefono1']=str_replace(' ', '', $post_array['telefono1']);
    $post_array['telefono1']=str_replace('.', '', $post_array['telefono1']);

    $post_array['telefono2']=trim($post_array['telefono2']);
    $post_array['telefono2']=str_replace(' ', '', $post_array['telefono2']);
    $post_array['telefono2']=str_replace('.', '', $post_array['telefono2']);

    $post_array['movil1']=trim($post_array['movil1']);
    $post_array['movil1']=str_replace(' ', '', $post_array['movil1']);
    $post_array['movil1']=str_replace('.', '', $post_array['movil1']);

    $post_array['movil2']=trim($post_array['movil2']);
    $post_array['movil2']=str_replace(' ', '', $post_array['movil2']);
    $post_array['movil2']=str_replace('.', '', $post_array['movil2']);
    return $post_array;
}

function _after_update_proveedor($post_array, $primary_key) {
    $update = array("fechaModificacion" => date('Y-m-d'));
    $this->db->update('pe_proveedores', $update);
    $this->actualizarProveedoresAcreedores();    
}

function _after_update_acreedor($post_array, $primary_key) {
    $update = array("fechaModificacion" => date('Y-m-d'));
    $this->db->update('pe_acreedores', $update);
    $this->actualizarProveedoresAcreedores();    
}

function _after_insert_proveedor($post_array, $primary_key) {
    $update = array("fechaModificacion" => date('Y-m-d'), 'id_proveedor' => $primary_key);
    $this->db->update('pe_proveedores', $update);
    $this->actualizarProveedoresAcreedores();
}

function _after_insert_acreedor($post_array, $primary_key) {
    $update = array("fechaModificacion" => date('Y-m-d'), 'id_proveedor' => $primary_key);
    $this->db->update('pe_acreedores', $update);
    $this->actualizarProveedoresAcreedores();
}

function _add_field_callback_pais() {     
    return "<input type='text' maxlength='50' value='España' name='pais' >";
}

function _column_left_align_nombre($value, $row) {
    if(strlen($value)>65) $value=substr($value, 0, 65).'...'; 
    return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
}

function _formatearTelefono($value, $row) {
     if(strlen($value)>=9) {
         $inicio=strlen($value)-9;
         $value=substr($value, 0, $inicio).' '.substr($value, $inicio, 3).' '.substr($value, $inicio+3, 3).' '.substr($value, $inicio+6); 
     }
    return "<span  style='width:100%;text-align:left;display:block;'>$value</span>";
}

function _delete_proveedores($primary_key){
    return $this->db->update('pe_proveedores',array('status_proveedor' => '0'),array('id_proveedor' => $primary_key));
}

function _delete_acreedores($primary_key){
    return $this->db->update('pe_acreedores',array('status_acreedor' => '0'),array('id_proveedor' => $primary_key));
}


function _table_output_directorios($output = null,$table="") {
    $this->load->view('templates/headerGrocery.php', $output);
    $this->load->view('templates/top.php');
    $this->load->view('table_template_directorios.php', $output);
    $this->load->view('templates/footer.html');
}



}
