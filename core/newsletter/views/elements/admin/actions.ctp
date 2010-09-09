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

    echo $this->Html->link( __( 'Dashboard', true ), '/admin/newsletter', array( 'class' => 'link' ) );
?>
<h3><?php __( 'Templates' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'add', ) ); ?></li>
</ul>
<h3><?php __( 'Campaigns' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index', 'Campaign.active' => 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index', 'Campaign.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Add', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'add' ) ); ?></li>
</ul>
<h3><?php __( 'Newsletters' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Sending', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 1, 'Newsletter.sent' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Completed', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.sent' => 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'add' ) ); ?></li>
</ul>

<h3><?php __( 'Maintanence' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Stop Sending', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'stopAll' ) ); ?></li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Templates', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'newsletter',
                    'm' => 'template'
                ),
                array(
                    'title' => __( 'Save a backup of all templates', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Campaigns', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'newsletter',
                    'm' => 'campaign'
                ),
                array(
                    'title' => __( 'Save a backup of all campaigns', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Newsletters', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'newsletter',
                    'm' => 'newsletter'
                ),
                array(
                    'title' => __( 'Save a backup of all newsletters', true )
                )
            );
        ?>
    </li>
</ul>