<?php
	/**
	 * convert content to infinitas content
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link          http://infinitas-cms.org
	 * @package       sort
	 * @subpackage    sort.comments
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since         0.5a
	 */

	echo $this->Form->create('GlobalContent');
        echo $this->Infinitas->adminEditHead(array('move', 'cancel'));	?>
		<fieldset>
			<h1><?php echo __('Content', true); ?></h1>
			<div class="input smaller required">
				<label for="GlobalLayoutPlugin"><?php echo __('Select a model', true); ?></label><?php
				echo $this->Form->input('plugin', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false, 'class' => "pluginSelect {url:{action:'getModels'}, target:'GlobalContentModel'}"));
				echo $this->Form->input('model', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false)); ?>
			</div><?php
			echo $this->Form->input('rows_to_move', array('value' => 500)); ?>
		</fieldset><?php
	echo $this->Form->end();
?>