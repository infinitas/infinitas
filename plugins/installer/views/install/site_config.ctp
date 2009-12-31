<h2><?php __( 'Set up your site' ); ?></h2>
<p>This will be the set up of the site, like config.php and some other stuff.</p>
<p>&nbsp;</p>
<?php
    echo $this->Html->link(
        __( 'Continue', true ),
        array(
            'action' => 'done'
        )
    );
?>