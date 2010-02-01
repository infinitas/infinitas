<?php
    /**
     * Blog post_dates view element file.
     *
     * date menu for the users in blog
     *
     * @todo -c Implement . move to {@see PostLayoutHelper}
     * @todo -c Implement . move css to a file
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
     * @subpackage    blog.views.elements.post_dates
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<style>
    .post-dates{
        font-size:120%;
    }
    .post-dates ul .years,
    .post-dates ul .months,
    .post-dates li .years,
    .post-dates li .months{
        list-style:none;
    }
    .post-dates li .years{
        padding-left: 10px;
    }
    .post-dates ul .months{
        padding-left: 10px;
    }
</style>
<div class="post-dates">
    <p style="width:150px;">
        <?php
        	$postDates = ClassRegistry::init('Blog.Post')->getDates();

            if ( empty( $postDates ) ){
                echo __( 'Nothing Found', true );
            }

            else{
                foreach( $postDates as $year => $months ){
                    ?>
                        <ul>
                            <li class="years">
                                <?php
                                    echo $this->Html->link(
                                        $year,
                                        array(
                                            'plugin' => 'blogs',
                                            'controller' => 'posts',
                                            'action'  => 'index',
                                            'all',
                                            $year
                                        )
                                    );

                                    if ( !empty( $months ) ){ ?>
										<ul><?php
                                        foreach( $months as $month ){ ?>
                                            <li class="months"> <?php
                                                echo $this->Html->link(
                                                    $month,
                                                    array(
                                                        'plugin' => 'blogs',
                                                        'controller' => 'posts',
                                                        'action'  => 'index',
                                                        'all',
                                                        $year,
                                                        $month
                                                    )
                                                ); ?>
                                            </li> <?php
                                        } ?>
                                        </ul>  <?php
                                    }
                                ?>
                            </li>
                        </ul>
                    <?php
                }
            }
        ?>
    </p>
</div>