<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No está permitido el acceso directo a esta URL</h2>");

class Inicio extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->library('email');
    }

    function index() {
        
        $data['error'] = ' ';
        $data = array();
        $this->load->model('stocks_model');
		$result=$this->db->query("SELECT id,id_producto FROM pe_productos WHERE id_producto_5 IS NULL")->result();
        foreach($result as $v){
            $id=$v->id;
            $id_producto=$v->id_producto;
            $id_producto_5=$id_producto;
            while(strlen($id_producto_5)<5) $id_producto_5="0".$id_producto_5;
            $this->db->query("UPDATE pe_productos SET id_producto_5='$id_producto_5' WHERE id='$id' ");
        }
		
        $result = $this->stocks_model->getNumProductosStoksCeroOMenor();
        $fechaActualizacionStocksMinimos = $this->stocks_model->getActualizacionStocksMinimos();
        $data['fechaActualizacionStocksMinimos']=$fechaActualizacionStocksMinimos;
        $data['numCero'] = $result['numCero'];
        $data['numMenorCero'] = $result['numMenorCero'];
        
        $this->load->view('templates/header.html', $data);
        $this->load->view('templates/top.php', $data);
        // para poner nuevos passwords y que se muestren en bienvenida
        // $data['password']=md5('gt426&gtTF');
        $this->load->view('inicio', $data);
        $this->load->view('templates/footer.html', $data);
        $this->load->view('myModal', $data);
    }

    function sizePantallaEmail(){
        $ancho=$_POST['ancho'];
        $alto=$_POST['alto'];
        $ahora=date('d/m/Y H:i:s');
        if($this->session->categoria!=1){
            enviarEmail($this->email, 'Entrada aplicación',host().' - Pernil181','Sesión iniciada por: <br>Usuario: '.$this->session->nombre.'<br>Fecha: '.$ahora."<br>Pantalla: $ancho x $alto",3);
        }
        echo  json_encode(0);
    }
    function sizePantallaCambiada(){
        $ancho=$_POST['ancho'];
        $alto=$_POST['alto'];
        $ahora=date('d/m/Y H:i:s');
        if($this->session->categoria!=1){
            enviarEmail($this->email, 'Cambio tamaño pantalla',host().' - Pernil181','<br>Usuario: '.$this->session->nombre.'<br>Fecha: '.$ahora."<br>Pantalla: $ancho x $alto",3);
        }
        echo  json_encode(['ancho'=>$ancho,'alto'=>$alto]);
    }


    function nueva() {
        
        $data['error'] = ' ';
        $data = array();
        $this->load->model('stocks_model');
          $ahora=date('d/m/Y H:i:s');
        if($this->session->categoria!=1){
            enviarEmail($this->email, 'Entrada aplicación',host().' - Pernil181','Sesión iniciada por: <br>Usuario: '.$this->session->nombre.'<br>Fecha: '.$ahora,3);
        }
        $this->load->view('templates/header.html', $data);
        //$this->load->view('templates/top.php', $data);
        
        $this->load->view('nueva', $data);
        $this->load->view('templates/footer.html', $data);
        //$this->load->view('myModal', $data);
    }

    function logout() {

        $dato['error'] = ' ';
        $this->session->sess_destroy();
        
        $dato['tituloAplicacion'] = tituloAplicacion();
        $dato['host'] = host();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/cabecera', $dato);
        $this->load->view('pernil181', $dato);
        $this->load->view('templates/footer.html', $dato);
    }

    function fechaMovimientoWeb() {
        // echo 'inicio fechaMovimientoWeb'.'<br />';
        // log_message('debug', 'SQL: '.$this->db->last_query());
        $fecha = date('Y-m-d H:i:s');
        $nombre = $_POST['nombre'];
        $anchoPantalla=$_POST['ancho_pantalla'];
        $this->load->model('user');
        $fecha = $this->user->fechaMovimientoWeb($fecha, $nombre, $anchoPantalla);
        echo json_encode($_POST);
    }

}
