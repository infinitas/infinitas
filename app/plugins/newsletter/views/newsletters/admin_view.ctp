<?php echo $this->Letter->adminOtherHead( $this ); ?>
<h3><?php __( 'Newsletter Preview' ); ?></h3>
<?php
    echo $this->Letter->preview( $newsletter['Newsletter']['id'], 'newsletters' );
?>

<h3><?php __( 'Test in a mail client' ); ?></h3>
<?php
    echo $this->Form->create( 'Newsletter', array( 'action' => 'view' ) );
    echo '<p>'.__( 'Enter the email addresses you would like to send to seperated by a , {comma}.', true ).'</p>';
    echo $this->Form->input( 'id', array( 'value' => $this->params['pass'][0] ) );
    echo $this->Form->input( 'email_addresses', array( 'type' => 'textarea', 'class' => 'title', 'value' => 'dogmatic69@gmail.com,carl@php-dev.co.za' ) );
    echo $this->Form->end( 'Send the test' );
?>