<?php
	/**
	 * Comment Template.
	 *
	 * @todo -c Implement .this needs to be sorted out.
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

	echo $this->Form->create('GlobalLayout');
        echo $this->Infinitas->adminEditHead();	?>
		<fieldset>
			<h1><?php echo __('Content', true); ?></h1><?php
			echo $this->Form->input('id');
			
			$error = $this->Form->error('plugin');
			$errorClass = !empty($error) ? 'error' : ''; ?>
			<div class="input smaller required <?php echo $errorClass; ?>">
				<label for="GlobalLayoutPlugin"><?php echo __('Route', true); ?></label><?php
				echo $this->Form->input('plugin', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false, 'class' => "pluginSelect {url:{action:'getModels'}, target:'GlobalLayoutModel'}"));
				echo $this->Form->input('model', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false));
				echo $error; ?>
			</div><?php
			echo $this->Form->input('name', array('class' => 'title'));
			echo $this->Form->input('css', array('class' => 'title'));
			echo $this->Infinitas->wysiwyg('GlobalLayout.html');
			// echo $this->Form->input('php', array('class' => 'title')); ?>
		</fieldset><?php
	echo $this->Form->end();
?>