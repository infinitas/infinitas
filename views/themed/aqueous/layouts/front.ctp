<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>Infinitas::<?php echo $title_for_layout; ?></title>
	<?php
		echo $html->meta('icon');
		echo $html->css('front');
		echo $scripts_for_layout;
	?>
</head>

<body>
<div id="wrapper">
	<div id="innerwrapper">
		<div id="header">			

			<form action="">
			<input value="Search" />
			</form>

			<h1><a href="">Aqueous</a></h1>

			<?php echo $html->tag('ul', $this->element('nav'), array('id' => 'nav'));?>
			<?php echo $html->tag('ul', $this->element('subnav'), array('id' => 'subnav'));?>

		</div>

		<?php echo $html->tag('div', $this->element('sidebar'), array('id' => 'sidebar'));?>

		<?php echo $html->tag('div', $this->element('sidebarright'), array('id' => 'sidebarright'));?>
		
		<?php echo $html->tag(
			'div',
			$session->flash() . $content_for_layout,
			array('id' => 'content'));
		?>

		<div id="footer">
			<!-- If you wish to delete this line of code please purchase our commercial license http://www.sixshootermedia.com/shop/commercial-license/ -->
			<p>Template design by <a href="http://www.sixshootermedia.com">Six Shooter Media</a>.</p>
		</div>
	</div>
</div>
</body>
</html>