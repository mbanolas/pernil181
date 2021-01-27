<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");

//session_start(); //we need to call PHP's session object to access it through CI
class ArchivosBoka extends CI_Controller {

 var $archivo;   
 var $path;
 function __construct()
 {
   parent::__construct();
   $this->load->helper('file');
   $this->load->helper('download');
   $this->path='uploads'.DIRECTORY_SEPARATOR;
   $this->archivo=$this->path.'BOKA_01_12_15.TXT';
   $this->load->database();
 }
 function write_test(){
     $this->load->helper('file');
     
     $data = 'Some file data';
    if ( ! write_file('./uploads/informeMABA.TXT', $data))
    {
            echo 'Unable to write the file';
    }
    else
    {
            echo 'File written!';
    }
                $datos['error'] = ' ';
                $datos=array();
                
                $this->load->view('templates/header.html',$datos);
                
                $datos['activeMenu']='Pruebas';
                $datos['activeSubmenu']='Write Test';
                
                $this->load->view('templates/top.php',$datos);
		//$this->load->view('inicio', $data);
                $this->load->view('templates/footer.html',$datos);  
 }
 
 function download_test(){
     $data['error'] = ' ';
     $path='uploads'.DIRECTORY_SEPARATOR;
     $file="my_backup.zip";
     header("Content-type: application/txt");
            header("Content-Disposition: attachment; filename=".$path.$file."");
            header("Content-length: " . filesize($path.$file));
            header("Pragma: no-cache"); 
            header("Expires: 0");
            readfile($path.$file);
            exit;
            
    /*
     $DB2=$this->load->database('pernil181bcn',TRUE);
     $sql="INSERT INTO pe_prueba_registros SET bonu='20'";
     $DB2->query($sql);
     
     $sql="INSERT INTO pe_prueba_registros SET bonu='2000'";
     $this->db->query($sql);
     
    $datos = 'Here is some text!';
     * 
     * 
     */
    /*
    $name1 = 'uploads/BOKA_01_12_15.TXT';
    $datos1=file_get_contents($name1);
    
    $this->zip->add_data($name1, $datos1);
    
    $name2 = 'uploads/BOKA_02_12_15.TXT';
    $datos2=file_get_contents($name2);
    
    $this->zip->add_data($name2, $datos2);
    
    $this->zip->archive('uploads/my_backup.zip');
    */
   // $this->zip->download($this->path.'my_backup.zip');
   

    $string=$this->archivo;
    $data['string']=$string;
    $this->load->view('templates/header.html',$data);
    $data['activeMenu']='Pruebas';
    $data['activeSubmenu']='Download Test';
    $this->load->view('templates/top.php',$data);
    $this->load->view('archivosBoka/read_test.php',$data);
    $this->load->view('templates/footer.html',$data);  
    
    
 }
 
 function read_test(){
     $string=read_file($this->archivo);
     
     $data['error'] = ' ';
     $data['string']=$string;

    $this->load->view('templates/header.html',$data);
    $data['activeMenu']='Pruebas';
    $data['activeSubmenu']='Read Test';
    $this->load->view('templates/top.php',$data);
    $this->load->view('archivosBoka/read_test.php',$data);
    $this->load->view('templates/footer.html',$data);  
     
 }
 
 function filenames_test(){
     $data['nombresArchivos']=array();
     $data['infoFile']=array();
     $data['nombresArchivos']=get_filenames($this->path, TRUE);
     
     foreach($data['nombresArchivos'] as $k=>$v){
         $info=get_file_info($v);
         $info['date']=date('d/m/Y',$info['date']);
         $data['infoFile'][]=  $info;
         
         
     }
     
    $this->load->view('templates/header.html',$data);
    $data['activeMenu']='Pruebas';
    $data['activeSubmenu']='Nombres Archivos';
    $this->load->view('templates/top.php',$data);
    $this->load->view('archivosBoka/nombresArchivos.php',$data);
    $this->load->view('templates/footer.html',$data); 
     
     
 }
 
 
 function delete_test(){
     $data['resultado']=  delete_files($this->path, TRUE);
     $data['activeMenu']='Pruebas';
    $data['activeSubmenu']='Borrar Archivos';
     
     
    $this->load->view('templates/header.html',$data);
    $this->load->view('templates/top.php',$data);
    $this->load->view('archivosBoka/borrarArchivos.php',$data);
    $this->load->view('templates/footer.html',$data); 
     
     
 }
 
 
}