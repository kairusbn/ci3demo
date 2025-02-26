<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('url');
        $this->load->library('session');

        // Redirect to login if user is not authenticated
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['users'] = $this->User_model->get_all_users(); // Fetch user list
        $this->load->view('dashboard', $data);
    }

    // Update User Username
    public function edit_user() {
        $user_id = $this->input->post('user_id');
        $new_username = $this->input->post('username');

        if (!$user_id || empty($new_username)) {
            echo json_encode(["status" => "error", "message" => "Invalid input"]);
            return;
        }

        $updated = $this->User_model->update_user($user_id, ['username' => $new_username]);

        if ($updated) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update username"]);
        }
    }

    // Delete User
    public function delete_user() {
        $user_id = $this->input->post('user_id');

        if (!$user_id) {
            echo json_encode(["status" => "error", "message" => "Invalid user ID"]);
            return;
        }

        $deleted = $this->User_model->delete_user($user_id);

        if ($deleted) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete user"]);
        }
    }
}
