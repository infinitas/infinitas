<?php
	if($this->action == 'search'){
		?>
			<div class="search">
				<?php
					echo $form->create('Search', array(array('url' => array('plugin' => 'shop', 'controller' => 'products', 'action' => 'search'))));
						echo $form->input('Search.search');
						echo $form->input('Product.category_id', array('options' => array(ClassRegistry::init( 'Shop.Category' )->generateTreeList( null, null, null, '__'))));
					echo $form->end( 'Submit' );
				?>
			</div>
		<?php
	}
?>
<h2 class="fade"><?php __('All Products'); ?></h2>
	<div class="list">
		<?php
			foreach($products as $product){
				echo $this->element('product', array('plugin' => 'shop', 'product' => $product));
			}
			echo $this->element('pagination/navigation');
		?>
	</div>
