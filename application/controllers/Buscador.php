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

    public function get_delegaciones($id_region = 0)
    {
        $this->load->model('Ranking_model', 'ranking');
        $delegaciones = $this->ranking->get_delegaciones($id_region);
        echo json_encode($delegaciones);
    }

    public function get_tipo_unidades_by_delegacion($id_delegacion = 0)
    {
        $this->load->model('Ranking_model', 'ranking');
        $tipos = $this->ranking->get_tipo_unidad_by_delegacion($id_delegacion);
        echo json_encode($tipos);
    }

    public function get_tipo_unidades_by_region($id_region = 0)
    {
        $this->load->model('Ranking_model', 'ranking');
        $tipos = $this->ranking->get_tipo_unidad_by_delegacion($id_region);
        echo json_encode($tipos);
    }

    public function get_unidades($id_delegacion = 0, $id_tipo_unidad = 0)
    {
        $this->load->model('Ranking_model', 'ranking');
        $unidades = $this->ranking->get_unidades($id_delegacion, $id_tipo_unidad);
        echo json_encode($unidades);
    }

    public function get_cursos_by_delegacion($id_delegacion = 0, $id_tipo_unidad = 0)
    {
        $this->load->model('Ranking_model', 'ranking');
        $cursos = $this->ranking->get_cursos_by_delegacion($id_delegacion, $id_tipo_unidad);
        echo json_encode($cursos);
    }

    public function get_cursos_by_region($id_region = 0, $id_tipo_unidad = 0)
    {
        $this->load->model('Ranking_model', 'ranking');
        $cursos = $this->ranking->get_cursos_by_region($id_region, $id_tipo_unidad);
        echo json_encode($cursos);
    }
}
