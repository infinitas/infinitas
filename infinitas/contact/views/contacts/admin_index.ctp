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

    echo $this->Form->create( 'Contact', array( 'url' => array( 'controller' => 'contacts', 'action' => 'mass', 'admin' => 'true' ) ) );

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
                    $this->Paginator->sort(__('Name', true), 'last_name'),
                    $this->Paginator->sort('email'),
                    $this->Paginator->sort('Branch', 'Branch.name') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('position') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('phone') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('mobile') => array(
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
            foreach ($contacts as $contact){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($contact['Contact']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/contact/contact/'.$contact['Contact']['image'],
									array(
										'height' => '35px;'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($contact['Contact']['last_name'].', '.$contact['Contact']['first_name'], array('action' => 'edit', $contact['Contact']['id'])); ?>&nbsp;
                		</td>
						<td>
							<?php echo $this->Text->autoLinkEmails($contact['Contact']['email']); ?>
						</td>
						<td>
							<?php echo $contact['Branch']['name']; ?>
						</td>
						<td>
							<?php echo $contact['Contact']['position']; ?>
						</td>
						<td>
							<?php echo $contact['Contact']['phone']; ?>
						</td>
						<td>
							<?php echo $contact['Contact']['mobile']; ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->ordering($contact['Contact']['id'], $contact['Contact']['ordering'], 'Contact.Contact'); ?>&nbsp;
                		</td>
						<td>
							<?php echo $this->Time->niceShort($contact['Contact']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($contact['Contact']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>