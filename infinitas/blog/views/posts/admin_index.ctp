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
     * @link          http://www.dogmatic.co.za
     * @package       blog
     * @subpackage    blog.views.posts.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    echo $this->Form->create( 'Post', array( 'url' => array( 'controller' => 'posts', 'action' => 'mass', 'admin' => 'true' ) ) );
        $massActions = $this->Blog->massActionButtons(
            array(
                'add',
                'edit',
                'preview',
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
                    $paginator->sort( 'title' ) => array(
                        'style' => 'width:150px;'
                    ),
                    __( 'Introduction', true ),
                    __( 'Tags', true ),
                    $paginator->sort( 'Comments','comment_count' ) => array(
                        'style' => 'width:50px;'
                    ),
                    $paginator->sort( 'views' ) => array(
                        'style' => 'width:50px;'
                    ),
                    __( 'Status', true ) => array(
                        'class' => 'actions'
                    )
                )
            );

            $i = 0;
            foreach( $posts as $post )
            {
                ?>
                    <tr class="<?php echo $this->Blog->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $post['Post']['id'] ); ?>&nbsp;</td>
                        <td title="<?php echo $post['Post']['slug']; ?>">
                            <?php echo $this->Html->link( $post['Post']['title'], array( 'action' => 'edit', $post['Post']['id'] ) ); ?>
                        </td>
                        <td><?php echo strip_tags( $post['Post']['intro'] ); ?>&nbsp;</td>
                        <td><?php echo implode( ', ', Set::extract( '/Tag/name', $post ) ); ?>&nbsp;</td>
                        <td><?php echo $post['Post']['comment_count']; ?>&nbsp;</td>
                        <td><?php echo $post['Post']['views']; ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $this->Status->toggle( $post['Post']['active'], $post['Post']['id'] ),
                                    $this->Status->locked( $post, 'Post' );
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