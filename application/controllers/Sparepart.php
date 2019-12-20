<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sparepart extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('UserModel');
		$this->load->model('SparepartModel');

		if (!$this->UserModel->isLogin()) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$tdata['title'] = 'Sparepart';
		$tdata['caption'] = 'Pengelolaan Data Sparepart';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}

	public function sparepart()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->SparepartModel->count_all();
		$sql_data = $this->SparepartModel->filter($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->SparepartModel->count_filter($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	private function _validationSparepart($mode)
	{
		if ($mode == "save") {
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('jenis', 'Jenis', 'required');
            $this->form_validation->set_rules('stock', 'Stock', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = array();
			$data['error_string'] = array();

			$fields = array('nama', 'jenis', 'stock', 'harga');
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


	public function addSparepart()
	{
		$this->_validationSparepart("save");
		$data = array(
			'nama' => $this->input->post('nama', TRUE),
			'jenis' => $this->input->post('jenis', TRUE),
            'stock' => $this->input->post('stock', TRUE),
            'harga' => $this->input->post('harga', TRUE)
		);

		$doInsert = $this->SparepartModel->entriDataSparepart($data);

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

	
	public function getSparepart($id)
	{
		$data = $this->SparepartModel->getSparepart($id);
		echo json_encode($data);
	}


	public function updateSparepart()
	{
		$this->_validationSparepart("save");
		$data = array(
			'id_sparepart' => $this->input->post('id_sparepart', TRUE),
			'nama' => $this->input->post('nama', TRUE),
			'jenis' => $this->input->post('jenis', TRUE),
            'stock' => $this->input->post('stock', TRUE),
            'harga' => $this->input->post('harga', TRUE)
		);

		$doUpdate = $this->SparepartModel->updateSparepart($data);

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

	public function deleteSparepart()
	{
		$id = $this->input->post('id_sparepart', TRUE);
		$doDelete = $this->SparepartModel->deleteSparepart($id);

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



