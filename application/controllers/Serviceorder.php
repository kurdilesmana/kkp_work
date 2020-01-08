<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Serviceorder extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('UserModel');
		$this->load->model('ServiceorderModel');

		if (!$this->UserModel->isLogin()) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$tdata['title'] = 'Service Order';
		$tdata['caption'] = 'Pengelolaan Data Barang Masuk';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}

	public function Serviceorder()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->ServiceorderModel->count_all();
		$sql_data = $this->ServiceorderModel->filter($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->ServiceorderModel->count_filter($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	private function _validationServiceorder($mode)
	{
		if ($mode == "save") {
			$this->form_validation->set_rules('customer_id', 'Customer', 'required');
			$this->form_validation->set_rules('jenis', 'Jenis', 'required');
			$this->form_validation->set_rules('spesifikasi', 'Spesifikasi', 'required');
			$this->form_validation->set_rules('serial_number', 'Serial Number', 'required');
			$this->form_validation->set_rules('kelengkapan_barang', 'Kelengkapan Barang', 'required');
			$this->form_validation->set_rules('keluhan', 'Keluhan', 'required');
			$this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required');
			$this->form_validation->set_rules('karyawan_id', 'Karyawan', 'required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = array();
			$data['error_string'] = array();

			$fields = array('customer_id', 'jenis', 'spesifikasi', 'serial_number', 'kelengkapan_barang', 'keluhan', 'tgl_masuk', 'karyawan_id');
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


	public function addServiceorder()
	{
		$this->_validationServiceorder("save");

		$tgl_masuk = $this->input->post('tgl_masuk', TRUE);
		$date = str_replace('/', '-', $tgl_masuk);
		$newDate = date("Y-m-d", strtotime($date));

		$data = array(
			'customer_id' => $this->input->post('customer_id', TRUE),
			'jenis' => $this->input->post('jenis', TRUE),
			'spesifikasi' => $this->input->post('spesifikasi', TRUE),
			'serial_number' => $this->input->post('serial_number', TRUE),
			'kelengkapan_barang' => $this->input->post('kelengkapan_barang', TRUE),
			'keluhan' => $this->input->post('keluhan', TRUE),
			'tgl_masuk' => $newDate,
			'karyawan_id' => $this->input->post('karyawan_id', TRUE)
		);

		$doInsert = $this->ServiceorderModel->entriDataServiceorder($data);

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


	public function getServiceorder($id)
	{
		$data = $this->ServiceorderModel->getServiceorder($id);
		$newDate = date('d/m/Y', strtotime($data->tgl_masuk));
		$data->tgl_masuk = $newDate;
		echo json_encode($data);
	}

	public function updateServiceorder()
	{
		$this->_validationServiceorder("save");

		$tgl_masuk = $this->input->post('tgl_masuk', TRUE);
		$date = str_replace('/', '-', $tgl_masuk);
		$newDate = date("Y-m-d", strtotime($date));
		
		$data = array(
			'id_barang' => $this->input->post('id_barang', TRUE),
			'customer_id' => $this->input->post('customer_id', TRUE),
			'jenis' => $this->input->post('jenis', TRUE),
			'spesifikasi' => $this->input->post('spesifikasi', TRUE),
			'serial_number' => $this->input->post('serial_number', TRUE),
			'kelengkapan_barang' => $this->input->post('kelengkapan_barang', TRUE),
			'keluhan' => $this->input->post('keluhan', TRUE),
			'tgl_masuk' => $newDate,
			'karyawan_id' => $this->input->post('karyawan_id', TRUE)
			
		);

		$doUpdate = $this->ServiceorderModel->updateServiceorder($data);

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

	public function deleteServiceorder()
	{
		$id = $this->input->post('id_barang', TRUE);
		$doDelete = $this->ServiceorderModel->deleteServiceorder($id);

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
