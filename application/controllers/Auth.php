<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session']);
<<<<<<< HEAD
=======
        $this->load->database(); // Ensure the database is loaded
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
    }

    // Register User
    public function register() {
<<<<<<< HEAD
        if ($this->session->userdata('user_id')) {
            redirect('dashboard'); // Prevent access if already logged in
            return;
        }

=======
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('register');
        } else {
<<<<<<< HEAD
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
=======
            $data = [
                'username' => $this->input->post('username', TRUE),
                'email' => $this->input->post('email', TRUE),
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
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
<<<<<<< HEAD
        if ($this->session->userdata('user_id')) {
            redirect('dashboard'); 
            return;
        }

=======
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password');

            $user = $this->User_model->login($email, $password);

            if ($user) {
                $this->session->set_userdata(['user_id' => $user->id, 'username' => $user->username]);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                $this->load->view('login');
            }
        }
    }

    // Logout User
    public function logout() {
        $this->session->unset_userdata(['user_id', 'username']);
        $this->session->sess_destroy();
        redirect('auth/login');
    }
<<<<<<< HEAD

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
=======
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
}
