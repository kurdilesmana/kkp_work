<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('UserModel');
	}

	public function index()
	{
		if ($this->UserModel->isLogin()) {
			redirect(base_url('dashboard'));
		} else {
			// validation
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if ($this->form_validation->run()) {
				$this->_login();
			} else {
				$this->load->view('auth/login');
			}
		}
	}

	private function _login()
	{
		// get data
		$email = $this->input->post('email', TRUE);
		$password = $this->input->post('password', TRUE);

		$data = [
			'email' => $email,
			'password' => $password
		];

		// check email
		$checking = $this->UserModel->checkLogin($data);

		if ($checking == 'wrong_password') {
			$this->session->set_flashdata(
				'message',
				'<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i> Wrong password!</div>'
			);
			redirect('auth');
		} else if ($checking == 'email_not_actived') {
			$this->session->set_flashdata(
				'message',
				'<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i> This email has not activated!</div>'
			);
			redirect('auth');
		} else if ($checking == 'email_not_registered') {
			$this->session->set_flashdata(
				'message',
				'<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i> Email has not been registered!</div>'
			);
			redirect('auth');
		} else {
			$data = [
				'email' => $checking['email'],
				'role_id' => $checking['role_id']
			];
			$this->session->set_userdata($data);
			redirect('dashboard');
		}
	}

	public function logout()
	{
		$this->load->view('base');
	}
}
