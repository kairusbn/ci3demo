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
        return $this->db->get('users')->result();
    }

    // Check if a username or email already exists
    public function check_exists($field, $value) {
        $this->db->where($field, $value);
        $query = $this->db->get('users');

        return $query->num_rows() > 0;
    }
}
