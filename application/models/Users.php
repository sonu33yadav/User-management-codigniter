<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Model
{

    public function insert_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function get_user_by_email($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }
    public function email_exists($email)
    {
        $query = $this->db->get_where('users', ['email' => $email]);
        return $query->num_rows() > 0;
    }
    public function getUsers()
    {
        return $this->db
            ->select('users.*, user_education.degree, user_education.institution, user_education.year_of_completion, user_employment.company, user_employment.designation, user_employment.start_date, user_employment.end_date')
            ->from('users')
            ->join('user_education', 'user_education.user_id = users.id', 'left')
            ->join('user_employment', 'user_employment.user_id = users.id', 'left')
            ->where('users.role_id', 2) // Filter for specific role
            ->order_by('users.id', 'DESC')
            ->limit(5)
            ->get()
            ->result_array();
    }
    public function getUserscount()
    {
        return $this->db->order_by('id', 'DESC')
            ->get_where('users', ['role_id' => 2])
            ->num_rows();
    }

    public function get_user_by_id($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }
    public function delete_user($id)
    {
        return $this->db->delete('users', ['id' => $id]);
    }

    public function update_user($user_id, $data)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }
    public function get_user_with_last_login($user_id)
    {
        return $this->db->select('id, first_name, last_name, last_login')
            ->where('id', $user_id)
            ->get('users')
            ->row_array();
    }

}