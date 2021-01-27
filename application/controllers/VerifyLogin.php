<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class VerifyLogin extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('user','',TRUE);
 }

 function index()
 {
   $data['post']=$_POST;
   
   $data['resultado']=$this->user->login($_POST['username'],$_POST['password']);
   //session_start();
   if ($data['resultado']==false){
       
                $dato['autor']='Miguel Angel Bañolas';
                $dato['tituloAplicacion']=tituloAplicacion();
                $dato['host']=host();
                $dato['error']="'El nombre de Usuario NO es correcto o la Contraseña no le coresponde.<BR />";
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/cabecera',$dato);
		            $this->load->view('pernil181', $dato);  
                $this->load->view('templates/footer.html',$dato);
   }
   else{
       foreach ($data['resultado'] as $row) { 
                switch($row->tipoUsuario){
                    case 0:
                        $tipoUsuario='Administrador Tienda';
                        break;
                    case 1:
                        $tipoUsuario='Administrador Sistema Informático';
                        break;
                    case 2:
                        $tipoUsuario='Dependiente';
                        break;
                    case 3:
                        $tipoUsuario='Administrativa';
                        break;
                    case 4:
                        $tipoUsuario='Administración';
                        break;
                    case 5:
                        $tipoUsuario='Administración';
                        break;
                    default:
                        $tipoUsuario='Sin Catalogar';
                }
                $newdata = array(
                    'id'=>$row->id,
                    'username'  => $row->username,
                    'nombre'     => $row->nombre,
                    'logged_in' => true,
                    'tipoUsuario' => $tipoUsuario,
                    'categoria' => $row->tipoUsuario,
                );
                
                //$this->session->set_userdata($newdata);
                foreach($newdata as $k=>$v){
                    $_SESSION[$k]=$v;
                }
                
                
                
            }
            redirect('inicio');
       //redirect('inicio/nueva');
   }
   
   
  

 }

 function check_database_1($password)
 {
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');

   //query the database
   $result = $this->user->login($username, $password);

   if($result)
   {
     $sess_array = array();
     foreach($result as $row)
     {
       $sess_array = array(
         'id' => $row->id,
         'username' => $row->username
       );
       //$this->session->set_userdata('logged_in', $sess_array);
       session_start();
       foreach($sess_array as $k=>$v){
                    $_SESSION['logged_in'][$k]=$v;
                }
       
     }
     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid username or password');
     return false;
   }
 }
}
?>