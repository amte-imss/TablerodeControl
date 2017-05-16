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
        //pr($this->session->userdata('usuario'));
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
                    $filtros['delegacion'] = $output['usuario']['id_delegacion'];
                }
                $datos = $this->comparativa->get_comparar_perfil($filtros);
                echo json_encode($datos);
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
                $delegacion = $output['usuario']['id_delegacion'];
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
                    $filtros['delegacion'] = $output['usuario']['id_delegacion'];
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
                $delegacion = $output['usuario']['id_delegacion'];
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
        $output['usuario'] = $this->session->userdata('usuario');
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $output += $cat_list->obtener_catalogos(array(
            Catalogo_listado::REGIONES,
            Catalogo_listado::DELEGACIONES => array('condicion' => array('id_region' => $output['usuario']['id_region'])),)
        );
        $view = $this->load->view('comparative/umae', $output, true);
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Comparativa por UMAE');
        $this->template->getTemplate();
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
                    $filtros['delegacion'] = $output['usuario']['id_delegacion'];
                }
                $datos = $this->comparativa->get_comparar_perfil($filtros);
                echo json_encode($datos);
            } else
            {
                pr(validation_errors());
            }
        } else
        {
            $this->load->model('Ranking_model', 'ranking');
            $output['niveles'] = dropdown_options($this->comparativa->get_niveles(), 'nivel_atencion', 'nivel_atencion');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $delegacion = 0;
            $condiciones_unidad = array('umae' => true);
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $delegacion = $output['usuario']['id_delegacion'];
                $condiciones_unidad += array('id_delegacion' => $delegacion);
            }
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(true, $delegacion, $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
            $output['no_edit_tipo_unidad'] = is_nivel_operacional($output['usuario']['grupos']);
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::UNIDADES_INSTITUTO => array(
                    'condicion' => $condiciones_unidad,
                    /* 'valor' => "concat(nombre,' [',clave_unidad, ']')") */
                    'valor' => 'nombre')
                    )
            );

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
                    $filtros['delegacion'] = $output['usuario']['id_delegacion'];
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
            $condiciones_unidad = array('umae' => true);
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $delegacion = $output['usuario']['id_delegacion'];
                $condiciones_unidad += array('id_delegacion' => $delegacion);
            }
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(true, $delegacion, $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
            $output['no_edit_tipo_unidad'] = is_nivel_operacional($output['usuario']['grupos']);
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::UNIDADES_INSTITUTO => array(
                    'condicion' => $condiciones_unidad,
                    /* 'valor' => "concat(nombre,' [',clave_unidad, ']')") */
                    'valor' => 'nombre'),)
            );
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
//            pr($data['comparativa']);
//            pr($usuario);
            
            foreach ($data['comparativa'] as $key => $value)
            {
                if ($value['region'] == $usuario['name_region'])
                {
                    $data['comparativa'][$key]['region'] = format_label_icon($data['comparativa'][$key]['region']);
                }
            }
            
        }

        $this->template->setBlank("comparative/region.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/index.tpl.php");
        //$this->output->enable_profiler(true);
    }

    public function delegacion_v2()
    {
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
        $output["texts"] = $this->lang->line('delegacion'); //Mensajes
        if ($this->input->post('view'))
        {
            $condiciones_del = array();
            if (is_nivel_tactico($output['usuario']['grupos']) || is_nivel_estrategico($output['usuario']['grupos']))
            {
                $condiciones_del['id_region'] = $output['usuario']['id_region'];
            }
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS, Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::DELEGACIONES => array('condicion' => $condiciones_del))
            );
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
            $this->load->view('comparative/' . $vista, $output);
        } else if ($this->input->post('tipo_comparativa'))
        {
            $filtros = $this->input->post();
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $filtros['region'] = $output['usuario']['id_region'];
            }
            if(is_nivel_central($output['usuario']['grupos']) && $this->input->post('umae')){
                $filtros['umae'] = $this->input->post('umae', true) == 1;
            }
            $datos = $this->comparativa->get_comparar_delegacion($filtros);
            echo json_encode($datos);
        } else
        {
            $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
            $this->template->setTitle($output["texts"]["title"]);
            $this->template->setSubTitle($output["texts"]["subtitle"]);
            $this->template->setDescripcion($output["texts"]["descripcion"]);
            $view = $this->load->view('comparative/delegacion_v2', $output, true);
            $this->template->setMainContent($view);
            $this->template->getTemplate();
        }
    }

    public function delegacion($num = null, $year = 2016, $type = null, $region = 0)
    {
        //1. modificar plantilla con campos y gráfica estática
        //2. generar querys para reporte
        //3. generar json dinamico
        //4. obtener datos para campos y campos relacionados
        //5. aplicar filtros
        // $user_data = $this->session->userdata('usuario');
        // pr($user_data);

        $data["texts"] = $this->lang->line('delegacion'); //Mensajes
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
            Catalogo_listado::SUBCATEGORIAS,
            Catalogo_listado::REGIONES,
        ));
        $data["catalogos"]["reporte"] = array(
            "tc" => "Tipo de curso",
            "p" => "Perfil"
        );
        //solo NC
        $data["catalogos"]["regiones"][0] = "Todas las regiones";
        $data["catalogos"]["regiones"]['promedio'] = "Promedio";



        if (!is_null($num) && !is_null($type))
        {
            $usuario = $this->session->userdata('usuario');
            $umae = null;
            if (!empty($usuario['umae']))
            {
                $umae = $usuario['umae'];
            }
            $this->load->model("Comparativa_model", "comp");
            $data["filters"]["type"] = $data["catalogos"]["reporte"][$type];
            $data["filters"]["year"] = $year;
            $data["filters"]["region"] = $data["catalogos"]["regiones"][$region];
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
                $data["filters"]["num"] = $data['catalogos']["tipos_cursos"][$num];
            }


            //$data["filters"]["num"] = $data["filters"]["type"] == 'tc' ? ;
            $data["comparativa"] = $this->comp->get_comparativa_delegacion($num, $year, $type, $region, $umae);
            //pr($usuario);
            foreach ($data['comparativa'] as $key => $value)
            {
                if ($value['delegacion'] == $usuario['name_delegacion'])
                {
                    $data['comparativa'][$key]['delegacion'] = format_label_icon($data['comparativa'][$key]['delegacion']);
                }
            }
            //pr($data['comparativa']);
        }

        $this->template->setBlank("comparative/delegacion.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/index.tpl.php");
//        $this->output->enable_profiler(true);
    }

}
