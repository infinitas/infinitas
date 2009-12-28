<div id="comment-box">
	<?php
        /**
         * fields allowed in the comments
         */
        $commentFields = array(
            'name',
            'email',
            'website',
            'comment'
        );

	    $action = ( isset( $action ) ) ? $action : 'comment';
        $modelName = ( isset( $modelName ) ) ? $modelName : Inflector::singularize( $this->name );

        if ( isset( $urlParams ) )
        {
            echo $form->create(
                $modelName,
                array(
                	'url' => array(
                		'plugin' => $this->params['plugin'],
                		'controller' => $this->params['controller'],
                		'action' => $action,
                		$urlParams
                	)
                )
            );
        }

        else
        {
            echo $form->create(
                $modelName,
                array(
                	'url' => array(
                		'plugin' => $this->params['plugin'],
                		'controller' => $this->params['controller'],
                		'action' => $action
                	)
                )
            );
        }
    ?>
    <fieldset>
        <legend><?php __( "Post a {$commentModel}" );?></legend>
        <?php
            echo $form->input( "$modelName.id", array( 'value' => $fk ) );

            foreach( $commentFields as $field )
            {
                if ( $field != 'comment' )
                {
                    echo $this->Form->input( 'Comment'.$field );
                }
                else
                {
                    echo $this->Blog->wysiwyg( 'Comment.comment', 'Simple' );
                }
            }
        ?>
    </fieldset>
	<?php echo $form->end('Submit'); ?>
</div>