<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para generar las imagenes de Captcha
 * @version 	: 1.0.0
 * @autor 		: Ale Quiroz
 */
class Usuario extends MY_Controller
{

    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('empleados_siap');
        $this->load->library('seguridad');
        $this->load->model('Usuario_model', 'registro');
    }

    public function index()
    {
        redirect(site_url());
    }

    public function agregar_usuario()
    {
        if ($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_registro'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $data = array(
                    'matricula' => $this->input->post('matricula', TRUE),
                    'delegacion' => $this->input->post('delegacion', TRUE),
                    'email' => $this->input->post('email', true),
                    'password' => $this->input->post('pass', TRUE),
                    'grupo' => $this->input->post('niveles', TRUE)
                );
                $output['registro_valido'] = $this->registro->registro_usuario($data);
                $modal['titulo_modal'] = 'Registro';
                $modal['cuerpo_modal'] = $this->load->view("usuario/modal_registro", $modal, true);
                $this->modal = $this->load->view("ci_template/modal_info_usuario", $modal);
            }
        }
        $output['delegaciones'] = dropdown_options($this->registro->lista_delegaciones(), 'clave_delegacional', 'nombre');
        $output['nivel_atencion'] = dropdown_options($this->registro->lista_nivel_atencion(), 'id_grupo', 'nombre');
        $view['contenido'] = $this->load->view('usuario/gestionRegistro', $output, true);
        $main_content = $this->load->view('admin/admin', $view, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    public function lista_usuarios()
    {
        $filtros['limit'] = 20;
        $filtros['current_page'] = 0;
        if ($this->input->post())
        {
            $filtros['order'] = $this->input->post('order', true);
            $filtros['limit'] = $this->input->post('per_page', true);

            if ($this->input->post('current_page') && is_numeric($this->input->post('current_page', true)))
            {
                $filtros['current_page'] = $this->input->post('current_page', true);
            }
            $output['current_page'] = $filtros['current_page'];
        }
        //pr($filtros);
        $output['usuarios'] = $this->registro->ver($filtros);
        $view['contenido'] = $this->load->view('usuario/gestionBuscar', $output, true);
        $main_content = $this->load->view('admin/admin', $view, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    public function mod($id = null, $status = null)
    {
        //pr('inicio');
        if (!is_null($id) && is_numeric($id))
        {
            $output['delegaciones'] = dropdown_options($this->registro->lista_delegaciones(), 'clave_delegacional', 'nombre');
            $output['unidad_instituto'] = dropdown_options($this->registro->lista_unidad(), 'id_unidad_instituto', 'nombre');
            $output['categoria'] = dropdown_options($this->registro->lista_categoria(), 'clave_categoria', 'nombre');
            $output['usuarios'] = $this->registro->datos_usuario($id);
            if ($status != null)
            {
                $output['status_password'] = $status;
            }
            //pr($output);
            if ($this->input->post())
            {
                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('form_actualizar'); //Obtener validaciones de archivo general
                $this->form_validation->set_rules($validations); //Añadir validaciones

                if ($this->form_validation->run() == TRUE)
                {
                    $data = array(
                        'id_usuario' => $id,
                        'delegacion' => $this->input->post('delegacion', TRUE),
                        'email' => $this->input->post('email', true),
                        'unidad' => $this->input->post('unidad', true),
                        'categoria' => $this->input->post('categoria', true),
                        'token' => $output['token']
                    );
                    $output['status'] = $this->registro->actualiza_registro($data);
                }
            }
            $view['contenido'] = $this->load->view('usuario/ver_registro_completo', $output, true);
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }
    }

    public function set_status($id_usuario = 0)
    {
        if ($this->input->post())
        {
            $status = $this->input->post('status', true);
            echo $this->registro->set_status($id_usuario, $status);
        }
    }

    public function update_password($id_usuario = 0)
    {
        $usuario = $this->registro->datos_usuario($id_usuario);
        if (count($usuario) > 0)
        {
            $output['usuario'] = $usuario;
            if ($this->input->post())
            {
                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('form_user_update_password'); //Obtener validaciones de archivo general
                $this->form_validation->set_rules($validations); //Añadir validaciones
                if ($this->form_validation->run() == TRUE)
                {
                    $post['id_usuario'] = $id_usuario;
                    $post['password'] = $this->input->post('pass', true);
                    $datos['status'] = $this->registro->update_password($post);

                    redirect(site_url() . '/registro/mod/' . $id_usuario . '/1');
                } else
                {
                    pr('datos no validos');
                    pr(validation_errors());
                    redirect(site_url() . '/registro/mod/' . $id_usuario . '/2');
                }
            }
        } else
        {
            //pr('fail');
        }
    }

    public function carga_usuarios()
    {
        if ($this->input->post())
        {     // SI EXISTE UN ARCHIVO EN POST
            $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
            $config['allowed_types'] = 'csv';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
            $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
            $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
            $view['status']['result'] = false;
            if ($this->upload->do_upload())
            { //Se ejecuta la validación de datos
                $this->load->library('csvimport');
                $file_data = $this->upload->data();     //BUSCAMOS LA INFORMACIÓN DEL ARCHIVO CARGADO
                $file_path = './uploads/' . $file_data['file_name'];         // CARGAMOS LA URL DEL ARCHIVO

                if ($this->csvimport->get_array($file_path))
                {              // EJECUTAMOS EL METODO get_array() DE LA LIBRERIA csvimport PARA BUSCAR SI EXISTEN DATOS EN EL ARCHIVO Y VERIFICAR SI ES UN CSV VALIDO
                    $csv_array = $this->csvimport->get_array($file_path);   //SI EXISTEN DATOS, LOS CARGAMOS EN LA VARIABLE $csv_array                    
                    $view['status'] = $this->registro->carga_masiva($csv_array);
                    //pr($view['status']);
                    $this->reporte_registro($view['status']);
                } else
                {
                    $view['status']['msg'] = "Formato inválido";
                }
            } else
            {
                $view['status']['msg'] = "Formato inválido";
            }
        }
        $view['contenido'] = $this->load->view('usuario/formulario_carga', array(), true);
        $main_content = $this->load->view('admin/admin', $view, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    private function reporte_registro(&$datos)
    {
        $filename = "Registro_" . date("d-m-Y_H-i-s") . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $this->load->view('usuario/reporte_registro', $datos, TRUE);
        exit();
    }

}
