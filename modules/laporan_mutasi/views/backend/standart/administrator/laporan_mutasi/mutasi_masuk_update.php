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
		Mutasi Masuk <small>Edit Mutasi Masuk</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/mutasi_masuk'); ?>">Mutasi Masuk</a></li>
		<li class="active">Edit</li>
	</ol>
</section>

<style>
	/* .group-mutasi_masuk_bidang_id */
	.group-mutasi_masuk_bidang_id {}

	.group-mutasi_masuk_bidang_id .control-label {}

	.group-mutasi_masuk_bidang_id .col-sm-8 {}

	.group-mutasi_masuk_bidang_id .form-control {}

	.group-mutasi_masuk_bidang_id .help-block {}

	/* end .group-mutasi_masuk_bidang_id */



	/* .group-mutasi_masuk_status */
	.group-mutasi_masuk_status {}

	.group-mutasi_masuk_status .control-label {}

	.group-mutasi_masuk_status .col-sm-8 {}

	.group-mutasi_masuk_status .form-control {}

	.group-mutasi_masuk_status .help-block {}

	/* end .group-mutasi_masuk_status */
</style>
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
							<h3 class="widget-user-username">Mutasi Masuk</h3>
							<h5 class="widget-user-desc">Edit Mutasi Masuk</h5>
							<hr>
						</div>
						<?= form_open(base_url('administrator/mutasi_masuk/edit_save/'.$this->uri->segment(4)), [
								'name' 		=> 'form_mutasi_masuk',
								'class' 	=> 'form-horizontal form-step',
								'id' 		=> 'form_mutasi_masuk',
								'method' 	=> 'POST'
							]);
							
							$user_groups = $this->model_group->get_user_group_ids();
						?>



						<div class="form-group group-mutasi_masuk_tgl_masuk">
							<label for="mutasi_masuk_tgl_masuk" class="col-sm-2 control-label">Tanggal Masuk <i
									class="required">*</i>
							</label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right datepicker"
										name="mutasi_masuk_tgl_masuk" placeholder="" id="mutasi_masuk_tgl_masuk"
										value="<?= set_value('mutasi_masuk_mutasi_masuk_tgl_masuk_name', $mutasi_masuk->mutasi_masuk_tgl_masuk); ?>">
								</div>
								<small class="info help-block">
								</small>
							</div>
						</div>





						<div class="form-group group-mutasi_masuk_bidang_id">
							<label for="mutasi_masuk_bidang_id" class="col-sm-2 control-label">Bidang <i
									class="required">*</i>
							</label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="mutasi_masuk_bidang_id"
									id="mutasi_masuk_bidang_id" data-placeholder="Select Bidang">
									<option value=""></option>
									<?php
										$conditions = [
											];
										?>
									<?php foreach (db_get_all_data('bidang', $conditions) as $row): ?>
									<option
										<?= $row->bidang_id == $mutasi_masuk->mutasi_masuk_bidang_id ? 'selected' : ''; ?>
										value="<?= $row->bidang_id ?>"><?= $row->bidang_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div>






						<div class="form-group">
							<label for="mutasi_masuk_status" class="col-sm-2 control-label">Status <i
									class="required">*</i>
							</label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select" name="mutasi_masuk_status"
									id="mutasi_masuk_status" data-placeholder="Select Status">
									<option value=""></option>
									<option <?= $mutasi_masuk->mutasi_masuk_status == "1" ? 'selected' :''; ?>
										value="1">Pembelian</option>
									<option <?= $mutasi_masuk->mutasi_masuk_status == "2" ? 'selected' :''; ?>
										value="2">Mutasi</option>
									<option <?= $mutasi_masuk->mutasi_masuk_status == "3" ? 'selected' :''; ?>
										value="3">Stok Opname</option>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div>




						<div class="form-group group-mutasi_masuk_keterangan">
							<label for="mutasi_masuk_keterangan" class="col-sm-2 control-label">Keterangan </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="mutasi_masuk_keterangan"
									id="mutasi_masuk_keterangan" placeholder=""
									value="<?= set_value('mutasi_masuk_keterangan', $mutasi_masuk->mutasi_masuk_keterangan); ?>">
								<small class="info help-block">
								</small>
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
												$details = db_get_all_data('mutasi_masuk_detail', ['mumadet_masuk_id' => $mutasi_masuk->mutasi_masuk_id]);
											?>
													<tr id="inputFormRow">
														<td>
															<select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang0" data-placeholder="Pilih Nama Item">
																<option value=""></option>
														<?php foreach(db_get_all_data('barang') as $row) {?>
																<option value="<?= $row->barang_id;?>" <?= $details[0]->mumadet_barang_id == $row->barang_id ? 'selected="selected"' : '';?>><?= $row->barang_nama; ?> (<?= join_multi_select($row->barang_satuan_id, 'satuan_barang', 'satuan_id', 'satuan_nama'); ?>) </option>
														<?php }; ?>
															</select>
														</td>
														<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Item" value="<?= $details[0]->mumadet_jumlah;?>"></td>
														<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Item" value="<?= $details[0]->mumadet_keterangan;?>"></td>
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
																<option value="<?= $row->barang_id;?>" <?= $details[$i]->mumadet_barang_id == $row->barang_id ? 'selected="selected"' : '';?>><?= $row->barang_nama; ?> (<?= join_multi_select($row->barang_satuan_id, 'satuan_barang', 'satuan_id', 'satuan_nama'); ?>) </option>
														<?php }; ?>
															</select>
														</td>
														<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Item" value="<?= $details[$i]->mumadet_jumlah;?>"></td>
														<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Item" value="<?= $details[$i]->mumadet_keterangan;?>"></td>
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
							<button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay'
								title="<?= cclang('save_button'); ?> (Ctrl+s)">
								<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
							</button>
							<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save"
								data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
								<i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?>
							</a>

							<div class="custom-button-wrapper">

							</div>
							<a class="btn btn-flat btn-default btn_action" id="btn_cancel"
								title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
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

		(function () {
			var mutasi_masuk_bidang_id = $('#mutasi_masuk_bidang_id');
			/* 
	mutasi_masuk_bidang_id.on('change', function() {});
	*/
			var mutasi_masuk_status = $('#mutasi_masuk_status');

		})()

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
						window.location.href = BASE_URL + 'administrator/mutasi_masuk';
					}
				});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_mutasi_masuk = $('#form_mutasi_masuk');
			var data_post = form_mutasi_masuk.serializeArray();
			var save_type = $(this).attr('data-stype');
			data_post.push({
				name: 'save_type',
				value: save_type
			});

			(function () {
				data_post.push({
					name: '_example',
					value: 'value_of_example',
				})
			})()


			data_post.push({
				name: 'event_submit_and_action',
				value: window.event_submit_and_action
			});

			$('.loading').show();

			$.ajax({
					url: form_mutasi_masuk.attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data_post,
				})
				.done(function (res) {
					$('form').find('.form-group').removeClass('has-error');
					$('form').find('.error-input').remove();
					$('.steps li').removeClass('error');
					if (res.success) {
						var id = $('#mutasi_masuk_image_galery').find('li').attr('qq-file-id');
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