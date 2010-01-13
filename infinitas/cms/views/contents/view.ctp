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
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

     if (empty($content)) {
     	echo 'Nothing to see';
     	return true;
     }
?>
<div class="cms-content">
	<div class="heading">
		<h2><?php echo $content['Content']['title']; ?></h2>
		<div class="stats">
			<div class="views">
				<?php echo sprintf( __('Viewed %s times', true), sprintf('<b>%s</b>', $content['Content']['views'] )); ?>
			</div>
			<div class="perma-link">
				<?php
					echo $this->Html->link(
						'permalink',
						array(
							'action' => 'view',
							$content['Content']['id'],
							$content['Content']['slug']
						)
					);
				?>
			</div>
		</div>
	</div>
	<div class="introduction quote">
		<blockquote>
			<span class="bqstart">&#8220;</span>
			<?php echo $content['Content']['introduction']; ?>
			<span class="bqend">&#8221;</span>
		</blockquote>
	</div>
	<div class="body">
		<?php echo $content['Content']['body']; ?>
		<div class="stats">
			<div class="modified">
				<?php echo sprintf( __('Last updated: %s', true), sprintf('<b>%s</b>', $this->Time->niceShort($content['Content']['modified']))); ?>
			</div>
		</div>
	</div>
</div>
<style>
	.quote blockquote{
		line-height:180%;
		margin:45px;
		font-size:130%;
		background-color:#EEEEEE;
	}
	.quote .bqstart,
	.quote .bqend{
		font-family:'Lucida Grande',Verdana,helvetica,sans-serif;
		font-size:700%;
		font-style:normal;
		color:#FF0000;
	}
	.quote .bqstart{
		padding-top:45px;
		float:left;
		height:45px;
		margin-bottom:-50px;
		margin-top:-20px;
	}
	.quote .bqend{
		padding-top:5px;
		float:right;
		height:25px;
		margin-top:0;
	}

	.cms-content big{
		font-size:120%;
	}
	.cms-content ol,
	.cms-content ul {
		list-style:lower-greek outside none;
	}

	.cms-content .heading{
		margin-bottom:20px;
	}

	.cms-content .heading h2{
		font-size:130%;
		color:#1E379C;
		padding-bottom:5px;
	}

	.cms-content .stats{
		border-top:1px dotted #E4E4E4;
	}

	.cms-content .stats div{
		float:left;
		padding-right:20px;
		font-size:80%;
		padding-top:3px;
	}

	.cms-content .introduction{
		font-style: italic;
		color: #8F8F8F;
	}

	.cms-content p{
		margin-bottom:10px;
	}

	.cms-content .body{
		color:#535D6F;
		line-height:110%;
	}
		.cms-content .body .stats div{
			float:right;
		}
</style>