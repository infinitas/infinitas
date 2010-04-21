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

    echo $this->Form->create('Image', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'edit',
            'copy', // @todo overwrite and copy file
            'delete' // @todo hard delete should unlink() file.
        )
    );
    echo $this->Infinitas->adminIndexHead($this, $paginator, $filterOptions, $massActions);
?>
<div class="table">
	<?php
		foreach ($images as $image){
			?>
				<div class=image">
					<?php
						echo $this->Html->image(
							'content/shop/global/'.$image['Image']['image'],
							array(
								'height' => '35px'
							)
						);
					?>
					<div class="name"><?php echo $this->Html->link($image['Image']['image'], array('action' => 'edit', $image['Image']['id'])); ?></div>
					<div class="width"><?php echo __('Width', true), ':', $image['Image']['width'], 'px'; ?></div>
					<div class="height"><?php echo __('Height', true), ':', $image['Image']['height'], 'px'; ?></div>
					<div class="ext"><?php echo __('Ext', true), ':', $image['Image']['ext']; ?></div>
					<div class="check"><?php echo $this->Form->checkbox($image['Image']['id']); ?></div>
				</div>
			<?php
		}
        echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>