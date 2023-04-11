<section class="content-header">
	<h1>
		Laporan Stok Akhir<small><?= cclang('list_all'); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Laporan Stok Akhir</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<form name="form_laporan_stok_akhir" id="form_laporan_stok_akhir" action="<?= base_url('administrator/laporan_stok_akhir/index'); ?>">
				<div class="box box-primary">
					<div class="box-body">
						<div class="box box-widget widget-user-2">
							<div class="widget-user-header">
							<div class="row pull-right">
								<?php is_allowed('laporan_stok_akhir_excel', function(){?>
								<a class="btn btn-flat btn-success" target="_blank" title="Export to PDF" href="<?= site_url('administrator/laporan_stok_akhir/export_excel?c='.$this->input->get('c').'&g='.$this->input->get('g').'&p='.$this->input->get('p').'&m='.$this->input->get('m').'&y='.$this->input->get('y').'&start='.$this->input->get('start').'&end='.$this->input->get('end')); ?>">
									<i class="fa fa-file-excel-o"></i> Export Excel</a>
								<?php }) ?>
							</div>
								<div class="widget-user-image">
									<img class="img-circle" src="<?= BASE_ASSET; ?>/img/list.png" alt="User Avatar">
								</div>
								<h3 class="widget-user-username">Laporan Stok Akhir</h3>
								<h5 class="widget-user-desc">Semua data laporan stok akhir
									<i class="label bg-yellow"><?= $laporan_stok_akhir_counts; ?> <?= cclang('items'); ?></i>
								</h5>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-2 padd-left-0">
										<select type="text" class="form-control chosen chosen-select" name="c" id="c" placeholder="Pilih Kategori">
											<option value="">Semua Kategori</option>
									<?php
										foreach (db_get_all_data('kategori_barang') as $item) {
									?>
											<option <?= $this->input->get('c') == $item->kategori_barang_id ? 'selected' : '';?> value="<?= $item->kategori_barang_id;?>"><?= $item->kategori_barang_nama;?></option>
									<?php
										}
									?>
										</select>
									</div>
									<div class="col-md-2 padd-left-0">
										<select type="text" class="form-control chosen chosen-select" name="g" id="g" data-placeholder="Pilih Bidang">
											<option value="">Semua Bidang</option>
									<?php
										foreach (db_get_all_data('bidang') as $item) {
									?>
											<option <?= $this->input->get('g') == $item->bidang_id ? 'selected' : '';?> value="<?= $item->bidang_id;?>"><?= $item->bidang_nama;?></option>
									<?php
										}
									?>
										</select>
									</div>
									<div class="col-md-2 padd-left-0">
										<select type="text" class="form-control chosen chosen-select" name="p" id="p" data-placeholder="Pilih Periode">
											<option value="">Semua Periode</option>
											<option <?= $this->input->get('p') == '1' ? 'selected' : '';?> value="1">Bulanan</option>
											<option <?= $this->input->get('p') == '2' ? 'selected' : '';?> value="2">Tahunan</option>
											<option <?= $this->input->get('p') == '3' ? 'selected' : '';?> value="3">Periode Tertentu</option>
										</select>
									</div>
									<div class="col-md-1 padd-left-0">
										<select type="text" class="form-control chosen chosen-select" name="m" id="m" data-placeholder="Pilih Bulan">
											<option value=""></option>
									<?php
										foreach (nama_bulan() as $key => $value) {
									?>
											<option <?= $this->input->get('m') == $key ? 'selected' : '';?> value="<?= $key;?>"><?= $value;?></option>
									<?php
										}
									?>
										</select>
									</div>
									<div class="col-md-1 padd-left-0">
										<select type="text" class="form-control chosen chosen-select" name="y" id="y" data-placeholder="Pilih Tahun">
											<option value=""></option>
									<?php
										foreach (array_combine(range(date("Y"), 2023), range(date("Y"), 2023)) as $key => $value) {
									?>
											<option <?= $this->input->get('y') == $key ? 'selected' : '';?> value="<?= $key;?>"><?= $value;?></option>
									<?php
										}
									?>
										</select>
									</div>
									<div class="col-md-1 padd-left-0">
										<div class="input-group date col-sm-12">
											<input type="text" class="form-control pull-right datepicker" name="start" id="start" placeholder="Tanggal Awal" value="<?= $this->input->get('start');?>">
										</div>
									</div>
									<div class="col-md-1 padd-left-0">
										<div class="input-group date col-sm-12">
											<input type="text" class="form-control pull-right datepicker" name="end" id="end" placeholder="Tanggal Akhir" value="<?= $this->input->get('end');?>">
										</div>
									</div>
									<div class="col-sm-1 padd-left-0">
										<button type="submit" class="btn btn-flat btn-primary" name="sbtn" id="sbtn" value="Apply" title="Tombol Pencarian">
											<i class="fa fa-search"></i> Cari ...
										</button>
									</div>
									<div class="col-sm-1 padd-left-0">
										<a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= base_url('administrator/laporan_stok_akhir');?>" title="<?= cclang('reset_filter'); ?>">
											<i class="fa fa-undo"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate">
										<?= $pagination; ?>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<br>
								<table class="table table-bordered table-striped dataTable">
									<thead>
										<tr class="">
											<th>No.</th>
											<th>Nama Barang</th>
											<th>Kategori Barang</th>
											<th>UOM</th>
											<th>Stok Akhir</th>
										</tr>
									</thead>
									<tbody id="tbody_laporan_stok_akhir">
								<?php
									$no = 1;

									$get_kategori = $this->input->get('c');

									if (!empty($get_kategori)) {
										$conditions = ['barang_kategori_barang_id' => $get_kategori];
									}

									foreach(db_get_all_data('barang', $conditions) as $item) {
								?>
										<tr>
											<td><?= $no++;?></td>
											<td><?= $laporan_stok_akhir[$item->barang_id]['nama_barang'];?></td>
											<td><?= join_multi_select($laporan_stok_akhir[$item->barang_id]['kategori_barang'], 'kategori_barang', 'kategori_barang_id', 'kategori_barang_nama');?></td>
											<td><?= join_multi_select($laporan_stok_akhir[$item->barang_id]['satuan_barang'], 'Satuan_barang', 'satuan_id', 'satuan_nama')?></td>
											<td><?= !empty($laporan_stok_akhir[$item->barang_id]['stok_akhir']) ? $laporan_stok_akhir[$item->barang_id]['stok_akhir'] : 0?></td>
										</tr>
								<?php
									}

									if ($laporan_stok_akhir_counts == 0) {
								?>
									<tr>
										<td colspan="100">
											Laporan Mutasi data is not available
										</td>
									</tr>
								<?php 
									}
								?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<!-- /.content -->

<!-- Page script -->

<script type="text/javascript">
	$(document).ready(function () {
		var p = '<?= $this->input->get('p')?>';

		if (p === '') {
			$('#m').prop('disabled', true).trigger("chosen:updated");
			$('#y').prop('disabled', true).trigger("chosen:updated");

			document.getElementById('start').setAttribute('disabled', 'disabled');
			document.getElementById('end').setAttribute('disabled', 'disabled');
		}else if (p === '1') {
			$('#m').prop('disabled', false).trigger("chosen:updated");
			$('#y').prop('disabled', false).trigger("chosen:updated");

			document.getElementById('start').setAttribute('disabled', 'disabled');
			document.getElementById('end').setAttribute('disabled', 'disabled');
		}else if (p === '2') {
			$('#m').prop('disabled', true).trigger("chosen:updated");
			$('#y').prop('disabled', false).trigger("chosen:updated");

			document.getElementById('start').setAttribute('disabled', 'disabled');
			document.getElementById('end').setAttribute('disabled', 'disabled');
		}else if (p === '2') {
			$('#m').prop('disabled', true).trigger("chosen:updated");
			$('#y').prop('disabled', true).trigger("chosen:updated");

			document.getElementById('start').removeAttribute('disabled', 'disabled');
			document.getElementById('end').removeAttribute('disabled', 'disabled');
		}

		$('#p').change(function() {
			var val = $(this).val();

			if (val == '') {
				$('#m').prop('disabled', true).trigger("chosen:updated");
				$('#y').prop('disabled', true).trigger("chosen:updated");
				document.getElementById('start').setAttribute('disabled', 'disabled');
				document.getElementById('end').setAttribute('disabled', 'disabled');
			}else if (val == '1') {
				$('#m').prop('disabled', false).trigger("chosen:updated");
				$('#y').prop('disabled', false).trigger("chosen:updated");
				document.getElementById('start').setAttribute('disabled', 'disabled');
				document.getElementById('end').setAttribute('disabled', 'disabled');
			}else if (val == '2') {
				$('#m').prop('disabled', true).trigger("chosen:updated");
				$('#y').prop('disabled', false).trigger("chosen:updated");
				document.getElementById('start').setAttribute('disabled', 'disabled');
				document.getElementById('end').setAttribute('disabled', 'disabled');
			}else if (val == '3') {
				$('#m').prop('disabled', true).trigger("chosen:updated");
				$('#y').prop('disabled', true).trigger("chosen:updated");
				document.getElementById('start').removeAttribute('disabled', 'disabled');
				document.getElementById('end').removeAttribute('disabled', 'disabled');
			}
		});
	}); /*end doc ready*/
</script>