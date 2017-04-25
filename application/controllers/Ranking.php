<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ranking
 *
 * @author chrigarc
 */
class Ranking extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->model('Ranking_model', 'ranking');
    }

    public function index(){
        $output = array();
        $output['regiones'] = dropdown_options($this->ranking->get_regiones(), 'id_region', 'nombre');
        $output['usuario'] = $this->session->userdata('usuario');
        if(!empty($output['usuario']['id_region'])){
            $output['delegaciones'] = dropdown_options($this->ranking->get_delegaciones($output['usuario']['id_region']), 'id_delegacion', 'nombre');
        }else{
            $output['delegaciones'] = [];
        }
        if(!empty($output['usuario']['id_delegacion'])){
            $output['tipos_unidades'] = dropdown_options($this->ranking->get_tipo_unidad_by_delegacion($output['usuario']['id_delegacion']), 'id_tipo_unidad', 'nombre');
        }else{
            $output['tipos_unidades'] = [];
        }
        if(!empty($output['usuario']['id_unidad_instituto'])){
            $output['cursos'] = dropdown_options($this->ranking->get_cursos_by_delegacion($output['usuario']['id_unidad_instituto']), 'id_curso', 'nombre');
        }else{
            $output['cursos'] = [];
        }
//        $output['usuario']['umae'] = true;
//        pr($output['usuario']);
        if($output['usuario']['umae']){
            $output['view_filtros'] = $this->load->view('ranking/view_filtros_umae', $output, true);
        }else{
            $output['view_filtros'] = $this->load->view('ranking/view_filtros', $output, true);
        }
        $main_content = $this->load->view('ranking/index', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }
    
    public function get_data(){
        if($this->input->post()){
            $id_curso = $this->input->post('curso', true);
            $output['titulo'] = 'Aprobados';
            $output['datos'] = $this->ranking->get_lista_aprobados($id_curso);
            $tabla1 = $this->load->view('ranking/tabla', $output, true);
            $output['titulo'] = 'Eficiencia terminal';
            $output['datos'] = $this->ranking->get_lista_etm( $id_curso);
            $tabla2 = $this->load->view('ranking/tabla', $output, true);
            echo $tabla1.'<br>'.$tabla2;
        }
    }
}
