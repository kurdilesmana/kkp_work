<?php
class MenuModel extends CI_Model
{
	private $_table = "user_header_menu";
	private $_tableMenu = "user_menu";

	public function __construct()
	{
		parent::__construct();
	}

	// Header Menu
	public function count_all()
	{
		return $this->db->count_all($this->_table);
	}

	public function filter($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$this->db->like('header_menu', $search);
		$this->db->order_by($order_field, $order_ascdesc);
		$this->db->limit($limit, $start);
		return $this->db->get($this->_table)->result_array();
	}

	public function count_filter($search)
	{
		$this->db->like('header_menu', $search);
		return $this->db->get($this->_table)->num_rows();
	}

	public function getHeaderById($id)
	{
		return $this->db->get_where($this->_table, ["id" => $id])->row();
	}

	public function entriDataHeader($data)
	{
		$check = $this->db->insert($this->_table, $data);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function updateDataHeader($data)
	{
		$id = $data['id'];
		$_data = array('header_menu' => $data['header_menu'],);

		$check = $this->db->update($this->_table, $_data, ['id' => $id]);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function deleteDataHeader($id)
	{
		$check = $this->db->delete($this->_table, ['id' => $id]);
		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	// Menu
	public function count_all_menu()
	{
		return $this->db->count_all($this->_tableMenu);
	}

	public function filter_menu($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$this->db->like('title', $search);
		$this->db->order_by($order_field, $order_ascdesc);
		$this->db->limit($limit, $start);
		$this->db->select(
			'`hm`.header_menu, m.*'
		);
		$this->db->from($this->_tableMenu . ' m');
		$this->db->join('user_header_menu hm', 'hm.id=m.header_id', 'inner');
		return $this->db->get()->result_array();
	}

	public function count_filter_menu($search)
	{
		$this->db->like('title', $search);
		return $this->db->get($this->_tableMenu)->num_rows();
	}
	public function getSubMenuById($id)
	{
		return $this->db->get_where($this->_tableMenu, ["id" => $id])->row();
	}

	public function entriDataMenu($data)
	{
		$check = $this->db->insert($this->_tableMenu, $data);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}
}
