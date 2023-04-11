<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_mutasi_keluar extends MY_Model {

    private $primary_key    = 'mutasi_keluar_id';
    private $table_name     = 'mutasi_keluar';
    public $field_search   = ['mutasi_keluar_tgl_keluar', 'mutasi_keluar_bidang_id', 'mutasi_keluar_status', 'bidang.bidang_nama'];
    public $sort_option = ['mutasi_keluar_id', 'DESC'];
    
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
                $f_search = "mutasi_keluar.".$field;

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
            $where .= "(" . "mutasi_keluar.".$field . " LIKE '%" . $q . "%' )";
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
                $f_search = "mutasi_keluar.".$field;
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
            $where .= "(" . "mutasi_keluar.".$field . " LIKE '%" . $q . "%' )";
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
        $this->db->join('bidang', 'bidang.bidang_id = mutasi_keluar.mutasi_keluar_bidang_id', 'LEFT');
        
        $this->db->select('bidang.bidang_nama,mutasi_keluar.*,bidang.bidang_nama as bidang_bidang_nama,bidang.bidang_nama as bidang_nama');


        return $this;
    }

    public function filter_avaiable() {
        if (!$this->aauth->is_admin()) {
            // $this->db->where($this->table_name.'.mutasi_keluar_user_created', get_user_data('id'));
        }

        return $this;
    }

    public function query_item_mutasi_keluar($id) {
        $this->db->join('barang', 'barang.barang_id = mutasi_keluar_detail.mukeldet_barang_id', 'LEFT');
        $this->db->where('mutasi_keluar_detail.mukeldet_keluar_id', $id);

        $query = $this->db->get('mutasi_keluar_detail');

        return $query;
    }

}

/* End of file Model_mutasi_keluar.php */
/* Location: ./application/models/Model_mutasi_keluar.php */