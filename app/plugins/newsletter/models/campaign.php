<?php
    /**
     *
     *
     */
    class Campaign extends NewsletterAppModel
    {
        var $name = 'Campaign';

        var $order = array( 'Campaign.name' => 'ASC' );

        var $hasMany = array(
            'Newsletter.Newsletter'
        );

        var $belongsTo = array(
            'Newsletter.Template',
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