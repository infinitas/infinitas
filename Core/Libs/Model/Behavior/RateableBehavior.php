<?php
/**
 * RateableBehavior
 *
 * @package Infinitas.Libs.Model.Behavior
 */

/**
 * RateableBehavior
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class RateableBehavior extends ModelBehavior {
/**
 * Default behavior settings
 *
 * @var array
 */
	public $defaults = array(
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
 * Settings indexed by model name.
 *
 * @var array
 */
	private $__settings = array();

/**
 * Initiate behaviour for the model using settings.
 *
 * @param Model $Model Model using the behaviour
 * @param array $settings Settings to override for model.
 *
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		return;
		$default = $this->defaults;
		$default['conditions'] = array('Rating.class' => $Model->alias);
		$default['requre_auth'] = Configure::read('Rating.require_auth');

		//$ratingClass = isset( $default['plugin'] ) ? $default['plugin'].'.'.$default['class'] : $default['class'];
		$ratingClass = 'Management.Rating';

		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $default;
		}

		$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], is_array($settings) ? $settings : array());
		// handles model binding to the model
		// according to the auto_bind settings (default true)
		if ($this->__settings[$Model->alias]['auto_bind']) {
			$hasManyRating = array(
				'Rating' => array(
					'className' => $ratingClass,
					'foreignKey' => $this->__settings[$Model->alias]['foreign_key'],
					'dependent' => $this->__settings[$Model->alias]['dependent'],
					'conditions' => $this->__settings[$Model->alias]['conditions']
				)
			);

			$ratingBelongsTo = array(
				$Model->alias => array(
					'className' => $Model->alias,
					'foreignKey' => $this->__settings[$Model->alias]['foreign_key'],
					'counterCache' => $this->__settings[$Model->alias]['counter_cache']
				)
			);

			$Model->bindModel(array('hasMany' => $hasManyRating), false);
			$Model->Rating->bindModel(array('belongsTo' => $ratingBelongsTo), false);
		}
	}

/**
 * Rate record
 *
 * @param Model $Model
 * @param array $data
 *
 * @return boolean
 */
	public function rateRecord(Model $Model, $data = array()) {
		if (!empty($data[$this->__settings[$Model->alias]['class']])) {
			unset($data[$Model->alias]);

			$findConditions = $data['Rating'];
			unset($findConditions['rating']);

			$Model->Rating->recursive = -1;
			$count = $Model->Rating->find('count', array(
				'conditions' => $findConditions
			));

			if ($count > 0) {
				return false; // already rated.
			}

			unset($findConditions['user_id'], $findConditions['ip'] );

			$Model->Rating->create();
			if ($Model->Rating->save($data, array('validate' => false))) {
				$Model->Rating->recursive = -1;
				$ratings = $Model->Rating->find('list', array(
					'conditions' => $findConditions,
					'fields' => array(
						'Rating.rating'
					)
				));

				$total = array_sum($ratings);
				$rating = number_format($total / count($ratings), 2, '.', ',');

				$Model->id = $data['Rating']['foreign_id'];
				$Model->data[$Model->name]['rating'] = $rating;
				$Model->data[$Model->name]['modified'] = false;

				$Model->save($Model->data, array('validate' => false));
				return true;
			}
		}

		return false;
	}

}