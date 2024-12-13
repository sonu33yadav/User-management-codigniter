<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Education_detail extends CI_Model
{

    public function insert_EducationDetails($education_details)
    {
        return $this->db->insert('user_education', $education_details);
    }
    public function get_education_detail_by_id($id)
    {
        return $this->db->get_where('user_education', ['user_id' => $id])->row_array();
    }
    public function updateEducationDetails($user_id, $education_details)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_education', $education_details);
    }

}