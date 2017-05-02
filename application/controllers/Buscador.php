<?php

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
        $this->load->library('session');
    }

    public function search_unidad_instituto()
    {
        if ($this->input->post())
        {
            //$keyword = 'COO';
            $this->load->model('Usuario_model', 'usuario');
            $keyword = $this->input->post('keyword', true);
            $keyword = strtolower($keyword);
            $usuario = $this->session->userdata('usuario');
            if(is_nivel_central($usuario['grupos'])){
                $output['unidades'] = $this->usuario->lista_unidad($keyword);
            }else{
                $output['unidades'] = $this->usuario->lista_unidad($keyword, $usuario['id_tipo_unidad']);
            }
            echo $this->load->view('buscador/unidades_instituto', $output, true);
        }
    }

    public function search_categoria()
    {
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
    
    public function search_grupos_categorias(){
        if($this->input->post()){
            $this->load->model('Buscador_model', 'buscador');
            $grupos_categorias = $this->buscador->get_grupos_categorias($this->input->post());
            echo json_encode($grupos_categorias);
        }
    }
}
