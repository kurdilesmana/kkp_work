<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('UserModel');
		$this->load->model('CustomersModel');

		if (!$this->UserModel->isLogin()) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$tdata['title'] = 'Customers';
		$tdata['caption'] = 'Pengelolaan Data Customers';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}

	public function customers()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->CustomersModel->count_all();
		$sql_data = $this->CustomersModel->filter($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->CustomersModel->count_filter($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	private function _validationCustomers($mode)
	{
		if ($mode == "save") {
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'required');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = array();
			$data['error_string'] = array();

			$fields = array('nama', 'alamat', 'phone');
			for ($i = 0; $i < count($fields); $i++) {
				if (form_error($fields[$i])) {
					$data['inputerror'][$i] = $fields[$i];
					$data['error_string'][$i] = form_error($fields[$i]);
				} else {
					$data['inputerror'][$i] = "";
					$data['error_string'][$i] = "";
				}
			}
			
			echo json_encode($data);
			exit();
		}
	}


	public function addCustomers()
	{
		$this->_validationCustomers("save");
		$data = array(
			'nama' => $this->input->post('nama', TRUE),
			'alamat' => $this->input->post('alamat', TRUE),
			'phone' => $this->input->post('phone', TRUE)
		);

		$doInsert = $this->CustomersModel->entriDataCustomers($data);

		//Pengecekan input data
		if ($doInsert == 'failed') {
			$isErr = 1;
			$Msg = 'Data gagal ditambahkan!';
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

	
	public function getCustomers($id)
	{
		$data = $this->CustomersModel->getCustomers($id);
		echo json_encode($data);
	}


	public function updateCustomers()
	{
		$this->_validationCustomers("save");
		$data = array(
			'id_customers' => $this->input->post('id_customers', TRUE),
			'nama' => $this->input->post('nama', TRUE),
			'alamat' => $this->input->post('alamat', TRUE),
			'phone' => $this->input->post('phone', TRUE),
		);

		$doUpdate = $this->CustomersModel->updateCustomers($data);

		//Pengecekan edit data
		if ($doUpdate == 'failed') {
			$isErr = 1;
			$Msg = 'Data gagal ditambahkan!';
		} else {
			$isErr = 0;
			$Msg = 'Data Berhasil Diubah.';
		}

		$callback = array(
			'status' => $isErr,
			'pesan' => $Msg
		);
		echo json_encode($callback);
	}

	public function deleteCustomers()
	{
		$id = $this->input->post('id_customers', TRUE);
		$doDelete = $this->CustomersModel->deleteCustomers($id);

		//Pengecekan edit data
		if ($doDelete == 'failed') {
			$isErr = 1;
			$Msg = 'Data gagal ditambahkan!';
		} else {
			$isErr = 0;
			$Msg = 'Data Berhasil Dihapus.';
		}

		$callback = array(
			'status' => $isErr,
			'pesan' => $Msg
		);
		echo json_encode($callback);
	}
}



