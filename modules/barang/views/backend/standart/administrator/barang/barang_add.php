<style>
	/* .group-barang_nama */
	.group-barang_nama {}

	.group-barang_nama .control-label {}

	.group-barang_nama .col-sm-8 {}

	.group-barang_nama .form-control {}

	.group-barang_nama .help-block {}

	/* end .group-barang_nama */



	/* .group-barang_satuan_id */
	.group-barang_satuan_id {}

	.group-barang_satuan_id .control-label {}

	.group-barang_satuan_id .col-sm-8 {}

	.group-barang_satuan_id .form-control {}

	.group-barang_satuan_id .help-block {}

	/* end .group-barang_satuan_id */
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Barang <small><?= cclang('new', ['Barang']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/barang'); ?>">Barang</a></li>
		<li class="active"><?= cclang('new'); ?></li>
	</ol>
</section>
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
							<h3 class="widget-user-username">Barang</h3>
							<h5 class="widget-user-desc"><?= cclang('new', ['Barang']); ?></h5>
							<hr>
						</div>
						<?= form_open('', [
								'name' => 'form_barang',
								'class' => 'form-horizontal form-step',
								'id' => 'form_barang',
								'enctype' => 'multipart/form-data',
								'method' => 'POST'
							]);

							$user_groups = $this->model_group->get_user_group_ids();
						?>
						<div class="form-group group-barang_kategori_barang_id">
							<label for="barang_kategori_barang_id" class="col-sm-2 control-label">Katgeori Barang <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="barang_kategori_barang_id" id="barang_kategori_barang_id" data-placeholder="Select Katgeori Barang">
									<option value=""></option>
									<?php foreach (db_get_all_data('kategori_barang') as $row): ?>
									<option value="<?= $row->kategori_barang_id ?>"><?= $row->kategori_barang_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>
						<div class="form-group group-barang_nama">
							<label for="barang_nama" class="col-sm-2 control-label">Nama Barang <i class="required">*</i></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="barang_nama" id="barang_nama" placeholder="Nama Barang" value="<?= set_value('barang_nama'); ?>">
								<small class="info help-block"></small>
							</div>
						</div>
						<div class="form-group group-barang_satuan_id">
							<label for="barang_satuan_id" class="col-sm-2 control-label">Satuan <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="barang_satuan_id" id="barang_satuan_id" data-placeholder="Select Satuan">
									<option value=""></option>
									<?php foreach (db_get_all_data('satuan_barang', $conditions) as $row): ?>
									<option value="<?= $row->satuan_id ?>"><?= $row->satuan_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block"></small>
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

							<div class="custom-button-wrapper">

							</div>

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

		(function () {
			var barang_nama = $('#barang_nama');
			var barang_satuan_id = $('#barang_satuan_id');
		})()

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
					window.location.href = BASE_URL + 'administrator/barang';
				}
			});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_barang = $('#form_barang');
			var data_post = form_barang.serializeArray();
			var save_type = $(this).attr('data-stype');

			data_post.push({
				name: 'save_type',
				value: save_type
			});

			data_post.push({
				name: 'event_submit_and_action',
				value: window.event_submit_and_action
			});

			(function () {
				data_post.push({
					name: '_example',
					value: 'value_of_example',
				})
			})()


			$('.loading').show();

			$.ajax({
				url: BASE_URL + 'administrator/barang/add_save',
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
							$('form #' + index).parents('.form-group').find('small').prepend(`<div class="error-input">` + val + `</div>`);
						});
						$('.steps li').removeClass('error');
						$('.content section').each(function (index, el) {
							if ($(this).find('.has-error').length) {
								$('.steps li:eq(' + index + ')').addClass('error').find('a').trigger('click');
							}
						});
					}
					$('.message').printMessage({
						message: res.message,
						type: 'danger'
					});
				}
			})
			.fail(function () {
				$('.message').printMessage({
					message: 'Error save data',
					type: 'danger'
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