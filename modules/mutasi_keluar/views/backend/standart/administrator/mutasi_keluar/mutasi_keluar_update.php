<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
	function domo() {
		// Binding keys
		$('*').bind('keydown', 'Ctrl+s', function assets() {
			$('#btn_save').trigger('click');
			return false;
		});

		$('*').bind('keydown', 'Ctrl+x', function assets() {
			$('#btn_cancel').trigger('click');
			return false;
		});

		$('*').bind('keydown', 'Ctrl+d', function assets() {
			$('.btn_save_back').trigger('click');
			return false;
		});
	}

	jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Mutasi Keluar <small>Edit Mutasi Keluar</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/mutasi_keluar'); ?>">Mutasi Keluar</a></li>
		<li class="active">Edit</li>
	</ol>
</section>

<style></style>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body">
					<!-- Widget: user widget style 1 -->
					<div class="box box-widget widget-user-2">
						<!-- Add the bg color to the header using any of the bg-* classes -->
						<div class="widget-user-header">
							<div class="widget-user-image">
								<img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
							</div>
							<!-- /.widget-user-image -->
							<h3 class="widget-user-username">Mutasi Keluar</h3>
							<h5 class="widget-user-desc">Edit Mutasi Keluar</h5>
							<hr>
						</div>
						<?= form_open(base_url('administrator/mutasi_keluar/edit_save/'.$this->uri->segment(4)), [
								'name' 		=> 'form_mutasi_keluar',
								'class' 	=> 'form-horizontal form-step',
								'id' 		=> 'form_mutasi_keluar',
								'method' 	=> 'POST'
							]);

							$user_groups = $this->model_group->get_user_group_ids();
						?>

						<div class="form-group group-mutasi_keluar_tgl_keluar">
							<label for="mutasi_keluar_tgl_keluar" class="col-sm-2 control-label">Tanggal Keluar <i class="required">*</i></label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right datepicker" name="mutasi_keluar_tgl_keluar" placeholder="" id="mutasi_keluar_tgl_keluar" value="<?= set_value('mutasi_keluar_mutasi_keluar_tgl_keluar_name', $mutasi_keluar->mutasi_keluar_tgl_keluar); ?>">
								</div>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-mutasi_keluar_bidang_id">
							<label for="mutasi_keluar_bidang_id" class="col-sm-2 control-label">Bidang <i class="required">*</i></label>
							<div class="col-sm-8">
					<?php
						if ($this->aauth->is_member(4)) {
					?>
							<label class="form-control"><?= join_multi_select($this->session->userdata('id_bidang'), 'bidang', 'bidang_id', 'bidang_nama').' ('.join_multi_select($this->session->userdata('id_bidang'), 'bidang', 'bidang_id', 'bidang_subyek').')';?></label>
					<?php
						}else{
					?>
								<select class="form-control chosen chosen-select-deselect" name="mutasi_keluar_bidang_id" id="mutasi_keluar_bidang_id" data-placeholder="Select Bidang">
									<option value=""></option>
									<?php foreach (db_get_all_data('bidang', $conditions) as $row): ?>
									<option
										<?= $row->bidang_id == $mutasi_keluar->mutasi_keluar_bidang_id ? 'selected' : ''; ?> value="<?= $row->bidang_id ?>"><?= $row->bidang_nama; ?></option>
									<?php endforeach; ?>
								</select>
					<?php
						}
					?>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group">
							<label for="mutasi_keluar_status" class="col-sm-2 control-label">Status <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select" name="mutasi_keluar_status"
									id="mutasi_keluar_status" data-placeholder="Select Status">
									<option value=""></option>
									<option <?= $mutasi_keluar->mutasi_keluar_status == "1" ? 'selected' :''; ?> value="1">Dijual</option>
									<option <?= $mutasi_keluar->mutasi_keluar_status == "2" ? 'selected' :''; ?> value="2">Mutasi</option>
									<option <?= $mutasi_keluar->mutasi_keluar_status == "3" ? 'selected' :''; ?> value="3">Stok Opname</option>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-mutasi_keluar_keterangan">
							<label for="mutasi_keluar_keterangan" class="col-sm-2 control-label">Keterangan </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="mutasi_keluar_keterangan" id="mutasi_keluar_keterangan" placeholder="" value="<?= set_value('mutasi_keluar_keterangan', $mutasi_keluar->mutasi_keluar_keterangan); ?>">
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="box box-primary">
									<div class="box-header">
										<h3 class="box-title">Daftar Item</h3>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-md-12">
												<a href="javascript:void(0);" id="addRow" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Item</a>
											</div>
										</div>
										<div class="row">
											<table class="table table-striped" id="tableMutasiMasuk">
												<thead>
													<tr>
														<th>Nama Item</th>
														<th>Jumlah Item</th>
														<th>Keterangan Item</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
											<?php
												$details = db_get_all_data('mutasi_keluar_detail', ['mukeldet_keluar_id' => $mutasi_keluar->mutasi_keluar_id]);
											?>
													<tr id="inputFormRow">
														<td>
															<select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang0" data-placeholder="Pilih Nama Item">
																<option value=""></option>
														<?php foreach(db_get_all_data('barang') as $row) {?>
																<option value="<?= $row->barang_id;?>" <?= $details[0]->mukeldet_barang_id == $row->barang_id ? 'selected="selected"' : '';?>><?= $row->barang_nama; ?> (<?= join_multi_select($row->barang_satuan_id, 'satuan_barang', 'satuan_id', 'satuan_nama'); ?>) </option>
														<?php }; ?>
															</select>
														</td>
														<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Item" value="<?= $details[0]->mukeldet_jumlah;?>"></td>
														<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Item" value="<?= $details[0]->mukeldet_keterangan;?>"></td>
														<td>&nbsp;</td>
													</tr>
											<?php
												for ($i=1; $i < count($details); $i++) {
											?>
													<tr id="inputFormRow">
														<td>
															<select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang0" data-placeholder="Pilih Nama Item">
																<option value=""></option>
														<?php foreach(db_get_all_data('barang') as $row) {?>
																<option value="<?= $row->barang_id;?>" <?= $details[$i]->mukeldet_barang_id == $row->barang_id ? 'selected="selected"' : '';?>><?= $row->barang_nama; ?> (<?= join_multi_select($row->barang_satuan_id, 'satuan_barang', 'satuan_id', 'satuan_nama'); ?>) </option>
														<?php }; ?>
															</select>
														</td>
														<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Item" value="<?= $details[$i]->mukeldet_jumlah;?>"></td>
														<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Item" value="<?= $details[$i]->mukeldet_keterangan;?>"></td>
														<td><a href="javascript:void(0);" id="removeRow" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>
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
						</div>

						<div class="message"></div>

						<div class="row-fluid col-md-7 container-button-bottom">
							<button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="<?= cclang('save_button'); ?> (Ctrl+s)">
								<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
							</button>
							<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
								<i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?>
							</a>

							<div class="custom-button-wrapper"></div>

							<a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
								<i class="fa fa-undo"></i> <?= cclang('cancel_button'); ?>
							</a>
							<span class="loading loading-hide">
								<img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">
								<i><?= cclang('loading_saving_data'); ?></i>
							</span>
						</div>
						<?= form_close(); ?>
					</div>
				</div>
				<!--/box body -->
			</div>
			<!--/box -->
		</div>
	</div>
</section>
<!-- /.content -->
<!-- Page script -->
<script>
	$(document).ready(function () {
		window.event_submit_and_action = '';

		$("#addRow").on('click', function () {
			var html = '';
			html += '<tr id="inputFormRow">';
			html +=
				'<td><select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang[]" data-placeholder="Pilih Nama Item"><option value="">- Pilih Nama Item -</option>';
		<?php foreach(db_get_all_data('barang') as $row) {?>
				html += '<option value="<?= $row->barang_id ?>"><?= $row->barang_nama; ?> ( <?= join_multi_select($row->barang_satuan_id, 'satuan_barang', 'satuan_id', 'satuan_nama'); ?>) </option>';
		<?php }; ?>
			html += '</select><small class="info help-block"></small></td>';
			html += '<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Stok"><small class="info help-block"></small></td>';
			html += '<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Item"></td>';
			html += '<td><a href="javascript:void(0);" id="removeRow" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>';
			html += '</tr>';

			$('#tableMutasiMasuk tr:last').after(html);
			$("#tableMutasiMasuk").find(".chosen").last();
		});

		$(document).on('click', '#removeRow', function () {
			$(this).closest('#inputFormRow').remove();
		});

		$('#btn_cancel').click(function () {
			swal({
				title: "Are you sure?",
				text: "the data that you have created will be in the exhaust!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes!",
				cancelButtonText: "No!",
				closeOnConfirm: true,
				closeOnCancel: true
			},
			function (isConfirm) {
				if (isConfirm) {
					window.location.href = BASE_URL + 'administrator/mutasi_keluar';
				}
			});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_mutasi_keluar = $('#form_mutasi_keluar');
			var data_post = form_mutasi_keluar.serializeArray();
			var save_type = $(this).attr('data-stype');
			data_post.push({
				name: 'save_type',
				value: save_type
			});

			data_post.push({
				name: 'event_submit_and_action',
				value: window.event_submit_and_action
			});

			$('.loading').show();

			$.ajax({
				url: form_mutasi_keluar.attr('action'),
				type: 'POST',
				dataType: 'json',
				data: data_post,
			})
			.done(function (res) {
				$('form').find('.form-group').removeClass('has-error');
				$('form').find('.error-input').remove();
				$('.steps li').removeClass('error');
				if (res.success) {
					var id = $('#mutasi_keluar_image_galery').find('li').attr('qq-file-id');
					if (save_type == 'back') {
						window.location.href = res.redirect;
						return;
					}

					$('.message').printMessage({
						message: res.message
					});
					$('.message').fadeIn();
					$('.data_file_uuid').val('');
				} else {
					if (res.errors) {
						parseErrorField(res.errors);
					}
					$('.message').printMessage({
						message: res.message,
						type: 'warning'
					});
				}
			})
			.fail(function () {
				$('.message').printMessage({
					message: 'Error save data',
					type: 'warning'
				});
			})
			.always(function () {
				$('.loading').hide();
				$('html, body').animate({
					scrollTop: $(document).height()
				}, 2000);
			});

			return false;
		}); /*end btn save*/

		async function chain() {}

		chain();

	}); /*end doc ready*/
</script>