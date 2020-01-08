<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('UserModel');
		$this->load->model('KaryawanModel');

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
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}

	public function karyawan()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->KaryawanModel->count_all();
		$sql_data = $this->KaryawanModel->filter($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->KaryawanModel->count_filter($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	public function searchKaryawan()
	{
		$json = [];
		$this->db->like('nama_karyawan', $this->input->get("q"));
		$this->db->where('lower(bagian_karyawan)', 'teknisi');
		$query = $this->db->select('id_karyawan as id, nama_karyawan as text')->limit(5)->get("karyawan");
		$json = $query->result();
		echo json_encode($json);
	}

	private function _validationKaryawan($mode)
	{
		if ($mode == "save") {
			$this->form_validation->set_rules('nama_karyawan', 'Nama Karyawan', 'required');
			$this->form_validation->set_rules('bagian_karyawan', 'Bagian Karyawan', 'required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = array();
			$data['error_string'] = array();

			$fields = array('nama_karyawan', 'bagian_karyawan');
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


	public function addKaryawan()
	{
		$this->_validationKaryawan("save");
		$data = array(
			'nama_karyawan' => $this->input->post('nama_karyawan', TRUE),
			'bagian_karyawan' => $this->input->post('bagian_karyawan', TRUE)
		);

		$doInsert = $this->KaryawanModel->entriDataKaryawan($data);

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


	public function getKaryawan($id)
	{
		$data = $this->KaryawanModel->getKaryawan($id);
		echo json_encode($data);
	}


	public function updateKaryawan()
	{
		$this->_validationKaryawan("save");
		$data = array(
			'id_karyawan' => $this->input->post('id_karyawan', TRUE),
			'nama_karyawan' => $this->input->post('nama_karyawan', TRUE),
			'bagian_karyawan' => $this->input->post('bagian_karyawan', TRUE),
		);

		$doUpdate = $this->KaryawanModel->updateKaryawan($data);

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

	public function deleteKaryawan()
	{
		$id = $this->input->post('id_karyawan', TRUE);
		$doDelete = $this->KaryawanModel->deleteKaryawan($id);

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
