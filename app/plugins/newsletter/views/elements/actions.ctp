<?php echo $this->Html->link( __( 'Dashboard', true ), '/admin/newsletter', array( 'class' => 'link' ) ); ?>
<h3><?php __( 'Templates' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'add', ) ); ?></li>
</ul>
<h3><?php __( 'Campaigns' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index', 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index', 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Add', true ), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'add', 0 ) ); ?></li>
</ul>
<h3><?php __( 'Newsletters' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Sending', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'sending' ) ); ?></li>
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