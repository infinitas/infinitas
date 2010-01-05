<?php
    /**
     * Blog right_boxes view element file.
     *
     * the notifications/overviews on the right hand side of the admin interface.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       blog
     * @subpackage    blog.views.elements.right_boxes
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<p><?php echo sprintf( __( 'Welcome: %s', true ), Inflector::humanize( $session->read( 'Auth.User.username' ) ) ); ?></p>
<?php
    if ( Configure::read( 'debug' ) )
    {
        echo '<p>&nbsp;</p><p>',
            __( 'You can create your own sideboxe here by creating a file called right_boxes.ctp in', true ),
            ' ',
            $this->params['plugin'], '/views/elements/admin/right_boxes.ctp',
        '</p>';
    }
?>