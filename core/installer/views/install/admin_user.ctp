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
<h2><?php __('Set up your administrator user'); ?></h2>
	<p><?php echo __('Infinitas is now sucessfully installed. Before using Infinitas you should first setup an administrative user. This will be the user that is responsible for maintaining and administrating your website.', true); ?></p>
<?php
	echo $form->input( 'User.username');
	echo $form->input( 'User.email');
	echo $form->input( 'User.password');
?>