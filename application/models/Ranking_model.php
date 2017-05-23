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
            'id_programa_proyecto', /* 'concat(nombre, $$ [$$, clave, $$]$$) proyecto' */
            'nombre proyecto'
        );
        $this->db->select($select);
        $this->db->where('activo', true);
        $this->db->order_by(2, 'asc');
        $proyectos = $this->db->get('catalogos.programas_proyecto')->result_array();
        return $proyectos;
    }

    public function get_periodos()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->distinct();
        $this->db->select('extract(year from fecha_inicio) periodo');
        $this->db->where('activo', true);
        $this->db->order_by('extract(year from fecha_inicio)');
        $periodos = $this->db->get('catalogos.implementaciones')->result_array();
        return $periodos;
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
                'B.id_unidad_instituto', '"B".nombre nombre', 'G.nombre programa', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        } else
        {
            $select = array(
                'B.id_unidad_instituto', '"B".nombre nombre', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        }
        $this->db->select($select);
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'left');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'left');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');       
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = E.id_programa_proyecto', 'left');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'left');
        $this->db->where("(t.grupo_tipo = 'UMAE' OR t.grupo_tipo = 'CUMAE')");        
        $this->db->where('CI.activa', true);
        if(isset($filtros['periodo']) && !empty($filtros['periodo'])){
            $inicio = $filtros['periodo'].'/01/01';
            $fin = $filtros['periodo'].'/12/31';
            $this->db->where('D.fecha_inicio >=', $inicio);
            $this->db->where('D.fecha_fin <=', $fin);
            $this->db->where('E.anio', $filtros['periodo']);
            
        }
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $group_by = array(
                'B.id_unidad_instituto', '"B".nombre', 'G.nombre'
            );
            $this->db->group_by($group_by);
        } else
        {
            $group_by = array(
                'B.id_unidad_instituto', '"B".nombre'
            );
            $this->db->group_by($group_by);
        }
        $this->db->order_by('cantidad', 'desc');
        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
//        pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

    private function get_aprobados(&$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        
        $grupo_principal[0] = 'A.grupo_delegacion';
        $grupo_principal[1] = 'A.nombre_grupo_delegacion';
        if(isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 1){
            $grupo_principal[0] = 'A.id_delegacion';
            $grupo_principal[1] = 'A.nombre';
        }        
        
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $select = array(
                $grupo_principal[0], $grupo_principal[1].' nombre', 'G.nombre programa', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        } else
        {
            $select = array(
                $grupo_principal[0], $grupo_principal[1].' nombre', 'sum("C".cantidad_alumnos_certificados) cantidad'
            );
        }
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'A.id_delegacion = B.id_delegacion', 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'left');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'left');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');        
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = E.id_programa_proyecto', 'left');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'left');
        $this->db->where("(t.grupo_tipo != 'UMAE' OR t.grupo_tipo != 'CUMAE')");
        $this->db->where('CI.activa', true);
        if(isset($filtros['periodo']) && !empty($filtros['periodo'])){
            $inicio = $filtros['periodo'].'/01/01';
            $fin = $filtros['periodo'].'/12/31';
            $this->db->where('D.fecha_inicio >=', $inicio);
            $this->db->where('D.fecha_fin <=', $fin);
            $this->db->where('E.anio', $filtros['periodo']);
        }
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $this->db->group_by($grupo_principal[0].', '.$grupo_principal[1].', G.nombre');
        } else
        {
            $this->db->group_by($grupo_principal[0].', '.$grupo_principal[1]);
        }
        $this->db->order_by('cantidad', 'desc');
        $datos = $this->db->get('catalogos.delegaciones A')->result_array();
//        pr($this->db->last_query());
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
                'B.id_unidad_instituto', '"B".nombre nombre', 'G.nombre programa',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("C".cantidad_no_accesos) no_acceso'
            );
        } else
        {
            $select = array(
                'B.id_unidad_instituto', '"B".nombre nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("C".cantidad_no_accesos) no_acceso'
            );
        }
        $this->db->select($select);
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'inner');        
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');        
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = E.id_programa_proyecto', 'left');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'left');
        $this->db->where("(t.grupo_tipo = 'UMAE' OR t.grupo_tipo = 'CUMAE')");
        $this->db->where('CI.activa', true);
        if(isset($filtros['periodo']) && !empty($filtros['periodo'])){
            $inicio = $filtros['periodo'].'/01/01';
            $fin = $filtros['periodo'].'/12/31';
            $this->db->where('D.fecha_inicio >=', $inicio);
            $this->db->where('D.fecha_fin <=', $fin);
            $this->db->where('E.anio', $filtros['periodo']);
        }
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $group_by = array(
                'B.id_unidad_instituto', '"B".nombre', 'G.nombre'
            );
            $this->db->group_by($group_by);
        } else
        {
            $group_by = array(
                'B.id_unidad_instituto', '"B".nombre'
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
        $grupo_principal[0] = 'A.grupo_delegacion';
        $grupo_principal[1] = 'A.nombre_grupo_delegacion';
        if(isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 1){
            $grupo_principal[0] = 'A.id_delegacion';
            $grupo_principal[1] = 'A.nombre';
        }        
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $select = array(
                $grupo_principal[0], $grupo_principal[1].' nombre', 'G.nombre programa',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("C".cantidad_no_accesos) no_acceso'
            );
        } else
        {
            $select = array(
                $grupo_principal[0], $grupo_principal[1].' nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("C".cantidad_no_accesos) no_acceso'
            );
        }
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'A.id_delegacion = B.id_delegacion', 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'inner');        
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');        
        $this->db->join('catalogos.programas_proyecto G ', ' G.id_programa_proyecto = E.id_programa_proyecto', 'left');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'left');
        $this->db->where("(t.grupo_tipo != 'UMAE' OR t.grupo_tipo != 'CUMAE')");
        $this->db->where('CI.activa', true);
        if(isset($filtros['periodo']) && !empty($filtros['periodo'])){
            $inicio = $filtros['periodo'].'/01/01';
            $fin = $filtros['periodo'].'/12/31';
            $this->db->where('D.fecha_inicio >=', $inicio);
            $this->db->where('D.fecha_fin <=', $fin);
            $this->db->where('E.anio', $filtros['periodo']);
        }
        if (isset($filtros['programa']) && !empty($filtros['programa']))
        {
            $this->db->where('G.id_programa_proyecto', $filtros['programa']);
            $this->db->group_by($grupo_principal[0].', '.$grupo_principal[1].', G.nombre');
        } else
        {
            $this->db->group_by($grupo_principal[0].', '.$grupo_principal[1]);
        }
        $datos = $this->db->get('catalogos.delegaciones A')->result_array();
//        pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

}
