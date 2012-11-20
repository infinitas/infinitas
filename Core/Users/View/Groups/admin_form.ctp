<?php
/**
 * Form for creating and editing groups
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/users
 * @package Infinitas.Users.View
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
echo $this->Form->create(null, array(
	'inputDefaults' => array(
		'empty' => Configure::read('Website.empty_select')
	)
));
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('users', 'Group')
	);
	$contents = array(
		implode('', array(
			$this->Form->input('name'),
			$this->Form->input('parent_id'),
			$this->Infinitas->wysiwyg('Group.description')
		))
	);
	
	echo $this->Design->tabs($tabs, $contents);
echo $this->Form->end();