<?php
/**
* Blog post_dates view element file.
*
* tag cloug box for use in the site.
*
* @todo Implement . move to a {@see TagLayoutHelper}
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package blog
* @subpackage blog.views.elements.post_dates
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*/
?>
<div class="right-item">
    <h3>Tag Cloud</h3>
    <div class="tags">
        <p style="width:150px;">
            <?php
				echo $this->TagCloud->display($tagCount,
					array(
						'model' => 'Tag',
						'url' => array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'index')
						)
					);
			?>
        </p>
    </div>
</div>