<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Clase que genera reporte dashboard de nivel central y de delegacionales
 * @version : 1.0.0
 * @autor : Miguel Guagnelli
 */
class Comparativa extends My_Controller{
	/**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
				$this->lang->load('comparativa', 'spanish');
				$this->load->model("Nomina_model","nom");
    }

    public function index(){

    }

		public function region(){
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
			$data["combos"]["tipo_perfil"] = $this->nom->get_tipo_perfil();
			$this->load->library('Catalogo_listado');
      $cat_list = new Catalogo_listado(); //Obtener catálogos
      $data['combos'] += $cat_list->obtener_catalogos(array(
					Catalogo_listado::TIPOS_CURSOS,
					Catalogo_listado::IMPLEMENTACIONES=>array(
							'valor'=>'EXTRACT(year FROM fecha_fin)',
							'llave'=>'DISTINCT(EXTRACT(year FROM fecha_fin))',
							'orden'=>'1 DESC')));

      $this->template->setBlank("comparative/region.tpl.php",$data,FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

      $this->template->getTemplate(null,"tc_template/index.tpl.php");
		}

}
?>
