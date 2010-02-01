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
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
<div class="install form">
    <h2><?php __( 'Database Setup' ); ?></h2>
	<blockquote class="extract">
	    <p>
			<?php
				echo __( 'Database tables and core data is about to be installed. '.
			    	'Please make sure you have created a database already, then fill out the '.
			    	'connection details below. If the installer is not able to connect to the databse '.
			    	'the installer will not be able to continue', true );
			?>
		</p>
	</blockquote>
    <?php
        echo $form->create( 'Install', array( 'url' => array( 'plugin' => 'installer', 'controller' => 'install', 'action' => 'database' ) ) );
        echo $form->input( 'Install.host', array( 'value' => 'localhost', 'title' => __('This is the host address of your database. If you dont know what it is leave it as "localhost"', true) ) );
        echo $form->input( 'Install.login', array( 'value' => 'root', 'title' => __('This is the username to access the database', true) ) );
        echo $form->input( 'Install.password', array('title' => __('This is the password to your database', true)) );
        echo $form->input( 'Install.database', array( 'value' => 'infinitas', 'title' => __('This is the name of the database Infinitas will use', true) ) );
        echo $form->end( 'Submit' );
    ?>
</div>
<blockquote class="extract">
    <p>
		<?php
			echo __( 'Please note that at the moment Infinitas is best used in its own databse. There is no global prefix for the database tables '.
				'as each plugin uses its own prefix. This may change in future versions.', true );
		?>
	</p>
</blockquote>