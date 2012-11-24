<?php
/**
 * Campaign
 *
 * @package Infinitas.Newsletter.Model
 */

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

class Campaign extends NewsletterAppModel {
/**
 * Make the model lockable
 *
 * @var boolean
 */
	public $lockable = true;

/**
 * Data order
 *
 * @var array
 */
	public $order = array(
		'Campaign.name' => 'asc'
	);

/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMany = array(
		'Newsletter.Newsletter'
	);

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Newsletter.Template'
	);

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'paginated' => true
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
			if(!is_array($query['fields'])) {
				$query['fields'] = array($query['fields']);
			}

			$query['fields'] = array_merge(
				$query['fields'],
				array(
					'Campaign.id',
					'Campaign.name',
					'Campaign.description',
					'Campaign.newsletter_count',
					'Campaign.active',
					'Campaign.created',
					'Campaign.modified',
					'Template.id',
					'Template.name',
				)
			);

			$query['joins'][] = array(
				'table' => 'newsletter_templates',
				'alias' => 'Template',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'Template.id = Campaign.template_id'
				)
			);

			return $query;
		}

		return $results;
	}

}