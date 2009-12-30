<?php
    /**
     * Management Config admin edit post.
     *
     * this page is for admin to manage the setup of the site
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       management
     * @subpackage    management.views.configs.admin_edit
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    echo $this->Core->adminOtherHead( $this );
    echo $this->Form->create( 'Config', array( 'action' => 'edit' ) );
        echo $this->Form->input( 'id' );
        echo $this->Form->input( 'key', array( 'readonly' => true ) );

        //echo $this->Form->input( 'type' );

        switch( $this->data['Config']['type'] )
        {
            case 'bool':
                $_label = explode( '.', $this->data['Config']['key'] );
                $label = ( isset( $_label[1] ) ? $_label[1] : $_label[0] );
                $this->data['Config']['value'] = ( $this->data['Config']['value'] == 'true' ) ? '1' : '0';
                echo $this->Form->input(
                    'value',
                    array(
                        'type' => 'checkbox',
                        'label' => Inflector::humanize( $label )
                    )
                );
                break;

            case 'dropdown':
                $_options = explode( ',', $this->data['Config']['options'] );
                foreach( $_options as $o )
                {
                    $options[$o] = $o;
                }
                echo $this->Form->input(
                    'value',
                    array(
                        'type' => 'select',
                        'options' => $options,
                        'selected' => $this->data['Config']['value']
                    )
                );
                break;

            case 'integer':
            case 'string':
                echo $this->Form->input( 'value' );
                break;
        } // switch

        //echo $this->Form->input( 'options' );
        echo $this->Form->input( 'core', array( 'disabled' => true ) );
        echo $this->Core->wysiwyg( 'Config.description' );
    echo $this->Form->end( 'Save Configuration' );
?>