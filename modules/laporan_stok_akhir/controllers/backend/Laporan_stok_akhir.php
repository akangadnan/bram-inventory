<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Laporan Stok Akhir Controller
*| --------------------------------------------------------------------------
*| Laporan Stok Akhir site
*|
*/
class Laporan_stok_akhir extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_laporan_stok_akhir');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	public function stok_keluar_masuk($get_kategori, $get_bidang, $get_periode, $get_bulan, $get_tahun, $get_tgl_awal, $get_tgl_akhir) {
		$tanggal_awal_bulan 	= '';
		$tanggal_akhir_bulan 	= '';

		if (strlen($get_bulan) > 1) {
			$bulan = $get_bulan;
		}else{
			$bulan = '0'.$get_bulan;
		}

		if (!empty($get_periode)) {
			if ($get_periode == 1) {
				$tanggal_awal_bulan 	= $get_tahun.'-'.$bulan.'-01';
				$tanggal_akhir_bulan 	= $get_tahun.'-'.$bulan.'-'.cal_days_in_month(CAL_GREGORIAN, $get_bulan, $get_tahun);
			}else if ($get_periode == 2) {
				$tanggal_awal_bulan 	= $get_tahun.'-01-01';
				$tanggal_akhir_bulan 	= $get_tahun.'-12-'.cal_days_in_month(CAL_GREGORIAN, '12', $get_tahun);
			}else if ($get_periode == 3) {
				$tanggal_awal_bulan 	= $get_tgl_awal;
				$tanggal_akhir_bulan 	= $get_tgl_akhir;
			}
		}

		$query_stok_awal 	= $this->model_laporan_stok_akhir->query_stok_awal($tanggal_awal_bulan, $tanggal_akhir_bulan, $get_kategori, $get_bidang)->result();
		$query_stok_akhir 	= $this->model_laporan_stok_akhir->query_stok_akhir($tanggal_awal_bulan, $tanggal_akhir_bulan, $get_kategori, $get_bidang)->result();

		foreach ($query_stok_awal as $item) {
			$stok_awal[$item->barang_id] = $item->stok_awal;
		}

		foreach ($query_stok_akhir as $item) {
			$stok_masuk[$item->barang_id] 	= $item->masuk;
			$stok_keluar[$item->barang_id] 	= $item->keluar;
		}

		$data = [
			'stok_awal' 	=> $stok_awal,
			'mutasi_masuk' 	=> $stok_masuk,
			'mutasi_keluar' => $stok_keluar,
		];

		return $data;
	}

	/**
	* show all Mutasi Masuks
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('laporan_stok_akhir_list');
		
		$get_kategori 	= $this->input->get('c');
		$get_bidang 	= $this->input->get('g');
		$get_periode 	= $this->input->get('p');
		$get_bulan 		= $this->input->get('m');
		$get_tahun 		= $this->input->get('y');
		$get_tgl_awal 	= $this->input->get('start');
		$get_tgl_akhir 	= $this->input->get('end');

		$stok_keluar_masuk = $this->stok_keluar_masuk($get_kategori, $get_bidang, $get_periode, $get_bulan, $get_tahun, $get_tgl_awal, $get_tgl_akhir);

		if (!empty($get_kategori)) {
			$conditions = ['barang_kategori_barang_id' => $get_kategori];
		}

		foreach (db_get_all_data('barang', $conditions) as $item) {
			$data[$item->barang_id] = [
				'nama_barang' 		=> $item->barang_nama,
				'kategori_barang' 	=> $item->barang_kategori_barang_id,
				'satuan_barang' 	=> $item->barang_satuan_id,
				'stok_awal' 		=> $stok_keluar_masuk['stok_awal'][$item->barang_id],
				'stok_masuk' 		=> $stok_keluar_masuk['mutasi_masuk'][$item->barang_id],
				'stok_keluar' 		=> $stok_keluar_masuk['mutasi_keluar'][$item->barang_id],
				'stok_akhir' 		=> ($stok_keluar_masuk['mutasi_masuk'][$item->barang_id] - $stok_keluar_masuk['mutasi_keluar'][$item->barang_id]),
			];
		}

		$this->data['laporan_stok_akhir'] 			= $data;
		$this->data['laporan_stok_akhir_counts'] 	= count($data);

		$config = [
			'base_url'     => 'administrator/laporan_stok_akhir/index/',
			'total_rows'   => $this->data['laporan_stok_akhir_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] 	= $this->pagination($config);

		$this->template->title('Laporan Stok Akhir List');
		$this->render('backend/standart/administrator/laporan_stok_akhir/laporan_stok_akhir_list', $this->data);
	}

	public function export_excel() {
		$this->is_allowed('laporan_stok_akhir_excel');
		
		$get_kategori 	= $this->input->get('c');
		$get_bidang 	= $this->input->get('g');
		$get_periode 	= $this->input->get('p');
		$get_bulan 		= $this->input->get('m');
		$get_tahun 		= $this->input->get('y');
		$get_tgl_awal 	= $this->input->get('start');
		$get_tgl_akhir 	= $this->input->get('end');

		$get_data = [
			'c' => $get_kategori,
			'g' => $get_bidang,
			'p' => $get_periode,
			'm' => $get_bulan,
			'y' => $get_tahun,
			'start' => $get_tgl_awal,
			'end' => $get_tgl_akhir,
		];

		$stok_keluar_masuk = $this->stok_keluar_masuk($get_kategori, $get_bidang, $get_periode, $get_bulan, $get_tahun, $get_tgl_awal, $get_tgl_akhir);

		if (!empty($get_kategori)) {
			$conditions = ['barang_kategori_barang_id' => $get_kategori];
		}

		$no = 1;
		foreach (db_get_all_data('barang', $conditions) as $item) {
			$data[$item->barang_id] = [
				'no' 				=> $no++,
				'nama_barang' 		=> $item->barang_nama,
				'kategori_barang' 	=> join_multi_select($item->barang_kategori_barang_id, 'kategori_barang', 'kategori_barang_id', 'kategori_barang_nama'),
				'uom' 				=> join_multi_select($item->barang_satuan_id, 'satuan_barang', 'satuan_id', 'satuan_nama'),
				'stok_akhir' 		=> ($stok_keluar_masuk['mutasi_masuk'][$item->barang_id] - $stok_keluar_masuk['mutasi_keluar'][$item->barang_id]),
			];
		}

		$fields = ['no', 'nama_barang', 'kategori_barang', 'uom', 'stok_akhir'];

		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);

		$alphabet = 'ABCDEFGHIJKLMOPQRSTUVWXYZ';
		$alphabet_arr = str_split($alphabet);
		$column = [];

		foreach ($alphabet_arr as $alpha) {
			$column[] =  $alpha;
		}

		foreach ($alphabet_arr as $alpha) {
			foreach ($alphabet_arr as $alpha2) {
				$column[] =  $alpha . $alpha2;
			}
		}

		foreach ($alphabet_arr as $alpha) {
			foreach ($alphabet_arr as $alpha2) {
				foreach ($alphabet_arr as $alpha3) {
					$column[] =  $alpha . $alpha2 . $alpha3;
				}
			}
		}

		foreach ($column as $col) {
			$this->excel->getActiveSheet()->getColumnDimension($col)->setWidth(20);
		}
		
		$col_total = $column[count($fields) - 1];

		$header = [
			'Laporan Stok Akhir Barang Persediaan',
			'Dinas Komunikasi, Informatika, Statistik dan Persandian',
			'Kota Semarang',
		];

		for ($i=0; $i < count($header); $i++) {
			$this->excel->getActiveSheet()->mergeCells('A'.($i+1).':'.$col_total.($i+1));
			$this->excel->getActiveSheet()->setCellValue('A'.($i+1), strtoupper($header[$i]));
			$this->excel->getActiveSheet()->getStyle('A'.($i+1))->getFont()->setBold(true);

			$this->excel->getActiveSheet()->getStyle('A'.($i+1).':' . $col_total.($i+1))->applyFromArray(
				array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				)
			);
		}

		$this->excel->getActiveSheet()->getStyle('A5:' . $col_total . '5')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->getRowDimension(5)->setRowHeight(20);

		$this->excel->getActiveSheet()->getStyle('A5:' . $col_total.'5')->applyFromArray(
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				)
			)
		);

		$col = 0;
		foreach ($fields as $field) {
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 5, strtoupper(str_replace('_', ' ', $field)));
			$col++;
		}

		$row 	= 6;
		foreach ($data as $item) {
			$col = 0;
			foreach ($fields as $field) {
				$this->excel->getActiveSheet()->setCellValueExplicit($column[$col] . $row, $item[$field], PHPExcel_Cell_DataType::TYPE_STRING);

				$col++;
			}

			$row++;
		}

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$this->excel->getActiveSheet()->getStyle('A5:' . $col_total.$row)->applyFromArray($styleArray);

		$this->excel->getActiveSheet()->setTitle(ucwords('Rekap Data Stok Akhir Barang'));

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . ucwords('Laporan Stok Akhir Barang') . '-' . date('Y-m-d') . '.xls');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');

		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter->save('php://output');
	}
}


/* End of file mutasi_masuk.php */
/* Location: ./application/controllers/administrator/Mutasi Masuk.php */