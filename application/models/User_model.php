<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Register User
    public function register($data) {
        // Make sure the 'users' table exists and has columns like 'username', 'email', 'password'
        return $this->db->insert('users', $data);  // Insert data into 'users' table
    }

    // Login User
    public function login($email, $password) {
        // Get user by email
        $query = $this->db->get_where('users', array('email' => $email));

        if ($query->num_rows() > 0) {
            $user = $query->row();

            // Verify password
            if (password_verify($password, $user->password)) {
                return $user; // Return user object if login is successful
            }
        }

        return false; // Return false if no user found or password mismatch
    }
}
