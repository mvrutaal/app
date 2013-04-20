<!doctype html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>CRM Login</title>
<link rel="stylesheet" href="<?=base_url()?>assets/css/default.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css" type="text/css" media="all" />
</head>
<body>

<div id="loginblock">

	<div id="Logo"><img src="<?=base_url()?>assets/logos/logo_crm_login.png"></div>

	<div class="login_form">
	<?php echo form_open("auth/login");?>
		<input type="hidden" name="remember" value="1">
		<p>
			<label>Username</label>
			<?php echo form_input($username, '', ' placeholder="Your Username" ');?>
		</p>
		<p>
			<label>Password</label>
			<?php echo form_input($password, '', ' placeholder="Your Password" ');?>
		</p>
		<p>
			<input type="submit" class="btn btn-large btn-primary" value="Login">
		</p>
	<?php echo form_close();?>
	</div>
</div>


</body>
</html>