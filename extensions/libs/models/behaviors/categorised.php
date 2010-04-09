<?php

class CategorisedBehavior extends ModelBehavior {
	/**
	* Contain settings indexed by model name.
	*
	* @var array
	* @access private
	*/
	var $__settings = array();

	/**
	* Initiate behaviour for the model using settings.
	*
	* @param object $Model Model using the behaviour
	* @param array $settings Settings to override for model.
	* @access public
	*/
	function setup(&$Model, $settings = array()) {
		$Model->bindModel(array(
			'belongsTo' => array(
				'Category' => array(
					'className' => 'Management.Category',
					'counterCache' => 'item_count'
				)
			)
		), false);
	}
}

?>