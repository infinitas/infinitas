<?php
	/**
	 *
	 *
	 */
	class Layout extends CmsAppModel{
		var $name = 'Layout';

		var $useTable = 'content_layouts';

		var $hasMany = array(
			'Content' => array(
				'className' => 'Cms.Content',
				'foreignKey' => 'layout_id',
			),
		);

		var $belongsTo = array(
			'Locker' => array(
				'className' => 'Core.User',
				'foreignKey' => 'locked_by',
				'fields' => array(
					'Locker.id',
					'Locker.username'
				)
			)
		);
	}
?>