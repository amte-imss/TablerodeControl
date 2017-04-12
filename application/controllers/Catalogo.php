<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP
 * */
class Catalogo extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('grocery_CRUD');
        //$this->load->model('Login_model', 'lm');
        $data_user = $this->session->userdata('usuario');
        //$info_user = $this->lm->get_genera_info_usuario($data_user['matricula']);
        $datos_perfil['usuario'] = $data_user;
        //$datos_perfil['menu'] = $info_user['ui']['menu'];
        //$this->ficha_usuario = $this->load->view('ci_template/ficha_usuario', $datos_perfil, true);
        //$this->menu = $this->load->view('ci_template/menu', $datos_perfil, true);
        $this->ficha_usuario = null;
        $this->menu = null;
    }

    public function index()
    {
        redirect(site_url());
    }
    
    public function alineacion_estrategica()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('alineaciones_estrategicas');
            $crud->set_subject('Alineación estratégica');
            $crud->display_as('id_alineacion_estrategica','ID');
            $crud->display_as('id_curso','Curso');
            $crud->display_as('id_tema_prioritario','Tema prioritario');
            $crud->display_as('id_linea_accion','Línea de acción');
            $crud->display_as('id_linea_estrategica','Línea estratégica');
            $crud->display_as('id_programa_proyecto','Programa / proyecto');
            $crud->display_as('descripcion','Descripción');
            
            $crud->set_primary_key('id_alineacion_estrategica','alineaciones_estrategicas'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_curso','cursos');
            $crud->set_primary_key('id_tema_prioritario','temas_prioritarios');
            $crud->set_primary_key('id_linea_accion','lineas_accion');
            $crud->set_primary_key('id_linea_estrategica','lineas_estrategicas');
            $crud->set_primary_key('id_programa_proyecto','programas_proyecto');
            
            //$crud->columns('id_unidad_instituto','clave_unidad','nombre','id_delegacion','clave_presupuestal','id_tipo_unidad','umae'); //Definir columnas a mostrar en el listado y su orden
            //$crud->add_fields('clave_unidad','nombre','id_delegacion','clave_presupuestal','fecha','nivel_atencion','id_tipo_unidad','umae','latitud','longitud','activa'); //Definir campos que se van a agregar y su orden
            //$crud->edit_fields('clave_unidad','nombre','id_delegacion','clave_presupuestal','fecha','nivel_atencion','id_tipo_unidad','umae','latitud','longitud','activa'); //Definir campos que se van a editar y su orden
            
            $crud->set_relation('id_curso','cursos','{nombre} ({clave})', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('id_tema_prioritario','temas_prioritarios','{nombre} ({clave})', null, 'nombre');
            $crud->set_relation('id_linea_accion','lineas_accion','{nombre} ({clave})', null, 'nombre');
            $crud->set_relation('id_linea_estrategica','lineas_estrategicas','{nombre} ({clave})', null, 'nombre');
            $crud->set_relation('id_programa_proyecto','programas_proyecto','{nombre} ({clave})', null, 'nombre');
            
            $crud->columns('id_alineacion_estrategica','id_tema_prioritario','id_linea_estrategica','id_linea_accion','id_programa_proyecto','id_curso','descripcion'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('id_tema_prioritario','id_linea_estrategica','id_linea_accion','id_programa_proyecto','id_curso','descripcion'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('id_tema_prioritario','id_linea_estrategica','id_linea_accion','id_programa_proyecto','id_curso','descripcion'); //Definir campos que se van a editar y su orden
            
            $crud->set_rules('id_curso','Curso','required');
            $crud->set_rules('id_tema_prioritario','Tema prioritario','required');
            $crud->set_rules('id_linea_accion','Línea de acción','required');
            $crud->set_rules('id_linea_estrategica','Línea estratégica','required');
            $crud->set_rules('id_programa_proyecto','Programa / proyecto','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function categoria()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('categorias');
            
            $crud->set_subject('Categoría');
            $crud->display_as('id_categoria','ID');
            $crud->display_as('nombre','Nombre de categoría');
            $crud->display_as('id_grupo_categoria','Grupo de categoría');
            $crud->display_as('id_subcategoria','Subcategoría');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('clave_categoria','Clave');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('inversion_estimada','Inversión estimada');
            $crud->display_as('activa','Activa');
            
            $crud->set_relation('id_grupo_categoria','grupos_categorias','nombre', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('id_subcategoria','subcategorias','nombre', null, 'nombre');
            
            $crud->columns('clave_categoria','nombre','id_grupo_categoria','id_subcategoria','activa'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_categoria','nombre','id_grupo_categoria','id_subcategoria','descripcion','inversion_estimada','fecha','activa'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_categoria','nombre','id_grupo_categoria','id_subcategoria','descripcion','inversion_estimada','fecha','activa'); //Definir campos que se van a editar y su orden
            
            //$crud->field_type('activa', 'true_false');
            $crud->change_field_type('activa', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de categoría','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activa','Activa','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function curso()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('cursos');
            
            $crud->set_subject('Curso');
            $crud->display_as('id_curso','ID');
            $crud->display_as('clave','Clave de categoría');
            $crud->display_as('nombre','Nombre de categoría');
            $crud->display_as('id_tipo_curso','Tipo de curso');
            $crud->display_as('id_modalidad','Modalidad');
            $crud->display_as('tutorizado','Tutorizado');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_tipo_curso','tipos_cursos'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->set_relation('id_tipo_curso','tipos_cursos','nombre', null, 'nombre'); //Establecer relaciones
            
            $crud->columns('id_curso','clave','nombre','id_tipo_curso','id_modalidad','tutorizado','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','id_tipo_curso','id_modalidad','tutorizado','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','id_tipo_curso','id_modalidad','tutorizado','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('tutorizado', 'true_false', array(0=>'No tutorizado', 1=>'Tutorizado'));
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('clave','Clave de categoría','trim|required');
            $crud->set_rules('nombre','Nombre de categoría','trim|required');
            $crud->set_rules('id_tipo_curso','Tipo de curso','required');
            $crud->set_rules('id_modalidad','Modalidad','required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function delegacion()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('delegaciones');
            
            $crud->set_subject('Delegación');
            $crud->display_as('clave_delegacional','Clave');
            $crud->display_as('nombre','Nombre de la delegación');
            $crud->display_as('id_region','Región');
            $crud->display_as('configuraciones','Configuraciones');
            $crud->display_as('latitud','Latitud');
            $crud->display_as('longitud','Longitud');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            
            $crud->set_primary_key('id_region','regiones'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_delegacion','delegaciones');
            
            $crud->set_relation('id_region','regiones','nombre', null, 'nombre'); //Establecer relaciones
            
            $crud->columns('clave_delegacional','nombre','id_region','latitud','longitud','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_delegacional','nombre','id_region','latitud','longitud','configuraciones','fecha','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_delegacional','nombre','id_region','latitud','longitud','configuraciones','fecha','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de categoría','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function departamento()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('departamentos_instituto');
            
            $crud->set_subject('Departamento');
            $crud->display_as('clave_departamental','Clave');
            $crud->display_as('nombre','Nombre del departamento');
            $crud->display_as('id_unidad_instituto','Unidad');
            $crud->display_as('activa','Activo');
            
            $crud->set_primary_key('id_departamento_instituto','departamentos_instituto'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_unidad_instituto','unidades_instituto');
            
            $crud->set_relation('id_unidad_instituto','unidades_instituto','{nombre} ({clave_unidad})', null, 'clave_unidad'); //Establecer relaciones
            
            $crud->columns('clave_departamental','nombre','id_unidad_instituto','activa'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_departamental','nombre','id_unidad_instituto','activa'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_departamental','nombre','id_unidad_instituto','activa'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activa', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de departamento','trim|required');
            $crud->set_rules('clave_departamental','Clave','trim|required');
            $crud->set_rules('id_unidad_instituto','Unidad','required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function grupo_categoria()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('grupos_categorias');
            
            $crud->set_subject('Grupo categoría');
            $crud->display_as('id_grupo_categoria','ID');
            $crud->display_as('clave','Clave');
            $crud->display_as('nombre','Nombre del grupo-categoría');
            $crud->display_as('descripcion','Descripción');            
            
            $crud->set_primary_key('id_grupo_categoria','grupos_categorias'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_grupo_categoria','clave','nombre'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','descripcion'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','descripcion'); //Definir campos que se van a editar y su orden
            
            $crud->set_rules('nombre','Nombre del grupo-categoría','trim|required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function implementacion()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('implementaciones');
            
            $crud->set_subject('Implementación');
            $crud->display_as('id_implementacion','ID');
            $crud->display_as('id_curso','Curso');
            $crud->display_as('clave','Clave');
            $crud->display_as('nombre','Nombre de la implementación');
            $crud->display_as('id_remoto','ID remoto');
            $crud->display_as('fecha_inicio','Fecha inicial');
            $crud->display_as('fecha_fin','Fecha final');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_implementacion','implementaciones'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->set_relation('id_curso','cursos','{nombre} ({clave})', null, 'clave'); //Establecer relaciones
            
            $crud->columns('id_implementacion','id_curso','clave','nombre','fecha_inicio','fecha_fin','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('id_curso','clave','nombre','id_remoto','fecha_inicio','fecha_fin','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('id_curso','clave','nombre','id_remoto','fecha_inicio','fecha_fin','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('id_curso','Curso','required');
            $crud->set_rules('clave','Clave','trim|required');
            $crud->set_rules('nombre','Nombre de departamento','trim|required');
            $crud->set_rules('id_remoto','ID remoto','trim|required|integer');
            $crud->set_rules('fecha_inicio','Fecha inicial','trim|required');
            $crud->set_rules('fecha_fin','Fecha final','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function linea_accion()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('lineas_accion');
            
            $crud->set_subject('Línea de acción');
            $crud->display_as('id_linea_accion','ID');
            $crud->display_as('clave','Clave');
            $crud->display_as('nombre','Nombre de la línea de acción');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_linea_accion','lineas_accion'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_linea_accion','clave','nombre','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','fecha','descripcion','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','fecha','descripcion','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de departamento','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function linea_estrategica()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('lineas_estrategicas');
            
            $crud->set_subject('Línea estratégica');
            $crud->display_as('id_linea_estrategica','ID');
            $crud->display_as('clave','Clave');
            $crud->display_as('nombre','Nombre de la línea estratégica');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_linea_estrategica','lineas_estrategicas'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_linea_estrategica','clave','nombre','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','fecha','descripcion','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','fecha','descripcion','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de departamento','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function perfiles_alumno()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('perfiles_alumnos');
            
            $crud->set_subject('Perfil de alumno');
            $crud->display_as('id_perfil_alumno','ID');
            $crud->display_as('clave_perfil','Clave');
            $crud->display_as('nombre','Nombre del perfil del alumno');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('fecha','Fecha');
            
            $crud->set_primary_key('id_perfil_alumno','perfiles_alumno'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_perfil_alumno','clave_perfil','nombre','fecha'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_perfil','nombre','descripcion','fecha'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_perfil','nombre','descripcion','fecha'); //Definir campos que se van a editar y su orden
            
            $crud->set_rules('nombre','Nombre de departamento','trim|required');
            $crud->set_rules('clave_perfil','Clave','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function programa_proyecto()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('programas_proyecto');
            
            $crud->set_subject('Programa - proyecto');
            $crud->display_as('id_programa_proyecto','ID');
            $crud->display_as('clave','Clave');
            $crud->display_as('nombre','Nombre del programa - proyecto');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_programa_proyecto','programas_proyecto'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_programa_proyecto','clave','nombre','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','descripcion','fecha','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','descripcion','fecha','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de departamento','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function region()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('regiones');
            
            $crud->set_subject('Región');
            $crud->display_as('id_region','ID');
            $crud->display_as('clave_regional','Clave');
            $crud->display_as('nombre','Nombre de la región');
            $crud->display_as('configuraciones','Configuraciones');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_region','regiones'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_region','clave_regional','nombre','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_regional','nombre','fecha','configuraciones','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_regional','nombre','fecha','configuraciones','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre de la región','trim|required');
            $crud->set_rules('clave_regional','Clave','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tema_prioritario()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado
            
            $crud = $this->new_crud();
            $crud->set_table('temas_prioritarios');
            
            $crud->set_subject('Tema prioritario');
            $crud->display_as('id_tema_prioritario','ID');
            $crud->display_as('clave','Clave');
            $crud->display_as('nombre','Nombre del tema prioritario');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_tema_prioritario','temas_prioritarios'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_tema_prioritario','clave','nombre','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','descripcion','fecha','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','descripcion','fecha','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre del tema prioritario','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tipo_curso()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado
            
            $crud = $this->new_crud();
            $crud->set_table('tipos_cursos');
            
            $crud->set_subject('Tipo de curso');
            $crud->display_as('id_tipo_curso','ID');
            $crud->display_as('nombre','Nombre del tipo de curso');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('activo','Activo');
            
            $crud->set_primary_key('id_tipo_curso','tipos_cursos'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_tipo_curso','nombre','fecha','activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('nombre','descripcion','fecha','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('nombre','descripcion','fecha','activo'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activo', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre del tipo de curso','trim|required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tipo_unidad()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado
            
            $crud = $this->new_crud();
            $crud->set_table('tipos_unidades');
            
            $crud->set_subject('Tipo de unidad');
            $crud->display_as('id_tipo_unidad','ID');
            $crud->display_as('nombre','Nombre del tipo de unidad');
            $crud->display_as('clave','Clave');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('activa','Activo');
            
            $crud->set_primary_key('id_tipo_unidad','tipos_unidades'); //Definir llaves primarias, asegurar correcta relación
            
            $crud->columns('id_tipo_unidad','nombre','clave','activa'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave','nombre','descripcion','activa'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave','nombre','descripcion','activa'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('activa', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('nombre','Nombre del tipo de unidad','trim|required');
            $crud->set_rules('activa','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function unidad_instituto()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado
            
            $crud = $this->new_crud();
            $crud->set_table('unidades_instituto');
            
            $crud->set_subject('Unidad');
            $crud->display_as('id_unidad_instituto','ID');
            $crud->display_as('clave_unidad','Clave');
            $crud->display_as('nombre','Nombre de la unidad');
            $crud->display_as('id_delegacion','Delegación');
            $crud->display_as('clave_presupuestal','Clave presupuestal');
            $crud->display_as('fecha','Fecha');
            $crud->display_as('nivel_atencion','Nivel de atención');
            $crud->display_as('id_tipo_unidad','Tipo de unidad');
            $crud->display_as('umae','Es UMAE');
            $crud->display_as('latitud','Latitud');
            $crud->display_as('longitud','Longitud');
            $crud->display_as('activa','Activo');
            
            $crud->set_primary_key('id_unidad_instituto','unidades_instituto'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_delegacion','delegaciones');
            
            $crud->set_relation('id_delegacion','delegaciones','{nombre} ({clave_delegacional})', null, 'clave_delegacional'); //Establecer relaciones
            $crud->set_relation('id_tipo_unidad','tipos_unidades','{nombre} ({clave})', null, 'clave');
            
            $crud->columns('id_unidad_instituto','clave_unidad','nombre','id_delegacion','clave_presupuestal','id_tipo_unidad','umae'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_unidad','nombre','id_delegacion','clave_presupuestal','fecha','nivel_atencion','id_tipo_unidad','umae','latitud','longitud','activa'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_unidad','nombre','id_delegacion','clave_presupuestal','fecha','nivel_atencion','id_tipo_unidad','umae','latitud','longitud','activa'); //Definir campos que se van a editar y su orden
            
            $crud->change_field_type('umae', 'true_false', array(0=>'No es UMAE', 1=>'Es UMAE'));
            $crud->change_field_type('activa', 'true_false', array(0=>'Inactivo', 1=>'Activo'));
            
            $crud->set_rules('clave_unidad','Clave','trim|required');
            $crud->set_rules('nombre','Nombre de la unidad','trim|required');
            $crud->set_rules('delegacion','Delegación','required');
            $crud->set_rules('fecha','Fecha','trim|required');
            $crud->set_rules('umae','Es UMAE','required');
            $crud->set_rules('activo','Activo','required');
            
            $crud->unset_delete();
            $output = $crud->render();
            
            $view['ficha_usuario'] = $this->ficha_usuario;
            $view['menu'] = $this->menu;
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            $main_content = $this->load->view('admin/admin', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
