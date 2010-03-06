
<h2><?php echo $branch['Branch']['name'];?></h2>
<div class="details">
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $branch['Branch']['phone']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fax'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $branch['Branch']['fax']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Updated'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->niceShort($branch['Branch']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="image">
	<?php
		echo $this->Html->image(
			'content/contact/branch/'.$branch['Branch']['image']
		);
	?>
</div>
<div class="related">
	<h3><?php __('Contacts');?></h3>
	<table cellpadding = "0" cellspacing = "0" width="100%">
		<tr>
			<th><?php __('Name'); ?></th>
			<th><?php __('Position'); ?></th>
			<th><?php __('Phone'); ?></th>
			<th><?php __('Mobile'); ?></th>
			<th><?php __('Email'); ?></th>
			<th><?php __('Skype'); ?></th>
			<th><?php __('Updated'); ?></th>
		</tr>
		<?php
			foreach ($branch['Contact'] as $contact){ ?>
				<tr<?php echo $class;?>>
					<td>
						<?php
							echo $this->Html->link(
								'a',//$contact['last_name'], ', ', $contact['first_name'],
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
					<td><?php echo $contact['skype'];?></td>
					<td><?php echo $this->Time->niceShort($contact['modified']);?></td>
				</tr><?php
			}
		?>
	</table>
</div>