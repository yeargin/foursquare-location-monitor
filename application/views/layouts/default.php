<?php
	// User object needed globally
	$user = unserialize($this->session->userdata('user'));
	$flash_message = $this->session->flashdata('message');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo isset($page_title) ? $page_title : 'Location Monitor'; ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/bootstrap/css/bootstrap.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/bootstrap/css/bootstrap-responsive.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/js/jquery.meow/jquery.meow.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/main.css'); ?>" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"> </script>

	<?php echo (isset($head_content)) ? $head_content : ''; ?>

</head>
<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<!-- Be sure to leave the brand out there if you want it shown -->
				<a class="brand" href="<?php echo site_url('/'); ?>">Location Monitor</a>
 
				<!-- Everything you want hidden at 940px or less, place within here -->
				<div class="nav-collapse">
				<?php if ($this->session->userdata('user')): ?>
				<ul class="nav">
					<li><a>Welcome <?php echo $user->display_name; ?>!</a></li>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">Main Menu <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/checks/">Checks</a></li>
							<li><a href="/foursquare/search/">Search</a></li>
						</ul>
					</li>
				</ul>
				<div class="pull-right">
				<ul class="nav">
					<li><a href="/profile/">Account Profile</a></li>
					<li><a href="<?php echo site_url('logout'); ?>">Logout</a></li>
				</ul>
				</div>
				<?php elseif ($this->router->class != 'login'): ?>
				<form action="<?php echo site_url('login/post'); ?>" method="post" class="navbar-form pull-right">
				<?php echo form_input('username', '', 'class="input-small" placeholder="Username"'); ?>
				<?php echo form_password('password', '', 'class="input-small" placeholder="Password"'); ?>
				<button class="btn" name="btnlogin" type="submit">Login</button>
				<?php echo form_hidden('redirect', (isset($redirect)) ? $redirect : ''); ?>
				</form>
				<?php endif; ?>
				</div>
 
			</div>
		</div>
	</div>

	<div class="container">
		<div class="content">
			<div class="page-header">
				<h1><?php echo isset($page_title) ? $page_title : 'Location Monitor'; ?></h1>
			</div>
			<div class="row">
				<div class="span9">{yield}</div>
				<div class="span3">
					<?php echo isset($sidebar_content) ? $sidebar_content : ''; ?>
				</div>
			</div>
		</div>
	</div>
	
	<footer class="container">
		<p>
			<a href="<?php echo site_url(); ?>">Location Monitor</a> &bull; Designed and developed by <a href="http://yearg.in">Yeargin Marketing &amp; Creative</a>
		</p>
	</footer>
	
	<div id="onready" style="display:none;">
		<?php if ($flash_message != ''): ?>
			<div><?php echo ($flash_message); ?></div>
		<?php endif; ?>
	</div>
	
	<script src="<?php echo base_url('/assets/bootstrap/js/bootstrap.js'); ?>"> </script>
	<script src="<?php echo base_url('/assets/js/jquery.meow/jquery.meow.js'); ?>"> </script>
	<script src="<?php echo base_url('assets/js/spin.js/spin.js'); ?>"> </script>
	<script src="<?php echo base_url('assets/js/jquery.spin.js'); ?>"> </script>
	<script src="<?php echo base_url('assets/js/jquery.fancybox/source/jquery.fancybox.pack.js'); ?>"> </script>
	<script src="<?php echo base_url('assets/js/jquery.fancybox/lib/jquery.mousewheel-3.0.6.pack.js'); ?>"> </script>
	<link rel="stylesheet" href="<?php echo base_url('assets/js/jquery.fancybox/source/jquery.fancybox.css'); ?>" type="text/css" media="screen">
	
	<script src="<?php echo base_url('/assets/js/global.js'); ?>"> </script>
</body>
</html>