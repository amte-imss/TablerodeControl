<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: Mr. Guag
 * @version: 1.0
 * @desc: Clase padre de los controladores del sistema
 * */
class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->lang->load('interface', 'spanish');
        $this->load->model('Menu_model', 'menu');
        $menu = $this->menu->get_menu_usuario();
        $this->template->setNav($menu);       
    }
    
    public function new_crud() {
        $db_driver = $this->db->platform();
        $model_name = 'Grocery_crud_model_' . $db_driver;
        $model_alias = 'm' . substr(md5(rand()), 0, rand(4, 15));
        unset($this->{$model_name});
        $this->load->library('grocery_CRUD');
        $crud = new Grocery_CRUD();
        if (file_exists(APPPATH . '/models/' . $model_name . '.php')) {
            $this->load->model('Grocery_crud_model');
            $this->load->model('Grocery_crud_generic_model');
            $this->load->model($model_name, $model_alias);
            $crud->basic_model = $this->{$model_alias};
        }
        $crud->set_theme('datatables');
        $crud->unset_print();
        return $crud;
    }
    
    public function print_excel($metodo = null){
        if(method_exists ($this, $metodo)){
            $series_nombres_string = $this->input->post("series_nombres");
            $datos = array();
            $datos["nombres_series"] = explode(',', $series_nombres_string);
            $datos_json = $this->{$metodo}(false);
            $datos["resultset"] = json_decode($datos_json);
            if(json_last_error() == JSON_ERROR_NONE){
                header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=archivo.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                $html = $this->load->view("exportar/comparativas_excel", $datos , true);
                echo $html;
            }
        }
    }

}
