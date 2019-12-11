<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');

		if (!$this->UserModel->isLogin()) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$tdata['title'] = 'Karyawan';
		$tdata['caption'] = 'Pengelolaan Data karyawan';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['scripts'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}
}
