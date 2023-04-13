<!-- Fine Uploader Gallery CSS file
   ====================================================================== -->
<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<!-- Fine Uploader jQuery JS file
   ====================================================================== -->
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>

<?php $this->load->view('core_template/fine_upload'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?= cclang('user'); ?>
		<small><?= cclang('update', cclang('user')); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= cclang('home'); ?></a></li>
		<li class=""><a href="<?= site_url('administrator/user'); ?>"><?= cclang('user'); ?></a></li>
		<li class="active"><?= cclang('update'); ?></li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<?= form_open(base_url('administrator/user/edit_save/'.$this->uri->segment(4)), [
			'name'    => 'form_user', 
			'class'   => 'form-horizontal', 
			'id'      => 'form_user', 
			'enctype' => 'multipart/form-data', 
			'method'  => 'POST'
			]);
		?>
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
								<h3 class="widget-user-username"><?= cclang('user') ?></h3>
								<h5 class="widget-user-desc"><?= cclang('update', cclang('user')); ?></h5>
								<hr>
							</div>

							<div class="form-group">
								<label for="username" class="col-sm-2 control-label"><?= cclang('username'); ?> <i class="required">*</i></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?= set_value('username', $user->username); ?>">
									<small class="info help-block">The username of user.</small>
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-sm-2 control-label"><?= cclang('email'); ?> <i class="required">*</i></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?= set_value('email', $user->email); ?>">
									<small class="info help-block">The email of user.</small>
								</div>
							</div>

							<div class="form-group">
								<label for="full_name" class="col-sm-2 control-label"><?= cclang('full_name'); ?> <i class="required">*</i></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" value="<?= set_value('full_name', $user->full_name); ?>">
									<small class="info help-block">The full name of user.</small>
								</div>
							</div>

							<div class="form-group">
								<label for="content" class="col-sm-2 control-label">Bidang <i class="required">*</i></label>
								<div class="col-sm-8">
									<select class="form-control chosen chosen-select" name="bidang" id="bidang" data-placeholder="Pilih Bidang">
										<option value=""></option>
										<?php foreach (db_get_all_data('bidang') as $row): ?>
										<option <?= $row->bidang_id == $user->bidang_id ? 'selected="selected"' : ''; ?> value="<?= $row->bidang_id; ?>"><?= $row->bidang_nama; ?></option>
										<?php endforeach; ?>
									</select>
									<small class="info help-block"></small>
								</div>
							</div>

							<div class="form-group">
								<label for="content" class="col-sm-2 control-label"><?= cclang('groups'); ?> <i class="required">*</i></label>
								<div class="col-sm-8">
									<select class="form-control chosen-select" name="group[]" id="group" multiple placeholder="Select groups">
										<?php foreach (db_get_all_data('aauth_groups') as $row): ?>
										<option <?= array_search($row->id, $group_user) !== false? 'selected="selected"' : ''; ?> value="<?= $row->id; ?>"><?= ucwords($row->name); ?></option>
										<?php endforeach; ?>
									</select>
									<small class="info help-block">Select one or more groups.</small>
								</div>
							</div>

							<div class="form-group">
								<label for="username" class="col-sm-2 control-label"><?= cclang('avatar'); ?> </label>
								<div class="col-sm-8">
									<div id="user_avatar_galery" src="<?= BASE_URL . 'uploads/user/' . $user->avatar; ?>"></div>
									<input name="user_avatar_uuid" id="user_avatar_uuid" type="hidden" value="<?= set_value('user_avatar_uuid'); ?>">
									<input name="user_avatar_name" id="user_avatar_name" type="hidden" value="<?= set_value('user_avatar_name', $user->avatar); ?>">
									<small class="info help-block">Format file must PNG, JPEG.</small>
								</div>
							</div>
							<?php is_allowed('user_update_password', function(){?>
							<div class="form-group">
								<label for="password" class="col-sm-2 control-label"><?= cclang('password'); ?> </label>
								<div class="col-sm-6">
									<div class="input-group col-md-8 input-password">
										<input type="password" class="form-control password" name="password" id="password"placeholder="Password" value="<?= set_value('password'); ?>">
										<span class="input-group-btn"><button type="button" class="btn btn-flat show-password"><iclass="fa fa-eye eye"></i></button></span>
									</div>
									<small class="info help-block">
										<?= cclang('do_not_be_fill_if_you_do_not_want_to_change_the_password'); ?>, <br>The password character must 6 or more.
									</small>
								</div>
							</div>
							<?php }) ?>

							<div class="message">

							</div>
							<div class="row-fluid col-md-7">
								<button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="save (Ctrl+s)"><i class="fa fa-save"></i> <?= cclang('save_button'); ?></button>
								<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
									<i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?></a>
								<a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
									<i class="fa fa-undo"></i> <?= cclang('cancel_button'); ?></a>
								<span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> <i><?= cclang('loading_saving_data'); ?></i></span>
							</div>
						</div>
					</div>
					<!--/box body -->
				</div>
				<!--/box -->
			</div>
		<?= form_close(); ?>
	</div>
</section>
<!-- /.content -->
<script src="<?= BASE_ASSET; ?>ckeditor/ckeditor.js"></script>
<!-- Page script -->

<script>
	$(document).ready(function () {
		$('#btn_cancel').click(function () {
			swal({
				title: "<?= cclang('are_you_sure'); ?>",
				text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
				cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
				closeOnConfirm: true,
				closeOnCancel: true
			},
			function (isConfirm) {
				if (isConfirm) {
					window.location.href = BASE_URL + 'administrator/user';
				}
			});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_user = $('#form_user');
			var data_post = form_user.serializeArray();
			var save_type = $(this).attr('data-stype');

			data_post.push({
				name: 'save_type',
				value: save_type
			});

			$('.loading').show();

			$.ajax({
				url: form_user.attr('action'),
				type: 'POST',
				dataType: 'json',
				data: data_post,
			})
			.done(function (res) {
				if (res.success) {
					var id = $('#user_avatar_galery').find('li').attr('qq-file-id');
					$('#user_avatar_uuid').val('');
					$('#user_avatar_name').val('');

					if (save_type == 'back') {
						window.location.href = res.redirect;
						return;
					}

					$('.message').printMessage({
						message: res.message
					});
					$('.message').fadeIn();
				} else {
					$('.message').printMessage({
						message: res.message,
						type: 'warning'
					});
					$('.message').fadeIn();
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
				}, 1000);
			});

			return false;
		}); /*end btn save*/

		$('#user_avatar_galery').fineUploader({
			template: 'qq-template-gallery',
			request: {
				endpoint: BASE_URL + 'administrator/user/upload_avatar_file',
				params: {
					'<?= $this->security->get_csrf_token_name(); ?>': '<?=   $this->security->get_csrf_hash(); ?>'
				}
			},
			deleteFile: {
				enabled: true,
				endpoint: BASE_URL + 'administrator/user/delete_avatar_file',
			},
			thumbnails: {
				placeholders: {
					waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
					notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
				}
			},
			session: {
				endpoint: BASE_URL + 'administrator/user/get_avatar_file/<?= $user->id; ?>',
				refreshOnRequest: true
			},
			multiple: false,
			validation: {
				allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
			},
			showMessage: function (msg) {
				toastr['error'](msg);
			},
			callbacks: {
				onComplete: function (id, name) {
					var uuid = $('#user_avatar_galery').fineUploader('getUuid', id);
					$('#user_avatar_uuid').val(uuid);
					$('#user_avatar_name').val(name);
				},
				onSubmit: function (id, name) {
					var uuid = $('#user_avatar_uuid').val();
					$.get(BASE_URL + '/administrator/user/delete_image_file/' + uuid);
				}
			}
		}); /*end image galey*/

	}); /*end doc ready*/
</script>