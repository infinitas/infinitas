<?php
	/**
	* Cms content model
	*
	* The Content Model sets up relations for the content items.  Some of the
	* relations are users, configs and categories.  Many users can be related
	* to one content item as there is the person that last edited it, the person
	* busy editing (locker) and the creator.
	*
	* content is always sroted by the ordering field, but can be changed in the
	* backend by clicking one of the sortable links.
	*
	* Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	*
	* @filesource
	* @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	* @link http://www.dogmatic.co.za
	* @package cms
	* @subpackage cms.models.content
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5
	*
	* @author dogmatic69
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	*/

	class Content extends CmsAppModel {
		var $name = 'Content';

		var $order = array(
			'Content.category_id' => 'ASC',
			'Content.ordering' => 'ASC'
			);

		var $validate = array(
			'title' => array(
				'notempty' => array('rule' => array('notempty')),
				),
			);

		var $actsAs = array(
			'Libs.Sluggable',
			'Libs.Viewable'
			);

		var $belongsTo = array(
			'Author' => array(
				'className' => 'core.User',
				'foreignKey' => 'created_by',
				'fields' => array(
					'Author.id',
					'Author.username'
				)
			),
			'Editor' => array(
				'className' => 'core.User',
				'foreignKey' => 'modified_by',
				'fields' => array(
					'Editor.id',
					'Editor.username'
				)
			),
			'Category' => array(
				'className' => 'Cms.Category',
				'counterCache' => true,
				'fields' => array(
					'Category.id',
					'Category.title'
				)
			),
			'Group' => array(
				'className' => 'Core.Group',
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			),
			'Locker' => array(
				'className' => 'Core.User',
				'foreignKey' => 'locked_by',
				'fields' => array(
					'Locker.id',
					'Locker.username'
				)
			),
			'Layout' => array(
				'className' => 'Cms.ContentLayout',
				'foreignKey' => 'layout_id',
				'counterCache' => true
			)
		);

		var $hasOne = array(
			'ContentConfig' => array(
				'className' => 'Cms.ContentConfig'
			),
			'Feature' => array(
				'className' => 'Cms.Feature',
				'fields' => array(
					'Feature.id'
				)
			),
			'Frontpage' => array(
				'className' => 'Cms.Frontpage',
				'fields' => array(
					'Frontpage.id'
				)

			)
		);
	}
?>