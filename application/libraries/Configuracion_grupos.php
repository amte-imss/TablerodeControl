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
    	return $this->CI->sesion['rol'];
    }

    public function obtener_tipos_busqueda($datos){
    	$resultado = array('tipos_busqueda'=>array(), 'catalogos'=>array());
    	switch ($this->obtener_grupo_actual()) {
    		case En_grupos::N1_CEIS: case En_grupos::N1_DH: case En_grupos::N1_DUMF: case En_grupos::N1_DEIS: case En_grupos::N1_DM: case En_grupos::N1_JDES:
    			$resultado['tipos_busqueda'] = array('perfil'=>$datos['perfil'], 'tipo_curso'=>$datos['tipo_curso'], 'periodo'=>$datos['periodo']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC')
		        );
    			break;
    		case En_grupos::N2_CPEI: case En_grupos::N2_DGU:
    			$resultado['tipos_busqueda'] = array('perfil'=>$datos['perfil'], 'tipo_curso'=>$datos['tipo_curso'], 'periodo'=>$datos['periodo']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC')
		        );
    		break;
    		case En_grupos::N3_JSPM:
    			$resultado['tipos_busqueda'] = array('perfil'=>$datos['perfil'], 'tipo_curso'=>$datos['tipo_curso'], 'periodo'=>$datos['periodo'], 'nivel_atencion'=>$datos['nivel_atencion']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC')
		        );
    			break;
    		case En_grupos::NIVEL_CENTRAL:
    			$resultado['tipos_busqueda'] = array('perfil'=>$datos['perfil'], 'tipo_curso'=>$datos['tipo_curso'], 'periodo'=>$datos['periodo'], 'nivel_atencion'=>$datos['nivel_atencion'], 'region'=>$datos['region'], 'delegacion'=>$datos['delegacion'], 'umae'=>$datos['umae']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::REGIONES, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC'),
		            Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion>1'), Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true', 'valor'=>"CONCAT(nombre,' (',clave_unidad,')')")
		        );
    			break;
    		case En_grupos::ADMIN: case En_grupos::SUPERADMIN:
    			$resultado['tipos_busqueda'] = array('perfil'=>$datos['perfil'], 'tipo_curso'=>$datos['tipo_curso'], 'periodo'=>$datos['periodo'], 'nivel_atencion'=>$datos['nivel_atencion'], 'region'=>$datos['region'], 'delegacion'=>$datos['delegacion'], 'umae'=>$datos['umae']);
    			$resultado['catalogos'] = array(Catalogo_listado::TIPOS_CURSOS, Catalogo_listado::REGIONES, Catalogo_listado::SUBCATEGORIAS=>array('orden'=>'id_subcategoria'),
		            Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'EXTRACT(year FROM fecha_fin)', 'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))', 'orden'=>'llave DESC'),
		            Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion>1'), Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true', 'valor'=>"CONCAT(nombre,' (',clave_unidad,')')")
		        );
    			break;
    	}
    	return $resultado;
    }

}