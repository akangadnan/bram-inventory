<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_laporan_stok_akhir extends MY_Model {
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