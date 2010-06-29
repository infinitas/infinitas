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
<p>
	<?php
		$message = array(
			__d('installer',
				'You are about to start installing '.
				'%s but a few things need to be checked first. '.
				'Anything that would prevent %s from running or that will negatively influence speed or security of %s will be shown below. '.
				'If nothing is shown, then your server is optinumly configured for %s.',
				true
			)
		);

		$siteName = '<b>Infinitas</b>';

		echo str_replace('%s', $siteName, implode('</p><p>', $message));
	?>
</p>
<?php
	$continue = true;
	$setupErrors = '';
	foreach( $setup as $option ){
		if ( $option['value'] == 'No' ){
			$continue = false;

			$setupErrors .= '
				<li>
					'.$option['label'].'
					<ul>
						<li class="'. strtolower( $option['value'] ).'">
							<a href="#" title="'. $option['desc'].'">'. __( $option['value'], true ).'</a>
						</li>
					</ul>
				</li>
			';
		}
	}

	$pathErrors = '';
	foreach( $paths as $option ){
		if ( $option['writeable'] == 'No' ){
			$continue = false;

			$pathErrors .= '
				<li>
					'.$option['path'].'
					<ul>
						<li class="'. strtolower( $option['writeable'] ).'">
							<a href="#" title="'. $option['desc'].'">'. __( $option['writeable'], true ).'</a>
						</li>
					</ul>
				</li>
			';
		}
	}

	$recommend = '';
	foreach( $recomendations as $recomended ){
		if ( $recomended['actual'] == $recomended['recomendation'] ){
			$recommend .= '
				<li>
					<b>'.__( Inflector::humanize( $recomended['setting'] ), true ).'</b>
					<ul>
						<li class="'. ($recomended['actual'] == $recomended['recomendation'] ? 'yes' : 'no').'">
							<i>'. __d('installer', 'Recomended value', true ) . ':</i> '. __d('installer', $recomended['recomendation'], true ) .'<br />
							<i>'.__d('installer', 'Actual value', true ).': </i><a href="#" title="'. $recomended['desc'].'">'. __( $recomended['actual'], true ).'</a>
						</li>
					</ul>
				</li>
			';
		}
	}

?>

<?php if(!empty($setupErrors)) { ?>
	<div style="width:50%; float:left;">
		<h2><?php __( 'Server Setup' ); ?></h2>
		<ul>
			<?php echo $setupErrors; ?>
		</ul>
	</div>
<?php } ?>

<?php if(!empty($pathErrors)) { ?>
	<div style="width:50%; float:left;">
		<h2><?php __( 'Paths Writeable' ); ?></h2>
		<ul>
			<?php echo $pathErrors;	?>
		</ul>
	</div>
<?php } ?>


<div style="width:50%; float:left;">
	<h2><?php __( 'Recomended PHP Settings' ); ?></h2>
	<ul>
		<?php
			echo $recommend;
		?>
	</ul>
</div>