<?php
defined('BASEPATH') or exit('No direct script access allowed');

class userController extends CI_Controller
{
    // public function index()
    // {
    //     $response = response_format(true, 'Helper loaded successfully', ['id' => 1]);
    //     echo json_encode($response);
    // }
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Education_detail');
        $this->load->model('Company_detail');
        $this->load->model('Users');
        $this->load->library('form_validation');

    }
    public function addUser()
    {
        try {
            // Load form helper and validation library
            $this->load->helper(['form', 'url']);
            $this->load->library('form_validation');

            // Define validation rules
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('role', 'Role', 'required');
            $this->form_validation->set_rules('degree', 'Degree', 'required');
            $this->form_validation->set_rules('institution', 'Institution', 'required');
            $this->form_validation->set_rules('year_of_completion', 'Year of Completion', 'required');

            //companyDetails
            $this->form_validation->set_rules('company', 'Company', 'required|max_length[100]');
            $this->form_validation->set_rules('designation', 'Designation', 'required|max_length[100]');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');
            $this->form_validation->set_rules('end_date', 'End Date', 'required');

            // Check if the form validation passed
            if ($this->form_validation->run() === false) {

                throw new Exception(validation_errors('<div>', '</div>'));
            }
            $this->db->trans_begin();
            // Gather form data
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'email'      => $this->input->post('email'),
                'password'   => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role_id'    => $this->input->post('role'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Handle profile image upload
            if (!empty($_FILES['profile_image']['name'])) {
                $config['upload_path']   = './uploads/profile_images/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = time() . '_' . $_FILES['profile_image']['name'];

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('profile_image')) {
                    throw new Exception($this->upload->display_errors());
                }

                $data['profile_image'] = $this->upload->data('file_name');
            }

            // Insert user into the database
            if (!$this->Users->insert_user($data)) {
                throw new Exception('Failed to save user to the database.');
            }
            $user_id = $this->db->insert_id();

            $education_details = [
                'user_id'            => $user_id,
                'degree'             => $this->input->post('degree'),
                'institution'        => $this->input->post('institution'),
                'year_of_completion' => $this->input->post('year_of_completion'),
            ];
            $company_Details = [
                'user_id'     => $user_id,
                'company'     => $this->input->post('company'),
                'designation' => $this->input->post('designation'),
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
            ];
            $this->Education_detail->insert_EducationDetails($education_details);
            $this->Company_detail->insert_Companydetails($company_Details);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Transaction failed. All operations have been rolled back.');
            }

            $this->db->trans_commit();
            // Set success message
            $this->session->set_flashdata('success', 'User added successfully!');
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', $e->getMessage());
            // Handle exceptions and set error messages
            $this->session->set_flashdata('error', $e->getMessage());
        }

        // Redirect back to the admin dashboard
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

    public function deleteUser($id)
    {
        try {
            // Validate the ID
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('Invalid user ID provided.');
            }

            // Check if the user exists
            $user = $this->Users->get_user_by_id($id);
            if (!$user) {
                throw new Exception('User not found.');
            }

            // Start a transaction
            $this->db->trans_start();

            // Delete dependent records first
            $this->db->delete('user_education', ['user_id' => $id]);
            $this->db->delete('user_employment', ['user_id' => $id]);

            // Delete the user from the database
            if (!$this->Users->delete_user($id)) {
                throw new Exception('Failed to delete the user. Please try again.');
            }

            // Commit the transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception('An error occurred while deleting the user.');
            }

            // Set a success message
            $this->session->set_flashdata('success', 'User deleted successfully!');
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $this->db->trans_rollback();

            // Handle exceptions and set error messages
            $this->session->set_flashdata('error', $e->getMessage());
        }

        // Get the logged-in user's ID from the session
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // If not logged in, redirect to the login page or show an error
            $this->session->set_flashdata('error', 'You need to log in to access the dashboard.');
            redirect('login');
        }

        // Load data for the admin dashboard
        $logged_in_user          = $this->Users->get_user_with_last_login($user_id);
        $user_count              = $this->Users->getUserscount();
        $last_five_users         = $this->Users->getUsers();
        $data['last_five_users'] = $last_five_users;
        $data['user_count']      = $user_count;
        $data['last_login']      = isset($logged_in_user['last_login']) ? $logged_in_user['last_login'] : 'No login details available.';

        // Load the admin dashboard view
        $this->load->view('adminDashbord', $data);
    }

    public function editUser($id)
    {
        try {
            // Validate the ID
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('Invalid user ID provided.');
            }

            // Fetch user data
            $user = $this->Users->get_user_by_id($id);
            if (!$user) {
                throw new Exception('User not found.');
            }
            $education_detail = $this->Education_detail->get_education_detail_by_id($id);
            if (!$education_detail) {
                throw new Exception('Education not found.');
            }
            $company_detail = $this->Company_detail->get_company_detail_by_id($id);
            if (!$company_detail) {
                throw new Exception('Company not found.');
            }

            // If the form is submitted, process the update
            if ($this->input->post()) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

                if ($this->form_validation->run() == false) {
                    throw new Exception(validation_errors());
                }

                // Prepare data for update
                $data = [
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'email'      => $this->input->post('email'),
                ];

                // Update user in the database
                if (!$this->Users->update_user($id, $data)) {
                    throw new Exception('Failed to update the user. Please try again.');
                }

                // Set success message and redirect
                $this->session->set_flashdata('success', 'User updated successfully!');

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

            // Load the edit view with user data

            $this->load->view('editUser', ['user' => $user, 'education' => $education_detail, 'company' => $company_detail]);

        } catch (Exception $e) {
            // Handle exceptions and set error messages
            $this->session->set_flashdata('error', $e->getMessage());

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

    public function updateUser()
    {
        try {
            // Load required libraries and models
            $this->load->library('form_validation');
            $this->load->model('Users');
            $this->load->model('Education_detail');
            $this->load->model('Company_detail');

            // Get user data for the current user ID
            $user = $this->Users->get_user_by_id($this->input->post('user_id'));

            $education_detail = $this->Education_detail->get_education_detail_by_id($this->input->post('user_id'));
            if (!$education_detail) {
                throw new Exception('Education not found.');
            }
            $company_detail = $this->Company_detail->get_company_detail_by_id($this->input->post('user_id'));
            if (!$company_detail) {
                throw new Exception('Company not found.');
            }

            // Set validation rules
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'Role', 'required');
            $this->form_validation->set_rules('degree', 'Degree', 'required');
            $this->form_validation->set_rules('institution', 'Institution', 'required');
            $this->form_validation->set_rules('year_of_completion', 'Year of Completion', 'required');
            $this->form_validation->set_rules('company', 'Company', 'required|max_length[100]');
            $this->form_validation->set_rules('designation', 'Designation', 'required|max_length[100]');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');

            // Validate form input
            if ($this->form_validation->run() == false) {
                throw new Exception(validation_errors('<div>', '</div>'));
            }

            // Retrieve form data
            $user_id    = $this->input->post('user_id');
            $first_name = $this->input->post('first_name');
            $last_name  = $this->input->post('last_name');
            $email      = $this->input->post('email');
            $password   = $this->input->post('password');
            $role       = $this->input->post('role');

            // Handle file upload (Profile Image)
            $profile_image = '';
            if ($_FILES['profile_image']['name'] != '') {
                $config['upload_path']   = './uploads/profile_images/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 1024 * 2; // 2MB max file size
                $config['file_name']     = uniqid('profile_', true);

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('profile_image')) {
                    throw new Exception($this->upload->display_errors());
                } else {
                    $file_data     = $this->upload->data();
                    $profile_image = $file_data['file_name'];
                }
            }

            // Prepare user update data
            $update_data = [
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'email'      => $email,
                'role_id'    => $role,
            ];

            if (!empty($password)) {
                $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($profile_image != '') {
                $update_data['profile_image'] = $profile_image;
            }

            // Begin database updates
            if (!$this->Users->update_user($user_id, $update_data)) {
                throw new Exception('An error occurred while updating the user.');
            }

            // Update education details
            $education_details = [
                'degree'             => $this->input->post('degree'),
                'institution'        => $this->input->post('institution'),
                'year_of_completion' => $this->input->post('year_of_completion'),
            ];

            if (!$this->Education_detail->updateEducationDetails($user_id, $education_details)) {
                throw new Exception('Failed to update education details.');
            }

            // Update company details
            $company_details = [
                'company'     => $this->input->post('company'),
                'designation' => $this->input->post('designation'),
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
            ];

            if (!$this->Company_detail->updateCompanyDetails($user_id, $company_details)) {
                throw new Exception('Failed to update company details.');
            }

            // Success
            $this->session->set_flashdata('success', 'User updated successfully.');
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
        } catch (Exception $e) {
            // Handle exceptions
            $this->session->set_flashdata('error', $e->getMessage());
            $this->load->view('editUser', ['user' => $user, 'education' => $education_detail, 'company' => $company_detail]);
        }
    }

}