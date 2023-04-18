<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login | <?= get_option('site_name');?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!--===============================================================================================-->
	<link rel="apple-touch-icon" sizes="57x57" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_ASSET; ?>img/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?= BASE_ASSET; ?>img/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_ASSET; ?>img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= BASE_ASSET; ?>img/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_ASSET; ?>img/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?= BASE_ASSET; ?>img/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?= BASE_ASSET; ?>img/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
<!--===============================================================================================-->

	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?= BASE_ASSET; ?>admin-lte/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= BASE_ASSET; ?>admin-lte/dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= BASE_ASSET; ?>admin-lte/plugins/iCheck/square/blue.css">
	<style type="text/css">
		.login-box-body {
			border-top: 5px solid #D7320C;
		}
	</style>
</head>

<body class="hold-transition login-page">
	<div class="content" style="margin-top: 7%;">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="box box-primary box-solid">
					<div class="login-logo js-tilt" data-tilt>
						<img src="<?= BASE_ASSET ?>img/diskominfo.png" alt="IMG">
					</div>
					<div class="login-logo">
						<a href="<?= BASE_URL;?>"><b><?= cclang('login'); ?></b> <?= get_option('site_name'); ?></a>
					</div>
					<div class="box-body">
						<p class="login-box-msg"><?= cclang('sign_to_start_your_session'); ?></p>
				<?php
					if(isset($error) AND !empty($error)) {
				?>
						<div class="callout callout-error" style="color:#C82626">
							<h4><?= cclang('error'); ?>!</h4>
							<p><?= $error; ?></p>
						</div>
				<?php
					}

					$message 	= $this->session->flashdata('f_message'); 
					$type 		= $this->session->flashdata('f_type'); 

					if ($message) {
				?>
						<div class="callout callout-<?= $type; ?>" style="color:#C82626">
							<p><?= $message; ?></p>
						</div>
				<?php
					}
				?>
				<?= form_open('', [
						'name' 		=> 'form_login', 
						'id' 		=> 'form_login', 
						'method' 	=> 'POST'
					]);
				?>
						<div class="form-group has-feedback <?= form_error('username') ? 'has-error' :''; ?>">
							<input type="text" class="form-control" placeholder="Email" name="username"
								value="<?= set_value('username', 'admin@admin.com'); ?>">
							<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
						</div>
						<div class="form-group has-feedback <?= form_error('password') ? 'has-error' :''; ?>">
							<input type="password" class="form-control" placeholder="Password" name="password" value="admin123">
							<span class="glyphicon glyphicon-lock form-control-feedback"></span>
						</div>
						<div class="row">
							<div class="col-xs-8">
								<div class="checkbox icheck">
									<label>
										<input type="checkbox" name="remember" value="1"> <?= cclang('remember_me'); ?>
									</label>
								</div>
							</div>
							<div class="col-xs-4">
								<button type="submit" class="btn btn-primary btn-block btn-flat"><?= cclang('sign_in'); ?></button>
							</div>
						</div>
						<?= form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="login-box">
		<div class="login-logo">
			<a href=""><b><?= cclang('login'); ?></b> <?= get_option('site_name'); ?></a>
		</div>
		<div class="login-box-body">
			<p class="login-box-msg"><?= cclang('sign_to_start_your_session'); ?></p>
	<?php
		if(isset($error) AND !empty($error)) {
	?>
			<div class="callout callout-error" style="color:#C82626">
				<h4><?= cclang('error'); ?>!</h4>
				<p><?= $error; ?></p>
			</div>
	<?php
		}

		$message 	= $this->session->flashdata('f_message'); 
		$type 		= $this->session->flashdata('f_type'); 

		if ($message) {
	?>
			<div class="callout callout-<?= $type; ?>" style="color:#C82626">
				<p><?= $message; ?></p>
			</div>
	<?php
		}
	?>
	<?= form_open('', [
			'name' 		=> 'form_login', 
			'id' 		=> 'form_login', 
			'method' 	=> 'POST'
		]);
	?>
			<div class="form-group has-feedback <?= form_error('username') ? 'has-error' :''; ?>">
				<input type="email" class="form-control" placeholder="Email" name="username"
					value="<?= set_value('username', 'admin@admin.com'); ?>">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback <?= form_error('password') ? 'has-error' :''; ?>">
				<input type="password" class="form-control" placeholder="Password" name="password" value="admin123">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" name="remember" value="1"> <?= cclang('remember_me'); ?>
						</label>
					</div>
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat"><?= cclang('sign_in'); ?></button>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div> -->

	<!-- jQuery 2.2.3 -->
	<script type="text/javascript" src="<?= BASE_ASSET; ?>admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script type="text/javascript" src="<?= BASE_ASSET; ?>admin-lte/bootstrap/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script type="text/javascript" src="<?= BASE_ASSET; ?>admin-lte/plugins/iCheck/icheck.min.js"></script>
	<script type="text/javascript" src="<?= BASE_ASSET; ?>tilt/tilt.jquery.min.js"></script>
	<script type="text/javascript">
		// $('.js-tilt').tilt({
		// 	scale: 1.1
		// });

		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			});
		});
	</script>
</body>

</html>