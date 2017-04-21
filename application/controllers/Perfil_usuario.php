<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perfil_usuario
 *
 * @author chrigarc
 */
class Perfil_usuario extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('seguridad');
    }
    
    public function index(){
        $usuario = $this->session->userdata('usuario');
        $output = array();
        $main_content = $this->load->view('perfil_usuario/index', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }
}
