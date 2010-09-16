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

	echo $this->Form->create('FeedItem');
		echo $this->Infinitas->adminEditHead();
		echo $this->Design->niceBox(); ?>
			<div class="data">
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('name', array('class' => 'title'));
					echo $this->Core->wysiwyg('FeedItem.description');
					echo $this->Form->input('fields');
					echo $this->Form->input('conditions');
				?>
			</div>

			<div class="config">
				<?php
					echo $this->Design->niceBox();
						?><h2><?php __('Configuration'); ?></h2><?php
				        echo $this->Form->input('plugin', array('class' => "pluginSelect {url:{action:'getControllers'}, target:'FeedItemController'}"));
				        echo $this->Form->input('controller', array('type' => 'select', 'class' => "controllerSelect {url:{action:'getActions'}, target:'FeedItemAction'}"));
				        echo $this->Form->input('action', array('type' => 'select'));

						echo $this->Form->input('active');
						echo $this->Form->input('group_id');
						echo $this->Form->input('limit');
					echo $this->Design->niceBoxEnd();

					echo $this->Design->niceBox();
						?><h2><?php __('Where to show'); ?></h2><?php
						echo $this->Form->input('Feed');
					echo $this->Design->niceBoxEnd();
				?>
			</div><?php
		echo $this->Design->niceBoxEnd();
	echo $this->Form->end();
?>