<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Autenticacion
 *
 * @author chrigarc
 */
class Autenticacion
{

    private static $libre_acceso = array(
        'welcome/index', 
        'welcome/cerrar_sesion', 
        'captcha/index', 
    );

    function acceso()
    {
        $CI = & get_instance(); //Obtiene la insatancia del super objeto en codeigniter para su uso directo
//        echo $CI->load->view('template/sin_acceso', $datos_, true);
//        return json_encode($array_result);
        $CI->load->helper('url');
        $CI->load->library('session');

        $controlador = $CI->uri->rsegment(1);  //Controlador actual o dirección actual
        $accion = $CI->uri->rsegment(2);  //Función que se llama en el controlador

        $url = $controlador . '/' . $accion;

        if (!in_array($url, Autenticacion::$libre_acceso))
        {
            $usuario = $CI->session->userdata('usuario');
            if(isset($usuario['id_usuario'])){
                if(!$this->verifica_permiso($CI, $usuario)){
                    redirect(site_url());
                }
            }else{
                redirect(site_url());
            }
        }
    }

    private function verifica_permiso($CI, $usuario){
        return true;
    }
}
