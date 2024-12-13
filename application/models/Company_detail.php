<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company_detail extends CI_Model
{

    public function insert_Companydetails($company_Details)
    {
        return $this->db->insert('user_employment', $company_Details);
    }
    public function get_company_detail_by_id($id)
    {
        return $this->db->get_where('user_employment', ['user_id' => $id])->row_array();
    }
    public function updateCompanyDetails($user_id, $company_details)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_employment', $company_details);
    }

    public function getCompanyDetails($user_id)
    {
        if (empty($user_id) || !is_numeric($user_id)) {
            return [];
        }

        $this->db->select('company, designation, start_date, end_date');
        $this->db->from('user_employment');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        return $query->result_array();
    }

}