<?php
    /**
	 * Install additional plugins and thmese
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link http://infinitas-cms.org
     * @package Infinitas.categories
     * @subpackage Infinitas.categories.admin_form
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
	 *
	 * @author dogmatic69
     */

	echo $this->Form->create('Install', array('type' => 'file'));
		echo $this->Infinitas->adminIndexHead(null, $this->Infinitas->massActionButtons(array('install', 'cancel')));
		
		$tabs = array(
			__d('installer', 'Plugin'),
			__d('installer', 'Theme'),
		);
		
		$content = array(
			$this->Form->input('Plugin.file', array('type' => 'file')) . $this->Form->input('Plugin.url') . 
			$this->Form->input('Plugin.local', array('empty' => Configure::read('Website.empty_select'), 'options' => InfinitasPlugin::listPlugins('notInstalled'))),
			
			$this->Form->input('Theme.file', array('type' => 'file')) . $this->Form->input('Theme.url') . 
			$this->Form->input('Theme.local', array('empty' => Configure::read('Website.empty_select'), 'options' => $possibleThemes))
		);
		
		echo $this->Design->tabs($tabs, $content);
	echo $this->Form->end();
?>