<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Assessment {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('table');
		$this->load->model('users_model');

	}

	/**
	 * Default method routed to if no other url params exist
	 */
	public function index()
	{
		$this->load->view('includes/header');
		$this->load->view('welcome_message');
		$this->load->view('includes/footer');
	}

	/**
	 * @return JSON OBJECT
	 * 
	 * Returns a json encoded string of all users
	 */
	public function ajax_get_all_users()
	{
		if ( ! $this->input->is_ajax_request()) {
			return FALSE;
		}

		echo $this->users_model->ajax_get_all_users();

		flush();
		exit();
	}
	

}
