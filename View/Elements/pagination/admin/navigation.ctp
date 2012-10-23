<?php
    /**
     * Admin pagination
	 *
	 * This is the default pagination file for infinitas. You can use your own
	 * by adding one to your_plugin/views/elements/pagination/admin/navigation.ctp
	 * or setting a different pagination element in the view you are using.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       Infinitas.views
     * @subpackage   Infinitas.views.pagination.admin
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */


$hasPrev = $this->Paginator->hasPrev();
$hasNext = $this->Paginator->hasNext();

if (!$this->Paginator->request->params['paging'][$this->Paginator->defaultModel()]['current']) {
	echo sprintf('<p class="pagination empty">%s</p>', __d(Inflector::underscore($this->plugin), Configure::read('Pagination.nothing_found_message' )));
	return true;
}

if(!$hasPrev && !$hasNext) {
	echo sprintf('<p class="pagination low">%s</p>', __d(Inflector::underscore($this->plugin), __d('shop', 'No more products')));
	return;
}
?>
<ul class="pagination">
	<?php
		$prev = $hasPrev ? $this->Paginator->prev(__d(Inflector::underscore($this->plugin), 'Prev'), array('tag' => 'li'), "\n") : null;
		$next = $hasNext ? $this->Paginator->next(__d(Inflector::underscore($this->plugin), 'Next'), array('tag' => 'li'), "\n") : null;

		$numbers = str_replace(
			array(
				sprintf('>%d<', $this->Paginator->request->params['paging'][$this->Paginator->defaultModel()]['page']),
				sprintf('>...<', $this->Paginator->request->params['paging'][$this->Paginator->defaultModel()]['page'])
			),
			array(
				sprintf('>%s<', $this->Html->link($this->Paginator->request->params['paging'][$this->Paginator->defaultModel()]['page'], '#', array(
					'onclick' => 'return false;'
				))),
				sprintf('>%s<', $this->Html->tag('li', $this->Html->link('...', '#', array('onclick' => 'return false;'))))
			),
			$this->Paginator->numbers(array(
				'separator' => false,
				'tag' => 'li',
				'first' => 5,
				'last' => 5,
				'currentClass' => 'disabled'
			))
		);
		echo $prev, $numbers, $next;

		echo $this->Html->tag('li',  $this->Html->link($this->Design->paginationCounter($this->Paginator), $this->here . '#', array(
			'onclick' => 'return false;'
		)), array('class' => 'text'));

		$_paginationOptions = explode(',', Configure::read('Global.pagination_select'));
		echo $this->Html->tag('li', implode('', array(
			$this->Form->create('PaginationOptions', array(
				'url' => str_replace($this->request->base, '', $this->request->here),
				'id' => 'PaginationOptions'
			)),
			$this->Form->input('pagination_limit', array(
				'options' => array_combine(array_values($_paginationOptions), array_values($_paginationOptions)),
				'div' => false,
				'label' => false,
				'selected' => (isset($this->request->params['named']['limit'])) ? $this->request->params['named']['limit'] : 20
			)),
			$this->Form->end()
		)), array('class' => 'form'));
	?>
</ul>