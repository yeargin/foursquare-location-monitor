Hi <?php echo __($key->name); ?>!

You have been registered for a beta key for <?php echo $application_name; ?>, a service that allows you to monitor check-in data for venues. We hope you enjoy using it! To get started, simply click the link below. Alternatively, you can copy and paste the beta key listed below into the form on our website.

	Your Beta Key: <?php echo -__($key->beta_key); ?>
	Registration Link: <<?php echo site_url('register'); ?>?beta_key=<?php echo __($key->beta_key); ?>>
	
Thank you for trying <?php echo $application_name; ?>!


---
The <?php echo $application_name; ?> Team
<?php echo site_url(); ?>