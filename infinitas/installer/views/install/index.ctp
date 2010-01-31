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
<blockquote class="extract">
	<p>
		<?php
			$message1 = 'Thank-you for choosing %s to power your website.';

			$message2 = 'You are about to start installing '.
				'%s but a few things need to be check first. The checks below are to make sure your server is '.
				'set up correctly to run %s. There are some server checks, file permission checks and then '.
				'some recommendations for the best possible setup.';

			$message3 = 'You should have a yes for everything in the server setup and the paths but the installation '.
				'can continue if not everything has a yes in the recommendations. If you have some no\'s there, some functionality '.
				'might not be available for you.';
			$siteName = '<b>Infinitas</b>';

			echo str_replace('%s', $siteName, __($message1.'</p><p>'.$message2.'</p><p>'.$message3, true));
		?>
	</p>
</blockquote>
<div style="clear:both; height:auto; padding-left:10px;">
	<div style="width:50%; float:left;">
		<h2><?php __( 'Server Setup' ); ?></h2>
		<ul>
			<?php
				$continue = true;

				foreach( $setup as $option ){
					if ( $option['value'] == 'No' ){
						$continue = false;
					} ?>
					<li>
						<?php echo $option['label']; ?>
						<ul>
							<li class="<?php echo strtolower( $option['value'] ); ?>">
								<a href="#" title="<?php echo $option['desc']; ?>"><?php echo __( $option['value'], true ); ?></a>
							</li>
						</ul>
					</li><?php
				}
			?>
		</ul>
	</div>

	<div style="width:50%; float:left;">
		<h2><?php __( 'Paths' ); ?></h2>
		<ul>
			<?php
				foreach( $paths as $path ){
					if ( $path['writeable'] == 'No' ){
						$continue = false;
					}?>
					<li>
						<?php echo $path['path']; ?>
						<ul>
							<li class="<?php echo strtolower( $path['writeable'] ); ?>">
								<a href="#" title="<?php echo $path['desc']; ?>"><?php echo __( $path['writeable'], true ); ?></a>
							</li>
						</ul>
					</li><?php
				}
			?>
		</ul>
	</div>
</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both; height:auto; padding-left:10px;">
	<h2><?php __( 'Recomended Setup' ); ?></h2>
	<ul>
		<?php
			foreach( $recomendations as $recomended ){
				?>
				<li>
					<?php echo __( Inflector::humanize( $recomended['setting'] ), true ); ?>
					<ul>
						<li>
							<?php echo __( 'Recomended', true ), ': ', __( $recomended['recomendation'], true ); ?>
						</li>
						<li class="<?php echo strtolower( $recomended['state'] ); ?>">
							<?php echo __( 'Correct', true ) ?>: <a href="#" title="<?php echo $recomended['desc']; ?>"><?php echo __( $recomended['state'], true ); ?></a>
						</li>
					</ul>
				</li><?php
			}
		?>
	</ul>
</div>
<br/>
<br/>
<?php
	if ( $continue ){
		echo $this->Html->link(
			'Click here to continue',
			array(
				'action' => 'licence'
			)
		);
	}
	else{
		echo $this->Html->link(
			__( 'Fix the errors above and click here to refreash.', true ),
			array(
				'action' => 'index'
			)
		);
	}
?>