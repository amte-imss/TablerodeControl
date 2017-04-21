<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Buscador
 *
 * @author chrigarc
 */
class Buscador extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function search_unidad_instituto()
    {
        if ($this->input->post())
        {
            //$keyword = 'COO';
            $this->load->model('Usuario_model', 'usuario');
            $keyword = $this->input->post('keyword', true);
            $keyword = strtolower($keyword);
            $output['unidades'] = $this->usuario->lista_unidad($keyword);
            echo $this->load->view('buscador/unidades_instituto', $output, true);
        }
    }

    public function search_categoria(){
        if ($this->input->post())
        {
            //$keyword = 'COO';
            $this->load->model('Usuario_model', 'usuario');
            $keyword = $this->input->post('keyword', true);
            $keyword = strtolower($keyword);
            $output['categorias'] = $this->usuario->lista_categoria($keyword);
            echo $this->load->view('buscador/categorias', $output, true);
        }
    }
}
