<?php
/**
 * The Branch model.
 *
 * CRUD for database of Contact branches
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contact.models
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Branch extends ContactAppModel {
/**
 * hasMany related models
 *
 * @var array
 */
	public $hasMany = array(
		'Contact.Contact'
	);

/**
 * belongsTo related models
 *
 * @var array
 */
	public $belongsTo = array(
		'Contact.ContactAddress'
	);


/**
 * belongsTo associations
 *
 * @var array
 */
	public $actsAs = array(
		'Filemanager.Upload' => array(
			'image' => array(
				'thumbnailSizes' => array(
					'large' => '1000l',
					'medium' => '600l',
					'small' => '300l',
					'thumb' => '75l'
				)
			)
		)
	);

	/**
	 * @copydoc AppModel::__construct()
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('contact', 'Please enter the name of your branch')
				),
			),
			'address' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('contact', 'Please enter the branches address')
				)
			),
			'phone' => array(
				'phone' => array(
					'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), // @todo Configure::read('Website.phone_regex')),
					'message' => __d('contact', 'The number does not seem to be valid'),
					'allowEmpty' => true
				)
			),
			'fax' => array(
				'phone' => array(
					'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), // @todo Configure::read('Website.phone_regex')),
					'message' => __d('contact', 'Please enter a valid fax number'),
					'allowEmpty' =>  true
				)
			),
			'time_zone_id' => array(
				'comparison' => array(
					'rule' => array('comparison', '>', 0),
					'message' => __d('contact', 'Please select your time zone')
				)
			)
		);
	}

/**
 * BeforeFind callback
 * 
 * @todo list all the time zones so that the current time can be shown
 * of different branches.
 *
 * @param array $queryData the find data
 *
 * @return boolean
 */
	public function beforeFind($queryData) {
		return true;
	}
}