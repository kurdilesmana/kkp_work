<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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
		$tdata['title'] = 'Users';
		$tdata['caption'] = 'Pengelolaan Data Users';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);

		$this->load->view('base', $ldata);
	}

	public function usersList()
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
			$this->form_validation->set_rules('name', 'Nama', 'required');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
			$this->form_validation->set_rules('confirm-password', 'Konfirmasi Password', 'trim|required|matches[password]');
			$this->form_validation->set_rules('role_id', 'Role', 'required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = array();
			$data['error_string'] = array();

			$fields = array('name', 'email', 'password', 'confirm-password', 'role_id');
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

	public function getRole($id)
	{
		$data = $this->UserModel->getRoleById($id);
		echo json_encode($data);
	}

	public function add()
	{
		$this->_validationUser("save");
		$data = array(
			'name' => htmlspecialchars($this->input->post("name", TRUE)),
			'email' => htmlspecialchars($this->input->post("email", TRUE)),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'role_id' => $this->input->post('role_id', TRUE),
			'is_active' => $this->input->post('is_active', TRUE),
		);

		var_dump($data);
		die();

		$doInsert = $this->UserModel->entriData($data);

		//Pengecekan input data
		if ($doInsert == 'failed') {
			$isErr = 1;
			$Msg = 'Data gagal ditambahkan!';
		} else if ($doInsert == 'exist') {
			$isErr = 1;
			$Msg = 'Email Sudah Tersedia!';
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
