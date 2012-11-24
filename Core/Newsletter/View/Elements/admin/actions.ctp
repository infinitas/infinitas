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

    echo $this->Html->link(__d('newsletter', 'Dashboard'), '/admin/newsletter', array( 'class' => 'link') );
?>
<h3><?php echo __d('newsletter', 'Templates'); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link(__d('newsletter', 'Index'), array( 'plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'New'), array( 'plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'add')); ?></li>
</ul>
<h3><?php echo __d('newsletter', 'Campaigns'); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link(__d('newsletter', 'Index'), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Active'), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index', 'Campaign.active' => 1)); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Pending'), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index', 'Campaign.active' => 0)); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Add'), array( 'plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'add')); ?></li>
</ul>
<h3><?php echo __d('newsletter', 'Newsletters'); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link(__d('newsletter', 'Index'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Active'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 1)); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Pending'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 0)); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Sending'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 1, 'Newsletter.sent' => 0)); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'Completed'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.sent' => 1)); ?></li>
    <li><?php echo $this->Html->link(__d('newsletter', 'New'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'add')); ?></li>
</ul>

<h3><?php echo __d('newsletter', 'Maintanence'); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link(__d('newsletter', 'Stop Sending'), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'stopAll')); ?></li>
    <li>
        <?php
            echo $this->Html->link(
                __d('newsletter', 'Backup Templates'),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'newsletter',
                    'm' => 'template'
                ),
                array(
                    'title' => __d('newsletter', 'Save a backup of all templates')
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __d('newsletter', 'Backup Campaigns'),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'newsletter',
                    'm' => 'campaign'
                ),
                array(
                    'title' => __d('newsletter', 'Save a backup of all campaigns')
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __d('newsletter', 'Backup Newsletters'),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'newsletter',
                    'm' => 'newsletter'
                ),
                array(
                    'title' => __d('newsletter', 'Save a backup of all newsletters')
                )
            );
        ?>
    </li>
</ul>