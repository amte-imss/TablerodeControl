<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Menu_model
 *
 * @author chrigarc
 */
class Menu_model extends CI_Model
{

    //put your code here
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_menu_usuario($array_servicios = [])
    {
        $niveles_menu = 10;
        
        $in = [];
        foreach ($array_servicios as $id_servicio)
        {
            $in[] = $id_servicio;
        }

        $select = array(
            'A.id_modulo id_menu', 'A.nombre label', 'A.url enlace', 'A.id_modulo_padre id_menu_padre'
        );

        $this->db->order_by('A.orden');
        $this->db->select($select);
        $this->db->from('sistema.modulos A');

        $this->db->where('A.activo', true);
        $this->db->where('A.id_configurador', 1); //elemento menu
        // $this->db->where_in('A.id_servicio_rest', $in);

        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();
//        pr($this->db->last_query());
        $query->free_result();
        //procesamos el arreglo para limpiarlo
        $pre_menu = [];
        for ($i = 0; $i < $niveles_menu + 1; $i++)
        {
            foreach ($result as $row)
            {
                //pr($row['id_menu_padre']);

                if (!isset($pre_menu[$row['id_menu']]))
                {
                    $pre_menu[$row['id_menu']]['id_menu_padre'] = $row['id_menu_padre'];
                    $pre_menu[$row['id_menu']]['titulo'] = $row['label'];
                    $pre_menu[$row['id_menu']]['id_menu'] = $row['id_menu'];
                    $pre_menu[$row['id_menu']]['link'] = $row['enlace'];
                }
                if (isset($pre_menu[$row['id_menu_padre']]) /* && !isset($pre_menu[$row['id_menu_padre']]['childs'][$row['id_menu']]) */)
                {
                    $pre_menu[$row['id_menu_padre']]['childs'][$row['id_menu']] = $pre_menu[$row['id_menu']];
                }
            }
        }
        $menu = [];


        foreach ($pre_menu as $row)
        {
            if (empty($row['id_menu_padre']) && !isset($menu[$row['id_menu']]))
            {
                $menu[$row['id_menu']] = $row;
            }
        }
        //pr($pre_menu);
        return $menu;
    }

}
