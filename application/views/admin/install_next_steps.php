<h2>Congratulations!</h2>

<p>You have installed <?php echo __($application_name); ?> sucessfully. Because this page will no longer be accessible, you may want to save or print these instructions for later use. If you get lost, they are also printed within the <tt>README.md</tt> of the software.

<h3>Create an administrator account</h3>
<ul>
	<li>Create a user by first running a query such as <code>`INSERT INTO beta_keys (beta_key, status) VALUES ('ABCDEF123456', 1);</code> and then use the given beta key to create an account.</li>
	<li>Change the 'level' column for the created user from 'user' to 'admin' to be able to access the Site Administration section (requires logout).</li>
</ul>

<h3>Setup the backend <tt>cron</tt> task</h3>

<p>Within the `application/cron` folder, you will find a shell script and a faux daemon script for local testing. Set these up to run about every 5-10 minutes on your server. You may need to prepend a `cd` into the working directory to make this work on some hosts.</p>

<p><code>cd /path/to/webroot ; ./application/cron/foursquare_checks.sh</code></p>

<p>The script will self-regulate (meaning, it will not attempt a daily check more than once a day, and live checks are configured to be no more than one every 15 minutes). The larger your check database becomes, the more often you will need to process through records.</p>