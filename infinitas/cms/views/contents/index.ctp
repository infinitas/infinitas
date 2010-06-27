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

	$i = 0;
	foreach($contents as $content){
		$content['Content']['Author']['username'] = $content['Content']['Editor']['username'] = 'Admin';
		?>
			<div class="introduction">
				<h2>
					<?php
						$eventData = $this->Event->trigger(
							'cms.slugUrl',
							array('type' => 'contents', 'data' => $content['Content'])
						);
						$urlArray = current($eventData['slugUrl']);
						echo $this->Html->link(
							$content['Content']['title'],
							$urlArray
						);
					?>
				</h2>
				<div class="stats">
					<div><?php echo __('Written by', true), ': ', $content['Content']['Author']['username']; ?></div>
					<div><?php echo $this->Time->niceShort($content['Content']['created']); ?></div>
				</div>
				<div class="body">
					<?php
						echo $this->Text->truncate($content['Content']['body'], 300	, array('html' => true));
					?>
					<p>
						<?php
							echo $this->Html->link(
								__(Configure::read('Website.read_more'), true),
								$urlArray,
								array(
									'class' => 'more'
								)
							);
						?>
					</p>
				</div>
				<div class="footer">
					<span><?php echo __('Last updated on', true), ': ', $this->Time->niceShort($content['Content']['modified']); ?></span>
					<span><?php echo '('.$content['Content']['Editor']['username'].')'; ?></span>
				</div>
			</div>
		<?php
	}
?>