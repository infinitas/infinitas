<?php
    class ContentFrontpage extends CmsAppModel
    {
    	var $name = 'ContentFrontpage';
    	var $primaryKey = 'content_id';
    	var $displayField = 'content_id';

    	var $belongsTo = array(
            'Cms.Content',
            'Core.Group'
    	);

        var $actsAs = array(
            'Core.Ordered'
        );
    }
?>