<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
        $this->db->order_by('order', 'asd');
        $subcategorias = $this->db->get('catalogos.grupos_categorias')->result_array();
        return $subcategorias;
    }
    
    public function get_delegaciones($id_region = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_delegacion', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.regiones B', ' B.id_region = A.id_region', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('B.activo', true);
        $this->db->where('A.id_region', $id_region);
        $delegaciones = $this->db->get('catalogos.delegaciones A')->result_array();
        return $delegaciones;
    }
}
