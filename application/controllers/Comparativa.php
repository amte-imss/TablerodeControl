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
        $this->load->model("Nomina_model", "nom");
        $this->lang->load('interface'); //Cargar archivo de lenguaje
        $this->load->model('Comparativa_model', 'comparativa');
        $this->load->library('form_validation');
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

    public function unidades()
    {
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
        $view = $this->load->view('comparative/unidades', $output, true);
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Comparativa por Unidades Instituto');
        $this->template->getTemplate();
    }

    public function unidades_perfil()
    {
        if ($this->input->post())
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_perfil'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $datos = $this->comparativa->get_comparar_perfil($this->input->post());
                echo json_encode($datos);
            }
        } else
        {
            $this->load->library('Catalogo_listado');
            $this->load->model('Ranking_model', 'ranking');
            $output['usuario'] = $this->session->userdata('usuario');
            $output['periodos'] = $this->ranking->get_periodos();
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,)
            );
            $view = $this->load->view('comparative/unidad_perfil', $output);
        }
    }

    public function unidades_tipo_curso()
    {
        if ($this->input->post())
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_tipo_curso'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $datos = $this->comparativa->get_comparar_tipo_curso($this->input->post());
                echo json_encode($datos);
            }
        } else
        {
            $this->load->library('Catalogo_listado');
            $this->load->model('Ranking_model', 'ranking');
            $output['usuario'] = $this->session->userdata('usuario');
            $output['periodos'] = $this->ranking->get_periodos();
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS)
            );
            $view = $this->load->view('comparative/unidad_tipo_curso', $output, false);
        }
    }

    public function umae()
    {
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
        $view = $this->load->view('comparative/umae', $output, true);
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Comparativa por UMAE');
        $this->template->getTemplate();
    }

    public function umae_perfil()
    {
        if ($this->input->post())
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_perfil'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $filtros = $this->input->post() + array('umae' => true);
                $datos = $this->comparativa->get_comparar_perfil($filtros);
                echo json_encode($datos);
            } else
            {
                pr(validation_errors());
            }
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
                    'condicion' => 'umae=true', /* 'valor' => "concat(nombre,' [',clave_unidad, ']')") */
                    'valor' => 'nombre')
                    )
            );
            $view = $this->load->view('comparative/umae_perfil', $output);
        }
    }

    public function umae_tipo_curso()
    {
        if ($this->input->post())
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_tipo_curso'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $filtros = $this->input->post() + array('umae' => true);
                $datos = $this->comparativa->get_comparar_tipo_curso($filtros);
                echo json_encode($datos);
            }
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
                    'condicion' => 'umae=true', /* 'valor' => "concat(nombre,' [',clave_unidad, ']')" */
                    'valor' => 'nombre')
                    )
            );
            $view = $this->load->view('comparative/umae_tipo_curso', $output, false);
        }
    }

    public function region($num = '3',$year='2016', $type=Comparativa_model::PERFIL)
    {
        //1. modificar plantilla con campos y gráfica estática
        //2. generar querys para reporte
        //3. generar json dinamico
        //4. obtener datos para campos y campos relacionados
        //5. aplicar filtros
        $data["texts"] = $this->lang->line('region'); //Mensajes
        $this->template->setTitle($data["texts"]["title"]);

        $this->template->setSubTitle($data["texts"]["subtitle"]);
        $this->template->setDescripcion($data["texts"]["descripcion"]);

        $data["combos"]["perfil"] = $this->nom->get_perfil();
        //$data["combos"]["tipo_perfil"] = $this->nom->get_tipo_perfil();
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $data['combos'] += $cat_list->obtener_catalogos(array(
            Catalogo_listado::TIPOS_CURSOS,
            Catalogo_listado::IMPLEMENTACIONES => array(
                'valor' => 'EXTRACT(year FROM fecha_fin)',
                'llave' => 'DISTINCT(EXTRACT(year FROM fecha_fin))',
                'orden' => '1 DESC'),
            Catalogo_listado::SUBCATEGORIAS,
            //Catalogo_listado::GRUPOS_CATEGORIAS
          ));

        $this->load->model("Comparativa_model","comp");
        $data["comparativa"] = $this->comp->get_comparativa_region($num,$year,$type);

        $this->template->setBlank("comparative/region.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/index.tpl.php");
        // $this->output->enable_profiler(true);

    }

}
