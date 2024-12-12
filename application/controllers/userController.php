<?php
defined('BASEPATH') or exit('No direct script access allowed');

class userController extends CI_Controller
{
    public function index()
    {
        $response = response_format(true, 'Helper loaded successfully', ['id' => 1]);
        echo json_encode($response);
    }
}