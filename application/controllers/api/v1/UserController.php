<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session']);
    }

    /**
     * @OA\Get(
     * path="/auth/register",
     * summary="Access the registration form",
     * tags={"Registration"},
     * @OA\Response(
     * response="200",
     * description="Registration form HTML"
     * )
     * )
     */
    public function registerForm() {
        $this->load->view('register');
    }

    /**
     * @OA\Post(
     * path="/auth/register",
     * summary="Register a new user",
     * tags={"Registration"},
     * @OA\RequestBody(
     * required=true,
     * description="Registration form data (username, email, password, confirm_password)"
     * ),
     * @OA\Response(
     * response="200",
     * description="Registration successful. Redirects to login."
     * ),
     * @OA\Response(
     * response="400",
     * description="Validation errors or registration failed."
     * ),
     * @OA\Response(
     * response="409",
     * description="Username or email already exists."
     * ),
     * @OA\Response(
     * response="302",
     * description="Redirect to dashboard if user is already logged in."
     * )
     * )
     */
    public function register() {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
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

    /**
     * @OA\Get(
     * path="/auth/login",
     * summary="Access the login form",
     * tags={"Authentication"},
     * @OA\Response(
     * response="200",
     * description="Login form HTML"
     * )
     * )
     */
    public function loginForm() {
        $this->load->view('login');
    }

    /**
     * @OA\Post(
     * path="/auth/login",
     * summary="Authenticate user and log in",
     * tags={"Authentication"},
     * @OA\RequestBody(
     * required=true,
     * description="Login form data (email, password)"
     * ),
     * @OA\Response(
     * response="200",
     * description="Login successful. Redirects to dashboard."
     * ),
     * @OA\Response(
     * response="401",
     * description="Invalid credentials."
     * ),
     * @OA\Response(
     * response="400",
     * description="Validation errors or login failed."
     * )
     * )
     */
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

    /**
     * @OA\Get(
     * path="/auth/logout",
     * summary="Logout user",
     * tags={"Authentication"},
     * @OA\Response(
     * response="302",
     * description="Redirects to login page after logout."
     * )
     * )
     */
    public function logout() {
        $this->session->unset_userdata(['user_id', 'username']);
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    /**
     * @OA\Post(
     * path="/auth/check_username",
     * summary="Check if username exists",
     * tags={"Registration"},
     * @OA\RequestBody(
     * required=true,
     * description="Username to check"
     * ),
     * @OA\Response(
     * response="200",
     * description="Username status: 'exists', 'available', or 'invalid'."
     * )
     * )
     */
    public function check_username() {
        $username = $this->input->post('username');
        if (!$username) {
            echo "invalid";
            return;
        }
        $exists = $this->User_model->check_exists('username', $username);
        echo $exists ? "exists" : "available";
    }

    /**
     * @OA\Post(
     * path="/auth/check_email",
     * summary="Check if email exists",
     * tags={"Registration"},
     * @OA\RequestBody(
     * required=true,
     * description="Email to check"
     * ),
     * @OA\Response(
     * response="200",
     * description="Email status: 'exists', 'available', or 'invalid'."
     * )
     * )
     */
    public function check_email() {
        $email = $this->input->post('email');
        if (!$email) {
            echo "invalid";
            return;
        }
        $exists = $this->User_model->check_exists('email', $email);
        echo $exists ? "exists" : "available";

    /**
     * @OA\Post(
     * path="/auth/edit_user",
     * summary="Update user username and email",
     * tags={"Update"},
     * @OA\RequestBody(
     * required=true,
     * description="User ID, new username, and new email",
     * ),
     * @OA\Response(
     * response="200",
     * description="User update status (success or error) in JSON format."
     * )
     * )
     */
    public function edit_user() {
        $user_id = $this->input->post('user_id');
        $new_username = $this->input->post('username');
        $new_email = $this->input->post('email');

        if (!$user_id || empty($new_username) || empty($new_email)) {
            echo json_encode(["status" => "error", "message" => "All fields are required"]);
            return;
        }

        $update_data = [
            'username' => $new_username,
            'email' => $new_email
        ];

        $updated = $this->User_model->update_user($user_id, $update_data);

        if ($updated) {
            echo json_encode(["status" => "success", "message" => "User updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update user"]);
        }
    }

    /**
     * @OA\Post(
     * path="/auth/delete_user",
     * summary="Delete user",
     * tags={"Delete"},
     * @OA\RequestBody(
     * required=true,
     * description="User ID to delete",
     * ),
     * @OA\Response(
     * response="200",
     * description="User deletion status (success or error) in JSON format."
     * )
     * )
     */
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

    /**
     * @OA\Post(
     * path="/auth/change_password",
     * summary="Change user password",
     * tags={"Update"},
     * @OA\RequestBody(
     * required=true,
     * description="User ID, current password, new password, and confirm password",
     * ),
     * @OA\Response(
     * response="200",
     * description="Password change status (success or error) in JSON format."
     * )
     * )
     */
    public function change_password() {
        $user_id = $this->input->post('user_id');
        $current_password = trim($this->input->post('current_password'));
        $new_password = trim($this->input->post('new_password'));
        $confirm_password = trim($this->input->post('confirm_password'));

        // Ensure user is only changing their own password
        if ($user_id != $this->session->userdata('user_id')) {
            echo json_encode(["status" => "error", "message" => "Unauthorized action!"]);
            return;
        }
    }
}