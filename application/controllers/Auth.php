<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session']);
    }

    // Register User
    public function register() {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard'); // Prevent access if already logged in
            return;
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('register');
        } else {
            $username = $this->input->post('username', TRUE);
            $email = $this->input->post('email', TRUE);

            // Check if username or email already exists
            if ($this->User_model->check_exists('username', $username)) {
                $this->session->set_flashdata('error', 'Username is already taken.');
                redirect('auth/register');
                return;
            }

            if ($this->User_model->check_exists('email', $email)) {
                $this->session->set_flashdata('error', 'Email is already registered.');
                redirect('auth/register');
                return;
            }

            // Hash password and insert user
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
            ];

            if ($this->User_model->register($data)) {
                $this->session->set_flashdata('success', 'Registration successful! Please log in.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Try again.');
                $this->load->view('register');
            }
        }
    }

    // Login User
    public function login() {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard'); 
            return;
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password');

            $user = $this->User_model->login($email, $password);

            if ($user) {
                $this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username
                ]);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                redirect('auth/login');
            }
        }
    }

    // Logout User (Properly destroy session)
    public function logout() {
        $this->session->unset_userdata(['user_id', 'username']);
        $this->session->sess_destroy(); // Ensures session is fully cleared
        redirect('auth/login');
    }

    // Check if username exists (AJAX)
    public function check_username() {
        $username = $this->input->post('username');
        if (!$username) {
            echo "invalid";
            return;
        }
        $exists = $this->User_model->check_exists('username', $username);
        echo $exists ? "exists" : "available";
    }

    // Check if email exists (AJAX)
    public function check_email() {
        $email = $this->input->post('email');
        if (!$email) {
            echo "invalid";
            return;
        }
        $exists = $this->User_model->check_exists('email', $email);
        echo $exists ? "exists" : "available";
    }
}
