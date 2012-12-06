<?php
echo $this->Form->input('ModuleConfig.menu', array(
	'type' => 'input',
	'label' => __d('menus', 'The menu to load')
));
echo $this->Form->input('ModuleConfig.type', array(
	'type' => 'select',
	'label' => __d('menus', 'How the menu should be rendered'),
	'empty' => __d('menus', 'Use module default'),
	'options' => array(
		'vertical' => __d('menus', 'Vertical'),
		'horizontal' => __d('menus', 'Horizontal'),
	)
));
