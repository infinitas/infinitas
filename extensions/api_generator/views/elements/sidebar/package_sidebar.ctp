<?php
/**
 * Package index sidebar element
 *
 */
?>
<h3><?php __d('api_generator', 'Package Index'); ?></h3>
<?php echo $apiDoc->generatePackageTree($packageIndex); ?>