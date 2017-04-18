<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para obtener datos a mostrar en la sección de información general
 * @version 	: 1.0.0
 * @autor 		: JZDP
 */
class Informacion_general_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    /**
     * El metodo obtiene el total de alumnos inscritos
     * @author JZDP
     * @param  array $params Se forma de los campos que se desea obtener, las condicionales en la búsqueda y el tipo y campo utilziados para el ordenamiento
     * @return array $resultado Contiene arreglo con los datos obtenidos de la base
     */
    public function calcular_totales($params = array()){
         $resultado = array();
        if (array_key_exists('fields', $params)) {
            $this->db->select($params['fields']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']['field'], $params['order']['type']);
        }

        $this->db->select('imp.id_curso, imp.fecha_fin,EXTRACT(MONTH FROM imp.fecha_fin) mes_fin,
            EXTRACT(YEAR FROM imp.fecha_fin) anio_fin, reg.id_region, reg.nombre as region, cur.id_tipo_curso, tc.nombre as tipo_curso,
            hia.id_categoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria, sub.id_subcategoria, sub.nombre as perfil,
            hia.id_unidad_instituto, hia.id_implementacion,  hia.cantidad_alumnos_inscritos, hia.cantidad_alumnos_certificados');

        $this->db->join('catalogos.implementaciones imp', 'imp.id_implementacion=hia.id_implementacion');
        $this->db->join('catalogos.cursos cur', 'cur.id_curso=imp.id_curso');
        $this->db->join('catalogos.tipos_cursos tc', 'tc.id_tipo_curso=cur.id_tipo_curso');
        $this->db->join('catalogos.unidades_instituto uni', 'uni.id_unidad_instituto=hia.id_unidad_instituto', 'left');
        $this->db->join('catalogos.delegaciones del', 'del.id_delegacion=uni.id_delegacion', 'left');
        $this->db->join('catalogos.regiones reg', 'reg.id_region=del.id_region', 'left');
        $this->db->join('catalogos.categorias cat', 'cat.id_categoria=hia.id_categoria', 'left');
        $this->db->join('catalogos.grupos_categorias gc', 'gc.id_grupo_categoria=cat.id_grupo_categoria', 'left');
        $this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=gc.id_subcategoria', 'left');
        /* select --count(*)
        imp.id_curso, imp.fecha_fin,EXTRACT(MONTH FROM imp.fecha_fin) mes_fin,
        EXTRACT(YEAR FROM imp.fecha_fin) anio_fin, reg.id_region, reg.nombre as region,
        cur.id_tipo_curso, tc.nombre as tipo_curso,
        hia.id_categoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria, sub.id_subcategoria, sub.nombre as perfil,
        hia.id_unidad_instituto, hia.id_implementacion,  hia.cantidad_alumnos_inscritos, hia.cantidad_alumnos_certificados
        from hechos.hechos_implementaciones_alumnos hia
        join catalogos.implementaciones imp on imp.id_implementacion=hia.id_implementacion
        join catalogos.cursos cur on cur.id_curso=imp.id_curso
        join catalogos.tipos_cursos tc on tc.id_tipo_curso=cur.id_tipo_curso
        left join catalogos.unidades_instituto uni on uni.id_unidad_instituto=hia.id_unidad_instituto
        left join catalogos.delegaciones del on del.id_delegacion=uni.id_delegacion
        left join catalogos.regiones reg on reg.id_region=del.id_region
        left join catalogos.categorias cat on cat.id_categoria=hia.id_categoria
        left join catalogos.grupos_categorias gc on gc.id_grupo_categoria=cat.id_grupo_categoria
        left join catalogos.subcategorias sub on sub.id_subcategoria=gc.id_subcategoria */
        //$this->db->limit(100);

        $query = $this->db->get('hechos.hechos_implementaciones_alumnos hia'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query());
        $query->free_result(); //Libera la memoria

        return $resultado;
    }
}
