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

    class CoreConfigComponent extends Object
    {
        function initialize( &$controller )
        {
            $this->controller = $controller;

            $configs = ClassRegistry::init( 'Management.Config' )->getConfig();

            foreach( $configs as $config )
            {
                Configure::write( $config['Config']['key'], $config['Config']['value'] );
            }
        }
    }
?>