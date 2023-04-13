<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Kategori Barang Controller
*| --------------------------------------------------------------------------
*| Kategori Barang site
*|
*/
class Kategori_barang extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_kategori_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Kategori Barangs
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('kategori_barang_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['kategori_barangs'] = $this->model_kategori_barang->get($filter, $field, $this->limit_page, $offset);
		$this->data['kategori_barang_counts'] = $this->model_kategori_barang->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/kategori_barang/index/',
			'total_rows'   => $this->data['kategori_barang_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Kategori Barang List');
		$this->render('backend/standart/administrator/kategori_barang/kategori_barang_list', $this->data);
	}
	
	/**
	* Add new kategori_barangs
	*
	*/
	public function add() {
		$this->is_allowed('kategori_barang_add');

		$this->template->title('Kategori Barang New');
		$this->render('backend/standart/administrator/kategori_barang/kategori_barang_add', $this->data);
	}

	/**
	* Add New Kategori Barangs
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('kategori_barang_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('kategori_barang_nama', 'Nama Kategori', 'trim|required');

		$nama_kategori = $this->input->post('kategori_barang_nama');

		$data_kategori = $this->db->where("LOWER(kategori_barang_nama)", strtolower($nama_kategori))->get('kategori_barang')->row();

		if ($this->form_validation->run()) {
			if (count($data_kategori) > 0) {
				$this->data['success'] = false;
				$this->data['message'] = 'Data sudah tersedia! <a href="'.site_url('administrator/kategori_barang/view/'.$data_kategori->kategori_barang_id).'">Lihat Data</a>';
			}else{
				$save_data = [
					'kategori_barang_nama' 			=> $nama_kategori,
					'kategori_barang_user_created' 	=> get_user_data('id'),
					'kategori_barang_created_at' 	=> date('Y-m-d H:i:s'),
				];
	
				$save_kategori_barang = $this->model_kategori_barang->store($save_data);
	
				if ($save_kategori_barang) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $save_kategori_barang;
						$this->data['message'] = cclang('success_save_data_stay', [
							anchor('administrator/kategori_barang/edit/' . $save_kategori_barang, 'Edit Kategori Barang'),
							anchor('administrator/kategori_barang', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_save_data_redirect', [
							anchor('administrator/kategori_barang/edit/' . $save_kategori_barang, 'Edit Kategori Barang')
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/kategori_barang');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/kategori_barang');
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
	* Update view Kategori Barangs
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('kategori_barang_update');

		$this->data['kategori_barang'] = $this->model_kategori_barang->find($id);

		$this->template->title('Kategori Barang Update');
		$this->render('backend/standart/administrator/kategori_barang/kategori_barang_update', $this->data);
	}

	/**
	* Update Kategori Barangs
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('kategori_barang_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('kategori_barang_nama', 'Nama Kategori', 'trim|required');

		$nama_kategori = $this->input->post('kategori_barang_nama');

		$data_kategori = $this->db->where(['LOWER(kategori_barang_nama)' => strtolower($nama_kategori), 'kategori_barang_id !=' => $id])->get('kategori_barang')->row();
		
		if ($this->form_validation->run()) {
			if (count($data_kategori) > 0) {
				$this->data['success'] = false;
				$this->data['message'] = 'Data sudah tersedia!';
			}else{
				$save_data = [
					'kategori_barang_nama' => $nama_kategori,
				];
				
				$save_kategori_barang = $this->model_kategori_barang->change($id, $save_data);
	
				if ($save_kategori_barang) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $id;
						$this->data['message'] = cclang('success_update_data_stay', [
							anchor('administrator/kategori_barang', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_update_data_redirect', [
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/kategori_barang');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/kategori_barang');
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
	* delete Kategori Barangs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('kategori_barang_delete');

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
            set_message(cclang('has_been_deleted', 'kategori_barang'), 'success');
        } else {
            set_message(cclang('error_delete', 'kategori_barang'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Kategori Barangs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('kategori_barang_view');

		$this->data['kategori_barang'] = $this->model_kategori_barang->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Kategori Barang Detail');
		$this->render('backend/standart/administrator/kategori_barang/kategori_barang_view', $this->data);
	}
	
	/**
	* delete Kategori Barangs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$kategori_barang = $this->model_kategori_barang->find($id);

		
		
		return $this->model_kategori_barang->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('kategori_barang_export');

		$this->model_kategori_barang->export(
			'kategori_barang', 
			'kategori_barang',
			$this->model_kategori_barang->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('kategori_barang_export');

		$this->model_kategori_barang->pdf('kategori_barang', 'kategori_barang');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('kategori_barang_export');

		$table = $title = 'kategori_barang';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_kategori_barang->find($id);
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


/* End of file kategori_barang.php */
/* Location: ./application/controllers/administrator/Kategori Barang.php */