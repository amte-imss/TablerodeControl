<?php

/**
 * Description of Ranking_model
 *
 * @author chrigarc
 */
class Ranking_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_regiones()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_region', 'nombre'
        );
        $this->db->select($select);
        $this->db->where('activo', true);
        $regiones = $this->db->get('catalogos.regiones')->result_array();
        return $regiones;
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

    public function get_tipo_unidad_by_delegacion($id_delegacion)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->distinct();
        $select = array(
            'A.id_tipo_unidad', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'B.id_tipo_unidad = A.id_tipo_unidad', 'inner');
        $this->db->join('catalogos.delegaciones C', 'B.id_delegacion = C.id_delegacion', 'inner');
        $this->db->where('A.activa', true);
        $this->db->where('B.activa', true);
        $this->db->where('C.activo', true);
        $this->db->where('B.id_delegacion', $id_delegacion);
        $tipos_unidades = $this->db->get('catalogos.tipos_unidades A')->result_array();
        return $tipos_unidades;
    }

    public function get_tipo_unidad_by_region($id_region)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->distinct();
        $select = array(
            'A.id_tipo_unidad', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'B.id_tipo_unidad = A.id_tipo_unidad', 'inner');
        $this->db->join('catalogos.delegaciones C', 'B.id_delegacion = C.id_delegacion', 'inner');
        $this->db->join('catalogos.regiones D', 'B.id_region = C.id_region', 'inner');
        $this->db->where('A.activa', true);
        $this->db->where('B.activa', true);
        $this->db->where('C.activo', true);
        $this->db->where('D.activo', true);
        $this->db->where('D.id_region', $id_region);
        $tipos_unidades = $this->db->get('catalogos.tipos_unidades A')->result_array();
        return $tipos_unidades;
    }

    public function get_unidades($id_delegacion = 0, $id_tipo_unidad = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_unidad_instituto', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.delegaciones B', 'B.id_delegacion = A.id_delegacion', 'inner');
        $this->db->join('catalogos.tipos_unidades C', ' C.id_tipo_unidad = A.id_tipo_unidad', 'inner');
        $this->db->where('B.activo', true);
        $this->db->where('C.activa', true);
        $this->db->where('A.activa', true);
        $this->db->where('A.id_delegacion', $id_delegacion);
        $this->db->where('A.id_tipo_unidad', $id_tipo_unidad);
        $unidades = $this->db->get('catalogos.unidades_instituto A')->result_array();
        return $unidades;
    }

    public function get_cursos_by_delegacion($id_delegacion = 0, $id_tipo_unidad = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_curso', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.implementaciones B ', ' B.id_curso = A.id_curso', 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C', 'C.id_implementacion = B.id_implementacion', 'inner');
        $this->db->join('catalogos.unidades_instituto D ', ' D.id_unidad_instituto = C.id_unidad_instituto', 'inner');
        $this->db->where('D.id_delegacion', $id_delegacion);
        $this->db->where('D.id_tipo_unidad', $id_tipo_unidad);
        $cursos = $this->db->get('catalogos.cursos A')->result_array();
        return $cursos;
    }
    
        public function get_cursos_by_region($id_region = 0, $id_tipo_unidad = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->distinct();
        $select = array(
            'A.id_curso', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.implementaciones B ', ' B.id_curso = A.id_curso', 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C', 'C.id_implementacion = B.id_implementacion', 'inner');
        $this->db->join('catalogos.unidades_instituto D ', ' D.id_unidad_instituto = C.id_unidad_instituto', 'inner');
        $this->db->join('catalogos.delegaciones E', 'E.id_delegacion = D.id_delegacion', 'inner');
        $this->db->where('E.id_region', $id_region);
        $this->db->where('D.id_tipo_unidad', $id_tipo_unidad);
        $cursos = $this->db->get('catalogos.cursos A')->result_array();
        return $cursos;
    }

    public function get_lista_aprobados($id_curso)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_unidad_instituto', 'D.nombre unidad', 'C.id_curso', 'sum("A".cantidad_alumnos_certificados) cantidad'
        );
        $this->db->select($select);
        $this->db->join('catalogos.implementaciones B', ' B.id_implementacion = A.id_implementacion', 'inner');
        $this->db->join('catalogos.cursos C', 'C.id_curso = B.id_curso', 'inner');
        $this->db->join('catalogos.unidades_instituto D', 'D.id_unidad_instituto = A.id_unidad_instituto', 'inner');
        $this->db->where('C.id_curso', $id_curso);
        $this->db->group_by('A.id_unidad_instituto, D.nombre, C.id_curso');
        $this->db->order_by('cantidad', 'desc');
        $this->db->limit(10);
        $datos = $this->db->get('hechos.hechos_implementaciones_alumnos A ')->result_array();
        return $datos;
    }

    public function get_lista_etm($id_curso)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_unidad_instituto', 'D.nombre unidad', 'C.id_curso', 'sum("A".cantidad_alumnos_certificados) aprobados',
            'sum("A".cantidad_alumnos_inscritos) inscritos', 'sum("AA".cantidad_no_accesos) no_acceso'
        );
        $this->db->select($select);
        $this->db->join('hechos.accesos_implemetaciones AA', 'AA.id_categoria = A.id_categoria and AA.id_implementacion = A.id_implementacion and AA.id_sexo = A.id_sexo and AA.id_unidad_instituto = A.id_unidad_instituto', 'inner');
        $this->db->join('catalogos.implementaciones B', ' B.id_implementacion = A.id_implementacion', 'inner');
        $this->db->join('catalogos.cursos C', 'C.id_curso = B.id_curso', 'inner');
        $this->db->join('catalogos.unidades_instituto D', 'D.id_unidad_instituto = A.id_unidad_instituto', 'inner');
        $this->db->where('C.id_curso', $id_curso);
        $this->db->group_by('A.id_unidad_instituto, D.nombre, C.id_curso');
        $this->db->having('(sum("A".cantidad_alumnos_inscritos)  - sum("AA".cantidad_no_accesos)) > 0');
        $this->db->limit(10);
        $query1 = $this->db->get_compiled_select('hechos.hechos_implementaciones_alumnos A');
        $this->db->reset_query();
        $select = array(
            'unidad',  'aprobados::float/(inscritos::float-no_acceso::float)*100 cantidad'
        );
        $this->db->select($select);
        $this->db->from('('.$query1.') BB');
        $this->db->order_by('cantidad', 'desc');
        $datos = $this->db->get()->result_array();
        
        return $datos;
    }

}
