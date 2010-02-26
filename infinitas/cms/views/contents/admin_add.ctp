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
?>
<div class="contents form">
	<?php
		echo $this->Form->create( 'Content' );
        echo $this->Infinitas->adminEditHead($this);		
	?>
	<div style="width:75%; float:left;">
	    <?php
    		echo $this->Form->input( 'id' );
    		echo $this->Form->input( 'title', array( 'class' => 'title' ) );
    		echo $this->Cms->wysiwyg( 'Content.introduction' );
    		echo $this->Cms->wysiwyg( 'Content.body' );
	    ?>
	</div>
	<div style="width:20%; float:right;">
	    <?php
			echo $this->Form->input( 'active' );
			echo $this->Form->input( 'layout_id' );
			echo $this->Form->input( 'category_id' );
    		echo $this->Form->input( 'group_id', array( 'label' => __( 'Min Group', true ) ) );
    		echo $this->Form->hidden( 'ContentConfig.id' );
    		echo $this->Form->input( 'ContentConfig.author_alias' );
    		echo $this->Form->input( 'ContentConfig.keywords' );
    		echo $this->Form->input( 'ContentConfig.description', array('class'=>'title') );
	    ?>
	</div>
	<div class="clr">&nbsp;</div>
	<?php echo $this->Form->end(  ); ?>
</div>