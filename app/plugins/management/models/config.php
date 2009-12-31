<?php
    /**
     *
     *
     */
    class Config extends ManagementAppModel
    {
        var $name = 'Config';

        var $tablePrefix = 'core_';

        var $order = array(
            'Config.core' => 'DESC',
            'Config.key' => 'ASC'
        );

        function getConfig()
        {
            $configs = Cache::read( 'core_configs' );
            if ( $configs !== false )
            {
                return $configs;
            }

            $configs = $this->find(
                'all',
                array(
                    'fields' => array(
                        'Config.key',
                        'Config.value',
                        'Config.type'
                    )
                )
            );

            foreach( $configs as $k => $config )
            {
                switch( $configs[$k]['Config']['type'] )
                {
                    case 'bool':
                        switch( $configs[$k]['Config']['value'] )
                        {
                            case 'true':
                                $configs[$k]['Config']['value'] = true;
                                break;

                            case 'false':
                                $configs[$k]['Config']['value'] = false;
                                break;
                        } // switch
                        break;

                    case 'string':
                        $configs[$k]['Config']['value'] = (string)$configs[$k]['Config']['value'];
                        break;

                    case 'integer':
                        $configs[$k]['Config']['value'] = (int)$configs[$k]['Config']['value'];
                        break;
                } // switch
            }

            Cache::write( 'core_configs', $configs, 'core' );

            return $configs;
        }

        function afterSave( $created )
        {
            parent::afterSave( $created );

            $this->__clearCache();
            return true;
        }

        function afterDelete()
        {
            parent::afterDelete();

            $this->__clearCache();
            return true;
        }

        private function __clearCache()
        {
            if ( is_file( CACHE.'core'.DS.'core_configs' ) )
            {
                unlink( CACHE.'core'.DS.'core_configs' );
            }

            return true;
        }
    }
?>