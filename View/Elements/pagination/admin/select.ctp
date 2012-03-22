<?php
	$_paginationOptions = explode( ',', Configure::read( 'Global.pagination_select' ) );
	$paginationLimmits = array_combine(
		array_values( $_paginationOptions ),
		array_values( $_paginationOptions )
	);

	$_paginationOptionsSelected = (isset($this->request->params['named']['limit'])) ? $this->request->params['named']['limit'] : 20;

	echo $this->Form->create('PaginationOptions', array('url' => str_replace($this->request->base, '', $this->request->here), 'id' => 'PaginationOptions'));
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
