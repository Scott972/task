<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 9/22/2017
 * Time: 11:40 AM
 */

class Users_model extends CI_Model{
    
    private $users; 
    
    public function __construct()
    {
        parent::__construct();
        $this->users = 'users';
    }
    
    
    public function ajax_get_all_users()
    {
        $this->load->library('datatables');

        $this->datatables->select('id, username, first_name, last_name, email');
        $this->datatables->from($this->users);
        
        return $this->datatables->generate();

    }
}