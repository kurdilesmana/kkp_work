<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('MenuModel');

		if (!$this->UserModel->isLogin()) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$tdata['title'] = 'Header Menu';
		$tdata['caption'] = 'Pengelolaan Header Menu!';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);
		$this->load->view('base', $ldata);
	}

	public function headerMenu()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->MenuModel->count_all();
		$sql_data = $this->MenuModel->filter($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->MenuModel->count_filter($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	private function _validation($mode)
	{
		$this->load->library('form_validation');
		if ($mode == "save") {
			$this->form_validation->set_rules('header_menu', 'Header Menu', 'trim|required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = 'header_menu';
			$data['error_string'] = form_error('header_menu');

			echo json_encode($data);
			exit();
		}
	}

	public function addHeaderMenu()
	{
		$this->_validation("save");
		$data = array(
			'header_menu' => $this->input->post('header_menu', TRUE)
		);

		$doInsert = $this->MenuModel->entriDataHeader($data);

		//Pengecekan input data
		if ($doInsert == 'failed') {
			$isErr = 1;
			$Msg = 'Data tidak bisa ditambahkan!';
		} else {
			$isErr = 0;
			$Msg = 'Data Berhasil Disimpan.';
		}

		$callback = array(
			'status' => $isErr,
			'pesan' => $Msg
		);
		echo json_encode($callback);
	}
}
