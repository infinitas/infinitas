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
<?php
    echo $this->PostLayout->pendingBox( $postPending );
    echo $this->CommentLayout->countBox( $commentCount );
    echo $this->PostLayout->mostPopular( $postPopular );
?>