<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Satuan Barang Controller
*| --------------------------------------------------------------------------
*| Satuan Barang site
*|
*/
class Satuan_barang extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_satuan_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Satuan Barangs
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('satuan_barang_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['satuan_barangs'] = $this->model_satuan_barang->get($filter, $field, $this->limit_page, $offset);
		$this->data['satuan_barang_counts'] = $this->model_satuan_barang->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/satuan_barang/index/',
			'total_rows'   => $this->data['satuan_barang_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Satuan Barang List');
		$this->render('backend/standart/administrator/satuan_barang/satuan_barang_list', $this->data);
	}
	
	/**
	* Add new satuan_barangs
	*
	*/
	public function add() {
		$this->is_allowed('satuan_barang_add');

		$this->template->title('Satuan Barang New');
		$this->render('backend/standart/administrator/satuan_barang/satuan_barang_add', $this->data);
	}

	/**
	* Add New Satuan Barangs
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('satuan_barang_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('satuan_nama', 'Nama Satuan', 'trim|required');

		$nama_satuan = $this->input->post('satuan_nama');

		$data_satuan = $this->db->where('LOWER(satuan_barang_nama)', $nama_satuan)->get('satuan_barang')->row();

		if ($this->form_validation->run()) {
			if (count($data_satuan) > 0) {
				$this->data['success'] = false;
				$this->data['message'] = 'Data sudah tersedia! <a href="'.site_url('administrator/satuan_barang/view/'.$data_satuan->satuan_barang_id).'">Lihat Data</a>';
			}else{
				$save_data = [
					'satuan_nama' 			=> $nama_satuan,
					'satuan_user_created' 	=> get_user_data('id'),
					'satuan_created_at' 	=> date('Y-m-d H:i:s'),
				];

				$save_satuan_barang = $this->model_satuan_barang->store($save_data);

				if ($save_satuan_barang) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $save_satuan_barang;
						$this->data['message'] = cclang('success_save_data_stay', [
							anchor('administrator/satuan_barang/edit/' . $save_satuan_barang, 'Edit Satuan Barang'),
							anchor('administrator/satuan_barang', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_save_data_redirect', [
							anchor('administrator/satuan_barang/edit/' . $save_satuan_barang, 'Edit Satuan Barang')
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/satuan_barang');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/satuan_barang');
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
	* Update view Satuan Barangs
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('satuan_barang_update');

		$this->data['satuan_barang'] = $this->model_satuan_barang->find($id);

		$this->template->title('Satuan Barang Update');
		$this->render('backend/standart/administrator/satuan_barang/satuan_barang_update', $this->data);
	}

	/**
	* Update Satuan Barangs
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('satuan_barang_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('satuan_nama', 'Nama Satuan', 'trim|required');

		$nama_satuan = $this->input->post('satuan_nama');

		$data_satuan = $this->db->where(['LOWER(satuan_barang_nama)' => $nama_satuan, 'satuan_barang_id !=' => $id])->get('satuan_barang')->row();

		if ($this->form_validation->run()) {
			if (count($data_satuan) > 0) {
				$this->data['success'] = false;
				$this->data['message'] = 'Data sudah tersedia!';
			}else{
				$save_data = [
					'satuan_nama' => $this->input->post('satuan_nama'),
				];
	
				$save_satuan_barang = $this->model_satuan_barang->change($id, $save_data);
	
				if ($save_satuan_barang) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $id;
						$this->data['message'] = cclang('success_update_data_stay', [
							anchor('administrator/satuan_barang', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_update_data_redirect', [
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/satuan_barang');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/satuan_barang');
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
	* delete Satuan Barangs
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('satuan_barang_delete');

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
            set_message(cclang('has_been_deleted', 'satuan_barang'), 'success');
        } else {
            set_message(cclang('error_delete', 'satuan_barang'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Satuan Barangs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('satuan_barang_view');

		$this->data['satuan_barang'] = $this->model_satuan_barang->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Satuan Barang Detail');
		$this->render('backend/standart/administrator/satuan_barang/satuan_barang_view', $this->data);
	}
	
	/**
	* delete Satuan Barangs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$satuan_barang = $this->model_satuan_barang->find($id);

		
		
		return $this->model_satuan_barang->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('satuan_barang_export');

		$this->model_satuan_barang->export(
			'satuan_barang', 
			'satuan_barang',
			$this->model_satuan_barang->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('satuan_barang_export');

		$this->model_satuan_barang->pdf('satuan_barang', 'satuan_barang');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('satuan_barang_export');

		$table = $title = 'satuan_barang';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_satuan_barang->find($id);
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

	
}


/* End of file satuan_barang.php */
/* Location: ./application/controllers/administrator/Satuan Barang.php */