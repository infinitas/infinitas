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

	$numbers = $paginator->numbers(
		array(
			'tag' => 'li',
			'before' => null,
			'after' => null,
			'model' => null,
			'modulus' => '8',
			'separator' => '',
			'first' => null,
			'last' => null
		)
	);

	// show a message if nothing is found ( count == 0 or its not set )
	if (
		!isset($this->Paginator->params['paging'][key( $this->Paginator->params['paging'] )]['count']) ||
		$this->Paginator->params['paging'][key( $this->Paginator->params['paging'] )]['count'] == 0 )
	{
		echo '<p class="empty">', __( Configure::read( 'Pagination.nothing_found_message' ), true ), '</p>';
		return true;
	}
?>
<ul class="pagination">
	<?php
		echo $paginator->prev('prev page', array('tag' => 'li'), "\n");
		echo $paginator->numbers(array('separator' => false, 'tag' => 'li', 'first' => 5, 'last' => 5));
		echo $paginator->next('next page', array('tag' => 'li'), "\n");
	?>
	<li class="form">
		<?php
			$_paginationOptions = explode( ',', Configure::read( 'Global.pagination_select' ) );
			$paginationLimmits = array_combine(
				array_values( $_paginationOptions ),
				array_values( $_paginationOptions )
			);

			$_paginationOptionsSelected = (isset($this->params['named']['limit'])) ? $this->params['named']['limit'] : 20;

			echo $this->Form->create('PaginationOptions', array('url' => str_replace($this->base, '', $this->here), 'id' => 'PaginationOptions'));
				echo $this->Form->input(
					'pagination_limit',
					array(
						'options' => $paginationLimmits,
						'div' => false,
						'label' => false,
						'selected' => $_paginationOptionsSelected
					)
				);
			echo $this->Form->end();
		?>
	</li>
	<li class="text"><?php echo $this->Design->paginationCounter($paginator); ?></li>
</ul>