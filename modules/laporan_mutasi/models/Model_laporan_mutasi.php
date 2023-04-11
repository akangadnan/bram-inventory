<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_laporan_mutasi extends MY_Model {
	private $primary_key    = 'barang_id';
	private $table_name 	= 'barang';
	public $field_search 	= ['barang_kategori_barang_id', 'barang_nama', 'barang_satuan_id', 'kategori_barang.kategori_barang_nama', 'satuan_barang.satuan_nama'];
	public $sort_option 	= ['barang_id', 'DESC'];

	public function __construct() {
		$config = array(
			'primary_key'   => $this->primary_key,
			'table_name'    => $this->table_name,
			'field_search'  => $this->field_search,
			'sort_option'   => $this->sort_option,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null) {
		$iterasi 	= 1;
		$num 		= count($this->field_search);
		$where 		= NULL;
		$q 			= $this->scurity($q);
		$field 		= $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				$f_search = "barang.".$field;

				if (strpos($field, '.')) {
					$f_search = $field;
				}

				if ($iterasi == 1) {
					$where .=  $f_search . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " .  $f_search . " LIKE '%" . $q . "%' ";
				}

				$iterasi++;
			}

			$where = '('.$where.')';
		} else {
			$where .= "(" . "barang.".$field . " LIKE '%" . $q . "%' )";
		}

		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = []) {
		$iterasi 	= 1;
		$num 		= count($this->field_search);
		$where 		= NULL;
		$q 			= $this->scurity($q);
		$field 		= $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				$f_search = "barang.".$field;

				if (strpos($field, '.')) {
					$f_search = $field;
				}

				if ($iterasi == 1) {
					$where .= $f_search . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " .$f_search . " LIKE '%" . $q . "%' ";
				}

				$iterasi++;
			}

			$where = '('.$where.')';
		} else {
			$where .= "(" . "barang.".$field . " LIKE '%" . $q . "%' )";
		}

		if (is_array($select_field) AND count($select_field)) {
			$this->db->select($select_field);
		}
		
		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$this->db->limit($limit, $offset);
		
		$this->sortable();
		
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	public function join_avaiable() {
		$this->db->join('kategori_barang', 'kategori_barang.kategori_barang_id = barang.barang_kategori_barang_id', 'LEFT');
		$this->db->join('satuan_barang', 'satuan_barang.satuan_id = barang.barang_satuan_id', 'LEFT');
		$this->db->join('stok_awal_barang', 'stok_awal_barang_id = barang_id', 'LEFT');
		
		
		$this->db->select('kategori_barang.kategori_barang_nama,satuan_barang.satuan_nama,barang.*,kategori_barang.kategori_barang_nama as kategori_barang_kategori_barang_nama,kategori_barang.kategori_barang_nama as kategori_barang_nama,satuan_barang.satuan_nama as satuan_barang_satuan_nama,satuan_barang.satuan_nama as satuan_nama,stok_awal_jumlah');

		return $this;
	}

	public function filter_avaiable() {
		if (!$this->aauth->is_admin()) {
			// $this->db->where($this->table_name.'.mutasi_masuk_user_created', get_user_data('id'));
		}

		return $this;
	}

	public function query_stok_awal($tgl_awal, $tgl_akhir, $kategori = NULL, $bidang = NULL) {
		$where_kategori 		= '';
		$where_bidang_masuk 	= '';
		$where_bidang_keluar 	= '';
		$periode_masuk 			= '';
		$periode_keluar 		= '';

		if ($kategori != NULL) {
			$where_kategori = "WHERE barang_kategori_barang_id = '$kategori'";
		}

		if ($bidang != NULL) {
			$where_bidang_masuk 	= "AND mutasi_masuk_bidang_id = '$bidang'";
			$where_bidang_keluar 	= "AND mutasi_keluar_bidang_id = '$bidang'";
		}

		if (!empty($tgl_awal) && !empty($tgl_akhir)) {
			if (!empty($tgl_awal)) {
				$periode_masuk 	= "AND mutasi_masuk_tgl_masuk < '$tgl_awal'";
				$periode_keluar = "AND mutasi_keluar_tgl_keluar < '$tgl_awal'";
			}else if (!empty($tgl_akhir)) {
				$periode_masuk 	= "AND mutasi_masuk_tgl_masuk < '$tgl_akhir'";
				$periode_keluar = "AND mutasi_keluar_tgl_keluar < '$tgl_akhir'";
			}
		}else{
			$periode_masuk 	= "AND mutasi_masuk_tgl_masuk < '2023-02-30'";
			$periode_keluar = "AND mutasi_keluar_tgl_keluar < '2023-02-30'";
		}

		$query = $this->db->query("SELECT
										barang_id,
										IFNULL( ( jumlah_masuk - jumlah_keluar ), 0 ) AS stok_awal 
									FROM
									barang
										LEFT JOIN (
										SELECT
											mukeldet_barang_id,
											SUM( mukeldet_jumlah ) AS jumlah_keluar 
										FROM
											mutasi_keluar
											LEFT JOIN mutasi_keluar_detail ON mutasi_keluar_id = mukeldet_keluar_id
										WHERE
											mutasi_keluar_verified = '1' 
											$periode_keluar $where_bidang_keluar
										GROUP BY
											mukeldet_barang_id 
										) AS mukel ON mukeldet_barang_id = barang_id
										LEFT JOIN (
										SELECT
											mumadet_barang_id,
											SUM( mumadet_jumlah ) AS jumlah_masuk 
										FROM
											mutasi_masuk
											LEFT JOIN mutasi_masuk_detail ON mutasi_masuk_id = mumadet_masuk_id
										WHERE
											mutasi_masuk_verified = '1'
											$periode_masuk $where_bidang_masuk
										GROUP BY
											mumadet_barang_id 
										) AS mumas ON mumadet_barang_id = barang_id 
										$where_kategori
									GROUP BY
										barang_id");

		return $query;
	}

	public function query_stok_akhir($tgl_awal, $tgl_akhir, $kategori = NULL, $bidang = NULL) {
		$where_kategori = '';
		$where_bidang 	= '';
		$periode_masuk 	= '';
		$periode_keluar = '';

		if ($kategori != NULL) {
			$where_kategori = "WHERE kategori_barang_id = '$kategori'";
		}

		if ($bidang != NULL) {
			$where_bidang_masuk 	= "AND mutasi_masuk_bidang_id = '$bidang'";
			$where_bidang_keluar 	= "AND mutasi_keluar_bidang_id = '$bidang'";
		}

		if (!empty($tgl_awal) && !empty($tgl_akhir)) {
			$periode_keluar = "AND mutasi_keluar_tgl_keluar >= '$tgl_awal' AND mutasi_keluar_tgl_keluar <= '$tgl_akhir'";
			$periode_masuk 	= "AND mutasi_masuk_tgl_masuk >= '$tgl_awal' AND mutasi_masuk_tgl_masuk <= '$tgl_akhir'";
		// }else {
		// 	if (empty($tgl_awal)) {
		// 		$periode_masuk 	= "AND mutasi_masuk_tgl_masuk = '$tgl_awal'";
		// 		$periode_keluar = "AND mutasi_keluar_tgl_keluar = '$tgl_awal'";
		// 	}else if (empty($tgl_akhir)) {
		// 		$periode_masuk 	= "AND mutasi_masuk_tgl_masuk = '$tgl_akhir'";
		// 		$periode_keluar = "AND mutasi_keluar_tgl_keluar = '$tgl_akhir'";
		// 	}
		}

		$query = $this->db->query("SELECT
										kategori_barang_id,
										kategori_barang_nama,
										barang_id,
										barang_nama,
										IFNULL( jumlah_masuk, 0) AS masuk,
										IFNULL( jumlah_keluar, 0) AS keluar
									FROM
										barang
									LEFT JOIN kategori_barang ON kategori_barang_id = barang_kategori_barang_id
									LEFT JOIN (
										SELECT
											mukeldet_barang_id,
											SUM( mukeldet_jumlah ) AS jumlah_keluar 
										FROM
											mutasi_keluar
											LEFT JOIN mutasi_keluar_detail ON mutasi_keluar_id = mukeldet_keluar_id
										WHERE
											mutasi_keluar_verified = '1'
											$periode_keluar $where_bidang_keluar
										GROUP BY
											mukeldet_barang_id 
										) AS mukel ON mukeldet_barang_id = barang_id
										LEFT JOIN (
										SELECT
											mumadet_barang_id,
											SUM( mumadet_jumlah ) AS jumlah_masuk 
										FROM
											mutasi_masuk
											LEFT JOIN mutasi_masuk_detail ON mutasi_masuk_id = mumadet_masuk_id
										WHERE
											mutasi_masuk_verified = '1'
											$periode_masuk $where_bidang_masuk
										GROUP BY
											mumadet_barang_id 
										) AS mumas ON mumadet_barang_id = barang_id 
										$where_kategori
									GROUP BY
										barang_id");

		return $query;
	}
}

/* End of file Model_mutasi_masuk.php */
/* Location: ./application/models/Model_mutasi_masuk.php */