<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Barang Controller
*| --------------------------------------------------------------------------
*| Barang site
*|
*/
class Barang extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Barangs
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('barang_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['barangs'] = $this->model_barang->get($filter, $field, $this->limit_page, $offset);
		$this->data['barang_counts'] = $this->model_barang->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/barang/index/',
			'total_rows'   => $this->data['barang_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Barang List');
		$this->render('backend/standart/administrator/barang/barang_list', $this->data);
	}
	
	/**
	* Add new barangs
	*
	*/
	public function add() {
		$this->is_allowed('barang_add');

		$this->template->title('Barang New');
		$this->render('backend/standart/administrator/barang/barang_add', $this->data);
	}

	/**
	* Add New Barangs
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('barang_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('barang_kategori_barang_id', 'Katgeori Barang', 'trim|required');
		$this->form_validation->set_rules('barang_nama', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('barang_satuan_id', 'Satuan', 'trim|required');

		$nama_barang 		= $this->input->post('barang_nama');
		$kategori_barang 	= $this->input->post('barang_kategori_barang_id');
		$satuan_barang 		= $this->input->post('barang_satuan_id');

		$conditions = [
			'LOWER(barang_nama)' 		=> strtolower($nama_barang),
			'barang_kategori_barang_id' => $kategori_barang,
			'barang_satuan_id' 			=> $satuan_barang,
		];

		$barang = $this->db->where($conditions)->get('barang')->row();

		if ($this->form_validation->run()) {
			if (count($barang) > 0) {
				$this->data['success'] = false;
				$this->data['message'] = 'Data sudah tersedia! <a href="'.site_url('administrator/barang/view/'.$barang->barang_id).'">Lihat Data</a>';
			}else{
				$save_data = [
					'barang_kategori_barang_id' => $kategori_barang,
					'barang_nama' 				=> $nama_barang,
					'barang_satuan_id' 			=> $satuan_barang,
					'barang_user_created' 		=> get_user_data('id'),
					'barang_created_at' 		=> date('Y-m-d H:i:s'),
				];
	
				$save_barang = $id = $this->model_barang->store($save_data);
	
				if ($save_barang) {
					$id = $save_barang;
	
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $save_barang;
						$this->data['message'] = cclang('success_save_data_stay', [
							anchor('administrator/barang/edit/' . $save_barang, 'Edit Barang'),
							anchor('administrator/barang', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_save_data_redirect', [
							anchor('administrator/barang/edit/' . $save_barang, 'Edit Barang')
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/barang');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/barang');
					}
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
	* Update view Barangs
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('barang_update');

		$this->data['barang'] = $this->model_barang->find($id);

		$this->template->title('Barang Update');
		$this->render('backend/standart/administrator/barang/barang_update', $this->data);
	}

	/**
	* Update Barangs
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('barang_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('barang_kategori_barang_id', 'Katgeori Barang', 'trim|required');
		$this->form_validation->set_rules('barang_nama', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('barang_satuan_id', 'Satuan', 'trim|required');

		$nama_barang 		= $this->input->post('barang_nama');
		$kategori_barang 	= $this->input->post('barang_kategori_barang_id');
		$satuan_barang 		= $this->input->post('barang_satuan_id');

		$conditions = [
			'barang_nama' 				=> $nama_barang,
			'barang_kategori_barang_id' => $kategori_barang,
			'barang_satuan_id' 			=> $satuan_barang,
			'barang_id !=' 				=> $id,
		];

		$barang = $this->db->where($conditions)->get('barang')->row();
		
		if ($this->form_validation->run()) {
			if (count($barang) > 0) {
				$this->data['success'] = false;
				$this->data['message'] = 'Data sudah tersedia!';
			}else{
				$save_data = [
					'barang_kategori_barang_id' => $kategori_barang,
					'barang_nama' 				=> $nama_barang,
					'barang_satuan_id' 			=> $satuan_barang,
				];

				$save_barang = $this->model_barang->change($id, $save_data);
	
				if ($save_barang) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $id;
						$this->data['message'] = cclang('success_update_data_stay', [
							anchor('administrator/barang', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_update_data_redirect', [
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/barang');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/barang');
					}
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
	* delete Barangs
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('barang_delete');

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
            set_message(cclang('has_been_deleted', 'barang'), 'success');
        } else {
            set_message(cclang('error_delete', 'barang'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Barangs
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('barang_view');

		$this->data['barang'] = $this->model_barang->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Barang Detail');
		$this->render('backend/standart/administrator/barang/barang_view', $this->data);
	}
	
	/**
	* delete Barangs
	*
	* @var $id String
	*/
	private function _remove($id) {
		$barang = $this->model_barang->find($id);

		return $this->model_barang->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('barang_export');

		$this->model_barang->export(
			'barang', 
			'barang',
			$this->model_barang->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf() {
		$this->is_allowed('barang_export');

		$this->model_barang->pdf('barang', 'barang');
	}


	public function single_pdf($id = null) {
		$this->is_allowed('barang_export');

		$table = $title = 'barang';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_barang->find($id);
        $fields = $result->list_fields();

        $content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf_single', [
            'data' 		=> $data,
            'fields' 	=> $fields,
            'title' 	=> $title,
        ], TRUE);

        $this->pdf->initialize($config);
        $this->pdf->pdf->SetDisplayMode('fullpage');
        $this->pdf->writeHTML($content);
        $this->pdf->Output($table.'.pdf', 'H');
	}
}


/* End of file barang.php */
/* Location: ./application/controllers/administrator/Barang.php */