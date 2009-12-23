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

        function beforeFilter()
        {
            parent::beforeFilter();
        }
    }
?>