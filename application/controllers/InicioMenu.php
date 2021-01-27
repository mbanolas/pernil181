<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");

class InicioMenu extends CI_Controller {

 function __construct()
 {
   
   parent::__construct();
   $this->load->database();
  
 }

 function index()
 {
                $data['error'] = ' ';
                $data=array();
                
                $this->load->view('templates/header.html',$data);
                $this->load->view('templates/top.php',$data);
		$this->load->view('inicioMenu', $data);
                $this->load->view('templates/footer.html',$data);  
 }

 
 
 
 

}


