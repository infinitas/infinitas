<?php
    class Section extends CmsAppModel
    {
    	var $name = 'Section';

        var $actsAs = array(
            'Core.Sluggable',
            'Core.Viewable',
            'Core.Ordered'
        );

    	var $validate = array(
    		'title' => array(
    			'notempty' => array('rule' => array('notempty')),
    		),
    		'description' => array(
    			'notempty' => array('rule' => array('notempty')),
    		),
    		'active' => array(
    			'boolean' => array('rule' => array('boolean')),
    		),
    	);
    	//The Associations below have been created with all possible keys, those that are not needed can be removed

    	var $hasMany = array(
    		'Cms.Category'
    	);

        var $belongsTo = array(
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
    }
?>