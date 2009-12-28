<?php
    /**
     * Blog Comment Model class file.
     *
     * This is the main model for Blog Comments. There are a number of
     * methods for getting the counts of all comments, active comments, pending
     * comments etc.  It extends {@see BlogAppModel} for some all round
     * functionality. look at {@see BlogAppModel::afterSave} for an example
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       blog
     * @subpackage    blog.models.comment
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    class Comment extends CoreAppModel
    {
        var $name = 'Comment';

        var $validate = array(
    		'name' => array(
    		    'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Please enter your name'
                )
    		),
    		'email' => array(
    		    'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Please enter your email address'
                ),
                'email' => array(
                    'rule' => array( 'email' ),
                    'message' => 'Please enter a valid email address'
                )
    		),
    		'comment' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter your comments'
            )
    	);

        function getCounts( $class = null )
        {
            if ( !$class )
            {
                return false;
            }

            $counts = Cache::read( 'comments_count_'.$class );
            if ( $counts !== false )
            {
                return $counts;
            }

            $counts['active'] = $this->find(
                'count',
                array(
                    'conditions' => array(
                        'Comment.active' => 1,
                        'Comment.class' => $class
                    ),
                    'contain' => false
                )
            );
            $counts['pending'] = $this->find(
                'count',
                array(
                    'conditions' => array(
                        'Comment.active' => 0,
                        'Comment.class' => $class
                    ),
                    'contain' => false
                )
            );

            Cache::write( 'comments_count_'.$class, $counts, 'blog' );

            return $counts;
        }
    }
?>