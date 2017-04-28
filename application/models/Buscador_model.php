<?php

/**
 * Description of Buscador_model
 *
 * @author chrigarc
 */
class Buscador_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_grupos_categorias($filtros = array()){
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_grupo_categoria', 'nombre'
        );
        $this->db->select($select);
        if(isset($filtros['subcategoria'])){
            $this->db->where('id_subcategoria', $filtros['subcategoria']);
        }
        $subcategorias = $this->db->get('catalogos.grupos_categorias')->result_array();
        return $subcategorias;
    }
}
