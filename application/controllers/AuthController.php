<?php
defined('BASEPATH') or exit('No direct script access allowed');

class userController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('auth_library');
    }
    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            echo json_encode(response_format(false, 'Email and password are required'));
            return;
        }

        $user = $this->User_model->get_user_by_email($data['email']);

        if ($user && $this->auth_library->verify_password($data['password'], $user['password'])) {
            // Add JWT token generation logic here
            echo json_encode(response_format(true, 'Login successful', ['user' => $user]));
        } else {
            echo json_encode(response_format(false, 'Invalid email or password'));
        }
    }
    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            echo json_encode(response_format(false, 'Email and password are required'));
            return;
        }

        $password_hash = $this->auth_library->hash_password($data['password']);

        $user_id = $this->User_model->create_user([
            'email'    => $data['email'],
            'password' => $password_hash,
            'role_id'  => $data['role_id'], // Admin = 1, Customer = 2
        ]);

        echo json_encode(response_format(true, 'User registered successfully', ['id' => $user_id]));
    }
}