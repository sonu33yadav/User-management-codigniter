<?php
function getDashborddata($userId)
{
    $CI = &get_instance(); // Get the CI instance to access models
    $CI->load->model('Users');

    $logged_in_user  = $CI->Users->get_user_with_last_login($userId);
    $user_count      = $CI->Users->getUserscount();
    $last_five_users = $CI->Users->getUsers();

    return [
        'last_five_users' => $last_five_users,
        'user_count'      => $user_count,
        'last_login'      => $logged_in_user['last_login'],
    ];
}