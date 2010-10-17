<?php
    /**
	 * view and manage short urls for your site
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

    echo $this->Form->create('ShortUrl', array('action' => 'mass'));

        $massActions = $this->Core->massActionButtons(
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
            echo $this->Core->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort('url'),
                    $this->Paginator->sort(__('Short Url', true), 'id'),
                    $this->Paginator->sort('views') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('dead') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($shortUrls as $shortUrl){
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($shortUrl['ShortUrl']['id']); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link($shortUrl['ShortUrl']['url'], array('action' => 'edit', $shortUrl['ShortUrl']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php
								$short = $this->Event->trigger('shorturls.getShortUrl', array('type' => 'preview', 'url' => $shortUrl['ShortUrl']['url']));
								$short = Router::url(current($short['getShortUrl']));
								echo $this->Html->link($short, $short, array('target' => '_blank'));
							?>&nbsp;
                		</td>
						<td><?php echo $shortUrl['ShortUrl']['views']; ?></td>
						<td><?php echo $this->Infinitas->status($shortUrl['ShortUrl']['dead']); ?></td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>