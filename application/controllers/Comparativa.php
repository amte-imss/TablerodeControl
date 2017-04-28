<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que genera reporte dashboard de nivel central y de delegacionales
 * @version : 1.0.0
 * @autor : Miguel Guagnelli
 */
class Comparativa extends MY_Controller
{

    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
        $this->lang->load('comparativa', 'spanish');
        $this->lang->load('interface'); //Cargar archivo de lenguaje
        $this->load->model('Comparativa_model', 'comparativa');
    }

    public function index()
    {
        /*
          1. generar plantilla con gRAFICO
          2. generar consulta
          3. generar gráfica con query
          4. generar filtros
          5.integrar filtros
          6. integrar sesión con filtro
         */
        $data["texts"] = $this->lang->line('formulario'); //Mensajes de respuesta
        //pr($data);

        $this->template->setTitle($data["texts"]["title"]);

        $this->template->setSubTitle($data["texts"]["subtitle"]);
        $this->template->setDescripcion($data["texts"]["descripcion"]);

        $this->template->setBlank("comparative/index.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/index.tpl.php");
    }

    public function umae()
    {
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
        $view = $this->load->view('comparative/umae', $output, true);
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Comparativa por UMAE');
        $this->template->getTemplate();
    }

    public function umae_perfil()
    {
        if ($this->input->post())
        {
            $datos = $this->comparativa->get_comparar_perfil($this->input->post());
            echo json_encode($datos);
        } else
        {
            $this->load->library('Catalogo_listado');
            $this->load->model('Ranking_model', 'ranking');
            $output['usuario'] = $this->session->userdata('usuario');
            $output['periodos'] = $this->ranking->get_periodos();
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::UNIDADES_INSTITUTO => array(
                    'condicion' => 'umae=true', 'valor' => "concat(nombre,' [',clave_unidad, ']')")
                    )
            );
            $view = $this->load->view('comparative/umae_perfil', $output);
        }
    }

    public function umae_tipo_curso()
    {
        if ($this->input->post())
        {
            $datos = $this->comparativa->get_comparar_tipo_curso($this->input->post());
            echo json_encode($datos);
        } else
        {
            $this->load->library('Catalogo_listado');
            $this->load->model('Ranking_model', 'ranking');
            $output['usuario'] = $this->session->userdata('usuario');
            $output['periodos'] = $this->ranking->get_periodos();
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::UNIDADES_INSTITUTO => array(
                    'condicion' => 'umae=true', 'valor' => "concat(nombre,' [',clave_unidad, ']')")
                    )
            );
            $view = $this->load->view('comparative/umae_tipo_curso', $output, false);
        }
    }

}

?>