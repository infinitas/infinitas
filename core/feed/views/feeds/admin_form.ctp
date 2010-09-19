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
		echo $this->Infinitas->adminEditHead();
		echo $this->Design->niceBox(); ?>
			<div class="data">
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('name', array('class' => 'title'));
					echo $this->Core->wysiwyg('Feed.description');
					echo $this->Form->input('Feed.fields');
					echo $this->Form->input('Feed.conditions');
					echo $this->Form->input('Feed.order');
				?>
			</div>

			<div class="config">
				<?php
					echo $this->Design->niceBox();
						?><h2><?php __('Configuration'); ?></h2><?php
				        echo $this->Form->input('plugin', array('class' => "pluginSelect {url:{action:'getControllers'}, target:'FeedController'}"));
				        echo $this->Form->input('controller', array('type' => 'select', 'class' => "controllerSelect {url:{action:'getActions'}, target:'FeedAction'}"));
				        echo $this->Form->input('action', array('type' => 'select'));

						echo $this->Form->input('active');
						echo $this->Form->input('group_id');
						echo $this->Form->input('limit');
					echo $this->Design->niceBoxEnd();
					echo $this->Design->niceBox();
						?><h2><?php __('What to include'); ?></h2><?php
						echo $this->Form->input('FeedsFeed');
					echo $this->Design->niceBoxEnd();
				?>
			</div><?php
		echo $this->Design->niceBoxEnd();
	echo $this->Form->end();
?>