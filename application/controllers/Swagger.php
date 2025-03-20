<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Swagger extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('Swagger' . DIRECTORY_SEPARATOR . 'index.php');
	}

	public function generateJson()
	{
		$openapi = \OpenApi\Generator::scan([APPPATH . 'controllers\api\v1', APPPATH . 'datamodels']);
		
		header('Content-Type: application/json');
		
		echo $openapi->toJSON();
	}

	public function generateYaml()
	{
		$openapi = \OpenApi\Generator::scan([APPPATH . 'controllers\api\v1', APPPATH . 'datamodels']);
		
		header('Content-Type: application/x-yaml');

		echo $openapi->toYaml();
	}
}
