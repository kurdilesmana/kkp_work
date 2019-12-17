<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('UserModel');
		$this->load->model('MenuModel');

		if (!$this->UserModel->isLogin()) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$tdata['title'] = 'Header Menu';
		$tdata['caption'] = 'Pengelolaan Header Menu';

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/index', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_index', $tdata, true);
		$this->load->view('base', $ldata);
	}

	public function headerMenuList()
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

	public function searchHeader()
	{
		$json = [];
		if (!empty($this->input->get("q"))) {
			$this->db->like('header_menu', $this->input->get("q"));
			$query = $this->db->select('id, header_menu as text')->limit(10)->get("user_header_menu");
			$json = $query->result();
		}
		echo json_encode($json);
	}

	private function _validationHeader($mode)
	{
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

	public function getHeaderMenu($id)
	{
		$data = $this->MenuModel->getHeaderById($id);
		echo json_encode($data);
	}

	public function addHeaderMenu()
	{
		$this->_validationHeader("save");
		$data = array(
			'header_menu' => $this->input->post('header_menu', TRUE)
		);

		$doInsert = $this->MenuModel->entriDataHeader($data);

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

	public function updateHeaderMenu()
	{
		$this->_validationHeader("save");
		$data = array(
			'id' => $this->input->post('id', TRUE),
			'header_menu' => $this->input->post('header_menu', TRUE)
		);

		$doUpdate = $this->MenuModel->updateDataHeader($data);

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

	public function deleteHeaderMenu()
	{
		$id = $this->input->post('id', TRUE);
		$doDelete = $this->MenuModel->deleteDataHeader($id);

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

	public function subMenu()
	{
		$tdata['title'] = "Menu";
		$tdata['caption'] = "Pengelolaan Data Menu";

		## LOAD LAYOUT ##	
		$ldata['content'] = $this->load->view($this->router->class . '/submenu', $tdata, true);
		$ldata['script'] = $this->load->view($this->router->class . '/js_submenu', $tdata, true);
		$this->load->view('base', $ldata);
	}

	public function subMenuList()
	{
		$search = $_POST['search']['value'];
		$limit = $_POST['length'];
		$start = $_POST['start'];
		$order_index = $_POST['order'][0]['column'];
		$order_field = $_POST['columns'][$order_index]['data'];
		$order_ascdesc = $_POST['order'][0]['dir'];
		$sql_total = $this->MenuModel->count_all_menu();
		$sql_data = $this->MenuModel->filter_menu($search, $limit, $start, $order_field, $order_ascdesc);
		$sql_filter = $this->MenuModel->count_filter_menu($search);
		$callback = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $sql_total,
			'recordsFiltered' => $sql_filter,
			'data' => $sql_data
		);
		header('Content-Type: application/json');
		echo json_encode($callback);
	}

	private function _validationMenu($mode)
	{
		if ($mode == "save") {
			$this->form_validation->set_rules('header_id', 'Header', 'required');
			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('url', 'URL', 'required');
			$this->form_validation->set_rules('icon', 'Icon', 'required');
			$this->form_validation->set_rules('no_order', 'Nomor Order', 'required');
		}
		if ($this->form_validation->run()) {
			return true;
		} else {
			$data = array();
			$data['inputerror'] = array();
			$data['error_string'] = array();

			$fields = array('header_id', 'title', 'url', 'no_order', 'icon');
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

	public function getSubMenu($id)
	{
		$data = $this->MenuModel->getSubMenuById($id);
		echo json_encode($data);
	}

	public function addSubMenu()
	{
		$this->_validationMenu("save");
		$data = array(
			'header_id' => $this->input->post('header_id', TRUE),
			'title' => $this->input->post('title', TRUE),
			'url' => $this->input->post('url', TRUE),
			'icon' => $this->input->post('icon', TRUE),
			'no_order' => $this->input->post('no_order', TRUE),
			'parent_id' => $this->input->post('parent_id', TRUE),
			'is_active' => $this->input->post('is_active', TRUE)
		);

		$doInsert = $this->MenuModel->entriDataMenu($data);

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
}
