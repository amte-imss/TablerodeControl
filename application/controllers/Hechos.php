<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para actualizar los hechos del tablero
 * @version 	: 1.0.0
 * @autor 		: Christian García
 */
class Hechos extends CI_Controller
{

    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Hechos_model', 'hechos');
        $this->load->model('Menu_model', 'menu');
        $menu = $this->menu->get_menu_usuario();
        $this->template->setNav($menu);
    }

    /**
     * Acceso principal del controlador.
     * @autor 		: Christian García
     * @modified 	:
     * @access 		: public
     */
    public function index()
    {
        redirect(site_url() . '/hechos/get_lista/');
    }

    public function upload()
    {
        if ($this->input->post())
        {     // SI EXISTE UN ARCHIVO EN POST
            $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
            $config['allowed_types'] = 'gz';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
            $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
            $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
            if ($this->upload->do_upload())
            {
                $json = $this->hechos->get_content_file();
                if ($this->hechos->valid_json($json)['status'])
                {
                    $json = json_decode($json, true);
                    $resultado = $this->hechos->insert_data($json);
                    if ($resultado['result'])
                    {
                        redirect(site_url() . '/hechos/get_lista/1');
                    } else
                    {
                        redirect(site_url() . '/hechos/draw_form/3');
                    }
                } else
                {
                    redirect(site_url() . '/hechos/draw_form/3');
                }
            } else
            {
                redirect(site_url() . '/hechos/draw_form/2');
            }
        } else
        {
            redirect(site_url() . '/hechos/draw_form');
        }
    }

    public function update_carga($id = 0, $activo = 0)
    {
        pr($activo);
        if ($id > 0)
        {
            $this->hechos->update($id, $activo);
        }
    }

    public function get_lista($status = null)
    {
        $data['status'] = $status;
        $data['lista'] = $this->hechos->get_lista();
        $datos['contenido'] = $this->load->view('hechos/lista', $data, true);
        $main_content = $this->load->view('admin/admin', $datos, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    public function draw_form($status = null)
    {
        $output['status'] = $status;
        $datos['contenido'] = $this->load->view('hechos/formulario', $output, true);
        $main_content = $this->load->view('admin/admin', $datos, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

}
