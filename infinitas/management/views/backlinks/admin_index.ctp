<?php
	/**
	 * Backlinks to the site
	 *
	 * Shows a list of backlinks to the site
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package blog
	 * @subpackage blog.category.views.admin_index
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

    //echo $this->Form->create( 'Category', array( 'url' => array( 'controller' => 'categories', 'action' => 'mass', 'admin' => 'true' ) ) );
        $massActions = $this->Core->massActionButtons(
            array(
                //'add',
                //'edit',
                //'toggle',
                //'copy',
                //'delete'
            )
        );
        echo $this->Core->adminIndexHead( $this, null, null, $massActions );
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
					__('Publishe', true) => array(
                        'style' => 'width:100px;'
                    ),
					__('Title', true) => array(
                        'style' => 'width:150px;'
                    ),
					__('Description', true),
					__('Creator', true) => array(
                        'style' => 'width:100px;'
                    ),
					__('Date', true)
                )
            );

            $i = 0;
            foreach( $data as $link )
            {
                ?>
                    <tr class="<?php echo $this->Core->rowClass(); ?>">
						<td><?php echo $link['Backlink']['publisher']; ?></td>
						<td><?php echo $this->Html->link($link['Backlink']['title'], $link['Backlink']['link']); ?></td>
						<td><?php echo $link['Backlink']['description']; ?></td>
						<td><?php echo $link['Backlink']['creator']; ?></td>
						<td><?php echo $this->Time->niceShort($link['Backlink']['date']); ?></td>
                    </tr>
                <?php
                $i++;
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>