<?php
/**
* Reviewable Model Behavior
*
* Allows you to attach a review to any model in your application
* Moderates/Validates reviews to check for spam.
* Validates reviews based on a point system. High points is an automatic approval,
* where as low points is marked as spam or deleted.
* Based on Jonathan Snooks outline.
* Based on Jose Diaz-Gonzalez Commentable behavior.
* @link http://github.com/josegonzalez/cakephp-commentable-behavior
*
* @filesource
* @copyright Stoop Dev
* @package core
* @subpackage core.models.behaviors.reviewable
* @modifiedby Jose Diaz-Gonzalez
* @modifiedby Carl Sutton
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*/

class ReviewableBehavior extends ModelBehavior {
	/**
	* Settings initialized with the behavior
	*
	* @access public
	* @var array
	*/
	var $defaults = array(
		'plugin' => 'Core',
		'class' => 'Review', // name of Review model
		'foreign_key' => 'foreign_id', // foreign key of Review model
		'counter_cache' => true,
		'counter_cache_scope' => array('Review.active' => 1),
		'dependent' => true, // model dependency
		'conditions' => array(), // conditions for find method on Review model
		'auto_bind' => true, // automatically bind the model to the User model (default true),
		'sanitize' => false, // whether to sanitize incoming reviews
		'column_author' => 'name', // Column name for the authors name
		'column_content' => 'review', // Column name for the review
		'column_class' => 'class', // Column name for the foreign model
		'column_email' => 'email', // Column name for the authors email
		'column_website' => 'website', // Column name for the authors website
		'column_foreign_id' => 'foreign_id', // Column name of the foreign id that links to the article/entry/etc
		'column_status' => 'status', // Column name for automatic rating
		'column_points' => 'points', // Column name for accrued points
		'blacklist_keywords' => array(),
		// List of blacklisted words within text blocks
		'blacklist_words' => array(),
		// List of blacklisted words within URLs
		'deletion' => - 10 // How many points till the Review is deleted (negative)
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
		$default['conditions'] = array('Review.class' => $model->alias);

		$default['blcklist_keywords'] = explode(',',Configure::read('Website.blacklist_keywords'));
		$default['blcklist_words'] = explode(',',Configure::read('Website.blacklist_words'));

		$reviewClass = isset( $default['plugin'] ) ? $default['plugin'].'.'.$default['class'] : $default['class'];

		if (!isset($this->__settings[$model->alias])) {
			$this->__settings[$model->alias] = $default;
		}

		$this->__settings[$model->alias] = array_merge($this->__settings[$model->alias], ife(is_array($settings), $settings, array()));
		// handles model binding to the model
		// according to the auto_bind settings (default true)
		if ($this->__settings[$model->alias]['auto_bind']) {
			$hasManyReview = array(
				'Review' => array(
					'className' => $reviewClass,
					'foreignKey' => $this->__settings[$model->alias]['foreign_key'],
					'dependent' => $this->__settings[$model->alias]['dependent'],
					'conditions' => $this->__settings[$model->alias]['conditions']));

			$reviewBelongsTo = array($model->alias => array(
					'className' => $model->alias,
					'foreignKey' => $this->__settings[$model->alias]['foreign_key'],
					'counterCache' => $this->__settings[$model->alias]['counter_cache'],
					'counterScope' => $this->__settings[$model->alias]['counter_cache_scope']
					)
				);
			$model->bindModel(array('hasMany' => $hasManyReview), false);
			$model->Review->bindModel(array('belongsTo' => $reviewBelongsTo), false);
		}
	}

	function createReview(&$model, $id, $data = array()) {
		if (!empty($data[$this->__settings[$model->alias]['class']])) {
			unset($data[$model->alias]);
			$model->Review->validate = array($this->__settings[$model->alias]['column_author'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						)
					),
				$this->__settings[$model->alias]['column_content'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						)
					),
				$this->__settings[$model->alias]['column_email'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						),
					'email' => array(
						'rule' => array('email'),
						'message' => 'Please enter a valid email address'
						)
					),
				$this->__settings[$model->alias]['column_class'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						)
					),
				$this->__settings[$model->alias]['column_foreign_id'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						)
					),
				$this->__settings[$model->alias]['column_status'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						)
					),
				$this->__settings[$model->alias]['column_points'] => array(
					'notempty' => array(
						'rule' => array('notempty')
						),
					'numeric' => array(
						'rule' => array('numeric'),
						)
					)
				);

			$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_class']] = $model->alias;
			$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_foreign_id']] = $id;
			$data[$this->__settings[$model->alias]['class']] = $this->_rateReview($model, $data['Review']);

			if ($data[$this->__settings[$model->alias]['class']]['status'] == 'spam') {
				$data[$this->__settings[$model->alias]['class']]['active'] == 0;
			}else if (Configure::read('Reviews.auto_moderate') === true && $data[$this->__settings[$model->alias]['class']]['status'] != 'spam') {
				$data[$this->__settings[$model->alias]['class']]['active'] == 1;
			}

			if ($this->__settings[$model->alias]['sanitize']) {
				App::import('Sanitize');
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_author']] =
				Sanitize::clean($data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_author']]);
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_email']] =
				Sanitize::clean($data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_email']]);
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_content']] =
				Sanitize::clean($data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_content']]);
			}else {
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_author']] = $data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_author']];
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_email']] = $data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_email']];
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_content']] = $data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_content']];
			}
			if ($this->_checkForEmptyVal($data[$this->__settings[$model->alias]['class']]) == false) {
				$model->Review->create();
				if ($model->Review->save($data)) {
					return true;
				}
			}
		}
		return false;
	}

	function getReviews(&$model, $options = array()) {
		$options = array_merge(array('id' => $model->id, 'options' => array()), $options);
		$parameters = array();
		if (isset($options['id']) && is_numeric($options['id'])) {
			$settings = $this->__settings[$model->alias];
			$parameters = array_merge_recursive(
				array('conditions' =>
					array($settings['class'] . '.' . $settings['column_class'] => $model->alias,
						$settings['class'] . '.' . $settings['foreign_key'] => $options['id'],
						$settings['class'] . '.' . $settings['column_status'] => 'approved')),
				$options['options']
				);
		}
		$parameters = (isset($parameters) && !$this->_checkForEmptyVal($parameters)) ? $parameters : array();
		return $model->Review->find('all', $parameters);
	}

	function _rateReview($model, $data) {
		if (!empty($data)) {
			$points = $this->_rateLinks($model, $data);
			$points += $this->_rateLength($model, $data);
			$points += $this->_rateEmail($model, $data);
			$points += $this->_rateKeywords($model, $data);
			$points += $this->_rateStartingWord($model, $data);
			$points += $this->_rateAuthorName($model, $data);
			$points += $this->_rateByPreviousReview($model, $data);
			$points += $this->_rateBody($model, $data);
			$data[$this->__settings[$model->alias]['column_points']] = $points;
			if ($points >= 1) {
				$data[$this->__settings[$model->alias]['column_status']] = 'approved';
			}else if ($points == 0) {
				$data[$this->__settings[$model->alias]['column_status']] = 'pending';
			}else if ($points <= $this->__settings[$model->alias]['deletion']) {
				$data[$this->__settings[$model->alias]['column_status']] = 'delete';
			}else {
				$data[$this->__settings[$model->alias]['column_status']] = 'spam';
			}
		}else {
			$data[$this->__settings[$model->alias]['column_points']] = "-100";
			$data[$this->__settings[$model->alias]['column_status']] = 'delete';
		}
		return $data;
	}

	function _rateLinks($model, $data) {
		$links = preg_match_all(
			"#(^|[\n ])(?:(?:http|ftp|irc)s?:\/\/|www.)(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,4}(?:[-a-zA-Z0-9._\/&=+%?;\#]+)#is",
			$data[$this->__settings[$model->alias]['column_content']], $matches);
		$links = $matches[0];

		$this->totalLinks = count($links);
		$length = mb_strlen($data[$this->__settings[$model->alias]['column_content']]);
		// How many links are in the body
		// -1 per link if over 2, otherwise +2 if less than 2
		$points = ($this->totalLinks > 2) ? $totalLinks * - 1 : 2;
		// URLs that have certain words or characters in them
		// -1 per blacklisted word
		// URL length
		// -1 if more then 30 chars
		foreach ($links as $link) {
			foreach ($this->__settings[$model->alias]['blacklist_words'] as $word) {
				$points = (stripos($link, $word) !== false) ? $points - 1 : $points;
			}

			foreach ($this->__settings[$model->alias]['blacklist_keywords'] as $keyword) {
				$points = (stripos($link, $keyword) !== false) ? $points - 1 : $points;
			}

			$points = (strlen($link) >= 30) ? $points - 1 : $points;
		}
		return $points;
	}

	function _rateLength($model, $data) {
		// How long is the body
		// +2 if more then 20 chars and no links, -1 if less then 20
		$length = mb_strlen($data[$this->__settings[$model->alias]['column_content']]);

		if ($length >= 20 && $this->totalLinks <= 0) {
			return 2;
		}elseif ($length >= 20 && $this->totalLinks == 1) {
			return 1;
		}elseif ($length < 20) {
			return - 1;
		}
	}

	function _rateEmail($model, $data) {
		$points = 0;
		// Number of previous reviewss from email
		// +1 per approved, -1 per spam
		$reviews = $model->Review->find(
			'all', array(
				'fields' => array('Review.id', 'Review.status'),
				'conditions' => array(
					'Review.' . $this->__settings[$model->alias]['column_email'] => $data[$this->__settings[$model->alias]['column_email']]),
				'recursive' => - 1,
				'contain' => false
				)
			);

		if (!empty($reviews)) {
			foreach ($reviews as $review) {
				if ($review['Review']['status'] == 'spam') {
					--$points;
				}elseif ($reviews['Review']['status'] == 'approved') {
					++$points;
				}
			}
		}
		return $points;
	}

	function _rateKeywords($model, $data) {
		$points = 0;
		// Keyword search
		// -1 per blacklisted keyword
		foreach ($this->__settings[$model->alias]['blacklist_keywords'] as $keyword) {
			if (stripos($data[$this->__settings[$model->alias]['column_content']], $keyword) !== false) {
				--$points;
			}
		}
		return $points;
	}

	function _rateStartingWord($model, $data) {
		// Body starts with...
		// -10 points
		$firstWord = mb_substr($data[$this->__settings[$model->alias]['column_content']],
			0,
			stripos($data[$this->__settings[$model->alias]['column_content']], ' '));

		return (in_array(mb_strtolower($firstWord), $this->__settings[$model->alias]['blacklist_keywords'])) ? - 10 : 0;
	}

	function _rateAuthorName($model, $data) {
		// Author name has http:// in it
		// -2 points
		if (stripos($data[$this->__settings[$model->alias]['column_author']], 'http://') !== false) {
			return - 2;
		}
		return 0;
	}

	function _rateByPreviousReview($model, $data) {
		// Body used in previous review
		// -1 per exact review
		$previousReviews = $model->Review->find('count', array(
				'conditions' => array(
					'Review.' . $this->__settings[$model->alias]['column_content'] => $data[$this->__settings[$model->alias]['column_content']]),
				'recursive' => - 1,
				'contain' => false
				));

		return ($previousReviews > 0) ? - $previousReviews : 0;
	}

	function _rateBody($model, $data) {
		// Random character match
		// -1 point per 5 consecutive consonants
		$consonants = preg_match_all(
			'/[^aAeEiIoOuU\s]{5,}+/i',
			$data[$this->__settings[$model->alias]['column_content']],
			$matches);
		$totalConsonants = count($matches[0]);

		return ($totalConsonants > 0) ? - $totalConsonants : 0;
	}

	function _checkForEmptyVal($array) {
		$isEmpty = 0;
		foreach ($array as $key => $item) {
			if (is_numeric($item)) {
			} elseif (empty($item)) {
				$isEmpty++;
			}
		}
		return ($isEmpty > 0) ? true : false;
	}
}

?>