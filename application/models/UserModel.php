<?php
class UserModel extends CI_Model
{
	private $_table = "users";

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
}
