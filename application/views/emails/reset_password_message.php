Hi <?php echo __($user->display_name); ?>!

You recently requested a password reset from <?php echo $application_name; ?>, a service that allows you to monitor check-in data for venues. Click the link below to complete this request. If you did request a password reset, simply ignore this e-mail.

	Your Username: <?php echo __($user->username); ?>
		
	Temporary Login Link: <<?php echo site_url(sprintf('login/login_reset/?reset=%s:%s', $user->username, $activation_key)); ?>>
	
Thank you for using <?php echo $application_name; ?>!


---
The <?php echo $application_name; ?> Team
<?php echo site_url(); ?>