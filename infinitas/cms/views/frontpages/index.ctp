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
<h2>Welcome to <?php echo Configure::read('Website.name'); ?></h2>
<?php
	$i = 0;
	foreach($frontpages as $frontpage ){
		if ($i >= 2) {
			echo '<div style="width:50%; float:left;">';
		}
		$frontpage['Content']['Author']['username'] = $frontpage['Content']['Editor']['username'] = 'Admin';
		?>
			<div class="introduction">
				<h3><?php echo $frontpage['Content']['title']; ?></h3>
				<div class="stats">
					<div><?php echo __('Written by', true), ': ', $frontpage['Content']['Author']['username']; ?></div>
					<div><?php echo $this->Time->niceShort( $frontpage['Content']['created'] ); ?></div>
				</div>
				<div class="body">
				<br/>
					<?php
						echo $frontpage['Content']['introduction'];
					?>
				</div>
				<div class="footer">
					<span><?php echo __('Last updated on', true), ': ', $this->Time->niceShort( $frontpage['Content']['modified'] ); ?></span>
					<span><?php echo '('.$frontpage['Content']['Editor']['username'].')'; ?></span>
					<div class="read-more">
						<?php
							echo $this->Html->link(
								Configure::read('Website.read_more'),
								array(
									'plugin'     => 'cms',
									'controller' => 'contents',
									'action'     => 'view',
									'id'         => $frontpage['Content']['id'],
									'slug'       => $frontpage['Content']['slug'],
									'category'   => $frontpage['Content']['Category']['slug']
								)
							);
						?>
					</div>
				</div>
			</div>
		<?php
		if ($i >= 2) {
			echo '</div>';
		}
		$i++;
	}
?>
<style>
	.cms h3{
		font-size:110%;
		color:#1E379C;
	}

	.cms big{
		font-size:120%;
	}
	.cms ol,
	.cms ul {
		list-style:lower-greek outside none;
	}

	.cms .heading{
		margin-bottom:20px;
	}

	.cms .heading h2{
		font-size:130%;
		color:#1E379C;
		padding-bottom:5px;
	}

	.cms .stats{
		border-top:1px dotted #E4E4E4;
	}

	.cms .stats div{
		float:left;
		padding-right:20px;
		font-size:80%;
		padding-top:3px;
	}

	.cms .introduction{
		font-style: italic;
		color: #8F8F8F;
		margin-top: 35px;
	}

	.cms p{
		margin-bottom:10px;
	}

	.cms .body{
		color:#535D6F;
		line-height:110%;
	}
		.cms .body .stats div{
			float:right;
		}
</style>