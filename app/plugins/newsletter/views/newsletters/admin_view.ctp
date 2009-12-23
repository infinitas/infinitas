<h3><?php __( 'Newsletter Preview' ); ?></h3>
<?php
    echo $this->Letter->preview( $newsletter['Newsletter']['id'], 'newsletters' );
?>