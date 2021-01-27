<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Caja extends CI_Controller {
	 
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                $this->load->model('caja_');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                    
        }
        
        function inicializarCaja() {

        // $this->form_validation->set_rules('cambioNoche', 'Cambio Noche', 'required');
        if ($this->form_validation->run('inicializarCaja') == FALSE) {
            $dato['autor'] = 'Miguel Angel Bañolas';
            $dato = array('error' => ' ');
            $this->load->view('templates/header.html', $dato);
            $dato['activeMenu']='Caja';
            $dato['activeSubmenu']='Inicialización Caja';
            $this->load->view('templates/top.php', $dato);
            $this->load->view('caja/inicializarCaja', $dato);
            $this->load->view('templates/footer.html', $dato);
        } else {
            $resultado = $this->caja_->saveDatosInicio($_POST['fecha'], $_POST['cambioNoche'], $_POST['saldoBanco'], $_POST['diferenciaCajaAcumulada'], $_POST['notas']);         // if($resultado->ticket && $resultado->ticket2)
            //$dato['autor'] = 'Miguel Angel Bañolas';
            $this->load->view('templates/header.html');
            $this->load->view('templates/top.php');
            $this->load->view('caja/inicializarCajaOK');
            $this->load->view('templates/footer.html');
        }
    }

    function cierreCaja(){
            if ($this->form_validation->run('cierreCaja') == FALSE) {
            $dato['autor']='Miguel Angel Bañolas';
            $dato=array('error' => ' ' );
            $this->load->view('templates/header.html',$dato);
            $dato['activeMenu']='Caja';
            $dato['activeSubmenu']='Cierre Caja';
            $this->load->view('templates/top.php',$dato);
            $this->load->view('caja/cierreCaja',$dato );
            $this->load->view('templates/footer.html',$dato);  
             } else {
           // $resultado = $this->caja_->grabarDatosCaja();
            //$dato['autor'] = 'Miguel Angel Bañolas';
            $this->load->view('templates/header.html');
            $this->load->view('templates/top.php');
            $this->load->view('caja/cierreCajaOK');
            $this->load->view('templates/footer.html');
        }
             
        }
        
        function contarDias(){
            $this->load->model('caja_');
            $resultado=$this->caja_->contarDias($_POST['inicio'],$_POST['final']);
            echo  json_encode($resultado);
        }
        
        //obsoleto
        function listaCaja($inicio,$final){
            $dato['autor']='Miguel Angel Bañolas';
            $dato=array('error' => ' ' );
            $this->load->model('caja_');
            $dato['inicio']=$inicio;
            $dato['final']=$final;
            $dato['cierresCaja']=$this->caja_->getCierres($inicio,$final);
            $dato['sumaCierresCaja']=$this->caja_->getSumaCierres($inicio,$final);
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            $this->load->view('caja/listaCaja',$dato );
            $this->load->view('templates/footerContainer.html',$dato);  
        }
        
        function listaCajaGrocery($inicio,$final){
            
            $this->load->library('grocery_CRUD');
            
            
            $crud=$this->grocery_crud;
            $crud->set_table('pe_caja');
            $crud->set_subject('Caja');
            $crud->unset_bootstrap();
            //$crud->unset_jquery();
            $crud->set_theme('bootstrap');
            
            
            $crud->where('fecha >= ', $inicio);
            $crud->where('fecha <= ', $final);
            $crud->order_by('fecha');
            $dato['autor']='Miguel Angel Bañolas';
            $crud->set_language("spanish");
            
            //$crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            
            
           // 
           $crud->unset_read_fields('ventaOtras','retiroOtros','diferenciaOtros');
          //  $crud->unset_operations();
            
            $crud->columns('fecha','cambioManana','ventaMetalico', 'ventaTarjeta','ventaACuenta','ventaOtras', 'retiroMetalico','retiroTarjeta', 'retiroOtros', 'cambioNoche', 'cobroAtrasosMetalico', 'cobroAtrasosTarjeta', 'ventaNoCobrada', 'ventaDia', 'diferenciaMetalico','diferenciaTarjeta', 'diferenciaOtros', 'diferenciaCaja','saldoBanco', 'diferenciaCajaAcumulada');
            $crud->display_as('cambioManana','Cambio Mañana');
            $crud->display_as('ventaMetalico','Venta Metálico');
            $crud->display_as('ventaTarjeta','Venta Tarjeta');
            $crud->display_as('ventaACuenta','Venta A Cuenta');
            $crud->display_as('ventaOtras','Venta Otras');
            $crud->display_as('ventaVale','Venta Vale');
            $crud->display_as('ventaCheque','Venta Cheque');
            $crud->display_as('retiroMetalico','Retiro Metálico');
            $crud->display_as('retiroTarjeta','Retiro Tarjeta');
            $crud->display_as('ventaACuenta','Venta A Cuenta');
            $crud->display_as('retiroOtros','Retiro Otros');
            $crud->display_as('retiroCheque','Retiro Cheque');
            $crud->display_as('retiroVale','Retiro Vale');
            $crud->display_as('cambioNoche','Cambio Noche');
            $crud->display_as('cobroAtrasosMetalico','Atrasos Metálico');
            $crud->display_as('cobroAtrasosTarjeta','Atrasos Tarjeta');
            $crud->display_as('ventaNoCobrada','Venta no cobrada');
            $crud->display_as('ventaDia','Venta Día');
            $crud->display_as('diferenciaMetalico','Dif. Metálico');
            $crud->display_as('diferenciaTarjeta','Dif. Tarjeta');
            $crud->display_as('diferenciaOtros','Dif. Otras');
            $crud->display_as('diferenciaVale','Dif. Vale');
            $crud->display_as('diferenciaTCheque','Dif. Cheque');
            $crud->display_as('diferenciaCaja','Dif. Caja');
            $crud->display_as('diferenciaCajaAcumulada','Dif. Caja Acumulada');
            $crud->display_as('saldoBanco','Pendiente');
            
            $crud->callback_column('cambioManana', array($this, '_column_right_align'));
            $crud->callback_column('ventaMetalico', array($this, '_column_right_align'));
            $crud->callback_column('ventaTarjeta', array($this, '_column_right_align'));
           // $crud->callback_column('ventaTransferencia', array($this, '_column_right_align'));
            $crud->callback_column('retiroMetalico', array($this, '_column_right_align'));
            $crud->callback_column('retiroTarjeta', array($this, '_column_right_align'));
            $crud->callback_column('ventaACuenta', array($this, '_column_right_align'));
            $crud->callback_column('retiroTransferencia', array($this, '_column_right_align'));
            $crud->callback_column('cobroAtrasosMetalico', array($this, '_column_right_align'));
            $crud->callback_column('cobroAtrasosTarjeta', array($this, '_column_right_align'));
            $crud->callback_column('cambioNoche', array($this, '_column_right_align'));
            $crud->callback_column('ventaNoCobrada', array($this, '_column_right_align'));
            $crud->callback_column('ventaDia', array($this, '_column_right_align'));
            $crud->callback_column('diferenciaMetalico', array($this, '_column_right_align'));
            $crud->callback_column('diferenciaTarjeta', array($this, '_column_right_align'));
            $crud->callback_column('diferenciaTransferencia', array($this, '_column_right_align'));
            $crud->callback_column('diferenciaCaja', array($this, '_column_right_align'));
            $crud->callback_column('diferenciaCajaAcumulada', array($this, '_column_right_align'));
            $crud->callback_column('saldoBanco', array($this, '_column_right_align'));
            
            $crud->callback_read_field('cambioManana',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaMetalico',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaTarjeta',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaACuenta',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaTransferencia',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaVale',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaCheque',array($this,'field_callback_cantidad'));
            
            $crud->callback_read_field('retiroMetalico',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('retiroTarjeta',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('retiroTransferencia',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('retiroVale',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('retiroCheque',array($this,'field_callback_cantidad'));
            
            $crud->callback_read_field('cambioNoche',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('cobroAtrasosMetalico',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('cobroAtrasosTarjeta',array($this,'field_callback_cantidad'));
            
            $crud->callback_read_field('ventaNoCobrada',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('ventaDia',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaMetalico',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaTarjeta',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaMetalico',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaTransferencia',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaVale',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaCheque',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaCaja',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('diferenciaCajaAcumulada',array($this,'field_callback_cantidad'));
            $crud->callback_read_field('saldoBanco',array($this,'field_callback_cantidad'));
            
            $crud->callback_read_field('fecha',array($this,'field_callback_fecha'));
            
            

            
          //  $crud->callback_column('ventaTransferencia',array($this,'_callback_venta_otras'));
          //  $crud->callback_column('retiroTransferencia',array($this,'_callback_retiro_otras'));
          //  $crud->callback_column('diferenciaTransferencia',array($this,'_callback_diferencia_otras'));
            
            $crud->callback_column('ventaOtras',array($this,'_callback_venta_otras'));
            $crud->callback_column('retiroOtros',array($this,'_callback_retiro_otras'));
            $crud->callback_column('diferenciaOtros',array($this,'_callback_diferencia_otras'));
            
            
            
            $output = $crud->render();
            
            $output = (array) $output;
            $output['titulo'] = 'Cierres Caja';
            $output['col_bootstrap'] = 12;
            $output = (object) $output;
            $this->_table_output($output);
            
            
        }
        
        
        function _table_output($output = null) {

        $this->load->view('templates/header.php', $output);
        $this->load->view('templates/top.php');
        $this->load->view('table_template_no_buscar.php', $output);
        $this->load->view('templates/footer.html');
    }
    
    function field_callback_cantidad($value = '', $primary_key = null) {
        if($value==0) return "";
        $value=number_format($value/100,2,",",".");
        
        return $value;
    }
    
    function field_callback_fecha($value = '', $primary_key = null) {
        $value=substr(fechaEuropea($value),0,10);
        return $value;
    }

    public function _callback_venta_otras($value, $row) {
        $total = $row->ventaTransferencia + $row->ventaVale + $row->ventaCheque;
        $total/=100;
        $total = $total != 0 ? number_format($total, 2, ".", ",") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$total</span>";
    }

    public function _callback_retiro_otras($value, $row) {
        $total = $row->retiroTransferencia + $row->retiroVale + $row->retiroCheque;
        $total/=100;
        $total = $total != 0 ? number_format($total, 2, ".", ",") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$total</span>";
    }
    
    public function _callback_diferencia_otras($value, $row) {
        $total = $row->diferenciaTransferencia + $row->diferenciaVale + $row->diferenciaCheque;
        $total/=100;
        $total = $total != 0 ? number_format($total, 2, ".", ",") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$total</span>";
    }
    
    function _column_right_align($value, $row) {
        $value/=100;
        $value = $value != 0 ? number_format($value, 2, ",", ".") : "";
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }
    
    public function _read_field($value) {
        
        return "<span class='derecha' style='width:100%;text-align:right;display:block;'>$value</span>";
    }
    
    function informacionCierresCaja(){
            $dato['autor']='Miguel Angel Bañolas';
            $dato=array('error' => ' ' );
            $dato['segmentos']="caja/listaCaja";
            $dato['idBoton']="listaCaja";
            $dato['nombreBoton']="Listado Cierre Caja";
            $dato['buscar']="buscarDiasCierre";
            $dato['buscarTexto']="Buscar datos";
            //$dato['periodosCaja']=$this->load->view('caja/periodosCaja',$dato,true);
            $dato['seleccionPeriodosDerecha']=$this->load->view('caja/seleccionResumenCierres',$dato,true);
            $dato['seleccionPeriodos']=$this->load->view('seleccionPeriodos',$dato,true);
            $this->load->view('templates/header.html',$dato);
            $dato['activeMenu']='Caja';
            $dato['activeSubmenu']='Información Cierres Caja';
            $this->load->view('templates/top.php',$dato);
            $this->load->view('caja/seleccionPeriodoCaja',$dato );
            $this->load->view('myModal',$dato );
            $this->load->view('templates/footer.html',$dato);   
        }
        
        
    function grabarDatosCaja(){
         $this->load->model('caja_');
        $resultado = $this->caja_->grabarDatosCaja();

        echo  json_encode($resultado);
    }

    function buscarDatosCaja(){
        $this->load->model('listados_');
        $resultado=$this->listados_->getDatosVentasBokaCaja($_POST['fecha'],$_POST['fecha']);           // if($resultado->ticket && $resultado->ticket2)
        //$resultadosCalculoCaja=$this->caja_->calculoCaja($resultado);

        $salida=array('categoria'=>$this->session->categoria,'resultado'=>$resultado['totales'],'post'=>$_POST, 'cajaAnterior'=>$resultado['cajaAnterior'],'sqlDV'=>$resultado['sqlDV']);
        echo  json_encode($salida);
    }

    function leerDatosAnterioresCaja(){
        $this->load->model('caja_');
        $resultado = $this->caja_->leerDatosAnterioresCaja($_POST['fecha']);

        echo  json_encode($resultado);
    }

    function leerDatosCierreCaja(){
            $this->load->model('caja_');
            $resultado = $this->caja_->leerDatosCierreCaja($_POST['fecha']);
            
            echo  json_encode($resultado);
        }
        
        
        
}