<?php
    /**
     * Shop Branches edit
     *
     * This page is to edit exsisting branches for the shop
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
     * @subpackage    shop.views.branches.admin_edit
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.8a
     */

    echo $this->Form->create('Branch');
        echo $this->Infinitas->adminEditHead($this);
    ?>
		<div style="width:50%; float:left;">
			<?php
				echo $this->Form->input('ShopBranch.id');
				echo $this->Form->input('ShopBranch.branch_id', array('label' => __('Branch', true), 'options' => $branchDetails));
				echo $this->Form->input('ShopBranch.manager_id');
				echo $this->Form->input('ShopBranch.active');
			?>
		</div>
		<div style="width:50%; float:left;">
			<?php
			?>
		</div>
		<div class="clr">&nbsp;</div>
	<?php
    echo $this->Form->end();
?>