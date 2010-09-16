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
?>
<div class="install">
    <p>
		<?php
			echo __('Congratulations, you have successfuly installed Infinitas. You can start using your site immediately.', true);
		?>
	</p>
	<p>
		<?php
			echo __('Infinitas has two main areas. The <em>frontend</em> and the <em>administration panel</em>.
					The frontend is what visitors to you site will see.
					The administration panel is where you configure your site and add content.
					You can access either section by using the links provided below.', true);
		?>
	</p>
	<p>
        <?php
			echo __('Frontend: ', true);
            echo $html->link(
                Router::url( '/', true),
                Router::url( '/', true )
            );
        ?><br />
        <?php
			echo __('Administration panel: ', true);
            echo $html->link(
                Router::url( '/admin', true),
                Router::url( '/admin', true)
            );
        ?>
    </p>
</div>