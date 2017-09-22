<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 9/21/2017
 * Time: 5:43 PM
 */

class Assessment extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        if ($this->ion_auth->logged_in()) {
        
        }else{
            redirect('auth/login');
        }
    }
}