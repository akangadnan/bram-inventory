<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Bidang Controller
*| --------------------------------------------------------------------------
*| Bidang site
*|
*/
class Stok extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_stok');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Bidangs
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('stok_list');

		$config = [
			'base_url'     => 'administrator/stok/index/',
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Stok List');
		$this->render('backend/standart/administrator/stok/stok_list', $this->data);
	}

	public function ajax_stok_bidang() {
		$id = $this->input->get('id');
		
		$mutasi_masuk 	= $this->model_stok->query_stok_masuk($id)->result();
		$mutasi_keluar 	= $this->model_stok->query_stok_keluar($id)->result();

		$barang 		= db_get_all_data('barang');
		$bidang 		= db_get_all_data('bidang', ['bidang_id' => $id])[0]->bidang_nama;

		$masuk_jumlah 	= [];
		$keluar_jumlah 	= [];

		foreach ($mutasi_masuk as $item) {
			$masuk_jumlah[$item->barang_id] = $item->mumadet_jumlah;
		}

		foreach ($mutasi_keluar as $item) {
			$keluar_jumlah[$item->barang_id] = $item->mukeldet_jumlah;
		}

		foreach ($barang as $item) {
			$jumlah_masuk[$item->barang_id] 	= 0;
			$jumlah_keluar[$item->barang_id] 	= 0;

			if (array_key_exists($item->barang_id, $masuk_jumlah)) {
				$jumlah_masuk[$item->barang_id] = $masuk_jumlah[$item->barang_id];
			}

			if (array_key_exists($item->barang_id, $keluar_jumlah)) {
				$jumlah_keluar[$item->barang_id] = $keluar_jumlah[$item->barang_id];
			}

			$jumlah_barang[$item->barang_id] 	= $jumlah_masuk[$item->barang_id] - $jumlah_keluar[$item->barang_id];
			$nama_barang[$item->barang_id] 		= $item->barang_nama;

			$result_barang[] 	= [
				'bidang' 			=> $bidang,
				'kategori_barang' 	=> join_multi_select($item->barang_kategori_barang_id, 'kategori_barang', 'kategori_barang_id', 'kategori_barang_nama'),
				'nama_barang' 		=> $nama_barang[$item->barang_id],
				'jumlah_barang' 	=> $jumlah_barang[$item->barang_id],
				'jumlah_masuk' 		=> $jumlah_masuk[$item->barang_id],
				'jumlah_keluar' 	=> $jumlah_keluar[$item->barang_id],
			];
		}

		$data = [
			'data' => $result_barang,
		];

		// echo json_encode($data);
		// exit;
		$this->load->view('backend/standart/administrator/stok/stok_view', $data);
	}

}


/* End of file bidang.php */
/* Location: ./application/controllers/administrator/Bidang.php */