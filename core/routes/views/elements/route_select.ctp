<?php
	/**
	 * builds the form select controlls with data fot the ajax fetch
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.routes
	 * @subpackage Infinitas.routes.elements.route_select
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	$model = isset($model) ? $model : $this->params['models'][0];
	$error = $this->Form->error('plugin');
	$errorClass = !empty($error) ? 'error' : '';
?>
<div class="input smaller required <?php echo $errorClass; ?>">
	<label for="'.$model.'Plugin"><?php echo __('Route', true); ?></label><?php
	echo $this->Form->select('plugin', array('label' => false, 'class' => "pluginSelect {url:{action:'getControllers'}, target:'".$model."Controller'}"));
	echo $this->Form->select('controller', array('label' => false, 'class' => "controllerSelect {url:{action:'getActions'}, target:'".$model."Action'}"));
	echo $this->Form->select('action', array('label' => false));
	echo $error; ?>
</div>