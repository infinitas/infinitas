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
	 * @package Infinitas.Comments
	 * @subpackage Infinitas.Comments.models.behaviors.commentable
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author Jose Diaz-Gonzalez - http://github.com/josegonzalez/cakephp-commentable-behavior
	 * @author dogmatic69
	 *
	 * @todo this code should be refactored into a spam filter lib that can be used
	 * all over (eg: email contact forms) the comment model can just check in beforeSave
	 * that it is not spam, could even be a validation rule.
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
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
			'class' => 'Comment',				// name of Comment model
			'auto_bind' => true,				// automatically bind the model to the User model (default true),
			'sanitize' => true,					// whether to sanitize incoming comments
			'column_author' => 'name',			// Column name for the authors name
			'column_content' => 'comment',		// Column name for the comments body
			'column_class' => 'class',			// Column name for the foreign model
			'column_email' => 'email',			// Column name for the authors email
			'column_website' => 'website',		// Column name for the authors website
			'column_foreign_id' => 'foreign_id',// Column name of the foreign id that links to the article/entry/etc
			'column_status' => 'status',		// Column name for automatic rating
			'column_points' => 'points',		// Column name for accrued points
			'deletion' => -10					// How many points till the comment is deleted (negative)
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
		 *
		 * @return void
		 */
		public function setup($Model, $settings = array()) {
			$default = $this->defaults;
			$default['blacklist_keywords'] = explode(',', Configure::read('Website.blacklist_keywords'));
			$default['blacklist_words'] = explode(',', Configure::read('Website.blacklist_words'));
			$default['conditions'] = array('Comment.class' => $Model->alias);
			$default['class'] = $Model->name.'Comment';

			if (!isset($this->__settings[$Model->alias])) {
				$this->__settings[$Model->alias] = $default;
			}

			$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], (array)$settings);

			$Model->bindModel(
				array(
					'hasMany' => array(
						$Model->alias.'Comment' => array(
							'className' => 'Comments.Comment',
							'foreignKey' => 'foreign_id',
							'limit' => 5,
							'order' => array(
								$Model->alias . 'Comment.created' => 'desc'
							),
							'fields' => array(
								$Model->alias . 'Comment.id',
								$Model->alias . 'Comment.class',
								$Model->alias . 'Comment.foreign_id',
								$Model->alias . 'Comment.user_id',
								$Model->alias . 'Comment.email',
								$Model->alias . 'Comment.comment',
								$Model->alias . 'Comment.active',
								$Model->alias . 'Comment.status',
								$Model->alias . 'Comment.created'
							),
							'conditions' => array(
								'or' => array(
									$Model->alias . 'Comment.active' => 1
								)
							),
							'dependent' => true
						)
					)
				),
				false
			);
		}

		/**
		 * contain the comments
		 *
		 * before a find is done, add the comments to contain so they are available
		 * in the view.
		 *
		 * @param object $Model referenced model
		 * @param array $query the query being done
		 * @access public
		 *
		 * @return array the find query data
		 */
		public function beforeFind($Model, $query) {
			if($Model->findQueryType == 'count'){
				return $query;
			}

			$query['contain'][$Model->alias.'Comment'] = array('CommentAttribute');
			if(isset($query['recursive']) && $query['recursive'] == -1){
				$query['recursive'] = 0;
			}

			call_user_func(array($Model, 'contain'), $query['contain']);
			return $query;
		}

		/**
		 * create a new comment calls the methods to do the spam checks
		 * 
		 * @param object $Model the model object
		 * @param array $data the comment being saved
		 * @access public
		 *
		 * @return bool true on save, false when not.
		 */
		public function createComment($Model, $data = array()) {
			if (empty($data[$this->__settings[$Model->alias]['class']])) {
				return false;
			}

			unset($data[$Model->alias]);
			$Model->Comment->validate = array(
				$this->__settings[$Model->alias]['column_content'] => array(
					'notempty' => array(
						'rule' => array('notempty')
					)
				),

				$this->__settings[$Model->alias]['column_email'] => array(
					'notempty' => array(
						'rule' => array('notempty')
					),
					'email' => array(
						'rule' => array('email'),
						'message' => __('Please enter a valid email address', true)
					)
				),

				$this->__settings[$Model->alias]['column_class'] => array(
					'notempty' => array(
						'rule' => array('notempty')
					)
				),

				$this->__settings[$Model->alias]['column_foreign_id'] => array(
					'notempty' => array(
						'rule' => array('notempty')
					)
				),

				$this->__settings[$Model->alias]['column_status'] => array(
					'notempty' => array(
						'rule' => array('notempty')
					)
				),
				
				$this->__settings[$Model->alias]['column_points'] => array(
					'notempty' => array(
						'rule' => array('notempty')
					),
					'numeric' => array(
						'rule' => array('numeric'),
					)
				)
			);
			
			$data[$this->__settings[$Model->alias]['class']] = $this->__rateComment($Model, $data[$this->__settings[$Model->alias]['class']]);

			if($data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_points']] < Configure::read('Comments.spam_threshold')){
				return false;
			}

			$data[$this->__settings[$Model->alias]['class']]['active'] = 0;
			if (Configure::read('Comments.auto_moderate') === true &&
				$data[$this->__settings[$Model->alias]['class']]['status'] == 'pending' ||
				$data[$this->__settings[$Model->alias]['class']]['status'] == 'approved'
			) {
				$data[$this->__settings[$Model->alias]['class']]['active'] = 1;
			}

			if ($this->__settings[$Model->alias]['sanitize']) {
				App::import('Sanitize');
				$data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_email']] =
						Sanitize::clean($data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_email']]);
				
				$data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_content']] =
						Sanitize::clean($data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_content']]);
			}
			
			$Model->{$this->__settings[$Model->alias]['class']}->create();
			if ($Model->{$this->__settings[$Model->alias]['class']}->save($data)) {
				return true;
			}

			return false;
		}

		/**
		 * gets comments 
		 *
		 * @var $Model object the model object
		 * @var $options array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to add/deduct
		 */
		public function getComments($Model, $options = array()) {
			$options = array_merge(array('id' => $Model->id, 'options' => array()), $options);
			$parameters = array();

			if (isset($options['id']) && is_numeric($options['id'])) {
				$settings = $this->__settings[$Model->alias];
				$parameters = array_merge_recursive(
					array(
						'conditions' => array(
							$settings['class'] . '.' . $settings['column_class'] => $Model->alias,
							$settings['class'] . '.foreign_id' => $options['id'],
							$settings['class'] . '.' . $settings['column_status'] => 'approved'
						)
					),
					$options['options']
				);
			}
			
			return $Model->Comment->find('all', $parameters);
		}

		/**
		 * the main method that calls all the comment rating code. after getting
		 * the score it will set a staus for the comment.
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to add/deduct
		 */
		private function __rateComment($Model, $data) {
			if (empty($data)) {
				$data[$this->__settings[$Model->alias]['column_points']] = -100;
				$data[$this->__settings[$Model->alias]['column_status']] = 'delete';
				return $data;
			}

			$points = $this->__rateLinks($Model, $data);
			$points += $this->__rateLength($Model, $data);
			$points += $this->__rateEmail($Model, $data);
			$points += $this->__rateKeywords($Model, $data);
			$points += $this->__rateStartingWord($Model, $data);
			$points += $this->__rateByPreviousComment($Model, $data);
			$points += $this->__rateBody($Model, $data);
			$data[$this->__settings[$Model->alias]['column_points']] = $points;

			if ($points >= 1) {
				$data[$this->__settings[$Model->alias]['column_status']] = 'approved';
			}

			else if ($points == 0) {
				$data[$this->__settings[$Model->alias]['column_status']] = 'pending';
			}

			else if ($points <= $this->__settings[$Model->alias]['deletion']) {
				$data[$this->__settings[$Model->alias]['column_status']] = 'delete';
			}

			else {
				$data[$this->__settings[$Model->alias]['column_status']] = 'spam';
			}
			
			return $data;
		}

		/**
		 * adds points based on the amount and length of links in the comment
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to add/deduct
		 */
		private function __rateLinks($Model, $data) {
			$links = preg_match_all(
				"#(^|[\n ])(?:(?:http|ftp|irc)s?:\/\/|www.)(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,4}(?:[-a-zA-Z0-9._\/&=+%?;\#]+)#is",
				$data[$this->__settings[$Model->alias]['column_content']],
				$matches
			);

			$links = $matches[0];

			$this->totalLinks = count($links);
			$length = mb_strlen($data[$this->__settings[$Model->alias]['column_content']]);
			// How many links are in the body
			// -1 per link if over 2, otherwise +2 if less than 2
			$maxLinks = Configure::read('Comments.maximum_links');
			$maxLinks > 0 ? $maxLinks : 2;
			
			$points = $this->totalLinks > $maxLinks
				? $this->totalLinks * -1
				: 2;
			// URLs that have certain words or characters in them
			// -1 per blacklisted word
			// URL length
			// -1 if more then 30 chars
			foreach ($links as $link) {
				foreach ($this->__settings[$Model->alias]['blacklist_words'] as $word) {
					$points = stripos($link, $word) !== false ? $points - 1 : $points;
				}

				foreach ($this->__settings[$Model->alias]['blacklist_keywords'] as $keyword) {
					$points = stripos($link, $keyword) !== false ? $points - 1 : $points;
				}

				$points = strlen($link) >= 30 ? $points - 1 : $points;
			}

			return $points;
		}

		/**
		 * Rate the length of the comment. if the length is greater than the required
		 * and there are no links then 2 points are added. with links only 1 point
		 * is added. if the lenght is too short 1 point is deducted
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to add/deduct
		 */
		private function __rateLength($Model, $data) {
			// How long is the body
			// +2 if more then 20 chars and no links, -1 if less then 20
			$length = mb_strlen($data[$this->__settings[$Model->alias]['column_content']]);

			$minLenght = Configure::read('Comments.minimum_length') > 0
				? Configure::read('Comments.minimum_length')
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

		/**
		 * Check previous comments by the same user. If they have been marked as
		 * active they get points added, if they are marked as spam points are
		 * deducted.
		 *
		 * on dogmatic69.com 95% of the spam had a number as the email address,
		 * There is hardly any people that have a number as the email address.
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to deduct
		 */
		private function __rateEmail($Model, $data) {
			$parts = explode('@', $data[$this->__settings[$Model->alias]['column_email']]);
			if(is_int($parts[0])){
				return -15;
			}
			
			$points = 0;
			$comments = $Model->{$this->__settings[$Model->alias]['class']}->find(
				'all',
				array(
					'fields' => array($this->__settings[$Model->alias]['class'].'.id', $this->__settings[$Model->alias]['class'].'.status'),
					'conditions' => array(
							$this->__settings[$Model->alias]['class'].'.' . $this->__settings[$Model->alias]['column_email'] => $data[$this->__settings[$Model->alias]['column_email']],
							$this->__settings[$Model->alias]['class'].'.active' => 1
					),
					'contain' => false
				)
			);

			foreach ($comments as $comment) {
				switch($comment[$this->__settings[$Model->alias]['class']]['status']){
					case 'approved':
						++$points;
						break;

					case 'spam':
						--$points;
						break;
				}
			}
			
			return $points;
		}

		/**
		 * Checks the text to see if it contains any of the blacklisted words.
		 * If there are, 1 point is deducted for each match.
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to deduct
		 */
		private function __rateKeywords($Model, $data) {
			$points = 0;

			foreach ($this->__settings[$Model->alias]['blacklist_keywords'] as $keyword) {
				if (stripos($data[$this->__settings[$Model->alias]['column_content']], $keyword) !== false) {
					--$points;
				}
			}

			return $points;
		}

		/**
		 * Checks the first word against the blacklist keywords. if there is a
		 * match then 10 points are deducted.
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to deduct
		 */
		private function __rateStartingWord($Model, $data) {
			$firstWord = mb_substr(
				$data[$this->__settings[$Model->alias]['column_content']],
				0,
				stripos($data[$this->__settings[$Model->alias]['column_content']], ' ')
			);

			return in_array(mb_strtolower($firstWord), $this->__settings[$Model->alias]['blacklist_keywords'])
				? - 10
				: 0;
		}

		/**
		 * Deduct points if it is a copy of any other comments in the database.
		 *
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to deduct
		 */
		private function __rateByPreviousComment($Model, $data) {
			// Body used in previous comment
			// -1 per exact comment
			$previousComments = $Model->{$this->__settings[$Model->alias]['class']}->find(
				'count',
				array(
					'conditions' => array(
						$this->__settings[$Model->alias]['class'] . '.' . $this->__settings[$Model->alias]['column_content'] => $data[$this->__settings[$Model->alias]['column_content']]
					),
					'contain' => false
				)
			);

			return 0 - $previousComments;
		}

		/**
		 * Rate according to the text. Generaly words do not contain more than
		 * a few consecutive consonants. -1 point is given per 5 consecutive
		 * consonants.
		 * 
		 * @var $Model object the model object
		 * @var $data array the data from the form
		 * @access private
		 *
		 * @return int the amout of points to deduct
		 */
		private function __rateBody($Model, $data) {
			$consonants = preg_match_all(
				'/[^aAeEiIoOuU\s]{5,}+/i',
				$data[$this->__settings[$Model->alias]['column_content']],
				$matches
			);

			return 0 - count($matches[0]);
		}
	}