<?php

/**
 * Description of Comparativa_model
 *
 * @author chrigarc
 */
class Comparativa_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_tipos_comparativas()
    {
        return array(1 => 'Tipo de curso', 2 => 'Perfil');
    }

    public function get_tipos_reportes()
    {
        return array(1 => 'Inscritos', 2 => 'Aprobados', 3 => 'Eficiencia terminal',
            5 => 'No aprobados');
    }

    public function get_comparar_perfil($filtros = [])
    {
        $datos['unidad1'] = $this->get_data_perfil($filtros['unidad1'], $filtros);
        $datos['unidad2'] = $this->get_data_perfil($filtros['unidad2'], $filtros);
        return $datos;
    }

    public function get_comparar_tipo_curso($filtros = [])
    {
        $datos['unidad1'] = $this->get_data_tipo_curso($filtros['unidad1'], $filtros);
        $datos['unidad2'] = $this->get_data_tipo_curso($filtros['unidad2'], $filtros);
        return $datos;
    }

    private function get_data_tipo_curso($unidad = 0, &$filtros = array())
    {
        $datos = [];

        $pre_datos = $this->get_data_tipo_curso_aux($unidad, $filtros);
        $datos['unidad'] = $pre_datos[0]['nombre'];
        $datos['cantidad'] = 0;
        switch ($filtros['reporte'])
        {
            case 1 : $datos['cantidad'] = $pre_datos[0]['inscritos'];
                break;
            case 2 : $datos['cantidad'] = $pre_datos[0]['aprobados'];
                break;
            case 3: if ($pre_datos[0]['inscritos'] != $pre_datos[0]['no_acceso'])
                {
                    $datos['cantidad'] = (($pre_datos[0]['aprobados'] / ($pre_datos[0]['inscritos'] - $pre_datos[0]['no_acceso'])) * 100);
                }
                break;
            case 4: $datos['cantidad'] = $pre_datos[0]['no_acceso'];
                break;
            case 5 : $datos['cantidad'] = $pre_datos[0]['inscritos'] - $pre_datos[0]['aprobados'];
                break;
        }
        $datos['cantidad'] = intval($datos['cantidad']);
        return $datos;
    }

    private function get_data_tipo_curso_aux($unidad = 0, &$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();

        if ($unidad > 0)
        {
            $select = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$) nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        } else
        {
            $select = array(
                '0', "'PROMEDIO' nombre",
                'avg("C".cantidad_alumnos_certificados) aprobados',
                'avg("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        }

        $this->db->select($select);
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('hechos.accesos_implemetaciones AA', ' AA.id_categoria = C.id_categoria and AA.id_implementacion = C.id_implementacion and AA.id_sexo = C.id_sexo and AA.id_unidad_instituto = C.id_unidad_instituto', 'left');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'left');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'left');
        if (isset($filtros['umae']) && $filtros['umae'])
        {
            $this->db->where('B.umae', true);
        }else{
            $this->db->where('B.umae', false);
        }
        if (isset($filtros['tipo_curso']))
        {
            $this->db->where('E.id_tipo_curso', $filtros['tipo_curso']);
        }
        if ($unidad > 0)
        {
            $this->db->where('B.id_unidad_instituto', $unidad);

            $group_by = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$)'
            );
            $this->db->group_by($group_by);
        }
        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
        //pr($this->db->last_query());
        //pr($filtros);
        if (count($datos) == 0)
        {
            $datos[0] = array(
                'id_unidad_instituto' => $unidad,
                'nombre' => '',
                'aprobados' => 0,
                'inscritos' => 0,
                'no_acceso' => 0
            );
        }
        return $datos;
    }

    private function get_data_perfil($unidad = 0, &$filtros = array())
    {
        $datos = [];

        $pre_datos = $this->get_data_perfil_aux($unidad, $filtros);
        $datos['unidad'] = $pre_datos[0]['nombre'];
        $datos['cantidad'] = 0;
        switch ($filtros['reporte'])
        {
            case 1 : $datos['cantidad'] = $pre_datos[0]['inscritos'];
                break;
            case 2 : $datos['cantidad'] = $pre_datos[0]['aprobados'];
                break;
            case 3: if ($pre_datos[0]['inscritos'] != $pre_datos[0]['no_acceso'])
                {
                    $datos['cantidad'] = (($pre_datos[0]['aprobados'] / ($pre_datos[0]['inscritos'] - $pre_datos[0]['no_acceso'])) * 100);
                }
                break;
            case 4: $datos['cantidad'] = $pre_datos[0]['no_acceso'];
                break;
            case 5 : $datos['cantidad'] = $pre_datos[0]['inscritos'] - $pre_datos[0]['aprobados'];
                break;
        }
        $datos['cantidad'] = intval($datos['cantidad']);
        return $datos;
    }

    private function get_data_perfil_aux($unidad = 0, &$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();

        if ($unidad > 0)
        {
            $select = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$) nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("AA".cantidad_no_accesos) no_acceso'
            );
        } else
        {
            $select = array(
                '0', "'PROMEDIO' nombre",
                'avg("C".cantidad_alumnos_certificados) aprobados',
                'avg("C".cantidad_alumnos_inscritos) inscritos',
                'avg("AA".cantidad_no_accesos) no_acceso'
            );
        }

        $this->db->select($select);
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('hechos.accesos_implemetaciones AA', ' AA.id_categoria = C.id_categoria and AA.id_implementacion = C.id_implementacion and AA.id_sexo = C.id_sexo and AA.id_unidad_instituto = C.id_unidad_instituto', 'left');
        $this->db->join('catalogos.categorias I', 'I.id_categoria = C.id_categoria', 'inner');
        $this->db->join('catalogos.grupos_categorias H', 'H.id_grupo_categoria = I.id_grupo_categoria', 'left');
        if (isset($filtros['umae']) && $filtros['umae'])
        {
            $this->db->where('B.umae', true);
        }else{
            $this->db->where('B.umae', false);
        }
        if (isset($filtros['subperfil']))
        {
            $this->db->where('H.id_grupo_categoria', $filtros['subperfil']);
        }
        if ($unidad > 0)
        {
            $this->db->where('B.id_unidad_instituto', $unidad);
            $group_by = array(
                'B.id_unidad_instituto', 'concat("B".nombre, $$[$$, "B".clave_unidad, $$]$$)'
            );
            $this->db->group_by($group_by);
        }


        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
        if (count($datos) == 0)
        {
            $datos[0] = array(
                'id_unidad_instituto' => $unidad,
                'nombre' => '',
                'aprobados' => 0,
                'inscritos' => 0,
                'no_acceso' => 0
            );
        }
        //pr($this->db->last_query());
        //pr($filtros);
        return $datos;
    }

}
