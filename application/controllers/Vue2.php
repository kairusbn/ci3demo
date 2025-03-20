<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Vue2 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect(site_url('auth/login'));
        }

        $this->load->helper('vite2');
    }

    public function index()
    {
        $this->load->view('vue2/index.vue.php');
    }
}
