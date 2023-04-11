<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Mutasi Keluar Controller
*| --------------------------------------------------------------------------
*| Mutasi Keluar site
*|
*/
class Mutasi_keluar extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_mutasi_keluar');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Mutasi Keluars
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('mutasi_keluar_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['mutasi_keluars'] = $this->model_mutasi_keluar->get($filter, $field, $this->limit_page, $offset);
		$this->data['mutasi_keluar_counts'] = $this->model_mutasi_keluar->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/mutasi_keluar/index/',
			'total_rows'   => $this->data['mutasi_keluar_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Mutasi Keluar List');
		$this->render('backend/standart/administrator/mutasi_keluar/mutasi_keluar_list', $this->data);
	}
	
	/**
	* Add new mutasi_keluars
	*
	*/
	public function add() {
		$this->is_allowed('mutasi_keluar_add');

		$this->template->title('Mutasi Keluar New');
		$this->render('backend/standart/administrator/mutasi_keluar/mutasi_keluar_add', $this->data);
	}

	/**
	* Add New Mutasi Keluars
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('mutasi_keluar_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		$this->form_validation->set_rules('mutasi_keluar_tgl_keluar', 'Tanggal Keluar', 'trim|required');
		$this->form_validation->set_rules('mutasi_keluar_bidang_id', 'Bidang', 'trim|required');
		$this->form_validation->set_rules('mutasi_keluar_status', 'Status', 'trim|required');
		
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
						'mutasi_keluar_tgl_keluar' 		=> $this->input->post('mutasi_keluar_tgl_keluar'),
						'mutasi_keluar_bidang_id' 		=> $this->input->post('mutasi_keluar_bidang_id'),
						'mutasi_keluar_status' 			=> $this->input->post('mutasi_keluar_status'),
						'mutasi_keluar_keterangan' 		=> $this->input->post('mutasi_keluar_keterangan'),
						'mutasi_keluar_user_created' 	=> get_user_data('id'),
						'mutasi_keluar_created_at' 		=> date('Y-m-d H:i:s'),
					];
					
					$save_mutasi_keluar = $id = $this->model_mutasi_keluar->store($save_data);
		
					if ($save_mutasi_keluar) {
						for ($i=0; $i < count($barang); $i++) {
							$save_detail = [
								'mukeldet_keluar_id' 	=> $id,
								'mukeldet_barang_id' 	=> $barang[$i],
								'mukeldet_jumlah' 		=> $jumlah[$i],
								'mukeldet_keterangan' 	=> $keterangan[$i],
								'mukeldet_user_created' => get_user_data('id'),
								'mukeldet_created_at' 	=> date('Y-m-d H:i:s'),
							];

							$this->db->insert('mutasi_keluar_detail', $save_detail);
						}

						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = true;
							$this->data['id'] 	   = $save_mutasi_keluar;
							$this->data['message'] = cclang('success_save_data_stay', [
								anchor('administrator/mutasi_keluar/edit/' . $save_mutasi_keluar, 'Edit Mutasi Keluar'),
								anchor('administrator/mutasi_keluar', ' Go back to list')
							]);
						} else {
							set_message(
								cclang('success_save_data_redirect', [
								anchor('administrator/mutasi_keluar/edit/' . $save_mutasi_keluar, 'Edit Mutasi Keluar')
							]), 'success');
		
							$this->data['success'] = true;
							$this->data['redirect'] = base_url('administrator/mutasi_keluar');
						}
					} else {
						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
						} else {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
							$this->data['redirect'] = base_url('administrator/mutasi_keluar');
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
	* Update view Mutasi Keluars
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('mutasi_keluar_update');

		$this->data['mutasi_keluar'] = $this->model_mutasi_keluar->find($id);

		$this->template->title('Mutasi Keluar Update');
		$this->render('backend/standart/administrator/mutasi_keluar/mutasi_keluar_update', $this->data);
	}

	/**
	* Update Mutasi Keluars
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('mutasi_keluar_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('mutasi_keluar_tgl_keluar', 'Tanggal Keluar', 'trim|required');
		$this->form_validation->set_rules('mutasi_keluar_bidang_id', 'Bidang', 'trim|required');
		$this->form_validation->set_rules('mutasi_keluar_status', 'Status', 'trim|required');
		
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
						'mutasi_keluar_tgl_keluar' 	=> $this->input->post('mutasi_keluar_tgl_keluar'),
						'mutasi_keluar_bidang_id' 	=> $this->input->post('mutasi_keluar_bidang_id'),
						'mutasi_keluar_status' 		=> $this->input->post('mutasi_keluar_status'),
						'mutasi_keluar_keterangan' 	=> $this->input->post('mutasi_keluar_keterangan'),
					];
					
					$save_mutasi_keluar = $this->model_mutasi_keluar->change($id, $save_data);

					if ($save_mutasi_keluar) {
						$this->db->delete('mutasi_keluar_detail', ['mukeldet_keluar_id' => $id]);
						
						for ($i=0; $i < count($barang); $i++) {
							$save_detail = [
								'mukeldet_keluar_id' 	=> $id,
								'mukeldet_barang_id' 	=> $barang[$i],
								'mukeldet_jumlah' 		=> $jumlah[$i],
								'mukeldet_keterangan' 	=> $keterangan[$i],
								'mukeldet_user_created' => get_user_data('id'),
								'mukeldet_created_at' 	=> date('Y-m-d H:i:s'),
							];

							$this->db->insert('mutasi_keluar_detail', $save_detail);
						}

						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = true;
							$this->data['id'] 	   = $id;
							$this->data['message'] = cclang('success_update_data_stay', [
								anchor('administrator/mutasi_keluar', ' Go back to list')
							]);
						} else {
							set_message(
								cclang('success_update_data_redirect', [
							]), 'success');

							$this->data['success'] = true;
							$this->data['redirect'] = base_url('administrator/mutasi_keluar');
						}
					} else {
						if ($this->input->post('save_type') == 'stay') {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
						} else {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
							$this->data['redirect'] = base_url('administrator/mutasi_keluar');
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
	* delete Mutasi Keluars
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('mutasi_keluar_delete');

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
            set_message(cclang('has_been_deleted', 'mutasi_keluar'), 'success');
        } else {
            set_message(cclang('error_delete', 'mutasi_keluar'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Mutasi Keluars
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('mutasi_keluar_view');

		$this->data = [
			'mutasi_keluar' => $this->model_mutasi_keluar->join_avaiable()->filter_avaiable()->find($id),
			'detail_keluar' => $this->model_mutasi_keluar->query_item_mutasi_keluar($id)->result(),
		];

		$this->template->title('Mutasi Keluar Detail');
		$this->render('backend/standart/administrator/mutasi_keluar/mutasi_keluar_view', $this->data);
	}
	
	/**
	* delete Mutasi Keluars
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$mutasi_keluar = $this->model_mutasi_keluar->find($id);

		
		
		return $this->model_mutasi_keluar->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('mutasi_keluar_export');

		$this->model_mutasi_keluar->export(
			'mutasi_keluar', 
			'mutasi_keluar',
			$this->model_mutasi_keluar->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('mutasi_keluar_export');

		$this->model_mutasi_keluar->pdf('mutasi_keluar', 'mutasi_keluar');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('mutasi_keluar_export');

		$table = $title = 'mutasi_keluar';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_mutasi_keluar->find($id);
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

	public function verify($id) {
		if (!$this->is_allowed('mutasi_keluar_verify', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$data = [
			'mutasi_keluar_verified' 		=> 1,
			'mutasi_keluar_user_verified' 	=> get_user_data('id'),
			'mutasi_keluar_verify_at' 		=> date('Y-m-d H:i:s'),
		];

		$this->model_mutasi_keluar->change($id, $data);
		redirect_back();
	}
}


/* End of file mutasi_keluar.php */
/* Location: ./application/controllers/administrator/Mutasi Keluar.php */