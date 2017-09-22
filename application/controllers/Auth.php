<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 9/21/2017
 * Time: 5:26 PM
 */

class Auth extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->load->library(
            array('ion_auth','form_validation')
        );
        $this->load->helper(
            array('url','language')
        );

        $this->form_validation->set_error_delimiters(
            $this->config->item('error_start_delimiter', 'ion_auth'),
            $this->config->item('error_end_delimiter', 'ion_auth')
        );

        $this->lang->load('auth');
    }


    public function index()
    {
        if ( ! $this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('/auth/login', 'refresh');
        }
    }

    // log the user in
    public function login()
    {
        //validate form input
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {
           /**third parameter set to false, not implementing remember me**/
            if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), false))
            {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('/', 'refresh');
            }
            else
            {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['username'] = array('name' => 'username',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id'   => 'password',
                'type' => 'password',
            );
            
            $this->load->view('includes/header');
            $this->load->view('auth/login', $this->data);
            $this->load->view('includes/footer');

        }
    }

    // log the user out
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('auth/login', 'refresh');
    }

    
}
