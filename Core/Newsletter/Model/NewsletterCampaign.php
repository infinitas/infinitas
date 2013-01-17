<?php
/**
 * Campaign
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * Campaign
 *
 * @package Infinitas.Newsletter.Model
 *
 * @property Newsletter $Newsletter
 * @property NewsletterTemplate $NewsletterTemplate
 */

class NewsletterCampaign extends NewsletterAppModel {

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'paginated' => true,
		'active' => true
	);

/**
 * Make the model lockable
 *
 * @var boolean
 */
	public $lockable = false;

/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMany = array(
		'Newsletter' => array(
			'className' => 'Newsletter.Newsletter'
		),
		'NewsletterSubscription' => array(
			'className' => 'Newsletter.NewsletterSubscription'
		)
	);

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'NewsletterTemplate' => array(
			'className' => 'Newsletter.NewsletterTemplate'
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
			$this->alias . '.name' => 'asc'
		);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please enter the name of this campaign')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('newsletter', 'There is already a campaign with that name')
				)
			),
			'template_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('newsletter', 'Please select the default template for this campaign')
				)
			)
		);
	}

/**
 * custom paginated find for campaigns
 *
 * @param string $state before or after the find
 * @param array $query the find query
 * @param array $results rows found
 *
 * @return string
 */
	protected function _findPaginated($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField,
				$this->alias . '.description',
				$this->alias . '.newsletter_count',
				$this->alias . '.active',
				$this->alias . '.created',
				$this->alias . '.modified',

				$this->NewsletterTemplate->alias . '.' . $this->NewsletterTemplate->primaryKey,
				$this->NewsletterTemplate->alias . '.' . $this->NewsletterTemplate->displayField,
			));

			$query['joins'][] = $this->autoJoinModel($this->NewsletterTemplate);

			return $query;
		}

		return $results;
	}

/**
 * Find active campaigns
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return boolean
 */
	protected function _findActive($state, array $query, array $results = array()) {
		if ($state === 'before') {
			$query = self::_findPaginated($state, $query);

			$query['conditions'][$this->alias . '.active'] = true;

			return $query;
		}

		return self::_findPaginated($state, $query, $results);
	}

}