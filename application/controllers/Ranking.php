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
        $this->lang->load('ranking', 'spanish');
    }

    public function index()
    {
        $output = array();
        $output['lenguaje'] = $this->lang->line('index');
        $output['usuario'] = $this->session->userdata('usuario');        
        if (is_nivel_central($output['usuario']['grupos']))
        {
            $output['usuario']['central'] = true;
        }
        $output['programas'] = dropdown_options($this->ranking->get_programas(), 'id_programa_proyecto', 'proyecto');
        $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
        $output['graficas'] = $this->ranking->get_tipos_reportes();
        $this->template->setTitle($output['lenguaje']['title']);
        $this->template->setSubTitle($output['lenguaje']['subtitle']);
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $main_content = $this->load->view('ranking/index', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
        //$this->output->enable_profiler(true);
    }

    public function get_data()
    {
        if ($this->input->post())
        {
            $usuario = $this->session->userdata('usuario');
            if ($this->input->post('umae', true))
            {
                $usuario['umae'] = true;
            }
            $datos = $this->ranking->get_data($usuario, $this->input->post());
//            pr($this->db->last_query());
            echo json_encode($datos);
            //$this->output->enable_profiler(true);
        }
    }

}
