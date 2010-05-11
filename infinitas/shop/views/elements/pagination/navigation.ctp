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
     * @subpackage    blog.views.elements.pagination.navigation
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

	// show a message if nothing is found ( count == 0 or its not set )
	if (
		!isset($this->Paginator->params['paging'][key($this->Paginator->params['paging'])]['count']) ||
		$this->Paginator->params['paging'][key($this->Paginator->params['paging'])]['count'] == 0 )
	{
		echo '<p class="empty">', __(Configure::read('Pagination.nothing_found_message'), true), '</p>';
		return true;
	}
?>
<div class="showMore">
	<?php
		echo $paginator->next(
			__('More', true),
			array(
				'escape' => false,
				'tag' => 'span',
				'class' => 'ajax'
			),
			null,
			null
		);
	?>
</div>