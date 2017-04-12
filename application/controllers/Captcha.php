<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las pruebas del captcha
 * @version 	: 1.0.0
 * @autor 		: Pablo José J.
 */
class Captcha extends CI_Controller {

    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper(array('secureimage'));
    }

    public function index() {
        new_captcha();
    }

}
