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

    echo $this->Form->create('Tag', array('action' => 'mass'));

    $massActions = $this->Infinitas->massActionButtons(
		array(
        	'add',
            'edit',
            'delete'
        )
    );

	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);


?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
					$this->Paginator->sort('identifier'),
					$this->Paginator->sort('name'),
					$this->Paginator->sort('keyname'),
					$this->Paginator->sort('weight'),
					$this->Paginator->sort('created')
                )
            );

            foreach ($tags as $tag){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($tag['Tag']['id']); ?>&nbsp;</td>
						<td><?php echo $tag['Tag']['identifier']; ?></td>
						<td><?php echo $tag['Tag']['name']; ?></td>
						<td><?php echo $tag['Tag']['keyname']; ?></td>
						<td><?php echo $tag['Tag']['weight']; ?></td>
						<td><?php echo $this->Time->niceShort($tag['Tag']['created']); ?></td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>