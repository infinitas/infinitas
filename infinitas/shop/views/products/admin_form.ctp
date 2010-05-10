<?php
    /**
     * Shop suppliers add/edit
     *
     * This page is used to add/edit suppliers for your products.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       shop
     * @subpackage    shop.views.suppliers.form
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.8a
     */

    echo $this->Form->create('Product', array('type' => 'file'));
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
        	?>
				<div class="data">
					<?php
						echo $this->Form->input('id');
						echo $this->Form->input('name', array('class' => 'title'));
						echo $this->Shop->wysiwyg('Product.description');
					?>
				</div>
				<div class="config">
					<?php
        				echo $this->Design->niceBox();
        					?><h2><?php __('Config'); ?></h2><?php
							echo $this->Form->input('supplier_id', array('empty' => __(Configure::read('Website.empty_select'), true)));
							echo $this->Form->input('ShopBranch');
							echo $this->Form->input('ShopCategory');
							echo $this->Form->input('active');
						echo $this->Design->niceBoxEnd();

        				echo $this->Design->niceBox();
        					?><h2><?php __('Pricing'); ?></h2><?php
							echo $this->Form->input('cost', array('before' => Configure::read('Currency.unit'),'title' => __('Cost :: This is the price that you pay for the item', true)));
							echo $this->Form->input('retail', array('before' => Configure::read('Currency.unit'), 'title' => __('Retail :: This is the going rate of the item in other stores', true)));
							echo $this->Form->input('price', array('before' => Configure::read('Currency.unit'), 'title' => __('Price :: This is your selling price', true)));
							echo $this->Form->input('unit_id', array('title' => __('Unit :: The unit that the product is sold in [eg: ounces]', true)));
        				echo $this->Design->niceBoxEnd();

        				echo $this->Design->niceBox();
        					?><h2><?php __('Images'); ?></h2><?php
							echo $this->Form->input('Image.image', array('label' => __('New Main Image', true), 'type' => 'file'));
							echo $this->Form->input('image_id', array('label' => __('Exsisting Main Image', true), 'empty' => __(Configure::read('Website.empty_select'), true)));
        					echo $this->Form->input('ProductImage', array('options' => $images, 'multiple' => true));
        				echo $this->Design->niceBoxEnd();
					?>
				</div>
				<div class="clr">&nbsp;</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>