<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    class Section extends CmsAppModel
    {
    	var $name = 'Section';

        var $actsAs = array(
            'Core.Sluggable',
            'Core.Viewable',
            'Core.Ordered'
        );

        var $order = array(
            'Section.ordering' => 'ASC'
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