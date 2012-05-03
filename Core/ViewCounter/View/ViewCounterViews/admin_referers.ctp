<?php
    /**
     * Blog Comments admin index
     *
     * this is the page for admins to view all the posts on the site.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.posts.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    echo $this->Form->create('ViewCounterView', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Paginator->sort('referer'),
                    $this->Paginator->sort('ViewCounterView.referer_count', __d('blog', 'Referals')) => array(
                        'style' => 'width:130px;'
                    ),
                    $this->Paginator->sort('external') => array(
                        'style' => 'width:30px;'
                    )
                )
            );

            foreach($views as $view) {
                ?>
                    <tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $view['ViewCounterView']['referer']; ?>&nbsp;</td>
                        <td><?php echo $view['ViewCounterView']['referer_count']; ?>&nbsp;</td>
                        <td><?php echo $this->Infinitas->status($view['ViewCounterView']['external'], $view['ViewCounterView']['id']); ?>&nbsp;</td>
                    </tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>