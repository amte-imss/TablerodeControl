<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config = array(
    'formulario_nombre' => array(
       array(
            'field' => 'campo',
            'label' => 'Etiqueta visible',
            'rules' => 'required|regla2'
        ),
    ),
);

$config["login"]= array(
	array(
        'field' => 'usuario',
        'label' => 'Usuario',
        'rules' => 'required',
        'errors' => array(
                'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
	),
	array(
        'field' => 'password',
        'label' => 'Contraseña',
        'rules' => 'required',
        'errors' => array(
                'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
	),
	array(
        'field' => 'captcha',
        'label' => 'Imagen de seguridad',
        'rules' => 'required|check_captcha',
        'errors' => array(
                'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
                'check_captcha'=>"El texto no coincide con la imagen, favor de verificarlo."
        ),
	),
);