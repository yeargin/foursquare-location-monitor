Hi <?php echo __($user->display_name); ?>!

Thank you for registering for an account with <?php echo $application_name; ?>, a service that allows you to monitor check-in data for venues. We hope you enjoy using it! This e-mail is to welcome you 

	Your Username: <?php echo __($user->username); ?>
	
	Your Password: [provided at registration]
	
	Login Link: <<?php echo site_url('login'); ?>>
	
Thank you for using <?php echo $application_name; ?>!


---
The <?php echo $application_name; ?> Team
<?php echo site_url(); ?>