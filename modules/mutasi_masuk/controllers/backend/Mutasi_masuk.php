<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Mutasi Masuk Controller
*| --------------------------------------------------------------------------
*| Mutasi Masuk site
*|
*/
class Mutasi_masuk extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_mutasi_masuk');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	public function nyoba1($id) {
		for ($i=4; $i <= $id; $i++) {
			$dateObj   = DateTime::createFromFormat('!m', $i);
			$monthName = $dateObj->format('F');

			$data[] = $monthName.' - '.cal_days_in_month(CAL_GREGORIAN, $i, 2023);
		}

		echo json_encode($data);
	}

	public function nyoba($id) {
		/* $date 		= '2023-03-01';
		$day_before = date('Y-m-d', strtotime( $date . ' -1 day' ) );

		echo $day_before; */

		// echo json_encode($data_awal);
	}

	/**
	* show all Mutasi Masuks
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('mutasi_masuk_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['mutasi_masuks'] = $this->model_mutasi_masuk->get($filter, $field, $this->limit_page, $offset);
		$this->data['mutasi_masuk_counts'] = $this->model_mutasi_masuk->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/mutasi_masuk/index/',
			'total_rows'   => $this->data['mutasi_masuk_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Mutasi Masuk List');
		$this->render('backend/standart/administrator/mutasi_masuk/mutasi_masuk_list', $this->data);
	}

	/**
	* Add new mutasi_masuks
	*
	*/
	public function add() {
		$this->is_allowed('mutasi_masuk_add');

		$this->template->title('Mutasi Masuk New');
		$this->render('backend/standart/administrator/mutasi_masuk/mutasi_masuk_add', $this->data);
	}

	/**
	* Add New Mutasi Masuks
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('mutasi_masuk_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('mutasi_masuk_tgl_masuk', 'Tanggal Masuk', 'trim|required');
		$this->form_validation->set_rules('mutasi_masuk_bidang_id', 'Bidang', 'trim|required');
		$this->form_validation->set_rules('mutasi_masuk_status', 'Status', 'trim|required');

		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');

		if ($this->form_validation->run()) {
			if (empty($barang[0]) || empty($jumlah[0])) {
				$this->data['success'] = false;
				$this->data['message'] = 'Tidak ada data barang yang di input!';
			}else{
				if (count($barang) > 0) {
					$save_data = [
						'mutasi_masuk_tgl_masuk' 	=> $this->input->post('mutasi_masuk_tgl_masuk'),
						'mutasi_masuk_bidang_id' 	=> $this->input->post('mutasi_masuk_bidang_id'),
						'mutasi_masuk_status' 		=> $this->input->post('mutasi_masuk_status'),
						'mutasi_masuk_keterangan' 	=> $this->input->post('mutasi_masuk_keterangan'),
						'mutasi_masuk_user_created' => get_user_data('id'),
						'mutasi_masuk_created_at' 	=> date('Y-m-d H:i:s'),
					];

					$save_mutasi_masuk = $id = $this->model_mutasi_masuk->store($save_data);

					if ($save_mutasi_masuk) {
						$id = $save_mutasi_masuk;

						for ($i=0; $i < count($barang); $i++) {
							$save_detail = [
								'mumadet_masuk_id' 		=> $id,
								'mumadet_barang_id' 	=> $barang[$i],
								'mumadet_jumlah' 		=> $jumlah[$i],
								'mumadet_keterangan' 	=> $keterangan[$i],
								'mumadet_user_created' 	=> get_user_data('id'),
								'mumadet_created_at' 	=> date('Y-m-d H:i:s'),
							];

							$this->db->insert('mutasi_masuk_detail', $save_detail);
						}

						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = true;
							$this->data['id'] 	   = $save_mutasi_masuk;
							$this->data['message'] = cclang('success_save_data_stay', [
								anchor('administrator/mutasi_masuk/edit/' . $save_mutasi_masuk, 'Edit Mutasi Masuk'),
								anchor('administrator/mutasi_masuk', ' Go back to list')
							]);
						} else {
							set_message(
								cclang('success_save_data_redirect', [
								anchor('administrator/mutasi_masuk/edit/' . $save_mutasi_masuk, 'Edit Mutasi Masuk')
							]), 'success');
		
							$this->data['success'] = true;
							$this->data['redirect'] = base_url('administrator/mutasi_masuk');
						}
					} else {
						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
						} else {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
							$this->data['redirect'] = base_url('administrator/mutasi_masuk');
						}
					}
				}else{
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
		/**
	* Update view Mutasi Masuks
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('mutasi_masuk_update');

		$this->data['mutasi_masuk'] = $this->model_mutasi_masuk->find($id);

		$this->template->title('Mutasi Masuk Update');
		$this->render('backend/standart/administrator/mutasi_masuk/mutasi_masuk_update', $this->data);
	}

	/**
	* Update Mutasi Masuks
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('mutasi_masuk_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('mutasi_masuk_tgl_masuk', 'Tanggal Masuk', 'trim|required');
		$this->form_validation->set_rules('mutasi_masuk_bidang_id', 'Bidang', 'trim|required');
		$this->form_validation->set_rules('mutasi_masuk_status', 'Status', 'trim|required');
		
		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');
		
		if ($this->form_validation->run()) {
			if (empty($barang[0]) || empty($jumlah[0])) {
				$this->data['success'] = false;
				$this->data['message'] = 'Tidak ada data barang yang di input!';
			}else{
				if (count($barang) > 0) {
					$save_data = [
						'mutasi_masuk_tgl_masuk' 	=> $this->input->post('mutasi_masuk_tgl_masuk'),
						'mutasi_masuk_bidang_id' 	=> $this->input->post('mutasi_masuk_bidang_id'),
						'mutasi_masuk_status' 		=> $this->input->post('mutasi_masuk_status'),
						'mutasi_masuk_keterangan' 	=> $this->input->post('mutasi_masuk_keterangan'),
					];

					$save_mutasi_masuk = $this->model_mutasi_masuk->change($id, $save_data);

					if ($save_mutasi_masuk) {
						$this->db->delete('mutasi_masuk_detail', ['mumadet_masuk_id' => $id]);

						$id = $save_mutasi_masuk;

						for ($i=0; $i < count($barang); $i++) {
							$save_detail = [
								'mumadet_masuk_id' 		=> $id,
								'mumadet_barang_id' 	=> $barang[$i],
								'mumadet_jumlah' 		=> $jumlah[$i],
								'mumadet_keterangan' 	=> $keterangan[$i],
								'mumadet_user_created' 	=> get_user_data('id'),
								'mumadet_created_at' 	=> date('Y-m-d H:i:s'),
							];

							$this->db->insert('mutasi_masuk_detail', $save_detail);
						}

						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = true;
							$this->data['id'] 	   = $id;
							$this->data['message'] = cclang('success_update_data_stay', [
								anchor('administrator/mutasi_masuk', ' Go back to list')
							]);
						} else {
							set_message(
								cclang('success_update_data_redirect', [
							]), 'success');

							$this->data['success'] = true;
							$this->data['redirect'] = base_url('administrator/mutasi_masuk');
						}
					} else {
						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
						} else {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
							$this->data['redirect'] = base_url('administrator/mutasi_masuk');
						}
					}
				}else{
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
	/**
	* delete Mutasi Masuks
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('mutasi_masuk_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'mutasi_masuk'), 'success');
        } else {
            set_message(cclang('error_delete', 'mutasi_masuk'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Mutasi Masuks
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('mutasi_masuk_view');

		$this->data = [
			'mutasi_masuk' => $this->model_mutasi_masuk->join_avaiable()->filter_avaiable()->find($id),
		];

		$this->template->title('Mutasi Masuk Detail');
		$this->render('backend/standart/administrator/mutasi_masuk/mutasi_masuk_view', $this->data);
	}
	
	/**
	* delete Mutasi Masuks
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$mutasi_masuk = $this->model_mutasi_masuk->find($id);

		
		
		return $this->model_mutasi_masuk->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('mutasi_masuk_export');

		$this->model_mutasi_masuk->export(
			'mutasi_masuk', 
			'mutasi_masuk',
			$this->model_mutasi_masuk->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf() {
		$this->is_allowed('mutasi_masuk_export');

		$this->model_mutasi_masuk->pdf('mutasi_masuk', 'mutasi_masuk');
	}


	public function single_pdf($id = null) {
		$this->is_allowed('mutasi_masuk_export');

		$table = $title = 'mutasi_masuk';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_mutasi_masuk->find($id);
        $fields = $result->list_fields();

        $content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf_single', [
            'data' => $data,
            'fields' => $fields,
            'title' => $title
        ], TRUE);

        $this->pdf->initialize($config);
        $this->pdf->pdf->SetDisplayMode('fullpage');
        $this->pdf->writeHTML($content);
        $this->pdf->Output($table.'.pdf', 'H');
	}

	public function ajax_kategori_id($id = null) {
		if (!$this->is_allowed('menara_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$results = db_get_all_data('barang', ['barang_kategori_barang_id' => $id]);
		$this->response($results);	
	}

	public function verify($id) {
		if (!$this->is_allowed('mutasi_masuk_verify', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$data = [
			'mutasi_masuk_verified' 		=> 1,
			'mutasi_masuk_user_verified' 	=> get_user_data('id'),
			'mutasi_masuk_verify_at' 		=> date('Y-m-d H:i:s'),
		];

		$this->model_mutasi_masuk->change($id, $data);

		$data_masuk = $this->db->join('mutasi_masuk', 'mutasi_masuk_id = mumadet_masuk_id', 'LEFT')->where('mumadet_masuk_id', $id)->get('mutasi_masuk_detail')->result();
		
		foreach ($data_masuk as $item) {
			$data_stok_awal = db_get_all_data('stok_awal_barang', ['stok_awal_barang_id' => $item->mumadet_barang_id])[0];

			if (count($data_stok_awal) <= 0) {
				$data_awal = [
					'stok_awal_barang_id' 				=> $item->mumadet_barang_id,
					'stok_awal_jumlah' 					=> $item->mumadet_jumlah,
					'stok_awal_mutasi_masuk_id' 		=> $item->mumadet_masuk_id,
					'stok_awal_detail_mutasi_masuk_id' 	=> $item->mumadet_id,
					'stok_awal_tgl_masuk' 				=> $item->mutasi_masuk_tgl_masuk,
					'stok_awal_created_at' 				=> date('Y-m-d H:I:s'),
					'stok_awal_user_created' 			=> get_user_data('id'),
				];

				$this->db->insert('stok_awal_barang', $data_awal);
			}
		}

		redirect_back();
	}
}


/* End of file mutasi_masuk.php */
/* Location: ./application/controllers/administrator/Mutasi Masuk.php */