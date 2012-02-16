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
?>
<div class="clr">&nbsp;</div>
<?php
	$hasPrev = $this->Paginator->hasPrev();
	$hasNext = $this->Paginator->hasNext();
	
	if (!$this->Paginator->request->params['paging'][$this->Paginator->defaultModel()]['current']) {
		echo sprintf('<p class="pagination empty">%s</p>', __d(Inflector::underscore($this->plugin), Configure::read('Pagination.nothing_found_message' )));
		return true;
	}

	if(!$hasPrev && !$hasNext) {
		echo sprintf('<p class="pagination low">%s</p>', __d(Inflector::underscore($this->plugin), 'No more posts'));
		return;
	}
?>
<div class="wrap">
	<div class="button2-left">
		<div class="prev">
			<?php
				echo $this->Paginator->prev(
					__( 'Newer', true ),
					array(
						'escape' => false,
						'tag' => 'span',
						'class' => ''
					),
					null,
					null
				);
			?>
		</div>
	</div>
	<div class="button2-right">
		<div class="next">
			<?php
				echo $this->Paginator->next(
					__( 'Older', true ),
					array(
						'escape' => false,
						'tag' => 'span',
						'class' => ''
					),
					null,
					null
				);
			?>
		</div>
	</div>
</div>