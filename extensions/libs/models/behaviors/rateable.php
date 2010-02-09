<?php
class RateableBehavior extends ModelBehavior {
	/**
	* Settings initialized with the behavior
	*
	* @access public
	* @var array
	*/
	var $defaults = array(
		'plugin' => 'Management', // name of Rating model
		'class' => 'Rating', // name of Rating model
		'foreign_key' => 'foreign_id', // foreign key of Rating model
		'counter_cache' => true,
		'counter_cache_scope' => null,
		'dependent' => true, // model dependency
		'conditions' => array(), // conditions for find method on Rating model
		'auto_bind' => true, // automatically bind the model to the User model (default true),
		'column_rating' => 'rating', // Column name for the rating counter
		);

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
	function setup(&$model, $settings = array()) {
		$default = $this->defaults;
		$default['conditions'] = array('Rating.class' => $model->alias);
		$default['requre_auth'] = Configure::read('Rating.require_auth');

		//$ratingClass = isset( $default['plugin'] ) ? $default['plugin'].'.'.$default['class'] : $default['class'];
		$ratingClass = 'Management.Rating';

		if (!isset($this->__settings[$model->alias])) {
			$this->__settings[$model->alias] = $default;
		}

		$this->__settings[$model->alias] = array_merge($this->__settings[$model->alias], ife(is_array($settings), $settings, array()));
		// handles model binding to the model
		// according to the auto_bind settings (default true)
		if ($this->__settings[$model->alias]['auto_bind']) {
			$hasManyRating = array(
				'Rating' => array(
					'className' => $ratingClass,
					'foreignKey' => $this->__settings[$model->alias]['foreign_key'],
					'dependent' => $this->__settings[$model->alias]['dependent'],
					'conditions' => $this->__settings[$model->alias]['conditions']));

			$ratingBelongsTo = array($model->alias => array(
					'className' => $model->alias,
					'foreignKey' => $this->__settings[$model->alias]['foreign_key'],
					'counterCache' => $this->__settings[$model->alias]['counter_cache']
					)
				);

			$model->bindModel(array('hasMany' => $hasManyRating), false);
			$model->Rating->bindModel(array('belongsTo' => $ratingBelongsTo), false);
		}
	}

	function rateRecord(&$model, $data = array()) {
		if (!empty($data[$this->__settings[$model->alias]['class']])) {
			unset($data[$model->alias]);

			$findConditions = $data['Rating'];
			unset( $findConditions['rating'] );

			$model->Rating->recursive = -1;
			$count = $model->Rating->find(
				'count',
				array(
					'conditions' => $findConditions
				)
			);

			if ( $count > 0 ){
				return false; // already rated.
			}

			unset( $findConditions['user_id'] );
			unset( $findConditions['ip'] );

			$model->Rating->create();
			if ($model->Rating->save($data, array( 'validate' => false))) {
				$model->Rating->recursive = -1;
				$ratings = $model->Rating->find(
					'list',
					array(
						'conditions' => $findConditions,
						'fields' => array(
							'Rating.rating'
						)
					)
				);

				$total = array_sum($ratings);
				$rating = number_format($total/count($ratings),2,'.',',');
				$model->id = $data['Rating']['foreign_id'];
				$model->saveField('rating',$rating);
				return true;
			}
		}
		return false;
	}
}

?>