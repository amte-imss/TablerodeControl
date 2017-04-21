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
	'ANUAL' => array('id'=>1),
	'SEMESTRAL' => array('id'=>2),
	'TRIMESTRAL' => array('id'=>3),
	'BIMESTRAL' => array('id'=>4),
	'MENSUAL' => array('id'=>5),
);

$config['tipo_busqueda'] = array(
	'UMAE' => array('id'=>'umae'),
	'DELEGACION' => array('id'=>'delegacion')
);
