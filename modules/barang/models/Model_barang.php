<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_barang extends MY_Model {
	private $primary_key    = 'barang_id';
	private $table_name     = 'barang';
	public $field_search   = ['barang_kategori_barang_id', 'barang_nama', 'barang_satuan_id', 'kategori_barang.kategori_barang_nama', 'satuan_barang.satuan_nama'];
	public $sort_option = ['barang_id', 'DESC'];
	
	public function __construct()
	{
		$config = array(
			'primary_key'   => $this->primary_key,
			'table_name'    => $this->table_name,
			'field_search'  => $this->field_search,
			'sort_option'   => $this->sort_option,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null)
	{
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

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

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

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
		
		$this->db->select('kategori_barang.kategori_barang_nama,satuan_barang.satuan_nama,barang.*,kategori_barang.kategori_barang_nama as kategori_barang_kategori_barang_nama,kategori_barang.kategori_barang_nama as kategori_barang_nama,satuan_barang.satuan_nama as satuan_barang_satuan_nama,satuan_barang.satuan_nama as satuan_nama');


		return $this;
	}

	public function filter_avaiable() {
		if (!$this->aauth->is_admin()) {
			// $this->db->where($this->table_name.'.barang_user_created', get_user_data('id'));
		}

		return $this;
	}

}

/* End of file Model_barang.php */
/* Location: ./application/models/Model_barang.php */