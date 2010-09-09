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
<h2 class="fade"><?php __('My Addresses'); ?></h2>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    __('Name', true),
                    __('Country', true),
                    __('Province', true),
                    __('City', true),
                    __('Street', true),
                    __('Postal', true),
                    __('modified', true)
                ),
                false
            );

            foreach ($addresses as $address){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
						<td>
							<?php
								echo $this->Html->link(
									$address['Address']['name'],
									array(
										'plugin' => 'management',
										'controller' => 'addresses',
										'action' => 'edit',
										$address['Address']['id']
									)
								);
							?>
						</td>
						<td>
							<?php echo $address['Country']['name']; ?>
						</td>
						<td>
							<?php echo $address['Address']['province']; ?>
						</td>
						<td>
							<?php echo $address['Address']['city']; ?>
						</td>
						<td>
							<?php echo $address['Address']['street']; ?>
						</td>
						<td>
							<?php echo $address['Address']['postal']; ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($address['Address']['modified']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
</div>