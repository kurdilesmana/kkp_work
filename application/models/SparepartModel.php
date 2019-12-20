<?php
class SparepartModel extends CI_Model
{
	private $_table = "sparepart";

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
		return $this->db->get_where($this->_table, ["id_sparepart" => $id])->row();
	}

	public function entriDataSparepart($data)
	{
		$check = $this->db->insert($this->_table, $data);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function getSparepart($id)
	{
		return $this->db->get_where($this->_table, ["id_sparepart" => $id])->row();
	}

	public function updateSparepart($data)
	{
		$id = $data['id_sparepart'];
		$_data = array(
			'nama' => $data['nama'],
            'jenis' => $data['jenis'],
            'stock' => $data['stock'],
            'harga' => $data['harga']
		);

		$check = $this->db->update($this->_table, $_data, ['id_sparepart' => $id]);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function deleteSparepart($id)
	{
		$check = $this->db->delete($this->_table, ['id_sparepart' => $id]);
		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}
}
