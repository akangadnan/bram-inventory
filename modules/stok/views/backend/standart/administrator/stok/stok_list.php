<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Stok
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Stok</li>
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
								<img class="img-circle" src="<?= BASE_ASSET; ?>/img/list.png" alt="User Avatar">
							</div>
							<!-- /.widget-user-image -->
							<h3 class="widget-user-username">Stok</h3>
							<h5 class="widget-user-desc">Daftar Stok Setiap Bidang</h5>
						</div>
					</div>
					<div class="padd-left-0 ">
						<select class="form-control chosen chosen-select" name="stok-bidang" id="stok-bidang">
							<option value="">- Pilih Bidang -</option>
						<?php
							foreach (db_get_all_data('bidang') as $row) {
						?>
								<option value="<?= $row->bidang_id ?>"><?= $row->bidang_nama; ?></option>
						<?php
							};
						?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="result"></div>
					</div>
				</div>
			</div>
			<!--/box -->
		</div>
	</div>
</section>
<!-- /.content -->

<!-- Page script -->

<script>
	$(document).ready(function () {
		$('#stok-bidang').change(function () {
			var bidang_id = $(this).val();

			$.ajax({
				url: "<?php echo site_url('administrator/stok/ajax_stok_bidang');?>",
				method: "GET",
				data: {
					id: bidang_id
				},
				dataType: 'html',
				success: function (responses, data) {
					$('#result').html(responses);
				}
			});
			return false;
		});
	});
</script>