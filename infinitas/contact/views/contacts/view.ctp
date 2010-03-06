
<h2><?php echo $contact['Contact']['last_name'], ', ', $contact['Contact']['first_name'];?></h2>
<div class="details">
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Branch'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
				echo $this->Html->link(
					$contact['Branch']['name'],
					array(
						'controller' => 'branches',
						'action' => 'view',
						'slug' => $contact['Branch']['slug'],
						'id' => $contact['Branch']['id']
					)
				);
			?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Position'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $contact['Contact']['position']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $contact['Contact']['phone']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Mobile'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $contact['Contact']['mobile']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Text->autoLinkEmails($contact['Contact']['email']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Skype'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $contact['Contact']['skype']; ?>
			<a href="skype:<?php echo $contact['Contact']['skype']; ?>?chat" title="<?php echo $contact['Contact']['skype']; ?>" alt="<?php echo $contact['Contact']['skype']; ?>">
				<?php
					echo $this->Html->image(
						$this->Image->getRelativePath('social', 'skype'),
						array(
							'width' => '16px'
						)
					);
				?>
			</a>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="image">
	<p class="vcf">
		<?php
			echo $this->Html->link(
				__('Download vCard', true),
				array(
					'action' => 'view',
					'branch' => $contact['Branch']['slug'],
					'slug' => $contact['Contact']['slug'],
					'id' => $contact['Contact']['id'],
					'ext' => 'vcf'
				)
			);
		?>
	</p>
	<?php
		echo $this->Html->image(
			'content/contact/contact/'.$contact['Contact']['image']
		);
	?>
</div>