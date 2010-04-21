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

    echo $this->Form->create('Category', array('type' => 'file'));
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
        	?>
				<div class="data">
					<?php
						echo $this->Form->input('id');
						echo $this->Form->input('name');
						echo $this->Shop->wysiwyg('Category.description');
					?>
				</div>
				<div class="config">
					<?php
        				echo $this->Design->niceBox();
        					?><h2><?php __('Config'); ?></h2><?php
							echo $this->Form->input('keywords');
							echo $this->Form->input('parent_id', array('empty' => __('Root Category', true)));
							echo $this->Form->input('ShopBranch');
							echo $this->Form->input('active');
						echo $this->Design->niceBoxEnd();
        				echo $this->Design->niceBox();
        					?><h2><?php __('Image'); ?></h2><?php
							echo $this->Form->input('Image.image', array('label' => __('New image', true), 'type' => 'file'));
							echo $this->Form->input('image_id', array('label' => __('Exsisting image', true), 'empty' => __(Configure::read('Website.empty_select'), true)));
        				echo $this->Design->niceBoxEnd();
					?>
				</div>
				<div class="clr">&nbsp;</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>