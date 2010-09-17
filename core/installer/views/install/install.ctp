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
	<blockquote class="extract">
	    <p><?php 
				echo __('Infinitas is now ready to install onto your server.', true );
				echo __(', .',true);
			?>
		</p>
	</blockquote>
	<br />
	<?php
		echo $this->Form->input('Sample.sample', array(
			'type' => 'radio',
			'options' => array(1 => 'Yes', 0 => 'No'),
			'value' => 0,
			'legend' => 'Do you wish to install some sample data (Which is recommended for new Infinitas users)?',
		))
	?>
</div>