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
	    <p>
			<?php
				echo __( 'Database tables and core data is about to be installed. '.
			    	'Please make sure you have created a database already, then fill out the '.
			    	'connection details below. If the installer is not able to connect to the database '.
			    	'the installer will not be able to continue', true );
			?>
		</p>
	    <p>
			<?php
				echo __( 'If you are unsure how to create a database, please contact your hosting provider\'s technical support.', true );
			?>
		</p>
		<?php if(isset($dbError) && $dbError === true) {?>
			<div class="error-message">
				<?php echo __('There is an error with your database connection. Please ensure that the details supplied are correct and that the database server is running.', true); ?>
			</div>
		<?php }?>
		<?php if(isset($versionError)) {?>
			<div class="error-message">
				<?php echo __(sprintf('The database server you selected is running version %1$s. Infinitas requires at least version %2$s.', $versionError, $requiredVersion), true); ?>
			</div>
		<?php }?>
    <?php
        echo $form->input( 'Install.driver', array(
			'label' => 'Database driver',
			'options' => $database,
			'empty' => '-- Database driver --',
			'after' => '<div>'.__('What type of database server should Infinitas use', true).'</div><br style="clear:both;" />'
		));

		echo $form->input( 'Install.database', array(
			'label' => 'Database name',
			'value' => isset($this->data['Install']) ? $this->data['Install']['database'] : 'infinitas',
			'after' => '<div>'.__('This is the name of the database Infinitas will use', true).'</div><br style="clear:both;" />'
		));

        echo $form->input( 'Install.login', array(
			'label' => 'Database username',
			'value' => isset($this->data['Install']) ? $this->data['Install']['login'] : 'username',
			'after' => '<div>'.__('The username to access the database', true).'</div><br style="clear:both;" />'
		));

        echo $form->input( 'Install.password', array(
			'type' => 'text',
			'label' => 'Database password',
			'value' => isset($this->data['Install']) ? $this->data['Install']['password'] : 'password',
			'after' =>'<div>'. __('The password to your database', true).'</div><br style="clear:both;" />'
		));

        echo $form->input( 'Install.host', array(
			'label' => 'Database host',
			'value' => isset($this->data['Install']) ? $this->data['Install']['host'] : 'localhost',
			'after' => '<div>'.__('The server for your database. <strong>localhost</strong> should work, if it does not then you can get the info from your web host.', true).'</div><br style="clear:both;" />'
		));

        echo $form->input( 'Install.port', array( 
			'label' => 'Database port',
			'after' => '<div>'.__('This is the port your database server runs is on, leave blank for default.', true).'</div><br style="clear:both;" />'
		));

        echo $form->input( 'Install.prefix', array( 
			'label' => 'Table prefix',
			'after' => '<div>'.__('This is a prefix for all the tables that infinitas will create. Useful if you want to share the database with other applications.', true).'</div><br style="clear:both;" />'
		));
    ?>
</div>