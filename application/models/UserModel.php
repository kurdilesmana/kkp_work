<?php
class UserModel extends CI_Model
{
	private $_table = "users";
	private $_tableRole = "users";

	public function __construct()
	{
		parent::__construct();
	}

	public function isLogin()
	{
		return $this->session->userdata('email');
	}

	public function checkLogin($data)
	{
		$user = $this->db->get_where($this->_table, array('email' => $data['email']))->row_array();
		if ($user) {
			if ($user['is_active'] == 1) {
				if (password_verify($data['password'], $user['password'])) {
					return $user;
				} else {
					return 'wrong_password';
				}
			} else {
				return 'email_not_actived';
			}
		} else {
			return 'email_not_registered';
		}
	}

	// Header Menu
	public function count_all()
	{
		return $this->db->count_all($this->_table);
	}

	public function filter($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$this->db->like('name', $search);
		$this->db->order_by($order_field, $order_ascdesc);
		$this->db->limit($limit, $start);
		return $this->db->get($this->_table)->result_array();
	}

	public function count_filter($search)
	{
		$this->db->like('name', $search);
		return $this->db->get($this->_table)->num_rows();
	}

	public function getUserById($id)
	{
		return $this->db->get_where($this->_table, ["id" => $id])->row();
	}

	public function getRoleById($id)
	{
		return $this->db->get_where($this->_tableRole, ["id" => $id])->row();
	}

	public function entriData($data)
	{
		$check = $this->_checkUserId($data['email']);
		if ($check > 0) {
			return 'exist';
		} else {
			$this->db->insert($this->_table, $data);
			return 'success';
		}
	}

	private function _checkUserId($email)
	{
		$this->db->select("id, email");
		$this->db->from($this->_table);
		$this->db->where('email', $this->db->escape_str($email));
		$result = $this->db->get()->num_rows();
		return $result;
	}
}
