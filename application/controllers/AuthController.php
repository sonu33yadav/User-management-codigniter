<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Users');

    }
    public function index()
    {
        $this->load->view('login');
    }
    public function register()
    {
        $this->load->view('register');

    }
    public function create_account()
    {

        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'email'      => $this->input->post('email'),
            'password'   => $this->auth_library->hash_password($this->input->post('password'), PASSWORD_BCRYPT),
            'role_id'    => $this->input->post('role'),
        ];
        if ($this->Users->email_exists($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'This email address is already registered. Please use a different one or login if you already have an account.');
            redirect('');
        }
        $this->Users->insert_user($data);
        $this->session->set_flashdata('success', 'Account created successfully! Please log in.');
        redirect('AuthController/index');
    }

    public function validate_login()
    {
        $email    = $this->input->post('email');
        $password = $this->input->post('password');
        // Fetch user details by email
        $user = $this->Users->get_user_by_email($email);
        if ($user && $this->auth_library->verify_password($password, $user['password'])) {
            // Set session data for user
            $this->session->set_userdata('user_id', $user['id']);
            $this->session->set_userdata('role', $user['role_id']);
            // Check user role
            if ($user['role_id'] == '1') {
                $this->db->where('id', $user['id'])
                    ->update('users', ['last_login' => date('Y-m-d H:i:s')]);
                redirect('AuthController/adminDashbord');
            } else if ($user['role_id'] == '2') {
                $this->db->where('id', $user['id'])
                    ->update('users', ['last_login' => date('Y-m-d H:i:s')]);
                $this->session->set_flashdata('message', 'Welcome to your account, ' . $user['first_name']);
                echo "Hi Customer";
            }

        } else {
            // Invalid login credentials
            $this->session->set_flashdata('error', 'Invalid email or password.');
            redirect('login');
        }
    }
    public function adminDashbord()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // If not logged in, redirect to the login page or show an error
            $this->session->set_flashdata('error', 'You need to log in to access the dashboard.');
            redirect('login');
        }
        $logged_in_user          = $this->Users->get_user_with_last_login($user_id);
        $user_count              = $this->Users->getUserscount();
        $last_five_users         = $this->Users->getUsers();
        $data['last_five_users'] = $last_five_users;
        $data['user_count']      = $user_count;
        $data['last_login']      = $logged_in_user['last_login'];

        $this->load->view('adminDashbord', $data);
    }
}