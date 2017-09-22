<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 9/21/2017
 * Time: 6:01 PM
 */

class Installer extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
        $this->load->model('ion_auth_model');

        //setting header so client is aware
        header('Content-type: application/json');
    }

    /**
     * Creates inital tables needed to authenticate
     */
    public function install()
    {
//        if(! $this->input->is_ajax_request()){//we dont want this to be runnable via the url
//            die('please run this from the login page');
//        }
//         
        if($this->migration->version(1)){
            
            $this->create_users();
            
            print json_encode(
                array(
                    'status' => 'success'
                )
            );
        }else{
            header("HTTP/1.0 500  Internal Server Error");
           print  json_encode(
                array(
                    'status' => 'failed',
                    'message' => 'Please check config/database.php settings and try again'
                )
            );
        }

        exit();
    }

    /**
     * Creates entries to populate users table
     */
    protected function create_users()
    {
        $users = array(
            array(
                'username' => 'john',
                'email' => 'example1@domain.com',
                'password' => '12345678',
                'name' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                )
            ),
            array(
                'username' => 'sam',
                'email' => 'example2@domain.com',
                'password' => '12345678',
                'name' => array(
                    'first_name' => 'Sam',
                    'last_name' => 'Wallace'
                )
            ),
            array(
                'username' => 'mike',
                'email' => 'example3@domain.com',
                'password' => '12345678',
                'name' => array(
                    'first_name' => 'Mike',
                    'last_name' => 'Ike'
                )
            ),
            array(
                'username' => 'carla',
                'email' => 'example4@domain.com',
                'password' => '12345678',
                'name' => array(
                    'first_name' => 'Carla',
                    'last_name' => 'Danilina'
                )
            ),
        );

        foreach ($users as $user) {
            
            if (!$this->ion_auth_model->register($user['username'], $user['password'], $user['email'], $user['name'])) {
                
                header("HTTP/1.0 500  Internal Server Error");
                print  json_encode(
                    array(
                        'status' => 'failed',
                        'message' => 'Could not create users.'
                    )
                );
                exit();
            }
        }
    
        return true;
    }


    function goofed($data)
    {
        echo "<pre>";
        var_dump($data);
        echo"</pre>";

        die();
    }
}