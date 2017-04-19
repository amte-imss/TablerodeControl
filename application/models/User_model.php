<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catalogos
 * @version 	: 1.0.0
 * @author      : LEAS
 * */
class User_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->config->load('general');
    }

    function validar_user($usr, $passwd){
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->start_cache();
        
        $this->db->select(array('matricula','password','token',''));
        $this->db->from('sistema.usuarios u');
        $this->db->where('u.matricula', $usr);
        
        $num_user = $this->db->count_all_results();

        if($num_user == 1){
            $usuario = $this->db->get();
            $result = $usuario->result_array();
            
            $this->load->library('seguridad');
            $cadena = $result[0]['token'] . $passwd . $result[0]['token'];
            $clave = $this->seguridad->encrypt_sha512($cadena);
            if($clave == $result[0]['password']){
                return 1; //Existe
            }
            return 2;//contraseña incorrrecta
        }else{
            return 3; //Usuario no existe
        }

        //$cadena = $result[0]['token'] . $password . $result[0]['token'];
    }

    public function recuperar_password($username) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select(array(
            'id_usuario', 'nombre', 'email', 'recovery_code'
        ));
        $this->db->where('matricula', $username);
        $this->db->or_where('email', $username);
        $this->db->limit(1);
        $resultado = $this->db->get('sistema.usuarios')->result_array();

        if ($resultado) {
            $usuario = $resultado[0];
            if (empty($usuario['recovery_code'])) {
                $this->load->library('seguridad');
                $usuario['recovery_code'] = $this->seguridad->crear_token();
                $this->db->reset_query();
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->set('recovery_code', $usuario['recovery_code']);
                $this->db->update('sistema.usuarios');
                //pr($this->db->last_query());
            }
            $this->send_recovery_mail($usuario);
        }
    }

    public function update_password($code = null, $new_password = null) {
        $salida = false;
        if ($code != null && $new_password != null) {
            $this->db->flush_cache();
            $this->db->reset_query();

            $this->db->select(array(
                'id_usuario', 'token'
            ));
            $this->db->where('recovery_code', $code);
            $this->db->limit(1);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($resultado);
            if ($resultado) {
                $this->load->library('seguridad');
                $usuario = $resultado[0];
                $this->db->reset_query();
                $pass = $this->seguridad->encrypt_sha512($usuario['token'] . $new_password . $usuario['token']);
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->set('password', $pass);
                $this->db->set('recovery_code', null);
                $this->db->update('sistema.usuarios');
                //pr($this->db->last_query());
                $salida = true;
            }
        }
        return $salida;
    }

    private function send_recovery_mail($usuario) {
        $this->load->config('email');
        $this->load->library('My_phpmailer');
        $mailStatus = $this->my_phpmailer->phpmailerclass();
        $emailStatus = $this->load->view('sesion/mail_recovery_password', $usuario, true);
//        $mailStatus->addAddress('zurgcom@gmail.com'); //pruebas chris
        $mailStatus->addAddress($usuario['email']);
        $mailStatus->Subject = 'Recuperación de contraseña para el Tablero';
        $mailStatus->msgHTML(utf8_decode($emailStatus));
        $mailStatus->send();
    }

    

    public function get_datos_usuario($matricula = null, $password = null) {
        if (is_null($matricula) and is_null($password)) {
            return null;
        }

        $select = array(
            'u.id_usuario', 'u.nombre name_user', 'u.matricula', 'u.curp'
            , 'u.clave_delegacional', 'd.nombre name_delegacion'
            , 'u.clave_categoria', 'c.nombre name_categoria'
            , 'u.id_unidad_instituto', 'ui.nombre name_unidad_ist'
            , 'r.id_region', 'd.nombre name_region', 'r.clave_regional', 'u.token', 'u.password'
            , 'u.email', 'ui.umae', 'ui.id_tipo_unidad'
            // , "case u.password when md5(concat(u.token, '$password')) then 1 else 0 end as valido_password "
            , 'g.id_grupo', 'g.nombre nombre_grupo', 'g.nivel'
        );

        $this->db->select($select);
        $this->db->from('sistema.usuarios u');
        $this->db->join('sistema.grupos_usuarios gu', 'gu.id_usuario = u.id_usuario', 'left');
        $this->db->join('sistema.grupos g', 'g.id_grupo = gu.id_grupo', 'left');
        $this->db->join('catalogos.categorias c', 'c.clave_categoria = u.clave_categoria', 'left');
        $this->db->join('catalogos.delegaciones d', 'd.clave_delegacional = u.clave_delegacional', 'left');
        $this->db->join('catalogos.unidades_instituto ui', 'ui.id_unidad_instituto = u.id_unidad_instituto', 'left');
        $this->db->join('catalogos.regiones r', 'r.id_region = d.id_region', 'left');

        $this->db->where('u.matricula', $matricula);
        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();
        $return = array();
//        pr($this->db->last_query());
        if (count($result) > 0) {
            $this->load->library('seguridad');
            $cadena = $result[0]['token'] . $password . $result[0]['token'];
            $clave = $this->seguridad->encrypt_sha512($cadena);
//            pr($clave);
//            pr($result[0]['password']);
//            
//            $return['valido'] = $result[0]['valido_password'];
            $return['valido'] = ($result[0]['password'] == $clave) ? 1 : 0;
            $return['datos_user'] = $result[0];
        }

        $query->free_result();
        return $return;
    }

}