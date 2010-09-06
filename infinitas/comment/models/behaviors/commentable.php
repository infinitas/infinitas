<?php
	/**
	 * Commentable Model Behavior
	 *
	 * Allows you to attach a comment to any model in your application
	 * Moderates/Validates comments to check for spam.
	 * Validates comments based on a point system.High points is an automatic approval,
	 * where as low points is marked as spam or deleted.
	 * Based on Jonathan Snooks outline.
	 *
	 * @filesource
	 * @copyright Stoop Dev
	 * @link http://github.com/josegonzalez/cakephp-commentable-behavior
	 * @package commentable
	 * @subpackage commentable.models.behaviors
	 * @version 0.3
	 * @author Jose Diaz-Gonzalez
	 * @author dogmatic69
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	class CommentableBehavior extends ModelBehavior{
		/**
		 * Settings initialized with the behavior
		 *
		 * @access public
		 * @var array
		 */
		public $defaults = array(
			'plugin' => 'Comment',
			'class' => 'Comment', // name of Comment model
			'auto_bind' => true, // automatically bind the model to the User model (default true),
			'sanitize' => true, // whether to sanitize incoming comments
			'column_author' => 'name', // Column name for the authors name
			'column_content' => 'comment', // Column name for the comments body
			'column_class' => 'class', // Column name for the foreign model
			'column_email' => 'email', // Column name for the authors email
			'column_website' => 'website', // Column name for the authors website
			'column_foreign_id' => 'foreign_id', // Column name of the foreign id that links to the article/entry/etc
			'column_status' => 'status', // Column name for automatic rating
			'column_points' => 'points', // Column name for accrued points
			// List of blacklisted words within URLs
			'deletion' => -10 // How many points till the comment is deleted (negative)
		);

		/**
		 * Contain settings indexed by model name.
		 *
		 * @var array
		 * @access private
		 */
		private $__settings = array();

		/**
		 * Initiate behaviour for the model using settings.
		 *
		 * @param object $Model Model using the behaviour
		 * @param array $settings Settings to override for model.
		 * @access public
		 */
		public function setup(&$model, $settings = array()) {
			$default = $this->defaults;
			$default['blacklist_keywords'] = explode(',', Configure::read('Website.blacklist_keywords'));
			$default['blacklist_words'] = explode(',', Configure::read('Website.blacklist_words'));
			$default['conditions'] = array('Comment.class' => $model->alias);			

			if (!isset($this->__settings[$model->alias])) {
				$this->__settings[$model->alias] = $default;
			}

			$this->__settings[$model->alias] = array_merge($this->__settings[$model->alias], (array)$settings);
			
			$hasManyComment = array(
				'Comment' => array(
					'className' => 'Comment.Comment',
					'foreignKey' => 'foreign_id',
					'dependent' => true,
					'conditions' => array(
						'Comment.status != ' => 'delete'
					)
				)
			);

			$commentBelongsTo = array(
				$model->alias => array(
					'className' => $model->alias,
					'foreignKey' => 'foreign_id',
					'counterCache' => true,
					'counterScope' => array('Comment.active' => 1)
				)
			);
			
			$model->bindModel(array('hasMany' => $hasManyComment), false);
			$model->Comment->bindModel(array('belongsTo' => $commentBelongsTo), false);
		}

		public function createComment(&$model, $data = array()) {
			if (!empty($data[$this->__settings[$model->alias]['class']])) {
				return false;
			}

			unset($data[$model->alias]);
			$model->Comment->validate = array(
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
						'message' => __('Please enter a valid email address', true)
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
			$data[$this->__settings[$model->alias]['class']] = $this->_rateComment($model, $data['Comment']);

			if ($data[$this->__settings[$model->alias]['class']]['status'] == 'spam') {
				$data[$this->__settings[$model->alias]['class']]['active'] == 0;
			}

			else if (Configure::read('Comments.auto_moderate') === true && $data[$this->__settings[$model->alias]['class']]['status'] != 'spam') {
				$data[$this->__settings[$model->alias]['class']]['active'] == 1;
			}

			if ($this->__settings[$model->alias]['sanitize']) {
				App::import('Sanitize');
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_email']] =
						Sanitize::clean($data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_email']]);
				
				$data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_content']] =
						Sanitize::clean($data[$this->__settings[$model->alias]['class']][$this->__settings[$model->alias]['column_content']]);
			}

			$model->Comment->create();
			if ($model->Comment->save($data)) {
				return true;
			}
		}

		public function getComments(&$model, $options = array()) {
			$options = array_merge(array('id' => $model->id, 'options' => array()), $options);
			$parameters = array();
			if (isset($options['id']) && is_numeric($options['id'])) {
				$settings = $this->__settings[$model->alias];
				$parameters = array_merge_recursive(
					array(
						'conditions' => array(
							$settings['class'] . '.' . $settings['column_class'] => $model->alias,
							$settings['class'] . '.foreign_id' => $options['id'],
							$settings['class'] . '.' . $settings['column_status'] => 'approved')
					),
					$options['options']
				);
			}
			$parameters = (isset($parameters) && !$this->_checkForEmptyVal($parameters)) ? $parameters : array();
			return $model->Comment->find('all', $parameters);
		}

		private function __rateComment($model, $data) {
			if (!empty($data)) {
				$points = $this->__rateLinks($model, $data);
				$points += $this->__rateLength($model, $data);
				$points += $this->__rateEmail($model, $data);
				$points += $this->__rateKeywords($model, $data);
				$points += $this->__rateStartingWord($model, $data);
				$points += $this->__rateByPreviousComment($model, $data);
				$points += $this->__rateBody($model, $data);
				$data[$this->__settings[$model->alias]['column_points']] = $points;
				if ($points >= 1) {
					$data[$this->__settings[$model->alias]['column_status']] = 'approved';
				}

				else if ($points == 0) {
					$data[$this->__settings[$model->alias]['column_status']] = 'pending';
				}

				else if ($points <= $this->__settings[$model->alias]['deletion']) {
					$data[$this->__settings[$model->alias]['column_status']] = 'delete';
				}

				else {
					$data[$this->__settings[$model->alias]['column_status']] = 'spam';
				}
			}

			else {
				$data[$this->__settings[$model->alias]['column_points']] = -100;
				$data[$this->__settings[$model->alias]['column_status']] = 'delete';
			}
			return $data;
		}

		private function __rateLinks($model, $data) {
			$links = preg_match_all(
				"#(^|[\n ])(?:(?:http|ftp|irc)s?:\/\/|www.)(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,4}(?:[-a-zA-Z0-9._\/&=+%?;\#]+)#is",
				$data[$this->__settings[$model->alias]['column_content']],
				$matches
			);

			$links = $matches[0];

			$this->totalLinks = count($links);
			$length = mb_strlen($data[$this->__settings[$model->alias]['column_content']]);
			// How many links are in the body
			// -1 per link if over 2, otherwise +2 if less than 2
			$maxLinks = Configure::read('Comment.maximum_links');
			$maxLinks > 0 ? $maxLinks : 2;
			
			$points = $this->totalLinks > 2
				? $this->totalLinks * -1
				: 2;
			// URLs that have certain words or characters in them
			// -1 per blacklisted word
			// URL length
			// -1 if more then 30 chars
			foreach ($links as $link) {
				foreach ($this->__settings[$model->alias]['blacklist_words'] as $word) {
					$points = stripos($link, $word) !== false ? $points - 1 : $points;
				}

				foreach ($this->__settings[$model->alias]['blacklist_keywords'] as $keyword) {
					$points = stripos($link, $keyword) !== false ? $points - 1 : $points;
				}

				$points = strlen($link) >= 30 ? $points - 1 : $points;
			}

			return $points;
		}

		private function __rateLength($model, $data) {
			// How long is the body
			// +2 if more then 20 chars and no links, -1 if less then 20
			$length = mb_strlen($data[$this->__settings[$model->alias]['column_content']]);

			$minLenght = Configure::read('Comment.minimum_length') > 0
				? Configure::read('Comment.minimum_length')
				: 20;

			if ($length >= $minLenght && $this->totalLinks <= 0) {
				return 2;
			}

			elseif ($length >= $minLenght && $this->totalLinks == 1) {
				return 1;
			}

			elseif ($length < $minLenght) {
				return - 1;
			}
		}

		private function __rateEmail($model, $data) {
			$points = 0;
			// Number of previous comments from email
			// +1 per approved, -1 per spam
			$comments = $model->Comment->find(
				'all',
				array(
					'fields' => array('Comment.id', 'Comment.status'),
					'conditions' => array(
							'Comment.' . $this->__settings[$model->alias]['column_email'] => $data[$this->__settings[$model->alias]['column_email']]
					),
					'contain' => false
				)
			);

			if (!empty($comments)) {
				foreach ($comments as $comment) {
					if ($comment['Comment']['status'] == 'spam') {
						--$points;
					}elseif ($comment['Comment']['status'] == 'approved') {
						++$points;
					}
				}
			}
			return $points;
		}

		private function __rateKeywords($model, $data) {
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

		private function __rateStartingWord($model, $data) {
			// Body starts with...
			// -10 points
			$firstWord = mb_substr(
				$data[$this->__settings[$model->alias]['column_content']],
				0,
				stripos($data[$this->__settings[$model->alias]['column_content']], ' ')
			);

			return in_array(mb_strtolower($firstWord), $this->__settings[$model->alias]['blacklist_keywords'])
				? - 10
				: 0;
		}

		private function __rateByPreviousComment($model, $data) {
			// Body used in previous comment
			// -1 per exact comment
			$previousComments = $model->Comment->find(
				'count',
				array(
					'conditions' => array(
						'Comment.' . $this->__settings[$model->alias]['column_content'] => $data[$this->__settings[$model->alias]['column_content']]
					),
					'contain' => false
				)
			);

			return $previousComments > 0 ? - $previousComments : 0;
		}

		private function __rateBody($model, $data) {
			// Random character match
			// -1 point per 5 consecutive consonants
			$consonants = preg_match_all(
				'/[^aAeEiIoOuU\s]{5,}+/i',
				$data[$this->__settings[$model->alias]['column_content']],
				$matches
			);
			$totalConsonants = count($matches[0]);

			return $totalConsonants > 0 ? - $totalConsonants : 0;
		}
	}