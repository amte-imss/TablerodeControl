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
        $this->load->config('general');
    }

    /**
     * El metodo obtiene el total de alumnos inscritos
     * @author JZDP
     * @param  array $params Se forma de los campos que se desea obtener, las condicionales en la búsqueda y el tipo y campo utilziados para el ordenamiento
     * @return array $resultado Contiene arreglo con los datos obtenidos de la base
     */
    public function calcular_totales($params = array()){
        //pr($params);
        $resultado = array();
        //Condiciones utilizadas en informacion_general/index
        if(isset($params['perfil']) AND !empty($params['perfil'])){
            $this->db->where('sub.id_subcategoria='.$params['perfil']);
        }        
        if(isset($params['tipo_curso']) AND !empty($params['tipo_curso'])){
            $this->db->where('tc.id_tipo_curso='.$params['tipo_curso']);
        }        
        if(isset($params['periodo']) AND !empty($params['periodo'])){
            $this->db->where('EXTRACT(YEAR FROM imp.fecha_fin)='.$params['periodo']);
        }
        if(isset($params['nivel_atencion']) AND is_numeric($params['nivel_atencion'])){
            if($params['nivel_atencion']==0){//Agregar condicional para nivel de atención no asignado
                $this->db->where('uni.nivel_atencion IS NULL');
            } else {
                $this->db->where('uni.nivel_atencion='.$params['nivel_atencion']);
            }
        }
        if(isset($params['region']) AND !empty($params['region'])){
            $this->db->where('reg.id_region='.$params['region']);
        }
        if(isset($params['tipos_busqueda']) AND $params['tipos_busqueda']==$this->config->item('tipo_busqueda')['DELEGACION']['id']){
            $this->db->where('uni.umae=false');
            if(isset($params['delegacion']) AND !empty($params['delegacion'])){
                $this->db->where('del.id_delegacion='.$params['delegacion']);
            }
        }
        if(isset($params['tipos_busqueda']) AND $params['tipos_busqueda']==$this->config->item('tipo_busqueda')['UMAE']['id']){
            $this->db->where('uni.umae=true');
            if(isset($params['umae']) AND !empty($params['umae'])){
                $this->db->where('uni.id_unidad_instituto='.$params['umae']);
            }
        }
        //Condiciones utilizadas en informacion_general/perfil
        if(isset($params['anio']) AND !empty($params['anio'])){
            $this->db->where('EXTRACT(YEAR FROM imp.fecha_fin)='.$params['anio']);
        }
        if(isset($params['perfil_seleccion']) AND !empty($params['perfil_seleccion'])){
            $this->db->where('gc.id_grupo_categoria IN ('.$params['perfil_seleccion'].')');
        }
        if(isset($params['tipo_curso_seleccion']) AND !empty($params['tipo_curso_seleccion'])){
            $this->db->where('tc.id_tipo_curso IN ('.$params['tipo_curso_seleccion'].')');
        }
        if (array_key_exists('fields', $params)) {
            $this->db->select($params['fields']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']['field'], $params['order']['type']);
        }
        ////Se agregan condiones establecidas para el tipo de grupo
        $this->load->library('Configuracion_grupos');
        $this->load->library('Catalogo_listado');
        $configuracion = $this->configuracion_grupos->obtener_tipos_busqueda();
        //pr($configuracion);
        if(!empty($configuracion['condicion_calcular_totales'])){
            $this->db->where($configuracion['condicion_calcular_totales']);
        }
        ////Fin se agregan condiones establecidas para el tipo de grupo

        //Periodo
        $periodo = '';
        if(isset($params['periodo_seleccion']) AND !empty($params['periodo_seleccion'])){
            $per = $this->config->item('periodo');
            //pr($per);
            if($params['periodo_seleccion']==$per['SEMESTRAL']['id']){
               $periodo = ", (CASE WHEN date_part('month', fecha_fin) <= 6 THEN 1 ELSE 2 END) as periodo";
            } elseif($params['periodo_seleccion']==$per['TRIMESTRAL']['id']){
                $periodo = ', EXTRACT(quarter FROM fecha_fin) as periodo';
            } elseif($params['periodo_seleccion']==$per['BIMESTRAL']['id']){
                $periodo = ', (EXTRACT(month FROM fecha_fin)/2+.1):: Integer as periodo';
            } elseif($params['periodo_seleccion']==$per['MENSUAL']['id']){
                $periodo = ', EXTRACT(month FROM fecha_fin) as periodo';
            } /* else {
                $this->db->where('EXTRACT(YEAR FROM imp.fecha_fin)='.$params['periodo_seleccion'].' as periodo');
            }*/
        }
        //$this->db->limit('500');
        if(isset($params['destino']) AND !empty($params['destino'])){ ///Se utiliza para construir los listados (tree) de vista por_perfil y por_tipo_curso
            $this->db->select('gc.id_grupo_categoria, gc.nombre as grupo_categoria, sub.id_subcategoria, sub.nombre as perfil, cur.id_tipo_curso, tc.nombre as tipo_curso');
            $this->db->group_by('gc.id_grupo_categoria, gc.nombre, sub.id_subcategoria, sub.nombre, cur.id_tipo_curso, tc.nombre');
        } else {
            $this->db->select('imp.id_curso, imp.fecha_fin,EXTRACT(MONTH FROM imp.fecha_fin) mes_fin, mes.nombre as mes,
                EXTRACT(YEAR FROM imp.fecha_fin) anio_fin, reg.id_region, reg.nombre as region, cur.id_tipo_curso, tc.nombre as tipo_curso,
                del.id_delegacion, del.nombre as delegacion, uni.id_unidad_instituto, uni.clave_unidad, uni.nombre as unidades_instituto, uni.umae,
                hia.id_categoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria, sub.id_subcategoria, sub.nombre as perfil,
                hia.id_unidad_instituto, hia.id_implementacion, hia.cantidad_alumnos_inscritos, hia.cantidad_alumnos_certificados, 
                case when uni.nivel_atencion=1 then \'Primer nivel\' when uni.nivel_atencion=2 then \'Segundo nivel\' when uni.nivel_atencion=3 then \'Tercer nivel\' else \'Nivel no disponible\' end as nivel_atencion,
                COALESCE(no_acc.cantidad_no_accesos, 0) as cantidad_no_accesos'.$periodo);
        }

        $this->db->join('hechos.accesos_implemetaciones no_acc', 'no_acc.id_unidad_instituto=hia.id_unidad_instituto AND no_acc.id_implementacion=hia.id_implementacion AND no_acc.id_categoria=hia.id_categoria AND no_acc.id_sexo=hia.id_sexo', 'left');
        $this->db->join('catalogos.implementaciones imp', 'imp.id_implementacion=hia.id_implementacion');
        $this->db->join('catalogos.meses mes', 'mes.id_mes=EXTRACT(MONTH FROM imp.fecha_fin)');
        $this->db->join('catalogos.cursos cur', 'cur.id_curso=imp.id_curso');
        $this->db->join('catalogos.tipos_cursos tc', 'tc.id_tipo_curso=cur.id_tipo_curso');
        $this->db->join('catalogos.unidades_instituto uni', 'uni.id_unidad_instituto=hia.id_unidad_instituto', 'left');
        $this->db->join('catalogos.delegaciones del', 'del.id_delegacion=uni.id_delegacion', 'left');
        $this->db->join('catalogos.regiones reg', 'reg.id_region=del.id_region', 'left');
        $this->db->join('catalogos.categorias cat', 'cat.id_categoria=hia.id_categoria', 'left');
        $this->db->join('catalogos.grupos_categorias gc', 'gc.id_grupo_categoria=cat.id_grupo_categoria', 'left');
        $this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=gc.id_subcategoria', 'left');

        $query = $this->db->get('hechos.hechos_implementaciones_alumnos hia'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query()); exit();
        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function obtener_listado_subcategorias($params = array()){
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
        $this->db->join('grupos_categorias gc', 'gc.id_subcategoria=sub.id_subcategoria', 'left');
        $query = $this->db->get('subcategorias sub'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query());
        $query->free_result(); //Libera la memoria

        return $resultado;
    }
}
