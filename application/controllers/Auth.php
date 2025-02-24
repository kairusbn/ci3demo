<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->database(); // Ensure the database is loaded here
    }

    // Register User
    public function register() {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('register');
        } else {
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
            );

            // Calling register function in User_model
            if ($this->User_model->register($data)) {
                $this->session->set_flashdata('success', 'Registration successful!');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Registration failed!');
                $this->load->view('register');
            }
        }
    }

    // Login User
    public function login() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Calling login function in User_model
            $user = $this->User_model->login($email, $password);

            if ($user) {
                $this->session->set_userdata('user_id', $user->id);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid login credentials');
                $this->load->view('login');
            }
        }
    }

    // Logout User
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
