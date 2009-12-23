<?php
    class ConfigsController extends ManagementAppController
    {
        var $name = 'Configs';

        function admin_index()
        {
            $configs = $this->Config->find(
                'all'
            );

            $this->set( compact( 'configs' ) );
        }

    }
?>