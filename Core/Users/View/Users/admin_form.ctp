<?php
/**
 * Page for creating and editing users
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * @link http://infinitas-cms.org
 * @package Infinitas.Users.View
 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Form->create('User');
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('users', 'User'),
	);

	$contents = array(
		implode('', array(
			$this->Form->input('full_name'),
			$this->Form->input('username', array('autocomplete' => 'off')),
			$this->Form->input('email', array('autocomplete' => 'off')),
			$this->Form->input('password', array('value' => '', 'autocomplete' => 'off')),
			$this->Form->input('confirm_password', array('type' => 'password', 'value' => '', 'autocomplete' => 'off'))
		))
	);

	$right = array(
		$this->Form->input('prefered_name'),
		$this->Form->input('group_id'),
		$this->Form->input('birthday', array('empty' => false, 'minYear' => date('Y') - 110, 'maxYear' => date('Y') - 13)),
		$this->Form->input('active')
	);
	echo $this->Html->tag('div', implode('', array(
		$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array('class' => 'span9')),
		$this->Design->sidebar($right)
	)), array('class' => 'row-fluid form'));
echo $this->Form->end();