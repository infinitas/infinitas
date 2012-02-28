<?php
    /**
	 * View to create and edit global categories
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
	$edit = strstr($this->request->params['action'], 'edit');

	echo $this->Form->create('GlobalCategory', array('action' => 'edit', 'type' => 'file'));
		echo $this->Infinitas->adminEditHead();
		$headings = array(
			__d('contents', 'Details'),
			__d('contents', 'Configuration'),
		);
		
		$tabs = array(
			$this->element('Contents.content_form'),
			$this->Form->input('id').
				$this->Form->input('parent_id', array('options' => $contentCategories, 'empty' => __d('contents', 'Root Category'))) .
				$this->Form->input('active') . $this->Form->input('hide') . $this->element('Contents.meta_form')
		);

		if($edit) {
			$headings[] = __d('contents', 'Statistics');
			$tabs[] = $this->element('ViewCounter.modules/admin/overall') . $this->element('ViewCounter.modules/admin/popular_items');
		}

		echo $this->Design->tabs($headings, $tabs);
	echo $this->Form->end();