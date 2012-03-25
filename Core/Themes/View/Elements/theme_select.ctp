<?php
	/**
	 * builds the form select controlls with data fot the ajax fetch
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.themes
	 * @subpackage Infinitas.themes.view
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.9a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	if (!isset($model)) {
		$model = current($this->request->params['models']);
		$model = $model['className'];
	}
	$themes = InfinitasTheme::themes();
	
	if($this->request->data[$model]['theme_id']) {
		$layouts = InfinitasTheme::layouts($theme);
	}
	
	$error = $this->Form->error('plugin');
	$errorClass = !empty($error) ? 'error' : '';
?>
<div class="input smaller <?php echo $errorClass; ?>">
	<label for="<?php echo $model; ?>Plugin"><?php echo __('Route'); ?></label><?php
	echo $this->Form->input('theme', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false, 'class' => "ajaxSelectPopulate {url:{action:'getThemeLayouts'}, target:'" . $model . "Layout'}"));
	echo $this->Form->input('layout', array('error' => false, 'div' => false, 'type' => 'select', 'label' => false));
	echo $error; ?>
</div>