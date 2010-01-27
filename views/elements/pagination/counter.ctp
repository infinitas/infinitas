<?php
    /**
     * Blog pagination view element file.
     *
     * this is a custom pagination element for the blog plugin.  you can
     * customize the entire blog pagination look and feel by modyfying this file.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.elements.pagination.counter
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<p class="pagination_plugin">
    <?php
        echo $paginator->counter( array( 'format' => __( 'Page %page% of %pages%, showing %current% items out of %count% total.', true ) ) );
    ?>
</p>