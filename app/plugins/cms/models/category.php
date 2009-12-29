<?php
    class Category extends CmsAppModel
    {
    	var $name = 'Category';

        var $actsAs = array(
            'Core.Sluggable',
            'Core.Viewable',
            'Core.Ordered' => array(
                'foreign_key' => 'section_id'
            )
        );

        var $order = array(
            'Category.section_id' => 'ASC',
            'Category.ordering' => 'ASC'
        );

    	var $validate = array(
    		'title' => array(
    			'notempty' => array('rule' => array('notempty')),
    		),
    	);

    	var $belongsTo = array(
    		'Section' => array(
        		'className' => 'Cms.Section',
                'counterCache' => true
            ),
            'Core.Group',
    		'Locker' => array(
    			'className' => 'Core.User',
    			'foreignKey' => 'locked_by',
    			'conditions' => '',
    			'fields' => array(
        			'Locker.id',
        			'Locker.username'
                ),
    			'order' => ''
    		)
    	);

    	var $hasMany = array(
    		'Cms.Content'
        );
    }
?>