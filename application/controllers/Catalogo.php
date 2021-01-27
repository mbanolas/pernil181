<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Catalogo extends CI_Controller {
	
    function __construct() {
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
    
    function _table_output_marcas($output = null,$table="") {

        $this->load->view('templates/headerGrocery.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_marcas.php', $output);
        $this->load->view('templates/footer.html');
    }
    
    function descuento_mostrar($value = '', $primary_key = null){
        return '<input type="text" name="descuento" value="'.($value/1000).'">';
    }
    function descuento_en_mostrar($value = '', $primary_key = null){
        return '<input type="text" name="descuento_en" value="'.($value/1000).'">';
    }
    function descuento_fr_mostrar($value = '', $primary_key = null){
        return '<input type="text" name="descuento_fr" value="'.($value/1000).'">';
    }
    
    function _descuento_read($value, $row) {
        $value /= 1000;
        $value = $value != 0 ? number_format($value, 3) : "";
        return "<span class='izquierda' style='text-align:left;width:100%;display:block;'>$value</span>";
    }
    
     public function marcas(){
        $this->grocery_crud->set_table('pe_marcas')
                    ->set_subject('Marcas')
                    ->columns('marca','sub_titulo');
        
        //$this->grocery_crud->where('status_proveedor','1');
        
        
       $display=  ['marca'=>'Nombre marca cast ' , 
           'sub_titulo'=>'Sub título marca cast ', 
           'mapa'=>'Url mapa marca cast ', 
           'imagen'=>'Url imagen marca cast ', 
           'descripcion_marca'=>'Descripción marca cast ',
           'descuento'=>'Descuento cast (%)',
           
           'marca_en'=>'Nombre marca inglés ', 
           'sub_titulo_en'=>'Sub título marca inglés ', 
           'mapa_en'=>'Url mapa marca inglés ', 
           'imagen_en'=>'Url imagen marca inglés inglés ', 
           'descripcion_marca_en'=>'Descripción marca inglés ',
           'descuento_en'=>'Descuento inglés (%)',
           
           'marca_fr'=>'Nombre marca francés ', 
           'sub_titulo_fr'=>'Sub título marca francés  ', 
           'mapa_fr'=>'Url mapa marca francés  ', 
           'imagen_fr'=>'Url imagen marca francés  ', 
           'descripcion_marca_fr'=>'Descripción marca francés  ', 
           'descuento_fr'=>'Descuento francés (%)',
           ];
        
       $this->grocery_crud->callback_field('descuento',array($this,'descuento_mostrar'));
       $this->grocery_crud->callback_field('descuento_en',array($this,'descuento_en_mostrar'));
       $this->grocery_crud->callback_field('descuento_fr',array($this,'descuento_fr_mostrar'));
       
       $this->grocery_crud->callback_read_field('descuento', array($this, '_descuento_read'));
       $this->grocery_crud->callback_read_field('descuento_en', array($this, '_descuento_read'));
       $this->grocery_crud->callback_read_field('descuento_fr', array($this, '_descuento_read'));
       
        $this->grocery_crud->display_as($display);
        $campos=array( 'marca',
           'sub_titulo',
           'mapa',
           'imagen',
           'descripcion_marca',
            'descuento',
                 'marca_en',
           'sub_titulo_en',
           'mapa_en',
           'imagen_en',
           'descripcion_marca_en',
            'descuento_en',
           
                 'marca_fr',
           'sub_titulo_fr',
           'mapa_fr',
           'imagen_fr',
           'descripcion_marca_fr',
            'descuento_fr'
           );
         $this->grocery_crud->edit_fields($campos);
         
        // $this->grocery_crud->add_fields($campos);
        
       
         $this->grocery_crud->required_fields(
              'marca',
           'sub_titulo',
           'mapa',
           'imagen',
           'descripcion_marca'
               );
       
        $this->grocery_crud->callback_before_insert(array($this,'_before_register'));
        $this->grocery_crud->callback_before_update(array($this,'_before_register')); 
        
        $this->grocery_crud->callback_after_insert(array($this,'_after_register'));
        $this->grocery_crud->callback_after_update(array($this,'_after_register')); 
       
        $output = $this->grocery_crud->render();
        
        
       
        
        $output = (array) $output;
        $output['titulo'] = 'Marcas';
        $output['col_bootstrap'] = 8;
        $output = (object) $output;
        $this->_table_output_marcas($output,"Marcas");
    }
    
    function _before_register($post_array, $primary_key){
        
        if(isset($post_array['descuento'])) $post_array['descuento']*=1000;
        if(isset($post_array['descuento_en'])) $post_array['descuento_en']*=1000;
        if(isset($post_array['descuento_fr'])) $post_array['descuento_fr']*=1000;
        return $post_array;
    }
    
    function _after_register($post_array, $primary_key) {
       //copyamos datos en castellano en los demas idiomas si NO esixtes
        $sql="UPDATE pe_marcas "
                . " SET marca_en=IF(marca_en='',marca,marca_en), "
                . "  marca_fr=IF(marca_fr='',marca,marca_fr), "
                . "  sub_titulo_en=IF(sub_titulo_en='',sub_titulo,sub_titulo_en), "
                . "  sub_titulo_fr=IF(sub_titulo_fr='',sub_titulo,sub_titulo_fr), "
                 . "  mapa_en=IF(mapa_en='',mapa,mapa_en), "
                . "  mapa_fr=IF(mapa_fr='',mapa,mapa_fr), "
                . "  imagen_en=IF(imagen_en='',imagen,imagen_en), "
                . "  imagen_fr=IF(imagen_fr='',imagen,imagen_fr), "
                . "  descripcion_marca_en=IF(descripcion_marca_en='',descripcion_marca,descripcion_marca_en), "
                . "  descripcion_marca_fr=IF(descripcion_marca_fr='',descripcion_marca,descripcion_marca_fr) "
               // . "  descuento_en=IF(descuento_en=0,descuento,descuento_en), "
               //  . "  descuento_fr=IF(descuento_fr=0,descuento,descuento_fr) "
                . " WHERE id='$primary_key' ";
        //log_message('INFO', $sql);
        $this->db->query($sql);
    }
    
    function replaceSpecialCharacters($str){
     
     $str=strip_tags($str);
     $str=str_replace('&aacute;','á',$str);
     $str=str_replace('&eacute;','é',$str);
     $str = str_replace('&iacute;', 'í',$str);
     $str = str_replace('&oacute;', 'ó',$str);
     $str = str_replace('&uacute;', 'ú',$str);
     $str = str_replace('&ntilde;', 'ñ',$str);
     $str = str_replace('&uuml;', 'ü',$str);
     
     $str=str_replace('&Aacute;','Á',$str);
     $str=str_replace('&Eacute;','É',$str);
     $str = str_replace('&Iacute;', 'Í',$str);
     $str = str_replace('&Oacute;', 'Ó',$str);
     $str = str_replace('&Uacute;', 'Ú',$str);
     $str = str_replace('&Ntilde;', 'Ñ',$str);
     $str = str_replace('&Uuml;', 'Ü',$str);
     $str = str_replace('&quot;', '"',$str);
     $str = str_replace('&nbsp;', ' ',$str);
     
    
     return trim($str);
     
 }
 
    function url_exists($url) {
            $ch = curl_init($url); 
        //cURL set options
        $options = array(
            CURLOPT_URL => $url,              #set URL address
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',  #set UserAgent to get right content like a browser
            CURLOPT_RETURNTRANSFER => true,         #redirection result from output to string as curl_exec() result
            CURLOPT_COOKIEFILE => 'cookies.txt',    #set cookie to skip site ads
            CURLOPT_COOKIEJAR => 'cookiesjar.txt',  #set cookie to skip site ads
            CURLOPT_FOLLOWLOCATION => true,         #follow by header location
            CURLOPT_HEADER => true,                 #get header (not head) of site
            CURLOPT_FORBID_REUSE => true,           #close connection, connection is not pooled to reuse
            CURLOPT_FRESH_CONNECT => true,          #force the use of a new connection instead of a cached one
            CURLOPT_SSL_VERIFYPEER => false         #can get protected content SSL
        );
        //set array options to object
        curl_setopt_array($ch, $options);
        curl_exec($ch); 
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);  if(empty($retcode) || $retcode > 400) { return false; } 
        else { return true; } 
    }

    function _imagen_producto($value) {
        
        if ($value != "" && $this->url_exists($value))
            return $value;
        else {
            $imagen = base_url()."images/cross-296507_1280.png";
            return $imagen;
        }
       
      
    }
    
    function imageCheck($image){
        if(getimagesize($image)[2]!=3 && getimagesize($image)[2]!=2){
                 return base_url()."images/cross-296507_1280.png";
            }
        return $image;
    }
    
    function tipoImagen($image){
        if(getimagesize($image)[2]==2) return 'JPG';
        if(getimagesize($image)[2]==3) return 'PNG';
        return '';
       
       
       if(exif_imagetype($image)==IMAGETYPE_JPEG) return 'JPG';
       if(exif_imagetype($image)==IMAGETYPE_PNG) return 'PNG';
        
      
    }

    function generarCatalogoDownLoad($modelo=""){
        
        
        //set_time_limit (0);

        $cat_marcas=array();
        /*
        var_dump($_POST['marcas']);
        echo '<br>';
        var_dump($_POST['ordenMarcas']);
          */
         
        foreach($_POST['marcas'] as $k=>$v){
                $cat_marcas[$v]=$_POST['ordenMarcas'][$k];
                //echo $v.' '.$_POST['ordenMarcas'][$k].'<br>';
        }
        //var_dump($cat_marcas); echo '<br>';
        asort($cat_marcas);
        //var_dump($_POST['ordenMarcas']);echo '<br>';
        //var_dump($cat_marcas);
        
        $ordenado=$_POST['orden'][0];
        
        $cat_nombre=array();
        $cat_imagen=array();
        $cat_referencia=array();
        $cat_origen=array();
        $cat_raza=array();
        $cat_curado=array();
        $cat_pesos=array();
        $cat_anada=array();
        $cat_formato=array();
        $cat_unidades_caja=array();
        $cat_ecologica=array();
        $cat_tipo_de_uva=array();
        $cat_volumen=array();
        $cat_variedades=array();
        $cat_descripcion=array();
        $cat_tarifa=array();
        $cat_unidad=array();
        $tarifa_profesionales=array();
        $tarifa_venta=array();
     
        // Se carga la libreria fpdf
        $this->load->library('pdf');
        
        $pdf = new Pdf();
        
       
        
        $pdf->SetAutoPageBreak(false);
      switch($_POST['idioma'][0]){
          case 'es':
              $this->crearPdf($cat_marcas,$ordenado,$modelo);
              break;
          case 'en':
              $this->crearPdfEn($cat_marcas,$ordenado,$modelo);
              break;
          case 'fr':
              $this->crearPdfFr($cat_marcas,$ordenado,$modelo);
              break;
      }  
      
     
 }
    
    function grabarDescuento(){
        $this->load->model('catalogo_model');
        $resultado=$this->catalogo_model->grabarDescuento();
        echo  json_encode($resultado);
    }
 
    function crearPdf($cat_marcas,$ordenado,$modelo){
       $this->load->library('pdf');
       $pdf = new Pdf();
        $pdf->SetAutoPageBreak(false); 
        
       foreach($cat_marcas as $km=>$vm){
           
          
            $marcaActual=$km;
            //echo $marcaActual.'<br>';
            
            $sql="SELECT * FROM pe_productos WHERE cat_marca='$marcaActual' ORDER BY $ordenado";
            if($this->db->query($sql)->num_rows()==0) continue;
            $result2=$this->db->query($sql)->result();
            //para cada marca
                $cat_nombre=array();
                $cat_imagen=array();
                $cat_referencia=array();
                $cat_origen=array();
                $cat_raza=array();
                $cat_curado=array();
                $cat_pesos=array();
                $cat_anada=array();
                $cat_formato=array();
                $cat_unidades_caja=array();
                $cat_ecologica=array();
                $cat_tipo_de_uva=array();
                $cat_volumen=array();
                $cat_variedades=array();
                $cat_descripcion=array();
                $cat_tarifa=array();
                $cat_unidad=array();
                $tarifa_profesionales=array();
                $tarifa_venta=array();
                $tipo_unidad=array();
                $iva=array();
            foreach($result2 as $kd=>$vd){
                //leer datos de la marca
                if(true){
                

                $cat_nombre[]=$vd->cat_nombre;
               // echo $v->cat_url_producto.'<br>';
                $cat_imagen[]=$vd->cat_url_producto;
                $cat_referencia[]=$vd->cat_referencia;
                $cat_origen[]=$vd->cat_origen;
                $cat_raza[]=$vd->cat_raza;
                $cat_curado[]=$vd->cat_curado;
                $cat_pesos[]=$vd->cat_pesos;
                $cat_anada[]=$vd->cat_anada;
                $cat_formato[]=$vd->cat_formato;
                $cat_unidades_caja[]=number_format($vd->cat_unidades_caja/1000,0);
                $cat_ecologica[]=$vd->cat_ecologica;
                $cat_tipo_de_uva[]=$vd->cat_tipo_de_uva;
                $cat_volumen[]=$vd->cat_volumen;
                $cat_variedades[]=$vd->cat_variedades;
                $cat_descripcion[]=$vd->cat_descripcion;
                //$cat_tarifa[]=number_format($vd->cat_tarifa/1000,2);
                
                $cat_unidad[]=$vd->cat_unidad;
                $tarifa_profesionales[]=number_format($vd->tarifa_profesionales/1000,2);
                $tarifa_venta[]=$vd->tarifa_venta;
                $iva[]=$vd->iva;
                $tipo_iva=$vd->iva/100000;
                $cat_tarifa[]=number_format($vd->tarifa_venta/1000/(1+$tipo_iva),2);
                $tipo_unidad[]=$vd->tipo_unidad;
                }
            }
                //leer datos cabecera de la marca
                $sql="SELECT * FROM pe_marcas WHERE id='$marcaActual'";
                $row=$this->db->query($sql)->row();
                extract((array)$row);
                $marca=trim($marca);
                if(preg_match("/\([0-9a-z]{1}\)/i",$marca)){
                    $marca=substr($marca,0,-3);
                }
                //escribir datos de la marca
                $margenIzq=10;
                $margenSup=15;
                $marco=0;
                $espacio=10;
                $anchoColumna=62;
                //var_dump($cat_referencia);
                
                foreach($cat_imagen as $k=>$v){
                   
                    if($k%4==0){
                        //cabecera
                        if(true){
                            $pdf->AddPage('L','A4');
                            $pdf->SetFont('Arial','B',22);
                           
                          $imagenMapa=$this->_imagen_producto(base_url().$mapa); 
                          $tipo=$this->tipoImagen($imagenMapa);
                          $pdf->Image($imagenMapa,$margenIzq,$margenSup,40,0,$tipo);
                          $imagenMarca=$this->_imagen_producto(base_url().$imagen);  
                          $tipo=$this->tipoImagen($imagenMarca);
                          $pdf->Image($imagenMarca,$margenIzq+43,$margenSup,73,40,$tipo);
                  
                            $pdf->setY($margenSup);
                            $pdf->Cell(120);
                            
                            $pdf->Cell(0,6,iconv('UTF-8', 'windows-1252', $marca),$marco,2,'L');
                            $pdf->SetFont('Arial','B',10);
                            $pdf->Cell(0,8,iconv('UTF-8', 'windows-1252', $sub_titulo),$marco,2,'L');
                            $pdf->Cell(0,1,"",$marco,2,'L');
                            $pdf->SetFont('Arial','',9);
                          
                            $descripcion_marca=$this->replaceSpecialCharacters($descripcion_marca);
                            $pdf->MultiCell(0,4,iconv('UTF-8', 'windows-1252', $descripcion_marca),$marco,'L');
                            
                            $y=$margenSup+45;
                            $pdf->Line($margenIzq,$y,297-$margenIzq,$y);
                        }
                    }
        
              $y=$margenSup+42;
              
              $imagenProducto=$this->_imagen_producto($cat_imagen[$k]); 
              $tipo=$this->tipoImagen($imagenProducto);
              if($tipo){
                  $pdf->Image($imagenProducto,$margenSup+$espacio*($k%4)+$anchoColumna*($k%4),$y+16,40,0,$tipo); 
              }else{
                   $imagenProducto = base_url()."images/cross-296507_1280.png"; 
                   $tipo='PNG';
                   $pdf->Image($imagenProducto,$margenSup+$espacio*($k%4)+$anchoColumna*($k%4),$y+16,40,0,$tipo); 
              }
             
            
            
            $yc=$pdf->getY();
            $pdf->setY($y+51+3+8);
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->MultiCell($anchoColumna,2,'',$marco,'L');
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_nombre[$k]),$marco,'L');
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->MultiCell($anchoColumna,2,'',$marco,'L');
           
            $tamañoLetra=9;
            $alturaTexto=3;
            $sepLineas=5;
            if($cat_referencia[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(16,$alturaTexto,"Referencia",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_referencia[$k]),$marco,0,'L');
            }
            
            if(isset($cat_origen[$k]) && $cat_origen[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(10,$alturaTexto,"Origen",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_origen[$k]),$marco,0,'L');
            }
            
            if(isset($cat_raza[$k]) && $cat_raza[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(8,$alturaTexto,"Raza",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_raza[$k]),$marco,0,'L');
            }
            
            if(isset($cat_curado[$k]) && $cat_curado[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(13,$alturaTexto,iconv('UTF-8', 'windows-1252', "Curación"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_curado[$k]),$marco,0,'L');
            }
            
            if(isset($cat_pesos[$k]) && $cat_pesos[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(9,$alturaTexto,iconv('UTF-8', 'windows-1252', "Pesos"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_pesos[$k]),$marco,0,'L');
            }
            
            if(isset($cat_anada[$k]) && $cat_anada[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(9,$alturaTexto,iconv('UTF-8', 'windows-1252', "Añada"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_anada[$k]),$marco,0,'L');
            }
            
            if(isset($cat_formato[$k]) && $cat_formato[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(12,$alturaTexto,iconv('UTF-8', 'windows-1252', "Formato"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_formato[$k]),$marco,0,'L');
            }
           // echo $cat_unidades_caja[$k].'<br>';
            if(isset($cat_unidades_caja[$k]) && ($cat_unidades_caja[$k]!=0)){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(20,$alturaTexto,iconv('UTF-8', 'windows-1252', "Unidades caja"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_unidades_caja[$k]),$marco,0,'L');
            }
            
            if(isset($cat_ecologica[$k]) && $cat_ecologica[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(14,$alturaTexto,iconv('UTF-8', 'windows-1252', "Ecológica"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_ecologica[$k]),$marco,0,'L');
            }
            
             if(isset($cat_tipo_de_uva[$k]) && $cat_tipo_de_uva[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(17,$alturaTexto,iconv('UTF-8', 'windows-1252', "Tipo de uva"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_tipo_de_uva[$k]),$marco,0,'L');
            }
            
             if(isset($cat_volumen[$k]) && $cat_volumen[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(13,$alturaTexto,iconv('UTF-8', 'windows-1252', "Volumen"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_volumen[$k]),$marco,0,'L');
            }
            
            if(isset($cat_variedades[$k]) && $cat_variedades[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(16,$alturaTexto,iconv('UTF-8', 'windows-1252', "Variedades"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_variedades[$k]),$marco,0,'L');
            }
            
            
             if(isset($cat_descripcion[$k]) && $cat_descripcion[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                //$pdf->SetFont('Arial','U',$tamañoLetra);
                //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Ecológica"),0,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->MultiCell($anchoColumna,3,'',$marco,'L');
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_descripcion[$k]),$marco,'L');
            }
            if($modelo=="profesionales"){
                //log_message('INFO','---------------------------------------'.$descuento);
                if($cat_tarifa[$k]!="0.00"){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    //$pdf->SetFont('Arial','B',$tamañoLetra);
                    //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
                    $pdf->SetFont('Arial','B',10);
                    $pvp=number_format($cat_tarifa[$k]*(100-$descuento/1000)/100,2);
                    $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$pvp.' '.$cat_unidad[$k]),0,0,'L');
                }
                else if(isset($tarifa_profesionales[$k]) && $tarifa_profesionales[$k]!=""){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    //$pdf->SetFont('Arial','B',$tamañoLetra);
                    //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
                    //$tarifa_profesionales[$k]= number_format($tarifa_profesionales[$k],2,",",".")." €/".$tipo_unidad[$k].".";
                    $pdf->SetFont('Arial','B',10);
                    $pvp=number_format($cat_tarifa[$k]*(100-$descuento/1000)/100,2);
                    //$pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$cat_tarifa[$k].' '.$cat_unidad[$k]),0,0,'L');
                    //$pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$tarifa_profesionales[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$pvp.' '.$cat_unidad[$k]),0,0,'L');
                }
            }
            if($modelo=="tienda"){
                $tarifa=$_POST['tarifa'][0];
                if(isset($tarifa_venta[$k]) && $tarifa_venta[$k]!=""){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    $pdf->SetFont('Arial','B',10);
                    if($tarifa=='pvp'){
                        $tarifa_venta[$k]=number_format($tarifa_venta[$k]/1000,2);
                        $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa venta PVP: '.$tarifa_venta[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    }
                    else if($tarifa=='base'){
                        $tarifa_venta[$k]=number_format($tarifa_venta[$k]/1000*(1-$iva[$k]/100000),2);
                        $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa BASE: '.$tarifa_venta[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    }
                }
       
            }
            
          }
        }
        $hoy=date('Y m d');
        $pdf->Output('F',iconv('UTF-8', 'windows-1252', "uploads/catalogos/Catalogo ").$hoy." es.pdf");  
        $pdf->Output('D',iconv('UTF-8', 'windows-1252', "Catálogo ").$hoy." es .pdf");
        
        
        
        
   }
   
    function crearPdfEn($cat_marcas,$ordenado,$modelo){
       $this->load->library('pdf');
        $pdf = new Pdf();
        $pdf->SetAutoPageBreak(false);
        
       foreach($cat_marcas as $km=>$vm){
          
            $marcaActual=$km;
            //echo $marcaActual.'<br>';
            
            $sql="SELECT * FROM pe_productos WHERE cat_marca='$marcaActual' ORDER BY $ordenado";
            if($this->db->query($sql)->num_rows()==0) continue;
            $result2=$this->db->query($sql)->result();
            //para cada marca
                $cat_nombre=array();
                $cat_imagen=array();
                $cat_referencia=array();
                $cat_origen=array();
                $cat_raza=array();
                $cat_curado=array();
                $cat_pesos=array();
                $cat_anada=array();
                $cat_formato=array();
                $cat_unidades_caja=array();
                $cat_ecologica=array();
                $cat_tipo_de_uva=array();
                $cat_volumen=array();
                $cat_variedades=array();
                $cat_descripcion=array();
                $cat_tarifa=array();
                $cat_unidad=array();
                $tarifa_profesionales=array();
                $tarifa_venta=array();
                $tipo_unidad=array();
                $iva=array();
            foreach($result2 as $kd=>$vd){
                //leer datos de la marca
                if(true){
                

                $cat_nombre[]=$vd->cat_nombre_en;
               // echo $v->cat_url_producto.'<br>';
                $cat_imagen[]=$vd->cat_url_producto_en;
                $cat_referencia[]=$vd->cat_referencia_en;
                $cat_origen[]=$vd->cat_origen_en;
                $cat_raza[]=$vd->cat_raza_en;
                $cat_curado[]=$vd->cat_curado_en;
                $cat_pesos[]=$vd->cat_pesos_en;
                $cat_anada[]=$vd->cat_anada_en;
                $cat_formato[]=$vd->cat_formato_en;
                $cat_unidades_caja[]=number_format($vd->cat_unidades_caja_en/1000,0);
                $cat_ecologica[]=$vd->cat_ecologica_en;
                $cat_tipo_de_uva[]=$vd->cat_tipo_de_uva_en;
                $cat_volumen[]=$vd->cat_volumen_en;
                $cat_variedades[]=$vd->cat_variedades_en;
                $cat_descripcion[]=$vd->cat_descripcion_en;
                //$cat_tarifa[]=number_format($vd->cat_tarifa_en/1000,2);
                $cat_unidad[]=$vd->cat_unidad_en;
                $tarifa_profesionales[]=number_format($vd->tarifa_profesionales/1000,2);
                $tarifa_venta[]=$vd->tarifa_venta;
                $iva[]=$vd->iva;
                $tipo_iva=$vd->iva/100000;
                $cat_tarifa[]=number_format($vd->tarifa_venta/1000/(1+$tipo_iva),2);

                $tipo_unidad[]=$vd->tipo_unidad;
                }
            }
               //echo $marcaActual;
                //leer datos cabecera de la marca
                $sql="SELECT * FROM pe_marcas WHERE id='$marcaActual'";
                $row=$this->db->query($sql)->row();
                //var_dump((array)$row);
                extract((array)$row);
                $marca=$marca_en;
                $sub_titulo=$sub_titulo_en;
                
                $mapa=$mapa_en;
               // echo $mapa_en;
               // echo $mapa;
                $imagen=$imagen_en;
                $descripcion_marca=$descripcion_marca_en;
                
                //escribir datos de la marca
                $margenIzq=10;
                $margenSup=15;
                $marco=0;
                $espacio=10;
                $anchoColumna=62;
                //var_dump($cat_referencia);
                
                foreach($cat_imagen as $k=>$v){
                   
                    if($k%4==0){
                        //cabecera
                        if(true){
                            $pdf->AddPage('L','A4');
                            $pdf->SetFont('Arial','B',22);
                           
                            
                            
                            $imagenMapa=$this->_imagen_producto(base_url().$mapa); 
                            $tipo=$this->tipoImagen($imagenMapa);
                            $pdf->Image($imagenMapa,$margenIzq,$margenSup,40,0,$tipo);

                            $imagenMarca=$this->_imagen_producto(base_url().$imagen);  
                            $tipo=$this->tipoImagen($imagenMarca);
                            $pdf->Image($imagenMarca,$margenIzq+43,$margenSup,73,40,$tipo);
                           
                            $pdf->setY($margenSup);
                            $pdf->Cell(120);
                            
                            $pdf->Cell(0,6,iconv('UTF-8', 'windows-1252', $marca),$marco,2,'L');
                            $pdf->SetFont('Arial','B',10);
                            $pdf->Cell(0,8,iconv('UTF-8', 'windows-1252', $sub_titulo),$marco,2,'L');
                            $pdf->Cell(0,1,"",$marco,2,'L');
                            $pdf->SetFont('Arial','',9);
                          
                            $descripcion_marca=$this->replaceSpecialCharacters($descripcion_marca);
                            $pdf->MultiCell(0,4,iconv('UTF-8', 'windows-1252', $descripcion_marca),$marco,'L');
                            
                            $y=$margenSup+45;
                            $pdf->Line($margenIzq,$y,297-$margenIzq,$y);
                        }
                    }
        
            $y=$margenSup+42;

            $imagenProducto=$this->_imagen_producto($cat_imagen[$k]); 
              $tipo=$this->tipoImagen($imagenProducto);
              if($tipo){
                  $pdf->Image($imagenProducto,$margenSup+$espacio*($k%4)+$anchoColumna*($k%4),$y+16,40,0,$tipo); 
              }else{
                   $imagenProducto = base_url()."images/cross-296507_1280.png"; 
                   $tipo='PNG';
                   $pdf->Image($imagenProducto,$margenSup+$espacio*($k%4)+$anchoColumna*($k%4),$y+16,40,0,$tipo); 
              }
            
            $yc=$pdf->getY();
            $pdf->setY($y+51+3+8);
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            //echo $k.'  -  '.($margenSup+$espacio*($k%4)+$anchoColumna*($k%4)).'<br>';
            $pdf->MultiCell($anchoColumna,2,'',$marco,'L');
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_nombre[$k]),$marco,'L');
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->MultiCell($anchoColumna,2,'',$marco,'L');
           
            $tamañoLetra=9;
            $alturaTexto=3;
            $sepLineas=5;
            if($cat_referencia[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(16,$alturaTexto,"Reference",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_referencia[$k]),$marco,0,'L');
            }
            
            if(isset($cat_origen[$k]) && $cat_origen[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(10,$alturaTexto,"Origin",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_origen[$k]),$marco,0,'L');
            }
            
            if(isset($cat_raza[$k]) && $cat_raza[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(8,$alturaTexto,"Breed",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_raza[$k]),$marco,0,'L');
            }
            
            if(isset($cat_curado[$k]) && $cat_curado[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(13,$alturaTexto,iconv('UTF-8', 'windows-1252', "Curing time"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_curado[$k]),$marco,0,'L');
            }
            
            if(isset($cat_pesos[$k]) && $cat_pesos[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(9,$alturaTexto,iconv('UTF-8', 'windows-1252', "Weight"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_pesos[$k]),$marco,0,'L');
            }
            
            if(isset($cat_anada[$k]) && $cat_anada[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(9,$alturaTexto,iconv('UTF-8', 'windows-1252', "Year"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_anada[$k]),$marco,0,'L');
            }
            
            if(isset($cat_formato[$k]) && $cat_formato[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(12,$alturaTexto,iconv('UTF-8', 'windows-1252', "Format"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_formato[$k]),$marco,0,'L');
            }
           // echo $cat_unidades_caja[$k].'<br>';
            if(isset($cat_unidades_caja[$k]) && ($cat_unidades_caja[$k]!=0)){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(20,$alturaTexto,iconv('UTF-8', 'windows-1252', "Unities per box"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_unidades_caja[$k]),$marco,0,'L');
            }
            
            if(isset($cat_ecologica[$k]) && $cat_ecologica[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(14,$alturaTexto,iconv('UTF-8', 'windows-1252', "Eco"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_ecologica[$k]),$marco,0,'L');
            }
            
             if(isset($cat_tipo_de_uva[$k]) && $cat_tipo_de_uva[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(17,$alturaTexto,iconv('UTF-8', 'windows-1252', "Sort of grape"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_tipo_de_uva[$k]),$marco,0,'L');
            }
            
             if(isset($cat_volumen[$k]) && $cat_volumen[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(13,$alturaTexto,iconv('UTF-8', 'windows-1252', "Volume"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_volumen[$k]),$marco,0,'L');
            }
            
            if(isset($cat_variedades[$k]) && $cat_variedades[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(16,$alturaTexto,iconv('UTF-8', 'windows-1252', "Varieties"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_variedades[$k]),$marco,0,'L');
            }
            
            
             if(isset($cat_descripcion[$k]) && $cat_descripcion[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                //$pdf->SetFont('Arial','U',$tamañoLetra);
                //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Ecológica"),0,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->MultiCell($anchoColumna,3,'',$marco,'L');
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_descripcion[$k]),$marco,'L');
            }
            if($modelo=="profesionales"){
                //log_message('INFO','---------------------------------------'.$descuento_en);
                if($cat_tarifa[$k]!="0.00"){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    //$pdf->SetFont('Arial','B',$tamañoLetra);
                    //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
                    $pdf->SetFont('Arial','B',10);
                    $pvp=number_format($cat_tarifa[$k]*(100-$descuento_en/1000)/100,2);
                    $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$pvp.' '.$cat_unidad[$k]),0,0,'L');
                }
                else if(isset($tarifa_profesionales[$k]) && $tarifa_profesionales[$k]!=""){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    //$pdf->SetFont('Arial','B',$tamañoLetra);
                    //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
                    //$tarifa_profesionales[$k]= number_format($tarifa_profesionales[$k],2,",",".")." €/".$tipo_unidad[$k].".";
                    $pdf->SetFont('Arial','B',10);
                    $pvp=number_format($cat_tarifa[$k]*(100-$descuento_en/1000)/100,2);
                    //$pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$cat_tarifa[$k].' '.$cat_unidad[$k]),0,0,'L');
                    //$pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$tarifa_profesionales[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$pvp.' '.$cat_unidad[$k]),0,0,'L');
                }
            }
            if($modelo=="tienda"){
                $tarifa=$_POST['tarifa'][0];
                if(isset($tarifa_venta[$k]) && $tarifa_venta[$k]!=""){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    $pdf->SetFont('Arial','B',10);
                    if($tarifa=='pvp'){
                        $tarifa_venta[$k]=number_format($tarifa_venta[$k]/1000,2);
                        $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Rate PVP: '.$tarifa_venta[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    }
                    else if($tarifa=='base'){
                        $tarifa_venta[$k]=number_format($tarifa_venta[$k]/1000*(1-$iva[$k]/100000),2);
                        $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Rate base: '.$tarifa_venta[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    }
                }
       
            }
            
          }
        
        }
        
       $hoy=date('Y m d');
       $pdf->Output('F',iconv('UTF-8', 'windows-1252', "uploads/catalogos/Catalogo ").$hoy." en.pdf");  
        $pdf->Output('D',iconv('UTF-8', 'windows-1252', "Catálogo ").$hoy." en .pdf");
        
        
   }
   
    function crearPdfFr($cat_marcas,$ordenado,$modelo){
       $this->load->library('pdf');
        $pdf = new Pdf();
        $pdf->SetAutoPageBreak(false);
        
       foreach($cat_marcas as $km=>$vm){
          
            $marcaActual=$km;
            //echo $marcaActual.'<br>';
            
            $sql="SELECT * FROM pe_productos WHERE cat_marca='$marcaActual' ORDER BY $ordenado";
            if($this->db->query($sql)->num_rows()==0) continue;
            $result2=$this->db->query($sql)->result();
            //para cada marca
                $cat_nombre=array();
                $cat_imagen=array();
                $cat_referencia=array();
                $cat_origen=array();
                $cat_raza=array();
                $cat_curado=array();
                $cat_pesos=array();
                $cat_anada=array();
                $cat_formato=array();
                $cat_unidades_caja=array();
                $cat_ecologica=array();
                $cat_tipo_de_uva=array();
                $cat_volumen=array();
                $cat_variedades=array();
                $cat_descripcion=array();
                $cat_tarifa=array();
                $cat_unidad=array();
                $tarifa_profesionales=array();
                $tarifa_venta=array();
                $tipo_unidad=array();
                $iva=array();
            foreach($result2 as $kd=>$vd){
                //leer datos de la marca
                if(true){
                

                $cat_nombre[]=$vd->cat_nombre;
               // echo $v->cat_url_producto.'<br>';
                $cat_imagen[]=$vd->cat_url_producto;
                $cat_referencia[]=$vd->cat_referencia;
                $cat_origen[]=$vd->cat_origen;
                $cat_raza[]=$vd->cat_raza;
                $cat_curado[]=$vd->cat_curado;
                $cat_pesos[]=$vd->cat_pesos;
                $cat_anada[]=$vd->cat_anada;
                $cat_formato[]=$vd->cat_formato;
                $cat_unidades_caja[]=number_format($vd->cat_unidades_caja/1000,0);
                $cat_ecologica[]=$vd->cat_ecologica;
                $cat_tipo_de_uva[]=$vd->cat_tipo_de_uva;
                $cat_volumen[]=$vd->cat_volumen;
                $cat_variedades[]=$vd->cat_variedades;
                $cat_descripcion[]=$vd->cat_descripcion;
                //$cat_tarifa[]=number_format($vd->cat_tarifa/1000,2);
                //$cat_tarifa[]=number_format($vd->tarifa_venta/1000,2);
                $cat_unidad[]=$vd->cat_unidad;
                $tarifa_profesionales[]=number_format($vd->tarifa_profesionales/1000,2);
                $tarifa_venta[]=$vd->tarifa_venta;
                $iva[]=$vd->iva;
                $tipo_iva=$vd->iva/100000;
                $cat_tarifa[]=number_format($vd->tarifa_venta/1000/(1+$tipo_iva),2);
                $tipo_unidad[]=$vd->tipo_unidad;
                }
            }
                //leer datos cabecera de la marca
                $sql="SELECT * FROM pe_marcas WHERE id='$marcaActual'";
                $row=$this->db->query($sql)->row();
                extract((array)$row);
                
                //escribir datos de la marca
                $margenIzq=10;
                $margenSup=15;
                $marco=0;
                $espacio=10;
                $anchoColumna=62;
                //var_dump($cat_referencia);
                
                foreach($cat_imagen as $k=>$v){
                   
                    if($k%4==0){
                        //cabecera
                        if(true){
                            $pdf->AddPage('L','A4');
                            $pdf->SetFont('Arial','B',22);
                          
                            $imagenMapa=$this->_imagen_producto(base_url().$mapa); 
                            $tipo=$this->tipoImagen($imagenMapa);
                            $pdf->Image($imagenMapa,$margenIzq,$margenSup,40,0,$tipo);

                            $imagenMarca=$this->_imagen_producto(base_url().$imagen);  
                            $tipo=$this->tipoImagen($imagenMarca);
                            $pdf->Image($imagenMarca,$margenIzq+43,$margenSup,73,40,$tipo);
                   
                            $pdf->setY($margenSup);
                            $pdf->Cell(120);
                            
                            $pdf->Cell(0,6,iconv('UTF-8', 'windows-1252', $marca),$marco,2,'L');
                            $pdf->SetFont('Arial','B',10);
                            $pdf->Cell(0,8,iconv('UTF-8', 'windows-1252', $sub_titulo),$marco,2,'L');
                            $pdf->Cell(0,1,"",$marco,2,'L');
                            $pdf->SetFont('Arial','',9);
                          
                            $descripcion_marca=$this->replaceSpecialCharacters($descripcion_marca);
                            $pdf->MultiCell(0,4,iconv('UTF-8', 'windows-1252', $descripcion_marca),$marco,'L');
                            
                            $y=$margenSup+45;
                            $pdf->Line($margenIzq,$y,297-$margenIzq,$y);
                        }
                    }
        
            $y=$margenSup+42;

            
            $imagenProducto=$this->_imagen_producto($cat_imagen[$k]); 
              $tipo=$this->tipoImagen($imagenProducto);
              if($tipo){
                  $pdf->Image($imagenProducto,$margenSup+$espacio*($k%4)+$anchoColumna*($k%4),$y+16,40,0,$tipo); 
              }else{
                   $imagenProducto = base_url()."images/cross-296507_1280.png"; 
                   $tipo='PNG';
                   $pdf->Image($imagenProducto,$margenSup+$espacio*($k%4)+$anchoColumna*($k%4),$y+16,40,0,$tipo); 
              }
            
            $yc=$pdf->getY();
            $pdf->setY($y+51+3+8);
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            //echo $k.'  -  '.($margenSup+$espacio*($k%4)+$anchoColumna*($k%4)).'<br>';
            $pdf->MultiCell($anchoColumna,2,'',$marco,'L');
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_nombre[$k]),$marco,'L');
            $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
            $pdf->MultiCell($anchoColumna,2,'',$marco,'L');
           
            $tamañoLetra=9;
            $alturaTexto=3;
            $sepLineas=5;
            if($cat_referencia[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(16,$alturaTexto,iconv('UTF-8', 'windows-1252', "Référence"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_referencia[$k]),$marco,0,'L');
            }
            
            if(isset($cat_origen[$k]) && $cat_origen[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(10,$alturaTexto,"Origine",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_origen[$k]),$marco,0,'L');
            }
            
            if(isset($cat_raza[$k]) && $cat_raza[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(8,$alturaTexto,"Race",$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_raza[$k]),$marco,0,'L');
            }
            
            if(isset($cat_curado[$k]) && $cat_curado[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(13,$alturaTexto,iconv('UTF-8', 'windows-1252', "Afinage"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_curado[$k]),$marco,0,'L');
            }
            
            if(isset($cat_pesos[$k]) && $cat_pesos[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(9,$alturaTexto,iconv('UTF-8', 'windows-1252', "Poids"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_pesos[$k]),$marco,0,'L');
            }
            
            if(isset($cat_anada[$k]) && $cat_anada[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(9,$alturaTexto,iconv('UTF-8', 'windows-1252', "Millésimes"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_anada[$k]),$marco,0,'L');
            }
            
            if(isset($cat_formato[$k]) && $cat_formato[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(12,$alturaTexto,iconv('UTF-8', 'windows-1252', "Format"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_formato[$k]),$marco,0,'L');
            }
           // echo $cat_unidades_caja[$k].'<br>';
            if(isset($cat_unidades_caja[$k]) && ($cat_unidades_caja[$k]!=0)){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(20,$alturaTexto,iconv('UTF-8', 'windows-1252', "Unités par carton"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_unidades_caja[$k]),$marco,0,'L');
            }
            
            if(isset($cat_ecologica[$k]) && $cat_ecologica[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(14,$alturaTexto,iconv('UTF-8', 'windows-1252', "Bio"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_ecologica[$k]),$marco,0,'L');
            }
            
             if(isset($cat_tipo_de_uva[$k]) && $cat_tipo_de_uva[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(17,$alturaTexto,iconv('UTF-8', 'windows-1252', "Type du raisin"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_tipo_de_uva[$k]),$marco,0,'L');
            }
            
             if(isset($cat_volumen[$k]) && $cat_volumen[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(13,$alturaTexto,iconv('UTF-8', 'windows-1252', "Volume"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_volumen[$k]),$marco,0,'L');
            }
            
            if(isset($cat_variedades[$k]) && $cat_variedades[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->SetFont('Arial','U',$tamañoLetra);
                $pdf->Cell(16,$alturaTexto,iconv('UTF-8', 'windows-1252', "Varietés"),$marco,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->Cell(0,$alturaTexto,": ".iconv('UTF-8', 'windows-1252', $cat_variedades[$k]),$marco,0,'L');
            }
            
            
             if(isset($cat_descripcion[$k]) && $cat_descripcion[$k]!=""){
                $yc=$pdf->getY();
                $pdf->setY($yc+$sepLineas);
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                //$pdf->SetFont('Arial','U',$tamañoLetra);
                //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Ecológica"),0,0,'L');
                $pdf->SetFont('Arial','',$tamañoLetra);
                $pdf->MultiCell($anchoColumna,3,'',$marco,'L');
                $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                $pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_descripcion[$k]),$marco,'L');
            }
            if($modelo=="profesionales"){
                //log_message('INFO','---------------------------------------'.$descuento_fr);
                if($cat_tarifa[$k]!="0.00"){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    //$pdf->SetFont('Arial','B',$tamañoLetra);
                    //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
                    $pdf->SetFont('Arial','B',10);
                    $pvp=number_format($cat_tarifa[$k]*(100-$descuento_fr/1000)/100,2);
                    $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$pvp.' '.$cat_unidad[$k]),0,0,'L');
                }
                else if(isset($tarifa_profesionales[$k]) && $tarifa_profesionales[$k]!=""){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    //$pdf->SetFont('Arial','B',$tamañoLetra);
                    //$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
                    //$tarifa_profesionales[$k]= number_format($tarifa_profesionales[$k],2,",",".")." €/".$tipo_unidad[$k].".";
                    $pdf->SetFont('Arial','B',10);
                    $pvp=number_format($cat_tarifa[$k]*(100-$descuento_fr/1000)/100,2);
                    //$pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$cat_tarifa[$k].' '.$cat_unidad[$k]),0,0,'L');
                    //$pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$tarifa_profesionales[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$pvp.' '.$cat_unidad[$k]),0,0,'L');
                }
            }
            if($modelo=="tienda"){
                $tarifa=$_POST['tarifa'][0];
                if(isset($tarifa_venta[$k]) && $tarifa_venta[$k]!=""){
                    $yc=$pdf->getY();
                    $pdf->setY(185);
                    $pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
                    $pdf->SetFont('Arial','B',10);
                    if($tarifa=='pvp'){
                        $tarifa_venta[$k]=number_format($tarifa_venta[$k]/1000,2);
                        $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarif PVP: '.$tarifa_venta[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    }
                    else if($tarifa=='base'){
                        $tarifa_venta[$k]=number_format($tarifa_venta[$k]/1000*(1-$iva[$k]/100000),2);
                        $pdf->Cell(0,$alturaTexto,iconv('UTF-8', 'windows-1252', 'Tarif base: '.$tarifa_venta[$k].' €/'.$tipo_unidad[$k]),0,0,'L');
                    }
                }
       
            }
            
          }
        
        }
        
            $hoy=date('Y m d');
       $pdf->Output('F',iconv('UTF-8', 'windows-1252', "uploads/catalogos/Catalogo ").$hoy." fr.pdf");  
        $pdf->Output('D',iconv('UTF-8', 'windows-1252', "Catálogo ").$hoy." fr .pdf");
        
        
   }
 
 
    function generarCatalogo($modelo="profesionales"){
        
        $sql="SELECT * FROM pe_marcas ORDER BY marca";
        $result=$this->db->query($sql)->result();
        $marcas=array();
        $ids=array();
        $n=array();
        $orden=array();
        $descuentos=array();
        $descuentos_en=array();
        $descuentos_fr=array();
        foreach($result as $k=>$v){
            $marcas[]=$v->marca;
            $ids[]=$v->id;
            $sql="SELECT count(*) as n FROM pe_productos WHERE cat_marca='".$v->id."'";
            $n[]=$this->db->query($sql)->row()->n;
            $orden[]=$k;
             $descuentos[]=$v->descuento/1000;
             $descuentos_en[]=$v->descuento_en/1000;
             $descuentos_fr[]=$v->descuento_fr/1000;
            
        }
        $dato['marcas']=$marcas;
        $dato['ids']=$ids;
        $dato['n']=$n;
        $dato['modelo']=$modelo;
        $dato['orden']=$orden;
        $dato['descuentos']=$descuentos;
        $dato['descuentos_en']=$descuentos_en;
        $dato['descuentos_fr']=$descuentos_fr;
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('generarCatalogo',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal');
    }
    
     
}