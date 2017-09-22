<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Assessment {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('table');
		$this->load->model('users_model');

	}

	public function index()
	{
		$this->load->view('includes/header');
		$this->load->view('welcome_message');
		$this->load->view('includes/footer');

	}

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
