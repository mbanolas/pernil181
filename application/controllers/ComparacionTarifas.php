<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class ComparacionTarifas extends CI_Controller {
	
    function __construct()
	{
		parent::__construct();
		
                $this->load->library('grocery_CRUD');
                $this->load->database();
                $tabla=$this->load->model('comparacionTarifas_model');
               // $this->comparacionTarifas_model->getComparacionTarifas();
               $this->grocery_crud->set_language("spanish"); 

                	
        }
     function index(){
            
     }   
     
     public function comparacionTarifas(){
        $this->grocery_crud->set_table('pe_comparacion_tarifas');
        
        //$this->grocery_crud->field_type('activa','true_false');        
        $this->grocery_crud->callback_column('ultima_tarifa_boka', array($this, '_column_right_align_precio'));
        $this->grocery_crud->callback_column('tarifa_tabla_productos', array($this, '_column_right_align_precio'));
        
            $this->grocery_crud->unset_add();
            $this->grocery_crud->unset_edit();
            $this->grocery_crud->unset_delete();
            $this->grocery_crud->unset_read();
            
            
        
        $output = $this->grocery_crud->render();


        $output = (array) $output;
        $output['titulo'] = 'Comparación Tarifas';
        $output['col_bootstrap'] = 10;
        $output = (object) $output;
        $this->_table_output($output);
        
    }    
    
    function _column_right_align_precio($value, $row) {
      //  $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        $value/=100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }
    
    function _table_output($output = null) {

        $this->load->view('templates/header.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template.php', $output);
        $this->load->view('templates/footer.html');
    }
}