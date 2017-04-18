<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Permiso_model
 *
 * @author chrigarc
 */
class Grupos_usuarios_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_grupos()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $grupos = [];
        $select = array(
            'id_grupo', 'nombre', 'descripcion'
        );
        $this->db->select($select);
        $this->db->where('activo', true);
        $grupos = $this->db->get('sistema.grupos')->result_array();
        return $grupos;
    }

    public function get_grupos_usuario($id_usuario = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
          'A.id_grupo', 'B.nombre', 'B.descripcion'  
        );
        $this->db->select($select);
        $this->db->from('sistema.grupos_usuarios A');
        $this->db->join('sistema.grupos B', 'B.id_grupo = A.id_grupo', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('B.activo', true);
        $this->db->where('A.id_usuario', $id_usuario);
        $grupos = $this->db->get()->result_array();
        return $grupos;
    }

}
