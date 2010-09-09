<?php
/**
* Short Description / title. @todo
*
* Overview of what the file does. About a paragraph or two @todo
*
* Copyright (c) 2010 Carl Sutton ( dogmatic69 )
*
* @filesource
* @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package libs
* @subpackage libs.helpers.slug
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.6
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @author Ceeram
*/


class SlugHelper extends AppHelper{

	var $helpers = array('Html');

	function link($title, $data, $model = null, $edit = false, $plugin = null){
		$id = $slug = null;
		$action = ($edit) ? 'edit' : 'view';
		if (!$model){
			$models = array_keys($data);
			$model = $models[0];
		}
		$controller = Inflector::tableize($model);
		$id = (isset($data[$model]['id'])) ? $data[$model]['id'] : null;
		$slug = (isset($data[$model]['slug'])) ? $data[$model]['slug'] : null;
		$url = array(
			'controller' => $controller,
			'action' => $action,
			'plugin' => $plugin,
			$id,
			$slugged
		);
		return $this->Html->link($title, $url);
	}
		
}	
?>