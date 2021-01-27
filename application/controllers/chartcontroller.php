<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
/**
 * @author http://roytuts.com
 */
class ChartController extends CI_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->model('chartmodel', 'chart');
    }
 
    public function index() {
        $this->load->model('listados_', 'listados');
        $año=2014;
        for($m=3;$m<=12;$m++){
            $mes=strlen($m)==1?"0".$m:$m;
        echo "Total $año $mes ".($this->listados->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6("$año-$m-01","$año-$m-31",'pe_boka')->totales+$this->listados->getDatosVentasBokaTipoBaseIvaTotalTotales("$año-$m-01","$año-$m-31",'pe_boka')->totales)/100;
        echo "<br>";
        
        }
        $año=2015;
        for($m=1;$m<=12;$m++){
            $mes=strlen($m)==1?"0".$m:$m;
        echo "Total $año $mes ".($this->listados->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6("$año-$m-01","$año-$m-31",'pe_boka')->totales+$this->listados->getDatosVentasBokaTipoBaseIvaTotalTotales("$año-$m-01","$año-$m-31",'pe_boka')->totales)/100;
        echo "<br>";
        
        }
        $año=2016;
        for($m=1;$m<=12;$m++){
            $mes=strlen($m)==1?"0".$m:$m;
        echo "Total $año $mes ".($this->listados->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6("$año-$m-01","$año-$m-31",'pe_boka')->totales+$this->listados->getDatosVentasBokaTipoBaseIvaTotalTotales("$año-$m-01","$año-$m-31",'pe_boka')->totales)/100;
        echo "<br>";
        
        }
        
        
        $results = $this->chart->get_chart_data();
        $data['chart_data'] = $results['chart_data'];
        $data['min_year'] = $results['min_year'];
        $data['max_year'] = $results['max_year'];
        $this->load->view('chart', $data);
    }
 
}