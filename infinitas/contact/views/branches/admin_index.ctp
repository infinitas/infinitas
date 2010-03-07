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

    echo $this->Form->create( 'Branch', array( 'url' => array( 'controller' => 'branches', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'copy',
                'toggle',
                'delete'
            )
        );
        echo $this->Infinitas->adminIndexHead( $this, $paginator, $filterOptions, $massActions );
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
                    $this->Paginator->sort('image') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('phone') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('fax') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('Users', 'user_count') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('ordering') => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            $i = 0;
            foreach ($branches as $branch){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($branch['Branch']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/contact/branch/'.$branch['Branch']['image'],
									array(
										'height' => '35px;'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($branch['Branch']['name'], array('action' => 'edit', $branch['Branch']['id'])); ?>&nbsp;
                		</td>
						<td>
							<?php echo $branch['Branch']['phone']; ?>
						</td>
						<td>
							<?php echo $branch['Branch']['fax']; ?>
						</td>
						<td>
							<?php echo $branch['Branch']['user_count']; ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->ordering($branch['Branch']['id'], $branch['Branch']['ordering'], 'Contact.Branch'); ?>&nbsp;
                		</td>
						<td>
							<?php echo $this->Time->niceShort($branch['Branch']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($branch['Branch']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>