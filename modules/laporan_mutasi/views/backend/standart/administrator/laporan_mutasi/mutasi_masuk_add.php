<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>

<script src="<?= BASE_ASSET; ?>js/jquery.hotkeys.js"></script>
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
<style>

</style>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Mutasi Masuk <small><?= cclang('new', ['Mutasi Masuk']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/mutasi_masuk'); ?>">Mutasi Masuk</a></li>
		<li class="active"><?= cclang('new'); ?></li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body ">
					<!-- Widget: user widget style 1 -->
					<div class="box box-widget widget-user-2">
						<!-- Add the bg color to the header using any of the bg-* classes -->
						<div class="widget-user-header ">
							<div class="widget-user-image">
								<img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
							</div>
							<!-- /.widget-user-image -->
							<h3 class="widget-user-username">Mutasi Masuk</h3>
							<h5 class="widget-user-desc"><?= cclang('new', ['Mutasi Masuk']); ?></h5>
							<hr>
						</div>
					<?= form_open('', [
							'name' => 'form_mutasi_masuk',
							'class' => 'form-horizontal form-step',
							'id' => 'form_mutasi_masuk',
							'enctype' => 'multipart/form-data',
							'method' => 'POST'
						]);

						$user_groups = $this->model_group->get_user_group_ids();
					?>
						<div class="form-group group-mutasi_masuk_tgl_masuk ">
							<label for="mutasi_masuk_tgl_masuk" class="col-sm-2 control-label">Tanggal Masuk <i class="required">*</i></label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right datepicker" name="mutasi_masuk_tgl_masuk" placeholder="Tanggal Masuk" id="mutasi_masuk_tgl_masuk">
								</div>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-mutasi_masuk_bidang_id ">
							<label for="mutasi_masuk_bidang_id" class="col-sm-2 control-label">Bidang <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="mutasi_masuk_bidang_id" id="mutasi_masuk_bidang_id" data-placeholder="Select Bidang">
									<option value=""></option>
									<?php foreach (db_get_all_data('bidang') as $row): ?>
									<option value="<?= $row->bidang_id ?>"><?= $row->bidang_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-mutasi_masuk_status ">
							<label for="mutasi_masuk_status" class="col-sm-2 control-label">Status <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select" name="mutasi_masuk_status" id="mutasi_masuk_status" data-placeholder="Select Status">
									<option value=""></option>
									<option value="1">Pembelian</option>
									<option value="2">Mutasi</option>
									<option value="3">Stok Opname</option>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-mutasi_masuk_keterangan ">
							<label for="mutasi_masuk_keterangan" class="col-sm-2 control-label">Keterangan </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="mutasi_masuk_keterangan" id="mutasi_masuk_keterangan" placeholder="Keterangan" value="<?= set_value('mutasi_masuk_keterangan'); ?>">
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
													<tr id="inputFormRow">
														<td>
															<select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang0" data-placeholder="Pilih Nama Item">
																<option value=""></option>
																<?php foreach(db_get_all_data('barang') as $row) {?>
																		<option value="<?= $row->barang_id;?>"><?= $row->barang_nama; ?></option>
																<?php }; ?>
															</select>
														</td>
														<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Item"></td>
														<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Item"></td>
														<td>&nbsp;</td>
													</tr>
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
			html += '<td><select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang0" data-placeholder="Pilih Nama Item"><option value="">- Pilih Nama Item -</option>';
			<?php foreach(db_get_all_data('barang') as $row) {?>
				html += '<option value="<?= $row->barang_id ?>"><?= $row->barang_nama; ?></option>';
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
				title: "<?= cclang('are_you_sure'); ?>",
				text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
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

			data_post.push({
				name: 'event_submit_and_action',
				value: window.event_submit_and_action
			});

			$('.loading').show();

			$.ajax({
				url: BASE_URL + '/administrator/mutasi_masuk/add_save',
				type: 'POST',
				dataType: 'json',
				data: data_post,
			})
			.done(function (res) {
				$('form').find('.form-group').removeClass('has-error');
				$('.steps li').removeClass('error');
				$('form').find('.error-input').remove();
				if (res.success) {
					if (save_type == 'back') {
						window.location.href = res.redirect;
						return;
					}

					$('.message').printMessage({
						message: res.message
					});
					$('.message').fadeIn();
					resetForm();
					$('.chosen option').prop('selected', false).trigger('chosen:updated');

				} else {
					if (res.errors) {
						$.each(res.errors, function (index, val) {
							$('form #' + index).parents('.form-group').addClass(
								'has-error');
							$('form #' + index).parents('.form-group').find('small')
								.prepend(`<div class="error-input">` + val + `</div>`);
						});
						$('.steps li').removeClass('error');
						$('.content section').each(function (index, el) {
							if ($(this).find('.has-error').length) {
								$('.steps li:eq(' + index + ')').addClass('error').find(
									'a').trigger('click');
							}
						});
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
	}); /*end doc ready*/
</script>