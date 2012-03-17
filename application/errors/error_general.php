<?php
$CI = get_instance();
if ($CI->input->is_cli_request()):
	printf('[%s] %s: %s'.PHP_EOL, date('c'), $heading, strip_tags($message));
	return;
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Error</title>
<link rel="stylesheet" type="text/css" href="<?php echo ('/assets/bootstrap/css/bootstrap.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo ('/assets/bootstrap/css/bootstrap-responsive.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo ('/assets/css/main.css'); ?>" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"> </script>
</head>
<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="/"><img src="/assets/img/monitor_icon.png" /> Location Monitor</a>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="content">
			<div class="page-header">
				<h1><?php echo $heading; ?></h1>
			</div>
			<div class="row">
				<div class="span10">
					<?php echo $message; ?>
					<p>
						<a href="javascript:history.back(-1)">&laquo; Back</a>
					</p>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>