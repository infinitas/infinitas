<?php
    /**
     *
     *
     */
    class CmsAppController extends AppController
    {
        var $helpers = array(
            // cake
            'Time', 'Html', 'Form',

            //core
            'Cms.Cms'
        );

        var $components = array(
            'Filter.Filter' => array(
                'actions' => array('admin_index')
            )
        );

        function beforeFilter()
        {
            parent::beforeFilter();
        }
    }
?>