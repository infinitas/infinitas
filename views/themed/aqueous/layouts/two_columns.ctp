<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<?php
		echo $html->meta('icon');
		echo $html->css('one');
		echo $scripts_for_layout;
	?>
</head>

<body>
<?php echo $this->element('template_selector'); ?>
<div id="wrapper">
	<div id="innerwrapper">
		<div id="header">

			<form action="">
			<input value="Search" />
			</form>

			<h1><a href="#">Aqueous</a></h1>

			<h2>A liquid layout by <a href="http://www.sixshootermedia.com/free-templates/">Six Shooter Media</a></h2>
				
			<?php echo $this->element('nav') ?>
			<?php echo $this->element('subnav') ?>
		
		</div>

		<?php echo $this->element('sidebar') ?>


		<div id="contentnorightbar">
			<?php $session->flash(); ?>
			<h3>This is the "dark" theme two column layout from aqueous plugin</h3>
			<?php echo $content_for_layout; ?>
		</div>
		
		<div id="footer">
			<!-- If you wish to delete this line of code please purchase our commercial license http://www.sixshootermedia.com/shop/commercial-license/ -->
			<p>Template design by <a href="http://www.sixshootermedia.com">Six Shooter Media</a>.<br />
			<!-- you can delete below here -->
			© All your copyright information here.</p><br /><br />
		</div>
	</div>
</div>
<?php echo $cakeDebug; ?>
</body>
</html>