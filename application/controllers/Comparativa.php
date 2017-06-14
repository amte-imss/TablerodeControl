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
        $this->load->library('Catalogo_listado');
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
//        pr($this->session->userdata('usuario'));
        $output['usuario'] = $this->session->userdata('usuario');
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $output += $cat_list->obtener_catalogos(array(
            Catalogo_listado::REGIONES,
            Catalogo_listado::DELEGACIONES => array('condicion' => array('id_region' => $output['usuario']['id_region'])))
        );
        $view = $this->load->view('comparative/unidades', $output, true);
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Comparativa por Unidades Instituto');
        $this->template->getTemplate();
    }

    public function unidades_perfil()
    {
        $output['usuario'] = $this->session->userdata('usuario');
        if ($this->input->post() && $this->input->post('vista', true) == null)
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_perfil'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $filtros = $this->input->post();
                if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
                {
                    $filtros['delegacion'] = $output['usuario']['grupo_delegacion'];
                }
                $datos = $this->comparativa->get_comparar_perfil($filtros);
                echo json_encode($datos);
                //$this->output->enable_profiler(TRUE);
            }
        } else
        {
            $this->load->model('Ranking_model', 'ranking');
            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $delegacion = 0;
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $delegacion = $output['usuario']['grupo_delegacion'];
            }
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, $delegacion, $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
            $output['no_edit_tipo_unidad'] = is_nivel_operacional($output['usuario']['grupos']);
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,)
            );
            $view = $this->load->view('comparative/unidad_perfil', $output);
        }
    }

    public function unidades_tipo_curso()
    {
        $output['usuario'] = $this->session->userdata('usuario');
        if ($this->input->post() && $this->input->post('vista', true) == null)
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_tipo_curso'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $filtros = $this->input->post();
                if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
                {
                    $filtros['delegacion'] = $output['usuario']['grupo_delegacion'];
                }
                $datos = $this->comparativa->get_comparar_tipo_curso($filtros);
                echo json_encode($datos);
            }
        } else
        {
            $this->load->model('Ranking_model', 'ranking');

            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $delegacion = 0;
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $delegacion = $output['usuario']['grupo_delegacion'];
            }
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, $delegacion, $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
            $output['no_edit_tipo_unidad'] = is_nivel_operacional($output['usuario']['grupos']);
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
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
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();        
        
        $output['usuario'] = $this->session->userdata('usuario');        
        if ($this->input->post('vista'))
        {
            $filtros_umae = array();
            $filtros_umae['agrupamiento'] = 1; //activamos el agrupamiento            
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null &&$this->input->post('agrupamiento') == 0)
            {                
                $filtros_umae['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central
            }
                                   
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS)
            );
            $output['agrupamiento'] = $filtros_umae['agrupamiento'];
            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false), 'id_tipo_unidad', 'nombre');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            switch ($this->input->post('vista', true))
            {
                case 1:
                    $vista = 'umae_tipo_curso';
                    break;
                case 2:
                    $vista = 'umae_perfil';
                    break;
            }
            $output['vista'] = $this->load->view('comparative/' . $vista, $output, true);
        }
        if ($this->input->post('tipo_comparativa'))
        {
            $filtros = $this->input->post();         
            $filtros['agrupamiento'] = 1;
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento', true) == 0)
            {
                $filtros['agrupamiento'] = 0;
            }
            $output['datos'] = $datos = $this->comparativa->get_comparar_delegacion($filtros);            
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        } 
        
        if($this->input->is_ajax_request()){
            echo $output['vista'];
        }else{
        
        $view = $this->load->view('comparative/umae', $output, true);
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Comparativa por UMAE');
        $this->template->getTemplate();
        }
    }

    public function umae_perfil()
    {
        $output['usuario'] = $this->session->userdata('usuario');
        if ($this->input->post() && $this->input->post('vista', true) == null)
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_perfil'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);
            if ($this->form_validation->run() == TRUE)
            {
                $filtros = $this->input->post() + array('umae' => true);
                if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
                {
                    $filtros['delegacion'] = $output['usuario']['grupo_delegacion'];
                }
                $filtros['agrupamiento'] = 0;
                if(is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') &&  $this->input->post('agrupamiento',true) == 1){
                    $filtros['agrupamiento'] = 1;
                }
                $datos = $this->comparativa->get_comparar_perfil($filtros);
                echo json_encode($datos);
            } else
            {
                pr(validation_errors());
            }
        } else
        {
            $this->load->model('Buscador_model', 'buscador');
            $this->load->model('Ranking_model', 'ranking');
            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $delegacion = 0;
            $condiciones_unidad = array('umae' => true, 'agrupamiento' => 0);
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $delegacion = $output['usuario']['grupo_delegacion'];
                $condiciones_unidad += array('id_delegacion' => $delegacion);
            }
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') && $this->input->post('agrupamiento', true) == 1)
            {                
                $condiciones_unidad['agrupamiento'] =  1;
            }
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(true, $delegacion, $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
            $output['no_edit_tipo_unidad'] = is_nivel_operacional($output['usuario']['grupos']);
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,)
            );
            $output['unidades_instituto'] = dropdown_options($this->buscador->get_unidades($condiciones_unidad), 'id_unidad_instituto', 'nombre');
            $view = $this->load->view('comparative/umae_perfil', $output);
        }
    }

    public function umae_tipo_curso()
    {
        $output['usuario'] = $this->session->userdata('usuario');
        if ($this->input->post() && $this->input->post('vista', true) == null)
        {

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('filtros_comparativa_tipo_curso'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);            
            if ($this->form_validation->run() == TRUE)
            {
                $filtros = $this->input->post() + array('umae' => true);
                if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
                {
                    $filtros['delegacion'] = $output['usuario']['grupo_delegacion'];
                }
                $filtros['agrupamiento'] = 0;
                if(is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') &&  $this->input->post('agrupamiento',true) == 1){
                    $filtros['agrupamiento'] = 1;
                }
                $datos = $this->comparativa->get_comparar_tipo_curso($filtros);
                echo json_encode($datos);
            }else
            {
                pr(validation_errors());
            }
        } else
        {
            $this->load->model('Buscador_model', 'buscador');
            $this->load->model('Ranking_model', 'ranking');
            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $delegacion = 0;
            $condiciones_unidad = array('umae' => true, 'agrupamiento' => 0);
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $delegacion = $output['usuario']['grupo_delegacion'];
                $condiciones_unidad += array('id_delegacion' => $delegacion);
            }            
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') && $this->input->post('agrupamiento', true) == 1)
            {                
                $condiciones_unidad['agrupamiento'] =  1;
            }
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(true, $delegacion, $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
            $output['no_edit_tipo_unidad'] = is_nivel_operacional($output['usuario']['grupos']);
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,)
            );
            $output['unidades_instituto'] = dropdown_options($this->buscador->get_unidades($condiciones_unidad), 'id_unidad_instituto', 'nombre');
            $view = $this->load->view('comparative/umae_tipo_curso', $output, false);
        }
    }

    public function region($num = null, $year = null, $type = null)
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

        $data["catalogos"]["perfil"] = $this->nom->get_perfil();
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $data['catalogos'] += $cat_list->obtener_catalogos(array(
            Catalogo_listado::TIPOS_CURSOS,
            Catalogo_listado::IMPLEMENTACIONES => array(
                'valor' => 'EXTRACT(year FROM fecha_fin)',
                'llave' => 'DISTINCT(EXTRACT(year FROM fecha_fin))',
                'orden' => '1 DESC'),
            Catalogo_listado::SUBCATEGORIAS
        ));
        $data["catalogos"]["reporte"] = array(
            "tc" => "Tipo de curso",
            "p" => "Perfil"
        );


        if (!is_null($num) && !is_null($year) && !is_null($type))
        {
            $usuario = $this->session->userdata('usuario');
            $this->load->model("Comparativa_model", "comp");
            $data["filters"]["type"] = $data["catalogos"]["reporte"][$type];
            $data["filters"]["year"] = $year;
            if ($type == 'p')
            {
                $cat = $cat_list->obtener_catalogos(array(Catalogo_listado::GRUPOS_CATEGORIAS => array(
                        'valor' => 'id_grupo_categoria,descripcion',
                        'condicion' => "id_grupo_categoria = $num"
                    )
                        )
                );
                $data["filters"]["num"] = $cat["grupos_categorias"][$num];
            } elseif ($type == 'tc')
            {
                $data["filters"]["num"] = $data['catalogos']["tipos_cursos"][$num];
            }


            //$data["filters"]["num"] = $data["filters"]["type"] == 'tc' ? ;
            $data["comparativa"] = $this->comp->get_comparativa_region($num, $year, $type);
            $data['usuario'] = $usuario;
//            pr($data['comparativa']);
//            pr($usuario);
        }

        $this->template->setBlank("comparative/region.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/index.tpl.php");
//        $this->output->enable_profiler(true);
    }

    public function delegacion_v2()
    {
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
//        pr($output['usuario']);
        $output["texts"] = $this->lang->line('delegacion'); //Mensajes
        if ($this->input->post('view'))
        {
            $filtros_delegacion = array();
            $filtros_delegacion['agrupamiento'] = 1; //activamos el agrupamiento
            if (is_nivel_tactico($output['usuario']['grupos']) || is_nivel_estrategico($output['usuario']['grupos']))
            {
                $filtros_delegacion['id_region'] = $output['usuario']['id_region'];
            }
//            pr($this->input->post('agrupamiento'));
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null &&$this->input->post('agrupamiento') == 0)
            {                
                $filtros_delegacion['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central
            }
            
            if($filtros_delegacion['agrupamiento'] == 1){
                $opciones_delegaciones = array(
                    'llave' => 'grupo_delegacion', 
                    'valor' => 'nombre_grupo_delegacion', 
                    'group' => array('grupo_delegacion', 'nombre_grupo_delegacion'), 
                    'orden' => 'nombre_grupo_delegacion'
                );
            }else{
                $opciones_delegaciones = array(
                    'llave' => 'id_delegacion', 
                    'valor' => 'nombre',                     
                    'orden' => 'nombre'
                );
            }
//            pr($opciones_delegaciones);
            
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS, 
                Catalogo_listado::DELEGACIONES => $opciones_delegaciones)
            );
            $output['agrupamiento'] = $filtros_delegacion['agrupamiento'];
            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false), 'id_tipo_unidad', 'nombre');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            switch ($this->input->post('view', true))
            {
                case 1:
                    $vista = 'delegacion_tipo_curso';
                    break;
                case 2:
                    $vista = 'delegacion_perfil';
                    break;
            }
            $output['vista'] = $this->load->view('comparative/' . $vista, $output, true);
        }
        if ($this->input->post('tipo_comparativa'))
        {
            $filtros = $this->input->post();
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $filtros['region'] = $output['usuario']['id_region'];
                $filtros['delegacion1'] = $output['usuario']['grupo_delegacion'];
            }            
            $filtros['agrupamiento'] = 1;
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento', true) == 0)
            {
                $filtros['agrupamiento'] = 0;
            }
            $output['datos'] = $datos = $this->comparativa->get_comparar_delegacion($filtros);            
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        } 
        
        if($this->input->is_ajax_request()){
            echo $output['vista'];
        }else{
            $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
            $this->template->setTitle($output["texts"]["title"]);
            $this->template->setSubTitle($output["texts"]["subtitle"]);
            $this->template->setDescripcion($output["texts"]["descripcion"]);
            $view = $this->load->view('comparative/delegacion_v2', $output, true);
            $this->template->setMainContent($view);
            $this->template->getTemplate();
        }
    }
}
