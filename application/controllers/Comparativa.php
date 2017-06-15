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
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();

        $output['usuario'] = $this->session->userdata('usuario');
        if ($this->input->post('vista'))
        {
            $filtros = $this->input->post();
            if (!isset($filtros['periodo']) || $filtros['periodo'] == "")
            {
                $periodo = date("Y");
            } else
            {
                $periodo = $filtros['periodo'];
            }
            $filtros['agrupamiento'] = 1; //activamos el agrupamiento            
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento') == 0)
            {
                $filtros['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central                
            }

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS => array('condicion'=>'id_subcategoria > 1'), Catalogo_listado::TIPOS_CURSOS)
            );
            $output['agrupamiento'] = $filtros['agrupamiento'];
            $output['niveles'] = $this->comparativa->get_niveles();
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false), 'id_tipo_unidad', 'nombre');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            switch ($this->input->post('vista', true))
            {
                case 1:
                    $vista = 'unidad_tipo_curso';
                    break;
                case 2:
                    $vista = 'unidad_perfil';
                    break;
            }
            $output['vista'] = $this->load->view('comparative/' . $vista, $output, true);
        }
        if ($this->input->post('tipo_comparativa'))
        {
            $output['datos'] = $datos = $this->comparativa->get_comparar_unidad($filtros);
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        }

        if ($this->input->is_ajax_request())
        {
            echo $output['vista'];
        } else
        {

            $view = $this->load->view('comparative/unidades', $output, true);
            $this->template->setDescripcion($this->mostrar_datos_generales());
            $this->template->setMainContent($view);
            $this->template->setSubTitle(render_subtitle('Comparativa por Unidades Instituto', 'comparativa_unidades'));
            $this->template->getTemplate();
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
            $filtros = $this->input->post();
            if (!isset($filtros['periodo']) || $filtros['periodo'] == "")
            {
                $periodo = date("Y");
            } else
            {
                $periodo = $filtros['periodo'];
            }
            $filtros['agrupamiento'] = 1; //activamos el agrupamiento            
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento') == 0)
            {
                $filtros['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central
                $opciones_umae = array(
                    'llave' => 'id_unidad_instituto',
                    'valor' => 'nombre',
                    'condicion' => "(grupo_tipo_unidad = 'UMAE' or grupo_tipo_unidad = 'CUMAE') and anio = {$periodo}",
                    'group' => array('id_unidad_instituto', 'nombre'),
                    'orden' => 'nombre'
                );
            } else
            {
                $opciones_umae = array(
                    'llave' => 'nombre_unidad_principal',
                    'valor' => 'nombre_unidad_principal',
                    'condicion' => "grupo_tipo_unidad = 'UMAE' and anio = {$periodo}",
                    'group' => array('nombre_unidad_principal'),
                    'orden' => 'nombre_unidad_principal'
                );
            }

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS  => array('condicion'=>'id_subcategoria > 1'), Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::UNIDADES_INSTITUTO => $opciones_umae)
            );
            $output['agrupamiento'] = $filtros['agrupamiento'];
            $output['niveles'] = $this->comparativa->get_niveles();
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
            $output['datos'] = $datos = $this->comparativa->get_comparar_umae($filtros);
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        }

        if ($this->input->is_ajax_request())
        {
            echo $output['vista'];
        } else
        {

            $view = $this->load->view('comparative/umae', $output, true);
            $this->template->setDescripcion($this->mostrar_datos_generales());
            $this->template->setMainContent($view);
            $this->template->setSubTitle(render_subtitle('Comparativa por UMAE', 'comparativa_umae'));
            $this->template->getTemplate();
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

        $this->template->setSubTitle(render_subtitle($data["texts"]["subtitle"], 'comparativa_regiones'));
        $this->template->setDescripcion($data["texts"]["descripcion"]);

        $data["catalogos"]["perfil"] = $this->nom->get_perfil();
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $data['catalogos'] += $cat_list->obtener_catalogos(array(
            Catalogo_listado::TIPOS_CURSOS,
            Catalogo_listado::IMPLEMENTACIONES => array(
                'valor' => 'EXTRACT(year FROM fecha_fin)',
                'llave' => 'DISTINCT(EXTRACT(year FROM fecha_fin))',
                'orden' => '1 DESC'),
            Catalogo_listado::SUBCATEGORIAS  => array('condicion'=>'id_subcategoria > 1')
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
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento') == 0)
            {
                $filtros_delegacion['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central
            }

            if ($filtros_delegacion['agrupamiento'] == 1)
            {
                $opciones_delegaciones = array(
                    'llave' => 'grupo_delegacion',
                    'valor' => 'nombre_grupo_delegacion',
                    'group' => array('grupo_delegacion', 'nombre_grupo_delegacion'),
                    'orden' => 'nombre_grupo_delegacion'
                );
            } else
            {
                $opciones_delegaciones = array(
                    'llave' => 'id_delegacion',
                    'valor' => 'nombre',
                    'orden' => 'nombre'
                );
            }
//            pr($opciones_delegaciones);

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS  => array('condicion'=>'id_subcategoria > 1'), Catalogo_listado::TIPOS_CURSOS,
                Catalogo_listado::DELEGACIONES => $opciones_delegaciones)
            );
            $output['agrupamiento'] = $filtros_delegacion['agrupamiento'];
            $output['niveles'] = $this->comparativa->get_niveles();
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

        if ($this->input->is_ajax_request())
        {
            echo $output['vista'];
        } else
        {
            $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
            $this->template->setTitle($output["texts"]["title"]);
            $this->template->setSubTitle(render_subtitle($output["texts"]["subtitle"], 'comparativa_delegaciones'));
            $this->template->setDescripcion($output["texts"]["descripcion"]);
            $view = $this->load->view('comparative/delegacion_v2', $output, true);
            $this->template->setMainContent($view);
            $this->template->getTemplate();
        }
    }

}
