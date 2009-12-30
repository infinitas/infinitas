<?php
    /**
     *
     *
     */
    class CoreConfigComponent extends Object
    {
        function startup( &$controller )
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