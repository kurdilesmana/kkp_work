<?php
class CustomersModel extends CI_Model
{
	private $_table = "customers";

	public function __construct()
	{
		parent::__construct();
	}

	public function count_all()
	{
		return $this->db->count_all($this->_table);
	}

	public function filter($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$this->db->like('nama', $search);
		$this->db->order_by($order_field, $order_ascdesc);
		$this->db->limit($limit, $start);
		return $this->db->get($this->_table)->result_array();
    }
    
    public function count_filter($search)
	{
		$this->db->like('nama', $search);
		return $this->db->get($this->_table)->num_rows();
	}
	public function getHeaderById($id)
	{
		return $this->db->get_where($this->_table, ["id_customers" => $id])->row();
	}

	public function entriDataCustomers($data)
	{
		$check = $this->db->insert($this->_table, $data);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function getCustomers($id)
	{
		return $this->db->get_where($this->_table, ["id_customers" => $id])->row();
	}

	public function updateCustomers($data)
	{
		$id = $data['id_customers'];
		$_data = array(
			'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'phone' => $data['phone']
		);

		$check = $this->db->update($this->_table, $_data, ['id_customers' => $id]);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function deleteCustomers($id)
	{
		$check = $this->db->delete($this->_table, ['id_customers' => $id]);
		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}
}
