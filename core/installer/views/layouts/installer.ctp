<!DOCTYPE html>
<html>
	<head>
		<title>Infinitas Installer :: <?php echo $title_for_layout; ?></title>
		<?php
			echo $this->Html->css('reset');
			echo $this->Html->css('960');
			echo $this->Html->css( '/installer/css/install' );
			echo $scripts_for_layout;
		?>
	</head>
	<body>
		<?php echo $this->Form->create('Install', array('id' => 'container', 'class' => 'container_12', 'url' => array('controller' => 'install', 'action' => 'index', $this->Wizard->activeStep()))) ?>
			<?php echo $this->Form->hidden('step', array('value' => $this->Wizard->activeStep())); ?>
			<h2 class="grid_12" id="header">Infinitas</h2>
			<div class="clear"></div>
			<div class="grid_8" id="inner-container">
				<div class="grid_8 alpha" id="inner-header">
					<h3 class="grid_5 alpha"><?php echo $title_for_layout; ?></h3>
					<div class="grid_3 omega" id="buttons">
						<?php
							if($this->Wizard->stepNumber() > 1 && !isset($hidePrevious)) {
								echo $this->Form->button('Previous', array('name' => 'Previous', 'value' => 'Previous'));
							}
							if(!isset($hideNext)) {
								echo $this->Form->button('Next', array('name' => 'Next', 'value' => 'Next'));
							}
						?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="grid_8 alpha" id="content">
					<?php echo $content_for_layout; ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="grid_3" id="steps">
				<?php echo $this->Wizard->progressMenu($installerProgress, false) ?>
			</div>
			<div class="clear"></div>
		</form>
	</body>
</html>


<?php /*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Infinitas Installer :: <?php echo $title_for_layout; ?></title>
        <?php
            echo $this->Html->css( '/installer/css/install' );
            echo $scripts_for_layout;
        ?>
    </head>
    <body>
		<div id="wrap">
			<div id="header">
				<?php echo $this->Html->image('/installer/img/infinitas.png', array('alt' => 'Infinitas Cms', 'style' => 'padding-left:35px;padding-top:10px; width:220px;')); ?>
				<div id="nav">
					<div id="topmenu">
						<ul>
							<li class="active"><?php echo $this->Html->link(__('Installer', true), '/'); ?></li>
							<li><?php echo $this->Html->link(__('Infinitas', true), 'http://infinitas-cms.org', array('target' => '_blank')); ?></li>
							<li><?php echo $this->Html->link(__('Code', true), 'http://github.com/infinitas/infinitas', array('target' => '_blank')); ?></li>
							<li><?php echo $this->Html->link(__('Themes', true), 'http://github.com/infinitas/themes', array('target' => '_blank')); ?></li>
							<li><?php echo $this->Html->link(__('Issues', true), 'http://infinitas.lighthouseapp.com', array('target' => '_blank')); ?></li>
						</ul>
						<h1 id="sitename"><?php echo __('Welcome to Infinitas', true); ?></h1>
					</div>
				</div>
				<div class="clear"></div>
				<div id="breadcrumb">
					Installer &raquo; <?php echo $this->action; ?>
				</div>
			</div>
			<div id="content">
				<div id="right">
					<div class="<?php echo isset($this->params['plugin'])?$this->params['plugin']:''; ?>">
						<div class="<?php echo isset($this->params['controller'])?$this->params['controller']:''; ?>">
							<div class="<?php echo isset($this->params['action'])?$this->params['action']:''; ?>">
								<?php
									//echo $this->Session->flash();
									echo $content_for_layout;
								?>
							</div>
						</div>
					</div>
				</div>
				<div id="sidebar">
					<div id="sidebartop"></div>
					<h2><?php echo __('Progress', true); ?></h2>
					<ul>
						<?php foreach($installerProgress as $action => $title) { ?>
							<li class="<?php echo ($this->action == $action) ? 'active' : ''; ?>"><?php echo $this->Html->link($title, array('plugin' => 'installer', 'controller' => 'install', 'action' => $this->action, '#' => 'here' )); ?></li>
						<?php } ?>
					</ul>
					<div id="sidebarbtm"></div>
				</div>
				<div class="clear"></div>
				<div id="bottom">
					<p>Copyright &copy; <?php echo date('Y'), ' ', $this->Html->link('Infinitas', 'http://infinitas-cms.org', array('title' => 'Infinitas Cms')); ?> </p>
				</div>
			</div>
			<div id="footer">
				<div id="credits"> <a href="http://ramblingsoul.com">CSS Template</a> by RamblingSoul</div>
			</div>
		</div>
    </body>
</html>
 */