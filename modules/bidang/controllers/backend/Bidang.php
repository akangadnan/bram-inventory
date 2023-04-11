<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Bidang Controller
*| --------------------------------------------------------------------------
*| Bidang site
*|
*/
class Bidang extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_bidang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Bidangs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('bidang_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['bidangs'] = $this->model_bidang->get($filter, $field, $this->limit_page, $offset);
		$this->data['bidang_counts'] = $this->model_bidang->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/bidang/index/',
			'total_rows'   => $this->data['bidang_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Bidang List');
		$this->render('backend/standart/administrator/bidang/bidang_list', $this->data);
	}
	
	/**
	* Add new bidangs
	*
	*/
	public function add()
	{
		$this->is_allowed('bidang_add');

		$this->template->title('Bidang New');
		$this->render('backend/standart/administrator/bidang/bidang_add', $this->data);
	}

	/**
	* Add New Bidangs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('bidang_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		

		$this->form_validation->set_rules('bidang_nama', 'Nama Bidang', 'trim|required');
		

		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'bidang_nama' => $this->input->post('bidang_nama'),
				'bidang_subyek' => $this->input->post('bidang_subyek'),
				'bidang_user_created' => get_user_data('id'),				'bidang_created_at' => date('Y-m-d H:i:s'),
			];

			
			
//$save_data['_example'] = $this->input->post('_example');
			



			
			
			$save_bidang = $id = $this->model_bidang->store($save_data);
            

			if ($save_bidang) {
				
				$id = $save_bidang;
				
				
					
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_bidang;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/bidang/edit/' . $save_bidang, 'Edit Bidang'),
						anchor('administrator/bidang', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/bidang/edit/' . $save_bidang, 'Edit Bidang')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/bidang');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/bidang');
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
	* Update view Bidangs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('bidang_update');

		$this->data['bidang'] = $this->model_bidang->find($id);

		$this->template->title('Bidang Update');
		$this->render('backend/standart/administrator/bidang/bidang_update', $this->data);
	}

	/**
	* Update Bidangs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('bidang_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
				$this->form_validation->set_rules('bidang_nama', 'Nama Bidang', 'trim|required');
		

		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'bidang_nama' => $this->input->post('bidang_nama'),
				'bidang_subyek' => $this->input->post('bidang_subyek'),
			];

			

			
//$save_data['_example'] = $this->input->post('_example');
			


			
			
			$save_bidang = $this->model_bidang->change($id, $save_data);

			if ($save_bidang) {

				
				

				
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/bidang', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/bidang');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/bidang');
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
	* delete Bidangs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('bidang_delete');

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
            set_message(cclang('has_been_deleted', 'bidang'), 'success');
        } else {
            set_message(cclang('error_delete', 'bidang'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Bidangs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('bidang_view');

		$this->data['bidang'] = $this->model_bidang->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Bidang Detail');
		$this->render('backend/standart/administrator/bidang/bidang_view', $this->data);
	}
	
	/**
	* delete Bidangs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$bidang = $this->model_bidang->find($id);

		
		
		return $this->model_bidang->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('bidang_export');

		$this->model_bidang->export(
			'bidang', 
			'bidang',
			$this->model_bidang->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('bidang_export');

		$this->model_bidang->pdf('bidang', 'bidang');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('bidang_export');

		$table = $title = 'bidang';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_bidang->find($id);
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


/* End of file bidang.php */
/* Location: ./application/controllers/administrator/Bidang.php */