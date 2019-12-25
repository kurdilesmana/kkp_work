<?php
class ServiceorderModel extends CI_Model
{
	private $_table = "service_order";

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
		return $this->db->get_where($this->_table, ["id_barang" => $id])->row();
	}

	public function entriDataServiceorder($data)
	{
		$check = $this->db->insert($this->_table, $data);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function getServiceorder($id)
	{
		return $this->db->get_where($this->_table, ["id_barang" => $id])->row();
	}

	public function updateServiceorder($data)
	{
		$id = $data['id_barang'];
		$_data = array(
			'nama' => $data['nama'],
            'jenis' => $data['jenis'],
            'spesifikasi' => $data['spesifikasi'],
            'serial_number' => $data['serial_number'],
            'kelengkapan_barang' => $data['kelengkapan_barang'],
            'keluhan' => $data['keluhan'],
            'tgl_masuk' => $data['tgl_masuk']
		);

		$check = $this->db->update($this->_table, $_data, ['id_barang' => $id]);

		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}

	public function deleteServiceorder($id)
	{
		$check = $this->db->delete($this->_table, ['id_barang' => $id]);
		if (!$check) {
			return 'failed';
		} else {
			return 'success';
		}
	}
}
