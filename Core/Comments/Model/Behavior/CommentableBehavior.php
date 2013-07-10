<?php
/**
 * CommentableBehavior
 *
 * @package Infinitas.Comments.Model.Behavior
 */

App::uses('ModelBehavior', 'Model');
App::uses('SpamRating', 'Contents.Lib');

/**
 * CommentableBehavior
 *
 * Allows you to attach a comment to any model in your application
 * Moderates/Validates comments to check for spam.
 * Validates comments based on a point system. High points is an automatic approval,
 * where as low points is marked as spam or deleted.
 *
 * Based on Jonathan Snooks outline.
 *
 * @copyright Stoop Dev
 * @link http://github.com/josegonzalez/cakephp-commentable-behavior
 * @package Infinitas.Comments.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Jose Diaz-Gonzalez - http://github.com/josegonzalez/cakephp-commentable-behavior
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class CommentableBehavior extends ModelBehavior {

/**
 * Settings initialized with the behavior
 *
 * @var array
 */
	public $defaults = array(
		'plugin' => 'Comments',
		'class' => 'InfinitasComment',				// name of Comment model
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
 */
	private $__settings = array();

/**
 * Initiate behaviour for the model using settings.
 *
 * @param object $Model Model using the behaviour
 * @param array $settings Settings to override for model.
 *
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		$default = $this->defaults;
		$default['blacklist_keywords'] = explode(',', Configure::read('Website.blacklist_keywords'));
		$default['blacklist_words'] = explode(',', Configure::read('Website.blacklist_words'));
		$default['conditions'] = array('Comment.class' => $Model->alias);
		$default['class'] = $Model->name . 'Comment';

		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $default;
		}

		$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], (array)$settings);

		$Model->bindModel(array(
			'hasMany' => array(
				$this->__settings[$Model->alias]['class'] => array(
					'className' => 'Comments.InfinitasComment',
					'foreignKey' => 'foreign_id',
					'limit' => 5,
					'order' => array(
						$this->__settings[$Model->alias]['class'] . '.created' => 'desc'
					),
					'fields' => array(
						$this->__settings[$Model->alias]['class'] . '.id',
						$this->__settings[$Model->alias]['class'] . '.class',
						$this->__settings[$Model->alias]['class'] . '.foreign_id',
						$this->__settings[$Model->alias]['class'] . '.user_id',
						$this->__settings[$Model->alias]['class'] . '.email',
						$this->__settings[$Model->alias]['class'] . '.comment',
						$this->__settings[$Model->alias]['class'] . '.active',
						$this->__settings[$Model->alias]['class'] . '.status',
						$this->__settings[$Model->alias]['class'] . '.created'
					),
					'conditions' => array(
						'or' => array(
							$this->__settings[$Model->alias]['class'] . '.active' => 1
						)
					),
					'dependent' => true
				)
			)
		), false);

		$commentClass = $this->__settings[$Model->alias]['class'];
		$counterScope = array();
		if ($Model->hasField('active')) {
			$counterScope[$Model->{$commentClass}->fullFieldName('active')] = true;
		}
		$Model->{$commentClass}->bindModel(array(
			'belongsTo' => array(
				$Model->alias => array(
					'className' => $Model->fullModelName(),
					'foreignKey' => 'foreign_id',
					'conditions' => array(
						$commentClass . '.class' => $Model->fullModelName()
					),
					'counterCache' => $Model->hasField('comment_count') ? 'comment_count' : false,
					'counterScope' => $counterScope
				)
			)
		), false);

		$Model->Comment = $Model->{$this->__settings[$Model->alias]['class']};
	}

	public function attachComments(Model $Model, $results) {
		$ids = Set::extract('/' . $Model->alias . '/' . $Model->primaryKey, $results);
		$comments = $Model->{$this->__settings[$Model->alias]['class']}->find(
			'linkedComments',
			array(
				'conditions' => array(
					$this->__settings[$Model->alias]['class'] . '.foreign_id' => $ids
				)
			)
		);

		foreach ($results as &$result) {
			$result[$this->__settings[$Model->alias]['class'] . ''] = Set::extract(
				sprintf('/%s[foreign_id=%s]', $this->__settings[$Model->alias]['class'], $result[$Model->alias][$Model->primaryKey]),
				$comments
			);

			$result[$this->__settings[$Model->alias]['class'] . ''] = Set::extract('{n}.' . $this->__settings[$Model->alias]['class'], $comments);
		}

		return $results;
	}

/**
 * create a new comment calls the methods to do the spam checks
 *
 * @param object $Model the model object
 * @param array $data the comment being saved
 *
 * @return boolean
 */
	public function createComment(Model $Model, $data = array()) {
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
					'message' => __d('comments', 'Please enter a valid email address')
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
		$SpamRating = new SpamRating(array(
			'content' => 'comment',
			'column_email' => 'email',
			'model' => $Model->{$this->__settings[$Model->alias]['class']},
		));
		
		$data[$this->__settings[$Model->alias]['class']] = array_merge(
			$data[$this->__settings[$Model->alias]['class']], 
			$SpamRating->outcome($data[$this->__settings[$Model->alias]['class']])
		);


		if ($this->__settings[$Model->alias]['sanitize']) {
			App::import('Sanitize');
			$data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_email']] =
					Sanitize::clean($data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_email']]);

			$data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_content']] =
					Sanitize::clean($data[$this->__settings[$Model->alias]['class']][$this->__settings[$Model->alias]['column_content']]);
		}

		$data[$this->__settings[$Model->alias]['class']]['class'] = $Model->fullModelName();
		$Model->{$this->__settings[$Model->alias]['class']}->create();
		$saved = $Model->{$this->__settings[$Model->alias]['class']}->save($data);
		if ($saved) {
			return $saved;
		}

		return false;
	}

/**
 * gets comments
 *
 * @param Model $Model object the model object
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function getComments(Model $Model, $options = array()) {
		$options = array_merge(array('id' => $Model->id, 'options' => array()), $options);
		$parameters = array();

		if (isset($options['id']) && is_numeric($options['id'])) {
			$settings = $this->__settings[$Model->alias];
			$parameters = array_merge_recursive(array(
				'conditions' => array(
					$settings['class'] . '.' . $settings['column_class'] => $Model->alias,
					$settings['class'] . '.foreign_id' => $options['id'],
					$settings['class'] . '.' . $settings['column_status'] => 'approved'
				)
			), $options['options']);
		}

		return $Model->{$this->__settings[$Model->alias]['class']}->find('all', $parameters);
	}

}