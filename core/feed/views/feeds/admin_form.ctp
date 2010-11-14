<?php
	/**
	 * Feed view/edit
	 *
	 * Add some documentation for Feed.
	 *
	 * Copyright (c) {yourName}
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright     Copyright (c) 2009 {yourName}
	 * @link          http://infinitas-cms.org
	 * @package       Feed
	 * @subpackage    Feed.views.feeds.index
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create('Feed');
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('RSS Feed', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('name', array('class' => 'title'));
			echo $this->Core->wysiwyg('Feed.description');
			echo $this->Form->input('Feed.fields');
			echo $this->Form->input('Feed.conditions');
			echo $this->Form->input('Feed.order');
			$options = explode(',', Configure::read('Global.pagination_select'));
			$options = array_combine($options, $options);
			echo $this->Form->input('limit', array('options' => $options, 'empty' => Configure::read('Website.empty_select'))); ?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Configuration', true); ?></h1><?php
			echo $this->Form->input('active');
			echo $this->element('route_select', array('plugin' => 'routes'));
			echo $this->Form->input('group_id'); 
			echo $this->Form->input('FeedsFeed', array('title' => __('Sub-Feeds', true), 'type' => 'select', 'multiple' => 'checkbox')); ?>
		</fieldset><?php
	echo $this->Form->end();
?>