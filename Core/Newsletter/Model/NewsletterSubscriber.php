<?php
/**
 * NewsletterSubscriber
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * NewsletterSubscriber
 *
 * @package Infinitas.Newsletter.Model
 *
 * @property User $User
 * @property NewsletterSubscription $NewsletterSubscription
 */

class NewsletterSubscriber extends NewsletterAppModel {

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'paginated' => true
	);

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id'
		)
	);

/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMany = array(
		'NewsletterSubscription' => array(
			'className' => 'Newsletter.NewsletterSubscription'
		)
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->virtualFields = array(
			'subscriber_name' => 'CASE WHEN COALESCE(User.username, \'\') = \'\' THEN ' . $this->alias . '.prefered_name else User.username end',
			'subscriber_email' => 'CASE WHEN COALESCE(User.email, \'\') = \'\' THEN ' . $this->alias . '.email else User.email end'
		);
	}

/**
 * BeforeFind callback
 *
 * @param array $queryData the query array
 *
 * @return array
 */
	public function beforeFind($queryData) {
		$queryData['fields'] = array_merge((array)$queryData['fields'], array(
			$this->alias . '.*',
			$this->User->alias . '.*',
		));

		$queryData['joins'][] = $this->autoJoinModel($this->User);

		return $queryData;
	}

/**
 * Paginated find method
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findPaginated($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['fields'] = array_merge((array)$query['fields'], array(

			));

			return $query;
		}

		return $results;
	}

/**
 * Check if the supplied details are already subscribed
 *
 * @param array|string $data the users email or an array containing email key
 *
 * @return boolean
 */
	public function isSubscriber($data) {
		if (is_array($data)) {
			if (!empty($data[$this->alias])) {
				$data = $data[$this->alias];
			}

			if(!empty($data['email'])) {
				$data = $data['email'];
			}
		}

		return (boolean)$this->find('count', array(
			'conditions' => array(
				$this->alias . '.email' => $data
			)
		));
	}

/**
 * Subscribe to newsletters
 *
 * @param array $data the subscription details
 *
 * @return array
 */
	public function subscribe(array $data) {
		if (!empty($data[$this->alias])) {
			$data = $data[$this->alias];
		}

		$data['active'] = false;
		$data['confimed'] = null;

		$this->create();
		return $this->save($data);
	}

	public function updateUserDetails(array $user) {
		return true;
	}
}