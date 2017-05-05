<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['alert_msg'] = array(
    'SUCCESS' => array('id_msg' => 1, 'class' => 'success'),
    'DANGER' => array('id_msg' => 2, 'class' => 'danger'),
    'WARNING' => array('id_msg' => 3, 'class' => 'warning'),
    'INFO' => array('id_msg' => 4, 'class' => 'info')
);

$config['periodo'] = array(
	//'ANUAL' => array('id'=>1),
	'SEMESTRAL' => array('id'=>2),
	'TRIMESTRAL' => array('id'=>3),
	'BIMESTRAL' => array('id'=>4),
	'MENSUAL' => array('id'=>5),
);

$config['tipo_busqueda'] = array(
	'UMAE' => array('id'=>'umae', 'valor'=>'UMAE'),
	'DELEGACION' => array('id'=>'delegacion', 'valor'=>'Delegación')
);

$config['tipo_grafica'] = array(
	'PERFIL' => array('id'=>'perfil', 'valor'=>'Perfil'),
	'TIPO_CURSO' => array('id'=>'tipo_curso', 'valor'=>'Tipo de curso')
);

$config['tipos_busqueda'] = array(
	'DELEGACION' => array('id'=>'delegacion', 'valor'=>'Delegación'),
	'NIVEL_ATENCION' => array('id'=>'nivel_atencion', 'valor'=>'Nivel de atención'),
	'PERFIL' => array('id'=>'perfil', 'valor'=>'Perfil'),
	'PERIODO' => array('id'=>'periodo', 'valor'=>'Periodo'),
	'REGION' => array('id'=>'region', 'valor'=>'Región'),
	'TIPO_CURSO' => array('id'=>'tipo_curso', 'valor'=>'Tipo de curso'),
	'UMAE' => array('id'=>'umae', 'valor'=>'UMAE')
);