<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class GestionBoka extends CI_Controller {

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

    }
    
    
    public function boka($bonu) {
        ini_set('memory_limit', '-1');
        $this->grocery_crud->set_table('pe_boka');
        $this->grocery_crud->set_subject('Datos archivo Boka');
        if($bonu!=='todo'){
            $this->grocery_crud->where('BONU',$bonu);
            $datos=$this->getNumTicketYFecha($bonu);
            if(!$datos['numTicket']) $this->grocery_crud->set_subject('Datos ticket Boka introducido NO EXISTE');
            else $this->grocery_crud->set_subject('Datos ticket Boka '.$datos['numTicket'].' de '.fechaEuropea($datos['fecha']));
        }
        $this->grocery_crud->order_by('zeis','desc');
        $this->grocery_crud->set_language("spanish");
        
        $this->grocery_crud->display_as('id_pe_producto','Código 13');
        $this->grocery_crud->set_relation('id_pe_producto','pe_productos','codigo_producto');
        
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->unset_delete();
        $this->grocery_crud->unset_read();

        $output = $this->grocery_crud->render();


        $output = (array) $output;
        $output['titulo'] = 'Facturas';
        $output['col_bootstrap'] = 10;
        $output = (object) $output;
        $this->_table_output_boka($output,"Datos archivo Boka ".$bonu);
    }
    
    function _table_output_boka($output = null,$table="") {

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_boka.php', $output);
        $this->load->view('templates/footer.html');
    }
    
    
    function seleccionarBoka(){
        $this->load->view('templates/header.html');
        $this->load->view('templates/top.php');
        $this->load->view('seleccionarBoka.php');
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }
    
    function getFechasTicket(){
        $numTicket=$_POST['numTicket'];
        $sql="SELECT id,BONU,ZEIS FROM pe_boka WHERE STYP=1 AND RASA='$numTicket'";
        $result=$this->db->query($sql)->result_array();
        echo json_encode($result); 
    }
    
    function getNumTicketYFecha($bonu){
        $sql="SELECT RASA,ZEIS FROM pe_boka WHERE STYP=1 AND BONU='$bonu'";
        if(!$this->db->query($sql)->num_rows()) 
            return array('numTicket'=>$bonu,'fecha'=>0);
        $row=$this->db->query($sql)->row();
        return array('numTicket'=>$row->RASA,'fecha'=>$row->ZEIS);
    }
    
    function ticket(){
        //var_dump($POST);
        if(isset($_POST['mostrarTodo'])){
            header('Location: '.base_url().'index.php/gestionBoka/boka/todo');
        }
        
        $bonu=isset($_POST['bonuTicket'])?$_POST['bonuTicket']:0;
       
        header('Location: '.base_url().'index.php/gestionBoka/boka/'.$bonu);
        
    }
    
    
}