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

    echo $this->Form->create('Supplier', array('type' => 'file'));
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
        	?>
				<div class="data">
					<?php
						echo $this->Form->input('id');
						echo $this->Form->input('name');
						echo $this->Form->input('address_id', array('empty' => __(Configure::read('Website.empty_select'), true)));
						echo $this->Form->input('email');
						echo $this->Form->input('phone');
						echo $this->Form->input('fax');
					?>
				</div>
				<div class="config">
					<?php
        				echo $this->Design->niceBox();
							echo $this->Form->input('logo', array('type' => 'file'));
							echo $this->Form->input('terms', array('empty' => __(Configure::read('Website.empty_select'), true), 'options' => (array)Configure::read('Shop.payment_terms')));
							echo $this->Form->input('active');
        				echo $this->Design->niceBoxEnd();
					?>
				</div>
				<div class="clr">&nbsp;</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>