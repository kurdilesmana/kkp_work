<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function login()
	{
		$this->load->view('base');
	}
}