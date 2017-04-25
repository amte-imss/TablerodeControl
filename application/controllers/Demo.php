<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Clase que genera reporte dashboard de nivel central y de delegacionales
 * @version : 1.0.0
 * @autor : Miguel Guagnelli
 */
class Demo extends My_Controller{
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

    public function index($tipo = null){
      $session = $this->session->userdata("usuario");
      //pr($session);

      $this->template->setTitle("Comparativa");
      $this->template->setSubTitle("Región nivel estratégico");
      //$this->template->setDescripcion($data["texts"]["descripcion"]);

        $file = "";
      switch($tipo){
        case 're':
          $file = 'region_estrategico';
          break;
        case 'dt':
          $file = 'delegacion_tactico';
          break;
          case 'uo':
            $file = 'unidad_operativo';
            break;
          case 'igo':
          case 'ige':
          case 'igt':
            $file = $tipo;
            break;
        default: $file = 'region_estrategico';
      }

      $this->template->setBlank("dommie/comparativa/$file.tpl.php",null,FALSE);
      //$this->template->setBlank("tc_template/index.tpl.php");
      $this->template->getTemplate(null,"tc_template/index.tpl.php");

    }
}
?>
