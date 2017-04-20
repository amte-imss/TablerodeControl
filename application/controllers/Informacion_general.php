<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que genera la pantalla de información general, de los diferentes roles
 * @version : 1.0.0
 * @autor : JZDP
 */
class Informacion_general extends MY_Controller
//class Informacion_general extends CI_Controller
{
    var $anio_actual;
    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
        $this->load->model('Informacion_general_model', 'inf_gen_model');
        $this->lang->load('interface'); //Cargar archivo de lenguaje
        $this->set_periodo_actual();
        
        $usuario['nombre'] = 'Fermin Reyes Chavez';
        $usuario['matricula'] = '99156322';
        $usuario['del_cve'] = '02';
        $usuario['del_nom'] = 'BAJA CALIFORNIA';
        $usuario['curp'] = 'RECF850214HDFYHR02';
        $usuario['unidad_cve'] = 1;
        $usuario['unidad_nom'] = 'COORDINACION REGIONAL AAQR 4';
        $usuario['tipo_unidad_cve'] = '';
        $usuario['categoria_cve'] = '20000180';
        $usuario['categoria_nom'] = 'ABOGADO 80';
        $usuario['nombre_grupo'] = 'Super administrador'; //6
        $usuario['nivel'] = 'Nivel 3';
        $usuario['id_region'] = 1;
        $usuario['name_region'] = 'BAJA CALIFORNIA';
        $usuario['is_umae'] = '';

        $usuario1['nombre'] = 'Jesús ZDP';
        $usuario1['matricula'] = '311091402';
        $usuario1['del_cve'] = '16';
        $usuario1['del_nom'] = 'EDO MEX PTE';
        $usuario1['curp'] = 'YYYYOAISIO89879789';
        $usuario1['unidad_cve'] = 2648;
        $usuario1['unidad_nom'] = 'UNIDAD MED FAM C/HOSP 6';
        $usuario1['tipo_unidad_cve'] = '';
        $usuario1['categoria_cve'] = '36112580';
        $usuario1['categoria_nom'] = 'SUPERV PROYECTOS E3';
        $usuario1['nombre_grupo'] = 'Primer nivel: Director de Hospital'; //3
        $usuario1['nivel'] = 'Nivel 1';
        $usuario1['id_region'] = 4;
        $usuario1['name_region'] = 'Centro Sureste';
        $usuario1['is_umae'] = '';

        $usuario2['nombre'] = 'Miguel A. González G';
        $usuario2['matricula'] = '311091403';
        $usuario2['del_cve'] = '12';
        $usuario2['del_nom'] = 'GUERRERO';
        $usuario2['curp'] = 'XXXX8098hj87jh98';
        $usuario2['unidad_cve'] = 1710;
        $usuario2['unidad_nom'] = 'HOSP GRAL SUBZONA/MF 20 (SN L DE LA PAZ)';
        $usuario2['tipo_unidad_cve'] = '';
        $usuario2['categoria_cve'] = '36112580';
        $usuario2['categoria_nom'] = 'SUPERV PROYECTOS E3';
        $usuario2['nombre_grupo'] = 'Primer nivel:Coordinador Clínico de Educación e Investigación en Salud'; //5
        $usuario2['nivel'] = 'Nivel 3';
        $usuario2['id_region'] = 3;
        $usuario2['name_region'] = 'Centro';
        $usuario2['is_umae'] = '';
        $_SESSION['usuario'] = $usuario2;
    }
    
    public function index(){
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS, 
            Catalogo_listado::REGIONES, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'))); //Catalogo_listado::PERIODO
        $datos['catalogos']['periodo'] = array(2016 => 2016);
        //pr($datos['catalogos']);
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general'];

        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($this->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        //$this->template->setDescripcion("Bienvenida a delegacional");
        $this->template->setMainContent($this->load->view('informacion_general/index.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_perfil(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::PERIODO=>array('orden'=>'id_periodo DESC'), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC')));
        //pr($datos['catalogos']);
        $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }
        //pr($datos);
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_perfil']);
        //$this->template->setDescripcion("Bienvenida a delegacional");
        $this->template->setMainContent($this->load->view('informacion_general/por_perfil.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_unidad(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::PERIODO=>array('orden'=>'id_periodo DESC'), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC')));
        //pr($datos['catalogos']);
        $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }
        //pr($datos);
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_unidad']);
        //$this->template->setDescripcion("Bienvenida a delegacional");
        $this->template->setMainContent($this->load->view('informacion_general/por_perfil.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function calcular_totales(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']);
                $resultado = array();
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        //Total
                        if(!isset($resultado['total']['cantidad_alumnos_inscritos'])){
                            $resultado['total']['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['total']['cantidad_alumnos_certificados'])){
                            $resultado['total']['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['total']['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['total']['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Perfil
                        if(!isset($resultado['perfil'][$dato['perfil']]['cantidad_alumnos_inscritos'])){
                            $resultado['perfil'][$dato['perfil']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['perfil'][$dato['perfil']]['cantidad_alumnos_certificados'])){
                            $resultado['perfil'][$dato['perfil']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['perfil'][$dato['perfil']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['perfil'][$dato['perfil']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'])){
                            $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'])){
                            $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Periodo
                        if(!isset($resultado['periodo'][$dato['anio_fin']]['cantidad_alumnos_inscritos'])){
                            //$resultado['periodo'][$dato['anio_fin']][$dato['mes']]['cantidad_alumnos_inscritos'] = 0;
                            $resultado['periodo'][$dato['anio_fin']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['periodo'][$dato['anio_fin']]['cantidad_alumnos_certificados'])){
                            $resultado['periodo'][$dato['anio_fin']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['periodo'][$dato['anio_fin']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['periodo'][$dato['anio_fin']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Región
                        if(!isset($resultado['region'][$dato['region']]['cantidad_alumnos_inscritos'])){
                            $resultado['region'][$dato['region']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['region'][$dato['region']]['cantidad_alumnos_certificados'])){
                            $resultado['region'][$dato['region']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['region'][$dato['region']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['region'][$dato['region']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                    }
                    //pr($datos);
                    echo json_encode($resultado);
                    exit();
                } else {
                    echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function buscar_perfil(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']); 
                $resultado = array();
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        if(!isset($dato['periodo']) OR empty($dato['periodo'])) {
                            $dato['periodo'] = $dato['anio_fin'];
                        }
                        //Periodo
                        if(!isset($resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'])){
                            $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['periodo'][$dato['periodo']]['cantidad_alumnos_certificados'])){
                            $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'])){
                            $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'])){
                            $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Perfil
                        if(!isset($resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_inscritos'])){
                            $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_certificados'])){
                            $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Total
                        if(!isset($resultado['total']['cantidad_alumnos_inscritos'])){
                            $resultado['total']['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['total']['cantidad_alumnos_certificados'])){
                            $resultado['total']['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['total']['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['total']['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                    }
                    $resultado['lenguaje'] = $this->lang->line('interface')['informacion_general'];
                    $resultado['tabla_tipo_curso'] = $this->load->view('informacion_general/tabla.tpl.php', array('titulo'=>$resultado['lenguaje']['tipo_curso'], 'valores'=>$resultado['tipo_curso'], 'lenguaje'=>$resultado['lenguaje']), true);
                    $resultado['tabla_perfil'] = $this->load->view('informacion_general/tabla.tpl.php', array('titulo'=>$resultado['lenguaje']['perfil'], 'valores'=>$resultado['perfil'], 'lenguaje'=>$resultado['lenguaje']), true);
                    //pr($datos);
                    echo json_encode($resultado);
                    exit();
                } else {
                    echo true;
                    //echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function index_obtener_subtitulo($titulo){
        //pr($_SESSION);
        $datos_usuario = $this->session->userdata('usuario');
        $tipo_curso = 'a distancia';
        $unidad = 'de la unidad \''.$datos_usuario['unidad_nom'].'\'';
        $delegacion = 'de la delegación '.$datos_usuario['del_nom'];
        $periodo = $this->get_periodo_actual();
        return str_replace(array('$tipo_curso', '$unidad', '$delegacion', '$periodo'), array($tipo_curso, $unidad, $delegacion, $periodo), $titulo);
    }

    private function set_periodo_actual(){
        $this->anio_actual = date('Y')-1;
    }

    private function get_periodo_actual(){
        return $this->anio_actual;
    }
}