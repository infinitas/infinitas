<?php
    class AppController extends Controller
    {
        var $helpers = array( 'Html' );

        var $components = array( 'Session' );

        function beforeFilter()
        {
            parent::beforeFilter();
        }

    }
?>