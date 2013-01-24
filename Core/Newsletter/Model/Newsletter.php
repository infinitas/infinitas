<?php

/**
 * Newsletter
 *
 * This is the main model for Newsletter Newsletters. There are a number of
 * methods for getting the counts of all posts, active posts, pending
 * posts etc.  It extends {@see NewsletterAppModel} for some all round
 * functionality. look at {@see NewsletterAppModel::afterSave} for an example
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 *
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * Newsletter
 *
 * @package Infinitas.Newsletter.Model
 *
 * @property NewsletterCampaign $NewsletterCampaign
 * @property NewsletterTemplate $NewsletterTemplate
 */

class Newsletter extends NewsletterAppModel {

/**
 * Custom prefix
 */
	public $tablePrefix = 'newsletter_';

/**
 * For generating lists due to not being convention of name|title
 */
	public $displayField = 'subject';

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'toSend' => true,
		'paginated' => true,
		'preview' => true,
		'deleteable' => true,
		'email' => true
	);

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'NewsletterCampaign' => array(
			'className' => 'Newsletter.NewsletterCampaign',
			'foreignKey' => 'newsletter_campaign_id',
			'counterCache' => 'newsletter_count',
		),
		'NewsletterTemplate' => array(
			'className' => 'Newsletter.NewsletterTemplate',
			'counterCache' => 'newsletter_count',
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

		$this->order = array(
			$this->alias . '.slug' => 'asc'
		);

		$this->validate = array(
			'newsletter_campaign_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please select the campaign this email belongs to')
				)
			),
			'newsletter_template_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please select the template this email will use')
				)
			),
			'from' => array(
				'email' => array(
					'rule' => array('email', true),
					'message' => __d('newsletter', 'Please enter a valid email addres'),
					'allowEmpty' => true
				)
			),
			'reply_to' => array(
				'email' => array(
					'rule' => array('email', true),
					'message' => __d('newsletter', 'Please enter a valid email addres'),
					'allowEmpty' => true
				)
			),
			'subject' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please enter the subject of this newsletter')
				)
			),
			'html' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please enter the html version of your email')
				)
			),
			'text' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please enter the text version of your email')
				)
			)
		);
	}

/**
 * General find
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findPaginated($state, array $query, array $results = array()) {
		if ($state == 'before') {
			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.plugin',
				$this->alias . '.slug',
				$this->alias . '.newsletter_campaign_id',
				$this->alias . '.from',
				$this->alias . '.reply_to',
				$this->alias . '.subject',
				$this->alias . '.active',
				$this->alias . '.sent',
				$this->alias . '.created',

				$this->NewsletterCampaign->alias . '.' . $this->NewsletterCampaign->displayField
			));

			$query['joins'] = (array)$query['joins'];
			$query['joins'][] = $this->autoJoinModel($this->NewsletterCampaign);

			if (empty($query['order'])) {
				$query['order'] = array(
					$this->NewsletterCampaign->alias . '.' . $this->NewsletterCampaign->displayField,
					$this->alias . '.plugin' => 'asc',
					$this->alias . '.slug' => 'asc'
				);
			}
			return $query;
		}

		return $results;
	}

/**
 * Find only active newsletters
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findActive($state, array $query, array $results = array()) {
		if ($state == 'before') {
			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.id',
				$this->alias . '.html',
				$this->alias . '.text',
				$this->alias . '.sends',

				$this->NewsletterTemplate->fullFieldName('header'),
				$this->NewsletterTemplate->fullFieldName('footer'),
			));

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.sent' => 0,
				$this->alias . '.active' => 1,
			));

			$query['joins'] = (array)$query['joins'];
			$query['joins'][] = $this->autoJoinModel($this->NewsletterTemplate);

			return $query;
		}
		if (empty($results)) {
			return array();
		}

		foreach ($results as &$result) {
			$result['User'] = array();
		}
		return $results;
	}

/**
 * Find preview data
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 *
 * @throws InvalidArgumentException
 */
	protected function _findPreview($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				throw new InvalidArgumentException(__d('newsletter', 'Invalid newsletter selected'));
			}

			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.html',

				$this->NewsletterTemplate->fullFieldName('header'),
				$this->NewsletterTemplate->fullFieldName('footer')
			));
			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.' . $this->primaryKey => $query[0]
			));

			$query['joins'] = (array)$query['joins'];
			$query['joins'][] = $this->autoJoinModel($this->NewsletterTemplate);

			$query['limit'] = 1;
			return $query;
		}

		if (empty($results[0])) {
			return array();
		}

		return $results[0];
	}

/**
 * Get newsletters that can be deleted safely
 *
 * This method takes list of `ids` and returns a list of ids that can be safely removed.
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 *
 * @throws InvalidArgumentException
 */
	protected function _findDeleteable($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query['ids'])) {
				throw new InvalidArgumentException(__d('newsletter', 'No newsletters selected'));
			}

			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey,
			);
			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.sent' => 0,
				$this->alias . '.sends > ' => 0,
				$this->alias . '.' . $this->primaryKey => $query['ids']
			));

			return $query;
		}

		$results = Hash::extract($results, '{n}.' . $this->alias . '.' . $this->primaryKey);
		if (empty($results)) {
			throw new CakeException(__d('newsletter', 'Nothing to delete'));
		}

		return $results;
	}

	protected function _findEmail($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				throw new InvalidArgumentException(__d('newsletter', 'No email selected'));
			}

			if (strstr('.', $query[0])) {
				throw new InvalidArgumentException(__d('newsletter', 'Invalid newsletter "%s"'));
			}


			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.from',
				$this->alias . '.reply_to',
				$this->alias . '.subject',
				$this->alias . '.html',
				$this->alias . '.text',

				$this->NewsletterTemplate->alias . '.header',
				$this->NewsletterTemplate->alias . '.footer',
			));

			list ($plugin, $email) = pluginSplit($query[0]);
			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.plugin' => $plugin,
				$this->alias . '.slug' => $email
			));

			$query['joins'] = (array)$query['joins'];
			$query['joins'][] = $this->autoJoinModel($this->NewsletterTemplate);

			$query['limit'] = 1;
			return $query;
		}
		if (empty($results[0])) {
			throw new CakeException(__d('newsletter', 'No email found'));
		}

		return $results[0];
	}

/**
 * toggle the send status
 *
 * @param string $id the newsletter id to toggle
 *
 * @return boolean
 *
 * @throws InvalidArgumentException
 */
	public function toggleSend($id) {
		$sent = $this->find('first', array(
			'fields' => array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.sent',
				$this->alias . '.active'
			),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $id
			)
		));

		if (empty($sent[$this->alias][$this->primaryKey])) {
			throw new InvalidArgumentException(__d('newsletter', 'The newsletter was not found'));
		}

		if ($sent[$this->alias]['sent']) {
			throw new InvalidArgumentException(__d('newsletter', 'The newsletter has already been sent'));
		}

		if (!$sent[$this->alias]['active']) {
			$sent[$this->alias]['active'] = 1;
			return $this->save($sent[$this->alias]);
		}

		throw new InvalidArgumentException(__d('newsletter', 'The newsletter is already being sent'));
	}

/**
 * Stop all current email sending
 *
 * @return array
 */
	public function stopAllSending() {
		return $this->updateAll(
			array($this->alias . '.active = "0"'),
			array(
				$this->alias . '.active = "1"',
				$this->alias . '.sent = "0"'
			)
		);
	}
}