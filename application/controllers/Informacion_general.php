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

        $usuario3['nombre'] = 'Ingrid Soto Venegas';
        $usuario3['matricula'] = '311091329';
        $usuario3['del_cve'] = '09';
        $usuario3['del_nom'] = 'Oficinas centrales';
        $usuario3['curp'] = 'SOVI7605038U4';
        $usuario3['unidad_cve'] = 1388;
        $usuario3['unidad_nom'] = 'U INVEST MED ENF NEUROL  S XXI';
        $usuario3['tipo_unidad_cve'] = '';
        $usuario3['categoria_cve'] = '37110580';
        $usuario3['categoria_nom'] = 'COORD PROGS NIVEL CENTRAL E1';
        $usuario3['nombre_grupo'] = 'Primer nivel:Coordinador Clínico de Educación e Investigación en Salud'; //5
        $usuario3['nivel'] = 'Nivel Central';
        $usuario3['id_region'] = '';
        $usuario3['name_region'] = '';
        $usuario3['is_umae'] = '';
        $_SESSION['usuario'] = $usuario3;
    }
    
    public function index(){
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $nivel_atencion = $cat_list->obtener_catalogos(array(Catalogo_listado::UNIDADES_INSTITUTO=>array('llave'=>'DISTINCT(COALESCE(nivel_atencion,0))', 'valor'=>"case when nivel_atencion=1 then 'Primer nivel' when nivel_atencion=2 then 'Segundo nivel' when nivel_atencion=3 then 'Tercer nivel' else 'Nivel no disponible' end", 'orden'=>'llave', 'alias'=>'nivel_atencion'))); //Obtener nivel de atenciónen otra llamada debido a que tiene el mismo indice que UMAE
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS, 
            Catalogo_listado::REGIONES, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'),
            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC'),
            Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion>1'), Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true', 'valor'=>"CONCAT(nombre,' (',clave_unidad,')')")
        )); //Catalogo_listado::PERIODO
        $datos['catalogos']+=$nivel_atencion;//Agregar arreglo de niveles de atención a los demás catálogos
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $datos['catalogos']['tipos_busqueda'] = array('perfil'=>$datos['lenguaje']['perfil'], 'tipo_curso'=>$datos['lenguaje']['tipo_curso'], 'periodo'=>$datos['lenguaje']['periodo'], 'nivel_atencion'=>$datos['lenguaje']['nivel_atencion'], 'region'=>$datos['lenguaje']['region'], 'delegacion'=>$datos['lenguaje']['delegacion'], 'umae'=>$datos['lenguaje']['umae']);
        //pr($datos['catalogos']);

        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($this->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        //$this->template->setDescripcion("Bienvenida a delegacional");
        $this->template->setMainContent($this->load->view('informacion_general/index.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_perfil(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
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

    public function por_tipo_curso(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
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

    /*public function por_unidad(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::REGIONES, Catalogo_listado::DELEGACIONES=>array('orden'=>'id_periodo DESC'), ));
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
    }*/

    public function buscar_filtros_listados(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //pr($datos['datos']);
                $res = array();
                if(!empty($datos['datos'])){
                    $resultado = array();
                    if(isset($datos_busqueda['destino']) AND $datos_busqueda['destino']=='tipo_curso'){
                        foreach ($datos['datos'] as $key_tip => $tipos) {
                            $resultado[$tipos['id_tipo_curso']]=$tipos['tipo_curso'];
                        }
                    }
                    if(!empty($resultado)){
                        foreach ($resultado as $key_val => $valor) {
                            //echo '{"title":"'.$valor.'", "key":'.$key_val.', selected: true, "children":[]},';
                            $res[$key_val]['title']=$valor;
                            $res[$key_val]["key"]=$key_val;
                            $res[$key_val]['selected']=true;
                            $res[$key_val]["children"]=array();
                        }
                    } else {
                        $res = array('no_datos'=>'true');
                    }
                } else {
                    $res = array('no_datos'=>'true');
                    //echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
                echo json_encode($res);
                //pr($datos);                    
                exit();
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function crear_arreglo_por_tipo($resultado, &$dato){
        if(!isset($resultado['cantidad_alumnos_inscritos'])){
            $resultado['cantidad_alumnos_inscritos'] = 0;
        }
        if(!isset($resultado['cantidad_alumnos_certificados'])){
            $resultado['cantidad_alumnos_certificados'] = 0;
        }
        if(!isset($resultado['cantidad_no_accesos'])){
            $resultado['cantidad_no_accesos'] = 0;
        }
        $resultado['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
        $resultado['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
        $resultado['cantidad_no_accesos'] += $dato['cantidad_no_accesos'];

        return $resultado;
    }

    public function calcular_totales_generales(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            //if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                
                //$datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales(array()); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']);
                $resultado = array('total'=>array());
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        //Total
                        $resultado['total'] = $this->crear_arreglo_por_tipo($resultado['total'], $dato);
                    }
                    //pr($datos);
                    echo json_encode($resultado);
                    exit();
                } else {
                    echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
            //}
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function calcular_totales(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']);
                $resultado = array('total'=>array(),'perfil'=>array(),'tipo_curso'=>array(),'periodo'=>array(),'region'=>array(),'delegacion'=>array(),'umae'=>array(),'nivel_atencion'=>array());
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        //Total
                        $resultado['total'] = $this->crear_arreglo_por_tipo($resultado['total'], $dato);
                        //Perfil
                        if(!isset($resultado['perfil'][$dato['perfil']])){
                            $resultado['perfil'][$dato['perfil']] = array();
                        }
                        $resultado['perfil'][$dato['perfil']] = $this->crear_arreglo_por_tipo($resultado['perfil'][$dato['perfil']], $dato);
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']])){
                            $resultado['tipo_curso'][$dato['tipo_curso']] = array();
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($resultado['tipo_curso'][$dato['tipo_curso']], $dato);
                        //Nivel atención
                        if(!isset($resultado['nivel_atencion'][$dato['nivel_atencion']])){
                            $resultado['nivel_atencion'][$dato['nivel_atencion']] = array();
                        }
                        $resultado['nivel_atencion'][$dato['nivel_atencion']] = $this->crear_arreglo_por_tipo($resultado['nivel_atencion'][$dato['nivel_atencion']], $dato);
                        //Periodo
                        if(!isset($resultado['periodo'][$dato['anio_fin']])){
                            $resultado['periodo'][$dato['anio_fin']] = array();
                        }
                        $resultado['periodo'][$dato['anio_fin']] = $this->crear_arreglo_por_tipo($resultado['periodo'][$dato['anio_fin']], $dato);
                        //Región
                        if(!isset($resultado['region'][$dato['region']])){
                            $resultado['region'][$dato['region']] = array();
                        }
                        $resultado['region'][$dato['region']] = $this->crear_arreglo_por_tipo($resultado['region'][$dato['region']], $dato);
                        //Delegación
                        if(!isset($resultado['delegacion'][$dato['delegacion']])){
                            $resultado['delegacion'][$dato['delegacion']] = array();
                        }
                        $resultado['delegacion'][$dato['delegacion']] = $this->crear_arreglo_por_tipo($resultado['delegacion'][$dato['delegacion']], $dato);
                        //UMAE
                        if($dato['umae']==true){
                            if(!isset($resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']])){
                                $resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']] = array();
                            }
                            $resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']] = $this->crear_arreglo_por_tipo($resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']], $dato);
                        }
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
                $resultado = array('total'=>array(),'perfil'=>array(),'tipo_curso'=>array(),'periodo'=>array());
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        if(!isset($dato['periodo']) OR empty($dato['periodo'])) {
                            $dato['periodo'] = $dato['anio_fin'];
                        }

                        //Total
                        $resultado['total'] = $this->crear_arreglo_por_tipo($resultado['total'], $dato);
                        //Periodo
                        if(!isset($resultado['periodo'][$dato['periodo']])){
                            $resultado['periodo'][$dato['periodo']] = array();
                        }
                        $resultado['periodo'][$dato['periodo']] = $this->crear_arreglo_por_tipo($resultado['periodo'][$dato['periodo']], $dato);
                        //Perfil
                        if(!isset($resultado['perfil'][$dato['perfil']])){
                            $resultado['perfil'][$dato['perfil']] = array();
                        }
                        $resultado['perfil'][$dato['perfil']] = $this->crear_arreglo_por_tipo($resultado['perfil'][$dato['perfil']], $dato);
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']])){
                            $resultado['tipo_curso'][$dato['tipo_curso']] = array();
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($resultado['tipo_curso'][$dato['tipo_curso']], $dato);
                        //Periodo
                        /*if(!isset($resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'])){
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
                        $resultado['total']['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];*/
                    }
                    $resultado['lenguaje'] = $this->lang->line('interface')['informacion_general'];
                    $resultado['tabla_tipo_curso'] = $this->load->view('informacion_general/tabla.tpl.php', array('titulo'=>$resultado['lenguaje']['tipo_curso'], 'valores'=>$resultado['tipo_curso'], 'lenguaje'=>$resultado['lenguaje']), true);
                    $resultado['tabla_perfil'] = $this->load->view('informacion_general/tabla.tpl.php', array('titulo'=>$resultado['lenguaje']['perfil'], 'valores'=>$resultado['perfil'], 'lenguaje'=>$resultado['lenguaje']), true);
                    //pr($datos);
                    echo json_encode($resultado);
                } else {
                    $resultado['total'] = 0;
                    echo json_encode($resultado);
                    //echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
                exit();
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