<?php
class KaryawanModel extends CI_Model
{
	private $_table = "karyawan";

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
		$this->db->like('nama_karyawan', $search);
		$this->db->order_by($order_field, $order_ascdesc);
		$this->db->limit($limit, $start);
		return $this->db->get($this->_table)->result_array();
	}

	public function count_filter($search)
	{
		$this->db->like('nama_karyawan', $search);
		return $this->db->get($this->_table)->num_rows();
	}
	public function getHeaderById($id)
	{
		return $this->db->get_where($this->_table, ["id_karyawan" => $id])->row();
	}

	public function entriDataKaryawan($data)
	{
		$check = $this->db->insert($this->_table, $data);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function getKaryawan($id)
	{
		return $this->db->get_where($this->_table, ["id_karyawan" => $id])->row();
	}

	public function updateKaryawan($data)
	{
		$id = $data['id_karyawan'];
		$_data = array(
			'nama_karyawan' => $data['nama_karyawan'],
			'bagian_karyawan' => $data['bagian_karyawan']
		);

		$check = $this->db->update($this->_table, $_data, ['id_karyawan' => $id]);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function deleteKaryawan($id)
	{
		$check = $this->db->delete($this->_table, ['id_karyawan' => $id]);
		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}
}
