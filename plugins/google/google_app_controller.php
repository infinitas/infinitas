<?php
    App::import(
        array(
            'type' => 'File',
            'name' => 'Google.GoogleConfig',
            'file' => 'config'. DS .'setup.php'
        )
    );

    /**
     * Google App Controller class file.
     *
     * the google_appcontroller file, extends AppController
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       google
     * @subpackage    google.controllers.google_app_controller
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    class GoogleAppController extends AppController
    {
        function beforeFilter()
        {
            parent::beforeFilter();

            $GoogleConfig = new GoogleConfig();
        }
    }
?>