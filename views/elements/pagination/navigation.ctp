<?php
    /**
     * Blog pagination view element file.
     *
     * this is a custom pagination element for the blog plugin.  you can
     * customize the entire blog pagination look and feel by modyfying this file.
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
     * @subpackage    blog.views.elements.pagination.navigation
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<div class="clr">&nbsp;</div>
<?php
     echo $this->Design->niceBox( 'pagination' );
?>
    <div class="wrap">
        <div class="limit">
            <span><?php echo __( 'Display', true ); ?> #</span>
            <span><?php
                $_paginationOptions = explode( ',', Configure::read( 'Global.pagination_select' ) );
                $paginationLimmits = array_combine(
                    array_values( $_paginationOptions ),
                    array_values( $_paginationOptions )
                );

                $_paginationOptionsSelected = ( isset( $this->params['named']['limit'] ) ) ? $this->params['named']['limit'] : 20;
                echo $this->Form->create( 'PaginationOptions', array( 'url' => $this->here  ) );
                    echo $this->Form->input(
                        'pagination_limit',
                        array(
                            'options' => $paginationLimmits,
                            'div' => false,
                            'label' => false,
                            'selected' => $_paginationOptionsSelected
                        )
                    );
                echo $this->Form->end( __( 'Update', true ) );
            ?><span>
        </div>
        <div class="button2-right">
            <div class="start">
                <?php
                    echo $this->Html->link(
                        __( 'Start', true ),
                        $paginator->url( array( 'page' => 1 ), true )
                    );
                ?>
            </div>
        </div>
        <div class="button2-right">
            <div class="prev">
                <?php
                    echo $paginator->prev(
                        __( 'Prev', true ),
                        array(
                            'escape' => false,
                            'tag' => 'span',
                            'class' => ''
                        ),
                        null,
                        null
                    );
                ?>
            </div>
        </div>
        <div class="button2-left">
            <div class="numbers">
                <?php
                    $numbers = $paginator->numbers(
                        array(
                            'tag' => 'span',
                            'before' => null,
                            'after' => null,
                            'model' => null,
                            'modulus' => '8',
                            'separator' => '',
                            'first' => null,
                            'last' => null
                        )
                    );

                    if ( $numbers === false )
                    {
                        echo '<span><b>1</b></span>';
                    }
                    else
                    {
                        echo $numbers;
                    }
                ?>
                <span class="blank"></span>
            </div>
        </div>
        <div class="button2-left">
            <div class="next">
                <?php
                    echo $paginator->next(
                        __( 'Next', true ),
                        array(
                            'escape' => false,
                            'tag' => 'span',
                            'class' => ''
                        ),
                        null,
                        null
                    );
                ?>
            </div>
        </div>
        <div class="button2-left">
            <div class="last">
                <?php
                    echo $this->Html->link(
                        __( 'End', true ),
                        $paginator->url(
                            array(
                                'page' => $paginator->params['paging'][Inflector::singularize( $this->name )]['pageCount']
                            ),
                            true
                        )
                    );
                ?>
            </div>
        </div>
        <span>
            <?php echo $this->Design->paginationCounter( $paginator ); ?>
        </span>
    </div>
<?php echo $this->Design->niceBoxEnd(); ?>