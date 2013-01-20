<?php
/**
 * Layout form
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * @link http://infinitas-cms.org
 * @package Infinitas.Contents.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Form->create('GlobalLayout');
	echo $this->Infinitas->adminEditHead();
	$headings = array(
		__d('contents', 'Layout'),
		__d('contents', 'Configuration'),
	);

	$error = $this->Form->error('plugin');
	$errorClass = !empty($error) ? 'error' : '';

	$tabs = array(
		sprintf('<div class="input smaller required %s">', $errorClass) .
			sprintf('<label for="GlobalLayoutPlugin">%s</label>', __d('contents', 'Route')).
			$this->Form->input('plugin', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false, 'class' => "ajaxSelectPopulate {url:{action:'getModels'}, target:'GlobalLayoutModel'}")) .
			$this->Form->input('model', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false)) .
			$this->Form->input('auto_load', array('error' => false, 'div' => false, 'label' => false, 'style' => 'width: 30%', 'placeholder' => __d('contents', 'Auto Load'))) .
			$error .
			$this->element('Themes.theme_select') . $this->Form->input('id') .
		'</div>' .
			$this->Form->input('name', array('class' => 'title')) . $this->Form->input('GlobalLayout.html', array('class' => 'span12')),
		$this->Form->input('css', array('class' => 'title'))
	);

	echo $this->Design->tabs($headings, $tabs);
echo $this->Form->end();