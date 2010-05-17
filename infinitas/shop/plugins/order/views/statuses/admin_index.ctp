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

    echo $this->Form->create('Status', array('url' => array('action' => 'mass')));

	$massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'edit',
            'delete'
        )
    );

    echo $this->Infinitas->adminIndexHead($this, $paginator, $filterOptions, $massActions);
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
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('order_count') => array(
                        'style' => 'width:150px;'
                    ),
                    $this->Paginator->sort('ordering') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            $i = 0;
            foreach ($statuses as $status){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($status['Status']['id']); ?>&nbsp;</td>
						<td>
							<?php
								echo $this->Html->link(
									$status['Status']['name'],
									array(
										'action' => 'edit',
										$status['Status']['id']
									)
								);
							?>
						</td>
						<td>
							<?php echo $status['Status']['order_count']; ?>
						</td>
                		<td>
							<?php
                			    echo $this->Infinitas->ordering(
                			        $status['Status']['id'],
                			        $status['Status']['ordering'],
									'Status',
									$statuses
                			    );
                			?>
                		</td>
						<td>
							<?php echo $this->Time->niceShort($status['Status']['modified']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('admin/pagination/navigation'); ?>