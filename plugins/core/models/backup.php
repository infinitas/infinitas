<?php
    /**
     *
     *
     */
    class Backup extends CoreAppModel
    {
        var $name = 'Backup';

        var $last_id = 0;
        /**
         * Constructor
         * @access protected
         */
        function getLastBackup( $model = null, $plugin = null )
        {
            $lastBackup = $this->find(
                'first',
                array(
                    'fields' => array(
                        'Backup.last_id'
                    ),
                    'conditions' => array(
                        'Backup.plugin' => $plugin,
                        'Backup.model' => $model
                    ),
                    'order' => array(
                        'Backup.id' => 'DESC'
                    )
                )
            );

            if ( !empty( $lastBackup ) )
            {
                $this->last_id = $lastBackup['Backup']['last_id'];
            }

            return $this->last_id;
        }

        function getRecordsForBackup( $Model )
        {
            return $Model->find(
                'all',
                array(
                    'conditions' => array(
                        $Model->name.'.id > ' => $this->last_id
                    ),
                    'contain' => false
                )
            );
        }
    }
?>