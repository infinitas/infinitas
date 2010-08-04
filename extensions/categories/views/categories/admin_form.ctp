<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

	echo $this->Form->create('Category', array('action' => 'edit'));
		echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
	        ?>
				<div class="config">
					<?php
				    	echo $this->Form->input('id');
				    	echo $this->Form->input('title', array('class' => 'title'));
    					echo $this->Categories->wysiwyg('Category.description');
				    ?>
				</div>
				<div class="data">
					<?php
						echo $this->Design->niceBox();
					    	echo $this->Form->input('parent_id');
					    	echo $this->Form->input('group_id', array('label' => __('Min Group', true), 'empty' => __('Public', true)));
					    	echo $this->Form->input('active');
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
	echo $this->Form->end();
?>