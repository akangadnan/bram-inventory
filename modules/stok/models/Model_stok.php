<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_stok extends MY_Model {
	public function __construct() {
		parent::__construct();
	}

	public function filter_avaiable() {
		if (!$this->aauth->is_admin()) {
			$this->db->where($this->table_name.'.bidang_user_created', get_user_data('id'));
		}

		return $this;
	}

	public function query_stok_masuk($bidang_id) {
		$this->db->select('mutasi_masuk_bidang_id, kategori_barang_id, kategori_barang_nama, barang_id, barang_nama, mumadet_jumlah');

		$this->db->join('mutasi_masuk', 'mutasi_masuk_id = mumadet_masuk_id', 'LEFT');
		$this->db->join('barang', 'barang_id = mumadet_barang_id', 'LEFT');
		$this->db->join('bidang', 'bidang_id = mutasi_masuk_bidang_id', 'LEFT');
		$this->db->join('kategori_barang', 'kategori_barang_id = barang_kategori_barang_id', 'LEFT');

		$this->db->where(['mutasi_masuk_bidang_id' => $bidang_id, 'mutasi_masuk_status' => '1']);
		
		$query = $this->db->get('mutasi_masuk_detail');

		return $query;
	}

	public function query_stok_keluar($bidang_id) {
		$this->db->select('mutasi_keluar_bidang_id, kategori_barang_id, kategori_barang_nama, barang_id, barang_nama, mukeldet_jumlah');
		
		$this->db->join('mutasi_keluar', 'mutasi_keluar_id = mukeldet_keluar_id', 'LEFT');
		$this->db->join('barang', 'barang_id = mukeldet_barang_id', 'LEFT');
		$this->db->join('bidang', 'bidang_id = mutasi_keluar_bidang_id', 'LEFT');
		$this->db->join('kategori_barang', 'kategori_barang_id = barang_kategori_barang_id', 'LEFT');
		
		$this->db->where(['mutasi_keluar_bidang_id' => $bidang_id, 'mutasi_keluar_status' => '1']);
		
		$query = $this->db->get('mutasi_keluar_detail');
		
		return $query;
	}

}

/* End of file Model_bidang.php */
/* Location: ./application/models/Model_bidang.php */