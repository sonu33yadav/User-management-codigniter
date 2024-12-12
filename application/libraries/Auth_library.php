<?php

class Auth_library
{

    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
        $this->ci->load->library('encryption');
    }

    public function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }
}