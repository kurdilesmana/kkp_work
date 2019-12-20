<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
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
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}

	public function user()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->UserModel->count_all();
		$sql_data = $this->UserModel->filter($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->UserModel->count_filter($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	public function searchRole()
	{
		$json = [];
		if (!empty($this->input->get("q"))) {
			$this->db->like('name', $this->input->get("q"));
			$query = $this->db->select('id, name as text')->limit(10)->get("user_role");
			$json = $query->result();
		}
		echo json_encode($json);
	}

	private function _validationUser($mode)
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
}
