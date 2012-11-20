<?php
/**
 * view and manage short urls for your site
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Infinitas.ShortUrls.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

	echo $this->Form->create(false, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('url'),
			$this->Paginator->sort('id', __d('short_urls', 'Short Url')) => array(
				'class' => 'large'
			),
			$this->Paginator->sort('views', __d('short_urls', 'Clicks')) => array(
				'class' => 'xlarge'
			),
			$this->Paginator->sort('dead') => array(
				'class' => 'small'
			)
		));

		foreach ($shortUrls as $shortUrl) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($shortUrl); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							String::truncate($shortUrl['ShortUrl']['url'], 100),
							array(
								'action' => 'edit',
								$shortUrl['ShortUrl']['id']
							),
							array('title' => __d('short_urls', 'URL :: %s', $shortUrl['ShortUrl']['url']))
						);
					?>&nbsp;
				</td>
				<td>
					<?php
						$short = $this->Event->trigger('ShortUrls.getShortUrl', array(
							'type' => 'preview',
							'url' => $shortUrl['ShortUrl']['url']
						));
						$short = Router::url(current($short['getShortUrl']));
						echo $this->Html->link($short, $short, array(
							'target' => '_blank',
							'title' => __d('short_url', 'Preview url')
						));
					?>&nbsp;
				</td>
				<td><?php echo $shortUrl['ShortUrl']['views']; ?></td>
				<td><?php echo $this->Infinitas->status($shortUrl['ShortUrl']['dead']); ?></td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');