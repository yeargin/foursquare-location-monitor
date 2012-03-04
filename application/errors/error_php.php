<?php
$CI = get_instance();
if ($CI->input->is_cli_request()):
	printf('[%s] %s: %s in %s, line %s'.PHP_EOL, date('c'), $severity, strip_tags($message), $filepath, $line);
	return;
endif;
?>
<div class="alert alert-block error">
	<a class="close" href="#">×</a>
	<p onclick="javascript:$('.error-detail').toggle();"><strong>A PHP Error was encountered</strong></p>
	<ul class="error-detail" style="display:none;">
	  <li>Severity: <?php echo $severity; ?></li>
	  <li>Message:  <?php echo $message; ?></li>
	  <li>Filename: <?php echo $filepath; ?></li>
	  <li>Line Number: <?php echo $line; ?></li>
	</ul>
</div>