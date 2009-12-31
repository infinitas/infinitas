<?php
    /**
     *
     *
     */
    class Template extends NewsletterAppModel
    {
        var $name = 'Template';

        var $order = array( 'Template.name' => 'ASC' );

        var $validation = array(
            'name' => array(
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'A template with that name already exists.'
                )
            )
        );

        var $hasMany = array(
            'Newsletter.Newsletter',
            'Newsletter.Campaign'
        );

        var $belongsTo = array(
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