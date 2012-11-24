<?php
/**
 * Trash
 *
 * @package Infinitas.Trash.Model
 */

/**
 * Trash
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Trash.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 * @author dakota
 */

class Trash extends TrashAppModel {
/**
 * Custom table
 *
 * @var string
 */
	public $useTable = 'trash';

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'deleted_by',
			'fields' => array(
				'User.id',
				'User.username'
			)
		)
	);

/**
 * Restor an item from the trash
 *
 * @param string $ids the ids to restore
 *
 * @return array
 */
	public function restore($ids) {
		$trashed = $this->find('all', array('conditions' => array('id' => $ids)));

		$result = true;
		foreach ($trashed as $trash) {
			$data = unserialize($trash['Trash']['data']);

			$model = ClassRegistry::init($trash['Trash']['model']);
			if ($model) {
				$model->create();
				$result = $result && $model->save($data);
				$this->delete($trash['Trash']['id']);
			}
		}

		return $result;
	}

}