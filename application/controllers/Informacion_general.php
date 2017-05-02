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
        $this->load->library('Configuracion_grupos');
        $this->load->library('Catalogo_listado');
        $this->load->model('Informacion_general_model', 'inf_gen_model');
        $this->lang->load('interface'); //Cargar archivo de lenguaje
        $this->configuracion_grupos->set_periodo_actual();
    }
    
    public function index(){
        //pr($_SESSION['usuario']);
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $nivel_atencion = $cat_list->obtener_catalogos(array(Catalogo_listado::UNIDADES_INSTITUTO=>array('llave'=>'DISTINCT(COALESCE(nivel_atencion,0))', 'valor'=>"case when nivel_atencion=1 then 'Primer nivel' when nivel_atencion=2 then 'Segundo nivel' when nivel_atencion=3 then 'Tercer nivel' else 'Nivel no disponible' end", 'orden'=>'llave', 'alias'=>'nivel_atencion'))); //Obtener nivel de atención en otra llamada debido a que tiene el mismo indice que UMAE
        $configuracion = $this->configuracion_grupos->obtener_tipos_busqueda($datos['lenguaje']);
        $datos['catalogos'] = $cat_list->obtener_catalogos($configuracion['catalogos']); //Catalogo_listado::PERIODO        
        $datos['catalogos']+=$nivel_atencion;//Agregar arreglo de niveles de atención a los demás catálogos        
        $datos['catalogos']['tipos_busqueda'] = $configuracion['tipos_busqueda'];
        //pr($datos['catalogos']);

        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/index.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_perfil(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::PERIODO=>array('orden'=>'id_periodo DESC'), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC')));
        //pr($datos['catalogos']);
        $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria', 'conditions'=>'sub.activa=true', 'order'=>'sub.order ASC, gc.order ASC'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }
        //pr($datos);
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_perfil'].'. '.$this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/por_perfil.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_tipo_curso(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::PERIODO=>array('orden'=>'id_periodo DESC'), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC')));
        $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria', 'conditions'=>'sub.activa=true', 'order'=>'sub.order ASC, gc.order ASC'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_tipo_usuario'].'. '.$this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/por_tipo_curso.tpl.php', $datos, true));
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_unidad(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::REGIONES, Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC')));
        $tipos_busqueda = $this->config->item('tipo_busqueda');
        $tipo_grafica = $this->config->item('tipo_grafica');
        $datos['catalogos']['tipos_busqueda'] = array($tipos_busqueda['UMAE']['id']=>$tipos_busqueda['UMAE']['valor'], $tipos_busqueda['DELEGACION']['id']=>$tipos_busqueda['DELEGACION']['valor']);
        $datos['catalogos']['tipo_grafica'] = array($tipo_grafica['PERFIL']['id']=>$tipo_grafica['PERFIL']['valor'], $tipo_grafica['TIPO_CURSO']['id']=>$tipo_grafica['TIPO_CURSO']['valor']);
        //pr($datos['catalogos']);
        /*$listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }*/
        //pr($datos);
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_unidad'].'. '.$this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/por_unidad.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function cargar_listado($tipo){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                $this->load->library('Catalogo_listado');
                $cat_list = new Catalogo_listado(); //Obtener catálogos
                $c_region = (isset($datos_busqueda['region']) AND !empty($datos_busqueda['region'])) ? " AND id_region=".$datos_busqueda['region'] : '';
                $c_delegacion = (isset($datos_busqueda['delegacion']) AND !empty($datos_busqueda['delegacion'])) ? ' AND del.id_delegacion='.$datos_busqueda['delegacion'] : '';
                $c_tipo_unidad = (isset($datos_busqueda['tipo_unidad']) AND !empty($datos_busqueda['tipo_unidad'])) ? ' AND ins.id_tipo_unidad='.$datos_busqueda['tipo_unidad'] : '';
                $resultado=array('resultado'=>false, 'datos'=>array(), 'mensaje'=>'');
                $lenguaje = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
                $vista = 'listado.tpl.php';
                switch ($tipo) {
                    case 'ud':
                        if($datos_busqueda['tipos_busqueda']=='umae'){
                            //$datos = $cat_list->obtener_catalogos(array(Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true AND region=')));
                            $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>"ins.id_unidad_instituto, ins.clave_unidad, ins.nombre as institucion", 'conditions'=>'ins.umae=true '.$c_region));
                            $resultado['form']['label'] = $lenguaje['umae'];
                            $resultado['form']['path'] = 'unidad';
                            $resultado['form']['evento'] = array('onchange'=>"javascript:calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');");
                            //$resultado['form']['destino'] = '#unidad_capa';
                            $resultado['datos'] = dropdown_options($dato_mod, 'id_unidad_instituto', 'institucion');
                            $resultado['resultado'] = true;
                            //$vista = 'listado_radio.tpl.php';
                            $tipo = 'umae';
                        } else {
                            $resultado['form']['label'] = $lenguaje['delegacion'];
                            $resultado['form']['path'] = 'tipo_unidad';
                            $resultado['form']['evento'] = array('onchange'=>"javascript:data_ajax(site_url+'/informacion_general/cargar_listado/".$resultado['form']['path']."', '#form_busqueda', '#".$resultado['form']['path']."_capa'); limpiar_capas(); $('#tipo_unidad').val('');");
                            //$resultado['form']['destino'] = '#tipo_unidad_capa';
                            $datos = $cat_list->obtener_catalogos(array(Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion>1 '.$c_region)));
                            $resultado['datos'] = $datos['delegaciones'];
                            $resultado['resultado'] = true;
                            $tipo = 'delegacion';
                        }
                        break;
                    case 'tipo_unidad':
                        $resultado['form']['label'] = $lenguaje['tipo_unidad'];
                        $resultado['form']['path'] = 'unidad';
                        $resultado['form']['evento'] = array('onchange'=>"javascript:data_ajax(site_url+'/informacion_general/cargar_listado/".$resultado['form']['path']."', '#form_busqueda', '#".$resultado['form']['path']."_capa'); $('#comparativa_chrt').html(''); $('#comparativa_chrt2').html('');");
                        //$resultado['form']['destino'] = '#unidad_capa';
                        $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'DISTINCT(tipo_uni.id_tipo_unidad), tipo_uni.clave, tipo_uni.nombre as tipo_unidad', 'conditions'=>'ins.umae=false '.$c_region.$c_delegacion));
                        $resultado['datos'] = dropdown_options($dato_mod, 'id_tipo_unidad', 'tipo_unidad');
                        $resultado['resultado'] = true;
                        break;
                    case 'unidad':
                        $resultado['form']['label'] = $lenguaje['unidades'];
                        $resultado['form']['path'] = 'unidad';
                        $resultado['form']['evento'] = array('onchange'=>"javascript:calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');");
                        $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'ins.id_unidad_instituto, ins.clave_unidad, ins.nombre as institucion', 'conditions'=>'ins.umae=false '.$c_region.$c_delegacion.$c_tipo_unidad));
                        $resultado['resultado'] = true;
                        $resultado['datos'] = dropdown_options($dato_mod, 'id_unidad_instituto', 'institucion');
                        //$vista = 'listado_radio.tpl.php';
                        break;
                }
                $resultado['form']['seleccione'] = $lenguaje['seleccione'];
                $resultado['tipo'] = $tipo;
                $resultado['busqueda'] = $datos_busqueda;
                echo $this->load->view('informacion_general/'.$vista, $resultado, true);
                //pr($datos);
                exit();
            }
        }
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
                    if(isset($datos_busqueda['destino'])) {
                        switch ($datos_busqueda['destino']) {
                            case 'tipo_curso':
                                foreach ($datos['datos'] as $key_tip => $tipos) {
                                    $resultado[$tipos['id_tipo_curso']]['principal']=$tipos['tipo_curso'];
                                }
                                break;
                            case 'perfil':
                                foreach ($datos['datos'] as $key_tip => $tipos) {
                                    if(!is_null($tipos['id_subcategoria'])){
                                        //pr($tipos);
                                        $resultado[$tipos['id_subcategoria']]['principal']=$tipos['perfil'];
                                        if(isset($tipos['grupo_categoria']) AND !empty($tipos['grupo_categoria'])) {
                                            //pr($tipos['id_grupo_categoria'].'-'.$tipos['grupo_categoria']);
                                            $resultado[$tipos['id_subcategoria']]['elementos'][$tipos['id_grupo_categoria']] = $tipos['grupo_categoria'];
                                        }
                                    }
                                }
                                break;
                        }
                        //pr($resultado);
                    }
                    if(!empty($resultado)){
                        foreach ($resultado as $key_val => $valor) {
                            //echo '{"title":"'.$valor.'", "key":'.$key_val.', selected: true, "children":[]},';
                            $res[$key_val]['title']=$valor['principal'];
                            $res[$key_val]["key"]=$key_val;
                            $res[$key_val]['selected']=true;
                            $res[$key_val]['expanded']=true;
                            $res[$key_val]['icon']=false;
                            $children = array();
                            if(isset($valor['elementos']) AND !empty($valor['elementos'])){
                                foreach ($valor['elementos'] as $key => $value) {
                                    $children[$key]['title']=$value;
                                    $children[$key]["key"]=$key;
                                    $children[$key]['selected']=true;
                                    $children[$key]['icon']=false;
                                }
                            }
                            $res[$key_val]["children"]=$children;
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
        if(!isset($resultado['cantidad_no_aprobados'])){
            $resultado['cantidad_no_aprobados'] = 0;
        }
        $resultado['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
        $resultado['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
        $resultado['cantidad_no_accesos'] += $dato['cantidad_no_accesos'];
        $resultado['cantidad_no_aprobados'] += $dato['cantidad_no_aprobados'];

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

    public function calcular_totales_unidad(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda+array('calcular_totales_unidad'=>true)); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']);
                $resultado = array('perfil'=>array(),'tipo_curso'=>array());
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        if(!isset($resultado['perfil'][$dato['perfil']])){
                            $resultado['perfil'][$dato['perfil']] = array();
                        }
                        $resultado['perfil'][$dato['perfil']] = $this->crear_arreglo_por_tipo($resultado['perfil'][$dato['perfil']], $dato);
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']])){
                            $resultado['tipo_curso'][$dato['tipo_curso']] = array();
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($resultado['tipo_curso'][$dato['tipo_curso']], $dato);
                        /*if(!isset($resultado['perfil']['incritos'][$dato['perfil']][$dato['tipo_curso']])){
                            $resultado['perfil']['incritos'][$dato['perfil']][$dato['tipo_curso']] = 0;
                        }
                        if(!isset($resultado['perfil']['aprobados'][$dato['perfil']][$dato['tipo_curso']])){
                            $resultado['perfil']['aprobados'][$dato['perfil']][$dato['tipo_curso']] = 0;
                        }
                        if(!isset($resultado['perfil']['nunca entraron'][$dato['perfil']][$dato['tipo_curso']])){
                            $resultado['perfil']['nunca entraron'][$dato['perfil']][$dato['tipo_curso']] = 0;
                        }
                        if(!isset($resultado['perfil']['no aprobados'][$dato['perfil']][$dato['tipo_curso']])){
                            $resultado['perfil']['no aprobados'][$dato['perfil']][$dato['tipo_curso']] = 0;
                        }
                        $resultado['perfil']['incritos'][$dato['perfil']][$dato['tipo_curso']] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['perfil']['aprobados'][$dato['perfil']][$dato['tipo_curso']] += $dato['cantidad_alumnos_certificados'];
                        $resultado['perfil']['nunca entraron'][$dato['perfil']][$dato['tipo_curso']] += $dato['cantidad_no_accesos'];
                        $resultado['perfil']['no aprobados'][$dato['perfil']][$dato['tipo_curso']] += $dato['cantidad_no_aprobados'];*/
                    }
                    echo json_encode($resultado);
                } else {
                    echo json_encode(array('error'=>true,'msg'=>'No existen datos')); //Mostrar mensaje de datos no existentes
                }
                exit();
            }
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
                        ksort($resultado['tipo_curso']);
                        //Nivel atención
                        if(!isset($resultado['nivel_atencion'][$dato['nivel_atencion']])){
                            $resultado['nivel_atencion'][$dato['nivel_atencion']] = array();
                        }
                        $resultado['nivel_atencion'][$dato['nivel_atencion']] = $this->crear_arreglo_por_tipo($resultado['nivel_atencion'][$dato['nivel_atencion']], $dato);
                        ksort($resultado['nivel_atencion']);
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
                        ksort($resultado['region']);
                        //Delegación
                        if(!isset($resultado['delegacion'][$dato['delegacion']])){
                            $resultado['delegacion'][$dato['delegacion']] = array();
                        }
                        $resultado['delegacion'][$dato['delegacion']] = $this->crear_arreglo_por_tipo($resultado['delegacion'][$dato['delegacion']], $dato);
                        ksort($resultado['delegacion']);
                        //UMAE
                        if($dato['umae']==true){
                            if(!isset($resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']])){
                                $resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']] = array();
                            }
                            $resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']] = $this->crear_arreglo_por_tipo($resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']], $dato);
                            ksort($resultado['umae']);
                        }
                    }
                    //pr($datos);
                    echo json_encode($resultado);
                } else {
                    echo json_encode(array('error'=>true,'msg'=>'No existen datos')); //Mostrar mensaje de datos no existentes
                }
                exit();
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
                        //pr($resultado);
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
                    $resultado['tabla_tipo_curso'] = $this->load->view('informacion_general/tabla.tpl.php', array('id'=>'tabla_tipo_curso', 'titulo'=>$resultado['lenguaje']['tipo_curso'], 'valores'=>$resultado['tipo_curso'], 'lenguaje'=>$resultado['lenguaje']), true);
                    $resultado['tabla_perfil'] = $this->load->view('informacion_general/tabla.tpl.php', array('id'=>'tabla_perfil', 'titulo'=>$resultado['lenguaje']['perfil'], 'valores'=>$resultado['perfil'], 'lenguaje'=>$resultado['lenguaje']), true);
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
}