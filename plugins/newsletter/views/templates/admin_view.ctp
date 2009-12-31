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
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
<div class="top-bar">
    <h1><?php __( $this->name ); ?></h1>
    <div class="breadcrumbs"><?php echo $this->Letter->breadcrumbs( $this ); ?></div>
</div>
<div class="main-actions">
    <?php
        echo $this->Html->link(
            'Edit',
            array(
                'action' => 'edit',
                $template['Template']['id']
            )
        );
    ?>
</div>
<div class="clr">&nbsp;</div>

<h3><?php __( 'Campaigns set to use this template' ); ?></h3>
<ul>
    <?php
        if ( empty( $template['Campaign'] ) )
        {
            echo '<li>'.__( 'There are none', true ).'</li>';
        }
        else
        {
            foreach( $template['Campaign'] as $campaign )
            {
                echo '<li>'.$campaign['name'].'</li>';
            }
        }
    ?>
</ul>

<h3><?php __( 'Newsletters using this template' ); ?></h3>
<ul>
    <?php
        if ( empty( $template['Newsletter'] ) )
        {
            echo '<li>'.__( 'There are none', true ).'</li>';
        }
        else
        {
            foreach( $template['Newsletter'] as $newsletter )
            {
                echo '<li>'.$newsletter['subject'].'</li>';
            }
        }
    ?>
</ul>

<h3><?php __( 'Template Preview' ); ?></h3>
<?php echo $this->Letter->preview( $template['Template']['id'], 'templates' ); ?>