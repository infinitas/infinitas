<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php
                echo Configure::read('Website.name'), ' :: ', $title_for_layout;
            ?>
        </title>
        <?php
            echo $this->Html->meta('icon');
        ?>
    </head>
    <body>
        <?php
            $link = $this->Html->link(__d('themes', 'theme docs'), 'http://infinitas-cms.org/infinitas_docs/Themes', array(
                'target' => '_blank'
            ));
        ?>
        <h1><?php echo __d('themes', 'Theme configuration error'); ?></h1>
        <p>It seems there has been a problem loading the layout from your theme, please see the <?php echo $link; ?> for more information on how to resolve this problem.</p>
    </body>
</html>