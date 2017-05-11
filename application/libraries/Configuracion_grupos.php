<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


/**
 * CodeIgniter CSV Import Class
 *
 * This library will help import a CSV file into
 * an associative array.
 * 
 * This library treats the first row of a CSV file
 * as a column header row.
 * 
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Brad Stinson
 */

class Configuracion_grupos {
	var $sesion;

	public function __construct() {
        $this->CI = & get_instance();
        $this->CI->sesion = $this->CI->session->userdata('usuario'); //Obtener datos de sesión
        $this->CI->load->library('En_grupos'); //Obtener constantes definidas para los grupos (roles)
    }

    public function obtener_grupo_actual(){
    	return $this->CI->sesion['grupos'][0]['id_grupo'];
    }

    public function obtener_unidad_actual(){
        return $this->CI->sesion['id_unidad_instituto'];
    }

    public function obtener_delegacion_actual(){
        return $this->CI->sesion['clave_delegacional'];
    }

    public function obtener_tipo_unidad_actual(){
        return $this->CI->sesion['id_tipo_unidad'];
    }

    public function obtener_tipos_busqueda($datos=null){
    	$resultado = array('tipos_busqueda'=>array(), 'catalogos'=>array());
        $grupo_actual = $this->obtener_grupo_actual();
        $this->CI->load->config('general');
        $tb = $this->CI->config->item('tipos_busqueda');
    	switch ($grupo_actual) {
    		case En_grupos::N1_CEIS: case En_grupos::N1_DH: case En_grupos::N1_DUMF: case En_grupos::N1_DEIS: case En_grupos::N1_DM: case En_grupos::N1_JDES:
    			$resultado['tipos_busqueda'] = array($tb['PERFIL']['id']=>$tb['PERFIL']['valor'], $tb['TIPO_CURSO']['id']=>$tb['TIPO_CURSO']['valor'], $tb['PERIODO']['id']=>$tb['PERIODO']['valor']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'order', 'condicion'=>'activa=CAST(1 as boolean)'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC')
		        );
                $resultado['condicion_calcular_totales'] = 'uni.id_unidad_instituto='.$this->obtener_unidad_actual();
    			break;
    		case En_grupos::N2_CPEI: case En_grupos::N2_DGU: case En_grupos::N2_CAME:
    			$resultado['tipos_busqueda'] = array($tb['PERFIL']['id']=>$tb['PERFIL']['valor'], $tb['TIPO_CURSO']['id']=>$tb['TIPO_CURSO']['valor'], $tb['PERIODO']['id']=>$tb['PERIODO']['valor']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'order', 'condicion'=>'activa=CAST(1 as boolean)'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC')
		        );
                if(in_array($grupo_actual, array(En_grupos::N2_CPEI, En_grupos::N2_CAME))){ //Si es unidad tiene diferentes condionales a las de una UMAE
                    $resultado['condicion_calcular_totales'] = "del.clave_delegacional='".$this->obtener_delegacion_actual()."' AND uni.id_tipo_unidad=".$this->obtener_tipo_unidad_actual();
                } else {
                    $resultado['condicion_calcular_totales'] = 'uni.id_unidad_instituto='.$this->obtener_unidad_actual();
                }
    		break;
    		case En_grupos::N3_JSPM:
    			$resultado['tipos_busqueda'] = array($tb['PERFIL']['id']=>$tb['PERFIL']['valor'], $tb['TIPO_CURSO']['id']=>$tb['TIPO_CURSO']['valor'], $tb['PERIODO']['id']=>$tb['PERIODO']['valor'], $tb['NIVEL_ATENCION']['id']=>$tb['NIVEL_ATENCION']['valor']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'order', 'condicion'=>'activa=CAST(1 as boolean)'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC')
		        );
                $resultado['condicion_calcular_totales'] = "del.clave_delegacional='".$this->obtener_delegacion_actual()."'";
    			break;
    		case En_grupos::NIVEL_CENTRAL:
    			$resultado['tipos_busqueda'] = array($tb['PERFIL']['id']=>$tb['PERFIL']['valor'], $tb['TIPO_CURSO']['id']=>$tb['TIPO_CURSO']['valor'], $tb['PERIODO']['id']=>$tb['PERIODO']['valor'], $tb['NIVEL_ATENCION']['id']=>$tb['NIVEL_ATENCION']['valor'], $tb['REGION']['id']=>$tb['REGION']['valor'], $tb['DELEGACION']['id']=>$tb['DELEGACION']['valor'], $tb['UMAE']['id']=>$tb['UMAE']['valor']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::REGIONES, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'order', 'condicion'=>'activa=CAST(1 as boolean)'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC'),
		            Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion>1'), Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true', 'valor'=>"CONCAT(nombre,' (',clave_unidad,')')")
		        );
                $resultado['condicion_calcular_totales'] = '';
    			break;
    		case En_grupos::ADMIN: case En_grupos::SUPERADMIN:
    			$resultado['tipos_busqueda'] = array($tb['PERFIL']['id']=>$tb['PERFIL']['valor'], $tb['TIPO_CURSO']['id']=>$tb['TIPO_CURSO']['valor'], $tb['PERIODO']['id']=>$tb['PERIODO']['valor'], $tb['NIVEL_ATENCION']['id']=>$tb['NIVEL_ATENCION']['valor'], $tb['REGION']['id']=>$tb['REGION']['valor'], $tb['DELEGACION']['id']=>$tb['DELEGACION']['valor'], $tb['UMAE']['id']=>$tb['UMAE']['valor']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::REGIONES, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'order', 'condicion'=>'activa=CAST(1 as boolean)'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_inicio)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_inicio))', 'orden'=>'llave DESC'),
		            Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion>1'), Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true', 'valor'=>"CONCAT(nombre,' (',clave_unidad,')')")
		        );
                $resultado['condicion_calcular_totales'] = '';
    			break;
    	}
        ksort($resultado['tipos_busqueda']);
    	return $resultado;
    }

    public function index_obtener_subtitulo($titulo){
        $tipo_curso = 'a distancia';
        if($this->CI->sesion['umae']==true){
            $unidad = 'de la UMAE \''.$this->CI->sesion['name_unidad_ist'].'\'';
            $delegacion = '';
        } else {
            $unidad = 'de la unidad \''.$this->CI->sesion['name_unidad_ist'].'\'';
            $delegacion = 'de la delegación '.$this->CI->sesion['name_delegacion'];
        }
        if(!in_array($this->CI->sesion['grupos'][0]['id_grupo'], array(En_grupos::N1_CEIS,En_grupos::N1_DH,En_grupos::N1_DUMF,En_grupos::N1_DEIS,En_grupos::N1_DM,En_grupos::N1_JDES,En_grupos::N2_DGU))) {
            $unidad = '';
        }
        if(in_array($this->CI->sesion['grupos'][0]['id_grupo'], array(En_grupos::NIVEL_CENTRAL, En_grupos::ADMIN, En_grupos::SUPERADMIN))){
            $delegacion = '';
        }
        //pr(array(En_grupos::N1_CEIS,En_grupos::N1_DH,En_grupos::N1_DUMF,En_grupos::N1_DEIS,En_grupos::N1_DM,En_grupos::N1_JDES,En_grupos::N2_DGU));
        //pr(array(En_grupos::NIVEL_CENTRAL, En_grupos::ADMIN, En_grupos::SUPERADMIN));
        $periodo = $this->get_periodo_actual();
        return str_replace(array('$tipo_curso', '$unidad', '$delegacion', '$periodo'), array($tipo_curso, $unidad, $delegacion, $periodo), $titulo);
    }

    public function set_periodo_actual(){
        $this->anio_actual = date('Y')-1;
    }

    public function get_periodo_actual(){
        return $this->anio_actual;
    }

}