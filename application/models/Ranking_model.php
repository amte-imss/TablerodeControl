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

    public function get_programas()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_programa_proyecto', 'concat(nombre, $$ [$$, clave, $$]$$) proyecto'
        );
        $this->db->select($select);
        $this->db->where('activo', true);
        $proyectos = $this->db->get('catalogos.programas_proyecto')->result_array();
        return $proyectos;
    }

    public function get_periodos()
    {
        return array('2016' => '2016');
    }

    public function get_tipos_reportes()
    {
        return array(1 => 'Aprobados', 2 => 'Por eficiencia terminal');
    }

    public function get_data($usuario = null, $filtros = [])
    {
        $datos = [];
        if ($usuario != null)
        {
//            $usuario['umae'] = true;
            if ($usuario['umae'])
            {
                if (isset($filtros['tipo']) && $filtros['tipo'] == 2)
                {
                    $datos = $this->get_eficiencia_terminal_umae($filtros);
                } else
                {
                    $datos = $this->get_aprobados_umae($filtros);
                }
            } else
            {
                if (isset($filtros['tipo']) && $filtros['tipo'] == 2)
                {
                    $datos = $this->get_eficiencia_terminal($filtros);
                } else
                {
                    $datos = $this->get_aprobados($filtros);
                }
            }
        }
        return $datos;
    }

    private function get_aprobados_umae(&$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $select = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$) nombre', 'G.nombre programa', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        } else
        {
            $select = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$) nombre', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        }
        $this->db->select($select);
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'left');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');
        $this->db->join('catalogos.curso_programa F ', ' F.id_curso = E.id_curso', 'left');
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = F.id_programa_proyecto', 'left');
        $this->db->where('B.umae', true);
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
             $group_by = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$)', 'G.nombre'
            );
            $this->db->group_by($group_by);
        } else
        {
            $group_by = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$)'
            );
            $this->db->group_by($group_by);
        }
        $this->db->order_by('cantidad', 'desc');
        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
        //pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

    private function get_aprobados(&$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $select = array(
                'A.id_delegacion', 'A.nombre', 'G.nombre programa', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        } else
        {
            $select = array(
                'A.id_delegacion', 'A.nombre', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        }
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'A.id_delegacion = B.id_delegacion', 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'left');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');
        $this->db->join('catalogos.curso_programa F ', ' F.id_curso = E.id_curso', 'left');
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = F.id_programa_proyecto', 'left');
        $this->db->where('B.umae', false);
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $this->db->group_by('A.id_delegacion, A.nombre, G.nombre');
        } else
        {
            $this->db->group_by('A.id_delegacion, A.nombre');
        }
        $this->db->order_by('cantidad', 'desc');
        $datos = $this->db->get('catalogos.delegaciones A')->result_array();
        //pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

    private function get_eficiencia_terminal_umae(&$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $select = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$) nombre', 'G.nombre programa',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        } else
        {
            $select = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$) nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        }
        $this->db->select($select);
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('hechos.accesos_implemetaciones AA', ' AA.id_categoria = C.id_categoria and AA.id_implementacion = C.id_implementacion and AA.id_sexo = C.id_sexo and AA.id_unidad_instituto = C.id_unidad_instituto', 'inner');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');
        $this->db->join('catalogos.curso_programa F ', ' F.id_curso = E.id_curso', 'left');
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = F.id_programa_proyecto', 'left');
        $this->db->where('B.umae', true);
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $group_by = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$)', 'G.nombre'
            );
            $this->db->group_by($group_by);
        } else
        {
            $group_by = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$)'
            );
            $this->db->group_by($group_by);
        }
        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
        //pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

    private function get_eficiencia_terminal(&$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $select = array(
                'A.id_delegacion', 'A.nombre', 'G.nombre programa',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        } else
        {
            $select = array(
                'A.id_delegacion', 'A.nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        }
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'A.id_delegacion = B.id_delegacion', 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('hechos.accesos_implemetaciones AA', ' AA.id_categoria = C.id_categoria and AA.id_implementacion = C.id_implementacion and AA.id_sexo = C.id_sexo and AA.id_unidad_instituto = C.id_unidad_instituto', 'inner');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');
        $this->db->join('catalogos.curso_programa F ', ' F.id_curso = E.id_curso', 'left');
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = F.id_programa_proyecto', 'left');
        $this->db->where('B.umae', false);
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $this->db->group_by('A.id_delegacion, A.nombre, G.nombre');
        } else
        {
            $this->db->group_by('A.id_delegacion, A.nombre');
        }
        $datos = $this->db->get('catalogos.delegaciones A')->result_array();
        //pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

}
