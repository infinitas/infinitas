<?php
    class Content extends CmsAppModel
    {
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
            'Core.Sluggable',
            'Core.Viewable',
            'Core.Ordered' => array(
                'foreign_key' => 'category_id'
            )
        );

    	var $belongsTo = array(
            'Category' => array(
                'className' => 'Cms.Category',
                'counterCache' => true
            ),
            'Core.Group'
    	);

    	var $hasMany = array(
    		'Cms.ContentFrontpage'
    	);

    }
?>