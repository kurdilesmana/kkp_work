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
		$this->db->select(
			'date_format(service_order.tgl_masuk, "%d/%m/%Y") as tgl_masuk, 
      COALESCE(customers.nama, "undefined") as customer, 
      COALESCE(karyawan.nama_karyawan, "undefined") as karyawan,
			service_order.id_barang, service_order.jenis, service_order.serial_number, 
			service_order.spesifikasi, service_order.kelengkapan_barang, service_order.keluhan'
		);
		$this->db->from($this->_table);
		$this->db->join('customers', 'customers.id_customers=service_order.customer_id', 'left');
		$this->db->join('karyawan', 'karyawan.id_karyawan=service_order.karyawan_id', 'left');
		$this->db->like('customers.nama', $search);
		$this->db->order_by($order_field, $order_ascdesc);
		$this->db->limit($limit, $start);
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function count_filter($search)
	{
		$this->db->select(
			'date_format(service_order.tgl_masuk, "%d/%m/%Y") as tgl_masuk, 
      COALESCE(customers.nama, "undefined") as customer, 
      COALESCE(karyawan.nama_karyawan, "undefined") as karyawan,
			service_order.id_barang, service_order.jenis, service_order.serial_number, 
			service_order.spesifikasi, service_order.kelengkapan_barang, service_order.keluhan'
		);
		$this->db->from($this->_table);
		$this->db->join('customers', 'customers.id_customers=service_order.customer_id', 'left');
		$this->db->join('karyawan', 'karyawan.id_karyawan=service_order.karyawan_id', 'left');
		$this->db->like('customers.nama', $search);
		$query = $this->db->get()->num_rows();
		return $query;
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
			'customer_id' => $data['customer_id'],
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
