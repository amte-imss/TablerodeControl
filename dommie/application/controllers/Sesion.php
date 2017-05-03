<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catalogos
 * @version 	: 1.2.0
 * @author      : LEAS
 * */
class Sesion extends CI_Controller{

    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access 		: public
     * * @modified 	: 
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Login_model', 'lm');
        $this->lang->load('interface', 'spanish');
    }

    function index(){

    }

    public function index2(){
        //echo "Hola mundo";
        $data_post = $this->input->post();
        $str_val = $this->lang->line('interface')['session']; //Mensajes de respuesta
        $array_resp = array();
        if($this->input->post()){
            
        
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('inicio_sesion'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
    //        $resultados = ($this->form_validation->run()) ? 1 : 0;
    //        pr($resultados);
    //        pr($data_post);
    //        pr($this->session->userdata('captchaWord'));
            if ($this->form_validation->run() == TRUE)
            {
                
                if (isset($data_post['usuario']) && isset($data_post['password']))
                {

                    $result_datos = $this->lm->get_datos_usuario($data_post['usuario'], $data_post['password']);
                    pr($result_datos);
                    //exit();
                    if (!empty($result_datos['datos_user']))
                    {//Existe el usuario xiste el ususario
                        if ($result_datos['valido'] == 1)
                        {//Passwor valido
    //                    $this->session->set_userdata('datos_user',$result_datos['datos_user']);//Almacena los datos de usuario en la variable de sesión
    //                        $array_resp = $this->lm->get_bloque_permisos_usuario_inicio($data_post['usuario']); //Obtiene todos los permisos de usuario
    //                        pr($array_resp);
    //                        exit();
                            $info_user = $this->lm->get_genera_info_usuario($result_datos['datos_user']['matricula']); //Obtiene todos los permisos de usuario
    //                        pr($info_user);
    //                        pr($info_user['config']); 
    //                        exit();
                            $array_resp['token'] = $result_datos['datos_user']['token'];
                            $array_resp['usuario']['nombre'] = $result_datos['datos_user']['name_user'];
                            $array_resp['usuario']['matricula'] = $result_datos['datos_user']['matricula'];
                            $array_resp['usuario']['del_cve'] = $result_datos['datos_user']['clave_delegacional'];
                            $array_resp['usuario']['del_nom'] = $result_datos['datos_user']['name_delegacion'];
                            $array_resp['usuario']['curp'] = $result_datos['datos_user']['curp'];
                            $array_resp['usuario']['unidad_cve'] = $result_datos['datos_user']['id_unidad_instituto'];
                            $array_resp['usuario']['unidad_nom'] = $result_datos['datos_user']['name_unidad_ist'];
                            $array_resp['usuario']['tipo_unidad_cve'] = $result_datos['datos_user']['id_tipo_unidad'];
                            $array_resp['usuario']['categoria_cve'] = $result_datos['datos_user']['clave_categoria'];
                            $array_resp['usuario']['categoria_nom'] = $result_datos['datos_user']['name_categoria'];
                            $array_resp['usuario']['nombre_grupo'] = $result_datos['datos_user']['nombre_grupo'];
                            $array_resp['usuario']['nivel'] = 'Nivel ' . $result_datos['datos_user']['nivel'];
                            $array_resp['usuario']['id_region'] = $result_datos['datos_user']['id_region'];
                            $array_resp['usuario']['name_region'] = $result_datos['datos_user']['name_region'];
                            $array_resp['usuario']['is_umae'] = $result_datos['datos_user']['umae'];
                            $array_resp['acceso'] = true;
                            $array_resp['tp_msg'] = En_tpmsg::SUCCESS;

                            $this->session->set_userdata('usuario', $array_resp['usuario']);
                            $this->session->set_userdata('servicios', $info_user['servicios']);
    //                        $this->session->set_userdata('anios_implementacion', $info_user['anios_implementacion']);
    //                        $this->session->set_userdata('config', $info_user['ui']['config']);
    //                        $this->session->set_userdata('menu', $info_user['ui']['menu']);
    //                        pr($this->session->userdata());
    //                        $this->session->userdata('menu');
    //                        $this->session->userdata('config');
    //                    redirect(base_url());
    //                        echo $this->load->view('main/index', $array_resp, true);
                            redirect(base_url());
                            //exit();
                        } else
                        {
                            $array_resp['acceso'] = FALSE;
                            $array_resp['msj'] = $str_val['user_password_invalidos'];
                            $array_resp['tp_msg'] = En_tpmsg::DANGER;
                        }
                    } else
                    {
                        $array_resp['acceso'] = FALSE;
                        $array_resp['msj'] = $str_val['user_password_invalidos'];
                        $array_resp['tp_msg'] = En_tpmsg::DANGER;
                    }
                } else
                {
                    $array_resp['acceso'] = FALSE;
                    $array_resp['msj'] = $str_val['user_password_invalidos'];
                    $array_resp['tp_msg'] = En_tpmsg::DANGER;
                }
            } else
            {
                $array_resp['acceso'] = FALSE;
                $array_resp['msj'] = $str_val['user_password_invalidos'];
                $array_resp['tp_msg'] = En_tpmsg::DANGER;
            }
        }
        echo $this->formulario($array_resp, "sesion/formulario");
    }

    public function recargar_captcha()
    {
        $this->load->library('captcha');
        $data['captcha'] = $this->captcha->main();
        $this->session->set_userdata('captchaWord', $data['captcha']);
    }

    private function formulario($data = array(), $tpl = "sesion/formulario", $return = TRUE){
//        $data['captcha'] = create_captcha($this->captcha_config());
//        $this->session->set_userdata('captchaWord', $data['captcha']['word']);
        //echo $data['token'] = $this->session->userdata('token'); //Se envia token al formulario
        $form_login = $this->load->view($tpl, $data, $return);
        return $form_login;
    }

    function cerrar_sesion()
    {
        $data_post = $this->input->post();
        $str_val = $this->lang->line('interface')['session']; //Mensajes de respuesta
//        if (isset($data_post['token']) and ! empty($data_post['token'])) {
//            $array_resp['msj'] = $str_val['cierre_sesion_correcto'];
//            $array_resp['tp_msg'] = En_tpmsg::SUCCESS;
//        } else {
//            $array_resp['msj'] = $str_val['token_no_valido'];
//            $array_resp['tp_msg'] = En_tpmsg::DANGER;
//        }
        $array_resp['msj'] = 'Cierre valido';
        $array_resp['tp_msg'] = En_tpmsg::SUCCESS;
        $this->session->sess_destroy();
        $json = json_encode($array_resp);
        echo $json;
    }

    private function conecta_siap($delegacion, $matricula)
    {
        $this->load->library('empleados_siap');

        $param = array("reg_delegacion" => $delegacion,
            "asp_matricula" => $matricula,
        );

        $datos_siap = $this->empleados_siap->buscar_usuario_siap($param);
        return $datos_siap;
    }

    function recuperar_password($code = null)
    {
        $datos = array();
        if ($this->input->post() && $code == null)
        {
            $usuario = $this->input->post("usuario", true);
            $this->form_validation->set_rules('usuario', 'Usuario', 'required');
            if ($this->form_validation->run() == TRUE)
            {
                $this->lm->recuperar_password($usuario);
                $datos['recovery'] = true;
            }
        } else if ($this->input->post() && $code != null)
        {
            $this->form_validation->set_rules('new_password', 'Constraseña', 'required');
            $this->form_validation->set_rules('new_password_confirm', 'Confirmar constraseña', 'required');
            if ($this->form_validation->run() == TRUE)
            {
                $new_password = $this->input->post('new_password', true);
                $datos['success'] = $this->lm->update_password($code, $new_password);
            }
        } else if ($code != null)
        {
            $datos['code'] = $code;
            $datos['form_recovery'] = true;
        }
        $this->formulario($datos, 'sesion/recuperar_password', false);
    }
}
