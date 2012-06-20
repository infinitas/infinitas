
<h2><?php echo $branch['Branch']['name'];?></h2>
<div class="details">
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $branch['Branch']['phone']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Fax'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $branch['Branch']['fax']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Updated'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->niceShort($branch['Branch']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="image">
	<p class="vcf">
		<?php
			echo $this->Html->link(
				__('Download vCard'),
				array(
					'action' => 'view',
					'slug' => $branch['Branch']['slug'],
					'id' => $branch['Branch']['id'],
					'ext' => 'vcf'
				)
			);
		?>
	</p>
	<?php
		echo $this->Html->image(
			'content/contact/branch/'.$branch['Branch']['image']
		);
	?>
</div>
<div class="related">
	<h3><?php echo __('Contacts');?></h3>
	<table cellpadding = "0" cellspacing = "0" width="100%">
		<tr>
			<th><?php echo __('Name'); ?></th>
			<th><?php echo __('Position'); ?></th>
			<th><?php echo __('Phone'); ?></th>
			<th><?php echo __('Mobile'); ?></th>
			<th><?php echo __('Email'); ?></th>
			<th><?php echo __('Contact'); ?></th>
			<th><?php echo __('Updated'); ?></th>
		</tr>
		<?php
			foreach ($branch['Contact'] as $contact){ ?>
				<tr<?php echo $class;?>>
					<td>
						<?php
							echo $this->Html->link(
								htmlspecialchars($contact['last_name'].', '.$contact['first_name']),
								array(
									'controller' => 'contacts',
									'action' => 'view',
									'slug' => $contact['slug'],
									'id' => $contact['id'],
									'branch' => $branch['Branch']['slug']
								)
							);
						?>
					</td>
					<td><?php echo $contact['position'];?></td>
					<td><?php echo $contact['phone'];?></td>
					<td><?php echo $contact['mobile'];?></td>
					<td><?php echo $contact['email'];?></td>
					<td>
						<a href="skype:<?php echo $contact['skype']; ?>?chat" title="<?php echo $contact['skype']; ?>">
							<?php
								echo $this->Html->image(
									$this->Image->getRelativePath('social', 'skype'),
									array(
										'width' => '16px'
									)
								);
							?>
						</a>
						<?php
							echo $this->Html->image(
								$this->Image->getRelativePath('social', 'vcf'),
								array(
									'width' => '16px',
									'url' => array(
										'controller' => 'contacts',
										'action' => 'view',
										'branch' => $branch['Branch']['slug'],
										'slug' => $contact['slug'],
										'id' => $contact['id'],
										'ext' => 'vcf'
									)
								)
							);
						?>
					</td>
					<td><?php echo $this->Time->niceShort($contact['modified']);?></td>
				</tr><?php
			}
		?>
	</table>
	<?php
		echo $this->StaticMap->draw(
			array(
				'location' => $branch['ContactAddress']['address'],
				'size' => array(
					'width' => '600',
					'height' => '200'
				),
				'zoom' => 12,
				'markers' => array(
					array(
						'size' => 'normal',
						'color' => 'ff0000',
						'label' => 'A',
						'location' => $branch['ContactAddress']['address']
					)
				)
			),
			array(
				'class' => 'map'
			)
		);
	?>
</div>