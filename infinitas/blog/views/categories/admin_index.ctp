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
    echo $this->Form->create( 'Category', array( 'url' => array( 'controller' => 'categories', 'action' => 'mass', 'admin' => 'true' ) ) );
        $massActions = $this->Blog->massActionButtons(
            array(
                'add',
                'edit',
                'toggle',
                'copy',
                'delete'
            )
        );
        echo $this->Blog->adminIndexHead( $this, $paginator, $filterOptions, $massActions );
?>
<div class="table">
    <?php echo $this->Blog->adminTableHeadImages(); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Blog->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $paginator->sort( 'name' ) => array(
                        'style' => 'width:150px;'
                    ),
                    __( 'Description', true ),
                    __( 'Status', true ) => array(
                        'class' => 'actions'
                    )
                )
            );

            $i = 0;
            foreach( $categories as $category )
            {
                ?>
                    <tr class="<?php echo $this->Blog->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $category['Category']['id'] ); ?>&nbsp;</td>
                        <td title="<?php echo $category['Category']['slug']; ?>">
                            <?php echo $this->Html->link( $category['Category']['name'], array( 'action' => 'edit', $category['Category']['id'] ) ); ?>
                        </td>
                        <td><?php echo $this->Text->truncate(strip_tags( $category['Category']['description'] )); ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $this->Infinitas->status( $category['Category']['active'], $category['Category']['id'] );
                            ?>
                        </td>
                    </tr>
                <?php
                $i++;
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>