<div class="dashboard">
	<h1>Problem with your event.</h1>
	<p><b>Message:</b> <?php echo $params['message']; ?></p>
	<p><b>Event:</b> <?php echo 'on', ucfirst(key($params['event'])); ?></p>
	<p>Check your config and try again.</p>
</div>