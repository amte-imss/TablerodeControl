<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Modulo_model
 *
 * @author chrigarc
 */
class Modulo_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_modulos($id_modulo = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_modulo', 'A.nombre', 'A.descripcion', 'A.url', 'A.visible', 'A.orden'
            , 'A.id_configurador', 'B.nombre configurador', 'A.id_modulo_padre', 'A.visible'
        );
        $this->db->select($select);
        $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'inner');
        $this->db->where('activo', true);
        if ($id_modulo > 0)
        {
            $this->db->where('A.id_modulo', $id_modulo);
        }
        $modulos = $this->db->get('sistema.modulos A')->result_array();
        return $modulos;
    }

    public function get_modulos_usuario($id_usuario = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_modulo', 'A.nombre', 'A.descripcion', 'A.url', 'A.visible', 'A.orden'
            , 'A.id_configurador', 'B.nombre configurador'
        );
        $this->db->select($select);
        $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'inner');
        $this->db->join('sistema.modulos_grupos C', ' C.id_modulo = A.id_modulo', 'inner');
        $this->db->join('sistema.grupos_usuarios D', 'D.id_grupo = C.id_grupo', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('C.activo', true);
        $this->db->where('D.activo', true);
        $this->db->where('C.id_grupo', $id_grupo);
        $modulos = $this->db->get('sistema.modulos A')->result_array();
    }

    public function get_modulos_grupo($id_grupo = 0, $todos = false)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_modulo', 'A.nombre', 'A.descripcion', 'A.url', 'A.visible', 'A.orden'
            , 'A.id_configurador', 'B.nombre configurador', 'C.id_grupo'
        );
        $this->db->select($select);
        if ($todos)
        {
            $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'left');
            $this->db->join('sistema.modulos_grupos C', ' C.id_modulo = A.id_modulo and C.id_grupo = ' . $id_grupo . ' AND C.activo', 'left');
            $this->db->where('A.activo', true);
        } else
        {
            $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'inner');
            $this->db->join('sistema.modulos_grupos C', ' C.id_modulo = A.id_modulo', 'inner');
            $this->db->where('A.activo', true);
            $this->db->where('C.activo', true);
            $this->db->where('C.id_grupo', $id_grupo);
        }
        $modulos = $this->db->get('sistema.modulos A')->result_array();
        //pr($this->db->last_query());
        return $modulos;
    }

    public function get_configuradores()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_configurador', 'nombre', 'descripcion'
        );
        $this->db->select($select);
        $configuradores = $this->db->get('sistema.configuradores')->result_array();
        return $configuradores;
    }

    public function upsert_modulos_grupo($id_grupo = 0, $modulos = array(), $opciones = array())
    {
        $this->db->trans_begin();
        foreach ($modulos as $modulo)
        {
            $id_modulo = $modulo['id_modulo'];
            $configurador = $opciones['configurador' . $id_modulo];
            $activo = (isset($opciones['activo' . $id_modulo])) ? true : false;
            $this->upsert_modulo_grupo($id_grupo, $id_modulo, $configurador, $activo);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
        }
    }

    private function upsert_modulo_grupo($id_grupo = 0, $id_modulo = 0, $configurador = '', $activo = false)
    {
        //pr('[CH][modulo_model][upsert_modulo_grupo] id_grupo: '.$id_grupo.' id_modulo: '.$id_modulo.', conf: '.$configurador.', activo: '.($activo?'true':'false') );
        if ($id_grupo > 0 && $id_modulo > 0)
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('count(*) cantidad');
            $this->db->start_cache();
            $this->db->where('id_grupo', $id_grupo);
            $this->db->where('id_modulo', $id_modulo);
            $this->db->stop_cache();
            $existe = $this->db->get('sistema.modulos_grupos')->result_array()[0]['cantidad'] != 0;
            if ($existe)
            {
                $this->db->set('activo', $activo);
                $this->db->update('sistema.modulos_grupos');
            } else
            {
                $this->db->flush_cache();
                $insert = array(
                    'id_modulo' => $id_modulo,
                    'id_grupo' => $id_grupo,
                    'configurador' => $configurador
                );
                $this->db->insert('sistema.modulos_grupos', $insert);
            }
        }
    }

    public function update($id_modulo = 0, &$datos = array())
    {
        $status = false;
        if ($id_modulo > 0)
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->set('nombre', $datos['nombre']);
            $this->db->set('url', $datos['url']);
            $this->db->set('id_modulo_padre', $datos['padre']);
            $this->db->set('orden', $datos['orden']);
            $this->db->set('id_configurador', $datos['tipo']);
            $this->db->set('visible', $datos['visible']);
            $this->db->where('id_modulo', $id_modulo);
            $this->db->update('sistema.modulos');
            $status = true;
        }
        return $status;
    }

    public function insert(&$datos = array()){
        
    }
}
