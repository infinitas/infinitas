<?php
	$links = array();
	
	$links[] = $this->Html->link(
		$this->Html->image('/filemanager/img/icon.png', array('class' => 'logout-link')),
		array(
			'plugin' => 'filemanager',
			'controller' => 'filemanager',
			'action' => 'admin_ajax_tree',
			'foo',
			'ext' => 'ajax'
		),
		array(
			'id' => 'ajax-filemanager',
			'title' => __d('libs', 'Filemanager :: View and manage assets'),
			'escape' => false
		)
	);
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<?php
				echo $this->Html->link('Infinitas CMS', '/admin', array(
					'class' => 'brand'
				));
				echo $this->Html->tag('div', implode('', array(
					$this->Design->arrayToList($this->Menu->builAdminMenu(), array(
						'ul' => 'nav'
					)),
					$this->Html->tag('p', implode('', $links))
				)), array('class' => 'nav-collapse collapse'));

				echo $this->element('Users.profile_nav');
				echo $this->element('Users.language_select');
			?>
		</div>
	</div>
</div>
<div id="filemanager-holder">
	<div class="bar"></div>
	<div class="tree"></div>
	<div class="preview"></div>
</div>