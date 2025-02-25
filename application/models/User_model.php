<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Register User
    public function register($data) {
        return $this->db->insert('users', $data);
    }

    // Login User
    public function login($email, $password) {
        $query = $this->db->get_where('users', array('email' => $email));

        if ($query->num_rows() > 0) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    // Fetch all users
    public function get_all_users() {
        return $this->db->get('users')->result();  // Fetch all users from the 'users' table
    }
}
