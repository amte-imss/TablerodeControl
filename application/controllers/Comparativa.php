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
    }

    public function index(){
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
        
        $this->template->setBlank("comparative/index.tpl.php",$data,FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");
        
        $this->template->getTemplate(null,"tc_template/index.tpl.php");

    }

}
?>